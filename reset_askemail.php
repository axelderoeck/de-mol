<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

include "includes/settings.php";

error_reporting( E_ALL );
ini_set('display_errors', 1);
set_error_handler("var_dump");

$email = "axelderoeck23@gmail.com";
$subject = "Wachtwoord reset";
$message = "Blabla";
$headers = "From: mail@aksol.be";

$status = mail($email,$subject,$message,$headers);

if ($status) {
  echo "Yes";
}else{
  echo "No";
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

      <form method="post" action="reset_send_link.php">
        <p>Geef je email waar jouw account aan verbonden is</p>
        <input placeholder="Email" type="text" name="email">
        <input value="Verzend" type="submit" name="submit_email">
      </form>
    </div>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
  <?php mysqli_close($dbconn); ?>
</body>
</html>
