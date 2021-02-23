<?php

//include "./demol_database_info.php"; //for development purposes

include "./../demol_database_info.php"; //external file above project folder level

$dbconn = mysqli_connect($servername,$username,$password,$database);

?>
