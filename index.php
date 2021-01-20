<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

$code = $_GET["code"];
if ($code==9) {
    $_SESSION["Id"] = NULL;
    $_SESSION["Naam"] = "";
    $_SESSION["Voted"] = 0;
    $_SESSION["Admin"] = 0;
    session_destroy();
    header('location:index.php');
}

if ($_SESSION["Id"] != NULL) {
  header('location:home.php');
}

$foutmelding = "";

if (isset($_POST["userLogin"])){
    //gegevens van de formfields ontvangen
    $naam = $_POST["Naam"];
    $wachtwoord = $_POST["Wachtwoord"];

    $sql = $dbconn->query("SELECT Id, Wachtwoord, Voted
                    FROM table_Users
                    WHERE Naam = '$naam'");

    if($sql->num_rows > 0) {
        $data = $sql->fetch_array();
        $id = ($data['Id']);
        $hasVoted = ($data['Voted']);
        if(password_verify($wachtwoord, $data['Wachtwoord'])){
            if($id == 7){
            $_SESSION["Id"] = $id;
            $_SESSION["Naam"] = $naam;
            $_SESSION["Voted"] = $hasVoted;
            $_SESSION["Admin"] = 1;
            $foutmelding = "";
            header('location:adminpanel.php');
          }elseif ($id <> NULL){
            //gebruiker is gevonden => aangemeld
            $_SESSION["Id"] = $id;
            $_SESSION["Naam"] = $naam;
            $_SESSION["Voted"] = $hasVoted;
            $_SESSION["Admin"] = 0;
            $foutmelding = "";
            header('location:home.php');
            }else{
            //gebruiker is niet gevonden => niet aangemeld
            $foutmelding = "Gebruiker niet gevonden of wachtwoord niet correct!";
            }
        }
    } else {
        $foutmelding = "Gebruiker niet gevonden of wachtwoord niet correct!";
    }

}



if (isset($_POST["userRegister"])){
    //waardes uit het formulier in lokale var steken
    $naam = $_POST["Naam"];
    $wachtwoord = $_POST["Wachtwoord"];
    $confirmWachtwoord = $_POST["confirmWachtwoord"];

    if($wachtwoord != $confirmWachtwoord){
        $foutmelding = "Het wachtwoord is niet bevestigd.";
    }else{
        $hash = password_hash($wachtwoord, PASSWORD_BCRYPT);
        $dbconn->query("INSERT INTO table_Users (Naam, Wachtwoord)
        VALUES ('$naam','$hash')");

        for ($i=1; $i <= 10; $i++) {
          $dbconn->query("INSERT INTO table_Scores (Naam, Identifier, Score)
          VALUES ('$naam','person$i',0)");
        }
    }



}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
</head>
<body>

  <div style="text-align: center; margin: 30% 0;">
    <img class="loginImg" src="img/assets/molLogo.png" alt="logo">
    <p class="loginText">Jij weet <span class="colored">niets</span><br>
    <span style="font-size: 25px;">(Behalve dat je moet <span class="colored">inloggen</span>)</span></p>
    <img style="width: 10%;" src="img/assets/arrow.png" alt="">
  </div>

  <div class="gradient"></div>
  <div id="loginbox">
            <div id="log" class="box">
                <form name="formLogin" action="" method="post">
                    <input placeholder="Naam" name="Naam" id="Naam" required>
                    <br>
                    <input placeholder="Wachtwoord" name="Wachtwoord" id="Wachtwoord" type="password" required>
                    <br>
                    <input type="submit" name="userLogin" id="userLogin" value="Login">
                    <?php echo "<p class='loginError'>" . $foutmelding . "</p>"; ?>
                </form>
                <p class="loginLink">Geen account? Klik <a href="javascript:openReg();">hier.</a></p>
            </div>
            <div id="reg" class="box">
                <form name="formRegister" action="" method="post">
                    <input placeholder="Naam" name="Naam" id="Naam" required>
                    <br>
                    <input placeholder="Wachtwoord" name="Wachtwoord" id="Wachtwoord" type="password" required>
                    <br>
                    <input placeholder="Wachtwoord" name="confirmWachtwoord" id="confirmWachtwoord" type="password" required>
                    <br>
                    <input type="submit" name="userRegister" id="userRegister" value="Register">
                    <br>
                    <?php echo "<p class='loginError'>" . $foutmelding . "</p>"; ?>
                </form>
                <p class="loginLink">Ga terug naar <a href="javascript:openReg();">login.</a></p>
            </div>
  </div>

  <!-- JavaScript -->

  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
