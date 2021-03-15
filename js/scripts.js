function hamburgerMenu() {
	var x = document.getElementById("myLinks");
	if (x.style.display === "block") {
		x.style.display = "none";
	} else {
		x.style.display = "block";
	}
}

window.stemKnop = function(toggle) {
	if (toggle == "aan") {
		document.getElementById('stemKnop').disabled = false;
	} else if (toggle == "uit") {
		document.getElementById('stemKnop').disabled = true;
	}
}

window.submitKnop = function(toggle) {
	if (toggle == "aan") {
		document.getElementById('formSubmitVote').disabled = false;
	} else if (toggle == "uit") {
		document.getElementById('formSubmitVote').disabled = true;
	}
}

window.infoTekst = function(tekst) {
	document.getElementById('infoTekst').innerHTML = tekst;
}

function openReg() {
	var x = document.getElementById("reg");
	var y = document.getElementById("log");
	if (x.style.display === "block") {
		x.style.display = "none";
		y.style.display = "block";
	} else {
		x.style.display = "block";
		y.style.display = "none";
	}
}

document.getElementById('menu-btn').onclick = function() {
	// access properties using this keyword
	if (this.checked) {
		document.getElementById("sideNav").style.width = "250px";
	} else {
		document.getElementById("sideNav").style.width = "0";
	}
};

function openNav() {
	document.getElementById("mySidenav").style.width = "250px";
	document.getElementById("main").style.marginLeft = "250px";
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
function closeNav() {
	document.getElementById("mySidenav").style.width = "0";
	document.getElementById("main").style.marginLeft = "0";
}

function collapse(contentId, buttonId) {
	var button = document.getElementById(buttonId);
	var content = document.getElementById(contentId);
	if (content.style.display === "block") {
		button.style.transform = "rotate(0deg)";
		content.style.display = "none";
	} else {
		button.style.transform = "rotate(180deg)";
		content.style.display = "block";
	}
}

function showNotification(message, type) {
	var notification = document.getElementById('informationPopup')
	notification.classList.add(type);
	notification.innerHTML = "<p>" + message + "</p>";
	notification.style.top = "20px";
	setTimeout(function() {
		notification.style.top = "-50px";
	}, 4000); //wait 4 seconds before closing again
}

function showPopup(id, showhide) {
	if (showhide == "show") {
		document.getElementById(id).style.display = "block";
	} else if (showhide == "hide") {
		document.getElementById(id).style.display = "none";
	}
}

$(document).ready(function() {
	$('ul.tabs').tabs({
		swipeable: true,
		responsiveThreshold: 1920
	});
});