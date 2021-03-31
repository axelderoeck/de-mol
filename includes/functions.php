<?php

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

?>
