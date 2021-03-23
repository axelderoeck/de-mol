<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php include "includes/headinfo.php"; ?>
</head>
<body>
    <?php include "includes/navigation.php"; ?>

 <div class="displayList" id="main">
   <div class="respContainer">
   <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <h1>Mijn Molboek</h1>
    <h2>Jouw meest gespendeerde punten:</h2>
        <?php
            $id = $_SESSION["Id"];

            $sql = "SELECT table_Scores.Score, table_Kandidaten.Naam, table_Kandidaten.Visibility
            FROM table_Users
            LEFT JOIN table_Scores
            ON table_Users.Id = table_Scores.UserId
            LEFT JOIN table_Kandidaten
            ON table_Kandidaten.Identifier = table_Scores.Identifier
            WHERE table_Users.Id = $id
            ORDER BY table_Scores.score DESC";

            if($result = mysqli_query($dbconn, $sql)){
                if(mysqli_num_rows($result) > 0){
                    $i = 0;
                    while($row = mysqli_fetch_array($result)){
                        if ($row['Visibility'] == 'out' ) {
                          $out = "isOut";
                        }else {
                          $out = "";
                        } ?>
                        <div style="animation-delay: <?php echo $i/4; ?>s;" class="displayItem <?php echo $out ?>">
                          <div class="wrapper">
                            <div class="div1">
                              <img src="img/kandidaten/small/<?php echo $row['Naam']; ?>.jpg" alt="">
                            </div>
                            <div class="div2">
                              <span class="displayItemName"><?php echo $row['Naam']; ?></span>
                              <br>
                              <br>
                              <br>
                              <span class="displayItemNumber"><?php echo $row['Score']; ?></span>
                            </div>
                          </div>
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
 </div>

    <!-- JavaScript -->
    <script type="text/javascript" src="js/scripts.js"></script>

    <?php mysqli_close($dbconn); ?>
</body>
</html>
