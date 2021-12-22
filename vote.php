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

function submitVote($id, $candidates, $maxScore){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Calculate the total points voted
  $totalScore = 0;
  foreach($candidates as $candidate){
    // Get the score from form on this candidate
    $totalScore += $_POST["slider" . $candidate["Id"]];
  }

  // Check if the score is correct
  if($totalScore > $maxScore){
    $type = "warning";
    $message = "Er zijn meer punten ingezet dan je hebt.";
  }else if($totalScore == 0){
    $type = "warning";
    $message = "Je hebt geen punten ingezet.";
  }else{
    // Score is ok -> execute vote

    // Prepare the statement for inserting scores
    $stmt = $pdo->prepare('INSERT INTO table_Scores (UserId, CandidateId, Score) VALUES (?,?,?)');

    // Insert each candidate score
    foreach($candidates as $candidate){
      // Get the score from form on this candidate
      $score = $_POST["slider" . $candidate["Id"]];
      if($score > 0){
        // Add score
        $stmt->execute([ $id, $candidate["Id"], $score ]);
      }
    }

    // Calculate new score
    $newScore = $maxScore - $totalScore;

    // Specify that this user has voted and seenresults is 0 and subtract score
    $stmt = $pdo->prepare('UPDATE table_Users SET Voted = 1, Score = ? WHERE Id = ?');
    $stmt->execute([ $newScore, $id ]);
    $_SESSION["Voted"] = 1;

    // AWARD SECTION TODO
    /*
    // Check if conditions for TUNNELVISIE match
    $stmt = $pdo->prepare('SELECT * FROM table_Scores WHERE UserId = ? AND Score > ?');
    $stmt->execute([ $_SESSION["Id"], AWARD_TUNNELVISIE_AMOUNT ]);
    $score_over_limit = $stmt->fetch(PDO::FETCH_ASSOC);
    // Give TUNNELVISIE award 
    if(!empty($score_over_limit)){
      giveAward($_SESSION["Id"], AWARD_TUNNELVISIE);
    }
    */

    // give DEELNEMER award to user
    giveAward($id, AWARD_DEELNEMER);

    $type = "success";
    $message = "Bedankt om te stemmen.";

    header('location:home.php');
  }

  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

if (isset($_POST["formSubmitVote"])){
  // Execute vote
  $notification = submitVote($_SESSION["Id"], $candidates, $account["Score"]);
}

?>

<?php include "includes/header.php"; ?>

  <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>

  <h1>WIE IS DE <span>MOL</span> ?</h1>
  <h2><span>Swipe</span> tussen de kandidaten en <span>stem</span>.</h2>
  
    <form id="deMolForm" method="POST" action=""></form>
    <!-- form carousel -->
    <div class="slider-for">
      <?php foreach($candidates as $candidate): ?>
        <div class="candidateSlide">
          <div class="scoreLeft">
            <p class="userPointsLeft"><?=$account['Score']?></p>
          </div>
          <div class="candidateInfo">
            <p><span><?=$candidate['Name']?></span>
            <br><?=$candidate['Age']?> <span style="font-weight: 800">//</span> <?=$candidate['Job']?></p>
            <p class="candidatePoints pointsCandidate<?=$candidate['Id']?>">0</p>
          </div>
          <input <?php if($candidate["Status"] == 0){echo "disabled";} ?> form="deMolForm" type="range" min="0" max="<?=$account["Score"]?>" step="1" value="0" class="demolslider" name="slider<?=$candidate['Id']?>" id="slider<?=$candidate['Id']?>" oninput="checkScore('<?=$candidate['Id']?>')">
        </div>
      <?php endforeach; ?>
    </div>

    <div class="arrow-down"></div>
    <!-- select carousel -->
    <div class="slider-nav">
      <?php foreach($candidates as $candidate): ?>
        <div class='candidate' id='candidate<?=$candidate['Id']?>'>
          <?php if($candidate["Status"] == 0): ?>
            <img class="red" src="img/assets/demol_logo_geen_tekst_rood.png" alt="logo de mol rood">
          <?php else: ?>
            <p class="candidateSliderPoints pointsCandidate<?=$candidate['Id']?>">0</p>
          <?php endif; ?>
          <img src="img/kandidaten/<?=$candidate['Name']?>.jpg" alt="foto van <?=$candidate['Name']?>" />
        </div>
      <?php endforeach; ?>
    </div>

    <div class="submitDiv">
      <input style="margin-bottom: 20%;" form="deMolForm" name="formSubmitVote" id="formSubmitVote" class="formSubmitBtn" type="submit" value="Inzenden" />
    </div>

<?php include "includes/footer.php"; ?>
