<?php
require_once("functions.php");
if (!$isLoggedIn) {
    header("Location:login.php");
    exit;
}

$submitted = isset($_POST['updateUser']) ? true : false;
$updatedImage = isset($_FILES['avatar']) ? true : false;
unset($_POST['updateUser']);

$errorMsg = NULL;
if ($submitted) {
    $submittingDetails = $_POST;
    
    if ($updatedImage) {
        $image = $_FILES['avatar'];    
        $imageTempDir = $image['tmp_name'];
        $imageName = $image['name'];
        $imagesDir = "pictures/";
        $imagePath = $imagesDir . $imageName;

        $uploaded = move_uploaded_file($imageTempDir, $imagePath);
        if ($uploaded) {
            $imageURL = $imagePath;
            $submittingDetails['profileImage'] = $imageURL;
            squareImageAtPath($imagePath, $imagePath, 200);
        }
    }
    
    if (isValidEmail($_POST['emailAddress'])) {
        updateUserForID($myUserID, $submittingDetails);
    } else {
        $errorMsg = "<b>* Invalid email address</b><br><br>";
    }
}

$user = getUserForID($myUserID);
$profilePic = $user['profileImage'] ? $user['profileImage'] : "pictures/default.png";
$fullName = $user['fullName'] ? $user['fullName'] : "No name set";
$username = $user['username'] ? $user['username'] : "No username set";
$emailAdd = $user['emailAddress'] ? $user['emailAddress'] : "No email address set";

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Account Settings</title>
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
    <body>
         <?php echo $htmlNavigation; ?>
        
         <section id="logins" class="body">
            <div id="login" align="center">
                <div id="backing" align="center" style="width:400px;background-color:rgba(32,44,75,0.90);">
                    <header>
                        <h2>Update Account</h2>
                    </header>
                    <?php echo "<img class='profilePic_user' src='". $profilePic ."' alt='Default Profile Pic'><br>$errorMsg"; ?>
                    <form method="post" enctype="multipart/form-data">
                        Avatar: <input type="file" name="avatar"><br><br>
                        <input type="text" name="fullName" style="text-align:center; height:30px;" placeholder="Full Name" value="<?php echo $fullName; ?>">
                        <input type="text" name="username" style="text-align:center; height:30px;" placeholder="Username" value="<?php echo $username; ?>">
                        <input type="text" name="emailAddress" style="text-align:center; height:30px;" placeholder="Email Address" value="<?php echo $emailAdd; ?>">
                        <br>
                        <input type="submit" style="height:35px;" name="updateUser" value="UPDATE">                        
                    </form>
                </div>
            </div>
        </section>
        
    </body>
</html>
