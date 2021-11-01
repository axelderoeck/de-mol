<?php

require_once("includes/phpdefault.php");

// Select all the group info from the url id
$stmt = $pdo->prepare('SELECT * FROM table_Groups WHERE Id = ?');
$stmt->execute([ $_GET["g"] ]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

// Select all the group members from the url id
$stmt = $pdo->prepare('SELECT DISTINCT * FROM table_UsersInGroups 
LEFT JOIN table_Users ON table_UsersInGroups.UserId = table_Users.Id 
WHERE GroupId = ? LIMIT 10');
$stmt->execute([ $group["Id"] ]);
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if current user is a member of this group
$stmt = $pdo->prepare('SELECT UserId FROM table_UsersInGroups WHERE UserId = ? AND GroupId = ?');
$stmt->execute([ $_SESSION["Id"], $_GET["g"] ]);
$ismember = $stmt->fetch(PDO::FETCH_ASSOC);
// If member exists in group -> set variable
if($ismember){
  $user_is_member = true;
}else{
  $user_is_member = false;
}

// check if the current profile is the group admin
if ($group["AdminId"] == $_SESSION["Id"]) {
  $user_is_admin = true;
}else{
  $user_is_admin = false;
}

if (isset($_POST["saveGroupSettings"])){
  // Check if something changed before executing function
  if($_POST["description"] != $group["Description"]){
    $notification = changeGroupDescription($group["Id"], $_POST["description"]);
  }
  if($_POST["private"] != $group["Private"]){
    $notification = changeGroupPrivacy($group["Id"], $_POST["private"]);
  }
  if($_POST["name"] != $group["Name"]){
    $notification = changeGroupName($group["Id"], $_POST["name"]);
  }
  // If no errors -> set general success message
  if($notification->type != 'warning'){
    $notification->type = "success";
    $notification->message = "Gegevens opgeslagen";
    // Refresh after 1 second to show the updated info
    header('Refresh:1');
  }
}

if (isset($_POST["joinGroup"])){
  $notification = addUserToGroup($_SESSION["Id"], $group["Id"]);
  // Refresh after 1 second to show the updated info
  header('Refresh:1');
}

if (isset($_POST["leaveGroup"])){
  $notification = deleteUserFromGroup($_SESSION["Id"], $group["Id"]);
  // Refresh after 1 second to show the updated info
  header('Refresh:1');
}

?>

<?php include "includes/header.php"; ?>

  <?php if($user_is_admin): ?>
  <a href="javascript:editMode('editscreen', true);">
    <div class="editbutton">
        <i class="fas fa-edit"></i>
    </div>
  </a>
  <?php endif; ?>

  <!-- User info -->
  <h1><?=$group["Name"]?></h1>
  <p><?=$group["Description"]?></p>
  <p>Deze groep is <?php if($group["Private"] == 0){echo "publiek.";}else{echo "privé.";} ?></p>

  <h3>Leden</h3>
  <div>
    <?php if($user_is_member && $group["Private"] == 1 || $group["Private"] == 0): ?>
      <?php if(!empty($members)): ?>
        <?php foreach($members as $member): ?>
          <a href="profile.php?u=<?=$member["Id"]?>">
            <p><?=$member["Username"]?></p>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align: center !important;">Er zijn nog geen leden</p>
      <?php endif; ?>
    <?php else: ?>
      <p style="text-align: center !important;">Je kan geen leden bekijken van een privé groep.</p>
    <?php endif; ?>
  </div>
  
  <?php if($group["Private"] == 0): ?>
    <?php if(!$user_is_member): ?>
      <form action="" method="post">
        <input type="submit" name="joinGroup" id="joinGroup" value="Aansluiten bij groep">
      </form>
    <?php endif; ?>
  <?php endif; ?>

  <?php if($user_is_member): ?>
    <form action="" method="post">
      <input type="submit" name="leaveGroup" id="leaveGroup" value="Groep verlaten">
    </form>
  <?php endif; ?>

  <?php if($user_is_admin): ?>
    <button onclick="location.href = 'addusertogroup.php?g=<?=$group["Id"]?>';" class="styledBtn" type="submit" name="button">Nodig spelers uit</button>

    <div id="editscreen" class="editmenu">
      <a href="javascript:editMode('editscreen', false);">&times;</a>
      <form action="" method="post">
        <label>Wijzig naam</label>
        <input type="text" id="name" name="name" value="<?=$group["Name"]?>">
        <br>
        <label>Wijzig beschrijving</label>
        <input type="text" id="description" name="description" value="<?=$group["Description"]?>">

        <input type="hidden" name="private" value="0">
        <input <?php if($group['Private'] == 1) {echo 'checked';}; ?> type="checkbox" id="private" name="private" value="1" />
        <label>Wijzig privacy</label>
        <br>
        <input type="submit" name="saveGroupSettings" id="saveGroupSettings" value="Opslaan">
      </form>
    </div>
  <?php endif; ?>

<?php include "includes/footer.php"; ?>
