<?php

require_once("includes/phpdefault.php");

// Get all friends from user
$stmt = $pdo->prepare('SELECT table_Users.Id, Username, Friendcode, Voted, Highscore
                      FROM table_Users
                      LEFT JOIN table_Friends
                      ON table_Users.Id = table_Friends.IsFriendsWithId
                      WHERE table_Friends.Id = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include "includes/header.php"; ?>

    <a href="home.php"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
    <h1>Mollenjagers</h1>
    <p class="example">Hier kan je al jouw mede-mollenjagers vinden. <br>
    <i class='fas fa-check-circle'></i> duid aan wie er al gestemd heeft.</p>

    <div class="deelnemersList">
      <?php if(!empty($friends)): ?>
      <?php $i = 0; foreach($friends as $friend): ?>
      <a class="deelnemerItem info" style="animation-delay: <?=$i/6?>s;" href="profile.php?u=<?=$friend['Id']?>">
        <i class='fas fa-user left'></i>
          <?=$friend["Username"]?>
          <?php if($friend["Voted"] == 1) {echo "<i class='fas fa-check-circle right'></i>";}?>
      </a>
      <?php $i++; endforeach; ?>
      <?php else: ?>
      <h2>Je hebt nog geen spelers toegevoegd.<br>Voeg er toe door op de knop hieronder te klikken.</h2>
      <?php endif; ?>
    </div>

    <hr>
    <button onclick="location.href = 'adduser.php';" class="styledBtn" type="submit" name="button">Voeg spelers toe</button>
    
<?php include "includes/footer.php"; ?>
