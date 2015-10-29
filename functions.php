<?php
require_once("vars.php");



/// D A T A B A S E ///

# Return the database connection
function dbConnection() {
    $connection = mysqli_connect("us-cdbr-iron-east-03.cleardb.net", "b0dd329ce7c575", "b696a200", "heroku_c70494bd637bbce");
    return $connection;
}

# Return results from SQL query
function dbResultFromQuery($query) {
    $connection = dbConnection();
    if (!$connection) die("MySQL Connection failed : " . mysqli_connect_error());

    $result = 0;
    if (!empty($query)) $result = mysqli_query($connection, $query)or die("<p>".mysqli_error($connection)."</p>");
    mysqli_close($connection);
    return $result;
}



/// U S E R S ///

function registerUser($fullName, $username, $password, $email) {
    
    // Check if required fields are empty
    if (empty($fullName) || empty($username) || empty($password) || empty($email)) {
        return returnResponse(0, "Registration failed, please complete all required fields.");
    }
    
    // Check if password is bit more complex
    if (strlen($password) < 6) {
        return returnResponse(0, "Registration failed, minimum password length is 6 characters.");
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return returnResponse(0, "Registration failed, password should at least contain 1 upper case.");
    }
    if (!preg_match('/[0-9]/', $password)) {
        return returnResponse(0, "Registration failed, password should at least contain 1 digit.");
    }
    
    // Encrypt
    $password = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if email address is valid
    $email = trim($email);
    if (!isValidEmail($email)) {
        return returnResponse(0, "Registration failed, invalid email address.");
    }
    
    $result = dbResultFromQuery("SELECT username, emailAddress FROM users WHERE username='$username' OR emailAddress='$email' LIMIT 1;", $username, $email);
    if ($result->num_rows > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($user['username'] === $username) {
            return returnResponse(0, "Registration failed, username already exists.");
        } else {
            return returnResponse(0, "Registration failed, email already exists.");
        }
    }
    
    $result = dbResultFromQuery("INSERT INTO users (fullName, username, password, emailAddress) VALUES ('$fullName', '$username', '$password', '$email');");
    if ($result) {
        return returnResponse(1, "Registration successful.");
    } else {
        return returnResponse(0, "Registration failed, please try again.");
    }
}

function loginUser($username, $password) {
    // Check if username or email address exist
    $result = dbResultFromQuery("SELECT * FROM users WHERE emailAddress='$username' OR username='$username' LIMIT 1;");
    if ($result->num_rows === 0) {
        return returnResponse(0, "Login failed, no such user.");
    }
    
    $user = mysqli_fetch_assoc($result);
    
    $userID = $user['user_id'];
    $hashedPassword = $user['password'];

    // Compare the given password with database password
    $passwordMatch = password_verify($password, $hashedPassword);   
    if (!$passwordMatch) {
        return returnResponse(0, "Login failed, incorrect password.");
    }
    
    if ($user['banned']) {
        $_SESSION['banned'] = $user['banned'];
        return returnResponse(0, "Login failed, you have been banned. Please contact the administrator.");
    } else {
        // Assign values to the session array
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['admin'] = $user['admin'];
        return returnResponse(1, "Login successful.", $user);
    }
}

function getUserForID($userID) {
    $result = dbResultFromQuery("SELECT * FROM users WHERE user_id='$userID';");
    $user = mysqli_fetch_assoc($result);
    return $user;
}

function updateUserForID($userID, $userDetails) {
    $updateString = "";
    foreach ($userDetails as $key=>$value) {
        $updateString .= ", $key='$value'";
    }
    $result = dbResultFromQuery("UPDATE users SET user_id='$userID'$updateString WHERE user_id='$userID';");
}

function banUser($userID) {
    $result = dbResultFromQuery("UPDATE users SET banned=1 WHERE user_id='$userID';");
    if ($result) {
        echo "User has been banned! Redirecting in 5 seconds...";
        header("Refresh:5; URL=user.php?id=$userID");
    } else {
        echo "Banning user failed, please try again.";
    }
}

function unbanUser($userID) {
    $result = dbResultFromQuery("UPDATE users SET banned=0 WHERE user_id='$userID';");
    if ($result) {
        echo "User has been unbanned! Redirecting in 5 seconds...";
        header("Refresh:5; URL=user.php?id=$userID");
    } else {
        echo "Unbanning user failed, please try again.";
    }
}



/// P O S T S ///

