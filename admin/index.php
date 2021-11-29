<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Admin"] != 1) {
  header('location:home.php');
}

if (isset($_POST["deleteUser"])){
  $deleteId = $_POST["idToDelete"];

  $dbconn->query("DELETE FROM table_Users
    WHERE Id = '$deleteId';
    ");

  $dbconn->query("DELETE FROM table_Scores
    WHERE UserId = '$deleteId';
    ");

  $dbconn->query("DELETE FROM table_UserAwards
    WHERE UserId = '$deleteId';
    ");

    $dbconn->query("DELETE FROM table_Followers
      WHERE UserId = '$deleteId';
    ");

    $dbconn->query("DELETE FROM table_Followers
      WHERE UserIsFollowingId = '$deleteId';
    ");
}

if(isset($_POST["resetScores"])) {
  $qryReset = "UPDATE `table_Scores`
  SET `Score` = 0";

  mysqli_query($dbconn, $qryReset);
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

$qryCountAllUsers = "SELECT COUNT(Id) FROM table_Users";
if ($stmtCountAllUsers = mysqli_prepare($dbconn, $qryCountAllUsers)){
    mysqli_stmt_execute($stmtCountAllUsers);
    mysqli_stmt_bind_result($stmtCountAllUsers, $totalUsers);
    mysqli_stmt_store_result($stmtCountAllUsers);
}
mysqli_stmt_fetch($stmtCountAllUsers);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php include "includes/headinfo.php"; ?>
</head>
<body>
    <?php include "includes/navigation.php"; ?>

    <div class="adminPanel" id="main">
      <div class="respContainer">

      <h1>Admin Panel</h1>

        <div class="info">
          <h2>Info:</h2>
          <p>Aantal Accounts: <?php echo $totalUsers; ?></p>
        </div>

        <hr>

        <h2>Delete een account</h2>
        <div class="box">
          <form method="post">
            <input type="text" name="idToDelete" id="idToDelete" placeholder="Id">
            <input type="submit" name="deleteUser" value="Delete">
          </form>
        </div>
        <hr>

        <h3>Update Kandidaten <button onclick="collapse('collapsible-content2','collapsible2');" type="button" id="collapsible2"><i class="fas fa-chevron-down"></i></button></h3>
        <div id="collapsible-content2">
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
            <p class="example">onbekend of person1,person2,...</p>
          </div>
          <hr>
        </div>

        <h3>Danger Zone <button onclick="collapse('collapsible-content','collapsible');" type="button" id="collapsible"><i class="fas fa-chevron-down"></i></button></h3>
        <div id="collapsible-content">
          <h2>Reset heeft gestemd</h2>
          <div class="box">
          <form id="resetVoteForm" method="post">
            <input class="warning" type="submit" name="resetVotes" value="Reset" />
          </form>
          </div>
          <hr>

          <h2>Reset alle scores</h2>
          <div class="box">
            <form method="post">
              <input type="submit" name="resetScores" value="Reset">
            </form>
          </div>
        </div>

        </div>
      </div>
      <script type="text/javascript" src="js/scripts.js"></script>
    <?php mysqli_close($dbconn); ?>
</body>
</html>