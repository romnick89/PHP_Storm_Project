<?php
session_start();
require_once ('Models/UsersDataSet.php');
require_once ('Models/TopicDataSet.php');

$view = new stdClass();
$view->pageTitle = 'Anime/Manga Forum';

//create connection to test the database if live
$connect = new mysqli('poseidon.salford.ac.uk', 'sye549', '890813Mrag00', 'sye549');

if($connect->connect_error) {

    $view->dbError = "Could not connect to the server at this moment. Please try again later." . $connect->connect_error; //display message if connection failed
}
else {
    $topicDataSet = new TopicDataSet();
//search topics in the database
    if (isset($_POST['searchBtn'])) {
        $textToSearch = $_POST['searchTopic'];

        $view->searchedTopic = $topicDataSet->searchTopic($textToSearch);
    }

//allows pagination
    if (isset($_GET['pageNo'])) {
        $pageNo = $_GET['pageNo'];
    } else {
        $pageNo = 1;
    }
    $noOfTopicsPerPage = 10;
    $offset = ($pageNo - 1) * $noOfTopicsPerPage;
    $totalTopics = $topicDataSet->countNumberOfTopics();
    $view->totalPages = ceil($totalTopics / $noOfTopicsPerPage);
    $view->topicDataSet = $topicDataSet->fetchTopicsDetails($offset, $noOfTopicsPerPage);

//login button
//validate user that logs in and set a session
    if (isset($_POST['login'])) {
        $userDataSet = new UsersDataSet();
        $user = $userDataSet->loginUser($_POST['email']);

        $userEmailDB = $user['email'];
        $usernameDB = $user['username'];
        $passwordDB = $user['password'];
        //verify email and password
        if ($_POST['email'] == $userEmailDB && password_verify($_POST['password'], $passwordDB) == true) {
            $_SESSION['uname'] = $usernameDB;
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['email'] = $userEmailDB;
            $view->login = '<h4 style="font-family: Arial Black;">' . "Welcome " . '<i style="color: darkgreen;">' . $_SESSION['uname'] . '!</i>' . " You are logged in." . '</h4>';
        } else {
            $view->invalid = '<p style="color: darkred;">' . "The email and password you entered did not match. Please try again!" . '</p>';
        }
    } elseif (isset($_SESSION['email'])) {
        $view->login = '<h4 style="font-family: Arial Black;">' . "Welcome " . '<i style="color: darkgreen;">' . $_SESSION['uname'] . '!</i>' . " You are logged in." . '</h4>';
    }

//logout and destroy session
    if (isset($_POST['logout'])) {
        unset($_SESSION['uname']);
        unset($_SESSION['email']);
        unset($_SESSION['userID']);
        session_destroy();
    }
}

require_once('Views/index.phtml');
