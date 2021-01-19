<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <script>
    window.addEventListener('load', function() {
      <?php

        if(date('D') == 'Sun') {
          ?>stemKnop("uit");<?php
        } else {
          if ($_SESSION["Voted"] == 0) {
            ?> stemKnop("aan"); <?php
          } elseif ($_SESSION["Voted"] == 1) {
            ?> stemKnop("uit"); <?php
          }
        }
        if ($_SESSION["Id"] == 7) {
          ?> stemKnop("aan") <?php
        }

      ?>

    })
  </script>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

  <div class="welcomeImage">
    <img src="img/assets/header.jpg" style="width: 100%; visibility: hidden;" alt="" />
  </div>

  <div class="welcomeBox">
    <span>Dag <?php echo $_SESSION["Naam"]; ?></span>
    <br>
    <span style="font-size: 30px;">Kan jij mij <b class="colored">ontmaskeren</b> ?</span>
  </div>

  <div class="infoDiv">
    <p>Dag vrienden</p>
    <p id="stemTekst"></p>
  </div>

  <div class="submitDiv">
    <button onclick="location.href = 'stemmen.php';" id="stemKnop" class="formSubmitBtn" type="submit">Stemmen</button>
  </div>

  <!-- JavaScript -->

  <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
