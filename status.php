<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

$id = $_SESSION["Id"];

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

$getMostVoted = "SELECT Naam, SUM(Score), visibility
FROM table_Scores
LEFT JOIN table_Kandidaten
ON table_Scores.Identifier = table_Kandidaten.Identifier
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

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

  <div id="main">
    <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>

    <h1>Statistieken</h1>
    <h2>Verdenkingen</h2>
      <?php
          if($result = mysqli_query($dbconn, $getMostVoted)){
              if(mysqli_num_rows($result) > 0){
                  while($row = mysqli_fetch_array($result)){
                    $percentCalc = round(($row['SUM(Score)'] / $totalVoted) * 100, 2);
                    $percentScore = explode(".", $percentCalc);
                    if ($row['Visibility'] == 'out') {
                      $outClass = 'class="isOut"';
                      $outClass2 = "isOut2";
                    }else{
                      $outClass = "";
                      $outClass2 = "";
                    }
                    ?>
                    <div class="status">
                      <p><?php echo $row['Naam']; ?> - <span class="percent <?php echo $outClass2; ?>"><?php echo $percentScore[0]; ?>%<span class="smaller <?php echo $outClass2; ?>">.<?php echo $percentScore[1]; ?></span></span></p>
                    </div>
                    <div class="meter">
                      <span <?php echo $outClass; ?> style="width: <?php echo $percentScore[0]; ?>%"></span>
                    </div>
                    <?php
                  }
                  // Free result set
                  mysqli_free_result($result);
              }
          }
      ?>
      <p class="example"><?php echo $amountVoted; ?> <?php if ($amountVoted == 1) {echo "mollenjager heeft";}else{echo "mollenjagers hebben";} ?>  gestemd deze week.</p>

  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
  <?php mysqli_close($dbconn); ?>
</body>
</html>
