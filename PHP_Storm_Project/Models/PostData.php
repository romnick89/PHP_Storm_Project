<?php
class PostData  implements  JsonSerializable {

    protected $_postID, $_postSubject, $_postContent, $_postDate, $_username, $_photoName, $_topicID;

    public function __construct($dbRow) {
        $this->_postID = $dbRow['post_id'];
        $this->_postSubject = $dbRow['post_subject'];
        $this->_postContent = $dbRow['post_content'];
        $this->_postDate = $dbRow['post_date'];
        $this->_username = $dbRow['username'];
        $this->_photoName = $dbRow['photo_name'];
        $this->_topicID = $dbRow['topic_id'];
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
    public function getPostSubject()
    {
        return $this->_postSubject;
    }
    /**
     * @return mixed
     */
    public function getPostContent()
    {
        return $this->_postContent;
    }
    /**
     * @return mixed
     */
    public function getPostDate()
    {
        return $this->_postDate;
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
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
                'postID' => $this->_postID,
                'postSubject' => $this->_postSubject,
                'postContent' => $this->_postContent,
                'postDate' => $this->_postDate,
                'postUsername' => $this->_username,
                'postTopicID' => $this->_topicID
        ];
    }
}