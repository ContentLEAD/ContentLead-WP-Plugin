<?php
/**
 * @package SamplePHPApi
 */
/**
 * class NewsComment models a comment object and has a static method to parse 
 * a set of comments and return them as a collection of comment objects
 * @package SamplePHPApi
 */
class NewsComment  {

    /* @var id */
    private $id;
    /* @var String */
    private $location;
    /* @var String */
    private $user;
    /* @var String */
    private $commentTxt;
    /* @var String */
    private $postDate;

    public function __construct(){

    }

    /**
     * @param string $url
     * @param int $id
     * @return Comment[]
     */
    public static function getComments($url){
        $xh = new XMLHandler($url);
        $nl = $xh->getNodes("commentListItem");
        $commentList = array();
        
        foreach ($nl as $n) {
            $c = new NewsComment();
            $c->setID($n->getElementsByTagName("id")->item(0)->textContent);
            $c->setLocation($n->getElementsByTagName("location")->item(0)->textContent);
            $c->setUser($n->getElementsByTagName("name")->item(0)->textContent);
            $c->setCommentTxt($n->getElementsByTagName("text")->item(0)->textContent);
            $c->setPostDate($n->getElementsByTagName("postDate")->item(0)->textContent);
            $commentList[] = $c;
        }
        return $commentList;
    }

    /**
     * @return the id
     */
    public function getID() {
        return $this->id;
    }
    
	/**
     * @param id the id to set
     */
    private function setID($id) {
        $this->id = $id;
    }
    
    
    /**
     * @return the location
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * @param location the location to set
     */
    private function setLocation($location) {
        $this->location = $location;
    }

    /**
     * @return the user
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param user the user to set
     */
    private function setUser($user) {
        $this->user = $user;
    }

    /**
     * @return the commentTxt
     */
    public function getCommentTxt() {
        return $this->commentTxt;
    }

    /**
     * @param commentTxt the commentTxt to set
     */
    private function setCommentTxt($commentTxt) {
        $this->commentTxt = $commentTxt;
    }
    
/**
     * @return the postDate
     */
    public function getPostDate() {
        return $this->postDate;
    }
    
	/**
     * @param postDate the postDate to set
     */
    public function setPostDate($postDate) {
        $this->postDate = $postDate;
    }
}
?>