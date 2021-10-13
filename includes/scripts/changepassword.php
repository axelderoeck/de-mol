<?php

if (isset($_POST["changePassword"])){
  // Select user that initiated change password
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
  $stmt->execute([ $_SESSION["Id"] ]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Check if user exists and password is correct
  if($user && password_verify($_POST['oudWachtwoord'], $user['Wachtwoord'])){
    if($_POST["Wachtwoord"] == $_POST["confirmWachtwoord"]){
      // Hash the password
      $password = password_hash($_POST['Wachtwoord'], PASSWORD_DEFAULT);
      // Update password from user
      $stmt = $pdo->prepare('UPDATE table_Users SET Wachtwoord = ? WHERE Id = ?');
      $stmt->execute([ $password, $_SESSION["Id"] ]);
      // Notify user
      $meldingSoort = "success";
      $foutmelding = "Wachtwoord is aangepast.";
    }else{
      // Notify user
      $meldingSoort = "warning";
      $foutmelding = "Wachtwoorden komen niet overeen.";
    }
  }else{
    // Notify user
    $meldingSoort = "warning";
    $foutmelding = "Verkeerd wachtwoord.";
  }

  header('location:profile.php?u=' . $_SESSION["Id"]);
}

?>
