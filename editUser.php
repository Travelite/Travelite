<?php
require_once("functions.php");
if (!$isLoggedIn) {
    header("Location:login.php");
    exit;
}

if (isset($_POST["submit"])) {
    $submittedDetails = $_POST;
    unset($submittedDetails['submit']);
    $image = $_FILES['avatar'];
    
    $imageTempDir = $image['tmp_name'];
    $imageName = $image['name'];
    $imagesDir = "pictures/";
    $imagePath = $imagesDir . $imageName;
    
    $uploaded = move_uploaded_file($imageTempDir, $imagePath);
    $submittedDetails['profileImage'] = "pictures/default.png"; // set default profileImage path to pictures/default.png
    if ($uploaded) {
        // if it works, insert new image path
        $submittedDetails['profileImage'] = $imagePath; // update image path
    } else {
        // file move failed
        echo "upload failed";
        // leave image path as pictures/default.png
    }
    
    $updated = updateUserForID($_SESSION['user_id'], $submittedDetails); 
    if ($updated) echo "successful";
}
$user = getUserForID($_SESSION['user_id']);

$profilePic = $user['profileImage'] ? $user['profileImage'] : "pictures/default.png";
$fullName = $user['fullName'] ? $user['fullName'] : "No name";
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
    </head>
    <body>
         <?php echo $htmlNavigation; ?>
        
         <section id="logins" class="body">
            <div id="login" align="center">
                <div id="backing" align="center">
                    <header>
                        <h2>Account Settings</h2>
                    </header>
                    <h3 style="color:#F9FAEE;">Change profile picture</h3>
                    <form method="post" id="loginform">
                        <?php
                            echo "<img class='profilePic' width='100' height='100' src='". $profilePic ."' alt='Default Profile Pic'>";
                            echo "<br>";
                        ?>
                    </form>
                    <form method="post" enctype="multipart/form-data" action="editUser.php">
                        <input type="file" name="avatar">
                        <input type="submit" name="submit">
                    </form>
                </div>
            </div>
        </section> 
    </body>
</html>