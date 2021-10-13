<?php
$enddate = new DateTime(SEASON_END);
$now = new DateTime();

// Get all notifications for logged in user
$stmt = $pdo->prepare('SELECT COUNT(*) FROM table_Notifications WHERE InvitedId = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$amount_notifications = $stmt->fetchColumn(0);
?>

<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fas fa-times closeIcon"></i></a>
  <a href="home.php"><i class="fas fa-home"></i>Home</a>
  <a href="molboek.php"><i class="fas fa-fingerprint"></i>Molboek</a>
  <a href="info.php"><i class="fas fa-question-circle"></i>Uitleg</a>
  <a href="profile.php?u=<?=$_SESSION['Id']?>"><i class="fas fa-user"></i>Profiel</a>
  <a href="friends.php"><i style="transform: translateX(-5px);" class="fas fa-users"></i>Vrienden</a>
  <a href="statistics.php"><i class="fas fa-chart-bar"></i>Statistieken</a>
  <a href="notifications.php"><i class="fas fa-bell"><span><?=$amount_notifications?></span></i>Meldingen</a>
  <?php if ($_SESSION["Admin"] == 1): ?>
    <a href="admin/index.php"><i class="fas fa-hammer"></i>Admin</a>
  <?php endif; ?>
  <a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i>Uitloggen</a>
  <img src="img/assets/molLogo.png" alt="logo de mol">
</div>

<span class="navButton" onclick="openNav()"><i class="fas fa-stream"></i></span>
