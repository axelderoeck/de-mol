<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

include "includes/settings.php";
include "includes/functions.php";

if (isset($_POST["sendMail"])){
  $randomGeneratedString = generateRandomString(10);

  $email = $_POST["email"];
  $subject = "Wachtwoord reset";


  $message = "https://aksol.be/demol/reset_password.php?s=$randomGeneratedString";

  $headers = "From: mail@aksol.be";
  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

  $status = mail($email,$subject,$message,$headers);

  if ($status) {
    echo "Yes";
  }else{
    echo "No";
  }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>
<body>

  <div id="main">
    <div class="respContainer">
      <h1>Wachtwoord vergeten</h1>

      <form method="post" action="">
        <p>Geef je email waar jouw account aan verbonden is</p>
        <input placeholder="Email" type="text" name="email">
        <input value="Stuur" type="submit" name="sendMail" id="sendMail">
      </form>
    </div>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
  <?php mysqli_close($dbconn); ?>
</body>
</html>
