<?php

require_once("includes/phpdefault.php");

$stmt = $pdo->prepare('SELECT table_Users.Id, Username, Screen, IFNULL(SUM(table_Scores.Score), 0) + IFNULL(table_Users.Score, 0) AS "TotalScore"
FROM table_Users
LEFT JOIN table_Scores
ON table_Users.Id = table_Scores.UserId
WHERE table_Users.Id IN
(SELECT table_Users.Id
    FROM table_Users
    LEFT JOIN table_Friends
    ON table_Users.Id = table_Friends.IsFriendsWithId
    WHERE table_Friends.Id = ? OR table_Friends.IsFriendsWithId = ?)
GROUP BY table_Users.Id
ORDER BY TotalScore DESC');
$stmt->execute([ $_SESSION["Id"], $_SESSION["Id"] ]);
$scores_friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT table_Users.Id, Username, Screen, IFNULL(SUM(table_Scores.Score), 0) + IFNULL(table_Users.Score, 0) AS "TotalScore"
FROM table_Users
LEFT JOIN table_Scores
ON table_Users.Id = table_Scores.UserId
GROUP BY table_Users.Id
ORDER BY TotalScore DESC
LIMIT 20'); // TODO: variable in prepared statement
$stmt->execute();
$scores_all = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare group scores statement
$stmt_group = $pdo->prepare('SELECT table_Users.Id, Username, Screen, IFNULL(SUM(table_Scores.Score), 0) + IFNULL(table_Users.Score, 0) AS "TotalScore"
FROM table_Users
LEFT JOIN table_Scores
ON table_Users.Id = table_Scores.UserId
WHERE table_Users.Id IN
(SELECT table_Users.Id
    FROM table_Users
    LEFT JOIN table_UsersInGroups
    ON table_Users.Id = table_UsersInGroups.UserId
    WHERE table_UsersInGroups.GroupId = ?)
GROUP BY table_Users.Id
ORDER BY TotalScore DESC');

// Get the groups user is part of
$stmt = $pdo->prepare('SELECT DISTINCT table_Groups.Id, table_Groups.Name
FROM table_Groups
LEFT JOIN table_UsersInGroups
ON table_Groups.Id = table_UsersInGroups.GroupId
WHERE table_UsersInGroups.UserId = ?'); 
$stmt->execute([ $_SESSION["Id"] ]);
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include "includes/header.php"; ?>

  <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>

  <h1>Scores</h1>

  <div class="slider-nav">
    <div><p>Top <?=LIMIT_TOPLIST?></p></div>
    <div><p>Vrienden</p></div>
    <?php if(!empty($groups)): ?>
      <?php foreach($groups as $group): ?>
      <div><p><?=$group["Name"]?></p></div>
      <?php endforeach; ?>
    <?php else: ?>
      <div><p>Groepen</p></div>
    <?php endif; ?>
  </div>
  <div class="slider-for">
    <!-- Scores Everyone -->
    <div style="overflow-x: hidden;">
      <?php if(!empty($scores_all)): ?>
        <?php $i = 1; foreach($scores_all as $score): ?>
          <a href="profile.php?u=<?=$score["Id"]?>">
            <div style="animation-delay: <?=$i/4;?>s;" class="displayUser <?php if($score['Id'] == $_SESSION["Id"]){echo "selected";} ?>">
              <div>
                <span><?=$score["TotalScore"]?></span>
                <?php if($score["Screen"] == 0): ?>
                  <img src="img/assets/demol_logo_geen_tekst_groen.png" alt="de mol logo">
                <?php elseif($score["Screen"] == 1): ?>
                  <img src="img/assets/demol_logo_geen_tekst_rood.png" alt="de mol logo">
                <?php else: ?>
                  <img src="img/assets/demol_logo_geen_tekst.png" alt="de mol logo">
                <?php endif; ?>
              </div>
              <span><?=$score["Username"]?></span>
            </div>
          </a>
        <?php $i++; endforeach; ?>
      <?php else: ?>
        <h2>Er zijn nog geen scores.</h2>
      <?php endif; ?>
    </div>

    <!-- Scores Friends -->
    <div style="overflow-x: hidden;">
      <?php if(!empty($scores_friends)): ?>
        <?php $i = 1; foreach($scores_friends as $score): ?>
          <a href="profile.php?u=<?=$score["Id"]?>">
            <div style="animation-delay: <?=$i/4;?>s;" class="displayUser <?php if($score['Id'] == $_SESSION["Id"]){echo "selected";} ?>">
              <div>
                <span><?=$score["TotalScore"]?></span>
                <?php if($score["Screen"] == 0): ?>
                  <img src="img/assets/demol_logo_geen_tekst_groen.png" alt="de mol logo">
                <?php elseif($score["Screen"] == 1): ?>
                  <img src="img/assets/demol_logo_geen_tekst_rood.png" alt="de mol logo">
                <?php else: ?>
                  <img src="img/assets/demol_logo_geen_tekst.png" alt="de mol logo">
                <?php endif; ?>
              </div>
              <span><?=$score["Username"]?></span>
            </div>
          </a>
        <?php $i++; endforeach; ?>
      <?php else: ?>
        <h2>Je hebt nog geen vrienden toegevoegd.</h2>
      <?php endif; ?>
    </div>
    
    <!-- Scores Groups -->
    <?php if(!empty($groups)): ?>
      <?php foreach($groups as $group):
      $stmt_group->execute([ $group["Id"] ]);
      $scores_group = $stmt_group->fetchAll(PDO::FETCH_ASSOC); 
      ?>
      <div style="overflow-x: hidden;">
        <?php if(!empty($scores_group)): ?>
          <?php $i = 1; foreach($scores_group as $score): ?>
            <a href="profile.php?u=<?=$score["Id"]?>">
              <div style="animation-delay: <?=$i/4;?>s;" class="displayUser <?php if($score['Id'] == $_SESSION["Id"]){echo "selected";} ?>">
                <div>
                  <span><?=$score["TotalScore"]?></span>
                  <?php if($score["Screen"] == 0): ?>
                    <img src="img/assets/demol_logo_geen_tekst_groen.png" alt="de mol logo">
                  <?php elseif($score["Screen"] == 1): ?>
                    <img src="img/assets/demol_logo_geen_tekst_rood.png" alt="de mol logo">
                  <?php else: ?>
                    <img src="img/assets/demol_logo_geen_tekst.png" alt="de mol logo">
                  <?php endif; ?>
                </div>
                <span><?=$score["Username"]?></span>
              </div>
            </a>
          <?php $i++; endforeach; ?>
        <?php else: ?>
          <h2>Er zijn nog geen scores.</h2>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div>
        <h2>Je hebt momenteel geen groepen</h2>
      </div>
    <?php endif; ?>
  </div>

<?php include "includes/footer.php"; ?>
