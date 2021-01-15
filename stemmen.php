<?php 

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

//mysqli_close($dbconn); //CLOSE DOEN

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <script>

    window.addEventListener('load', function() {

      var deelnemers = [

      <?php
        $sql = "SELECT * FROM table_Kandidaten";
        if($result = mysqli_query($dbconn, $sql)){
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result)){

                  ?>
                    { id: <?php echo $row['Id']; ?>, naam: <?php echo $row['Naam']; ?>, leeftijd: <?php echo $row['Leeftijd']; ?>, job: <?php echo $row['Job']; ?> },
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

    ] 

      var html = "";
      deelnemers.forEach(deelnemer => {
      html += `<div class="swiper-slide" id="${deelnemer.id}"> 
                <div class="cardNameBG">
                <p class="cardName">${deelnemer.naam}</p>
                </div>
                <p class="cardInfo">${deelnemer.leeftijd} <span style="color: #53adb5; font-weight: 800">//</span> ${deelnemer.job}</p>

                <div class="cardBottom">
                  <img class="cardLogo" src="img/assets/molLogo.png" alt="mol logo" >
                  <p>Inzet: <input form="deMolForm" type="text" class="btnValue" id="${deelnemer.naam}" value="0" readonly/></p>              
                  <input type="image" src="img/assets/ButtonMin.png" class="btnValueChange" onclick="decrementValue('${deelnemer.naam}')"/>  
                  <input type="image" src="img/assets/ButtonPlus.png" class="btnValueChange" onclick="incrementValue('${deelnemer.naam}')"/> 
                </div> 
                <img class="cardImage" src="img/${deelnemer.naam}.jpg" alt="foto van ${deelnemer.naam}">
              </div>`;
    });
    document.getElementById("carousel").innerHTML += html;

    var swiper = new Swiper('.swiper-container', {
      slidesPerView: 1,
        loop: true,
      pagination: {
        el: '.swiper-pagination',
      },
        // Responsive breakpoints
  breakpoints: {
    // when window width is >= 1000px
    1000: {
      slidesPerView: 3,
        loop: true,
    }
  }
    });
})
  </script>
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