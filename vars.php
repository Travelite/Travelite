<?php

session_start();
$myUserID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$isAdmin = isset($_SESSION['admin']) ? $_SESSION['admin'] : 0;
$isLoggedIn = $myUserID ? true : false;

$htmlNavigation = NULL;
if ($isLoggedIn) {
    $htmlNavigation = '
        <header id="banner" class="body">
            <nav><ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="newPost.php">New Post</a></li>
                <li><a href="editUser.php">Profile</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul></nav>
        </header>
        ';
} else {
    $htmlNavigation = '
        <header id="banner" class="body">
            <nav><ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul></nav>
        </header>
        ';
}

?>