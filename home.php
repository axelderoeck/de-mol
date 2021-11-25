<?php

require_once("includes/phpdefault.php");

$votetime = str_split(VOTE_HOUR, 2);

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

// Get the total voted points (for calculating percentages)
$stmt = $pdo->prepare('SELECT DISTINCT SUM(Score) FROM table_Scores');
$stmt->execute();
$total_voted_all = $stmt->fetchColumn(0);

// DEV - DELETE ON PROD
if (isset($_POST["resetVote"])){
  $stmt = $pdo->prepare('UPDATE table_Users SET Voted = 0 WHERE Id = ?');
  $stmt->execute([ $_SESSION["Id"] ]);

  $_SESSION["Voted"] = 0;
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <!-- <script data-name="BMC-Widget" data-cfasync="false" src="https://cdnjs.buymeacoffee.com/1.0.0/widget.prod.min.js" data-id="aksol" data-description="Support me on Buy me a coffee!" data-message="Ben je tevreden met mijn werk en wil je me steunen?" data-color="#5F7FFF" data-position="Right" data-x_margin="18" data-y_margin="18"></script> -->

  <?php include "includes/headinfo.php"; ?>
  <script>
    window.addEventListener('load', function() {
      <?php
      $begindate = new DateTime(SEASON_START);
      $enddate = new DateTime(SEASON_END);
      $now = new DateTime();

      if($begindate > $now) {
        ?>
        stemKnop("uit");
        infoTekst("Het <span>seizoen</span> is nog niet begonnen.");
        <?php
      }elseif($enddate < $now) {
        ?>
        stemKnop("uit");
        infoTekst("Het <span>seizoen</span> is voorbij. <br> <button type='submit' onclick='location.href = `scores.php`;'>Bekijk de scores</button>");
        <?php
      }elseif(date('D') == VOTE_DAY && date('Hi') < VOTE_HOUR) {
        ?>
        stemKnop("uit");
        infoTekst("Vanaf <?php echo $votetime[0] . ":" . $votetime[1]; ?>u kan je <span>stemmen</span>.");
        <?php
        // Reset has voted
        $stmt = $pdo->prepare('UPDATE table_Users SET Voted = 0');
        $stmt->execute();
      }else{
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
        <?php endif;
        if($account["Voted"] == 0) {
          ?>
          stemKnop("aan");
          infoTekst("Je hebt nog tot en met <span>zaterdag</span> om te stemmen <i class='fas fa-clock'></i>");
          <?php
        }elseif($account["Voted"] == 1) {
          ?>
          stemKnop("uit");
          infoTekst("Je hebt al <span>gestemd</span> <i class='fas fa-check'></i><br>Kom terug na de volgende aflevering!");
          <?php
        }
      }
      ?>

    })
  </script>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

<!-- <img id="screenGreen" src="img/assets/scherm_groen.png" alt="groen scherm van de mol">
<img id="screenRed" src="img/assets/scherm_rood.png" alt="rood scherm van de mol"> -->

<div class="homeScreen" id="main">

  <form name="userSeenResultsConfirm" method="post"></form>

  <?php
  // IDEA: later
  //$file = "demol_logo_geen_tekst_rood.png";
  //$file = "demol_logo_geen_tekst_groen.png";
  // replace
  ?>

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
    <?php if($redScreen == true): ?>
    <p>- <?=$lostPoints?></p>
    <?php endif; ?>
    <table>
    <?php $newScore = $account["Score"]; ?>
    <?php foreach($scores as $score): ?>
      <?php if($score["Status"] == 1 && $score["Score"] > 0): ?>
        <?php 
          $newScore += ($score["Score"] *2); 
          $percentCalc = round(($score["TotalScore"] / $total_voted_all) * 100, 2);
          $percentScore = explode(".", $percentCalc);
          $percentScore[0];
          $multiplier = 2;
        ?>
        <tr>
          <td><?=$score["Name"]?></td>
          <td><?=$score["Score"]?></td>
          <td>*<?=$multiplier?></td>
          <td>= <?=$score["Score"] *2?></td>
        </tr>
      <?php endif; ?>
    <?php endforeach; ?>
        <tr>
          <td>Nieuwe score</td>
          <td></td>
          <td></td>
          <td><?=$newScore?></td>
        </tr>
    </table>
    <input form="userSeenResultsConfirm" type="submit" name="confirmSeenResults" value="Ga door">
  </div>

  <div class="respContainer">
  
  <a href="profile.php?u=<?=$_SESSION["Id"]?>">
    <div class="userBox info">
      <span><?=$account["Username"]?></span><br>
      <span class="friendcode">#<?=$account["Friendcode"]?></span><br>
      <span><?=$votedPoints + $account["Score"]?> <i class="fas fa-fingerprint"></i></span>
    </div>
  </a>

  <h1>Dag <?=$account["Username"]?></h1>

  <div class="buttonsDiv">
    <a href="molboek.php"><i class="fas fa-fingerprint translucent"></i></a>
    <a href="info.php"><i class="fas fa-question-circle translucent"></i></a>
    <a href="friends.php"><i class="fas fa-users translucent"></i></a>
  </div>

  <div class="submitDiv">
    <button onclick="location.href = 'vote.php';" id="stemKnop" class="styledBtn" type="submit">Stemmen</button>
  </div>

  <!-- DEV - DELETE ON PROD -->
  <form name="resetVoteForm" method="POST" action="">
    <input type="submit" name="resetVote" value="(dev) Reset Vote status">
  </form>

  <h2 id="infoTekst"></h2>

  <p class="hiddenField">.- -. - .-. --- .--. --- -. -.-- -- .. .</p>

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
