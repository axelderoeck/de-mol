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
 
 <div class="displayList">
    <h1>Jouw Mol</h1>
    <p style="text-align: center">De meest gespendeerde punten:</p>
     <div class="displayItem">
         <p class="displayItemName">Naam</p>
         <p class="displayItemNumber">15</p>
     </div>
     <div class="displayItem">
         <p class="displayItemName">Naam</p>
         <p class="displayItemNumber">13</p>
     </div>
     <div class="displayItem">
         <p class="displayItemName">Naam</p>
         <p class="displayItemNumber">10</p>
     </div>
     <div class="displayItem">
         <p class="displayItemName">Naam</p>
         <p class="displayItemNumber">8</p>
     </div>
     <div class="displayItem">
         <p class="displayItemName">Naam</p>
         <p class="displayItemNumber">4</p>
     </div>
     <div class="displayItem">
         <p class="displayItemName">Naam</p>
         <p class="displayItemNumber">1</p>
     </div>
     <div class="displayItem">
         <p class="displayItemName">Naam</p>
         <p class="displayItemNumber">0</p>
     </div>
     <div class="displayItem">
         <p class="displayItemName">Naam</p>
         <p class="displayItemNumber">0</p>
     </div>
     <div class="displayItem">
         <p class="displayItemName">Naam</p>
         <p class="displayItemNumber">0</p>
     </div>
     <div class="displayItem">
         <p class="displayItemName">Naam</p>
         <p class="displayItemNumber">0</p>
     </div>
 </div>
  
    <!-- JavaScript -->
    <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>