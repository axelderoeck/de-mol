<?php
    switch(basename($_SERVER['PHP_SELF'])){
        case "profile.php":
            $css_body = "background-image: url(./img/assets/background_dots.jpg)";
            break;
        case "index.php":
            $css_body = "background-image: url(./img/assets/background_dots.jpg)";
            break;
        default:
            break;
    }
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <!-- FAVICON -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="manifest" href="img/favicon/site.webmanifest">
    <link rel="mask-icon" href="img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">
    <!-- CSS -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/theme<?="V" . STYLE_VERSION?>.css">
    <link rel="stylesheet" href="css/navigation<?="V" . STYLE_VERSION?>.css">
    <link rel="stylesheet" href="css/style<?="V" . STYLE_VERSION?>.css">
    <link rel="stylesheet" href="css/desktop<?="V" . STYLE_VERSION?>.css">
    <!-- Slick -->
    <link rel="stylesheet" type="text/css" href="slick/slick.css"/>
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/90f9e5d42f.js" crossorigin="anonymous"></script>
    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js"></script>
    <!-- META -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, user-scalable=no, initial-scale=0.9, shrink-to-fit=no">
    <!-- Show current page name -->
    <title>De Mol - <?=ucwords(explode(".php", basename($_SERVER['PHP_SELF']))[0])?></title>

    <!-- NOTIFICATION LOAD -->
    <script>
        window.addEventListener('load', function() {
            <?php
            $pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache');
            if($pageRefreshed == 1){
                echo "showNotification('$notification->message','$notification->type');"; //message + color style
            }
            ?>
            <?php if(basename($_SERVER['PHP_SELF']) == "home.php"): ?>
                <?php
                $votetime = str_split(VOTE_HOUR, 2);
                $begindate = new DateTime(SEASON_START);
                $enddate = new DateTime(SEASON_END);
                $now = new DateTime();

                if($begindate > $now): ?>
                    stemKnop("uit");
                    infoTekst("Het <span>seizoen</span> is nog niet begonnen.");
                <?php elseif($enddate < $now): ?>
                    stemKnop("uit");
                    infoTekst("Het <span>seizoen</span> is voorbij. <br> <button type='submit' onclick='location.href = `scores.php`;'>Bekijk de scores</button>");
                <?php elseif(date('D') == VOTE_DAY && date('Hi') < VOTE_HOUR): ?>
                    stemKnop("uit");
                    infoTekst("Vanaf <?=$votetime[0] . ":" . $votetime[1]?>u kan je <span>stemmen</span>.");
                    <?php
                        // Reset has voted
                        $stmt = $pdo->prepare('UPDATE table_Users SET Voted = 0, SeenResults = 0');
                        $stmt->execute();
                    ?>
                <?php else: ?>
                    <?php if($account["SeenResults"] == 0 && $account["Voted"] == 0):
                        header('location: screen.php');
                    endif; ?>
                    <?php if($account["Voted"] == 0): ?>
                        stemKnop("aan");
                        infoTekst("Je hebt nog tot en met <span>zaterdag</span> om te stemmen <i class='fas fa-clock'></i>");
                    <?php elseif($account["Voted"] == 1): ?>
                        stemKnop("uit");
                        infoTekst("Je hebt al <span>gestemd</span> <i class='fas fa-check'></i><br>Kom terug na de volgende aflevering!");
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        })
    </script>
</head>
<body style="<?=$css_body?>">
    <?php
        $enddate = new DateTime(SEASON_END);
        $now = new DateTime();
    ?>

    <?php if(basename($_SERVER['PHP_SELF']) != "index.php"): ?>
    <!-- Navigation -->
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><i class="fas fa-times closeIcon"></i></a>
        <a href="home.php"><i class="fas fa-home"></i>Home</a>
        <a href="molboek.php"><i class="fas fa-fingerprint"></i>Molboek</a>
        <a href="info.php"><i class="fas fa-question-circle"></i>Uitleg</a>
        <a href="profile.php?u=<?=$_SESSION['Id']?>"><i class="fas fa-user"></i>Profiel</a>
        <a href="friends.php"><i style="transform: translateX(-5px);" class="fas fa-users"></i>Vrienden</a>
        <a href="groups.php"><i style="transform: translateX(-5px);" class="fas fa-users"></i>Groepen</a>
        <a href="statistics.php"><i class="fas fa-chart-bar"></i>Statistieken</a>
        <a href="scores.php"><i class="fas fa-award"></i>Scores</a>
        <a href="notifications.php"><i class="fas fa-bell"><span><?=getNotificationCount($_SESSION["Id"])?></span></i>Meldingen</a>
        <a href="settings.php"><i class="fas fa-cog"></i>Instellingen</a>
        <?php if ($_SESSION["Admin"] == 1): ?>
            <a href="admin/index.php"><i class="fas fa-hammer"></i>Admin</a>
        <?php endif; ?>
        <a href="index.php?logout=1"><i class="fas fa-sign-out-alt"></i>Uitloggen</a>
        <!-- <img src="img/assets/demol_logo.png" alt="logo de mol"> -->
    </div>
    <span class="navButton" onclick="openNav()"><i class="fas fa-stream"></i></span>
    <?php endif; ?>

    <!-- Dynamic popup -->
    <div id="notification"></div>
    
    <!-- Main Page -->
    <div id="main">
        <div class="respContainer">
          
        <?php if(basename($_SERVER['PHP_SELF']) != "home.php"): ?>
        <?php if(basename($_SERVER['PHP_SELF']) != "index.php"): ?>
            <a onclick="history.go(-1);"><img class="goBackArrow" src="img/assets/arrow.png" alt="arrow"></a>
        <?php endif; ?>
        <?php endif; ?>
