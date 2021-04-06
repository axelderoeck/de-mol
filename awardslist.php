<?php

ob_start();
require_once("includes/dbconn.inc.php");
session_start();

$id = $_SESSION["Id"];

if ($_SESSION["Id"] == NULL) {
  header('location:index.php');
}

/* Get all awards that the user has and put it in array */

$selectAwards = "SELECT AwardId
FROM table_UserAwards
LEFT JOIN table_Awards
ON table_UserAwards.AwardId = table_Awards.Id
WHERE table_UserAwards.UserId = '$id'
";

$arrayUserAwards = array();

if($executeSelectAwards = mysqli_query($dbconn, $selectAwards)){
  while($row = mysqli_fetch_array($executeSelectAwards)){
    array_push($arrayUserAwards, $row['AwardId']);
  }
}

/* Get all awards available */

$selectAllAwards = "SELECT Id, Naam, Beschrijving, Editie, Secret
FROM table_Awards
WHERE Actief = 1;
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
    <a href="profiel.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <div class="awardslist">
      <?php
      $delay = 0; //set animation delay value

      // show all unlocked awards
      if($result = mysqli_query($dbconn, $selectAllAwards)){
        while($row = mysqli_fetch_array($result)){
          if (in_array($row['Id'], $arrayUserAwards)) { ?>
            <div class="info" style="animation-delay: <?php echo $delay/4; ?>s;" >
              <img src="img/awards/<?php echo $row['Id']; ?>.png" alt="award foto van <?php echo $row['Naam']; ?>">
              <h3><?php echo $row['Naam']; ?></h3>
              <p><?php echo $row['Beschrijving']; ?></p>
              <i class="fas fa-unlock"></i>
            </div>
            <?php $delay++; //increase animation delay value
          }
        }
      }

      // show all locked awards
      if($result = mysqli_query($dbconn, $selectAllAwards)){
        while($row = mysqli_fetch_array($result)){
          if (!in_array($row['Id'], $arrayUserAwards)) { ?>
            <div class="locked" style="animation-delay: <?php echo $delay/4; ?>s;" >
              <img src="img/awards/<?php echo $row['Id']; ?>.png" alt="award foto van <?php echo $row['Naam']; ?>">
              <h3 style="color: #707070;"><?php echo $row['Naam']; ?></h3>
              <p style="color: #707070;"><?php echo $row['Beschrijving']; ?></p>
              <?php
              // if this record is the secret award -> set hidden code
                if ($row['Secret'] == 1) {echo "<p class='hiddenField'>" . $award_secret_mol_randomcode . "</p>";}
              ?>
              <i style="color: #707070;" class="fas fa-lock"></i>
            </div>
            <?php $delay++; //increase animation delay value
          }
        }
      }
      ?>

    </div>
    </div>
  </div>

  <script type="text/javascript" src="js/scripts.js"></script>
  <?php mysqli_close($dbconn); ?>
</body>
</html>
