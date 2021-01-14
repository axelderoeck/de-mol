<?php 

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

//mysqli_close($dbconn);

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
            echo "<table>";
                echo "<tr>";
                    echo "<th>id</th>";
                    echo "<th>first_name</th>";
                    echo "<th>last_name</th>";
                    echo "<th>email</th>";
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                    echo "<td>" . $row['Id'] . "</td>";
                    echo "<td>" . $row['Naam'] . "</td>";
                    echo "<td>" . $row['alina'] . "</td>";
                    echo "<td>" . $row['bart'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
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

</body>
</html>