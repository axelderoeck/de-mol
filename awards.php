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
$stmt = $pdo->prepare('SELECT * FROM table_Awards WHERE Active = 1 ORDER BY Name');
$stmt->execute();
$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include "includes/header.php"; ?>

    <a href="profile.php?u=<?=$_SESSION["Id"] ?>"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <div class="awardslist">
      <?php $delay = 0; /* Animation delay value */ ?>

      <!-- Unlocked Awards -->
      <?php foreach($awards as $award): ?>
        <?php if (in_array($award['Id'], $user_awards_id)): ?>
          <div class="info" style="animation-delay: <?=$delay/4; ?>s;" >
            <img src="img/awards/<?=$award['Id']; ?>.png" alt="award foto van <?=$award['Name']; ?>">
            <h3><?=$award['Name']; ?></h3>
            <p><?=$award['Description']; ?></p>
            <i class="fas fa-unlock"></i>
          </div>
          <?php $delay++; ?>
        <?php endif; ?>
      <?php endforeach; ?>
        
      <!-- Locked Awards -->
      <?php foreach($awards as $award): ?>
        <?php if (!in_array($award['Id'], $user_awards_id)): ?>
          <div class="info" style="animation-delay: <?=$delay/4; ?>s;" >
            <img src="img/awards/<?=$award['Id']; ?>.png" alt="award foto van <?=$award['Name']; ?>">
            <h3 style="color: #707070;"><?=$award['Name']; ?></h3>
            <p style="color: #707070;"><?=$award['Description']; ?></p>
            <?php
              // if this record is the secret award -> set hidden code
              if ($award['Secret'] == 1) {echo "<p class='hiddenField'>" . AWARD_SECRET_MOL_CODE . "</p>";}
            ?>
            <i style="color: #707070;" class="fas fa-lock"></i>
          </div>
          <?php $delay++; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

<?php include "includes/footer.php"; ?>
