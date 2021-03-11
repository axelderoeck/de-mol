<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

$id = $_SESSION["Id"];

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

$topAmount = 10;

$selectScoreListAll = "SELECT table_Users.Gebruikersnaam, table_Scores.Score
FROM table_Users
LEFT JOIN table_Scores
ON table_Users.Id = table_Scores.UserId
LEFT JOIN table_Kandidaten
ON table_Kandidaten.Identifier = table_Scores.Identifier
WHERE table_Kandidaten.Identifier = '$demol'
ORDER BY table_Scores.score DESC
LIMIT $topAmount";

$selectScoreList = "SELECT table_Users.Naam, table_Scores.Score
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
</head>
<body>
 <?php include "includes/navigation.php"; ?>

 <?php if ($demol != 'onbekend') {
   ?>
   <div class="rangList" id="main">
     <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
      <h1>Scores</h1>
      <?php

          if($result = mysqli_query($dbconn, $selectScoreList)){
              if(mysqli_num_rows($result) > 0){
                $i = 1;
                  while($row = mysqli_fetch_array($result)){
                      ?>

                      <div style="animation-delay: <?php echo $i/4; ?>s;" class="rangItem">
                        <?php if ($i <= 3){ ?>
                          <img style="animation-delay: <?php echo $i+2; ?>s;" src="img/awards/place<?php echo $i; ?>.png" alt="">
                        <?php }; ?>
                          <p class="rangItemName"><?php echo $row['Naam']; ?></p>
                          <p class="rangItemNumber"><?php echo $row['Score']; ?></p>
                      </div>
                      <?php
                      $i++;
                  }
                  // Free result set
                  mysqli_free_result($result);
              } else{
                  echo "No records matching your query were found.";
              }
          } else{
              echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
          }

  ?>
   </div>
   <?php
 } else {
   ?>
   <div class="rangList" id="main">
      <h1>Ranglijst</h1>
      <p class="rangListSubTitle">Hier komen de punten wanneer de mol bekend is.</p>
    </div>
   <?php
 } ?>


    <!-- JavaScript -->
    <script type="text/javascript" src="js/scripts.js"></script>

    <?php mysqli_close($dbconn); ?>
</body>
</html>
