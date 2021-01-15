<?php 

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

mysqli_close($dbconn);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php include "includes/headinfo.php"; ?>
</head>
<body>
 <?php include "includes/navigation.php"; ?>
 
 <div class="rangList">
    <h1>Ranglijst</h1>

    <?php

        $sql = "SELECT table_Users.Naam, table_Scores.Score
        FROM table_Users
        LEFT JOIN table_Scores
        ON table_Users.Naam = table_Scores.Naam
        LEFT JOIN table_Kandidaten
        ON table_Kandidaten.Identifier = table_Scores.Identifier
        WHERE table_Kandidaten.Identifier = 'person1'
        ORDER BY table_Scores.score DESC";
        if($result = mysqli_query($dbconn, $sql)){
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result)){
                    ?>
                    <div class="rangItem"> 
                        <p class="rangItemName"><?php echo $row['Naam']; ?></p>
                        <p class="rangItemNumber"><?php echo $row['Score']; ?></p>
                    </div>
                    <?php
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
  
    <!-- JavaScript -->
    <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>