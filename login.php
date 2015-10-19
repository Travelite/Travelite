<?php
include_once 'functions.php';
session_start();

$loggedIn = 1;
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $loggedIn = loginUser($username, $password);
    if ($loggedIn) header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Login</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/main.css" type="text/css" />

        <!--[if IE]>
          <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <!--[if lte IE 7]>
            <link rel="stylesheet" type="text/css" media="all" href="css/ie.css"/>
            <script src="js/IE8.js" type="text/javascript"></script><![endif]-->	
        <!--[if lt IE 7]>
            <link rel="stylesheet" type="text/css" media="all" href="css/ie6.css"/><![endif]-->

    </head>
    <body id="index" class="home">
        
        <header id="banner" class="body">
            <nav><ul>
                <li><a href="#">home</a></li>
                <li><a href="#">posts</a></li>
                <li><a href="#">blog</a></li>
                <li><a href="#">contact</a></li>
                <li><a href="#"> <i class="fa fa-cog"></i></a></li>
            </ul></nav>
        </header>
        
        <section id="logins" class="body">
            <div id="login" align="center">
                <div id="backing" align="center">
                    <header>
                        <h2>LOGIN</h2>
                    </header>
                    <h3 style="color:#F9FAEE;">Sign into your account</h3>
                    <?php if (!$loggedIn) echo "<p>Wrong login details, please try again.</p>"; ?>
                    <form method="post" id="loginform">
                        <input style="font-size:12px;" type="text" name="username" placeholder="Email Address" tabindex="2" required="required">
                        <input style="font-size:12px;" type="password" name="password" placeholder="Password" tabindex="3" required="required">
                        <input name="login" type="submit" value="Log In" style="margin-left:50px; float:left;">
                    </form>
                    <form method="post" id="regform" action="register.php">
                        <input name="register" type="submit" value="Register" style="margin-right:50px; float:right;">
                    </form>
                </div>
            </div>
        </section>
        
        <footer id="contentinfo" class="body">
            <p><?php echo $footerMessage; ?></p>
        </footer>
    
    </body>
</html>
