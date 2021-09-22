<?php

require_once("includes/phpdefault.php");

// Get the logged in and selected user id
$session_id = $_SESSION["Id"];
$user_id = $_GET["u"];

// check if the current profile is from the logged in user or not
if ($user_id == $session_id) {
  $user_owns_account = true;
}else{
  $user_owns_account = false;
}

// Select all the user info from the id from url
$stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
$stmt->execute([ $user_id ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

// Select all the awards the specified user has
$stmt = $pdo->prepare('SELECT AwardId, Naam, Beschrijving, Editie
                      FROM table_UserAwards
                      LEFT JOIN table_Awards
                      ON table_UserAwards.AwardId = table_Awards.Id
                      WHERE table_UserAwards.UserId = ?');
$stmt->execute([ $user_id ]);
$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($user_owns_account == true) {
  include "includes/account-actions/changename.php";
  include "includes/account-actions/changepassword.php";
  include "includes/account-actions/deleteaccount.php";
  include "includes/account-actions/changefirstname.php";
  include "includes/account-actions/addemail.php";

  $geenAwardsMelding = "Je hebt nog geen <span>awards</span>.";
} elseif ($user_owns_account == false){

  $geenAwardsMelding = "Deze gebruiker heeft nog geen <span>awards</span>.";

  if (isset($_POST["deleteFromFollowing"])){
    $stmt = $pdo->prepare('DELETE FROM table_Followers WHERE UserId = ? AND UserIsFollowingId = ?');
    $stmt->execute([ $session_id, $user_id ]);

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

  <?php if ($user_owns_account == false) { ?>
  <a href="deelnemers.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
  <?php } ?>

  <?php if ($user_owns_account == true) { ?>

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

  <div id="popUpAddEmail" class="popupStyle translucent">
    <div class="box">
      <a class="closeLink" href="javascript:showPopup('popUpAddEmail','hide');">&times;</a>
      <form name="formAddEmail" action="" method="post">
          <input placeholder="Email" name="emailvalue" id="emailvalue" type="text" required>
          <br>
          <input type="submit" name="addEmail" id="addEmail" value="Voeg toe">
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

  <!-- User info -->
  <h1><?=$account["Naam"]?></h1>
  <p class="userInfo">Placeholder: <span><?=$account["Voted"]?></span></p>
  <?php if ($user_owns_account): ?>
  <!-- Private user info -->
  <p class="userInfo">Email: <span><?=$account["Email"] ? $account["Email"] : "Geen"?></span></p>
  <p class="userInfo">FriendCode: <span>#<?=$account["Friendcode"]?></span></p>
  <?php endif; ?>

  <?php if ($user_owns_account && $account["Email"] == null) { ?>
    <div class="bericht info">
      <p>U hebt nog geen email ingesteld. U kan nog steeds inloggen met uw gebruikersnaam.</p>
    </div>
  <?php } ?>
  <hr>

  <h3>Awards <?php if ($user_owns_account == true) { echo "- <a class='smallBtn info' href='awardslist.php'>Overzicht</a>"; } ?></h3>
  <div class="awards">
    <?php if(!empty($awards)): ?>
    <?php $i = 0; foreach($awards as $award): ?>
      <div style="animation-delay: <?=$i/4?>s;" >
        <img src="img/awards/<?=$award['AwardId']?>.png" alt="award foto van <?=$award['Naam']?>">
        <p><?=$award['Naam']?><br><span style="<?=$award['Editie'] == 'De Mol' ? 'opacity: 0;' : ''?>" ><?php echo $award['Editie']; ?></span></p>
      </div>
    <?php $i++; endforeach; ?>
    <?php else: ?>
      <p style="text-align: center !important;"><?php echo $geenAwardsMelding; ?></p>
    <?php endif; ?>
  </div>
  
  <hr>
  <?php if($user_owns_account == true) { ?>
  <h3>Account Acties <button onclick="collapse('collapsible-content','collapsible');" type="button" id="collapsible"><i class="fas fa-chevron-down"></i></button></h3>
  <div id="collapsible-content">
    <ul>
      <li><i class="fas fa-edit"></i><a href="javascript:showPopup('popUpChangeName','show');"> gebruikersnaam wijzigen</a></li>
      <li><i class="fas fa-edit"></i><a href="javascript:showPopup('popUpChangeFirstName','show');"> voornaam wijzigen</a></li>
      <li><i class="fas fa-edit"></i><a href="javascript:showPopup('popUpAddEmail','show');"> email wijzigen</a></li>
      <li><i class="fas fa-edit"></i><a href="javascript:showPopup('popUpChangePassword','show');"> wachtwoord wijzigen</a></li>
      <li class="delete warning"><i class="fas fa-trash-alt"></i><a href="javascript:showPopup('popUpDeleteAccount','show');"> verwijder account</a></li>
    </ul>
  </div>
  <?php } ?>
  <?php if($user_owns_account == false) {?>
    <form action="" method="post">
      <input type="submit" name="deleteFromFollowing" id="deleteFromFollowing" value="Verwijder van lijst">
    </form>
  <?php } ?>
  </div>
</div>

  <!-- JavaScript -->
  <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>
