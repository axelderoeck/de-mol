<?php 

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

  <?php

    $sql = "SELECT * FROM table_Users";
    if($result = mysqli_query($dbconn, $sql)){
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){
              echo "<p>" . $row['Id'] . "</p>";
              echo "<p>" . $row['Naam'] . "</p>";
              echo "<p>" . $row['alina'] . "</p>";
              echo "<p>" . $row['bart'] . "</p>";
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
    <!-- JavaScript -->
    <script type="text/javascript" src="js/scripts.js"></script>

    <?php mysqli_close($dbconn); ?>

</body>
</html>