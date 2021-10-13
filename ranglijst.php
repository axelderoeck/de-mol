<?php

require_once("includes/phpdefault.php");

$stmt = $pdo->prepare('SELECT table_Users.Gebruikersnaam, table_Scores.Score, table_Users.Id
FROM table_Users
LEFT JOIN table_Scores
ON table_Users.Id = table_Scores.UserId
RIGHT JOIN table_Kandidaten
ON table_Kandidaten.Identifier = table_Scores.Identifier
WHERE table_Kandidaten.Mol = 1
AND table_Users.Id IN
(SELECT table_Users.Id
    FROM table_Users
    LEFT JOIN table_Friends
    ON table_Users.Id = table_Friends.IsFriendsWithId
    WHERE table_Friends.Id = ?)
ORDER BY table_Scores.Score DESC');
$stmt->execute([ $_SESSION["Id"] ]);
$scores_friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT table_Users.Gebruikersnaam, table_Scores.Score, table_Users.Id
FROM table_Users
LEFT JOIN table_Scores
ON table_Users.Id = table_Scores.UserId
LEFT JOIN table_Kandidaten
ON table_Kandidaten.Identifier = table_Scores.Identifier
WHERE table_Kandidaten.Mol = 1
ORDER BY table_Scores.Score DESC
LIMIT 20'); // TODO: variable in prepared statement
$stmt->execute();
$scores_all = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php include "includes/headinfo.php"; ?>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

   <div class="rangList" id="main">
     <div class="respContainer">

     <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
      <h1>Scores</h1>
      <ul id="tabs-swipe-demo" class="tabs">
        <li class="tab col s3"><a class="active" onclick="setIndicator('left')" href="#swipe-1">Volgend</a></li>
        <li class="tab col s3"><a onclick="setIndicator('right')" href="#swipe-2">Top <?php echo $top_aantal; ?></a></li>
        <div id="thecooler_indicator"></div>
      </ul>
      <div class="slide-container">
        <div class="slide-wrapper">
          <div id="swipe-1">
            <?php if(!empty($scores_friends)): ?>
              <?php $i = 1; foreach($scores_friends as $score): ?>
                <a href="profiel.php?u=<?=$score['Id']?>">
                  <div style="animation-delay: <?=$i/4; ?>s;" class="rangItem <?php if($score['Id'] == $_SESSION["Id"]){echo "selected";} ?>">
                    <p>
                      <span class="rangItemScore"><?=$score['Score']?></span>
                      <?=$score['Gebruikersnaam']; ?>
                    </p>
                  </div>
                </a>
              <?php $i++; endforeach; ?>
            <?php else: ?>
              <h2>Er is nog geen mol bekend of je hebt geen vrienden.</h2>
            <?php endif; ?>
          </div>

          <div id="swipe-2">
            <?php if(!empty($scores_all)): ?>
              <?php $i = 1; foreach($scores_all as $score): ?>
                <a href="profiel.php?u=<?=$score['Id']?>">
                  <div style="animation-delay: <?=$i/4; ?>s;" class="rangItem <?php if($score['Id'] == $_SESSION["Id"]){echo "selected";} ?>">
                    <p>
                      <span class="rangItemScore"><?=$score['Score']?></span>
                      <?=$score['Gebruikersnaam']; ?>
                    </p>
                  </div>
                </a>
              <?php $i++; endforeach; ?>
            <?php else: ?>
              <h2>Er is nog geen mol bekend.</h2>
            <?php endif; ?>
          </div>
        </div>
      </div>
       </div>
   </div>

   <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
   <script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js'></script>

    <!-- JavaScript -->
    <script type="text/javascript" src="js/scripts.js" defer></script>
</body>
</html>
