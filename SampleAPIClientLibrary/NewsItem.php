<?php
/**
 * @package SamplePHPApi
 */
/**
 * Include Files
 */
include_once 'NewsCategory.php';
include_once 'Photo.php';
include_once 'NewsComment.php';

/**
 * Constant Definitions for XML elements and attributes
 */
define("NEWS_LIST_ITEM", "newsListItem");
define("NEWS_ITEM", "newsItem");
define("HREF", "href");
define("ID", "id");
define("HEADLINE", "headline");
define("PUBLISH_DATE", "publishDate");
define("ENCODING", "encoding");
define("CREATED_DATE", "createdDate");
define("LAST_MODIFIED_DATE", "lastModifiedDate");
define("EXTRACT", "extract");
define("TEXT", "text");
define("BY_LINE", "byLine");
define("TWEET_TEXT", "tweetText");
define("SOURCE", "source");
define("STATE", "state");
define("CLIENT_QUOTE", "clientQuote");
define("HTML_TITLE", "htmlTitle");
define("HTML_META_DESCRIPTION", "htmlMetaDescription");
define("HTML_META_KEYWORDS", "htmlMetaKeywords");
define("HTML_META_LANGUAGE", "htmlMetaLanguage");
define("KEYWORDS", 'keywords');
define("TAGS", "tags");
define("PRIORITY", "priority");
define("FORMAT", "format");
define("PHOTOS", "photos");
define("CATEGORIES", "categories");
define("COMMENTS", "comments");
/**
 * class NewsItem models a news object and has a static method to parse 
 * a set of news items and return them as a collection of NewsItem objects
 * @package SamplePHPApi
 */
class NewsItem	{
	/* @var XMLHandler */
	private $xh;
	/* @var String */
	private $encoding;
	/* @var int */
	private $id;
	/* @var String */
	private $publishDate;
	/* @var String */
	private $createdDate;
	/* @var String */
	private $lastModifiedDate;
	/*  @var String */
	private $headline;
	/* @var String */
	private $extract;
	/* @var String */
	private $text;
	/* @var String */
	private $href;
	/* @var String */
	private $byLine;
	/* @var String */
	private $tweetText;
	/* @var String */
	private $source;
	/* @var String */
	private $state;
	/* @var String */
	private $clientQuote;
	/* @var String */
	private $htmlTitle;
	/* @var String */
	private $htmlMetaDescription;
	/* @var String */
	private $htmlMetaKeywords;
	/* @var String */
	private $htmlMetaLanguage;
	/* @var String */
	private $tags;
	/* @var int */
	private $priority;
	/* @var String */
	private $format;
  /* @var String */
  private $keywords;
	/* @var photos[] */
	private $photos;
	/* @var NewsCategory[] */
	private $categories;
	/* @var NewsComment[] */
	private $comments;

	/** @return NewsItem **/
	function __construct(){
	}

	/** @return XMLHandler **/
	private function getFullNewsXML(){
		if(empty($this->xh)){
			if(strcasecmp($this->getFormat(), "html"))$this->xh = new XMLHandler($this->href);
			else $this->xh = new XMLHandler($this->href . $this->getFormat());
		}
		return $this->xh;
	}

