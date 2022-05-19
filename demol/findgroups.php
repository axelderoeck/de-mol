<?php

require_once("includes/phpdefault.php");

// Get all groups from user
$stmt = $pdo->prepare('SELECT COUNT(GroupId) AS Members, table_Groups.* 
FROM table_Groups
LEFT JOIN table_UsersInGroups 
ON table_Groups.Id = table_UsersInGroups.GroupId
WHERE Private = 0
GROUP BY GroupId
ORDER BY Members DESC');
$stmt->execute();
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

$getGroupScore = $pdo->prepare('SELECT SUM(Score)
FROM table_Groups
LEFT JOIN table_UsersInGroups
ON table_Groups.Id = table_UsersInGroups.GroupId
LEFT JOIN table_Users
ON table_UsersInGroups.UserId = table_Users.Id
WHERE GroupId = ?');

?>

<?php include "includes/header.php"; ?>

    <h1>Publieke Groepen</h1>

    <?php if(!empty($groups)): ?>
      <?php $i = 0; foreach($groups as $group): ?>
      <?php 
        $getGroupScore->execute([ $group["Id"] ]);
        $groupScore = $getGroupScore->fetchColumn(0); 
      ?>
        <a href="group.php?g=<?=$group["Id"]?>">
          <div style="animation-delay: <?=$i/4;?>s;" class="displayUser">
            <div>
              <!-- <span><?=$groupScore?></span> -->
              <img src="img/assets/demol_logo_geen_tekst.png" alt="de mol logo">
            </div>
            <span><?=$group["Name"]?></span>
          </div>
        </a>
      <?php $i++; endforeach; ?>
      <?php else: ?>
        <p style="text-align: center !important;">Geen groepen gevonden.</p>
      <?php endif; ?>

<?php include "includes/footer.php"; ?>
