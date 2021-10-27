<?php

require_once("includes/phpdefault.php");

// Get all groups from user
$stmt = $pdo->prepare('SELECT table_Groups.Name
                      FROM table_Groups
                      LEFT JOIN table_UsersInGroups
                      ON table_Groups.Id = table_UsersInGroups.GroupId
                      WHERE table_UsersInGroups.UserId = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

  <div id="main">
    <div class="respContainer">

    <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <h1>Mijn Groepen</h1>

    <div class="deelnemersList">
      <?php if(!empty($groups)): ?>
      <?php $i = 0; foreach($groups as $group): ?>
      <a class="deelnemerItem info" style="animation-delay: <?=$i/6?>s;" href="group.php?g=<?=$group['Id']?>">
        <i class='fas fa-user left'></i>
          <?=$group["Name"]?>
      </a>
      <?php $i++; endforeach; ?>
      <?php else: ?>
      <h2>Je hebt nog geen groep</h2>
      <?php endif; ?>
    </div>

    <hr>
    <button onclick="location.href = 'addgroup.php';" class="styledBtn" type="submit" name="button">Zoek groepen</button>
</div>
  </div>

  <!-- JavaScript -->
  <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>
