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
          { id: 1, naam: "alina", leeftijd: "20", job: "studente logopedie" },
          { id: 2, naam: "bart", leeftijd: "43", job: "advocaat" },
          { id: 3, naam: "bruno", leeftijd: "50", job: "technisch directeur" },
          { id: 4, naam: "christian", leeftijd: "26", job: "consultant" },
          { id: 5, naam: "dorien", leeftijd: "27", job: "sauna uitbaatster" },
          { id: 6, naam: "els", leeftijd: "51", job: "lerares" },
          { id: 7, naam: "gilles", leeftijd: "29", job: "horeca uitbater" },
          { id: 8, naam: "jolien", leeftijd: "25", job: "bankbediende" },
          { id: 9, naam: "laure", leeftijd: "46", job: "management assistant" },
          { id: 10, naam: "salim", leeftijd: "28", job: "shopmanager bioscoop" }
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