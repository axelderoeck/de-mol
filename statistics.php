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

// Get the amount of users that voted
$stmt = $pdo->prepare('SELECT COUNT(Id) FROM table_Users WHERE Voted = 1');
$stmt->execute();
$users_voted = $stmt->fetchColumn(0);

?>

<?php include "includes/header.php"; ?>

      <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>

      <h1>Statistieken</h1>
      <h2>Verdenkingen</h2>
      
      <div>
        <?php $i = 0; foreach($scores as $score): ?>
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
        <?php $i++; endforeach; ?>
      </div>
      
      <p class="example"><?=$users_voted?> <?php if ($users_voted == 1) {echo "mollenjager heeft";}else{echo "mollenjagers hebben";} ?>  gestemd deze week.</p>
    
<?php include "includes/footer.php"; ?>
