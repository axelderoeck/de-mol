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
  $stmt = $pdo->prepare('SELECT * FROM table_Friends 
  WHERE Id = ? AND IsFriendsWithId = ?
  OR Id = ? AND IsFriendsWithId = ?');
  $stmt->execute([ $_SESSION["Id"], $_GET["u"], $_GET["u"], $_SESSION["Id"] ]);
  $friendship = $stmt->fetch(PDO::FETCH_ASSOC);

  // Search for a pending friend invite with user
  $stmt = $pdo->prepare('SELECT * FROM table_Notifications WHERE InviterId = ? AND InvitedId = ?');
  $stmt->execute([ $_SESSION["Id"], $_GET["u"] ]);
  $friendinvite = $stmt->fetch(PDO::FETCH_ASSOC);
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

  <a href="friends.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>

  <!-- User Name -->
  <div class="profileFingerprint">
    <img src="img/assets/demol_logo_geen_tekst.png" alt="logo van de mol">
    <h1><?=$firstname?></h1>
    <span>#<?=$account["Friendcode"]?></span>
  </div>

  <!-- User Info -->
  <div class="profileInfo">
    Score <span>//</span> <?=getVotedPoints($account["Id"]) + $account["Score"]?>
  </div>
  
  <!-- User Awards -->
  <h3 style="margin: 30px 0 0 0;">Awards</h3>
  <div class="profileAwards">
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

  <!-- Add/Delete friend button -->
  <?php if(!$user_owns_account) {?>
    <form class="profileButton" action="" method="post">
      <?php if($friendship): ?>
        <input class="delete" type="submit" name="deleteFriend" value="Verwijder vriend">
      <?php else: ?>
        <input <?php if($friendinvite){echo "disabled";} ?> class="add" type="submit" name="addFriend" value="<?php if($friendinvite){echo "Vriendschapsverzoek verzonden";} ?>">
      <?php endif; ?>
    </form>
  <?php } ?>

  </div>
</div>

<!-- JavaScript -->
<script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>