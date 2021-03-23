<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

$id = $_SESSION["Id"];

$selectFollowedUsers = "SELECT Naam, Id
FROM table_Users
LEFT JOIN table_Followers
ON table_Users.Id = table_Followers.UserIsFollowingId
WHERE table_Followers.UserId = '$id'
";

?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>
<body>
  <?php include "includes/navigation.php"; ?>

  <div id="main">
    <div class="respContainer">

    <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <h1>Mollenjagers</h1>
    <p class="example">Hier kan je al jouw mede-mollenjagers vinden.</p>

    <div class="deelnemersList">
      <?php

      if($result = mysqli_query($dbconn, $selectFollowedUsers)){
          if(mysqli_num_rows($result) > 0){
            $i = 1;
              while($row = mysqli_fetch_array($result)){
                  if ($row['Id'] != $id) { ?>
                    <a class="info" style="animation-delay: <?php echo $i/4; ?>s;" href="profiel.php?user=<?php echo $row['Id'];?>">
                        <?php echo $row['Naam']; ?>
                    </a>
                  <?php
                  }
                  $i++;
              }
              // Free result set
              mysqli_free_result($result);
          } else{
              echo "<h2>Je hebt nog geen spelers toegevoegd.<br>Voeg er toe door op de knop hieronder te klikken.</h2>";
          }
      } else{
          echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
      }

      ?>
    </div>

    <hr>
    <button onclick="location.href = 'followUser.php';" class="styledBtn" type="button" name="button">Voeg spelers toe</button>
</div>
  </div>

  <!-- JavaScript -->
  <script type="text/javascript" src="js/scripts.js"></script>

<?php mysqli_close($dbconn); ?>
</body>
</html>
