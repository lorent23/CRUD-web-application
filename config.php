<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'employees');
 
/* Provojmë të lidhemi me databazën */
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Kontrollo lidhjen
if($mysqli === false){
    die("ERROR: Nuk mund te lidhemi me DB " . $mysqli->connect_error);
}
?>