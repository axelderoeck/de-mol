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
</head>
<body>
  <?php include "includes/navigation.php"; ?>

<div id="main">

  <h1>Mijn Profiel</h1>
  <hr>
  <h3>Awards</h3>
  <div class="awards">
    <div class="info">
      <img src="img/assets/place1.png" alt="">
      <p>Winnaar</p>
    </div>
    <div class="info">
      <img src="img/assets/place2.png" alt="">
      <p>Tunnelvisie</p>
    </div>
    <div class="info">
      <img src="img/assets/place3.png" alt="">
      <p>Jij weet niets</p>
    </div>
  </div>
  <hr>

  <h3>Account Acties <button onclick="collapse('collapsible-content','collapsible');" type="button" id="collapsible"><i class="fas fa-chevron-down"></i></button></h3>
  <div id="collapsible-content">
    <ul>
      <li><i class="fas fa-edit"></i><a href="#"> naam wijzigen</a></li>
      <li><i class="fas fa-edit"></i><a href="changepassword.php"> wachtwoord wijzigen</a></li>
      <li class="delete warning"><i class="fas fa-trash-alt"></i><a href="#"> verwijder account</a></li>
    </ul>
  </div>


</div>

  <!-- JavaScript -->
  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
