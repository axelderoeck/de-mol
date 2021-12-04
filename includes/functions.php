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

function sendFriendInvite($inviterId, $friendcode){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search for an existing user
  $stmt = $pdo->prepare('SELECT Id FROM table_Users WHERE Friendcode = ?');
  $stmt->execute([ $friendcode ]);
  $invitedId = $stmt->fetchColumn(0);
  // If user exists -> invite
  if($invitedId){
    $stmt = $pdo->prepare('INSERT INTO table_Notifications (NotificationType, InviterId, InvitedId) VALUES (?, ?, ?)');
    $stmt->execute([ 0, $inviterId, $invitedId ]);
    // Notify user
    $message = "Vriendschapsverzoek verzonden.";
    $type = "success";
  }else{
    // Notify user
    $message = "Gebruiker niet gevonden.";
    $type = "warning";
  }
  
  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function sendGroupInvite($groupId, $friendcode){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search for an existing user
  $stmt = $pdo->prepare('SELECT Id FROM table_Users WHERE Friendcode = ?');
  $stmt->execute([ $friendcode ]);
  $invitedId = $stmt->fetchColumn(0);

  // If user exists -> invite
  if($invitedId){
    $stmt = $pdo->prepare('INSERT INTO table_Notifications (NotificationType, GroupId, InvitedId) VALUES (?, ?, ?)');
    $stmt->execute([ 1, $groupId, $invitedId ]);
    // Notify user
    $message = "Groepsverzoek verzonden.";
    $type = "success";
  }else{
    // Notify user
    $message = "Gebruiker niet gevonden.";
    $type = "warning";
  }
  
  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function deleteAccount($id){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search for user
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
  $stmt->execute([ $id ]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // User exists -> delete
  if($user){
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
    // Delete the user from groups
    $stmt = $pdo->prepare('DELETE FROM table_UsersInGroups WHERE UserId = ?');
    $stmt->execute([ $id ]);
    // Notify User
    $type = 'success';
    $message = 'Gebruiker is verwijderd.';
  }else{
    // Notify User
    $type = 'warning';
    $message = 'Gebruiker niet gevonden.';
  }

  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function createGroup($adminId, $name, $description, $private){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search for a group with name
  $stmt = $pdo->prepare('SELECT Name FROM table_Groups WHERE Name = ?');
  $stmt->execute([ $name ]);
  $group = $stmt->fetch(PDO::FETCH_ASSOC);

  // Group doesn't exist -> create group
  if(!$group){
    $stmt = $pdo->prepare('INSERT INTO table_Groups (AdminId, Name, Description, Private) VALUES (?,?,?,?)');
    $stmt->execute([ $adminId, $name, $description, $private ]);
    $group_id = $pdo->lastInsertId();
    addUserToGroup($adminId, $group_id);
    // Notify User
    $type = 'success';
    $message = 'Groep is aangemaakt.';
  }else{
    // Notify User
    $type = 'warning';
    $message = 'Deze groep naam is al in gebruik.';
  }

  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function deleteGroup($groupId){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search for a group with specified id
  $stmt = $pdo->prepare('SELECT * FROM table_Groups WHERE Id = ?');
  $stmt->execute([ $groupId ]);
  $group = $stmt->fetch(PDO::FETCH_ASSOC);

  // Group exists
  if($group){
    // Delete the group
    $stmt = $pdo->prepare('DELETE FROM table_Groups WHERE Id = ?');
    $stmt->execute([ $groupId ]);
    // Delete all the group relationship records
    $stmt = $pdo->prepare('DELETE FROM table_UsersInGroups WHERE GroupId = ?');
    $stmt->execute([ $groupId ]);
    // Notify User
    $type = 'success';
    $message = 'De groep is verwijderd.';
  }else{
    // Notify User
    $type = 'warning';
    $message = 'Groep niet gevonden.';
  }
  
  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function changeGroupName($groupId, $name){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search group
  $stmt = $pdo->prepare('SELECT * FROM table_Groups WHERE Id = ?');
  $stmt->execute([ $groupId ]);
  $mygroup = $stmt->fetch(PDO::FETCH_ASSOC);
  // If group exists
  if($mygroup){
    // Search group with given name
    $stmt = $pdo->prepare('SELECT Name FROM table_Groups WHERE Name = ?');
    $stmt->execute([ $name ]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);
    // Group with name doesn't exist -> change name
    if(!$group){
      $stmt = $pdo->prepare('UPDATE table_Groups SET Name = ? WHERE Id = ?');
      $stmt->execute([ $name, $groupId ]);
      // Notify user
      $type = "success";
      $message = "Groepsnaam is gewijzigd.";
    }else{
      // Notify user
      $type = "warning";
      $message = "Deze groepsnaam is al in gebruik.";
    }
  }else{
    // Notify user
    $type = "warning";
    $message = "Groep niet gevonden.";
  }
  
  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function changeGroupDescription($groupId, $description){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search group
  $stmt = $pdo->prepare('SELECT * FROM table_Groups WHERE Id = ?');
  $stmt->execute([ $groupId ]);
  $group = $stmt->fetch(PDO::FETCH_ASSOC);
  // If group exists
  if($group){
    // Update the description
    $stmt = $pdo->prepare('UPDATE table_Groups SET Description = ? WHERE Id = ?');
    $stmt->execute([ $description, $groupId ]);
    // Notify user
    $type = "success";
    $message = "Beschrijving is gewijzigd.";
  }else{
    $type = "warning";
    $message = "Groep niet gevonden.";
  }
  
  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function changeGroupPrivacy($groupId, $private){
  // DB connection
  $pdo = pdo_connect_mysql();
 
  // Search group
  $stmt = $pdo->prepare('SELECT * FROM table_Groups WHERE Id = ?');
  $stmt->execute([ $groupId ]);
  $group = $stmt->fetch(PDO::FETCH_ASSOC);
  // If group exists
  if($group){
    // Update the description
    $stmt = $pdo->prepare('UPDATE table_Groups SET Private = ? WHERE Id = ?');
    $stmt->execute([ $private, $groupId ]);
    // Notify user
    $type = "success";
    if($private == 0){
      $message = "Groep is publiek gemaakt.";
    }elseif($private == 1){
      $message = "Groep is privé gemaakt.";
    }
  }else{
    $type = "warning";
    $message = "Groep niet gevonden.";
  }
  
  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function confirmFriendInvite($inviterId, $invitedId){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search friendship
  $stmt = $pdo->prepare('SELECT * FROM table_Friends 
  WHERE Id = ? AND IsFriendsWithId = ?
  OR Id = ? AND IsFriendsWithId = ?');
  $stmt->execute([ $inviterId, $invitedId, $invitedId, $inviterId ]);
  $friendship = $stmt->fetch(PDO::FETCH_ASSOC);

  // Friendship doesn't exist -> add
  if(!$friendship){
    // Insert friendship to table
    $stmt = $pdo->prepare('INSERT INTO table_Friends (Id, IsFriendsWithId) VALUES (?, ?)');
    $stmt->execute([ $inviterId, $invitedId ]);
    $stmt->execute([ $invitedId, $inviterId ]);
    // Remove notification
    deleteNotification($invitedId, $inviterId, 0);
    // Notify user
    $message = "Vriendschapsverzoek aanvaard.";
    $type = "success";

    // AWARD_GILLES SECTION
    // Get how many friends a user has
    $stmt = $pdo->prepare('SELECT COUNT(UserId) AS Count
    FROM table_Friends
    WHERE Id = ?
    GROUP BY Id');

    // User 1: If 10 friends -> Give award
    $stmt->execute([ $inviterId ]);
    $amount_friends = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($amount_friends["Count"] >= AWARD_GILLES_AMOUNT) {
      giveAward($inviterId, AWARD_GILLES);
    }
    // User 2: If 10 friends -> Give award
    $stmt->execute([ $invitedId ]);
    $amount_friends = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($amount_friends["Count"] >= AWARD_GILLES_AMOUNT) {
      giveAward($invitedId, AWARD_GILLES);
    }
  }else{
    // Notify user
    $message = "U bent al bevriend met deze gebruiker.";
    $type = "warning";
  }
  
  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function deleteNotification($invitedId, $inviterId, $notificationType){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search notification
  $stmt = $pdo->prepare('SELECT * FROM table_Notifications WHERE InvitedId = ? AND InviterId = ? AND NotificationType = ?');
  $stmt->execute([ $invitedId, $inviterId, $notificationType ]);
  $notification = $stmt->fetch(PDO::FETCH_ASSOC);

  // Notification exists -> delete
  if($notification){
    // Remove notification
    $stmt = $pdo->prepare('DELETE FROM table_Notifications WHERE InvitedId = ? AND InviterId = ? AND NotificationType = ?');
    $stmt->execute([ $invitedId, $inviterId, $notificationType ]);
    // Notify user
    $message = "Verzoek geweigerd."; 
    $type = "success";
  }else{
    // Notify user
    $message = "Verzoek bestaat niet."; 
    $type = "warning";
  }
  
  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function deleteFriend($id, $friendId){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search friendship
  $stmt = $pdo->prepare('SELECT * FROM table_Friends WHERE Id = ? AND IsFriendsWithId = ?');
  $stmt->execute([ $id, $friendId ]);
  $friendship = $stmt->fetch(PDO::FETCH_ASSOC);

  // Friendship exists -> delete
  if($friendship){
    // Remove friendship
    $stmt = $pdo->prepare('DELETE FROM table_Friends WHERE Id = ? AND IsFriendsWithId = ?');
    $stmt->execute([ $id, $friendId ]);
    $stmt->execute([ $friendId, $id ]);
    // Notify user
    $message = "Vriend verwijderd.";
    $type = "success";
  }else{
    // Notify user
    $message = "Vriend niet gevonden om te verwijderen.";
    $type = "warning";
  }
    
  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function addUserToGroup($id, $groupId){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search group
  $stmt = $pdo->prepare('SELECT * FROM table_Groups WHERE Id = ?');
  $stmt->execute([ $groupId ]);
  $group = $stmt->fetch(PDO::FETCH_ASSOC);

  // Group exists
  if($group){
    // Insert user to group
    $stmt = $pdo->prepare('INSERT INTO table_UsersInGroups (UserId, GroupId) VALUES (?, ?)');
    $stmt->execute([ $id, $groupId ]);
    // Remove notification
    deleteNotification($id, $groupId, 1);
    // Notify user
    $message = "Aangesloten bij groep.";
    $type = "success";
  }else{
    // Notify user
    $message = "Groep niet gevonden.";
    $type = "warning";
  }
  
  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

function deleteUserFromGroup($id, $groupId){
  // DB connection
  $pdo = pdo_connect_mysql();

  // Search the user
  $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
  $stmt->execute([ $id ]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Search the group
  $stmt = $pdo->prepare('SELECT * FROM table_Groups WHERE Id = ?');
  $stmt->execute([ $groupId ]);
  $group = $stmt->fetch(PDO::FETCH_ASSOC);

  // Remove user from group
  $stmt = $pdo->prepare('DELETE FROM table_UsersInGroups WHERE UserId = ? AND GroupId = ?');
  $stmt->execute([ $id, $groupId ]);
  // Notify user
  $message = "Groep verlaten.";
  $type = "success";

  // If user is the admin of the group
  if($user["Id"] == $group["AdminId"]){
    // Get the amount of members
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM table_UsersInGroups WHERE GroupId = ? GROUP BY GroupId');
    $stmt->execute([ $groupId ]);
    $members_count = $stmt->fetchColumn(0);
    // If there are members -> assign a new admin
    if($members_count > 0){
      // Search members
      $stmt = $pdo->prepare('SELECT UserId FROM table_UsersInGroups WHERE GroupId = ?');
      $stmt->execute([ $groupId ]);
      $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $i = 0;
      foreach($members as $member){
        if($i == 0){
          // Assign new admin to first member in loop
          $stmt = $pdo->prepare('UPDATE table_Groups SET AdminId = ? WHERE Id = ?');
          $stmt->execute([ $member["UserId"], $group["Id"] ]);
          $i++;
        }else{
          // Stop the loop
          break;
        }
      }
    }else{
      // No members left -> delete group
      deleteGroup($groupId);
    }    
  }
  
  return (object)[
    'type' => $type,
    'message' => $message
  ];
}

/*
function name($variable){
  // DB connection
  $pdo = pdo_connect_mysql();
  
  return (object)[
    'type' => $type,
    'message' => $message
  ];
}
*/

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
