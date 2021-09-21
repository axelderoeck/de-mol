<?php

ob_start();
// Initialize a new session
session_start();
// these 2 need to be merged
// Include the settings file
include "includes/settings.php";
// Include the configuration file
include 'includes/config.php';
// Include functions
include 'includes/functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();

$meldingSoort = "succes";

// Active user id found -> send to home page
if ($_SESSION["Id"] != NULL) {
  header('location:home.php');
}

// If logout is set to 1 -> destroy session
if ($_GET["logout"] == 1) { 
  $_SESSION['LoggedIn'] = FALSE;
  $_SESSION["Id"] = NULL;
  $_SESSION["Naam"] = "";
  $_SESSION["Voted"] = 0;
  $_SESSION["Admin"] = 0;
  session_destroy();
  header('location:index.php');
}

// User pressed login button
if (isset($_POST["userLogin"])){
  // Check if the account exists
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Email = ? OR Gebruikersnaam = ?');
  $stmt->execute([ $_POST['Email'], $_POST['Email'] ]);
  $account = $stmt->fetch(PDO::FETCH_ASSOC);
  // If account exists verify password
  if ($account && password_verify($_POST['Wachtwoord'], $account['Wachtwoord'])) {
    // User has logged in, create session data
    $_SESSION['LoggedIn'] = TRUE;
    $_SESSION["Id"] = $account['Id'];
    $_SESSION["Naam"] = $account["Naam"];
    $_SESSION["Gebruikersnaam"] = $account["Gebruikersnaam"];
    $_SESSION["Voted"] = $account["Voted"];
    $_SESSION["Email"] = $account["Email"];
    $_SESSION["Admin"] = $account["Admin"];
    $foutmelding = "";
    header('location:home.php');
  } else {
    $meldingSoort = "warning";
    $foutmelding = "Wachtwoord is niet correct!";
  }
}

// User pressed register button
if (isset($_POST["userRegister"], $_POST['Email'], $_POST['Wachtwoord'], $_POST['confirmWachtwoord']) && filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL)){
  // Check if the account exists
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Email = ?');
  $stmt->execute([ $_POST['Email'] ]);
  $account = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($account) {
    // Account already exists!
    $register_error = "tekst";
  } else if ($_POST['confirmWachtwoord'] != $_POST['Wachtwoord']) {
    // Passwords do not match
    $register_error = "tekst";
  } else if (strlen($_POST['Wachtwoord']) > 20 || strlen($_POST['Wachtwoord']) < 3) {
    // Password must be between 3 and 20 characters long.
    $register_error = "tekst";
  } else {
    // Hash the password
    $password = password_hash($_POST['Wachtwoord'], PASSWORD_DEFAULT);
    // Account does not exist, create new account
    $stmt = $pdo->prepare('INSERT INTO table_Users (Email, Naam, Wachtwoord) VALUES (?,?,?)');
    $stmt->execute([ $_POST['Email'], $_POST['Naam'], $password ]);
    $account_id = $pdo->lastInsertId();
    // Automatically login the user
    $_SESSION['LoggedIn'] = TRUE;
    $_SESSION["Id"] = $account_id;
    $_SESSION["Naam"] = $_POST["Naam"];
    $_SESSION["Voted"] = 0;
    $_SESSION["Email"] = $_POST["Email"];
    $_SESSION["Admin"] = 0;
    // Add 0 points to every candidate as new account's score
    $stmt = $pdo->prepare('INSERT INTO table_Scores (UserId, Identifier, Score) VALUES (?,?,?)');
    for ($i=1; $i <= 10; $i++) {
      $stmt->execute([ $account_id, "person$i", 0 ]);
    }
    // Send user to home page
    header('location:home.php');
  }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <script>
  window.addEventListener('load', function() {
    <?php
      $pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache');
      if($pageRefreshed == 1){
        echo "showNotification('$foutmelding','$meldingSoort');"; //message + color style
      }
      if ($code == 9) {
        echo "showNotification('$foutmelding','$meldingSoort');";
      }
    ?>
  })
  </script>
</head>
<body>

  <div id="informationPopup">
    <!-- Dynamische info -->
  </div>

  <div class="respContainer">

  <div style="text-align: center; margin: 10% 0;">
    <img class="loginImg" src="img/assets/molLogo.png" alt="logo">
  </div>

  <div id="loginbox">
            <div id="log">
                <form name="formLogin" action="" method="post">
                    <label>Gebruikersnaam of Email</label>
                    <input placeholder="Gebruikersnaam of Email" name="Email" id="Email" type="text" required>
                    <br>
                    <label>Wachtwoord</label>
                    <input placeholder="Wachtwoord" name="Wachtwoord" id="Wachtwoord" type="password" required>
                    <br>
                    <input type="submit" name="userLogin" id="userLogin" value="Login">
                    <br>
                    <input type="checkbox" name="rememberme" value="">
                    <label>Onthoud Mij</label>                
                </form>
                <a href="reset_askemail.php">wachtwoord vergeten?</a>
                <p class="loginLink">Geen account? Klik <a href="javascript:openReg();">hier.</a></p>
            </div>
            <div id="reg">
                <form name="formRegister" action="" method="post">
                    <input placeholder="Email" name="Email" id="Email" type="text" required>
                    <br>
                    <input placeholder="Voornaam" name="Naam" id="Naam" type="text" required>
                    <br>
                    <input placeholder="Wachtwoord" name="Wachtwoord" id="Wachtwoord" type="password" required>
                    <br>
                    <input placeholder="Wachtwoord" name="confirmWachtwoord" id="confirmWachtwoord" type="password" required>
                    <br>
                    <input type="submit" name="userRegister" id="userRegister" value="Register">
                    <br>
                </form>
                <p class="loginLink">Ga terug naar <a href="javascript:openReg();">login.</a></p>
            </div>
            <?php include "includes/legal.php"; ?>
    </div>
  </div>

  <!-- JavaScript -->
  <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>
