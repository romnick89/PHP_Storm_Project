<?php

require_once('Models/Database.php');
require_once('Models/ReplyData.php');


class ReplyDataSet {
    protected $_dbHandle, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    //allows retrieval of all replies/comments
    //by specifying the topicID and postID param
    //offset and number of pages parameters allows pagination
    public function fetchAllReplies($postID, $topicID, $offset, $noOfPostsPerPage) {
        $sqlQuery =
            "SELECT reply_id, post_id, username, photo_name, topic_id, reply_date, comment FROM replies,users WHERE post_id = '$postID' AND topic_id = '$topicID' AND replies.user_id = users.userID ORDER BY reply_date DESC LIMIT $offset, $noOfPostsPerPage";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new ReplyData($row);
        }
        return $dataSet;
    }

    //add reply/comment to a post
    //param postID, userID, topicID, date and comment
    public function addReply($postID, $userID, $topicID, $replyDate, $replyComment) {
        $sqlQuery = "INSERT INTO replies (post_id, user_id, topic_id, reply_date, comment) VALUES ('$postID', '$userID', '$topicID', '$replyDate', '$replyComment')";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
    }

    //get number of replies/comments
    //by specifying the topicID and postID
    //necessary for pagination
    public function countNumberOfReplies($postID, $topicID) {
        $sqlQuery = "SELECT COUNT(*) as total FROM replies WHERE topic_id = '$topicID' AND post_id = '$postID'";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $data = $statement->fetch();

        return $data['total'];
    }
}
