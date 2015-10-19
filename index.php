<?php
include_once 'functions.php';

?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<title>Smashing HTML5!</title>
	<meta charset="utf-8" />
	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
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
			<li class="active"><a href="index.php">home</a></li>
			<li><a href="#">portfolio</a></li>
            <li><a href="#"> <i class="fa fa-cog"></i></a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="editUser.php">Edit Profile</a></li>
               
		</ul></nav>
	</header>
	
	<section id="content" class="body">
	  
	  <article class="hentry">	
			<header>
				<h2 class="entry-title"><a href="#" rel="bookmark" title="Permalink to this Building a Pusher-powered Real-Time Commenting System">Building a Pusher-powered Real-Time Commenting System</a></h2>
			</header>
			
			<footer class="post-info">
				<abbr class="published" title="2012-02-10T14:07:00-07:00">
					10th February 2012
				</abbr>

				<address class="vcard author">
					By <a class="url fn" href="#">Phil Leggetter</a>
				</address>
			</footer>
			
			<div class="entry-content">
				<p>The web has become increasingly interactive over the years. This trend is set to continue with the next generation of applications driven by the <strong>real-time web</strong>. Adding real-time functionality to an application can result in a more interactive and engaging user experience. However, setting up and maintaining the server-side realtime components can be an unwanted distraction. But don't worry, there is a solution.</p>
			</div>
		</article>
			
	</section>
	
	<section id="comments" class="body">
	  
	  <header>
			<h2>Comments</h2>
		</header>

    <ol id="posts-list" class="hfeed">
      <li><article id="comment_1" class="hentry">	
				<footer class="post-info">
					<abbr class="published" title="Thu, 23 Feb 2012 23:54:46 +0000">
						23 February 2012
					</abbr>

					<address class="vcard author">
						By <a class="url fn" href="#">Phil Leggetter</a>
					</address>
				</footer>

				<div class="entry-content">
					<p>The Realtime Web Rocks!</p>
				</div>
			</article></li>
		</ol>
        
        <div id="respond">

          <h3>Leave a Comment </h3>

          <form action="post_comment.php" method="post" id="commentform">

            <label for="comment_author" class="required">Your name</label>
            <input type="text" name="comment_author" id="comment_author" value="" tabindex="1" required="required">

            <label for="email" class="required">Your email;</label>
            <input type="email" name="email" id="email" value="" tabindex="2" required="required">

            <label for="comment" class="required">Your message</label>
              <i class="fa fa-chevron-up"></i><br /><i class="fa fa-chevron-down"></i>
            <textarea name="comment" id="comment" rows="10" tabindex="4"  required="required"></textarea>

            <-- comment_post_ID value hard-coded as 1 -->
            <input type="hidden" name="comment_post_ID" value="1" id="comment_post_ID" />
            <input name="submit" type="submit" value="Submit comment" />

          </form>

        </div>
	</section>
        
	<footer id="contentinfo" class="body">		
		<p>2015 voltage designs.</p>
	</footer>
</body>
</html>
