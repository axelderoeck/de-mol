<?php 

// Include the database info
include_once("./db/db_info.php");
// Set the timezone
date_default_timezone_set('Europe/Brussels');
define('STYLE_VERSION', 5);

/* DATABASE SETTINGS */
// Database hostname
define('DB_HOST', $servername);
// Database username
define('DB_USER', $username);
// Database password
define('DB_PASS', $password);
// Database name
define('DB_NAME', $database);

/* REGISTER SETTINGS */
// Minimum characters username
define('MIN_CHAR_USERNAME', 3);
// Maximum characters username
define('MAX_CHAR_USERNAME', 20);
// Minimum characters password
define('MIN_CHAR_PASSWORD', 3);
// Maximum characters password
define('MAX_CHAR_PASSWORD', 20);

/* TIME SETTINGS */
// Start of the season date
define('SEASON_START', "03/21/2021"); // format: MM/DD/YYYY
// End of the season date
define('SEASON_END', "05/09/2022"); // format: MM/DD/YYYY
// Day of the episode
define('VOTE_DAY', "Mon"); // format: Mon, Tue, Wed, Thu, Fri, Sat, Sun
// Vote hour
define('VOTE_HOUR', "2200"); // format: HHMM

/* OTHER SETTINGS */
// Length of the random generated friendcode
define('FRIENDCODE_LENGTH', 5);
// Maximum amount of users in the top list
define('LIMIT_TOPLIST', 20);
// Amount of candidates
define('CANDIDATES_AMOUNT', 10);
// Days untill remember me cookie expires
define('REMEMBER_ME_EXPIRE_DAYS', 30);

/* AWARDS */
// Award Jij weet niets id
define('AWARD_WEETNIETS', 5);
// Award tunnelvisie id
define('AWARD_TUNNELVISIE', 8);
// Award all in id
define('AWARD_ALL_IN', 9);
// Award gilles id
define('AWARD_GILLES', 10);
// Award mol id
define('AWARD_SECRET_MOL', 11);

/* EDITION AWARDS */
// Award winnaar id
define('AWARD_WINNAAR', 1);
// Award topper id
define('AWARD_TOPPER', 2);
// Award deelnemer id
define('AWARD_DEELNEMER', 4);

/* AWARDS CRITERIA */
// Award mol code
define('AWARD_SECRET_MOL_CODE', "OK4LIVX3");
// Award tunnelvisie amount
define('AWARD_TUNNELVISIE_AMOUNT', 45);
// Award gilles amount
define('AWARD_GILLES_AMOUNT', 10);
// Award all in amount
define('AWARD_ALL_IN_AMOUNT', 10);

?>