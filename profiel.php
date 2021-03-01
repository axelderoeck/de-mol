<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

include "includes/account-actions/changename.php";
include "includes/account-actions/changepassword.php";
include "includes/account-actions/deleteaccount.php";

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

<div id="main">


<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>Some text in the Modal..</p>
  </div>

</div>

  <div id="popUpChangePassword" class="popupStyle translucent">
    <div class="box">
      <a class="closeLink" href="javascript:showPopup('popUpChangePassword','hide');">&times;</a>
      <form name="formChangePassword" action="" method="post">
          <input placeholder="Oud wachtwoord" name="oudWachtwoord" id="oudWachtwoord" type="password" required>
          <br>
          <input placeholder="Wachtwoord" name="Wachtwoord" id="Wachtwoord" type="password" required>
          <br>
          <input placeholder="Wachtwoord" name="confirmWachtwoord" id="confirmWachtwoord" type="password" required>
          <br>
          <input type="submit" name="changePassword" id="changePassword" value="Verander">
      </form>
    </div>
  </div>

  <div id="popUpChangeName" class="popupStyle translucent">
    <div class="box">
      <a class="closeLink" href="javascript:showPopup('popUpChangeName','hide');">&times;</a>
      <form name="formChangeName" action="" method="post">
          <input placeholder="Nieuwe Naam" name="nieuweNaam" id="nieuweNaam" type="text" required>
          <br>
          <input type="submit" name="changeName" id="changeName" value="Verander">
      </form>
    </div>
  </div>

  <div id="popUpDeleteAccount" class="popupStyle translucent">
    <div class="box">
      <a class="closeLink" href="javascript:showPopup('popUpDeleteAccount','hide');">&times;</a>
      <p>Bent u zeker dat u uw account wilt verwijderen?</p>
      <form name="formDeleteAccount" action="" method="post">
          <input type="submit" name="deleteAccount" id="deleteAccount" value="Verwijder">
      </form>
    </div>
  </div>

  <h1>Mijn Profiel</h1>
  <hr>
  <h3>Awards</h3>
  <div class="awards">
    <div class="info">
      <img src="img/awards/place1.png" alt="">
      <p>Winnaar</p>
    </div>
    <div class="info">
      <img src="img/awards/place2.png" alt="">
      <p>Tunnelvisie</p>
    </div>
    <div class="info">
      <img src="img/awards/place3.png" alt="">
      <p>Jij weet niets</p>
    </div>
  </div>
  <hr>

  <h3>Account Acties <button onclick="collapse('collapsible-content','collapsible');" type="button" id="collapsible"><i class="fas fa-chevron-down"></i></button></h3>
  <div id="collapsible-content">
    <ul>
      <li><i class="fas fa-edit"></i><a href="javascript:showPopup('popUpChangeName','show');"> naam wijzigen</a></li>
      <li><i class="fas fa-edit"></i><a href="javascript:showPopup('popUpChangePassword','show');"> wachtwoord wijzigen</a></li>
      <li class="delete warning"><i class="fas fa-trash-alt"></i><a href="javascript:showPopup('popUpDeleteAccount','show');"> verwijder account</a></li>
    </ul>
  </div>

  </div>

  <!-- JavaScript -->
  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
