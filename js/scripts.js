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
