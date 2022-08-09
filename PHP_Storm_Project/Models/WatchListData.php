<?php

class WatchListData {
    protected $_watchListID, $_topicID, $_userID, $_title;

    public function __construct($dbRow)
    {
        $this->_watchListID = $dbRow['watch_list_id'];
        $this->_topicID = $dbRow['topic_id'];
        $this->_userID = $dbRow['user_id'];
        $this->_title = $dbRow['title'];
    }
    /**
     * @return mixed
     */
    public function getWatchListID()
    {
        return $this->_watchListID;
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
    public function getUserID()
    {
        return $this->_userID;
    }
    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

}