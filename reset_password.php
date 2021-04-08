<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

include "includes/settings.php";
include "includes/functions.php";

// get id and key from url
$url_userKey = $_GET["s"];
$url_userId = $_GET["u"];

$queryGetUserKey = $dbconn->query("SELECT UserKey
                FROM table_Users
                WHERE Id = '$url_userId'");

$data = $queryGetUserKey->fetch_array();
$userKey = ($data['UserKey']);

if ($url_userKey == $userKey) {
  echo "matches";
}else{
  echo "not matching";
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
      <h1>Wachtwoord veranderen</h1>

    </div>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
  <?php mysqli_close($dbconn); ?>
</body>
</html>
