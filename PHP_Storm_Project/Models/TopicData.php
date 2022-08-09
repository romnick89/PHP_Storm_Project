<?php
class TopicData {

    protected $_topicID, $_title, $_description, $_username;

    public function __construct($dbRow) {
        $this->_topicID = $dbRow['topic_id'];
        $this->_title = $dbRow['title'];
        $this->_description = $dbRow['description'];
        $this->_username = $dbRow['username'];
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
    public function getTitle() {
        return $this->_title;
    }
    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->_description;
    }
    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->_username;
    }

}