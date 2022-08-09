<?php
session_start();
require_once ('Models/MessagesDataSet.php');
require_once ('Models/UsersDataSet.php');

$view = new stdClass();
$view->pageTitle = $_SESSION['uname'] . ' Inbox';

//if user access site via url
if(!isset($_SERVER['HTTP_REFERER'])){
    // redirect to home page
    header('location:../index.php');
    exit;
}
//check if session is set
if (isset($_SESSION['email'])) {

    $messagesDataSet = new MessagesDataSet();
    //pagination
    if (isset($_GET['pageNo'])) {
        $pageNo = $_GET['pageNo'];
    } else {
        $pageNo = 1;
    }
    $noOfMessagePerPage = 10;
    $offset = ($pageNo - 1) * $noOfMessagePerPage;
    $totalMessages = $messagesDataSet->countNumberOfMessages($_SESSION['userID']);
    $view->totalPages = ceil($totalMessages / $noOfMessagePerPage);
    $view->messages = $messagesDataSet->fetchMessages($_SESSION['userID'], $offset, $noOfMessagePerPage);

    //display if no message in inbox
    if (empty($view->messages)) {
        $view->noMessages = "*** There are currently no messages in your inbox ***";
    }

    if (isset($_POST['delete'])) {
        //get message id from inbox and remove from database
        if (isset($_POST['messageID'])) {
            $messageDataSet = new MessagesDataSet();
            $view->delete = $messageDataSet->deleteMessage($_POST['messageID']);
            $view->messageDeleted = "The message has been deleted";
        }
    }
}
require_once ('Views/inbox.phtml');