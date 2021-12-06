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

// Get voted points
$stmt = $pdo->prepare('SELECT SUM(Score) FROM table_Scores WHERE UserId = ? GROUP BY UserId');
$stmt->execute([ $account["Id"] ]);
$votedPoints = $stmt->fetchColumn(0);

// Get firstname if exists
if($account["Name"] != null || $account["Name"] != ""){
  $firstname = $account["Name"];
}else{
  $firstname = $account["Username"];
}

// Select all the awards the specified user has
$stmt = $pdo->prepare('SELECT *
                      FROM table_UserAwards
                      LEFT JOIN table_Awards
                      ON table_UserAwards.AwardId = table_Awards.Id
                      WHERE table_UserAwards.UserId = ?');
$stmt->execute([ $user_id ]);
$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($user_owns_account) {
  $geenAwardsMelding = "Je hebt nog geen <span>awards</span>.";
}else{
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
  <div class="profileFingerprint">
    <img src="img/assets/demol_logo_geen_tekst.png" alt="logo van de mol">
    <h1><?=$firstname?></h1>
  </div>

  <h3 style="margin: 30px 0 0 0;">Awards</h3>
  <div class="profileAwards">
    <?php $count_awards = 0; foreach($awards as $award): ?>
      <img src="img/awards/<?=$award['AwardId']?>.png" alt="award foto van <?=$award['Name']?>">
    <?php $count_awards++; endforeach; ?>
  </div>
    
  <h3>Dossier</h3>
  <div class="profileInfo">
    <span>Score:</span> <?=$votedPoints + $account["Score"]?> <br>
    <span>Gebruikersnaam:</span> <?=$account["Username"]?> <br>
    <?php if ($user_owns_account): ?>
    <span>Email:</span> <?=$account["Email"] ? $account["Email"] : "Geen"?> <br>
    <?php else: ?>
    <span style="text-decoration: line-through;">Email:</span> <span style="text-decoration: line-through; color:#000;"><?=$account["Username"]?>@mol.be</span> <br>
    <?php endif; ?>
    <span>FriendCode:</span> #<?=$account["Friendcode"]?>
    <img src="img/assets/demol_logo_classified.png" alt="">
  </div>
  
  <!-- <h3>Awards <?php if ($user_owns_account == true) { echo "- <a class='smallBtn info' href='awards.php'>Overzicht</a>"; } ?></h3>
  <span class="awardsTitle">Awards</span>
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
  </div> -->

  <?php if($user_owns_account == false) {?>
    <form action="" method="post">
      <input type="submit" name="deleteFromFollowing" id="deleteFromFollowing" value="Verwijder van lijst">
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
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
    $(document).ready(function(){
      // Slick settings for candidate list
      $('.profileAwards').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        dots: false,
        arrows: false,
        centerMode: true,
        centerPadding: '5%',
        focusOnSelect: true,
        draggable: true,
        mobileFirst: true,
        infinite: false,
        swipe: true,
        initialSlide: <?=round($count_awards/2)?>
      });
    });
</script>

</body>
</html>