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


if (isset($_POST["setOutBtn"])){
    $identifierOut = $_POST["identifier"];

    $qrySetOut = "UPDATE `table_Kandidaten`
    SET `Visibility` = 'out'
    WHERE `Identifier` = '$identifierOut'";

    mysqli_query($dbconn, $qrySetOut);
}

if (isset($_POST["setInBtn"])){
    $identifierIn = $_POST["identifier"];

    $qrySetIn = "UPDATE `table_Kandidaten`
    SET `Visibility` = 'visible'
    WHERE `Identifier` = '$identifierIn'";

    mysqli_query($dbconn, $qrySetIn);
}

if (isset($_POST["setMolBtn"])){
    $demol = $_POST["demol"];

    $qrySetMol = "UPDATE `table_Mol`
    SET `demol` = '$demol'";

    mysqli_query($dbconn, $qrySetMol);
}

//query ledenoverzicht
$qrySelectAll = "SELECT Naam, Identifier, Visibility
FROM table_Kandidaten";

//statement aanmaken
if ($stmtSelectAll = mysqli_prepare($dbconn, $qrySelectAll)){
    //query uitvoeren
    mysqli_stmt_execute($stmtSelectAll);
    //resultaat binden aan lokale variabelen
    mysqli_stmt_bind_result($stmtSelectAll, $naam, $identifier, $visibility);
    //resultaten opslaan
    mysqli_stmt_store_result($stmtSelectAll);
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php include "includes/headinfo.php"; ?>
</head>
<body>
    <?php include "includes/navigation.php"; ?>

    <div class="adminPanel" id="main">
      <h1>Admin Panel</h1>

        <h2>De Kandidaten</h2>

        <table>
            <tr>
                <th>Naam</th>
                <th>Identifier</th>
                <th>Visibility</th>
            </tr>
            <?php
            $i = 1;
            while(mysqli_stmt_fetch($stmtSelectAll)){
            echo
            "<tr>
            <td> " . $naam . " </td>
            <td> " . $identifier . " </td>
            <td> " . $visibility . " </td>
            </tr>";
            $i++;
            }   ?>
        </table>

        <h2>Hup der uit jong</h2>

        <div class="box">
        <form id="setOutForm" class="setOutForm" method="post">
          <input placeholder="identifier" type="text" name="identifier">
          <input type="submit" name="setOutBtn">
        </form>
        </div>
        <hr>

        <h2>Oops foutje komt ma terug</h2>

        <div class="box">
        <form id="setInForm" class="setInForm" method="post">
          <input placeholder="identifier" type="text" name="identifier">
          <input type="submit" name="setInBtn">
        </form>
        </div>
        <hr>

        <h2>De Mol is... *tromgeroffel*</h2>

        <div class="box">
          <form id="setMolForm" class="setMolForm" method="post">
            <input placeholder="identifier" type="text" id="demol" name="demol">
            <input type="submit" name="setMolBtn">
          </form>
        </div>
        <hr>


        <h2>Reset heeft gestemd</h2>

        <div class="box">
        <form id="resetVoteForm" method="post">
          <input type="submit" name="resetVotes" value="Reset" />
        </form>
        </div>

      </div>

    <?php mysqli_close($dbconn); ?>
</body>
</html>
