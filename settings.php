<?php

require_once("includes/phpdefault.php");

// Select all the user info from the id from url
$stmt = $pdo->prepare('SELECT * FROM table_Users WHERE Id = ?');
$stmt->execute([ $_SESSION["Id"] ]);
$account = $stmt->fetch(PDO::FETCH_ASSOC);

// Save settings
if (isset($_POST["saveUserSettings"])){
    // Check if something changed before executing function
    if($_POST["username"] != $account["Username"]){
      $notification = changeUsername($account["Id"], $_POST["username"]);
    }
    if($_POST["firstname"] != $account["Name"]){
      $notification = changeFirstname($account["Id"], $_POST["firstname"]);
    }
    if($_POST["email"] != $account["Email"]){
      $notification = changeEmail($account["Id"], $_POST["email"]);
    }
    // If no errors -> set general success message
    if($notification->type != 'warning'){
      $notification->type = "success";
      $notification->message = "Gegevens opgeslagen";
    }
    // Refresh after 1 second to show the updated info
    header('Refresh:2');
}

// Change password
if (isset($_POST["changePassword"])){
    $notification = changePassword($account["Id"], $_POST["oldPassword"], $_POST["password"], $_POST["confirmPassword"]);
}
// Delete account
if (isset($_POST["deleteAccount"])){
    $notification = deleteAccount($account["Id"]);
    header('location:index.php?logout=1');
}

?>

<?php include "includes/header.php"; ?>

    <div id="popUpDeleteAccount" class="popupStyle translucent">
        <div class="box">
            <a class="closeLink" href="javascript:showPopup('popUpDeleteAccount','hide');">&times;</a>
            <p>Bent u zeker dat u uw account wilt verwijderen?</p>
            <form name="formDeleteAccount" action="" method="post">
                <input type="submit" name="deleteAccount" id="deleteAccount" value="Verwijder">
            </form>
        </div>
    </div>

    <h3 style="margin-top: 100px;">Gegevens</h3>
    <form style="width: 90%; margin: 0 auto;" action="" method="post">
        <label>Gebruikersnaam</label>
        <input name="username" id="username" type="text" value="<?=$account["Username"]?>">
        <br>
        <label>Voornaam (Optioneel)</label>
        <input name="firstname" id="firstname" type="text" value="<?=$account["Name"]?>">
        <br>
        <label>Email</label>
        <input name="email" id="email" type="text" value="<?=$account["Email"]?>">
        <br>
        <input type="submit" name="saveUserSettings" id="saveUserSettings" value="Opslaan">
    </form>

    <h3>Wachtwoord wijzigen</h3>
    <form style="width: 90%; margin: 0 auto 100px auto;" name="formChangePassword" action="" method="post">
        <label>Oud wachtwoord</label>
        <input placeholder="Oud wachtwoord" name="oldPassword" id="oldPassword" type="password" required>
        <br>
        <label>Wachtwoord</label>
        <input placeholder="Wachtwoord" name="password" id="password" type="password" required>
        <br>
        <label>Bevestig Wachtwoord</label>
        <input placeholder="Wachtwoord" name="confirmPassword" id="confirmPassword" type="password" required>
        <br>
        <input type="submit" name="changePassword" id="changePassword" value="Wijzig">
    </form>

<?php include "includes/footer.php"; ?>