<?php
define("SERVERNAME", "sql189.main-hosting.eu"); //lokaal: sql189.main-hosting.eu, online: localhost
define("USERNAME", "u939917173_Aksol");
define("PASSWORD", "Axelsnow1973");
define("DATABASE", "u939917173_demol");
//connectie maken met de databank
$dbconn = mysqli_connect(SERVERNAME,USERNAME,PASSWORD,DATABASE);

 //testen of de connectie is gemaakt
 //var_dump($dbconn);


?>
