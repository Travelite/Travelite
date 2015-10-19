<?php
include_once 'functions.php';
session_start();

$myUserID = $_SESSION['user_id'] ? $_SESSION['user_id'] : 0;
$isAdmin = $_SESSION['admin'] ? $_SESSION['admin'] : 0;
$didComment = $_POST['submit'] ? true : false;
$postID = $_GET['id'] ? $_GET['id'] : 0;

/// Insert new comment
if ($didComment && $postID) {
    if ($myUserID) {
        $commented = newCommentForPostID($_GET['id'], $_SESSION['user_id'], $_POST['comment']);
    } else {
        // Not logged in
    }
}

/// Post details
$title = "Untitled";
$body = "Post not found!";
$date = "A post";
$authorID = "0";
$authorName = "no one";

if ($postID) {
    $post = getPostForID($postID);
    $title = $post['title'];
    $body = $post['body'];
    $date = wordedTimestamp($post['timestamp'], false);
    
    $authorID = $post['user_id'];
    $author = getUserForID($authorID);
    $authorName = $author['username'];
}

/// Comments
$comments = $postID ? getCommentsForPostID($postID) : array();
$commentsCount = count($comments) ? count($comments) : 0;

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Smashing HTML5!</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/main.css" type="text/css" />

        <!--[if IE]>
          <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <!--[if lte IE 7]>
            <link rel="stylesheet" type="text/css" media="all" href="css/ie.css"/>
            <script src="js/IE8.js" type="text/javascript"></script><![endif]-->	
        <!--[if lt IE 7]>
            <link rel="stylesheet" type="text/css" media="all" href="css/ie6.css"/><![endif]-->
        <script>
            function confirmCommentDelete(url) {
                if (confirm("Are you sure you want to delete this comment? This can't be undone!")) {
                    window.location.replace(url);
                } else {
                    false;
                }       
            }
        </script>
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
        
        
        <section id="content" class="body">
            <article class="hentry">
                <header>
                    <?php echo '<h2 class="entry-title"><a href="#" rel="bookmark">' .$title. '</a></h2>'; ?>
                </header>
                
                <footer class="post-info">
                    <abbr class="published"><?php echo "<small>$date</small>"; ?></abbr>
                    <address class="vcard author"> by <?php echo'<a class="url fn" href="user?id=' .$authorID. '">' .$authorName. '</a>'; ?></address>
                </footer>
                
                <div class="entry-content">
                    <?php echo '<p>' .$body. '<p>'; ?>
                </div>
            </article>
        </section>
        
        
        <section id="comments" class="body">
            <header>
                <h2><?php echo $commentsCount; ?> Comments</h2>
            </header>
            
            <ol id="posts-list" class="hfeed">
                <?php
                    foreach ($comments as $comment) {
                        $timestamp = wordedTimestamp($comment['timestamp'], true);
                        $userID = $comment['user_id'];
                        $user = getUserForID($userID);
                        $userURL = "user.php?id=$userID";
                        $username = $user['username'];
                        $body = $comment['comment'];
                        $commentID = $comment['comment_id'];
                        echo '
                        <li>
                            <article id="' .$commentID. '" class="hentry">
                                <footer class="post-info">
                                    <abbr class="published">' .$timestamp. '</abbr>
                                    <address class="vcard author">by <a class="url fn" href="' .$userURL. '">' .$username. '</a></address>
                                </footer>
                                <div class="entry-content"><p>' .$body. '</p></div>
                                <div><a href="javascript:confirmCommentDelete(\'?id=' .$postID. '&deleteComment=' .$commentID. '\')">Delete comment</a></div>
                            </article>
                        </li>'; 
                    }
                ?>
            </ol>
            <div id="respond">
                <h3>Leave a Comment</h3>
                <form method="post" id="commentform">
                    <label for="comment_author" class="required">Your name</label>
                    <input type="text" name="fullName" id="comment_author" value="" tabindex="1" required="required">
                    <label for="email" class="required">Your email address</label>
                    <input type="email" name="emailAddress" id="email" value="" tabindex="2" required="required">
                    <label for="comment" class="required">Your message</label>
                    <textarea name="comment" id="comment" rows="10" tabindex="3" required="required"></textarea>
                    <input name="submit" type="submit" value="Comment">
                </form>
            </div>
        </section>
        
        <footer id="contentinfo" class="body">
            <p><?php echo $footerMessage; ?></p>
        </footer>
    
    </body>
</html>
