<?php
include_once 'functions.php';

    if (isset($_POST['registerUser'])) {
        $fullName= $_POST['fullName'];
        $username= $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        
        $success = registerUser($fullName, $username, $password, $email);
        if ($success) {
            header("Location:index.php");
        } else {
            echo "Register unseccessful!";
        }
    }

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Register</title>
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
            </ul></nav>
        </header>
        
        <section id="logins" class="body">
            <div id="login" align="center">
                <div id="backing" align="center">
                    <header>
                        <h2>REGISTER</h2>
                    </header>
                    <h3 style="color:#F9FAEE;">Register an account</h3>
                    <form method="post" id="regform">
                        <input style="font-size:12px;" type="text" name="fullName" placeholder="Name and Surname" tabindex="1" required="required">
                        <input style="font-size:12px;" type="text" name="username" placeholder="Username" tabindex="2" required="required">
                        <input style="font-size:12px;" type="text" name="email" placeholder="Email Address" tabindex="3" required="required">
                        <input style="font-size:12px;" type="password" name="password" placeholder="Password" tabindex="4" required="required">
                        <input name="registerUser" type="submit" value="Register" style="margin-right:50px; float:right;">
                    </form>
                    <form method="post" id="loginform" action="login.php">
                        <input name="login" type="submit" value="Log In" style="margin-left:50px; float:left;">
                    </form>
                </div>
            </div>
        </section>
        
        <footer id="contentinfo" class="body">
            <p><?php echo $footerMessage; ?></p>
        </footer>
    
    </body>
</html>
