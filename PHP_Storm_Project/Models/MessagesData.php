<?php
class MessagesData {
    protected $_messageID, $_subject, $_content, $_date, $_sentBy;

    public function __construct($dbRow)
    {
        $this->_messageID = $dbRow['messages_id'];
        $this->_subject = $dbRow['subject'];
        $this->_content = $dbRow['content'];
        $this->_date = $dbRow['date'];
        $this->_sentBy = $dbRow['email'];
    }
    /**
     * @return mixed
     */
    public function getMessageID()
    {
        return $this->_messageID;
    }
    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->_subject;
    }
    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->_content;
    }
    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->_date;
    }
    /**
     * @return mixed
     */
    public function getSentBy()
    {
        return $this->_sentBy;
    }

}