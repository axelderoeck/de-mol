<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

include "includes/settings.php";
include "includes/functions.php";

$id = $_SESSION["Id"];

$user = $_GET["user"];

if ($user == $id) {
  header('location:home.php');
}

if ($user == null) {
  include "includes/account-actions/changename.php";
  include "includes/account-actions/changepassword.php";
  include "includes/account-actions/deleteaccount.php";
  include "includes/account-actions/changefirstname.php";

  $selectAwards = "SELECT AwardId, Naam, Beschrijving, Editie
  FROM table_UserAwards
  LEFT JOIN table_Awards
  ON table_UserAwards.AwardId = table_Awards.Id
  WHERE table_UserAwards.UserId = '$id'
  ";

  $geenAwardsMelding = "Je hebt nog geen <span>awards</span>.";
}else{
  $selectAwards = "SELECT AwardId, Naam, Beschrijving, Editie
  FROM table_UserAwards
  LEFT JOIN table_Awards
  ON table_UserAwards.AwardId = table_Awards.Id
  WHERE table_UserAwards.UserId = '$user'
  ";

  $selectUserName = "SELECT Gebruikersnaam, Naam
  FROM table_Users
  WHERE Id = '$user'
  ";

  //statement aanmaken
  if ($stmtSelectUserName = mysqli_prepare($dbconn, $selectUserName)){
      //query uitvoeren
      mysqli_stmt_execute($stmtSelectUserName);
      //resultaat binden aan lokale variabelen
      mysqli_stmt_bind_result($stmtSelectUserName, $profiel_gebruikersnaam, $profiel_naam);
      //resultaten opslaan
      mysqli_stmt_store_result($stmtSelectUserName);
  }

  mysqli_stmt_fetch($stmtSelectUserName);

  $geenAwardsMelding = "Deze gebruiker heeft nog geen <span>awards</span>.";

  if (isset($_POST["deleteFromFollowing"])){
    $dbconn->query("DELETE FROM table_Followers
      WHERE UserId = '$id' AND UserIsFollowingId = '$user';
      ");

    // AWARD_GILLES SECTION
    // get how many people this person is following
    $queryIf10Followed = $dbconn->query("SELECT COUNT(UserId) AS 'Count'
    FROM table_Followers
    WHERE UserId = '$id'
    GROUP BY UserId");
    $data = $queryIf10Followed->fetch_array();
    // enter amount followed in a variable
    $amountFollowed = ($data['Count']);

    // IF user follows less than 10 -> delete award
    if ($amountFollowed == 10) {
      deleteAward($id, $award_gilles, $dbconn);
    }

    header('location:deelnemers.php');
  }

}

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
  <div class="respContainer">

  <?php if ($user != null) { ?>
  <a href="deelnemers.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
  <?php } ?>

  <?php if ($user == null) { ?>

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

  <div id="popUpChangeFirstName" class="popupStyle translucent">
    <div class="box">
      <a class="closeLink" href="javascript:showPopup('popUpChangeFirstName','hide');">&times;</a>
      <form name="formChangeFirstName" action="" method="post">
          <input placeholder="Nieuwe Voornaam" name="nieuweVoornaam" id="nieuweVoornaam" type="text" required>
          <br>
          <input type="submit" name="changeFirstName" id="changeFirstName" value="Verander">
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

  <?php } ?>

  <?php if ($user != null) { ?>
  <h1><?php echo $profiel_gebruikersnaam; ?></h1>
  <p class="userInfo">Naam: <span><?php echo $profiel_naam; ?></span></p>
  <?php }else{ ?>
  <h1>Mijn Profiel</h1>
  <p class="userInfo">Gebruikersnaam: <span><?php echo $_SESSION["Gebruikersnaam"]; ?></span></p>
  <p class="userInfo">Naam: <span><?php echo $_SESSION["Naam"]; ?></span></p>
  <?php } ?>
  <hr>
  <h3>Awards <?php if ($user == null) { echo "- <a class='smallBtn info' href='awardslist.php'>Overzicht</a>"; } ?></h3>
  <div class="awards">
    <?php
    if($result = mysqli_query($dbconn, $selectAwards)){
      if(mysqli_num_rows($result) > 0){
        $i = 0;
        while($row = mysqli_fetch_array($result)){
          if ($row['Editie'] == "De Mol") {
            $hideEditionName = "style='opacity: 0;'";
          }else{
            $hideEditionName = "";
          }
          ?>
          <div style="animation-delay: <?php echo $i/4; ?>s;" >
            <img src="img/awards/<?php echo $row['AwardId']; ?>.png" alt="award foto van <?php echo $row['Naam']; ?>">
            <p><?php echo $row['Naam']; ?><br><span <?php echo $hideEditionName; ?> ><?php echo $row['Editie']; ?></span></p>
          </div>
          <?php
          $i++;
        }
      }else{
        ?>
        <p style="text-align: center !important;"><?php echo $geenAwardsMelding; ?></p>
        <?php
      }
    }
    ?>
  </div>
  <hr>
  <?php if($user == null) { ?>
  <h3>Account Acties <button onclick="collapse('collapsible-content','collapsible');" type="button" id="collapsible"><i class="fas fa-chevron-down"></i></button></h3>
  <div id="collapsible-content">
    <ul>
      <li><i class="fas fa-edit"></i><a href="javascript:showPopup('popUpChangeName','show');"> gebruikersnaam wijzigen</a></li>
      <li><i class="fas fa-edit"></i><a href="javascript:showPopup('popUpChangeFirstName','show');"> voornaam wijzigen</a></li>
      <li><i class="fas fa-edit"></i><a href="javascript:showPopup('popUpChangePassword','show');"> wachtwoord wijzigen</a></li>
      <li class="delete warning"><i class="fas fa-trash-alt"></i><a href="javascript:showPopup('popUpDeleteAccount','show');"> verwijder account</a></li>
    </ul>
  </div>
  <?php } ?>
  <?php if($user != null) {?>
    <form action="" method="post">
      <input type="submit" name="deleteFromFollowing" id="deleteFromFollowing" value="Verwijder van lijst">
    </form>
  <?php } ?>
  </div>
</div>

  <!-- JavaScript -->
  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
