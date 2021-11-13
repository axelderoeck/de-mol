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

<!DOCTYPE html>
<html lang="nl">
<head>
  <link rel="stylesheet" href="css/stemmen<?php echo "V" . STYLE_VERSION; ?>.css">
  <?php include "includes/headinfo.php"; ?>
  <script>
    window.addEventListener('load', function() {
      //PHP waardes in array steken
      let candidates = [
        { id: 0, 
          name: 'dummy', 
          age: 0, 
          job: 'placeholder', 
          status: 'hidden', 
          direction: 'Right' },

        <?php $i = 1; foreach($candidates as $candidate): ?>
          { id: <?= $candidate['Id'] ?>,  
            name: <?= "'" . $candidate['Name'] . "'"; ?>, 
            age: <?= $candidate['Age']; ?>, 
            job: <?= "'" . $candidate['Job'] . "'"; ?>, 
            status: <?= "'" . $candidate['Status'] . "'"; ?> 
          },
        <?php $i++; endforeach; ?>

        { id: <?= $i+1; ?>, name: 'dummy', age: 0, job: 'placeholder', status: 'hidden', direction: 'Left' }
      ]

      //Array waardes in een div card steken
      var html = "";
      candidates.forEach(candidate => {
        if (candidate.status == "hidden") {
          if (candidate.direction == "Left") {
            var imgSrc = "src='img/assets/dummyLeft.jpg'";
          } else if (candidate.direction == "Right") {
            var imgSrc = "src='img/assets/dummyRight.jpg'";
          }
          html += `<div class='swiper-slide' id='${candidate.id}'>
                  <div style="display: none;">
                    <input form="deMolForm" type="text" class="btnValue" name="${candidate.id}" id="${candidate.id}" value="0" readonly/>
                  </div>
                  <img class="cardImage" ${imgSrc} alt="foto van ${candidate.name}" />
            </div>`;
        }else if(candidate.status == 0) {
          html += `<div class='swiper-slide' id='${candidate.id}'>
                  <div class="cardNameBG">
                  <p class="cardName">${candidate.name}</p>
                  </div>
                  <p class="cardInfo">${candidate.age} <span style="color: #53adb5; font-weight: 800">//</span> ${candidate.job}</p>

                  <div style="display: none;">
                    <input form="deMolForm" type="text" class="btnValue" name="${candidate.id}" id="${candidate.id}" value="0" readonly/>
                  </div>
                  <div class="disabledPerson"><img class="cardImage" src="img/kandidaten/${candidate.name}.jpg" alt="foto van ${candidate.name}" /></div>
            </div>`;
        } else {
          html += `<div class='swiper-slide' id='${candidate.id}'>
                  <div class="cardNameBG">
                  <p class="cardName">${candidate.name}</p>
                  </div>
                  <p class="cardInfo">${candidate.age} <span style="color: #53adb5; font-weight: 800">//</span> ${candidate.job}</p>

                  <div class="cardBottom">
                    <input form="deMolForm" type="hidden" name="${candidate.id}_id" id="${candidate.id}_id" value="${candidate.id}" />
                    <img class="cardLogo" src="img/assets/molLogo.png" alt="mol logo" />
                    <p>Inzet: <input form="deMolForm" type="text" class="btnValue" name="candidate_${candidate.id}" id="candidate_${candidate.id}" value="0" readonly/></p>
                    <button style="background-color: rgba(0,0,0,0); border: 0;" type="button" onclick="decrementValue('candidate_${candidate.id}')"><img class="btnValueChange" src="img/assets/ButtonMin.png"/></button>
                    <button style="background-color: rgba(0,0,0,0); border: 0;" type="button" onclick="incrementValue('candidate_${candidate.id}')"><img class="btnValueChange" src="img/assets/ButtonPlus.png"/></button>
                  </div>
                  <img class="cardImage" src="img/kandidaten/${candidate.name}.jpg" alt="foto van ${candidate.name}" />
            </div>`;
        }
      });
      document.getElementById("carousel").innerHTML += html;

      //Carousel aanmaken
      var swiper = new Swiper('.swiper-container', {
        slidesPerView: 1,
          loop: false,
          spaceBetween: 0,
          initialSlide: 1,
          pagination: {
            el: '.swiper-pagination',
          },
      });

      submitKnop("aan");
      
      //Punten Bereken functies
      window.isOverValue = function(value)
      {
        var total = 0;
        candidates.forEach(candidate => {
            total += parseInt(document.getElementById('candidate_' + candidate.id).value, 10);
        });
        if(total < value){
          return false;
        }
        return true;
      }

    }) //Einde Event Listener

      function incrementValue(id){
        var value = parseInt(document.getElementById(id).value, 10);
        value = isNaN(value) ? 0 : value;
        /*
        if (isOverValue(9) == true) {
          submitKnop("aan");
        }
        if (isOverValue(10) == false){
          value++;
        }else {
          alert("Je kan niet meer dan 10 punten inzetten.")
        }
        */
        value++;
        document.getElementById(id).defaultValue = value;
      }

      function decrementValue(id){
        var value = parseInt(document.getElementById(id).value, 10);
        value = isNaN(value) ? 0 : value;
        /*
        if (isOverValue(9) == true) {
          submitKnop("uit");
        }
        if(value > 0) {
          value--;
        }
        */
        value--;
        document.getElementById(id).defaultValue = value;
      }

  </script>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

<div class="votePage" id="main">
 <div class="respContainer">
  <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>

  <h1>WIE IS DE <span>MOL</span> ?</h1>
  <h2><span>Swipe</span> tussen de kandidaten en <span>stem</span>.</h2>

  <form id="deMolForm" method="POST" action="">

  <div class="swiper-container">
    <div id="carousel" class="swiper-wrapper">

      <!-- dynamische items -->

    </div>
  </div>

  <div class="submitDiv">
    <input style="margin-bottom: 20%;" form="deMolForm" name="formSubmitVote" id="formSubmitVote" class="formSubmitBtn" type="submit" value="Inzenden" />
  </div>
  </form>
  </div>
</div>

  <!-- JavaScript -->
  <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>
