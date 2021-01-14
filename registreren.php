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
  
    //query ledenoverzicht
    $qrySelectLid = `SELECT Id, Naam, alina
    FROM table_Users 
    WHERE Naam = "Joske"
    `;

    //statement aanmaken
    if ($stmtSelectLid = mysqli_prepare($dbconn, $qrySelectLid)){
      mysqli_stmt_bind_param($stmtSelectLid, "sss", $Id, $Naam, $alina);
      mysqli_stmt_execute($stmtSelectLid);
      //mysqli_stmt_bind_result($stmtSelectLid, $lidID);
      mysqli_stmt_fetch($stmtSelectLid);
    }
  ?>

    <p><?php echo $Id; ?></p>
    <p><?php echo $Naam; ?></p>
    <p><?php echo $alina; ?></p>
    <!-- JavaScript -->
    <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>