<?php

require_once("includes/phpdefault.php");

// Check if the time to vote is correct
if(date('D') == VOTE_DAY) {
  if (date('Hi') < VOTE_HOUR) {
    header('location:home.php');
  }
}
// Check if user has already voted
if ($_SESSION["Voted"] == 1 ) {
  header('location:home.php');
}

$stmt = $pdo->prepare('SELECT * FROM table_Candidates');
$stmt->execute();
$candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST["formSubmitVote"])){
  // Check if user has voting records
  $stmt = $pdo->prepare('SELECT * FROM table_Scores WHERE UserId = ?');
  $stmt->execute([ $_SESSION["Id"] ]);
  $hasvoted = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // If user has no voting records -> add them
  if(empty($hasvoted)){
    // Add 0 points to every candidate as new account's score
    $stmt = $pdo->prepare('INSERT INTO table_Scores (UserId, CandidateId, Score) VALUES (?,?,?)');
    foreach ($candidates as $candidate) {
      $stmt->execute([ $_SESSION["Id"], $candidate["Id"], 0 ]);
    }
  }

  // Prepare the statement for updating scores
  $stmt = $pdo->prepare('UPDATE table_Scores SET Score = Score + ? WHERE UserId = ? AND CandidateId = ?');

  foreach($candidates as $candidate){
    // Get the score from form on this candidate
    $score = $_POST["candidate_" . $candidate["Id"]];

    // Check if user matches criteria for All-In award
    if ($score == AWARD_ALL_IN_AMOUNT) {
      giveAward($_SESSION["Id"], AWARD_ALL_IN);
    }

    // Add score
    $stmt->execute([ $score, $_SESSION["Id"], $candidate["Id"] ]);
  }

  // Specify that this user has voted
  $stmt = $pdo->prepare('UPDATE table_Users SET Voted = 1 WHERE Id = ?');
  $stmt->execute([ $_SESSION["Id"] ]);
  $set_voted = $stmt->fetch(PDO::FETCH_ASSOC);
  $_SESSION["Voted"] = 1;
  
  // AWARD SECTION
    // Check if conditions for TUNNELVISIE match
    $stmt = $pdo->prepare('SELECT * FROM table_Scores WHERE UserId = ? AND Score > ?');
    $stmt->execute([ $_SESSION["Id"], AWARD_TUNNELVISIE_AMOUNT ]);
    $score_over_limit = $stmt->fetch(PDO::FETCH_ASSOC);
    // Give TUNNELVISIE award 
    if(!empty($score_over_limit)){
      giveAward($_SESSION["Id"], AWARD_TUNNELVISIE);
    }
    // give DEELNEMER award to user
    giveAward($_SESSION["Id"], AWARD_DEELNEMER);
  header('location:home.php');

}

?>

<?php include "includes/header.php"; ?>

  <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>

  <h1>WIE IS DE <span>MOL</span> ?</h1>
  <h2><span>Swipe</span> tussen de kandidaten en <span>stem</span>.</h2>

  <form id="deMolForm" method="POST" action="">

    <!-- form carousel -->
    <div class="slider-for">
      <?php foreach($candidates as $candidate): ?>
        <div class="candidateInfo">
          <p><?=$candidate['Name']?> 
          <br><?=$candidate['Age']?> <span style="font-weight: 800">//</span> <?=$candidate['Job']?></p>
          <p class="userPointsLeft"><?=$account['Score']?></p>
          <p id="pointsCandidate<?=$candidate['Id']?>">0</p>
          <input type="range" min="0" max="<?=$account["Score"]?>" step="1" value="0" class="demolslider" id="slider<?=$candidate['Id']?>">
        </div>
      <?php endforeach; ?>
    </div>

    <div class="arrow-down"></div>
    <!-- select carousel -->
    <div class="slider-nav">
      <?php foreach($candidates as $candidate): ?>
        <div class='candidate' id='candidate<?=$candidate['Id']?>'>
          <img src="img/kandidaten/<?=$candidate['Name']?>.jpg" alt="foto van <?=$candidate['Name']?>" />
        </div>
      <?php endforeach; ?>
    </div>

    <div class="submitDiv">
      <input style="margin-bottom: 20%;" form="deMolForm" name="formSubmitVote" id="formSubmitVote" class="formSubmitBtn" type="submit" value="Inzenden" />
    </div>

  </form>

</div>
</div>

  <!-- JavaScript -->
  <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="slick/slick.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>

  <!-- Slick Settings -->
  <script type="text/javascript">
    $(document).ready(function(){
      // Slick settings for voting system
      $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: false,
        speed: 0,
        asNavFor: '.slider-nav',
        swipe: false,
        draggable: false
      });
      // Slick settings for candidate list
      $('.slider-nav').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: false,
        arrows: false,
        centerMode: true,
        centerPadding: '5%',
        focusOnSelect: true,
        draggable: true,
        mobileFirst: true,
      });
      <?php foreach($candidates as $candidate): ?>
      // If slider value is changed ->
      $("#slider<?=$candidate['Id']?>").on("input", function(){
        // Get the current total voted points
        var totalVoted = 0;
        <?php foreach($candidates as $candidateVoted): ?>
        totalVoted += parseInt($('#slider<?=$candidateVoted['Id']?>').val());
        <?php endforeach; ?>
        
        // Check for max available points
        if(totalVoted > <?=$account["Score"]?>){
          // Get the current selected value
          let selectedValue = $('#slider<?=$candidate['Id']?>').val();
          // Calculate the new value correctly with set limit
          let newSelectedValue = selectedValue - (totalVoted - <?=$account["Score"]?>);
          // Change display values
          $('#slider<?=$candidate['Id']?>').val(newSelectedValue);
          $("#pointsCandidate<?=$candidate['Id']?>").text(newSelectedValue);
          // Correct the total points left to 0
          $(".userPointsLeft").text(0);
        }else{
          // Change the total points display
          $(".userPointsLeft").text(<?=$account["Score"]?> - totalVoted);
        }

        // Change the displayed value
        $("#pointsCandidate<?=$candidate['Id']?>").text($('#slider<?=$candidate['Id']?>').val());
      });
      <?php endforeach; ?>
    });
  </script>

</body>
</html>