	/**
	 * @param String $url
	 * @return NewsItem[]
	 */
	public static function getNewsList($url, $format) {
		//Exception thrown in XMLHandler constructor if url is incorrect	
		$xh = new XMLHandler($url);

		$newsList = array();
		if(isset($xh)){
			$news = $xh->getNodes(NEWS_LIST_ITEM);
			$exceptionList = array();

			foreach($news as $n){
				/* @var $n DomElement */
				$ni = new NewsItem();
				try{
					//Check if all required nodes exist, throw exception if not!
					if($n->getElementsByTagName(ID)->length==0)throw new XMLNodeException("Element " . ID . " for " . NEWS_LIST_ITEM);
					//set value of ID here to use in debugging!
					$ni->id = $n->getElementsByTagName(ID)->item(0)->textContent;
						
					if($n->getElementsByTagName(PUBLISH_DATE)->length==0)throw new XMLNodeException("Element " . PUBLISH_DATE . " for " . NEWS_LIST_ITEM . " with id: " . $ni->id);
					if(!$n->getAttribute(HREF))throw new XMLNodeException("Attribute " . HREF . " for " . NEWS_LIST_ITEM . " with id: " . $ni->id);
					if($n->getElementsByTagName(HEADLINE)->length==0)throw new XMLNodeException("Element " . HEADLINE . " for " . NEWS_LIST_ITEM . " with id: " . $ni->id);

					//Check if date is valid if not throw exception
					$ni->publishDate = $n->getElementsByTagName(PUBLISH_DATE)->item(0)->textContent;
					$dateIsValid = date_parse($ni->publishDate);
					if(!$dateIsValid)throw new DateParseException("Invalid Date for " . PUBLISH_DATE . "  on " . NEWS_LIST_ITEM . " with id: " . $ni->id . "<br />\n");

					//Set the value of all other required elements
					$ni->href = $n->getAttribute(HREF);
					$ni->headline = $n->getElementsByTagName(HEADLINE)->item(0)->textContent;
					$ni->format = $format;
						
					//Add to newslist array
					$newsList[] = $ni;
				}
				catch(XMLException $e){
					$exceptionList[] = $e; //Add exception to a list
				}
				catch(DateParseException $e){
					$exceptionList[] = $e;
				}
			}
			//If exception list contains any exceptions throw a new exception which relays all exceptions to the user
			if(!empty($exceptionList)){
				echo implode("<br />", $exceptionList) . "<br /><br />";
			}
		}
		return $newsList;
	}

	/** @return String **/
	public function getEncoding() {
		if(empty($this->encoding)){
			$xh = $this->getFullNewsXML();
			$this->encoding = $xh->getAttributeValue(NEWS_ITEM, ENCODING);
		}
		return $this->encoding;
	}

	/** @return int **/
	public function getId() {
		if(empty($this->id)){
			$xh = $this->getFullNewsXML();
			$this->id = $xh->getValue(ID);
		}
		return $this->id;
	}

	/** @return String **/
	public function getPublishDate() {
		if(empty($this->publishDate)){
			$xh = $this->getFullNewsXML();
			$this->publishDate = $xh->getValue(PUBLISH_DATE);
		}
		return $this->publishDate;
	}

	/** @return String **/
	public function getHeadline() {
		if(empty($this->headline)){
			$xh = $this->getFullNewsXML();
			$this->headline = $xh->getValue(HEADLINE);
		}
		return $this->headline;
	}

	/** @return String **/
	public function getCategories() {
		if(empty($this->categories)){
			$xh = $this->getFullNewsXML();
			$this->categories = NewsCategory::getCategories($xh->getHrefValue(CATEGORIES));
		}
		return $this->categories;
	}
  
  /** @return String **/
  public function getKeywords() {
    if(empty($this->categories)){
			$xh = $this->getFullNewsXML();
			$this->keywords = $xh->getValue(KEYWORDS);
		}
		return $this->keywords;
  }

	/** @return String **/
	public function getCreatedDate() {
		if(empty($this->createdDate)){
			$xh = $this->getFullNewsXML();
			$this->createdDate = $xh->getValue(CREATED_DATE);
			if(empty($this->createdDate))throw new XMLNodeException("Element " . CREATED_DATE . " for " . NEWS_LIST_ITEM . " with id: " . $this->id . "<br />\n");
		}
		return $this->createdDate;
	}

	/** @return String **/
	public function getLastModifiedDate() {
		if(empty($this->lastModifiedDate)){
			$xh = $this->getFullNewsXML();
			$this->lastModifiedDate = $xh->getValue(LAST_MODIFIED_DATE);
			if(empty($this->lastModifiedDate))throw new XMLNodeException("Element " . LAST_MODIFIED_DATE . " for " . NEWS_LIST_ITEM . " with id: " . $this->id . "<br />\n");
		}
		return $this->lastModifiedDate;
	}

	/** @return String **/
	public function getPhotos() {
		if(empty($this->photos)){
			$xh = $this->getFullNewsXML();
			$this->photos = Photo::getPhotos($xh->getHrefValue(PHOTOS));
		}
		return $this->photos;
	}

