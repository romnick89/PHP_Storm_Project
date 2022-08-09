<?php
session_start();
require_once('Models/ReplyDataSet.php');
require_once ('Models/PostDataSet.php');

$view = new stdClass();
$view->pageTitle = 'Reply';

//if user access site via url
if(!isset($_SERVER['HTTP_REFERER'])){
    // redirect to home page
    header('location:../index.php');
    exit;
}

$postDataSet = new PostDataSet();
$replyDataSet = new ReplyDataSet();

//allows pagination
if (isset($_GET['pageNum'])) {
    $pageNumber = $_GET['pageNum'];
} else {
    $pageNumber = 1;
}
$noOfPostsPerPage = 10;
$offset = ($pageNumber - 1) * $noOfPostsPerPage;
$totalPosts = $replyDataSet->countNumberOfReplies($_SESSION['postID'],$_SESSION['topicID']);
$view->numberOfPages = ceil($totalPosts / $noOfPostsPerPage);

if (isset($_GET['postID'])) {
    //display post subject
    $_SESSION['postID'] = $_GET['postID'];
    $view->postSubject = $postDataSet->fetchPost($_SESSION['postID']);

    //display all replies from a post
    $comments = $replyDataSet->fetchAllReplies($_SESSION['postID'], $_SESSION['topicID'], $offset, $noOfPostsPerPage);

    if (empty($comments)) {
        $view->noComments = "***There are no comments in this post***";
    }
    else {
        $view->comments = $comments;
    }
}
//allows user to return in the same post subject
else {
    //display post subject
    $view->postSubject = $postDataSet->fetchPost($_SESSION['postID']);

    //display all replies from a post
    $comments = $replyDataSet->fetchAllReplies($_SESSION['postID'], $_SESSION['topicID'], $offset, $noOfPostsPerPage);

    if (empty($comments)) {
        $view->noComments = "***There are no comments in this post***";
    }
    else {
        $view->comments = $comments;
    }
}

//add reply/comment to a post
$date = date('Y-m-d H:i:s');
if (isset($_POST['reply'])) {
    //validate field if empty
    if (empty($_POST['comment'])) {
        $view->error = "Comment field cannot be empty";
    }
    else {
        $replyDataSet->addReply($_SESSION['postID'], $_SESSION['userID'], $_SESSION['topicID'], $date, $_POST['comment']);
        $view->insertReply = "Post has been updated! " . $_SESSION['uname'] . ", your reply has been added.";
    }
}

require_once ('Views/reply.phtml');
