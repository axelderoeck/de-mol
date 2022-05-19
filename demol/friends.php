<?php

require_once("includes/phpdefault.php");

// Get all friends from user
$stmt = $pdo->prepare('SELECT *
                      FROM table_Users
                      LEFT JOIN table_Friends
                      ON table_Users.Id = table_Friends.IsFriendsWithId
                      WHERE table_Friends.Id = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include "includes/header.php"; ?>

    <h1>Vrienden</h1>
    <p class="example">Hier kan je al jouw mede-mollenjagers vinden.</p>

    <button onclick="location.href = 'adduser.php';" class="styledBtn" type="submit" name="button">Voeg vrienden toe</button>

    <?php if(!empty($friends)): ?>
      <?php $i = 0; foreach($friends as $friend): ?>
        <a href="profile.php?u=<?=$friend["IsFriendsWithId"]?>">
          <div style="animation-delay: <?=$i/4;?>s;" class="displayUser">
            <div>
              <span><?=getVotedPoints($friend["IsFriendsWithId"]) + $friend["Score"]?></span>
              <?php if($friend["Screen"] == 0): ?>
                <img src="img/assets/demol_logo_geen_tekst_groen.png" alt="de mol logo">
              <?php elseif($friend["Screen"] == 1): ?>
                <img src="img/assets/demol_logo_geen_tekst_rood.png" alt="de mol logo">
              <?php else: ?>
                <img src="img/assets/demol_logo_geen_tekst.png" alt="de mol logo">
              <?php endif; ?>
            </div>
            <span><?=$friend["Username"]?></span>
          </div>
        </a>
      <?php $i++; endforeach; ?>
    <?php else: ?>
      <p style="text-align: center !important;">Je hebt nog geen vrienden</p>
    <?php endif; ?>
    
<?php include "includes/footer.php"; ?>
