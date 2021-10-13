<?php
/*
require_once("includes/phpdefault.php");

$stmt = $pdo->prepare('SELECT * FROM table_Users');
$stmt->execute();
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($accounts as $account){
    if($account["Friendcode"] == 0){
        // Add unused friendcode to user
        $inserted_friendcode = false;
        $stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Friendcode = ?');
        while($inserted_friendcode == false) {
            // Generate random friendcode
            $friend_code = generateRandomInt(FRIENDCODE_LENGTH);
            // Search for existing account with generated friendcode
            $stmt->execute([ $friend_code ]);
            $accountSearch = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$accountSearch) {
                // Account does not exist -> add friendcode
                $stmt = $pdo->prepare('UPDATE table_Users SET Friendcode = ? WHERE Id = ?');
                $stmt->execute([ $friend_code, $account["Id"] ]);
                $inserted_friendcode = true;
            }
        }
    }
}
*/
?>