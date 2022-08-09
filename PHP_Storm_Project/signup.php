<?php
require_once ('Models/UsersDataSet.php');

$view = new stdClass();
$view->pageTitle = 'SignUp';

$errors = array();
//captcha verification
if(isset($_POST['g-recaptcha-response']))
{
    $secret = '6LcFU8UUAAAAABkQrlQzpCruWqaR8okSCJiovA3o';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);
}

//check input details of signup
if(isset($_POST['register'])) {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['confirmPassword'];
    $reCaptcha = $_POST['g-recaptcha-response'];

    //validate if fields are empty and store in array $errors
    if (empty($reCaptcha)){array_push($errors, "Robot verification failed, please try again");}
    if(empty($firstname)){array_push($errors, "Please type your name in the field provided");}
    if(empty($lastname)){array_push($errors, "Please type your last name in the field provided");}
    if(empty($email)){array_push($errors, "Please type your email in the field provided");}
    if(empty($username)){array_push($errors, "Please type your username in the field provided");}
    if(empty($password)){array_push($errors, "Password is required");}
    if(empty($password2)){array_push($errors, "Please confirm your password");}

    //check email and username if it's already registered
    $userDataSet = new UsersDataSet();
    $users = $userDataSet->checkIfUserExists($username, $email);
    if ($users) {
        if($users['username'] === $username) {
            array_push($errors, "This username is already taken!");
        }
        if($users['email'] === $email) {
            array_push($errors, "This email is already taken!");
        }
    }

    //check if both password are identical
    if (!empty($password)) {
        if ($password != $password2) {
            array_push($errors,"Both passwords do not match");
        }
    }

    //if no errors allow registration
    if (count($errors) == 0) {
        $hash_pass = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12)); //hash password using PASSWORD_BCRYPT
        $photoName = 'noimage.png'; //default profile photo for every users
        //insert user details in users table
        $userDataSet->registerUser($firstname,$lastname,$email,$username,$hash_pass,$photoName);
        $view->accountCreated = "Your account has been created. You are now able to login!";
        require_once 'Views/signup.phtml';
    }
    //else display errors
    else {
        $view->errors = $errors;
        require_once 'Views/signup.phtml';
    }
}
else
    require_once('Views/signup.phtml');

