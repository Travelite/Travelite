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
    
    // Check if password is at least 6 characters long
    if (strlen($password) < 6) {
        return returnResponse(0, "Registration failed, minimum password length is 6 characters.");
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
        return returnResponse(1, "Registration successful.", $result);
    } else {
        return returnResponse(0, "Registration failed, please try again.", $result);
    }
}

function loginUser($username, $password) {
    // Check if username or email address exist
    $result = dbResultFromQuery("SELECT * FROM users WHERE emailAddress='$username' OR username='$username' LIMIT 1;");
    if ($result->num_rows === 0) {
        return returnResponse(0, "Login failed, no such user.", $result);
    }
    
    $user = mysqli_fetch_assoc($result);
    
    $userID = $user['user_id'];
    $hashedPassword = $user['password'];

    // Compare the given password with database password
    $passwordMatch = password_verify($password, $hashedPassword);   
    if (!$passwordMatch) {
        return returnResponse(0, "Login failed, incorrect password.", $result);
    }
    
    // Assign values to the session array
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['admin'] = $user['admin'];
    
    return returnResponse(1, "Login successful.", $user);
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

function insertNewPost($userID, $postTitle, $postBody, $imageURL=NULL) {
    if (empty($userID) || empty($postTitle) || empty($postBody)) {
        return returnResponse(0, "Failed to create new post, please complete all fields.", $result);
    }
    
    $result = dbResultFromQuery("INSERT INTO posts (user_id, title, body, imageURL) VALUES ('$userID', '$postTitle', '$postBody', '$imageURL');");
    if ($result) {
        return returnResponse(1, "New post created successful.", $result);
    } else {
        return returnResponse(0, "Failed to create new post, please try again.", $result);
    }
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

?>