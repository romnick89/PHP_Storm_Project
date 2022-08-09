<?php

require_once('Models/Database.php');
require_once('Models/PostData.php');

class PostDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    //allows retrieval of all posts
    //with specified topicID
    //offset and number of pages parameters allows pagination
    public function fetchAllPosts($topicID, $offset, $noOfPostsPerPage) {
        $sqlQuery = "SELECT post_id, post_subject, post_content, post_date, username, photo_name, topic_id FROM posts,users WHERE topic_id = '$topicID' AND posts.user_id = users.userID ORDER BY post_date DESC LIMIT $offset, $noOfPostsPerPage";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new PostData($row);
        }
        return $dataSet;
    }

    //insert comments/posts about a topic
    //param subject,content,date posted, userID of user who posted and topicID to identify which topic
    public function addComment($postSubject, $postContent, $postDate, $userID, $topicID) {
        $sqlQuery = "INSERT into posts (post_subject, post_content, post_date, user_id, topic_id) VALUES ('$postSubject', '$postContent', '$postDate', '$userID', '$topicID')";
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
    }

    //count the number of posts
    public function countNumberOfPosts($topicID) {
        $sqlQuery = "SELECT COUNT(*) as total FROM posts WHERE topic_id = '$topicID'";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $data = $statement->fetch();

        return $data['total'];
    }

    //fetch the subject of a posts
    //by using param postID
    public function fetchPost($postID) {
        $sqlQuery = "SELECT post_subject FROM posts WHERE post_id = '$postID'";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = $statement->fetch();
        return $dataSet['post_subject'];
    }

    //allows search of specified post
    //param topicID and searched topic which is "$search"
    //by giving topicID minimise searching resource
    public function searchPost($topicID, $search) {
        $sqlQuery = "SELECT post_id, post_subject, post_content, post_date, username, photo_name, topic_id FROM posts,users WHERE post_subject LIKE '%".$search."%' AND posts.user_id = users.userID AND topic_id = '$topicID' ORDER BY post_date DESC";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new PostData($row);
        }
        return $dataSet;
    }

    public function searchPosts($topicID, $search) {
        $sqlQuery = "SELECT post_id, post_subject, post_content, post_date, username, photo_name, topic_id FROM posts,users WHERE post_subject LIKE '%".$search."%' AND posts.user_id = users.userID AND topic_id = '$topicID' ORDER BY post_date DESC";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = $row;
        }
        return $dataSet;
    }

    public function selectPost($topicID, $postID) {
        $sqlQuery = "SELECT post_id, post_subject, post_content, post_date, username, photo_name, topic_id FROM posts,users WHERE post_id = '$postID' AND posts.user_id = users.userID AND topic_id = '$topicID' ORDER BY post_date DESC";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new PostData($row);
        }
        return $dataSet;
    }
}
