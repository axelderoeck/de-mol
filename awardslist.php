<?php

require_once("includes/phpdefault.php");

// Get all achieved awards
$stmt = $pdo->prepare('SELECT AwardId
FROM table_UserAwards
LEFT JOIN table_Awards
ON table_UserAwards.AwardId = table_Awards.Id
WHERE table_UserAwards.UserId = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$user_awards = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Add the award id's to array
$user_awards_id = array();
foreach($user_awards as $award){
  array_push($user_awards_id, $award['AwardId']);
}

// Get all available awards
$stmt = $pdo->prepare('SELECT * FROM table_Awards WHERE Actief = 1 ORDER BY Naam');
$stmt->execute();
$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <a href="profiel.php?user=<?=$_SESSION["Id"] ?>"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <div class="awardslist">
      <?php $delay = 0; /* Animation delay value */ ?>

      <!-- Unlocked Awards -->
      <?php foreach($awards as $award): ?>
        <?php if (in_array($award['Id'], $user_awards_id)): ?>
          <div class="info" style="animation-delay: <?=$delay/4; ?>s;" >
            <img src="img/awards/<?=$award['Id']; ?>.png" alt="award foto van <?=$award['Naam']; ?>">
            <h3><?=$award['Naam']; ?></h3>
            <p><?=$award['Beschrijving']; ?></p>
            <i class="fas fa-unlock"></i>
          </div>
          <?php $delay++; ?>
        <?php endif; ?>
      <?php endforeach; ?>
        
      <!-- Locked Awards -->
      <?php foreach($awards as $award): ?>
        <?php if (!in_array($award['Id'], $user_awards_id)): ?>
          <div class="info" style="animation-delay: <?=$delay/4; ?>s;" >
            <img src="img/awards/<?=$award['Id']; ?>.png" alt="award foto van <?=$award['Naam']; ?>">
            <h3 style="color: #707070;"><?=$award['Naam']; ?></h3>
            <p style="color: #707070;"><?=$award['Beschrijving']; ?></p>
            <?php
              // if this record is the secret award -> set hidden code
              if ($award['Secret'] == 1) {echo "<p class='hiddenField'>" . $award_secret_mol_randomcode . "</p>";}
            ?>
            <i style="color: #707070;" class="fas fa-lock"></i>
          </div>
          <?php $delay++; ?>
        <?php endif; ?>
      <?php endforeach; ?>

    </div>
    </div>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
</body>
</html>
