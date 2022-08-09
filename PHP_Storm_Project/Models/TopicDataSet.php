<?php

require_once('Models/Database.php');
require_once('Models/TopicData.php');

class TopicDataSet
{
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    //allows retrieval of all topics
    //offset and number of pages parameters allows pagination
    public function fetchTopicsDetails($offset, $noOfTopicsPerPage) {
        $sqlQuery = "SELECT topic_id,title,description, username FROM topics, users WHERE topics.user_id = users.userID ORDER BY topic_id DESC LIMIT $offset, $noOfTopicsPerPage";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new TopicData($row);
        }
        return $dataSet;
    }

    //return number of topics from the database
    //necessary for pagination
    public function countNumberOfTopics() {
        $sqlQuery = "SELECT COUNT(*) as total FROM topics";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement


        $data = $statement->fetch();

        return $data['total'];
    }

    //allows creation of new topic
    //param title, description and userID
    public function createTopic($title, $description, $userID) {
        $sqlQuery = "INSERT INTO topics (title, description, user_id) VALUES ('$title', '$description', '$userID')";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
    }

    //returns title of a topic
    //param topicID
    public function fetchTopic($topicID) {
        $sqlQuery = "SELECT title FROM topics,users WHERE topic_id = '$topicID'";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = $statement->fetch();
        return $dataSet['title'];
    }

    //returns search results
    //by specifying parameter variable "$search"
    public function searchTopic($search) {
        $sqlQuery = "SELECT * FROM topics,users WHERE title LIKE '%".$search."%' AND topics.user_id = users.userID";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new TopicData($row);
        }
        return $dataSet;
    }



}