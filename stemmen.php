<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

include "includes/settings.php";

$id = $_SESSION["Id"];

$checkIfUserHasAward = "SELECT *
FROM table_UserAwards
WHERE UserId = '$id' AND AwardId = $award_deelnemer";

$giveUserAward = "INSERT INTO table_UserAwards (UserId, AwardId)
VALUES ($id, $award_deelnemer)";

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}
// if(date('D') == "$stemmen_dag" && date('Hi') < "$stemmen_uur") {
//   header('location:home.php');
// }
if ($_SESSION["Voted"] == 1 ) {
  header('location:home.php');
}
//|| date('D') == "$stemmen_dag" && date('Hi') < "$stemmen_uur"

if (isset($_POST["formSubmitVote"])){

  $naam = $_SESSION["Naam"];
  $id = $_SESSION["Id"];

  for ($i=1; $i <= $aantal_kandidaten; $i++) {
    $score = $_POST["person$i"];

    $query = "UPDATE `table_Scores`
    SET `Score` = `Score` + $score
    WHERE `UserId` = '$id' AND `Identifier` = 'person$i'";

    mysqli_query($dbconn, $query);
  }

  $votedQuery = "UPDATE `table_Users`
  SET `Voted` = 1
  WHERE `Id` = '$id'";
  mysqli_query($dbconn, $votedQuery);
  $_SESSION["Voted"] = 1;

  $queryCheckIfUserHasAward = $dbconn->query("SELECT *
  FROM table_UserAwards
  WHERE UserId = '$id' AND AwardId = $award_deelnemerId");

  if($queryCheckIfUserHasAward->num_rows == 0) {
    mysqli_query($dbconn, $giveUserAward);
  }

  header('location:home.php');

}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <link rel="stylesheet" href="css/stemmen<?php echo "V" . $styleversion; ?>.css">
  <?php include "includes/headinfo.php"; ?>
  <script>
    window.addEventListener('load', function() {
      //PHP waardes in array steken
      let deelnemers = [
        { id: 0, identifier: 'person0', naam: 'dummy', leeftijd: 0, job: 'placeholder', visibility: 'hidden', direction: 'Right' },
      <?php
        $sql = "SELECT * FROM table_Kandidaten";
        if($result = mysqli_query($dbconn, $sql)){
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result)){

                  ?>
                    { id: <?php echo $row['Id'] ?> , identifier: <?php echo "'" . $row['Identifier'] . "'"; ?>, naam: <?php echo "'" . $row['Naam'] . "'"; ?>, leeftijd: <?php echo $row['Leeftijd']; ?>, job: <?php echo "'" . $row['Job'] . "'"; ?>, visibility: <?php echo "'" . $row['Visibility'] . "'"; ?> },
                  <?php

                }
                // Free result set
                mysqli_free_result($result);
            } else{
                echo "No records matching your query were found.";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }

      ?>
        { id: <?php echo $aantal_kandidaten+1; ?>, identifier: 'person<?php echo $aantal_kandidaten+1 . "'"; ?>, naam: 'dummy', leeftijd: 0, job: 'placeholder', visibility: 'hidden', direction: 'Left' }
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


<?php mysqli_close($dbconn); ?>
</body>
</html>
