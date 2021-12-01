<?php

require_once("includes/phpdefault.php");

// Get all notifications for logged in user
$stmt = $pdo->prepare('SELECT * FROM table_Notifications WHERE InvitedId = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($notifications as $notification){

  if (isset($_POST["confirmInvite" . $notification["NotificationType"] . $notification["InviterId"]])){
    if($_POST["notificationType"] == 0){
      $notification = confirmFriendInvite($_POST["inviterId"], $_SESSION["Id"]);
    }else{
      $notification = addUserToGroup($_SESSION["Id"], $_POST["inviterId"]);
    }
    header('location: notifications.php');
  }

  if (isset($_POST["deleteNotification" . $notification["NotificationType"] . $notification["InviterId"]])){
    if($_POST["notificationType"] == 0){
      // TODO
    }else{
      // TODO
    }
    header('location: notifications.php');
  }
}

// if (isset($_POST["confirmFriendInvite"])){
//   $notification = confirmFriendInvite($_POST["userId"], $_SESSION["Id"]);
//   header('location: notifications.php');
// }
// if (isset($_POST["confirmGroupInvite"])){
//   $notification = addUserToGroup($_SESSION["Id"], $_POST["groupId"]);
//   header('location: notifications.php');
// }

?>

<?php include "includes/header.php"; ?>

    <a href="friends.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <h1>Meldingen</h1>

    <?php if(!empty($notifications)): ?>
      <?php foreach($notifications as $notification): ?>
        <?php 
          // Get the correct info for notification
          if($notification["NotificationType"] == 0){
            // Get username from inviting user
            $stmt = $pdo->prepare('SELECT Username FROM table_Users WHERE Id = ?');
            $stmt->execute([ $notification["InviterId"] ]);

            // Set variables
            $notification_inviter_info = $stmt->fetchColumn(0);
            $notification_type = "Vriendverzoek";
            $notification_href_link = "profile.php?u=" . $notification["InviterId"];
          }else{
            // Get name from inviting group
            $stmt = $pdo->prepare('SELECT Name FROM table_Groups WHERE Id = ?');
            $stmt->execute([ $notification["InviterId"] ]);

            // Set variables
            $notification_inviter_info = $stmt->fetchColumn(0);
            $notification_type = "Groepsverzoek";
            $notification_href_link = "group.php?g=" . $notification["InviterId"];
          }
        ?>

        <div class="notification">
          <a href="<?=$notification_href_link?>">
            <div>
              <span><?=$notification_type?></span>
              <?=$notification_inviter_info?>
            </div>
          </a>
          <!-- POSSIBLE ISSUE: all forms have same name /submitting all? -->
          <form name="notificationForm<?=$notification["NotificationType"] . $notification["InviterId"]?>" action="" method="post">
            <input type="submit" name="deleteNotification<?=$notification["NotificationType"] . $notification["InviterId"]?>" value="Weiger">
            <input type="submit" name="confirmInvite<?=$notification["NotificationType"] . $notification["InviterId"]?>" value="Accepteer">
          </form>
          <input form="notificationForm<?=$notification["NotificationType"] . $notification["InviterId"]?>" type="hidden" name="notificationType" value="<?=$notification["NotificationType"]?>">
          <input form="notificationForm<?=$notification["NotificationType"] . $notification["InviterId"]?>" type="hidden" name="inviterId" value="<?=$notification["InviterId"]?>">
        </div>
      
      <?php endforeach; ?>
    <?php else: ?>
      <p>U heeft momenteel geen meldingen</p>
    <?php endif; ?>

<?php include "includes/footer.php"; ?>
