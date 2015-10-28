<?php

require_once("functions.php");


$user = getUserForID($_SESSION['user_id']);
$profilePic = $user['profileImage'] ? $user['profileImage'] : "pictures/default.png";
$fullName = $user['fullName'] ? $user['fullName'] : "No name";
$username = $user['username'] ? $user['username'] : "No username";
$emailAddress = $user['emailAddress'] ? $user['emailAddress'] : "No email address";
$date = wordedTimestamp($user['registerDate'], false);

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
                            echo "<img class='profilePic_user' src='". $profilePic ."' alt='Default Profile Pic'>";
                            echo "<br>";
                            echo 
                                "<p> 
                                Full Name: ".$fullName."<br>
                                Username: ".$username."<br>
                                Email Address: ".$emailAddress."<br>
                                Registered Since: ".$date.                                
                                "</p>";
                        ?>
                    </div>
                </article>
            </div>
        </section>
        
    </body>
</html>
