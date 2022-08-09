<?php
session_start();
require_once ('Models/MessagesDataSet.php');
require_once ('Models/UsersDataSet.php');

$view = new stdClass();
$view->pageTitle = 'Message';

//if user access site via url
if(!isset($_SERVER['HTTP_REFERER'])){
    // redirect to home page
    header('location:../index.php');
    exit;
}

$errors = array();
//create email and check for session
if (isset($_SESSION['email'])) {
    //check if fields are empty and push errors to array
    if (isset($_POST['send'])) {
        if(empty($_POST['email'])){array_push($errors, "Please make sure you entered the recipient's email.");}
        if(empty($_POST['content'])){array_push($errors, "Please make sure at least the content is filled in.");}
        $userDataSet = new UsersDataSet();
        //validate email provided
        $sendToUserID = $userDataSet->fetchUserID($_POST['email']);
        if (empty($sendToUserID)) {array_push($errors, "Email does not exist. Please check the email address you provided.");}

        //if no errors send to registered users
        if (count($errors) == 0) {
            $date = date('Y-m-d H:i:s');
            //message inserted to table
            $messagesDataSet = new MessagesDataSet();
            $messagesDataSet->sendMessage($_POST['subject'], $_POST['content'], $date, $_SESSION['userID'], $sendToUserID);

            $view->sentMessage = "Message has been sent!!!";
        }
        //else display errors
        else {
            $view->errors = $errors;
        }
    }
}
require_once ('Views/email.phtml');

