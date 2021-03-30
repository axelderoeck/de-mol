<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

$id = $_SESSION["Id"];

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

$getMostVoted = "SELECT Naam, SUM(Score), Visibility
FROM table_Scores
LEFT JOIN table_Kandidaten
ON table_Scores.Identifier = table_Kandidaten.Identifier
GROUP BY table_Scores.Identifier
ORDER BY SUM(Score) DESC";

$getMostVotedFollowers = "SELECT Naam, SUM(Score), Visibility
FROM table_Scores
LEFT JOIN table_Kandidaten
ON table_Scores.Identifier = table_Kandidaten.Identifier
WHERE UserId IN (SELECT UserIsFollowingId FROM table_Followers WHERE UserId = '$id')
GROUP BY table_Scores.Identifier
ORDER BY SUM(Score) DESC";

$getAmountVoted = "SELECT COUNT(Id) FROM table_Users WHERE Voted = 1";
if ($stmtGetAmountVoted = mysqli_prepare($dbconn, $getAmountVoted)){
    mysqli_stmt_execute($stmtGetAmountVoted);
    mysqli_stmt_bind_result($stmtGetAmountVoted, $amountVoted);
    mysqli_stmt_store_result($stmtGetAmountVoted);
}
mysqli_stmt_fetch($stmtGetAmountVoted);

$getTotalVoted = "SELECT DISTINCT SUM(Score) FROM table_Scores";
if ($stmtGetTotalVoted = mysqli_prepare($dbconn, $getTotalVoted)){
    mysqli_stmt_execute($stmtGetTotalVoted);
    mysqli_stmt_bind_result($stmtGetTotalVoted, $totalVoted);
    mysqli_stmt_store_result($stmtGetTotalVoted);
}
mysqli_stmt_fetch($stmtGetTotalVoted);

$getTotalVotedFollowers = "SELECT DISTINCT SUM(Score) FROM table_Scores
WHERE UserId IN (SELECT UserIsFollowingId FROM table_Followers WHERE UserId = '$id')
";
if ($stmtGetTotalVotedFollowers = mysqli_prepare($dbconn, $getTotalVotedFollowers)){
    mysqli_stmt_execute($stmtGetTotalVotedFollowers);
    mysqli_stmt_bind_result($stmtGetTotalVotedFollowers, $totalVotedFollowers);
    mysqli_stmt_store_result($stmtGetTotalVotedFollowers);
}
mysqli_stmt_fetch($stmtGetTotalVotedFollowers);

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
    <!--
    <ul id="tabs-swipe-demo" class="tabs">
      <li class="tab col s3"><a class="active" href="#swipe-2">Volgend</a></li>
      <li class="tab col s3"><a href="#swipe-1">Iedereen</a></li>
    </ul> -->
    <div class="slide-container">
      <div class="slide-wrapper">
        <div id="swipe-1">
      <?php
          if($result = mysqli_query($dbconn, $getMostVoted)){
              if(mysqli_num_rows($result) > 0){
                  while($row = mysqli_fetch_array($result)){
                    if ($row['Visibility'] != 'out') {
                      $percentCalc = round(($row['SUM(Score)'] / $totalVoted) * 100, 2);
                      $percentScore = explode(".", $percentCalc);
                      ?>
                      <div class="status">
                        <p><?php echo $row['Naam']; ?> - <span class="percent"><?php echo $percentScore[0]; ?><span class="smaller">.<?php echo $percentScore[1]; ?></span>%</span></p>
                      </div>
                      <div class="meter">
                        <span style="width: <?php echo $percentScore[0]; ?>%"></span>
                      </div>
                      <?php
                    }
                  }
                  // Free result set
                  mysqli_free_result($result);
              }
          }
          if($result = mysqli_query($dbconn, $getMostVoted)){
              if(mysqli_num_rows($result) > 0){
                  while($row = mysqli_fetch_array($result)){
                    if ($row['Visibility'] == 'out') {
                      $percentCalc = round(($row['SUM(Score)'] / $totalVoted) * 100, 2);
                      $percentScore = explode(".", $percentCalc);
                      ?>
                      <div class="status">
                        <p><?php echo $row['Naam']; ?> - <span class="percent isOut2"><?php echo $percentScore[0]; ?><span class="smaller isOut2">.<?php echo $percentScore[1]; ?></span>%</span></p>
                      </div>
                      <div class="meter">
                        <span class="isOut" style="width: <?php echo $percentScore[0]; ?>%"></span>
                      </div>
                      <?php
                    }
                  }
                  // Free result set
                  mysqli_free_result($result);
              }
          }
      ?>
        </div>
        <!--
        <div id="swipe-2">

        </div>
      -->
      </div>
    </div>
      <p class="example"><?php echo $amountVoted; ?> <?php if ($amountVoted == 1) {echo "mollenjager heeft";}else{echo "mollenjagers hebben";} ?>  gestemd deze week.</p>

    </div>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js'></script>

  <?php mysqli_close($dbconn); ?>
</body>
</html>
