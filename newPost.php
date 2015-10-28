<?php
require_once("functions.php");
if (!$isLoggedIn) {
    header("Location:login.php");
    exit;
}

$uploadedImg = isset($_FILES['post_image']) ? true : false;
$imageURL = "";

if ($uploadedImg) {
    $image = $_FILES['post_image'];    
    $imageTempDir = $image['tmp_name'];
    $imageName = $image['name'];
    $imagesDir = "user_pictures/";
    $imagePath = $imagesDir . $imageName;
    $thumbPath = $imagesDir . "thumbs/" . $imageName;
    
    $uploaded = move_uploaded_file($imageTempDir, $imagePath);
    if ($uploaded) {
        $imageURL = $imagePath;
        squareImageAtPath($imagePath, $thumbPath, 100);
    }
}

$submitted = isset($_POST['submit']) ? true : false;
$postTitle = isset($_POST['title']) ? $_POST['title'] : NULL;
$postBody = isset($_POST['body']) ? $_POST['body'] : NULL;
$responseMsg = NULL;

if ($submitted) {
    $inserted = insertNewPost($myUserID, $postTitle, $postBody, $imageURL, $thumbPath);
    if ($inserted['success']) {
        header("Location:index.php");
    } else {
        $responseMsg = $inserted['response'] . "<br><br>";
    }
}

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>New Post</title>
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
        
        <?php echo $htmlNavigation; ?>
        
        <section id="content" class="body">
            <div id="respond">
                <?php echo $responseMsg; ?>
                <form method="post" enctype='multipart/form-data'>
                    <label for="postTitle" class="required">Post Title</label>
                    <input type="text" name="title" id="postTitle" value="<?php echo $postTitle; ?>" tabindex="1" required="required">
                    
                    <label for="postBody" class="required">Post Body</label>
                    <textarea name="body" id="postBody" rows="20" tabindex="2" required="required"><?php echo $postBody; ?></textarea>
                    
                    <input type="file" name="post_image">
                    <input name="submit" type="submit" value="Post">
                </form>
            </div>
        </section>
    
    </body>
</html>
