<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

$id = $_SESSION["Id"];

include "includes/account-actions/changename.php";
include "includes/account-actions/changepassword.php";
include "includes/account-actions/deleteaccount.php";

$selectAwards = "SELECT AwardId, Naam, Beschrijving, Editie
FROM table_UserAwards
LEFT JOIN table_Awards
ON table_UserAwards.AwardId = table_Awards.Id
WHERE table_UserAwards.UserId = '$id'
";

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <script>
  window.addEventListener('load', function() {
    <?php
      $pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache');
      if($pageRefreshed == 1){
        echo "showNotification('$foutmelding','$meldingSoort');"; //message + color style
      }
    ?>
  })
  </script>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

  <div id="informationPopup">
    <!-- Dynamische info -->
  </div>

<div id="main">

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
  <p class="userInfo">Gebruikersnaam: <span><?php echo $_SESSION["Gebruikersnaam"]; ?></span></p>
  <p class="userInfo">Naam: <span><?php echo $_SESSION["Naam"]; ?></span></p>
  <hr>
  <h3>Awards</h3>
  <div class="awards">
    <?php

    if($result = mysqli_query($dbconn, $selectAwards)){
      if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
          ?>
          <div class="info">
            <img src="img/awards/<?php echo $row['AwardId']; ?>.png" alt="award foto van <?php echo $row['Naam']; ?>">
            <p><?php echo $row['Naam']; ?><br><span><?php echo $row['Editie']; ?></span></p>
          </div>
          <?php
        }
      }else{
        ?>
        <p style="text-align: center !important;">Je hebt nog geen <span>awards</span>.</p>
        <?php
      }
    }

    ?>
  </div>
  <hr>

  <h3>Account Acties <button onclick="collapse('collapsible-content','collapsible');" type="button" id="collapsible"><i class="fas fa-chevron-down"></i></button></h3>
  <div id="collapsible-content">
    <ul>
      <li><i class="fas fa-edit"></i><a href="javascript:showPopup('popUpChangeName','show');"> gebruikersnaam wijzigen</a></li>
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
