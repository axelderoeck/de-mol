<?php

require_once("includes/phpdefault.php");

// Get all notifications for logged in user
$stmt = $pdo->prepare('SELECT DISTINCT * FROM table_Notifications WHERE InvitedId = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST["confirmNotification"])){
  if($_POST["notificationType"] == 0){
    $notification = confirmFriendInvite($_POST["inviterId"], $_SESSION["Id"]);
  }else{
    $notification = addUserToGroup($_SESSION["Id"], $_POST["inviterId"]);
  }
  header('location: notifications.php');
}
if (isset($_POST["deleteNotification"])){
  $notification = deleteNotification($_SESSION["Id"], $_POST["inviterId"], $_POST["notificationType"]);
  header('location: notifications.php');
}


?>

<?php include "includes/header.php"; ?>

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
            $notification_inviter_name = $stmt->fetchColumn(0);
            $notification_type = "Vriendverzoek";
            $notification_href_link = "profile.php?u=" . $notification["InviterId"];
            $notification_id = $notification["NotificationType"] . $notification["InviterId"];
          }else{
            // Get name from inviting group
            $stmt = $pdo->prepare('SELECT Name FROM table_Groups WHERE Id = ?');
            $stmt->execute([ $notification["InviterId"] ]);

            // Set variables
            $notification_inviter_name = $stmt->fetchColumn(0);
            $notification_type = "Groepsverzoek";
            $notification_href_link = "group.php?g=" . $notification["InviterId"];
            $notification_id = $notification["NotificationType"] . $notification["InviterId"];
          }
        ?>
        <form action="" method="post">
          <div class="notification">
            <a href="<?=$notification_href_link?>">
              <div>
                <span><?=$notification_type?></span>
                <?=$notification_inviter_name?>
              </div>
            </a>
            <div class="buttons">
              <input type="submit" name="deleteNotification" value="Weiger">
              <input type="submit" name="confirmNotification" value="Accepteer">
            </div>
            <input type="hidden" name="notificationType" value="<?=$notification["NotificationType"]?>">
            <input type="hidden" name="inviterId" value="<?=$notification["InviterId"]?>">
          </div>
        </form>
      <?php endforeach; ?>
    <?php else: ?>
      <p>U heeft momenteel geen meldingen</p>
    <?php endif; ?>

<?php include "includes/footer.php"; ?>
