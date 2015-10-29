<?php
require_once("functions.php");

$postID = isset($_GET['id']) ? $_GET['id'] : 0;
$didComment = isset($_POST['submit']) ? true : false;
$comment = isset($_POST['comment']) ? $_POST['comment'] : NULL;

/// Insert new comment
if ($didComment && $postID) {
    if ($myUserID) {
        $commented = insertNewComment($postID, $myUserID, $comment);
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

$postImgURL = $post['imageURL'] ? $post['imageURL'] : NULL;
$postImage = file_exists($postImgURL) ? '<p><img align="center" style="margin-bottom:40px;" class="uploaded_image" width="100%" src="' .$postImgURL. '" alt="Post Image"></p>' : NULL;

/// Comments
$comments = $postID ? getCommentsForPostID($postID) : array();
$commentsCount = count($comments) ? count($comments) : 0;

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Posts</title>
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
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
        <script type="text/javascript" >
            $(function() {
            $(".submit").click(
                function() {
                    var commentID = $("#commentID").val();
                    var commenterID = $("#commenterID").val();
                    var voterID = $("#voterID").val();
                    var vote = $("#vote").val();
                    
                    var dataString = 'commentID='+ commentID + '&commenterID=' + commenterID + '&voterID=' + voterID + '&vote=' + vote;

                    $.ajax({
                        type: "POST",
                        url: "vote.php",
                        data: dataString,
                        success: function(){
                            $('.success').fadeIn(200).show();
                            $('.error').fadeOut(200).hide();
                        }
                    });
                    return false;
                });
            });
        </script>
    </head>
    <body id="index" class="home">
        
        <?php echo $htmlNavigation; ?>
        
        <section id="content" class="body">
            <article class="hentry">
                <header>
                    <?php echo '<h2 class="entry-title"><a href="#" rel="bookmark">' .$title. '</a></h2>'; ?>
                </header>
                
                <footer class="post-info">
                    <abbr class="published"><?php echo "<small>$date</small>"; ?></abbr>
                    <address class="vcard author"> by <?php echo'<a class="url fn" href="user.php?id=' .$authorID. '">' .$authorName. '</a>'; ?></address>
                </footer>
                
                <?php echo $postImage; ?>
                
                <div class="entry-content">
                    <?php echo '<p>' .$body. '</p>'; ?>
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
                        
                        $reportURL = $isLoggedIn ? '<a href="report.php?commentID='.$commentID.'&postID='.$postID.'" target="_blank">Report comment</a>' : NULL;
                        $deleteURL = $isAdmin ? ' - <a href="javascript:confirmCommentDelete(\'?id=' .$postID. '&deleteComment=' .$commentID. '\')">Delete comment</a>' : NULL;
                        $urlsDiv = '<div>' . $reportURL . $deleteURL . '</div>';
                        echo '
                        <li>
                            <article id="' .$commentID. '" class="hentry">
                                <footer class="post-info">
                                    <abbr class="published">' .$timestamp. '</abbr>
                                    <address class="vcard author">by <a class="url fn" href="' .$userURL. '">' .$username. '</a></address>
                                </footer>
                                <div class="entry-content"><p>' .$body. '</p></div>
                                '.$urlsDiv.'
                            </article>
                        </li>'; 
                    }
                ?>
            </ol>

            <div id="respond">
                <h3>Leave a Comment</h3>
                <?php
                if ($isLoggedIn) {
                    echo '<form method="post" id="commentform">
                        <label for="comment" class="required">Your message</label>
                        <textarea name="comment" id="comment" rows="10" tabindex="3" required="required"></textarea>
                        <input name="submit" type="submit" value="Comment">
                    </form>';
                } else {
                    echo "<p><b>* Please login to comment.</b></p>";
                    echo '<form method="post" id="commentform">
                        <label for="comment" class="required">Your message</label>
                        <textarea name="comment" id="comment" rows="10" tabindex="3" required="required" disabled></textarea>
                        <input name="submit" type="submit" value="Comment" disabled>
                    </form>';
                }
                ?>
            </div>
        </section>
        

    
    </body>
</html>
