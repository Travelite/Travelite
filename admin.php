<?php
require_once("functions.php");
if (!$isLoggedIn && !$isAdmin) {
    header("Location:login.php");
    exit;
}

$report_id = isset($_GET['reportID']) ? $_GET['reportID'] : false;
$reporting = isset($_GET['reporting']) ? $_GET['reporting'] : false;
$report_option = isset($_GET['option']) ? $_GET['option'] : false;

if ($report_id && $report_option && $reporting) {
    switch ($reporting) {
        case "user";
            updateReportedUser($report_id, $report_option);
            break;
            
        case "comment";
            updateReportedComment($report_id, $report_option);
            break;
            
        default:
            break;
    }
}


$reportedUsers = getAllReportedUsers();
$reportedComments = getAllReportedComments();

?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Admin Panel</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <style>
            table, th, td {
               border: 1px solid #fff;
               padding: 5px;
            }
        </style>
    </head>
    <body id="index" class="home">
        
        <?php echo $htmlNavigation; ?>
        
        <section id="logins" class="body">
            <div id="login" align="center">
                <div id="backing" align="center" style="width:95%;background-color:rgba(32,44,75,0.90);">
                    <?php 
                    
                    /// REPORTED USERS ///
                    
                    echo "<br><h2>Reported Users</h2>";
                    if ($reportedUsers) {
                        echo '<table border="0">';
                        echo '<th>User</th><th>Reporter</th><th>Reason</th><th colspan="2">Options</th>';
                        foreach ($reportedUsers as $report) {
                            $reportID = $report['report_id'];
                            $reportedUserID = $report['user_id'];
                            $reportedUserUsername = $report['username'];
                            $reporterID = $report['reporter_id'];
                            $reportReason = $report['reportReason'];
                            echo '<tr>';
                            echo '<td><a href="user.php?id='.$reportedUserID.'" target="_blank">'.$reportedUserUsername.'</td>';
                            echo '<td><a href="user.php?id='.$reporterID.'" target="_blank">'.$reporterID.'</td>';
                            echo '<td style="width:100%;">'.$reportReason.'</td>';
                            echo '<td><a href="?reporting=user&reportID='.$reportID.'&option=ban">Ban</td>';
                            echo '<td><a href="?reporting=user&reportID='.$reportID.'&option=dismiss">Dismiss</td>';
                            echo '</tr>';
                        }
                        echo '</table><br>';
                        
                    } else {
                        echo "<p>No reported users.</p>";
                    }
                    
                    ################
                    echo "<hr>";
                    ################
                    
                    /// REPORTED COMMENTS ///
                    
                    echo "<br><h2>Reported Comments</h2>";
                    if ($reportedComments) {
                        echo '<table border="0">';
                        echo '<th>Comment</th><th>Reporter</th><th>Comment</th><th>Reason</th><th colspan="2">Options</th>';
                        foreach ($reportedComments as $report) {
                            $reportID = $report['report_id'];
                            $reportedUserID = $report['user_id'];
                            $postID = $report['post_id'];
                            $commentID = $report['comment_id'];
                            $comment = $report['comment'];
                            $reporterID = $report['reporter_id'];
                            $reportReason = $report['reportReason'];
                            echo '<tr>';
                            echo '<td><a href="post.php?id='.$postID.'#'.$commentID.'" target="_blank">'.$commentID.'</td>';
                            echo '<td><a href="user.php?id='.$reporterID.'" target="_blank">'.$reporterID.'</td>';
                            echo '<td style="width:50%;">'.$comment.'</td>';
                            echo '<td style="width:50%;">'.$reportReason.'</td>';
                            echo '<td><a href="?reporting=comment&reportID='.$reportID.'&option=delete" target="">Delete</td>';
                            echo '<td><a href="?reporting=comment&reportID='.$reportID.'&option=dismiss">Dismiss</td>';
                            echo '</tr>';
                        }
                        echo '</table><br>';
                        
                    } else {
                        echo "<p>No reported comments.</p>";
                    }
                    
                    ?>
                </div>
            </div>
        </section>
        
    </body>
</html>