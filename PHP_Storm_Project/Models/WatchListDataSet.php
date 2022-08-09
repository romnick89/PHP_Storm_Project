<?php

require_once('Models/Database.php');
require_once('Models/WatchListData.php');

class WatchListDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    //insert topics in user's watch list
    //param topicID and userID
    public function addToWatchList($topicID, $userID) {
        $sqlQuery = "INSERT INTO watch_list(topic_id, user_id) VALUES ('$topicID', '$userID') ";

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
    }

    //check if topic is already in watch list
    //param topicID and userID
    public function checkIfInWatchList($topicID, $userID) {
        $sqlQuery = "SELECT * FROM watch_list WHERE topic_id = '$topicID' AND user_id = '$userID'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        return $statement->fetch();
    }

    //fetch all details of topics
    //param userID
    public function fetchTopics($userID){
        $sqlQuery = "SELECT * FROM watch_list, topics WHERE topics.topic_id = watch_list.topic_id AND watch_list.user_id = '$userID'";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new WatchListData($row);
        }
        return $dataSet;
    }

    //delete topics from watch list
    //param watchListID
    public function removeFromWatchList($watchListID) {
        $sqlQuery = "DELETE FROM watch_list WHERE watch_list_id = '$watchListID'";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }

    //fetch users that has the specified topic in their watch list
    //param topicID
    public function fetchUsersInWatchList($topicID) {
        $sqlQuery = "SELECT user_id,email FROM watch_list,users WHERE topic_id = '$topicID' AND watch_list.user_id = users.userID";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = $row;
        }
        return $dataSet;
    }
}
