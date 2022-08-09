<?php
session_start();
require_once ('Models/UsersDataSet.php');
require_once ('Models/PostDataSet.php');
require_once ('Models/TopicDataSet.php');
require_once ('Models/WatchListDataSet.php');
require_once ('Models/MessagesDataSet.php');

$view = new stdClass();
$view->pageTitle = 'Post';


//if user access site via url
if(!isset($_SERVER['HTTP_REFERER'])){
    // redirect to home page
    header('location:../index.php');
    exit;
}

//use $get to fetch topicID and retrieve details in the database
$topicDataSet = new TopicDataSet();
if (isset($_GET['topicID'])) {
    $_SESSION['topicID'] = $_GET['topicID'];//store query string topicID to a session
    $view->title = $topicDataSet->fetchTopic($_SESSION['topicID']);
}
else {
    $view->title = $topicDataSet->fetchTopic($_SESSION['topicID']);
}

$postDataSet = new PostDataSet();
//search post for each title
//this is for the search button
if (isset($_POST['searchPostBtn'])) {
    $view->searchedPost = $postDataSet->searchPost($_SESSION['topicID'], $_POST['searchPost']);
}
//display post by dropdown link
if(isset($_GET['postID'])) {
    $view->displayPost = $postDataSet->selectPost($_SESSION['topicID'],$_GET['postID']);
}

//allows pagination
if (isset($_GET['pageNumber'])) {
    $pageNumber = $_GET['pageNumber'];
}
else {
    $pageNumber = 1;
}
$noOfPostsPerPage = 10;
$offset = ($pageNumber - 1) * $noOfPostsPerPage;
$totalPosts = $postDataSet->countNumberOfPosts($_SESSION['topicID']);
$view->numberOfPages = ceil($totalPosts / $noOfPostsPerPage);
$view->postDataSet = $postDataSet->fetchAllPosts($_SESSION['topicID'], $offset, $noOfPostsPerPage);

//display this if no comments
if (empty($view->postDataSet)) {
    $view->noComments = "***There are still no posts for this topic***";
}

$watchListDataSet = new WatchListDataSet();
//add comment
if (isset($_POST['addComment'])) {
    $errors = array();
    $subject = $_POST['subject'];
    $comment = $_POST['comment'];
    $userID = $_SESSION['userID'];
    $topicID = $_SESSION['topicID'];
    $date = date('Y-m-d H:i:s');

    //check fields if empty push errors to array
    if (empty($subject)) { array_push($errors, "Subject field cannot be empty");}
    if (empty($comment)) { array_push($errors, "Comment field cannot be empty");}

    //if no errors allow user to add comment
    if (count($errors) == 0) {
        $postDataSet->addComment($subject, $comment, $date, $userID, $topicID);
        $view->addComment = "Comment has been added!";

        //notify users a message has been added "if this topic is in their watch list."
        $usersDetails = $watchListDataSet->fetchUsersInWatchList($topicID);
        foreach ($usersDetails as $user) {
            if ($user['user_id'] != $_SESSION['userID']) {
            //create auto-message sent to specified users
            $subject = "**This is an automated message**";
            $content = "<i>There is a new discussion on " . "<b>" . $view->title. "</b>" . " topic.</i>";
            $adminID = 135;
            $messageDataSet = new MessagesDataSet();
            $messageDataSet->sendMessage($subject,$content,$date,$adminID, $user['user_id']);
            }
        }
    }
    //else display errors
    else {
        $view->errors = $errors;
    }
}
//add to watch list
if (isset($_POST['watchListBtn'])) {
    //validate if topic is already in watch list
    $watchList = $watchListDataSet->checkIfInWatchList($_SESSION['topicID'],$_SESSION['userID']);
    if($watchList['topic_id'] === $_SESSION['topicID'] && $watchList['user_id'] === $_SESSION['userID']) {
        $view->watchlist = '<h4 style="color: darkred">' . "Unable to add topic. Topic is already in your watch list." . '</h4>';
    }
    else {
        $watchListDataSet->addToWatchList($_SESSION['topicID'], $_SESSION['userID']);
        $view->watchlist = '<h4 style="color: darkgreen">'."Topic was added to your watch list!" . '</h4>';
    }
}


require_once ('Views/post.phtml');