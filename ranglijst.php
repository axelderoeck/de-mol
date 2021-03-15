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
  <!-- externe stylesheet maakt problemen -->
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css'>
    <?php include "includes/headinfo.php"; ?>

    <?php
    $selectScoreListAll = "SELECT table_Users.Gebruikersnaam, table_Scores.Score
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
     <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
      <h1>Scores</h1>
      <ul id="tabs-swipe-demo" class="tabs">
        <li class="tab col s3"><a class="active" href="#swipe-1">Volgend</a></li>
        <li class="tab col s3"><a href="#swipe-2">Top <?php echo $top_aantal; ?></a></li>
      </ul>
      <div class="slide-container">
        <div class="slide-wrapper">
          <div id="swipe-1">
<?php
if($result = mysqli_query($dbconn, $selectScoreList)){
if(mysqli_num_rows($result) > 0){
$i = 1;
while($row = mysqli_fetch_array($result)){?>

                            <div style="animation-delay: <?php echo $i/4; ?>s;" class="rangItem">
                              <p>
                                <span class="rangItemScore"><?php echo $row['Score']; ?></span>
                                <?php echo $row['Naam']; ?>
                              </p>
                            </div>

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

                            <div style="animation-delay: <?php echo $i/4; ?>s;" class="rangItem">
                              <p>
                                <span><?php echo $row['Score']; ?></span>
                                <?php echo $row['Gebruikersnaam']; ?>
                              </p>
                            </div>

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

    <!-- JavaScript -->
    <script type="text/javascript" src="js/scripts.js"></script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js'></script>

    <?php mysqli_close($dbconn); ?>
</body>
</html>
