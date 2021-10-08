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
  $pdo = pdo_connect_mysql();
  // Check if user has this specific award
  $stmt = $pdo->prepare('SELECT *
  FROM table_UserAwards
  WHERE UserId = ? AND AwardId = ?');
  $stmt->execute([ $accountId, $awardId ]);
  $has_award = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Action: IF user doesn't have award -> give award
  if(empty($has_award)){
    $stmt = $pdo->prepare('INSERT INTO table_UserAwards (UserId, AwardId)
    VALUES (?, ?)');
    $stmt->execute([ $accountId, $awardId ]);
    $given_award = $stmt->fetch(PDO::FETCH_ASSOC);
  }
}

function deleteAward($accountId, $awardId){
  $pdo = pdo_connect_mysql();
  // Delete the award from user
  $stmt = $pdo->prepare('DELETE FROM table_UserAwards
  WHERE UserId = ? AND AwardId = ?');
  $stmt->execute([ $accountId, $awardId ]);
  $deleted_award = $stmt->fetch(PDO::FETCH_ASSOC);
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

?>
