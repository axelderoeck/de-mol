<?php

ob_start();
//require_once("includes/dbconn.inc.php");
require_once("includes/phpdefault.php");
//session_start();

//include "includes/settings.php";

$meldingSoort = "succes";

$code = $_GET["code"];
if ($code==9) {
    $_SESSION["Id"] = NULL;
    $_SESSION["Naam"] = "";
    $_SESSION["Voted"] = 0;
    $_SESSION["Admin"] = 0;
    session_destroy();
    header('location:index.php');
    //$meldingSoort = "succes";
    //$foutmelding = "Je bent uitgelogd.";
}

if ($_SESSION["Id"] != NULL) {
  header('location:home.php');
}

if (isset($_POST["userLogin"])){
  // Check if the account exists
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Gebruikersnaam = ?');
  $stmt->execute([ $_POST['Naam'] ]);
  $account = $stmt->fetch(PDO::FETCH_ASSOC);
  // If account exists verify password
  if ($account && password_verify($_POST['Wachtwoord'], $account['Wachtwoord'])) {
    // User has logged in, create session data
    //session_regenerate_id();
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

// LOOK
if (isset($_POST['register'], $_POST['email'], $_POST['password'], $_POST['cpassword']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  // Check if the account exists
  $stmt = $pdo->prepare('SELECT * FROM accounts WHERE email = ?');
  $stmt->execute([ $_POST['email'] ]);
  $account = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($account) {
      // Account exists!
      $register_error = "tekst";
  } else if ($_POST['cpassword'] != $_POST['password']) {
      $register_error = "tekst";
  } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
      // Password must be between 5 and 20 characters long.
      $register_error = "tekst";
  } else {
      // Account doesnt exist, create new account
      $stmt = $pdo->prepare('INSERT INTO accounts (email, password, first_name, last_name, address_street, address_city, address_state, address_zip, address_country) VALUES (?,?,"","","","","","","")');
      // Hash the password
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $stmt->execute([ $_POST['email'], $password ]);
      $account_id = $pdo->lastInsertId();
      // Automatically login the user
      $_SESSION['account_loggedin'] = TRUE;
      $_SESSION['account_id'] = $account_id;
      $_SESSION['account_admin'] = 0;
  }
}




// OLD
if (isset($_POST["userRegister"])){
    //waardes uit het formulier in lokale var steken
    $naam = $_POST["Naam"];
    $gebruikersnaam = $_POST["Gebruikersnaam"];
    $wachtwoord = $_POST["Wachtwoord"];
    $confirmWachtwoord = $_POST["confirmWachtwoord"];

    $sql = $dbconn->query("SELECT Gebruikersnaam
                    FROM table_Users
                    WHERE Gebruikersnaam = '$gebruikersnaam'");

    if($wachtwoord != $confirmWachtwoord){
      $meldingSoort = "warning";
      $foutmelding = "Het wachtwoord is niet bevestigd.";
    }elseif($sql->num_rows > 0){
      $meldingSoort = "warning";
      $foutmelding = "Deze gebruikersnaam is al in gebruik.";
    }else{
        $hash = password_hash($wachtwoord, PASSWORD_BCRYPT);
        $dbconn->query("INSERT INTO table_Users (Gebruikersnaam, Naam, Wachtwoord)
        VALUES ('$gebruikersnaam','$naam','$hash')");

        $selectNewId = $dbconn->query("SELECT Id
                        FROM table_Users
                        WHERE Gebruikersnaam = '$gebruikersnaam' AND Wachtwoord = '$hash'");

        $data = $selectNewId->fetch_array();
        $newId = ($data['Id']);

        for ($i=1; $i <= 10; $i++) {
          $dbconn->query("INSERT INTO table_Scores (UserId, Identifier, Score)
          VALUES ('$newId','person$i',0)");
        }

        $dbconn->query("INSERT INTO table_Followers (UserId, UserIsFollowingId)
        VALUES ('$newId','$newId')");

        //HIERE DA DING DOEN YES add id aan volgers volg zichzelf
        $meldingSoort = "succes";
        $foutmelding = "Account is aangemaakt.";
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
                    <label>Gebruikersnaam</label>
                    <input placeholder="Gebruikersnaam" name="Naam" id="Naam" type="text" required>
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
                    <input placeholder="Gebruikersnaam" name="Gebruikersnaam" id="Gebruikersnaam" type="text" required>
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

<?php mysqli_close($dbconn); ?>
</body>
</html>
