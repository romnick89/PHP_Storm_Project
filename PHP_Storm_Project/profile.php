<?php
session_start();
require_once ('Models/Database.php');
require_once ('Models/UsersDataSet.php');
require_once ('Models/WatchListDataSet.php');


$view = new stdClass();
$view->pageTitle = $_SESSION['uname'] . ' Profile';

//if user access site via url
if(!isset($_SERVER['HTTP_REFERER'])){
    // redirect to home page
    header('location:../index.php');
    exit;
}
//check for session and display details
if (isset($_SESSION['email'])) {
    //display user details
    $userDataSet = new UsersDataSet();
    $view->userDetails = $userDataSet->fetchUserDetails($_SESSION['email']);

    //display watch list details
    $watchListDataSet = new WatchListDataSet();
    $view->titles = $watchListDataSet->fetchTopics($_SESSION['userID']);

    if (empty($view->titles)) {
        $view->noWatchList = " ****Your watch list is EMPTY****";
    }

    if (isset($_POST['upload'])) {

        $file = $_FILES['image']['name'];
        //check file validation
        //check for errors and files being uploaded
        try {
            if(!isset($_FILES['image']['error']) || is_array($_FILES['image']['error'])) {
                throw new RuntimeException('Invalid parameters');
            }
            switch ($_FILES['image']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded file size limit');
                default:
                    throw new RuntimeException('Unknown errors');
            }
            //limit file size to 200mb
            if ($_FILES['image']['size'] > 200000) {
                throw new RuntimeException('Exceeded file size limit. The file size limit is 200mb');
            }

            //validate file type to be uploaded
            $fInfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search(
                    $fInfo->file($_FILES['image']['tmp_name']),
                    array(
                        'jpg' => 'image/jpg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                        'jpeg' => 'image/jpeg'
                    ),
                    true
                )) {
                throw new RuntimeException('Invalid file format.');
            }

            //edit file name using explode method
            $extension = explode(".", $_FILES['image']['name']);

            //change photoID using unique identifier which is userID
            $newFilename = $_SESSION['userID'] . '.' . end($extension);
            move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $newFilename);

            //insert new file name in users table
            $userDataSet->addPhoto($_SESSION['userID'], $newFilename);
            $view->addPhoto = "Your profile has been updated";
        }
        catch (RuntimeException $re) {

            $view->errors = $re->getMessage();

        }
    }
}

require_once ('Views/profile.phtml');