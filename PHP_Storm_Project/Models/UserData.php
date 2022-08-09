<?php
class UserData {

    protected $_userID, $_firstName, $_lastName, $_email, $_username, $_password, $_photoName;

    public function __construct($dbRow) {
        $this->_userID = $dbRow['userID'];
        $this->_firstName = $dbRow['first_name'];
        $this->_lastName = $dbRow['last_name'];
        $this->_email = $dbRow['email'];
        $this->_username = $dbRow['username'];
        $this->_photoName = $dbRow['photo_name'];
    }
    /**
     * @return mixed
     */
    public function getUserID() {
        return $this->_userID;
    }
    /**
     * @return mixed
     */
    public function getFirstName() {
        return $this->_firstName;
    }
    /**
     * @return mixed
     */
    public function getLastName() {
        return $this->_lastName;
    }
    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->_email;
    }
    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->_username;
    }
    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->_password;
    }
    /**
     * @return mixed
     */
    public function getPhotoName()
    {
        return $this->_photoName;
    }

}