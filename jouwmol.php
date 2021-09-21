<?php

require_once("includes/phpdefault.php");

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

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
      $stmt = $pdo->prepare('SELECT table_Scores.Score, table_Kandidaten.Naam, table_Kandidaten.Visibility
                            FROM table_Users
                            LEFT JOIN table_Scores
                            ON table_Users.Id = table_Scores.UserId
                            LEFT JOIN table_Kandidaten
                            ON table_Kandidaten.Identifier = table_Scores.Identifier
                            WHERE table_Users.Id = ?
                            ORDER BY table_Scores.score DESC');
      $stmt->execute([ $_SESSION["Id"] ]);
      $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);
      // Set iterable to 0
      $i = 0; 
      // Loop through all scores
      foreach($candidates as $candidate): ?>
        <div style="animation-delay: <?=$i/4;?>s;" class="displayItem <?=$candidate['Visibility'] == 'out' ? 'isOut' : '';?>">
          <div class="wrapper">
            <div class="div1">
              <img src="img/kandidaten/small/<?=$candidate['Naam'];?>.jpg" alt="">
            </div>
            <div class="div2">
              <span class="displayItemName"><?=$candidate['Naam'];?></span>
              <br>
              <br>
              <br>
              <span class="displayItemNumber"><?=$candidate['Score'];?></span>
            </div>
          </div>
        </div>
      <?php $i++; endforeach; ?>
    </div>
 </div>

  <!-- JavaScript -->
  <script type="text/javascript" src="js/scripts.js"></script>
</body>
</html>
