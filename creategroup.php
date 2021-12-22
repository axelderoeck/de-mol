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
      <label>Naam</label>
      <input placeholder="Naam" type="text" id="name" name="name">
      <br>
      <label>Beschrijving</label>
      <input placeholder="Beschrijving" type="text" id="description" name="description">
      <br>
      <label>Privé groep</label>
      <input style="border: 0;" type="text" readonly>
      <input type="hidden" id="private" name="private" value="0">
      <input type="checkbox" id="private" name="private" value="1">
      <br>
      <input type="submit" name="createGroup" id="createGroup" value="Maak groep">
    </form>

<?php include "includes/footer.php"; ?>
