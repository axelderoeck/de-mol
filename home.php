<?php

require_once("includes/phpdefault.php");

$stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

// Get name
if($account["Name"] != null || $account["Name"] != ""){
  $firstname = $account["Name"];
}else{
  $firstname = $account["Username"];
}

?>

<?php include "includes/header.php"; ?>

  <div class="userBox">
    <span><?=$account["Username"]?><span>#<?=$account["Friendcode"]?></span></span>
    <br>
    <span><?=getVotedPoints($account["Id"]) + $account["Score"]?> <i class="fas fa-fingerprint"></i></span>
  </div>

  <h1>Dag <?=$firstname?></h1>

  <div class="buttonsDiv">
    <a href="molboek.php"><i class="fas fa-fingerprint translucent"></i></a>
    <a href="info.php"><i class="fas fa-question-circle translucent"></i></a>
    <a href="friends.php"><i class="fas fa-users translucent"></i></a>
  </div>

  <div class="submitDiv">
    <button onclick="location.href = 'vote.php';" id="stemKnop" class="styledBtn" type="submit">Stemmen</button>
  </div>

  <h2 id="infoTekst"></h2>

  <p class="hiddenField">.- -. - .-. --- .--. --- -. -.-- -- .. .</p>

<?php include "includes/footer.php"; ?>



