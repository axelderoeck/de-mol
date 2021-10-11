<?php

if (isset($_POST["deleteAccount"])){
  // Delete the user
  $stmt = $pdo->prepare('DELETE FROM table_Users WHERE Id = ?');
  $stmt->execute([ $_SESSION["Id"] ]);

  // Delete the scores from user
  $stmt = $pdo->prepare('DELETE FROM table_Scores WHERE UserId = ?');
  $stmt->execute([ $_SESSION["Id"] ]);

  // Delete the awards from user
  $stmt = $pdo->prepare('DELETE FROM table_UserAwards WHERE UserId = ?');
  $stmt->execute([ $_SESSION["Id"] ]);

  // Delete the user from friends list
  $stmt = $pdo->prepare('DELETE FROM table_Friends WHERE Id = ? OR IsFriendsWithId = ?');
  $stmt->execute([ $_SESSION["Id"], $_SESSION["Id"] ]);

  // Reset session
  $_SESSION["Id"] = NULL;
  $_SESSION["Naam"] = "";
  $_SESSION["Gebruikersnaam"] = "";
  $_SESSION["Voted"] = 0;
  $_SESSION["Admin"] = 0;
  session_destroy();
  header('location:index.php');
}


?>
