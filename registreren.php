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

  <?php

    mysql_select_db('u939917173_demol');

    $query = "SELECT Id, Naam, alina
    FROM table_Users";

    $result = $dbconn->query($query);

    while($row = mysql_fetch_array($result)){   //Creates a loop to loop through results
      echo "<p>" . $row['Naam'] . $row['alina'] . "</p>";  //$row['index'] the index here is a field name
      }

  ?>

    <p><?php echo $Id; ?></p>
    <p><?php echo $Naam; ?></p>
    <p><?php echo $alina; ?></p>
    <!-- JavaScript -->
    <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>