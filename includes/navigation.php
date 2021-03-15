<?php
$enddate = new DateTime($seizoen_eind);
$now = new DateTime();
?>

<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fas fa-times closeIcon"></i></a>
  <a href="home.php"><i class="fas fa-home"></i>Home</a>
  <a href="jouwmol.php"><i class="fas fa-fingerprint"></i>Molboek</a>
  <?php if ($enddate < $now || $_SESSION["Admin"] == 1) { ?>
    <a href="ranglijst.php"><i class="fas fa-medal"></i>Scores</a>
  <?php } ?>
  <a href="uitleg.php"><i class="fas fa-question-circle"></i>Uitleg</a>
  <a href="profiel.php"><i class="fas fa-user"></i>Profiel</a>
  <a href="deelnemers.php"><i style="transform: translateX(-5px);" class="fas fa-users"></i>Mollenjagers</a>
  <?php if ($_SESSION["Admin"] == 1) { ?>
    <a href="adminpanel.php"><i class="fas fa-hammer"></i>Admin</a>
  <?php } ?>
  <a href="index.php?code=9"><i class="fas fa-sign-out-alt"></i>Uitloggen</a>
  <img src="img/assets/molLogo.png" alt="logo de mol">
</div>

<span class="navButton" onclick="openNav()"><i class="fas fa-stream"></i></span>
