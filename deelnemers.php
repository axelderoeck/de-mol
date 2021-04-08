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
WHERE table_Followers.UserId = '$id'";

// GET ALL FOLLOWED USERS THAT VOTED
$selectFollowedUsersThatVoted = "SELECT Id
FROM table_Users
WHERE Voted = 1 AND Id IN
(SELECT UserIsFollowingId
FROM table_Followers
WHERE UserId = '$id')";

// INSERT RESULTS INTO ARRAY
$arrayVotedUsers = array();
if($executeSelectVotedUsers = mysqli_query($dbconn, $selectFollowedUsersThatVoted)){
  while($row = mysqli_fetch_array($executeSelectVotedUsers)){
    array_push($arrayVotedUsers, $row['Id']);
  }
}

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
    <p class="example">Hier kan je al jouw mede-mollenjagers vinden. <br>
    p.s. de <i class='fas fa-check-circle'></i> duid aan wie er al gestemd heeft.</p>

    <div class="deelnemersList">
      <?php

      if($result = mysqli_query($dbconn, $selectFollowedUsers)){
          if(mysqli_num_rows($result) > 0){
            $i = 1;
              while($row = mysqli_fetch_array($result)){
                  if ($row['Id'] != $id) { ?>
                    <a class="deelnemerItem info" style="animation-delay: <?php echo $i/6; ?>s;" href="profiel.php?user=<?php echo $row['Id'];?>">
                      <i class='fas fa-user left'></i>
                        <?php echo $row['Naam']; ?>
                        <?php if (in_array($row['Id'], $arrayVotedUsers)) {
                          // IF this ID has voted -> display checkmark
                          echo "<i class='fas fa-check-circle right'></i>";
                        } ?>
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
