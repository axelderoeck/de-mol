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
  
  <div class="infoDiv">
        <h1>WIE IS DE <span>MOL</span> ?</h1>
        <p><span>Swipe</span> tussen de kandidaten en <span>stem</span>.</p>
  </div>
  
  <form id="deMolForm"></form>

  <div class="swiper-container">
    <div id="carousel" class="swiper-wrapper">
   
      <!-- dynamische items -->
         
    </div>
  </div>
    
  <div class="submitDiv">
    <input form="deMolForm" class="formSubmitBtn" type="submit" value="Inzenden">
  </div>

  <!-- JavaScript --> 
  <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>