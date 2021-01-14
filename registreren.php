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
    $qrySelectLeden = `SELECT Id, Naam, alina
    FROM table_Users 
    WHERE Naam = "Joske"
    `;

    //statement aanmaken
    if ($stmtSelectLeden = mysqli_prepare($dbconn, $qrySelectLeden)){
    //query uitvoeren
    mysqli_stmt_execute($stmtSelectLeden);
    //resultaat binden aan lokale variabelen
    mysqli_stmt_bind_result($stmtSelectLeden, $Id, $Naam, $alina);
    //resultaten opslaan
    mysqli_stmt_store_result($stmtSelectLeden);
    }

    echo $Id;
    echo $Naam;
    echo $alina;
  ?>

    <p><?php $Id ?></p>
    <p><?php $Naam ?></p>
    <p><?php $alina ?></p>
    <!-- JavaScript -->
    <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>