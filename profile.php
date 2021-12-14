<?php

require_once("includes/phpdefault.php");

// Select all the user info from the id from url
$stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
$stmt->execute([ $_GET["u"] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

// Get firstname if exists
if($account["Name"] != null || $account["Name"] != ""){
  $firstname = $account["Name"];
}else{
  $firstname = $account["Username"];
}

// Get voted points
$stmt = $pdo->prepare('SELECT SUM(Score) FROM table_Scores WHERE UserId = ? GROUP BY UserId');
$stmt->execute([ $account["Id"] ]);
$votedPoints = $stmt->fetchColumn(0);

// Select all the awards the specified user has
$stmt = $pdo->prepare('SELECT *
                      FROM table_UserAwards
                      LEFT JOIN table_Awards
                      ON table_UserAwards.AwardId = table_Awards.Id
                      WHERE table_UserAwards.UserId = ?');
$stmt->execute([ $_GET["u"] ]);
$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the current profile is from the logged in user or not
if ($_GET["u"] == $_SESSION["Id"]) {
  $user_owns_account = true;
  $awards_notification = "Je hebt nog geen <span>awards</span>.";
}else{
  $user_owns_account = false;
  $awards_notification = "Deze gebruiker heeft nog geen <span>awards</span>.";

  // Search for a friendship with user
  $stmt = $pdo->prepare('SELECT * FROM table_Friends WHERE Id = ? AND IsFriendsWithId = ?');
  $stmt->execute([ $_SESSION["Id"], $_GET["u"] ]);
  $friendship = $stmt->fetch(PDO::FETCH_ASSOC);

  // Search for a pending friend invite with user
  $stmt = $pdo->prepare('SELECT * FROM table_Notifications WHERE InviterId = ? AND InvitedId = ?');
  $stmt->execute([ $_SESSION["Id"], $_GET["u"] ]);
  $friendinvite = $stmt->fetch(PDO::FETCH_ASSOC);
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

// Delete friend
if (isset($_POST["deleteFriend"])){
  $notification = deleteFriend($_SESSION["Id"], $$_GET["u"]);
  header('location:friends.php');
}
// Add friend
if (isset($_POST["addFriend"])){
  $notification = sendFriendInvite($_SESSION["Id"], $account["Friendcode"]);
  // Refresh page
  header('Refresh:1');
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
  <div class="profileFingerprint">
    <img src="img/assets/demol_logo_geen_tekst.png" alt="logo van de mol">
    <h1><?=$firstname?></h1>
    <span>#<?=$account["Friendcode"]?></span>
  </div>

  <div class="profileInfo">
    Score <span>//</span> <?=$votedPoints + $account["Score"]?>
    <br>
    <?php if($user_owns_account): $email = explode("@",$account["Email"]); ?>
      <?=$email[0]?><span>@<?=$email[1]?></span>
    <?php endif; ?>
  </div>
  
  <h3 style="margin: 30px 0 0 0;">Awards</h3>
  <div class="awards">
    <?php if(!empty($awards)): ?>
    <?php $i = 0; foreach($awards as $award): ?>
      <div style="animation-delay: <?=$i/4?>s;" >
        <img src="img/awards/<?=$award['AwardId']?>.png" alt="award foto van <?=$award['Name']?>">
        <p><?=$award['Name']?><br><span style="<?=$award['Edition'] == 'De Mol' ? 'opacity: 0;' : ''?>" ><?php echo $award['Edition']; ?></span></p>
      </div>
    <?php $i++; endforeach; ?>
    <?php else: ?>
      <p style="text-align: center !important;"><?=$awards_notification?></p>
    <?php endif; ?>
  </div>

  <?php if($user_owns_account == false) {?>
    <form class="profileButton" action="" method="post">
      <?php if($friendship): ?>
      <input class="delete" type="submit" name="deleteFriend" value="Verwijder vriend">
      <?php else: ?>
      <input <?php if($friendinvite){echo "disabled";} ?> class="add" type="submit" name="addFriend" value="<?php if($friendinvite){echo "Vriendschapsverzoek verzonden";} ?>">
      <?php endif; ?>
    </form>
  <?php } ?>

  <?php if($user_owns_account): ?>
  <div id="editscreen" class="editmenu">

    <div class="paper">
      <div class="lines">
        <a href="javascript:editMode('editscreen', false);">&times;</a>
        <div class="text">

          <form action="" method="post">
            <label>Gebruikersnaam</label>
            <input name="username" id="username" type="text" value="<?=$account["Username"]?>">
            <br>
            <label>Email</label>
            <input name="email" id="email" type="text" value="<?=$account["Email"]?>">
            <br>
            <input type="submit" name="saveUserSettings" id="saveUserSettings" value="Opslaan">
          </form>

          <h3>Wachtwoord wijzigen</h3>
          <form name="formChangePassword" action="" method="post">
            <label>Oud wachtwoord</label>
            <input placeholder="Oud wachtwoord" name="oldPassword" id="oldPassword" type="password" required>
            <br>
            <label>Wachtwoord</label>
            <input placeholder="Wachtwoord" name="password" id="password" type="password" required>
            <br>
            <label>Wachtwoord</label>
            <input placeholder="Wachtwoord" name="confirmPassword" id="confirmPassword" type="password" required>
            <br>
            <input type="submit" name="changePassword" id="changePassword" value="Wijzig">
          </form>

        </div>
      </div>
    </div>

  </div>
  <?php endif; ?>

  </div>
</div>

<!-- JavaScript -->
<script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>