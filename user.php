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
    if (!$isMyProfile) {
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

$profilePic = $user['profileImage'] ? $user['profileImage'] : NULL;
$profilePic = file_exists($profilePic) ? $profilePic : "images/default.png";

$fullName = $user['fullName'] ? $user['fullName'] : "No name";
$username = $user['username'] ? $user['username'] : "No username";
$emailAddress = $user['emailAddress'] ? $user['emailAddress'] : "No email address";
$date = $user['registerDate'] ? wordedTimestamp($user['registerDate'], false) : "Never registered";
$banned = $user['banned'] ? "Banned" : "Good";

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
        
        <section id="logins" class="body">
            <div id="login" align="center">
                <div id="backing" align="center" style="width:400px;background-color:rgba(32,44,75,0.90);">
                    <?php 
                    echo "<header><h2>$username</h2></header>";
                    
                    if ($error !== NULL) {
                        echo $error;
                    } else {
                        echo '<img class="profilePic_user" align="middle" style="margin-top:20px;" src="'. $profilePic .'" alt="Profile Pic"><br>';
                        echo '<table border="0">
                            <tr><td style="text-align:right; width:50%"><b>Full Name:</b></td><td>'.$fullName.'<br></td></tr>
                            <tr><td style="text-align:right;"><b>Email Address:</b></td><td>'.$emailAddress.'<br></td></tr>
                            <tr><td style="text-align:right;"><b>Registered Since:</b></td><td>'.$date.'</td></tr>
                            <tr><td style="text-align:right;"><b>Standing:</b></td><td>'.$banned.'<br></td></tr>
                            </table>';
                        if ($isMyProfile) echo '<form action="editUser.php" method="post"><input type="submit" style="height:35px;" value="EDIT PROFILE"></form>';
                        echo '</div>';
                    }
                    echo $url;
                    ?>
                </div>
            </div>
        </section>
        
    </body>
</html>