	/** @return String **/
	public function getComments() {
		if(empty($this->comments)){
			$xh = $this->getFullNewsXML();
			$this->comments = NewsComment::getComments($xh->getHrefValue(COMMENTS));
		}
		return $this->comments;
	}

	/** @return String **/
	public function getExtract() {
		if(empty($this->extract)){
			$xh = $this->getFullNewsXML();
			$this->extract = $xh->getValue(EXTRACT);
		}
		return $this->extract;
	}

	/** @return String **/
	public function getText() {
		if(empty($this->text)){
			$xh = $this->getFullNewsXML();
			$this->text = $xh->getValue(TEXT);
			//if(empty($this->text))throw new XMLNodeException("Element " . TEXT . " for " . NEWS_LIST_ITEM . " with id: " . $this->id . "<br />\n");
		}
		return $this->text;
	}

	/** @return String **/
	public function getByLine() {
		if(empty($this->byLine)){
			$xh = $this->getFullNewsXML();
			$this->byLine = $xh->getValue(BY_LINE);
		}
		return $this->byLine;
	}

	/** @return String **/
	public function getTweetText() {
		if(empty($this->tweetText)){
			$xh = $this->getFullNewsXML();
			$this->tweetText = $xh->getValue(TWEET_TEXT);
		}
		return $this->tweetText;
	}

	/** @return String **/
	public function getSource() {
		if(empty($this->source)){
			$xh = $this->getFullNewsXML();
			$this->source = $xh->getValue(SOURCE);
		}
		return $this->source;
	}

	/** @return String **/
	public function getState() {
		if(empty($this->state)){
			$xh = $this->getFullNewsXML();
			$this->state = $xh->getValue(STATE);
			if(empty($this->state))throw new XMLNodeException("Element " . STATE . " for " . NEWS_LIST_ITEM . " with id: " . $this->id . "<br />\n");
		}
		return $this->state;
	}

	/** @return String **/
	public function getClientQuote() {
		if(empty($this->clientQuote)){
			$xh = $this->getFullNewsXML();
			$this->clientQuote = $xh->getValue(CLIENT_QUOTE);
		}
		return $this->clientQuote;
	}

	/** @return String **/
	public function getHtmlTitle() {
		if(empty($this->htmlTitle)){
			$xh = $this->getFullNewsXML();
			$this->htmlTitle = $xh->getValue(HTML_TITLE);
		}
		return $this->htmlTitle;
	}

	/** @return String **/
	public function getHtmlMetaDescription() {
		if(empty($this->htmlMetaDescription)){
			$xh = $this->getFullNewsXML();
			$this->htmlMetaDescription = $xh->getValue(HTML_META_DESCRIPTION);
		}
		return $this->htmlMetaDescription;
	}

	/** @return String **/
	public function getHtmlMetaKeywords() {
		if(empty($this->htmlMetaKeywords)){
			$xh = $this->getFullNewsXML();
			$this->htmlMetaKeywords = $xh->getValue(HTML_META_KEYWORDS);
		}
		return $this->htmlMetaKeywords;
	}

	/** @return String **/
	public function getHtmlMetaLanguage() {
		if(empty($this->htmlMetaLanguage)){
			$xh = $this->getFullNewsXML();
			$this->htmlMetaLanguage = $xh->getValue(HTML_META_LANGUAGE);
		}
		return $this->htmlMetaLanguage;
	}

	/** @return String **/
	public function getTags() {
		if(empty($this->tags)){
			$xh = $this->getFullNewsXML();
			$this->tags = $xh->getValue(TAGS);
		}
		return $this->tags;
	}

	/** @return int **/
	public function getPriority() {
		if(empty($this->priority)){
			$xh = $this->getFullNewsXML();
			$this->priority = $xh->getValue(PRIORITY);
		}
		return $this->priority;
	}

	/** @return String **/
	public function getFormat() {
		if(empty($this->format)){
			$xh = $this->getFullNewsXML();
			$this->htmlMetaLanguage = $xh->getAttributeValue(TEXT, FORMAT);
		}
		return $this->format;
	}
}
/**
 * Custom Exception DateParseException to be thrown if a date does not parse correctly
 * @package SamplePHPApi
 */
class DateParseException extends Exception{}
?>