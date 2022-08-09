<?php
session_start();
require_once('Models/TopicDataSet.php');

$view = new stdClass();
$view->pageTitle = 'Create Topic';

//if user access site via url
if(!isset($_SERVER['HTTP_REFERER'])){
    // redirect to home page
    header('location:../index.php');
    exit;
}

$topicDataSet = new TopicDataSet();
//allows user to create topics
$errors = array();
if (isset($_POST['createTopic'])) {
    //check if fields are empty and push to errors array
    if (empty($_POST['title'])) {array_push($errors, "Please make sure the topic title have been filled in.");}
    if (empty($_POST['description'])) {array_push($errors, "Please make sure the topic description have been filled in.");}

    $title = $_POST['title'];
    $description = $_POST['description'];

    //if no errors found create topic
    if (count($errors) == 0) {
        $topicDataSet->createTopic($title, $description, $_SESSION['userID']);
        $view->createTopic = "Topic has been created!!!";
    }
    //else display errors
    else {
        $view->errors = $errors;
    }
}

require_once ('Views/topic.phtml');