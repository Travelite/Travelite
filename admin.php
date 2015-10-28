<?php
require_once("functions.php");
if (!$isLoggedIn && !$isAdmin) {
    header("Location:login.php");
    exit;
}

$submitted = isset($_POST['submit']) ? true : false;
$postTitle = isset($_POST['postTitle']) ? $_POST['postTitle'] : NULL;
$postBody = isset($_POST['postBody']) ? $_POST['postBody'] : NULL;
$responseMsg = NULL;

if ($submitted) {
    $inserted = insertNewPost($myUserID, $postTitle, $postBody);
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
                <form method="post">
                    <label for="postTitle" class="required">Post Title</label>
                    <input type="text" name="postTitle" id="postTitle" value="<?php echo $postTitle; ?>" tabindex="1" required="required">
                    
                    <label for="postBody" class="required">Post Body</label>
                    <textarea name="postBody" id="postBody" rows="20" tabindex="2" required="required"><?php echo $postBody; ?></textarea>
                    
                    <input name="submit" type="submit" value="Post">
                </form>
            </div>
        </section>
    
    </body>
</html>
