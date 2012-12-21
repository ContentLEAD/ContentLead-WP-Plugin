<?php

include_once dirname(__FILE__) . '/../AdferoEntityBase.php';

/**
 * Represents an Article.
 * 
 */
class AdferoArticle extends AdferoEntityBase {

    /**
     * @var int
     */
    public $feedId;

    /**
     * @var int
     */
    public $briefId;

    /**
     * @var string
     */
    public $state;

    /**
     * @var array
     */
    public $fields = array();

    public function getFields() {
        return $this->fields;
    }

    public function setFields($fields) {
        $this->fields = $fields;
    }

    public function getFeedId() {
        return $this->feedId;
    }

    public function setFeedId($feedId) {
        $this->feedId = $feedId;
    }

    public function getBriefId() {
        return $this->briefId;
    }

    public function setBriefId($briefId) {
        $this->briefId = $briefId;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function getCreatedDate() {
        return $this->createdDate;
    }

    public function setCreatedDate($createdDate) {
        $this->createdDate = $createdDate;
    }

    public function getLastModifiedDate() {
        return $this->lastModifiedDate;
    }

    public function setLastModifiedDate($lastModifiedDate) {
        $this->lastModifiedDate = $lastModifiedDate;
    }

}

?>
