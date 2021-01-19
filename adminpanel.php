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



//testen of er op de knop werd gedrukt

if (isset($_POST["deletebtn"])){
    $identifier2 = $_GET["Identifier"];

    $qrySetOut = "UPDATE `table_Kandidaten`
    SET `Visibility` = 'out'
    WHERE `Identifier` = '$identifier2'";

    mysqli_query($dbconn, $qrySetOut);

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

      <form id="resetVoteForm" action="" method="post">
        <input type="submit" name="resetVotes" value="Reset Votes" />
      </form>

        <table>
            <tr>
                <th>Naam</th>
                <th>Visibility</th>
                <th>Set Out</th>
            </tr>
            <?php
            $i = 1;
            while(mysqli_stmt_fetch($stmtSelectAll)){
            echo
            "<tr>
            <td> " . $naam . " </td>
            <td> " . $visibility . " </td>
            <td>
            <a href='adminpanel.php?identifier=" . $identifier . "'>
            Set out
            </a>
            </td>
            </tr>";
            $i++;
            }   ?>
        </table>

    <?php mysqli_close($dbconn); ?>
</body>
</html>