function getAllPosts() {
    $posts = [];
    $result = dbResultFromQuery("SELECT * FROM posts ORDER BY timestamp DESC;");
    if ($result->num_rows > 0) {
        $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $posts;
}

function getPostForID($postID) {
    $post = [];
    $result = dbResultFromQuery("SELECT * FROM posts WHERE post_id='$postID' LIMIT 1;");
    if ($result->num_rows > 0) {
        $post = mysqli_fetch_assoc($result);
    }
    return $post;
}

function insertNewPost($userID, $postTitle, $postBody, $imageURL=NULL, $thumbPath=NULL) {
    if (empty($userID) || empty($postTitle) || empty($postBody)) {
        return returnResponse(0, "Failed to create new post, please complete all fields.", $result);
    }
    
    $result = dbResultFromQuery("INSERT INTO posts (user_id, title, body, imageURL, thumbURL) VALUES ('$userID', '$postTitle', '$postBody', '$imageURL', '$thumbPath');");
    if ($result) {
        return returnResponse(1, "New post created successful.", $result);
    } else {
        return returnResponse(0, "Failed to create new post, please try again.", $result);
    }
}

function votePost($commentID, $commenterID, $voterID, $vote) {
    $post = dbResultFromQuery("SELECT comment_id, commenter_id, voter_id, vote FROM votes WHERE (comment_id='$commentID' AND voter_id='$voterID') LIMIT 1;");
    $voted = false;
    $voting = 1;
    if ($post->num_rows > 0) {
        // update
        $post = mysqli_fetch_assoc($post);
        if ($post['vote'] === 0) {
            $voted = dbResultFromQuery("UPDATE votes SET vote=1 WHERE comment_id='$commentID');");
        } else {
            $voted = dbResultFromQuery("UPDATE votes SET vote=0 WHERE comment_id='$commentID');");
            $voting = 0;
        }
    } else {
        // insert
        $voted = dbResultFromQuery("INSERT INTO votes (comment_id, commenter_id, voter_id, vote) VALUES ('$commentID', '$commenterID', '$voterID', '$vote');");
        $voting = $vote;
    }
    
    if (!$voted) return 0;
    
    $commenter = dbResultFromQuery("SELECT reputation FROM users WHERE user_id='$commenterID');");
    if ($post->num_rows > 0) {
        $reputation = intval($commenter['reputation']);
        if ($voting) {
            $reputation = $reputation + 10;
        } else {
            $reputation = $reputation - 10;
        }
        $voted = dbResultFromQuery("UPDATE users SET reputation=$reputation WHERE user_id='$commenterID');");
    } else {
        return 0;
    }
    
    return $voted;
}

function countVotesForCommentID($commentID) {
    $votes = dbResultFromQuery("SELECT vote FROM votes WHERE comment_id=$commentID;");
    $totalVots = 0;
    if ($votes->num_rows > 0) {
        $votes = mysqli_fetch_all($votes, MYSQLI_ASSOC);
        foreach ($votes as $vote) {
            $voted = $vote['vote'];
            $totalVots = $totalVots + $voted;
        }
    }
    return $totalVots;
}



/// C O M M E N T S ///

function getCommentsForPostID($postID) {
    $comments = [];
    $result = dbResultFromQuery("SELECT * FROM comments WHERE post_id='$postID' ORDER BY timestamp DESC;");
    if ($result->num_rows > 0) {
        $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $comments;
}

function getCommentForID($commentID) {
    $comment = [];
    $result = dbResultFromQuery("SELECT * FROM comments WHERE comment_id='$commentID' LIMIT 1;");
    if ($result->num_rows > 0) {
        $comment = mysqli_fetch_assoc($result);
    }
    return $comment;
}

function insertNewComment($postID, $userID, $comment) {
    if (empty($postID) || empty($userID) || empty($comment)) return 0;

    $result = dbResultFromQuery("INSERT INTO comments (post_id, user_id, comment) VALUES ('$postID', '$userID', '$comment');");
    return $result;
}



/// R E P O R T S ///

function reportUser($userID, $reporterID, $reason) {
    $user = getUserForID($userID);
    if (!$user) return returnResponse(0, "Failed to report, user not found.", $result);
    $username = $user['username'];
    $fullName = $user['fullName'];
    $emailAdd = $user['emailAddress'];
        
    $result = dbResultFromQuery("INSERT INTO reported_users (user_id, username, fullName, emailAddress, reporter_id, reportReason) VALUES ('$userID', '$username', '$fullName', '$emailAdd', '$reporterID', '$reason');");
    if ($result) {
        return returnResponse(1, "User reported, thank you for keeping the community clean.");
    } else {
        return returnResponse(0, "Failed to report, please try again.");
    }
}

function updateReportedUser() {
    
}

function reportComment($commentID, $postID, $reporterID, $reason) {
    $comment = getCommentForID($commentID);
    if (!$comment) return returnResponse(0, "Failed to report, comment not found.", $result);
    $userID = $comment['user_id'];
    $reportedComment = $comment['comment'];
    
    $result = dbResultFromQuery("INSERT INTO reported_comments (comment_id, post_id, user_id, comment, reporter_id, reportReason) VALUES ('$commentID', '$postID', '$userID', '$reportedComment', '$reporterID', '$reason');");
    if ($result) {
        return returnResponse(1, "Comment reported, thank you for keeping the community clean.");
    } else {
        return returnResponse(0, "Failed to report, please try again.");
    }
}

function updateReportedComment() {
    
}



/// L O C A T I O N S ///

function insertNewLocation($place, $desc, $lat, $long) {
    if (empty($place) || empty($desc) || empty($lat) || empty($long)) return 0;
    
    $result = dbResultFromQuery("INSERT INTO tbl_places (place, description, lat, lng) VALUES ('$place', '$desc', '$lat', '$long');");
    return $result;
}

function getAllLocations() {
    $locations = [];
    $result = dbResultFromQuery("SELECT * FROM tbl_places;");
    if ($result->num_rows > 0) {
        $locations = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $locations;
}



/// H E L P E R S ///

# Checks if the email address is valid
function isValidEmail($emailAddress) {
    if (preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", $emailAddress)) {
		return 1;
	} else {
		return 0;
	}
}

# Return an array of a success boolean, response message, and optional data
function returnResponse($success, $response, $data=NULL) {
    $return = [];
    if ($data !== NULL) {
        $return = ["success"=>$success, "response"=>$response, "data"=>$data];
    } else {
        $return = ["success"=>$success, "response"=>$response];
    }
    return $return;
}

# Return validated string input for inserting
function validatedInput($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

function wordedTimestamp($date, $showTime) {
    $dateSentence = $date;
    if ($showTime) {
        $dateSentence = date("d F Y \@ H:i", strtotime($date));
    } else {
        $dateSentence = date("l, d F Y", strtotime($date));
    }
    return $dateSentence;
}

function sanitise($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function echoVar($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function squareImageAtPath($imgPath, $destPath, $imgSize) {
    $source_img = NULL;
    if (substr_count(strtolower($imgPath), ".jpg") or substr_count(strtolower($imgPath), ".jpeg")){
        $source_img = @imagecreatefromjpeg($imgPath);
    } else if(substr_count(strtolower($imgPath), ".gif")){
        $source_img = @imagecreatefromgif($imgPath);
    } else if(substr_count(strtolower($imgPath), ".png")){
        $source_img = @imagecreatefrompng($imgPath);
    } else {
        return 0;
    }

    $orig_w = imagesx($source_img);
    $orig_h = imagesy($source_img);

    $w_ratio = ($imgSize / $orig_w);
    $h_ratio = ($imgSize / $orig_h);

    if ($orig_w > $imgSize ) {//landscape
        $crop_w = round($orig_w * $h_ratio);
        $crop_h = $imgSize;
        $src_x = ceil( ( $orig_w - $orig_h ) / 2 );
        $src_y = 0;
    } elseif ($orig_w < $orig_h ) {//portrait
        $crop_h = round($orig_h * $w_ratio);
        $crop_w = $new_w;
        $src_x = 0;
        $src_y = ceil( ( $orig_h - $orig_w ) / 2 );
    } else {//square
        $crop_w = $imgSize;
        $crop_h = $imgSize;
        $src_x = 0;
        $src_y = 0;	
    }
    $dest_img = imagecreatetruecolor($imgSize,$imgSize);
    imagecopyresampled($dest_img, $source_img, 0 , 0 , $src_x, $src_y, $crop_w, $crop_h, $orig_w, $orig_h);
	
    $imgSaved = false;
    if (substr_count(strtolower($imgPath), ".jpg") or substr_count(strtolower($imgPath), ".jpeg")){
        $imgSaved = imagejpeg($dest_img, $destPath);
    } else if(substr_count(strtolower($imgPath), ".gif")){
        $imgSaved = imagegif($dest_img, $destPath);
    } else if(substr_count(strtolower($imgPath), ".png")){
        $imgSaved = imagepng($dest_img, $destPath, PNG_NO_FILTER);
    } else {
        return 0;
    }
    if ($imgSaved) {
        imagedestroy($dest_img);
        imagedestroy($source_img);
    }
    return $imgSaved;
}

?>