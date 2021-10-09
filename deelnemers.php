<?php

require_once("includes/phpdefault.php");

$id = $_SESSION["Id"];

$stmt = $pdo->prepare('SELECT Naam, Id
                      FROM table_Users
                      LEFT JOIN table_Followers
                      ON table_Users.Id = table_Followers.UserIsFollowingId
                      WHERE table_Followers.UserId = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$followedUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// GET ALL FOLLOWED USERS THAT VOTED
$stmt = $pdo->prepare('SELECT Id
                      FROM table_Users
                      WHERE Voted = 1 AND Id IN
                      (SELECT UserIsFollowingId
                      FROM table_Followers
                      WHERE UserId = ?)');
$stmt->execute([ $_SESSION["Id"] ]);
$followedUsersVoted = $stmt->fetchAll(PDO::FETCH_ASSOC);

// INSERT RESULTS INTO ARRAY
$arrayVotedUsers = array();
foreach($followedUsersVoted as $user){
  array_push($arrayVotedUsers, $user['Id']);
}

/*
if($executeSelectVotedUsers = mysqli_query($dbconn, $selectFollowedUsersThatVoted)){
  while($row = mysqli_fetch_array($executeSelectVotedUsers)){
    array_push($arrayVotedUsers, $row['Id']);
  }
}*/

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
    <i class='fas fa-check-circle'></i> duid aan wie er al gestemd heeft.</p>

    <div class="deelnemersList">
      <?php if(!empty($followedUsers)): ?>
      <?php $i = 0; foreach($followedUsers as $followedUser): ?>
      <?php if($followedUser["Id"] != $_SESSION["Id"]): ?>
      <a class="deelnemerItem info" style="animation-delay: <?=$i/6?>s;" href="profiel.php?user=<?=$followedUser['Id']?>">
        <i class='fas fa-user left'></i>
          <?=$followedUser['Naam']?>
          <?php if (in_array($followedUser['Id'], $arrayVotedUsers)) {
            // IF this ID has voted -> display checkmark
            echo "<i class='fas fa-check-circle right'></i>";
          } ?>
      </a>
      <?php endif; ?>
      <?php $i++; endforeach; ?>
      <?php else: ?>
      <h2>Je hebt nog geen spelers toegevoegd.<br>Voeg er toe door op de knop hieronder te klikken.</h2>
      <?php endif; ?>
    </div>

    <hr>
    <button onclick="location.href = 'followUser.php';" class="styledBtn" type="submit" name="button">Voeg spelers toe</button>
</div>
  </div>

  <!-- JavaScript -->
  <script type="text/javascript" src="js/scripts.js"></script>

</body>
</html>
