<?php

require_once("includes/phpdefault.php");

/*

CODE FROM SNUFFELSNACK FOR CHECKBOX CHANGE PRIVATE VALUE

<input type="hidden" name="personalisation" value="0">
<input <?php if($product['personalisation'] == 1) {echo 'checked';}; ?> type="checkbox" id="personalisationCheckbox" name="personalisation" value="1" class="custom-control-input" />

*/

// Select all the group info from the url id
$stmt = $pdo->prepare('SELECT * FROM table_Groups WHERE Id = ?');
$stmt->execute([ $_GET["g"] ]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

// Select all the group members from the url id
$stmt = $pdo->prepare('SELECT DISTINCT * FROM table_UsersInGroups 
LEFT JOIN table_Users ON table_UsersInGroups.UserId = table_Users.Id 
WHERE GroupId = ?');
$stmt->execute([ $_GET["g"] ]);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// check if the current profile is the group admin
if ($group["AdminId"] == $_SESSION["Id"]) {
  $user_is_admin = true;
}else{
  $user_is_admin = false;
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

  <!-- User info -->
  <h1><?=$group["Name"]?></h1>
  <p><?=$group["Description"]?></p>
  <p>Deze groep is <?php if($group["Private"] == 0){echo "publiek.";}else{echo "privé.";} ?></p>

  <h3>Leden</h3>
  <div>
    <?php if(!empty($members)): ?>
    <?php foreach($members as $member): ?>
      <a href="profile.php?u=<?=$member["Id"]?>">
        <p><?=$member["Username"]?></p>
      </a>
    <?php endforeach; ?>
    <?php else: ?>
      <p style="text-align: center !important;">Er zijn nog geen leden</p>
    <?php endif; ?>
  </div>
  <?php if($user_is_admin){ ?>
    <button onclick="location.href = 'addusertogroup.php?g=<?=$group["Id"]?>';" class="styledBtn" type="submit" name="button">Nodig spelers uit</button>
  <?php } ?>

  </div>
</div>

  <!-- JavaScript -->
  <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>
