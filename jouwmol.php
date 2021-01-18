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

 <div class="displayList">

    <h1>Mijn Molboek</h1>
    <p style="text-align: center">Jouw meest gespendeerde punten:</p>
        <?php
            $id = $_SESSION["Id"];

            $sql = "SELECT table_Scores.Score, table_Kandidaten.Naam, table_Kandidaten.Visibility
            FROM table_Users
            LEFT JOIN table_Scores
            ON table_Users.Naam = table_Scores.Naam
            LEFT JOIN table_Kandidaten
            ON table_Kandidaten.Identifier = table_Scores.Identifier
            WHERE table_Users.Id = $id
            ORDER BY table_Scores.score DESC";
            if($result = mysqli_query($dbconn, $sql)){
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_array($result)){
                        if ($row['Visibility'] == 'out' ) {
                          $out = "isOut";
                        }else {
                          $out = "";
                        } ?>
                        <div class="displayItem <?php echo $out ?>">
                            <p class="displayItemName"><?php echo $row['Naam']; ?></p>
                            <p class="displayItemNumber"><?php echo $row['Score']; ?></p>
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

    <?php mysqli_close($dbconn); ?>
</body>
</html>
