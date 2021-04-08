<?php

$sessionId = $_GET["s"];
$userId = $_GET["u"];
if ($sessionId != $_SESSION["sessionString"]) {
  echo "not matching";
}else{
  echo "matches";
}

echo $sessionId;
echo $_SESSION["sessionString"];

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
