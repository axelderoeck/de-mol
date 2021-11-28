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

  // Set variables based on result
  if($redScreen == true){
    $file_name = "demol_logo_geen_tekst_rood.png";
    $color = "red";
  }else{
    $file_name = "demol_logo_geen_tekst_groen.png";
    $color = "green";
  }

  // Get name to type out in animation
  if($account["Name"] != null){
    $firstname = $account["Name"];
  }else{
    $firstname = $account["Username"];
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

<body class="voteScreen">
    <!-- <div class="respContainer" style="height: 100%;"> -->

    <?php if($account["SeenResults"] == 0){ ?>
      <!-- <div class="screenContainer"></div> -->
      <div id="screen_<?=$color?>" class="screen">
        <div class="respContainer" style="height: 100%;">
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
      </div>
      
      <div id="screenPage">
        <img src="img/assets/molLogo.png" alt="logo de mol">
        <div id="textfield"></div>  
      </div>

    <?php 
      }else{
        header('location: home.php');
      }
    ?>

  <!-- JavaScript -->
  <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script type="text/javascript" src="js/scripts.js"></script>
  <!-- Slick.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- Typewriter.js -->
  <script type="text/javascript" src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>

  <script type="text/javascript">
    $(document).ready(function(){
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