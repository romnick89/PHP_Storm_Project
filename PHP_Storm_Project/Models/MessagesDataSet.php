<?php

require_once('Models/Database.php');
require_once('Models/MessagesData.php');

class MessagesDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    //allows retrieval of all messages
    //with specified userID
    //offset and number of pages parameters allows pagination
    public function fetchMessages($userID, $offset, $noOfPostsPerPage){
        $sqlQuery = "SELECT messages_id, subject, content, date, email FROM messages,users WHERE messages.receive_by = '$userID' AND messages.sent_by = users.userID ORDER BY date DESC LIMIT $offset, $noOfPostsPerPage";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new MessagesData($row);
        }
        return $dataSet;
    }

    //allows users to send message to users that are registered in the system
    public function sendMessage($subject, $content, $date, $sentBy, $receiveBy) {
        $sqlQuery = "INSERT INTO messages (subject, content, date, sent_by, receive_by) VALUES ('$subject', '$content', '$date', '$sentBy', '$receiveBy') ";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
    }

    //remove a message in the database
    public function deleteMessage($messageID) {
        $sqlQuery = "DELETE FROM messages WHERE messages_id = '$messageID'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }

    //fetch total number of messages in the database
    //with param userID
    public function countNumberOfMessages($userID) {
        $sqlQuery = "SELECT COUNT(*) as total FROM messages WHERE receive_by = '$userID'";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $data = $statement->fetch();

        return $data['total'];
    }
}