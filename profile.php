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
// Change password
if (isset($_POST["changePassword"])){
  $notification = changePassword($_SESSION["Id"], $_POST["oldPassword"], $_POST["password"], $_POST["confirmPassword"]);
}
// Change email
if (isset($_POST["changeEmail"])){
  $notification = changeEmail($_SESSION["Id"], $_POST["email"]);
}
// Change username
if (isset($_POST["changeUsername"])){
  $notification = changeUsername($_SESSION["Id"], $_POST["username"]);
}
// Delete account
if (isset($_POST["deleteAccount"])){
  $notification = deleteAccount($_SESSION["Id"]);
  header('location:index.php?logout=1');
}

// Select all the user info from the id from url
$stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
$stmt->execute([ $user_id ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

// Select all the awards the specified user has
$stmt = $pdo->prepare('SELECT *
                      FROM table_UserAwards
                      LEFT JOIN table_Awards
                      ON table_UserAwards.AwardId = table_Awards.Id
                      WHERE table_UserAwards.UserId = ?');
$stmt->execute([ $user_id ]);
$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($user_owns_account == true) {
  $geenAwardsMelding = "Je hebt nog geen <span>awards</span>.";
} elseif ($user_owns_account == false){

  $geenAwardsMelding = "Deze gebruiker heeft nog geen <span>awards</span>.";

  if (isset($_POST["deleteFromFollowing"])){
    $stmt = $pdo->prepare('DELETE FROM table_Followers WHERE UserId = ? AND UserIsFollowingId = ?');
    $stmt->execute([ $session_id, $user_id ]);

    header('location:friends.php');
  }

}

if (isset($_POST["saveUserSettings"])){
  // Check if something changed before executing function
  if($_POST["username"] != $account["Username"]){
    $notification = changeUsername($account["Id"], $_POST["username"]);
  }
  if($_POST["email"] != $account["Email"]){
    $notification = changeEmail($account["Id"], $_POST["email"]);
  }
  // If no errors -> set general success message
  if($notification->type != 'warning'){
    $notification->type = "success";
    $notification->message = "Gegevens opgeslagen";
    // Refresh after 1 second to show the updated info
    header('Refresh:1');
  }
}

?>

<?php include "includes/header.php"; ?>

  <?php if($user_owns_account): ?>
  <a href="javascript:editMode('editscreen', true);">
    <div class="editbutton">
        <i class="fas fa-edit"></i>
    </div>
  </a>
  <?php endif; ?>

  <?php if ($user_owns_account == false) { ?>
  <a href="friends.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
  <?php } ?>

  <?php if ($user_owns_account == true) { ?>

  <div id="popUpChangePassword" class="popupStyle translucent">
    <div class="box">
      <a class="closeLink" href="javascript:showPopup('popUpChangePassword','hide');">&times;</a>
      <form name="formChangePassword" action="" method="post">
          <input placeholder="Oud wachtwoord" name="oldPassword" id="oldPassword" type="password" required>
          <br>
          <input placeholder="Wachtwoord" name="password" id="password" type="password" required>
          <br>
          <input placeholder="Wachtwoord" name="confirmPassword" id="confirmPassword" type="password" required>
          <br>
          <input type="submit" name="changePassword" id="changePassword" value="Verander">
      </form>
    </div>
  </div>

  <div id="popUpChangeName" class="popupStyle translucent">
    <div class="box">
      <a class="closeLink" href="javascript:showPopup('popUpChangeName','hide');">&times;</a>
      <form name="formChangeName" action="" method="post">
          <input placeholder="Nieuwe gebruikersnaam" name="username" id="username" type="text" required>
          <br>
          <input type="submit" name="changeUsername" id="changeUsername" value="Verander">
      </form>
    </div>
  </div>

  <div id="popUpAddEmail" class="popupStyle translucent">
    <div class="box">
      <a class="closeLink" href="javascript:showPopup('popUpAddEmail','hide');">&times;</a>
      <form name="formAddEmail" action="" method="post">
          <input placeholder="Email" name="email" id="email" type="text" required>
          <br>
          <input type="submit" name="changeEmail" id="changeEmail" value="Verander">
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
  <h1><?=$account["Username"]?></h1>
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

  <h3>Awards <?php if ($user_owns_account == true) { echo "- <a class='smallBtn info' href='awards.php'>Overzicht</a>"; } ?></h3>
  <div class="awards">
    <?php if(!empty($awards)): ?>
    <?php $i = 0; foreach($awards as $award): ?>
      <div style="animation-delay: <?=$i/4?>s;" >
        <img src="img/awards/<?=$award['AwardId']?>.png" alt="award foto van <?=$award['Name']?>">
        <p><?=$award['Name']?><br><span style="<?=$award['Edition'] == 'De Mol' ? 'opacity: 0;' : ''?>" ><?php echo $award['Edition']; ?></span></p>
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

  <?php if($user_owns_account): ?>
  <div id="editscreen" class="editmenu">
    <a href="javascript:editMode('editscreen', false);">&times;</a>

    <form action="" method="post">
      <label>Gebruikersnaam</label>
      <input name="username" id="username" type="text" value="<?=$account["Username"]?>">
      <br>
      <label>Email</label>
      <input name="email" id="email" type="text" value="<?=$account["Email"]?>">
      <br>
      <input type="submit" name="saveUserSettings" id="saveUserSettings" value="Opslaan">
    </form>

      <form name="formChangePassword" action="" method="post">
          <input placeholder="Oud wachtwoord" name="oldPassword" id="oldPassword" type="password" required>
          <br>
          <input placeholder="Wachtwoord" name="password" id="password" type="password" required>
          <br>
          <input placeholder="Wachtwoord" name="confirmPassword" id="confirmPassword" type="password" required>
          <br>
          <input type="submit" name="changePassword" id="changePassword" value="Verander">
      </form>
  </div>
  <?php endif; ?>

<?php include "includes/footer.php"; ?>