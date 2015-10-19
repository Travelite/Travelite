<?php

# Set timzeone
date_default_timezone_set("Africa/Johannesburg");

# Footer message
$footerMessage = "2015 &copy; voltage designs.";

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
    $result = dbResultFromQuery("INSERT INTO users (fullName, username, password, emailAddress) VALUES ('$fullName',' $username',' $password', '$email');");
    return $result;
}

function loginUser($username, $password) {
    $result = dbResultFromQuery("SELECT * FROM users WHERE (emailAddress='$username' OR username='$username') AND password='$password' LIMIT 1;");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['admin'] = $user['admin'];
        return 1;
    } else {
        return 0;
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

/// P O S T S ///

function getPostForID($postID) {
    $result = dbResultFromQuery("SELECT * FROM posts WHERE post_id='$postID' LIMIT 1;");
    $post = mysqli_fetch_assoc($result);
    return $post;
}

/// C O M M E N T S ///

function getCommentsForPostID($postID) {
    $comments = [];
    $result = dbResultFromQuery("SELECT * FROM comments WHERE post_id='$postID' ORDER BY timestamp DESC;");
    if ($result->num_rows > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            array_push($comments, $data);
        }
        return $comments;
    }
    return 0;
}

function newCommentForPostID($postID, $userID, $comment) {
    $result = dbResultFromQuery("INSERT INTO comments (post_id, user_id, comment) VALUES ('$postID',' $userID', '$comment');");
    return $result;
}

/// L O C A T I O N S

function insertNewLocation($place, $desc, $lat, $long) {
    $result = dbResultFromQuery("INSERT INTO tbl_places (place, description, lat, lng) VALUES ('$place', '$desc', '$lat', '$long');");
    return $result;
}

function getAllLocations() {
    $locations = [];
    $result = dbResultFromQuery("SELECT * FROM tbl_places;");
    if ($result->num_rows > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            array_push($locations, $data);
        }
        return $locations;
    }
    return 0;
}

/// H E L P E R S

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
