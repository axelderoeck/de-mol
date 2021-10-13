<?php

if (isset($_POST["changeName"])){
  // Search user with given name
  $stmt = $pdo->prepare('SELECT Gebruikersnaam FROM table_Users WHERE Gebruikersnaam = ?');
  $stmt->execute([ $_POST["nieuweNaam"] ]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // User with name doesn't exist -> change name
  if(!$user){
    $stmt = $pdo->prepare('UPDATE table_Users SET Gebruikersnaam = ? WHERE Id = ?');
    $stmt->execute([ $_POST["nieuweNaam"], $_SESSION["Id"] ]);
    $_SESSION["Gebruikersnaam"] = $_POST["nieuweNaam"];
  }else{
    $meldingSoort = "warning";
    $foutmelding = "Deze gebruikersnaam is al in gebruik.";
  }

  header('location:profile.php?u=' . $_SESSION["Id"]);
}


?>
