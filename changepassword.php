<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

if (isset($_POST["changePassword"])){
  $id = $_SESSION["Id"];
  $naam = $_SESSION["Naam"];
  $wachtwoord = $_POST["oudWachtwoord"];
  $newWachtwoord = $_POST["Wachtwoord"];
  $confirmWachtwoord = $_POST["confirmWachtwoord"];

  $sql = $dbconn->query("SELECT Id, Wachtwoord
                  FROM table_Users
                  WHERE Naam = '$naam'");

  if($sql->num_rows > 0) {
    $data = $sql->fetch_array();
    if(password_verify($wachtwoord, $data['Wachtwoord'])){
      if($newWachtwoord == $confirmWachtwoord){
        $hash = password_hash($newWachtwoord, PASSWORD_BCRYPT);
        $dbconn->query("UPDATE table_Users
        SET Wachtwoord = '$hash'
        WHERE Id = '$id'");
      }
    }
    header('location:profiel.php');
  }
header('location:changepassword.php');
}


?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

<div class="profileScreen" id="main">

  <h1>Wachtwoord veranderen</h1>
  <hr>
  <div class="box">
    <form name="formChangePassword" action="" method="post">
        <input placeholder="Oud wachtwoord" name="oudWachtwoord" id="oudWachtwoord" type="password" required>
        <br>
        <input placeholder="Wachtwoord" name="Wachtwoord" id="Wachtwoord" type="password" required>
        <br>
        <input placeholder="Wachtwoord" name="confirmWachtwoord" id="confirmWachtwoord" type="password" required>
        <br>
        <input type="submit" name="changePassword" id="changePassword" value="Verander">
    </form>
  </div>

</div>

  <!-- JavaScript -->
  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
