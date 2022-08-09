<?php
class ReplyData {

    protected $_replyID, $_postID, $_username , $_photoName , $_topicID, $_replyDate, $_replyComment;

    public function __construct($dbRow)
    {
        $this->_replyID = $dbRow['reply_id'];
        $this->_postID = $dbRow['post_id'];
        $this->_username = $dbRow['username'];
        $this->_photoName = $dbRow['photo_name'];
        $this->_topicID = $dbRow['topic_id'];
        $this->_replyDate = $dbRow['reply_date'];
        $this->_replyComment = $dbRow['comment'];
    }
    /**
     * @return mixed
     */
    public function getReplyID()
    {
        return $this->_replyID;
    }
    /**
     * @return mixed
     */
    public function getPostID()
    {
        return $this->_postID;
    }
    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->_username;
    }
    /**
     * @return mixed
     */
    public function getPhotoName()
    {
        return $this->_photoName;
    }
    /**
     * @return mixed
     */
    public function getTopicID()
    {
        return $this->_topicID;
    }
    /**
     * @return mixed
     */
    public function getReplyDate()
    {
        return $this->_replyDate;
    }
    /**
     * @return mixed
     */
    public function getReplyComment()
    {
        return $this->_replyComment;
    }
}