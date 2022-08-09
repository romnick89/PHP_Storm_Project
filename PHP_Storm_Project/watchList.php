<?php
session_start();
require_once ('Models/WatchListDataSet.php');
require_once ('Models/MessagesDataSet.php');

$view = new stdClass();
$view->pageTitle = 'Watch List';

//if user access site via url
if(!isset($_SERVER['HTTP_REFERER'])){
    // redirect to home page
    header('location:../index.php');
    exit;
}
//session check
if (isset($_SESSION['email'])) {
    //get watch list id and remove from watch list
    if (isset($_GET['watchListID'])) {
        $watchListDataSet = new WatchListDataSet();

        //remove topics from user's watch list
        $view->remove = $watchListDataSet->removeFromWatchList($_GET['watchListID']);
        $view->topicRemoved = $_SESSION['uname'] . "! This topic was removed from your watch list";
    }

}
require_once ('Views/watchList.phtml');
