<?php
/** 
 * @package SamplePHPApi
*/

/** 
 * Include Files
*/
include_once 'XMLHandler.php';
include_once 'NewsItem.php';

/**
 * class ApiHandler has a contructor which takes an API KEY and a baseurl as parameters
 * and will throw an exception if either are incorrect 
 * @package SamplePHPApi
 */
class ApiHandler {

    /**
     * @var Feed $feed
     */
    private $API_KEY;
    private $apiUrl;
    private $newsUrl;
    private $categoryUrl;
    private $commentUrl;
    private $feedName;	 

    public function __construct($API_KEY, $apiUrl){
        $this->API_KEY = $API_KEY;
        $trimmedUrl =  rtrim($apiUrl, "/");
        $this->apiUrl = $trimmedUrl . "/";
       
        $xh = new XMLHandler($this->getFeedUrl());
        
        $this->newsUrl = $xh->getHrefValue("news");
        $this->categoryUrl = $xh->getHrefValue("categoryDefinitions");
        $this->commentUrl = $xh->getHrefValue("comments");
        $this->feedName = $xh->getValue("name");
    }

    /**
     * @return NewsItem[]
     */
    public function getNewsHTML(){
        return NewsItem::getNewsList($this->newsUrl, "html");
    }
    
	/**
     * @return NewsItem[]
     */
    public function getNewsRaw(){
        return NewsItem::getNewsList($this->newsUrl, "raw");
    }
    
    /**
     * @return Category[]
     */
    public function getCategoryDefinitions(){
        return NewsCategory::getCategories($this->categoryUrl);
    }
    
	/**
     * @return Comment[]
     */
    public function getLatestComments(){
        return NewsComment::getComments($this->commentUrl);
    }

    /**
     * @return String
     */
    private function getFeedUrl(){
       return $this->apiUrl . $this->API_KEY . "/";
    }  
}
?>