<?php

require_once("includes/phpdefault.php");

if (isset($_POST["createGroup"])){
  $notification = createGroup($_SESSION["Id"], $_POST["name"], $_POST["description"], $_POST["private"]);
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
  <script>
  window.addEventListener('load', function() {
    <?php
      $pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache');
      if($pageRefreshed == 1){
        echo "showNotification('$notification->message','$notification->type');"; //message + color style
      }
    ?>
  })
  </script>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

  <div id="informationPopup">
    <!-- Dynamische info -->
  </div>

  <div id="main">
    <div class="respContainer">

    <a href="friends.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <h1>Maak een groep aan</h1>
    <form action="" method="post">
      <input placeholder="Naam" type="text" id="name" name="name">
      <input placeholder="Beschrijving" type="text" id="description" name="description">
      <input type="hidden" id="private" name="private" value="0">
      <input type="checkbox" id="private" name="private" value="1">
      <label for="private">Privé groep</label>
      <br>
      <input type="submit" name="createGroup" id="createGroup" value="Maak groep">
    </form>
    <p class="example">
      Maak een groep aan
    </p>
    </div>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
</body>
</html>
