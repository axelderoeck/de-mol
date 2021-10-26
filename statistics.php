<?php

require_once("includes/phpdefault.php");

// Get all scores
$stmt = $pdo->prepare('SELECT Name, SUM(Score) AS TotalScore, Status
FROM table_Scores
LEFT JOIN table_Candidates
ON table_Scores.CandidateId = table_Candidates.Id
GROUP BY table_Scores.CandidateId
ORDER BY SUM(Score) DESC');
$stmt->execute();
$scores_all = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total voted points (for calculating percentages)
$stmt = $pdo->prepare('SELECT DISTINCT SUM(Score) FROM table_Scores');
$stmt->execute();
$total_voted_all = $stmt->fetchColumn(0);

// Get friends scores
$stmt = $pdo->prepare('SELECT Name, SUM(Score) AS TotalScore, Status
FROM table_Scores
LEFT JOIN table_Candidates
ON table_Scores.CandidateId = table_Candidates.Id
WHERE UserId IN (SELECT IsFriendsWithId FROM table_Friends WHERE Id = ?)
GROUP BY table_Scores.CandidateId
ORDER BY SUM(Score) DESC');
$stmt->execute([ $_SESSION["Id"] ]);
$scores_friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total voted points from friends (for calculating percentages)
$stmt = $pdo->prepare('SELECT DISTINCT SUM(Score) FROM table_Scores
WHERE UserId IN (SELECT IsFriendsWithId FROM table_Friends WHERE Id = ?)');
$stmt->execute([ $_SESSION["Id"] ]);
$total_voted_friends = $stmt->fetchColumn(0);

// Get the amount of users that voted
$stmt = $pdo->prepare('SELECT COUNT(Id) FROM table_Users WHERE Voted = 1');
$stmt->execute();
$users_voted = $stmt->fetchColumn(0);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <!--
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css'>
  -->
  <?php include "includes/headinfo.php"; ?>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

  <div id="main">
    <div class="respContainer">

    <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>

    <h1>Statistieken</h1>
    <h2>Verdenkingen</h2>
    <ul id="tabs-swipe-demo" class="tabs">
      <li class="tab col s3"><a class="active" onclick="setIndicator('left')" href="#swipe-1">Vrienden</a></li>
      <li class="tab col s3"><a onclick="setIndicator('right')" href="#swipe-2">Iedereen</a></li>
      <div id="thecooler_indicator"></div>
    </ul>
    <div class="slide-container">
      <div class="slide-wrapper">
        <!-- STATISTICS FOLLOWED USERS -->
        <div id="swipe-1">
          <?php if(!empty($scores_friends)): ?>
            <?php $i = 0; foreach($scores_friends as $score): ?>
              <?php 
              if ($score['Status'] == 1) {
                $percentCalc = round(($score["TotalScore"] / $total_voted_friends) * 100, 2);
                $percentScore = explode(".", $percentCalc);
                ?>
                <div class="status">
                  <p><?php echo $score['Name']; ?> - <span class="percent"><?=$percentScore[0]; ?><span class="smaller">.<?=$percentScore[1]; ?></span>%</span></p>
                </div>
                <div class="meter">
                  <span style="width: <?=$percentScore[0]; ?>%"></span>
                </div>
                <?php
              }
              ?>
            <?php $i++; endforeach; ?>

            <?php $i = 0; foreach($scores_friends as $score): ?>
              <?php 
              if ($score['Status'] == 0) {
                $percentCalc = round(($score["TotalScore"] / $total_voted_friends) * 100, 2);
                $percentScore = explode(".", $percentCalc);
                ?>
                <div class="status">
                  <p><?php echo $score['Name']; ?> - <span class="percent isOut2"><?=$percentScore[0]; ?><span class="smaller">.<?=$percentScore[1]; ?></span>%</span></p>
                </div>
                <div class="meter">
                  <span style="width: <?=$percentScore[0]; ?>%"></span>
                </div>
                <?php
              }
              ?>
            <?php $i++; endforeach; ?>
          <?php else: ?>
          <h2>Er zijn nog geen vrienden die hebben gestemd.</h2>
          <?php endif; ?>
        </div>
        
        <!-- STATISTICS ALL USERS -->
        <div id="swipe-2">

        <?php $i = 0; foreach($scores_all as $score): ?>
              <?php 
              if ($score['Status'] == 1) {
                $percentCalc = round(($score["TotalScore"] / $total_voted_all) * 100, 2);
                $percentScore = explode(".", $percentCalc);
                ?>
                <div class="status">
                  <p><?php echo $score['Name']; ?> - <span class="percent"><?=$percentScore[0]; ?><span class="smaller">.<?=$percentScore[1]; ?></span>%</span></p>
                </div>
                <div class="meter">
                  <span style="width: <?=$percentScore[0]; ?>%"></span>
                </div>
                <?php
              }
              ?>
            <?php $i++; endforeach; ?>
            
            <?php $i = 0; foreach($scores_all as $score): ?>
              <?php 
              if ($score['Status'] == 0) {
                $percentCalc = round(($score['SUM(Score)'] / $total_voted_all) * 100, 2);
                $percentScore = explode(".", $percentCalc);
                ?>
                <div class="status">
                  <p><?php echo $score['Name']; ?> - <span class="percent isOut2"><?=$percentScore[0]; ?><span class="smaller">.<?=$percentScore[1]; ?></span>%</span></p>
                </div>
                <div class="meter">
                  <span style="width: <?=$percentScore[0]; ?>%"></span>
                </div>
                <?php
              }
              ?>
            <?php $i++; endforeach; ?>
        </div>

      </div>
    </div>
      <p class="example"><?=$users_voted?> <?php if ($users_voted == 1) {echo "mollenjager heeft";}else{echo "mollenjagers hebben";} ?>  gestemd deze week.</p>

    </div>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js'></script>

</body>
</html>
