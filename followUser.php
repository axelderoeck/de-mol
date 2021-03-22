<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

include "includes/settings.php";

$id = $_SESSION["Id"];

$checkIfUserHasAward = "SELECT *
FROM table_UserAwards
WHERE UserId = '$id' AND AwardId = $award_gilles";

$giveUserAward = "INSERT INTO table_UserAwards (UserId, AwardId)
VALUES ($id, $award_gilles)";

if (isset($_POST["submitUserToFollow"])){
  $userToFollow = $_POST["userToAdd"];

  $findUserToFollowId = "SELECT Id
  FROM table_Users
  WHERE Gebruikersnaam = '$userToFollow'
  ";

  //statement aanmaken
  if ($stmtFindUserToFollowId = mysqli_prepare($dbconn, $findUserToFollowId)){
      //query uitvoeren
      mysqli_stmt_execute($stmtFindUserToFollowId);
      //resultaat binden aan lokale variabelen
      mysqli_stmt_bind_result($stmtFindUserToFollowId, $userToFollowId);
      //resultaten opslaan
      mysqli_stmt_store_result($stmtFindUserToFollowId);
  }

  mysqli_stmt_fetch($stmtFindUserToFollowId);

  if ($userToFollowId != 0) {
    $dbconn->query("INSERT INTO table_Followers (UserId, UserIsFollowingId)
    VALUES ('$id','$userToFollowId')");
    $foutmelding = "Gebruiker toegevoegd.";
    $meldingSoort = "succes";
  }else{
    $foutmelding = "Gebruiker niet gevonden.";
    $meldingSoort = "warning";
  }

  $queryCheckIfUserHasAward = $dbconn->query("SELECT *
  FROM table_UserAwards
  WHERE UserId = '$id' AND AwardId = $award_gilles");

  if($queryCheckIfUserHasAward->num_rows == 0) {
    $queryIf10Followed = $dbconn->query("SELECT COUNT(UserId) AS 'Count'
    FROM table_Followers
    WHERE UserId = '$id'
    GROUP BY UserId");

    $data = $queryIf10Followed->fetch_array();
    $amountFollowed = ($data['Count']);

    if ($amountFollowed == 11) {
      mysqli_query($dbconn, $giveUserAward);
    }
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
    <a href="deelnemers.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <h1>Voeg een speler toe</h1>
    <form action="" method="post">
      <input placeholder="Gebruikersnaam" type="text" id="userToAdd" name="userToAdd">
      <input type="submit" name="submitUserToFollow" id="submitUserToFollow" value="Voeg toe">
    </form>
    <p class="example">
      Vul de gebruikersnaam in van de speler die je wil toevoegen in jouw mollenjacht. <br><br>
      Let op: Het is belangrijk dat je de gebruikersnaam invult en niet de voornaam.
    </p>
    <h2>Voor wat dient dit?</h2>
    <p>Als je jouw vrienden en/of familie hier <span>toevoegt</span>, spelen jullie tegen elkaar in de <span>mollenjacht</span>. <br><br>
    Wanneer de <span>mol</span> bekend is zal er een <span>ranglijst</span> te zien zijn die jullie vertelt wie de beste <span>mollenjager</span> is.</p>

  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
  <?php mysqli_close($dbconn); ?>
</body>
</html>
