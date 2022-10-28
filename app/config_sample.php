<?php
/**
 * mmilan, website that manage e-sport teams
 * Propulsed by Arthur Mondon.
 *
 * @author     Arthur Mondon
 *
 * Contributors :
 * -
 *
 */

if(!defined('MyConst')) {
   die('Direct access not permitted');
}
define('DB_SERVER', 'server_url');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');

setlocale (LC_TIME, 'fr_FR.utf8','fra');
date_default_timezone_set('Europe/Paris');

$conn2 = new PDO("mysql:dbname=" . DB_NAME . ";host=" . DB_SERVER, DB_USERNAME, DB_PASSWORD);
$conn2 -> exec("set names utf8");

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($conn === false){
    die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
}
$conn->set_charset("utf8");

$settings = [];
$sql = "SELECT * FROM settings";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($config = $result->fetch_assoc()) {
        $settings[$config['SettingsName']] = $config['SettingsValue'];
    }
}

function generateRandomString($length = 20){
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
function generateRandomNumber($length = 20){
    return substr(str_shuffle(str_repeat($x = '0123456789', ceil($length / strlen($x)))), 1, $length);
}
function getDayName($dayOfWeek) {
    switch ($dayOfWeek){
        case 1:
            return 'Lundi';
        case 2:
            return 'Mardi';
        case 3:
            return 'Mercredi';
        case 4:
            return 'Jeudi';
        case 5:
            return 'Vendredi';
        case 6:
            return 'Samedi';
        case 7:
            return 'Dimanche';
        default:
            return '';
    }
}
function getMonthName($monthOfYear) {
    switch ($monthOfYear){
        case 1:
            return 'Janvier';
        case 2:
            return 'Février';
        case 3:
            return 'Mars';
        case 4:
            return 'Avril';
        case 5:
            return 'Mai';
        case 6:
            return 'Juin';
        case 7:
            return 'Juillet';
        case 8:
            return 'Août';
        case 9:
            return 'Septembre';
        case 10:
            return 'Octobre';
        case 11:
            return 'Novembre';
        case 12:
            return 'Décembre';
        default:
            return '';
    }
}
?>