<?php

require_once("includes/phpdefault.php");

/*
$stmt = $pdo->prepare('');
$stmt->execute([  ]);
$followedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
*/

//$_SESSION["Id"];

$qrySelectMol = "SELECT demol
FROM table_Mol";

//statement aanmaken
if ($stmtSelectMol = mysqli_prepare($dbconn, $qrySelectMol)){
    //query uitvoeren
    mysqli_stmt_execute($stmtSelectMol);
    //resultaat binden aan lokale variabelen
    mysqli_stmt_bind_result($stmtSelectMol, $demol);
    //resultaten opslaan
    mysqli_stmt_store_result($stmtSelectMol);
}

mysqli_stmt_fetch($stmtSelectMol);

$selectScoreList = "SELECT table_Users.Naam, table_Scores.Score, table_Users.Id
FROM table_Users
LEFT JOIN table_Scores
ON table_Users.Id = table_Scores.UserId
RIGHT JOIN table_Kandidaten
ON table_Kandidaten.Identifier = table_Scores.Identifier
WHERE table_Kandidaten.Identifier = '$demol'
AND table_Users.Id IN
(SELECT Id
    FROM table_Users
    LEFT JOIN table_Followers
    ON table_Users.Id = table_Followers.UserIsFollowingId
    WHERE table_Followers.UserId = '$id')
ORDER BY table_Scores.score DESC";

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php include "includes/headinfo.php"; ?>

    <?php
    $selectScoreListAll = "SELECT table_Users.Gebruikersnaam, table_Scores.Score, table_Users.Id
    FROM table_Users
    LEFT JOIN table_Scores
    ON table_Users.Id = table_Scores.UserId
    LEFT JOIN table_Kandidaten
    ON table_Kandidaten.Identifier = table_Scores.Identifier
    WHERE table_Kandidaten.Identifier = '$demol'
    ORDER BY table_Scores.score DESC
    LIMIT $top_aantal";
    ?>
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
<?php
if($result = mysqli_query($dbconn, $selectScoreList)){
if(mysqli_num_rows($result) > 0){
$i = 1;
while($row = mysqli_fetch_array($result)){?>
<a href="profiel.php?user=<?php echo $row['Id']; ?>">
  <div style="animation-delay: <?php echo $i/4; ?>s;" class="rangItem <?php if($row['Id'] == $id){echo "selected";} ?>">
    <p>
      <span class="rangItemScore"><?php echo $row['Score']; ?></span>
      <?php echo $row['Naam']; ?>
    </p>
  </div>
</a>
<?php
$i++;
}
mysqli_free_result($result);
}
}
?>
          </div>
          <div id="swipe-2">
<?php
if($result = mysqli_query($dbconn, $selectScoreListAll)){
if(mysqli_num_rows($result) > 0){
$i = 1;
while($row = mysqli_fetch_array($result)){?>
<a href="profiel.php?user=<?php echo $row['Id']; ?>">
  <div style="animation-delay: <?php echo $i/4; ?>s;" class="rangItem <?php if($row['Id'] == $id){echo "selected";} ?>">
    <p>
      <span><?php echo $row['Score']; ?></span>
      <?php echo $row['Gebruikersnaam']; ?>
    </p>
  </div>
</a>
<?php
$i++;
}
mysqli_free_result($result);
}
}
?>
          </div>
        </div>
      </div>
       </div>
   </div>

   <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
   <script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js'></script>

    <!-- JavaScript -->
    <script type="text/javascript" src="js/scripts.js" defer></script>

    <?php mysqli_close($dbconn); ?>
</body>
</html>
