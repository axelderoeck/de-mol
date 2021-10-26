<?php

// Function that will connect to the MySQL database
function pdo_connect_mysql() {
  try {
      // Connect to the MySQL database using PDO...
    return new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
  } catch (PDOException $exception) {
    // Could not connect to the MySQL database, if this error occurs make sure you check your db settings are correct!
    exit('Failed to connect to database!');
  }
}

// AWARD FUNCTIONS

function giveAward($accountId, $awardId){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Check if user has this specific award
  $stmt = $pdo->prepare('SELECT * FROM table_UserAwards WHERE UserId = ? AND AwardId = ?');
  $stmt->execute([ $accountId, $awardId ]);
  $has_award = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Action: IF user doesn't have award -> give award
  if(empty($has_award)){
    $stmt = $pdo->prepare('INSERT INTO table_UserAwards (UserId, AwardId)
    VALUES (?, ?)');
    $stmt->execute([ $accountId, $awardId ]);
  }
}

function removeAward($accountId, $awardId){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Delete the award from user
  $stmt = $pdo->prepare('DELETE FROM table_UserAwards WHERE UserId = ? AND AwardId = ?');
  $stmt->execute([ $accountId, $awardId ]);
}


function deleteAward($id){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Delete the award
  $stmt = $pdo->prepare('DELETE FROM table_Awards WHERE Id = ?');
  $stmt->execute([ $id ]);

  // Delete the award from users
  $stmt = $pdo->prepare('DELETE FROM table_UserAwards WHERE AwardId = ?');
  $stmt->execute([ $id ]);

  return (object)[
    'type' => 'success',
    'message' => 'Award is verwijderd'
  ];
}

// ACCOUNT FUNCTIONS

function changePassword($id, $old, $new, $confirmNew){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Select user that initiated change password
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
  $stmt->execute([ $id ]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Check if user exists and password is correct
  if($user && password_verify($old, $user['Password'])){
    if($new == $confirmNew){
      // Hash the password
      $password = password_hash($new, PASSWORD_DEFAULT);
      // Update password from user
      $stmt = $pdo->prepare('UPDATE table_Users SET Password = ? WHERE Id = ?');
      $stmt->execute([ $password, $id ]);
      // Notify user
      $type = "success";
      $message = "Wachtwoord is aangepast.";
    }else{
      // Notify user
      $type = "warning";
      $message = "Wachtwoorden komen niet overeen.";
    }
  }else{
    // Notify user
    $type = "warning";
    $message = "Verkeerd wachtwoord ingegeven.";
  }

  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function changeEmail($id, $email){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search for user with this email
  $stmt = $pdo->prepare('SELECT Email FROM table_Users WHERE Email = ?');
  $stmt->execute([ $email ]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Check if user exists
  if($user){
    // Notify user
    $type = "warning";
    $message = "Dit e-mailadres is al in gebruik.";
  }else{
    // Update user with new email
    $stmt = $pdo->prepare('UPDATE table_Users SET Email = ? WHERE Id = ?');
    $stmt->execute([ $email, $id ]);
    $_SESSION["Email"] = $email;
    // Notify user
    $type = "success";
    $message = "Je e-mailadres is aangepast.";
  }

  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function changeUsername($id, $username){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search user with given name
  $stmt = $pdo->prepare('SELECT Username FROM table_Users WHERE Username = ?');
  $stmt->execute([ $username ]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // User with name doesn't exist -> change name
  if(!$user){
    $stmt = $pdo->prepare('UPDATE table_Users SET Username = ? WHERE Id = ?');
    $stmt->execute([ $username, $id ]);
    $_SESSION["Username"] = $username;
    // Notify user
    $type = "success";
    $message = "Gebruikersnaam is gewijzigd.";
  }else{
    // Notify user
    $type = "warning";
    $message = "Deze gebruikersnaam is al in gebruik.";
  }

  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function changeFriendcode($id, $friendcode){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search user with given friendcode
  $stmt = $pdo->prepare('SELECT Friendcode FROM table_Users WHERE Friendcode = ?');
  $stmt->execute([ $friendcode ]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // User with friendcode doesn't exist -> change friendcode
  if(!$user){
    $stmt = $pdo->prepare('UPDATE table_Users SET Friendcode = ? WHERE Id = ?');
    $stmt->execute([ $friendcode, $id ]);
    // Notify user
    $type = "success";
    $message = "Friendcode is gewijzigd.";
  }else{
    // Notify user
    $type = "warning";
    $message = "Deze friendcode is al in gebruik.";
  }

  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function deleteAccount($id){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Delete the user
  $stmt = $pdo->prepare('DELETE FROM table_Users WHERE Id = ?');
  $stmt->execute([ $id ]);

  // Delete the scores from user
  $stmt = $pdo->prepare('DELETE FROM table_Scores WHERE UserId = ?');
  $stmt->execute([ $id ]);

  // Delete the awards from user
  $stmt = $pdo->prepare('DELETE FROM table_UserAwards WHERE UserId = ?');
  $stmt->execute([ $id ]);

  // Delete the user from friends list
  $stmt = $pdo->prepare('DELETE FROM table_Friends WHERE Id = ? OR IsFriendsWithId = ?');
  $stmt->execute([ $id, $id ]);

  return (object)[
    'type' => 'success',
    'message' => 'Gebruiker is verwijderd'
  ];
}

// OTHER FUNCTIONS

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandomInt($length) {
  $characters = '0123456789';
  $charactersLength = strlen($characters);
  $randomInt = '';
  for ($i = 0; $i < $length; $i++) {
      $randomInt .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomInt;
}

// Encrypt cookie
function encryptCookie($value) {
  $key = hex2bin(openssl_random_pseudo_bytes(4));
  $cipher = "aes-256-cbc";
  $ivlen = openssl_cipher_iv_length($cipher);
  $iv = openssl_random_pseudo_bytes($ivlen);
  $ciphertext = openssl_encrypt($value, $cipher, $key, 0, $iv);

  return (base64_encode($ciphertext . '::' . $iv. '::' .$key));
}

// Decrypt cookie
function decryptCookie($ciphertext) {
  $cipher = "aes-256-cbc";
  list($encrypted_data, $iv,$key) = explode('::', base64_decode($ciphertext));

  return openssl_decrypt($encrypted_data, $cipher, $key, 0, $iv);
}

?>
