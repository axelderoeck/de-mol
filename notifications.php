<?php

require_once("includes/phpdefault.php");

// Get all notifications for logged in user
$stmt = $pdo->prepare('SELECT * FROM table_Notifications WHERE InvitedId = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST["confirmInvite"])){
  // Insert friendship to table
  $stmt = $pdo->prepare('INSERT INTO table_Friends (Id, IsFriendsWithId) VALUES (?, ?)');
  $stmt->execute([ $_POST["userId"], $_SESSION["Id"] ]);
  $stmt->execute([ $_SESSION["Id"], $_POST["userId"] ]);
  // Remove notification
  $stmt = $pdo->prepare('DELETE FROM table_Notifications WHERE InvitedId = ? AND InviterId = ?');
  $stmt->execute([ $_SESSION["Id"], $_POST["userId"] ]);
  // Notify user
  $foutmelding = "Je bent nu bevriend met " . $_POST["userName"] . ".";
  $meldingSoort = "succes";
  
  // AWARD_GILLES SECTION
  // Get how many friends a user has
  $stmt = $pdo->prepare('SELECT COUNT(UserId) AS Count
  FROM table_Friends
  WHERE Id = ?
  GROUP BY Id');

  // User 1: If more than 10 friends -> Give award
  $stmt->execute([ $_POST["userId"] ]);
  $amount_friends = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($amount_friends["Count"] > 10) {
    giveAward($_POST["userId"], $award_gilles);
  }

  // User 2: If more than 10 friends -> Give award
  $stmt->execute([ $_SESSION["Id"] ]);
  $amount_friends = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($amount_friends["Count"] > 10) {
    giveAward($_SESSION["Id"], $award_gilles);
  }

  header('location:notifications.php');
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

    <a href="deelnemers.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <h1>Meldingen</h1>

    <?php if(!empty($notifications)): ?>
    <?php foreach($notifications as $notification): ?>
      <?php if($notification["NotificationType"] == 0): ?>
        <?php
        // Get all info from user that initiated invite
        $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
        $stmt->execute([ $notification["InviterId"] ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC); 
        ?>
        <p>U hebt een vriendschapsverzoek ontvangen van <?=$user["Gebruikersnaam"]?></p>
        <form action="" method="post">
          <input type="hidden" name="userName" id="userName" value="<?=$user["Gebruikersnaam"]?>">
          <input type="hidden" name="userId" id="userId" value="<?=$user["Id"]?>">
          <input type="submit" name="confirmInvite" id="confirmInvite" value="Accepteer">
        </form>
      <?php elseif($notification["NotificationType"] == 1): ?>
        <?php
        // Get all info from inviting group
        $stmt = $pdo->prepare('SELECT * FROM table_Groups WHERE Id = ?');
        $stmt->execute([ $notification["InviterId"] ]);
        $group = $stmt->fetch(PDO::FETCH_ASSOC); 
        ?>
        <p>U hebt een groepsuitnodiging ontvangen voor de groep: <?=$group["Name"]?></p>
      <?php elseif($notification["NotificationType"] == 2): ?>
        <p><?=$notification["Message"]?></p>
      <?php endif; ?>
    <?php endforeach; ?>
    <?php else: ?>
      <p>U hebt momenteel geen meldingen</p>
    <?php endif; ?>
   
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
</body>
</html>
