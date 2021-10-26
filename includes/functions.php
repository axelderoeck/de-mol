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

function deleteAward($accountId, $awardId){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Delete the award from user
  $stmt = $pdo->prepare('DELETE FROM table_UserAwards WHERE UserId = ? AND AwardId = ?');
  $stmt->execute([ $accountId, $awardId ]);
}

function changePassword($id, $old, $new, $confirm){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Select user that initiated change password
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
  $stmt->execute([ $id ]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Check if user exists and password is correct
  if($user && password_verify($old, $user['Wachtwoord'])){
    if($new == $confirm){
      // Hash the password
      $password = password_hash($new, PASSWORD_DEFAULT);
      // Update password from user
      $stmt = $pdo->prepare('UPDATE table_Users SET Wachtwoord = ? WHERE Id = ?');
      $stmt->execute([ $password, $id ]);
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

}

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
