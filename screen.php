<?php

require_once("includes/phpdefault.php");

$stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT SUM(Score) FROM table_Scores WHERE UserId = ? GROUP BY UserId');
$stmt->execute([ $_SESSION["Id"] ]);
$votedPoints = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT SUM(Score) FROM table_Scores
LEFT JOIN table_Candidates
ON table_Scores.CandidateId = table_Candidates.Id
WHERE table_Candidates.Status = ? AND UserId = ?
GROUP BY UserId');
$stmt->execute([ 0, $_SESSION["Id"] ]);
$lostPoints = $stmt->fetchColumn(0);

$stmt = $pdo->prepare('SELECT * FROM table_Scores
LEFT JOIN table_Candidates
ON table_Scores.CandidateId = table_Candidates.Id
WHERE UserId = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if user has seen results
if($account["SeenResults"] == 0){
  // Check if user has red screen
  $redScreen = false;
  foreach($scores as $score){
    if($score["Status"] == 0 && $score["Score"] > 0){
      $redScreen = true;
      break;
    }
  }

  if($redScreen == true){
    $file_name = "demol_logo_geen_tekst_rood.png";
    $idname = "screenRed";
  }else{
    $file_name = "demol_logo_geen_tekst_groen.png";
    $idname = "screenGreen";
  }

  // Calculate the new score
  $newScore = $account["Score"];
  foreach($scores as $score){
    if($score["Status"] == 1 && $score["Score"] > 0){
      $multiplier = 2;
      $newScore += ($score["Score"] * $multiplier); 
    }
    if($score["Status"] == 0 && $score["Score"] > 0){
      // possible issue
      //$newScore -= $score["Score"];
    }
  }
  
  /*
  // Set new user score
  $stmt = $pdo->prepare('UPDATE table_Users SET Score = ? WHERE Id = ?');
  $stmt->execute([ $newScore, $account["Id"] ]);

  // Reset score table
  $stmt = $pdo->prepare('UPDATE table_Scores SET Score = ? WHERE UserId = ?');
  $stmt->execute([ 0, $account["Id"] ]);

  // Set "SeenResults" to 1 after the query so it will only execute once
  $stmt = $pdo->prepare('UPDATE table_Users SET SeenResults = ? WHERE Id = ?');
  $stmt->execute([ 1, $account["Id"] ]);
*/
}


?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <?php include "includes/headinfo.php"; ?>
</head>

<body>
  <div class="homeScreen" id="main">
    <div class="respContainer" style="height: 100%;">

    <?php if($account["SeenResults"] == 0){ ?>

      <div id="<?=$idname?>" class="screen">
        <img src="img/assets/<?=$file_name?>" alt="logo van de mol">
        <h2>Resultaat</h2>
        <p>Tekst.</p>
        <?php if($account["Score"] > 0): ?>
        <p>Niet gebruikte punten: <?=$account["Score"]?></p>
        <?php endif; ?>
        <div class="results">
          <?php foreach($scores as $score): ?>
            <?php if($score["Status"] == 1 && $score["Score"] > 0): ?>
              <img src="img/kandidaten/<?=$score['Name']?>.jpg" alt="foto van <?=$score['Name']?>" />
            <?php endif; ?>
            <?php if($score["Status"] == 0 && $score["Score"] > 0): ?>
              <img class="candidateOut" src="img/kandidaten/<?=$score['Name']?>.jpg" alt="foto van <?=$score['Name']?>" />
            <?php endif; ?>
          <?php endforeach; ?>
        </div>

        <table>
        <?php foreach($scores as $score): ?>
          <?php if($score["Status"] == 1 && $score["Score"] > 0): ?>
            <tr>
              <td><?=$score["Name"]?></td>
              <td><i class="fas fa-fingerprint color-success"></i></td>
            </tr>
          <?php endif; ?>
          <?php if($score["Status"] == 0 && $score["Score"] > 0): ?>
            <tr>
              <td><?=$score["Name"]?></td>
              <td><i class="fas fa-fingerprint color-warning"></i></td>
            </tr>
          <?php endif; ?>
        <?php endforeach; ?>
            <tr>
              <td>Score</td>
              <td><?=$newScore?></td>
            </tr>
        </table>
        <button onclick="location.href = 'home.php';" class="styledBtn" type="submit">Ga door</button>
      </div>

      <div class="screenNameType">
        <p id="textfield"></p>
      </div>
      <input id="typingName" type="text" value="" readonly>
     

    <?php 
      }else{
        header('location: home.php');
      }
    ?>

    </div>
  </div>

  <!-- JavaScript -->
  <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="slick/slick.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>
  <script src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>

  <script type="text/javascript">
    $(document).ready(function(){
      <?php 
      if($account["SeenResults"] == 0){
        if($account["Name"] != null){
          $firstname = $account["Name"];
        }else{
          $firstname = $account["Username"];
        }
        if($redScreen == true){
          $color = 'red';
        }else{
          $color = 'green';
        } 
      } 
      ?>
      screenAnimation('textfield','<?=$firstname?>','<?=$color?>');
      $('.results').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: false,
        arrows: false,
        centerMode: true,
        centerPadding: '5%',
        focusOnSelect: true,
        draggable: true,
        mobileFirst: true,
        infinite: false
      });
    });
  </script>

</body>
</html>