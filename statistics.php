<?php

require_once("includes/phpdefault.php");

// Get all scores
$stmt = $pdo->prepare('SELECT Name, SUM(Score) AS TotalScore
FROM table_Scores
LEFT JOIN table_Candidates
ON table_Scores.CandidateId = table_Candidates.Id
WHERE table_Candidates.Status = 1
GROUP BY table_Scores.CandidateId
ORDER BY SUM(Score) DESC');
$stmt->execute();
$scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total voted points (for calculating percentages)
$stmt = $pdo->prepare('SELECT DISTINCT SUM(Score) FROM table_Scores');
$stmt->execute();
$total_voted = $stmt->fetchColumn(0);

?>

<?php include "includes/header.php"; ?>

      <h1>Statistieken</h1>
      <h2>Verdenkingen</h2>
      
      <div>
        <?php if(!empty($scores)): ?>
          <?php foreach($scores as $score): ?>
            <?php 
              $percentCalc = round(($score["TotalScore"] / $total_voted) * 100, 2);
              $percentScore = explode(".", $percentCalc);
              ?>
              <div class="status">
                <p><?=$score['Name']?> - <span class="percent"><?=$percentScore[0]; ?><span class="smaller">.<?=$percentScore[1]; ?></span>%</span></p>
              </div>
              <div class="meter">
                <span style="width: <?=$percentScore[0]; ?>%"></span>
              </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="text-align: center !important;">Er is nog niet gestemd deze week</p>
        <?php endif; ?>
      </div>
          
<?php include "includes/footer.php"; ?>
