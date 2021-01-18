<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Admin"] != 1) {
  header('location:home.php');
}

if(isset($_POST["resetVotes"])) {
  $query = "UPDATE `table_Users`
  SET `Voted` = 0";

  mysqli_query($dbconn, $query);
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php include "includes/headinfo.php"; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
</head>
<body>
    <?php include "includes/navigation.php"; ?>

      <form id="resetVoteForm" action="" method="post">
        <input type="submit" name="resetVotes" value="Reset Votes" />
      </form>

    <?php mysqli_close($dbconn); ?>
</body>
</html>
