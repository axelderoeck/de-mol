<?php

ob_start();
// Initialize a new session
session_start();

// Include the configuration file
include 'includes/config.php';
// Include functions
include 'includes/functions.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();

$meldingSoort = "success";

// Active user id found -> send to home page

if ($_SESSION["Id"] != NULL) {
  header('location:home.php');
}

// If logout is set to 1 -> destroy everything no mercy
if ($_GET["logout"] == 1) { 
  // Reset session values to be sure
  $_SESSION['LoggedIn'] = FALSE;
  $_SESSION["Id"] = NULL;
  $_SESSION["Username"] = "";
  $_SESSION["Voted"] = 0;
  $_SESSION["Admin"] = 0;
  // Remove cookie variable
  setcookie ("rememberme","", 1);
  // Destroy session
  session_destroy();
  // Redirect user to index page
  header('location:index.php');
}

// Check if the remember me cookie exists
if(isset($_COOKIE['rememberme'])){
  // Decrypt cookie variable value
  $userId = decryptCookie($_COOKIE['rememberme']);

  // Check if the account exists
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
  $stmt->execute([ $userId ]);
  $account = $stmt->fetch(PDO::FETCH_ASSOC);

  // account exists -> login
  if($account){
      // User has logged in, create session data
      $_SESSION['LoggedIn'] = TRUE;
      $_SESSION["Id"] = $account["Id"];
      $_SESSION["Username"] = $account["Username"];
      $_SESSION["Voted"] = $account["Voted"];
      $_SESSION["Email"] = $account["Email"];
      $_SESSION["Admin"] = $account["Admin"];
      // Redirect user
      $foutmelding = "";
      header('location:home.php');
  }
}

// User pressed login button
if (isset($_POST["userLogin"])){
  // Check if the account exists
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Email = ? OR Username = ?');
  $stmt->execute([ $_POST['email'], $_POST['email'] ]);
  $account = $stmt->fetch(PDO::FETCH_ASSOC);
  // If account exists verify password
  if ($account && password_verify($_POST['password'], $account['Password'])) {
    // User has logged in, create session data
    $_SESSION['LoggedIn'] = TRUE;
    $_SESSION["Id"] = $account["Id"];
    $_SESSION["Username"] = $account["Username"];
    $_SESSION["Voted"] = $account["Voted"];
    $_SESSION["Email"] = $account["Email"];
    $_SESSION["Admin"] = $account["Admin"];
    // If remember me is checked -> set cookie
    if (isset($_POST['remember'])){
      // Set cookie variables
      $value = encryptCookie($account["Id"]);
      setcookie("rememberme", $value, strtotime('+' . REMEMBER_ME_EXPIRE_DAYS .' days'));
    }
    $foutmelding = "";
    header('location:home.php');
  } else {
    $meldingSoort = "warning";
    $foutmelding = "Wachtwoord is niet correct!";
  }
}

// User pressed register button
if (isset($_POST["userRegister"], $_POST['email'], $_POST['password'], $_POST['confirmPassword']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
  // Check if the account exists
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Email = ?');
  $stmt->execute([ $_POST['email'] ]);
  $account = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($account) {
    // Account already exists!
    $register_error = "tekst";
  } else if ($_POST['confirmPassword'] != $_POST['password']) {
    // Passwords do not match
    $register_error = "tekst";
  } else if (strlen($_POST['username']) > MAX_CHAR_USERNAME || strlen($_POST['username']) < MIN_CHAR_USERNAME) {
    $register_error = "Gebruikersnaam moet tussen " . MIN_CHAR_USERNAME . " en " . MAX_CHAR_USERNAME . " karakters zijn.";
  }else if (strlen($_POST['password']) > MAX_CHAR_PASSWORD || strlen($_POST['password']) < MIN_CHAR_PASSWORD) {
    $register_error = "Wachtwoord moet tussen " . MIN_CHAR_PASSWORD . " en " . MAX_CHAR_PASSWORD . " karakters zijn.";
  }else{
    // Hash the password
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // Account does not exist, create new account
    $stmt = $pdo->prepare('INSERT INTO table_Users (Email, Username, Password) VALUES (?,?,?)');
    $stmt->execute([ $_POST['email'], $_POST['username'], $password ]);
    $account_id = $pdo->lastInsertId();
    // Automatically login the user
    $_SESSION['LoggedIn'] = TRUE;
    $_SESSION["Id"] = $account_id;
    $_SESSION["Username"] = $_POST["username"];
    $_SESSION["Voted"] = 0;
    $_SESSION["Email"] = $_POST["email"];
    $_SESSION["Admin"] = 0;

    // Add unused friendcode to user
    $inserted_friendcode = false;
    $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Friendcode = ?');
    while($inserted_friendcode == false) {
      // Generate random friendcode
      $friend_code = generateRandomInt(FRIENDCODE_LENGTH);
      // Search for existing account with generated friendcode
      $stmt->execute([ $friend_code ]);
      $account = $stmt->fetch(PDO::FETCH_ASSOC);
      if(!$account) {
        // Account does not exist -> add friendcode
        $stmt = $pdo->prepare('UPDATE table_Users SET Friendcode = ? WHERE Id = ?');
        $stmt->execute([ $friend_code, $account_id ]);
        $inserted_friendcode = true;
      }
    }

    // Send user to home page
    header('location:home.php');
  }
}

?>

<?php include "includes/header.php"; ?>

  <div style="text-align: center; margin: 10% 0;">
    <img class="loginImg" src="img/assets/demol_logo_animated.gif" alt="logo de mol animated">
  </div>

  <!-- <button onclick="location.href = 'nothingyet.php';" class="styledBtn" type="submit" name="button">Inloggen</button>
  <button onclick="location.href = 'nothingyet.php';" class="styledBtn" type="submit" name="button">Registreren</button> -->

  <div id="loginbox">
    <div id="log">
      <form name="formLogin" action="" method="post">
        <label>Gebruikersnaam of Email</label>
        <input placeholder="Gebruikersnaam of Email" name="email" id="email" type="text" required>
        <br>
        <label>Wachtwoord</label>
        <input placeholder="Wachtwoord" name="password" id="password" type="password" required>
        <br>
        <!-- <label>Onthoud mij</label>
        <input style="border: 0;" type="text" readonly> -->
        <input type="hidden" name="remember" id="remember" value="1">
        <br>
        <input type="submit" name="userLogin" id="userLogin" value="Login">
        <br>             
      </form>
      <a href="forgotpassword.php">wachtwoord vergeten?</a>
      <p class="loginLink">Geen account? Klik <a href="javascript:openReg();">hier.</a></p>
    </div>
    
    <div id="reg">
      <form name="formRegister" action="" method="post">
        <label>Email</label>
        <input placeholder="Email" name="email" id="email" type="text" required>
        <br>
        <label>Gebruikersnaam</label>
        <input placeholder="Gebruikersnaam" name="username" id="username" type="text" required>
        <br>
        <label>Wachtwoord</label>
        <input placeholder="Wachtwoord" name="password" id="password" type="password" required>
        <br>
        <label>Bevestig Wachtwoord</label>
        <input placeholder="Wachtwoord" name="confirmPassword" id="confirmPassword" type="password" required>
        <br>
        <input type="submit" name="userRegister" id="userRegister" value="Registreer">
        <br>
      </form>
      <p class="loginLink">Ga terug naar <a href="javascript:openReg();">login.</a></p>
    </div>
    <?php include "includes/legal.php"; ?>
  </div>

<?php include "includes/footer.php"; ?>

