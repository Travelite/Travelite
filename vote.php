<?php
require_once "functions.php";

$commentID = $_POST['commentID'] ? $_POST['commentID'] : NULL;
$commenterID = $_POST['commenterID'] ? $_POST['commenterID'] : NULL;
$voterID = $_POST['voterID'] ? $_POST['voterID'] : NULL;
$vote = $_POST['vote'] ? $_POST['vote'] : NULL;

if (empty($commentID) || empty($commenterID) || empty($voterID) || empty($vote)) return;

$voted = votePost($commentID, $commenterID, $voterID, $vote);
return $voted;
    
?>