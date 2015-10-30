<?php
ob_start();
require_once("functions.php");

if (!$isLoggedIn && !isAdmin) {
    header("Location:index.php");
    exit;
}

$ban = isset($_GET['ban']) ? $_GET['ban'] : false;
$userID = isset($_GET['id']) ? $_GET['id'] : false;

if ($userID) {
    if ($ban) {
        banUser($userID);
    } else {
        unbanUser($userID);
    }
}

?>