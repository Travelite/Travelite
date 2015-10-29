<?php
require_once("functions.php");
session_start();

$posts = getAllPosts();

?>

<!DOCTYPE html>
<html lang="en-US">
<head>
	<title>Home</title>
	<meta charset="utf-8" />
	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/main.css" type="text/css" />

	<!--[if IE]>
	  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<!--[if lte IE 7]>
		<link rel="stylesheet" type="text/css" media="all" href="css/ie.css"/>
		<script src="js/IE8.js" type="text/javascript"></script><![endif]-->	
	<!--[if lt IE 7]>
		<link rel="stylesheet" type="text/css" media="all" href="css/ie6.css"/><![endif]-->

</head>

<body id="index" class="home">
	
	<?php echo $htmlNavigation;
    
        if (!$posts) {
            echo'
                <section id="content" class="body">
                <article class="hentry">
                    <header> 
                    <h2 class="entry-title">No Posts Found!</a></h2>
                    </header>
                </article>
                </section>
                ';
        } else {
            
            // Loop through posts
            foreach ($posts as $post) {
                $timestamp = wordedTimestamp($post['timestamp'], true);
                $postURL = "post.php?id=" . $post['post_id'];
                $postBody = $post['body'];
                $userImg = strlen($post['thumbURL']) ? '<img class="uploaded_image" height="100" width="100" src="' .$post['thumbURL']. '" alt="Uploaded Image">' : NULL;
                if (strlen($postBody) > 256) {
                    $postBody = substr($postBody, 0, 256);
                    $postBody .= '... <a href="' .$postURL. '">Read more</a>';
                }

                $user = getUserForID($post['user_id']);
                $userURL = "user.php?id=" . $user['user_id'];
                $authorName = $user['username'];
                echo '
                <section id="content" class="body">
                    <article class="hentry">
                        <header>
                            <h2 class="entry-title"><a href="' .$postURL. '" rel="bookmark">' .$post['title']. '</a></h2>
                        </header>
                        <footer class="post-info">
                            <abbr class="published">' .$timestamp. '</abbr>
                            <address class="vcard author">by <a class="url fn" href="' .$userURL. '">' .$authorName. '</a></address>
                        </footer>'.$userImg.
                        '<div class="entry-content"><p>' .$postBody. '</p></div>
                    </article>
                </section>
                ';
            }
        }
    ?>  
</body>
</html>
