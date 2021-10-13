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

$stmt = $pdo->prepare('SELECT * FROM table_Kandidaten');
$stmt->execute();
$candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST["formSubmitVote"])){
  for ($i=1; $i <= CANDIDATES_AMOUNT; $i++) {
    $score = $_POST["person$i"];

    // Check if user matches criteria for All-In award
    if ($score == AWARD_ALL_IN_AMOUNT) {
      giveAward($_SESSION["Id"], AWARD_ALL_IN);
    }

    // Update the scores
    $stmt = $pdo->prepare('UPDATE table_Scores
    SET Score = Score + ?
    WHERE UserId = ? AND Identifier = ?');
    $stmt->execute([ $score, $_SESSION["Id"], 'person'.$i ]);
    $update_scores = $stmt->fetch(PDO::FETCH_ASSOC);
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
      let deelnemers = [
        { id: 0, 
          identifier: 'person0', 
          naam: 'dummy', 
          leeftijd: 0, 
          job: 'placeholder', 
          visibility: 'hidden', 
          direction: 'Right' },

        <?php $i = 0; foreach($candidates as $candidate): ?>
          { id: <?php echo $candidate['Id'] ?>, 
            identifier: <?= "'" . $candidate['Identifier'] . "'"; ?>, 
            naam: <?= "'" . $candidate['Naam'] . "'"; ?>, 
            leeftijd: <?= $candidate['Leeftijd']; ?>, 
            job: <?= "'" . $candidate['Job'] . "'"; ?>, 
            visibility: <?= "'" . $candidate['Visibility'] . "'"; ?> 
          },
        <?php $i++; endforeach; ?>

        { id: <?= $i+1; ?>, identifier: 'person<?= $i+1 . "'"; ?>, naam: 'dummy', leeftijd: 0, job: 'placeholder', visibility: 'hidden', direction: 'Left' }
      ]

      //Array waardes in een div card steken
      var html = "";
      deelnemers.forEach(deelnemer => {
        if (deelnemer.visibility == 'hidden') {
          if (deelnemer.direction == "Left") {
            var imgSrc = "src='img/assets/dummyLeft.jpg'";
          } else if (deelnemer.direction == "Right") {
            var imgSrc = "src='img/assets/dummyRight.jpg'";
          }
          html += `<div class='swiper-slide' id='${deelnemer.id}'>
                  <div style="display: none;">
                    <input form="deMolForm" type="text" class="btnValue" name="${deelnemer.identifier}" id="${deelnemer.identifier}" value="0" readonly/>
                  </div>
                  <img class="cardImage" ${imgSrc} alt="foto van ${deelnemer.naam}" />
            </div>`;
        }else if(deelnemer.visibility == 'out') {
          html += `<div class='swiper-slide' id='${deelnemer.id}'>
                  <div class="cardNameBG">
                  <p class="cardName">${deelnemer.naam}</p>
                  </div>
                  <p class="cardInfo">${deelnemer.leeftijd} <span style="color: #53adb5; font-weight: 800">//</span> ${deelnemer.job}</p>

                  <div style="display: none;">
                    <input form="deMolForm" type="text" class="btnValue" name="${deelnemer.identifier}" id="${deelnemer.identifier}" value="0" readonly/>
                  </div>
                  <div class="disabledPerson"><img class="cardImage" src="img/kandidaten/${deelnemer.naam}.jpg" alt="foto van ${deelnemer.naam}" /></div>
            </div>`;
        } else {
          html += `<div class='swiper-slide' id='${deelnemer.id}'>
                  <div class="cardNameBG">
                  <p class="cardName">${deelnemer.naam}</p>
                  </div>
                  <p class="cardInfo">${deelnemer.leeftijd} <span style="color: #53adb5; font-weight: 800">//</span> ${deelnemer.job}</p>

                  <div class="cardBottom">
                    <input form="deMolForm" type="hidden" name="${deelnemer.identifier}_id" id="${deelnemer.identifier}_id" value="${deelnemer.identifier}" />
                    <img class="cardLogo" src="img/assets/molLogo.png" alt="mol logo" />
                    <p>Inzet: <input form="deMolForm" type="text" class="btnValue" name="${deelnemer.identifier}" id="${deelnemer.identifier}" value="0" readonly/></p>
                    <button style="background-color: rgba(0,0,0,0); border: 0;" type="button" onclick="decrementValue('${deelnemer.identifier}')"><img class="btnValueChange" src="img/assets/ButtonMin.png"/></button>
                    <button style="background-color: rgba(0,0,0,0); border: 0;" type="button" onclick="incrementValue('${deelnemer.identifier}')"><img class="btnValueChange" src="img/assets/ButtonPlus.png"/></button>
                  </div>
                  <img class="cardImage" src="img/kandidaten/${deelnemer.naam}.jpg" alt="foto van ${deelnemer.naam}" />
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

      submitKnop("uit");

      //Punten Bereken functies
      window.isOverValue = function(value)
      {
        var total = 0;
        deelnemers.forEach(deelnemer => {
            total += parseInt(document.getElementById(deelnemer.identifier).value, 10);
        });
        if( total < value ){
          return false;
        }
        return true;
      }

    }) //Einde Event Listener

      function incrementValue(id)
      {
        var value = parseInt(document.getElementById(id).value, 10);
        value = isNaN(value) ? 0 : value;
        if (isOverValue(9) == true) {
          submitKnop("aan");
        }
        if (isOverValue(10) == false){
          value++;
        }else {
          alert("Je kan niet meer dan 10 punten inzetten.")
        }
        document.getElementById(id).defaultValue = value;
      }

      function decrementValue(id)
      {
        var value = parseInt(document.getElementById(id).value, 10);
        value = isNaN(value) ? 0 : value;
        if (isOverValue(9) == true) {
          submitKnop("uit");
        }
        if(value > 0) {
          value--;
        }
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
