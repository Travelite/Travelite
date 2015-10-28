<?php
require_once("functions.php");
if (!$isLoggedIn) {
    header("Location:login.php");
    exit;
}

$submitted = isset($_POST['submit']) ? true : false;
$report = isset($_GET['report']) ? $_GET['report'] : false;
$reason = isset($_GET['reason']) ? $_GET['reason'] : NULL;

$userID = isset($_GET['id']) ? $_GET['id'] : 0;
$commentID = isset($_GET['id']) ? $_GET['id'] : 0;
$postID = isset($_GET['id']) ? $_GET['id'] : 0;

$responseMsg = NULL;
$reportingMsg = "<p>Cannot report user/comment at this moment.</p>";

if ($report) {
    if ($commentID && $postID) {
        // Reporting comment
        $user = getUserForID($id);
        $url = 'post.php?id=' .$postID. '#' .$commentID;
        $reportingMsg = '<p>Reporting comment <a href="'.$url.'">'.$commentID.'</a></p>';

    } else if ($userID && !$commentID) {
        // Reporting user
        $user = getUserForID($id);
        $url = 'user.php?id=' .$id;
        $reportingMsg = '<p>Reporting user <a href="'.$url.'">'.$user['username'].'</a></p>';
    }
} else {
    #header("Location:index.php");
    #exit;
}

if ($submitted) {
    if ($report === "user") {
        
    } else if ($report === "comment") {
        
    }
}

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Report</title>
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
                <?php echo $reportingMsg . $responseMsg; ?>
                <form method="post">
                    
                    <label for="reportReason" class="required">Report Reason:</label>
                    <textarea name="reportReason" id="reportReason" rows="5" tabindex="2" required="required"><?php echo $reason; ?></textarea>
                    
                    <input name="submit" type="submit" value="Report">
                </form>
            </div>
        </section>
    
    </body>
</html>
