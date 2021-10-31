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

?>

<?php include "includes/header.php"; ?>

    <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <h1>Publieke Groepen</h1>

    <div class="deelnemersList">
      <?php if(!empty($groups)): ?>
      <?php $i = 0; foreach($groups as $group): ?>
      <a class="deelnemerItem info" style="animation-delay: <?=$i/6?>s;" href="group.php?g=<?=$group["Id"]?>">
        <i class='fas fa-users left'><?=$group["Members"]?></i>
          <?=$group["Name"]?>
      </a>
      <?php $i++; endforeach; ?>
      <?php else: ?>
      <h2>Geen groepen gevonden</h2>
      <?php endif; ?>
    </div>

    <hr>
    <button onclick="location.href = 'creategroup.php';" class="styledBtn" type="submit" name="button">Maak een groep</button>

<?php include "includes/footer.php"; ?>
