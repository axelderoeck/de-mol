<?php

require_once("includes/phpdefault.php");

if (isset($_POST["createGroup"])){
  $notification = createGroup($_SESSION["Id"], $_POST["name"], $_POST["description"], $_POST["private"]);
}

?>

<?php include "includes/header.php"; ?>

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

<?php include "includes/footer.php"; ?>
