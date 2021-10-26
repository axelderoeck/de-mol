<?php

require_once("includes/phpdefault.php");

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

  <div class="displayList" id="main">
    <div class="respContainer">
      <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
      <h1>Mijn Molboek</h1>
      <h2>Jouw meest gespendeerde punten:</h2>
      <?php
      // Select all the scores the user has on the candidates and sort them from high to low
      $stmt = $pdo->prepare('SELECT table_Scores.Score, table_Candidates.Name, table_Candidates.Status
                            FROM table_Users
                            LEFT JOIN table_Scores
                            ON table_Users.Id = table_Scores.UserId
                            LEFT JOIN table_Candidates
                            ON table_Candidates.Id = table_Scores.CandidateId
                            WHERE table_Users.Id = ?
                            ORDER BY table_Scores.score DESC');
      $stmt->execute([ $_SESSION["Id"] ]);
      $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Loop through all scores
      if(!empty($candidates)):
        $i = 0; foreach($candidates as $candidate): ?>
          <div style="animation-delay: <?=$i/4;?>s;" class="displayItem <?=$candidate['Status'] == 0 ? 'isOut' : '';?>">
            <div class="wrapper">
              <div class="div1">
                <img src="img/kandidaten/small/<?=$candidate['Name'];?>.jpg" alt="">
              </div>
              <div class="div2">
                <span class="displayItemName"><?=$candidate['Name'];?></span>
                <br>
                <br>
                <br>
                <span class="displayItemNumber"><?=$candidate['Score'];?></span>
              </div>
            </div>
          </div>
        <?php $i++; endforeach; ?>
      <?php else: ?>
        <h2>Je hebt nog niet gestemd.</h2>  
      <?php endif; ?>
      
    </div>
 </div>

  <!-- JavaScript -->
  <script type="text/javascript" src="js/scripts.js"></script>
</body>
</html>
