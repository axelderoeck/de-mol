<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

$id = $_SESSION["Id"];

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}
if ($_SESSION["Admin"] != 1) {
  header('location:index.php');
}

$getMostVoted = "SELECT Naam, SUM(Score)
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

    <h1>Status</h1>
    <h2><span><?php echo $amountVoted; ?></span> <?php if ($amountVoted == 1) {echo "heeft";}else{echo "hebben";} ?>  gestemd deze week.</h2>
    <table>
      <tr>
        <th>Naam</th>
        <th>Score</th>
        <th>%</th>
      </tr>
      <?php
          if($result = mysqli_query($dbconn, $getMostVoted)){
              if(mysqli_num_rows($result) > 0){
                  while($row = mysqli_fetch_array($result)){
                    $percentScore = ceil(($row['SUM(Score)'] / $totalVoted) * 100);
                    ?>
                      <tr>
                        <td><?php echo $row['Naam']; ?></td>
                        <td><?php echo $row['SUM(Score)']; ?></td>
                        <td><?php echo $percentScore; ?>%</td>
                      </tr>
                    <?php
                  }
                  // Free result set
                  mysqli_free_result($result);
              }
          }
      ?>
    </table>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
  <?php mysqli_close($dbconn); ?>
</body>
</html>
