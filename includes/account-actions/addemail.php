<?php

if (isset($_POST["addEmail"])){
  // Search for user with this email
  $stmt = $pdo->prepare('SELECT Email FROM table_Users WHERE Email = ?');
  $stmt->execute([ $_POST["emailvalue"] ]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Check if user exists
  if($user){
    // Notify user
    $meldingSoort = "warning";
    $foutmelding = "Deze email is al in gebruik.";
  }else{
    // Update user with new email
    $stmt = $pdo->prepare('UPDATE table_Users SET Email = ? WHERE Id = ?');
    $stmt->execute([ $_POST["emailvalue"], $_SESSION["Id"] ]);
    $_SESSION["Email"] = $_POST["emailvalue"];
    // Notify user
    $meldingSoort = "success";
    $foutmelding = "Email is aangepast.";
  }

  header('location:profiel.php?u=' . $_SESSION["Id"]);
}


?>
