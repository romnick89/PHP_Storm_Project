<?php
require_once('Models/Database.php');
require_once('Models/UserData.php');

class UsersDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    //registers users and insert into users table
    public function registerUser($firstname, $lastname, $email, $username, $password, $photoName) {
        $sqlQuery = "INSERT into users (first_name, last_name, email, username, password, photo_name) VALUES ('$firstname', '$lastname', '$email', '$username', '$password', '$photoName')";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }

    //check if user already exists
    //param $username and $email which is unique
    public function checkIfUserExists($username, $email) {
        $sqlQuery = "SELECT * FROM users WHERE username = '$username' or email = '$email' LIMIT 1";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        return $statement->fetch();
    }

    //return all details of users when logged in
    //param email address
    //return string of user details
    public function loginUser($email) {
        $sqlQuery = "SELECT * FROM users WHERE email = '$email'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        return $statement->fetch();
    }

    //return user details from database
    //param email
    //display user details in profile
    //return array of user details
    public function fetchUserDetails($email) {
        $sqlQuery = "SELECT * FROM users WHERE email = '$email'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    //fetch userID from database
    //param email
    public function fetchUserID($email) {
        $sqlQuery = "SELECT userID FROM users WHERE email = '$email'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet = $statement->fetch();
        return $dataSet['userID'];
    }

    //add profile image of user
    //param userID and photo name
    // $photoName is a string that references image stored in images folder
    public function addPhoto($userID, $photoName) {
        $sqlQuery = "UPDATE users SET photo_name = '$photoName' WHERE userID = '$userID'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }

    //updates/edit the user password
    //param email and new password
    public function changePassword($email, $newPassword) {
        $sqlQuery = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }

}