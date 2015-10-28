<?php
require_once("functions.php");

$error = NULL;
$user = [];
$userID = 0;
$isMyProfile = false;

if ($_GET['id']) {
    $userID = $_GET['id'];
    $user = getUserForID($userID);
    if ($userID === $myUserID) $isMyProfile = true;
} else if ($myUserID) {
    $user = getUserForID($myUserID);
    $isMyProfile = true;
} else {
    $error = "Please sign in or create an account to see the profile";
}

$isBanned = $user['banned'];

$url = NULL;
if ($isLoggedIn) {
    if ($isMyProfile) {
        $url = '<p><a href="editProfile.php">Edit My Profile</a></p>';
    } else {
        $url = NULL;
        if ($userID) {
            $reportUrl = '<a href="report.php?userID='.$userID.'">Report User</a>';
            $banUrl = NULL;
            if ($isAdmin) {
                if ($isBanned) {
                    $banUrl = ' - <a href="banUser.php?ban=0&id='.$userID.'">Unban User</a>';
                } else {
                    $banUrl = ' - <a href="banUser.php?ban=1&id='.$userID.'">Ban User</a>';
                }
            }
            $url = '<p>' .$reportUrl . $banUrl. '</p>';
        }
    }
}

$profilePic = $user['profileImage'] ? $user['profileImage'] : "pictures/default.png";
$fullName = $user['fullName'] ? $user['fullName'] : "No name";
$username = $user['username'] ? $user['username'] : "No username";
$emailAddress = $user['emailAddress'] ? $user['emailAddress'] : "No email address";
$date = $user['registerDate'] ? wordedTimestamp($user['registerDate'], false) : "Never registered";

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Profile</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/main.css" type="text/css" />
    </head>
    <body id="index" class="home">
        
        <?php echo $htmlNavigation; ?>
        
        <section id="content" class="body">
            <div id="user" align="center">
                <article class="hentry">
                    <div class="entry-content">
                        <?php 
                        if ($error !== NULL) {
                            echo $error;
                        } else {
                            echo "<img class='profilePic_user' src='". $profilePic ."' alt='Default Profile Pic'>";
                            echo "<br>";
                            echo 
                                "<p> 
                                Full Name: ".$fullName."<br>
                                Username: ".$username."<br>
                                Email Address: ".$emailAddress."<br>
                                Registered Since: ".$date.                                
                                "</p>";
                            }
                            
                             echo $url;
                                
                        ?>
                    </div>
                </article>
            </div>
        </section>
        
    </body>
</html>