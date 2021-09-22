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

function giveAward($accountId, $awardId, $dbconn){
  // Query: check if user has award
  $checkIfUserHasAward = $dbconn->query("SELECT *
  FROM table_UserAwards
  WHERE UserId = '$accountId' AND AwardId = $awardId");

  // Query: give user the award
  $giveUserAward = "INSERT INTO table_UserAwards (UserId, AwardId)
  VALUES ($accountId, $awardId)";

  // Action: IF user doesn't have award -> give award
  if($checkIfUserHasAward->num_rows == 0) {
    mysqli_query($dbconn, $giveUserAward);
  }
}

function deleteAward($accountId, $awardId, $dbconn){
  // Query: delete the award
  $deleteUserAward = "DELETE FROM table_UserAwards
  WHERE UserId = '$accountId' AND AwardId = '$awardId'";

  mysqli_query($dbconn, $deleteUserAward);
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
