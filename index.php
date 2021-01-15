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
  <script>
    window.addEventListener('load', function() {
      stemKnop("aan");
    })    
  </script>
</head>
<body>
  <?php include "includes/navigation.php"; ?>
  
  <div class="infoDiv">
    <h1>DE MOL</h1>
    <p id="stemTekst"></p>
  </div>
  
  <div class="submitDiv">
    <button onclick="location.href = 'stemmen.php';" id="stemKnop" class="formSubmitBtn" type="submit">Stemmen</button>
  </div>

  <!-- JavaScript -->
    
  <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>


</body>
</html>