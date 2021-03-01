<?php

$localdbinfo = './demol_database_info.php';
$onlinedbinfo = './../demol_database_info.php';

if (file_exists($localdbinfo)) {
  include $localdbinfo;
} else {
  include $onlinedbinfo;
}

$dbconn = mysqli_connect($servername,$username,$password,$database);

?>
