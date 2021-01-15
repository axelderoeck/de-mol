/*
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
]   */


function loadItems() {
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
} 
/*
window.addEventListener('load', function() {
    //loadItems();
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
    stemKnop("aan");
}) */

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
    
    //value adder
    if( isOverValue(10) == false ){
    	value++;   
    }else {
    	console.log("Je kan niet meer dan 10 punten inzetten.")    
    }
    
    document.getElementById(id).value = value;
}

function decrementValue(id) 
{
    if( isOverValue(9) == true ){
            switchButtons('btnPlus', 'enable');
    }
    var value = parseInt(document.getElementById(id).value, 10);
    value = isNaN(value) ? 0 : value;
    if(value > 0) {
    	value--;
        if(isOverValue(0) == false) {
            switchButtons('btnMin', 'disable');
        }
    }     
    document.getElementById(id).value = value;
}


function hamburgerMenu() {
  var x = document.getElementById("myLinks");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}

function stemKnop(toggle) {
    if(toggle == "aan"){
        document.getElementById('stemTekst').innerHTML = "<span>Stemmen</span> is nu mogelijk.";   
        document.getElementById('stemKnop').disabled = false;
    }else if(toggle == "uit") {
        document.getElementById('stemTekst').innerHTML = "<span>Stemmen</span> is nu niet mogelijk. <br/> Je hebt al gestemd voor de meest recente aflevering.";
        document.getElementById('stemKnop').disabled = true;
    }
}
