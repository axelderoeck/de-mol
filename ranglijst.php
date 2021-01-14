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
     <div class="rangItem"> 
         <p class="rangItemName">1. Naam</p>
         <p class="rangItemNumber">15</p>
     </div>
     <div class="rangItem">
         <p class="rangItemName">2. Naam</p>
         <p class="rangItemNumber">13</p>
     </div>
     <div class="rangItem">
         <p class="rangItemName">3. Naam</p>
         <p class="rangItemNumber">10</p>
     </div>
     <div class="rangItem">
         <p class="rangItemName">4. Naam</p>
         <p class="rangItemNumber">8</p>
     </div>
     <div class="rangItem">
         <p class="rangItemName">5. Naam</p>
         <p class="rangItemNumber">4</p>
     </div>
     <div class="rangItem">
         <p class="rangItemName">6. Naam</p>
         <p class="rangItemNumber">1</p>
     </div>
 </div>
  
    <!-- JavaScript -->
    <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>