<?php

require_once("includes/phpdefault.php");

$stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT SUM(Score) FROM table_Scores WHERE UserId = ? GROUP BY UserId');
$stmt->execute([ $_SESSION["Id"] ]);
$votedPoints = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT SUM(Score) FROM table_Scores
LEFT JOIN table_Candidates
ON table_Scores.CandidateId = table_Candidates.Id
WHERE table_Candidates.Status = ? AND UserId = ?
GROUP BY UserId');
$stmt->execute([ 0, $_SESSION["Id"] ]);
$lostPoints = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT * FROM table_Scores
LEFT JOIN table_Candidates
ON table_Scores.CandidateId = table_Candidates.Id
WHERE UserId = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate the new score
$newScore = $account["Score"];
foreach($scores as $score){
  if($score["Status"] == 1 && $score["Score"] > 0){
    $multiplier = 2;
    $newScore += ($score["Score"] * $multiplier); 
  }
  if($score["Status"] == 0 && $score["Score"] > 0){
    $newScore -= $score["Score"];
  }
}

// Only execute update score query if it's users first time opening the screen
if($account["SeenResults"] == 0){


  // Set "SeenResults" to 1 after the query so it will only execute once
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <script>
    window.addEventListener('load', function() {
        <?php 
          if($account["SeenResults"] == 0):
          // Check if user has red screen
          $redScreen = false;
          foreach($scores as $score){
            if($score["Status"] == 0 && $score["Score"] > 0){
              $redScreen = true;
              break;
            }
          }
          if($redScreen == true): ?>
            showScreen('red');
          <?php else: ?>
            showScreen('green');
          <?php endif; ?>
        <?php endif; ?>
    });
  </script>
</head>
<body>

<div class="homeScreen" id="main">

  <?php
  // IDEA: later
  //$file = "demol_logo_geen_tekst_rood.png";
  //$file = "demol_logo_geen_tekst_groen.png";
  // replace
  ?>

  <div class="respContainer" style="height: 100%;">

  <form name="userSeenResultsConfirm" method="post"></form>

  <?php if($redScreen == true): ?>
  <div id="screenRed" class="screen">
    <img src="img/assets/demol_logo_geen_tekst_rood.png" alt="rood logo van de mol">
  <?php else: ?>
  <div id="screenGreen" class="screen">
    <img src="img/assets/demol_logo_geen_tekst_groen.png" alt="groen logo van de mol">
  <?php endif; ?>
    <h2>Resultaat</h2>
    <p>Tekst.</p>
    <?php if($account["Score"] > 0): ?>
    <p>Niet gebruikte punten: <?=$account["Score"]?></p>
    <?php endif; ?>
    <table>
    <?php foreach($scores as $score): ?>
      <?php if($score["Status"] == 1 && $score["Score"] > 0): ?>
        <tr>
          <td><?=$score["Name"]?></td>
          <td><?=$score["Score"]?></td>
          <td>*<?=$multiplier?></td>
          <td>= <?=$score["Score"] *2?></td>
        </tr>
      <?php endif; ?>
      <?php if($score["Status"] == 0 && $score["Score"] > 0): ?>
        <tr>
          <td><?=$score["Name"]?></td>
          <td><?=$score["Score"]?></td>
          <td>-<?=$score["Score"]?></td>
          <td>= <?=$score["Score"] - $score["Score"]?></td>
        </tr>
      <?php endif; ?>
    <?php endforeach; ?>
        <tr>
          <td>Score</td>
          <td></td>
          <td></td>
          <td><?=$newScore?></td>
        </tr>
    </table>
    <input form="userSeenResultsConfirm" type="submit" name="confirmSeenResults" value="Ga door">
  </div>

    <input type="text" value="<?=$account["Username"]?>">
  </div>
</div>

  <!-- JavaScript -->
  <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>
