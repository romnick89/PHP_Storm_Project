<?php
session_start();
$view = new stdClass();
$view->pageTitle = $_SESSION['uname'] . ' Change Password';
require_once ('Models/UsersDataSet.php');

//if user access site via url
if(!isset($_SERVER['HTTP_REFERER'])){
    // redirect to home page
    header('location:../index.php');
    exit;
}

$userDataSet = new UsersDataSet();

if (isset($_SESSION['email'])) {
    $errors = array();

    //allows users to edit password
    if (isset($_POST['verify'])) {
        //check if fields are empty
        if (empty($_POST['password'])){array_push($errors, "Please type your current password in the field provided");}
        if(empty($_POST['newPassword'])){array_push($errors, "Please type your new password in the field provided");}
        if(empty($_POST['confirmPassword'])){array_push($errors, "Please confirm your new password in the field provided");}

        //if no errors update password
        if (count($errors) == 0) {
            $user = $userDataSet->loginUser($_SESSION['email']); //fetch password using login method
            $passwordDB = $user['password'];
            if (password_verify($_POST['password'], $passwordDB) == true && $_POST['newPassword'] == $_POST['confirmPassword']) {
                $hash_pass = password_hash($_POST['newPassword'], PASSWORD_BCRYPT, array('cost' => 12));
                $userDataSet->changePassword($_SESSION['email'], $hash_pass);
                $view->confirmChange = "Your password has been changed";
                unset($_SESSION['uname']);
                unset($_SESSION['email']);
                unset($_SESSION['userID']);
                session_destroy();
            }
            else {
                $view->incorrect = '<p style="color: darkred;">'. "Incorrect current password input" . '</p>';
            }
        }
        else {
            $view->errors = $errors;

        }

    }
}

require_once ('Views/editPassword.phtml');