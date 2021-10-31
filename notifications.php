<?php

require_once("includes/phpdefault.php");

// Get all notifications for logged in user
$stmt = $pdo->prepare('SELECT * FROM table_Notifications WHERE InvitedId = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST["confirmFriendInvite"])){
  $notification = confirmFriendInvite($_POST["userId"], $_SESSION["Id"]);
}
if (isset($_POST["confirmGroupInvite"])){
  $notification = addUserToGroup($_SESSION["Id"], $_POST["groupId"]);
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
        echo "showNotification('$notification->message','$notification->type');"; //message + color style
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

    <a href="friends.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
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
        <p>U hebt een vriendschapsverzoek ontvangen van <?=$user["Username"]?></p>
        <form action="" method="post">
          <input type="hidden" name="userId" id="userId" value="<?=$user["Id"]?>">
          <input type="submit" name="confirmFriendInvite" id="confirmFriendInvite" value="Accepteer">
        </form>
      <?php elseif($notification["NotificationType"] == 1): ?>
        <?php
        // Get all info from inviting group
        $stmt = $pdo->prepare('SELECT * FROM table_Groups WHERE Id = ?');
        $stmt->execute([ $notification["GroupId"] ]);
        $group = $stmt->fetch(PDO::FETCH_ASSOC); 
        ?>
        <p>U hebt een groepsuitnodiging ontvangen voor de groep: <?=$group["Name"]?></p>
        <form action="" method="post">
          <input type="hidden" name="groupId" id="groupId" value="<?=$group["Id"]?>">
          <input type="submit" name="confirmGroupInvite" id="confirmGroupInvite" value="Accepteer">
        </form>
      <?php endif; ?>
    <?php endforeach; ?>
    <?php else: ?>
      <p>U hebt momenteel geen meldingen</p>
    <?php endif; ?>
   
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
</body>
</html>
