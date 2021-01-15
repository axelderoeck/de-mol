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
  <script type="text/javascript">

    //Punten Bereken functies
    function isOverValue(value)
    {
      var total = 0;  
      deelnemers.forEach(deelnemer => {
          total += parseInt(document.getElementById(deelnemer.naam).value, 10);
      });   
      if( total < value ){
        return false;
      }
      return true;
    }

    function incrementValue(id)
    {
      var value = parseInt(document.getElementById(id).value, 10);
      value = isNaN(value) ? 0 : value;     
      if( isOverValue(10) == false ){
        value++;   
      }else {
        console.log("Je kan niet meer dan 10 punten inzetten.")    
      }    
      document.getElementById(id).value = value;
    }

    function decrementValue(id) 
    {
      var value = parseInt(document.getElementById(id).value, 10);
      value = isNaN(value) ? 0 : value;
      if(value > 0) {
        value--;
      }     
      document.getElementById(id).value = value;
    }

    window.addEventListener('load', function() {
      //PHP waardes in array steken
      var deelnemers = [
      <?php
        $sql = "SELECT * FROM table_Kandidaten";
        if($result = mysqli_query($dbconn, $sql)){
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result)){

                  ?>
                    { id: <?php echo $row['Id']; ?>, naam: <?php echo "'" . $row['Naam'] . "'"; ?>, leeftijd: <?php echo $row['Leeftijd']; ?>, job: <?php echo "'" . $row['Job'] . "'"; ?> },
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
      
      //Array waardes in een div card steken
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

      //Carousel aanmaken
      var swiper = new Swiper('.swiper-container', {
        slidesPerView: 1,
          loop: true,
          pagination: {
            el: '.swiper-pagination',
          },
        breakpoints: {
          //ALS scherm gelijk is aan >= 1000px
          1000: {
            slidesPerView: 3,
            loop: true,
          }
        }
      });
      
    }) //Einde Event Listener

    

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
<!-- <script type="text/javascript" src="js/scripts.js"></script> -->

  <?php mysqli_close($dbconn); ?>

</body>
</html>