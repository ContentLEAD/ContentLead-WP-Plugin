<?php
/**
 * @package SamplePHPApi
 */
/**
 * class XMLHandler is a helper class to parse the XML feed data
 * @package SamplePHPApi
 */
class XMLHandler {
	/** @var Document */
	private $doc;

  	static $ch;
	

		/**
	 * @param String $url
	 * @return XMLHandler
	 */
	function __construct($url){
	 if(!preg_match('/^http:\/\//', $url)){
      $url = 'file://' . $url;
    }

	$this->doc = new DOMDocument();
    
    //load wp_http class   
	if( !class_exists( 'WP_Http' ) )
	  	include_once( ABSPATH . WPINC . '/class-http.php' );

    $request = new WP_Http; 
    $result = $request->request( $url );

    $feed_string = $result['body'];

    
		if(!$this->doc->loadXML($feed_string)) {
			echo $url."<br>";
			throw new XMLLoadException($url);
		}

	}
  
  /*
  public static function CURL_pull($url,$timeout) { // use CURL library to fetch remote file
    print 'called getfile '; flush();

    print ' execced curl ';
    if ( curl_getinfo($ch,CURLINFO_HTTP_CODE) != 200 ) {
      throw new Exception('Return Status: '.curl_getinfo($ch,CURLINFO_HTTP_CODE).', please try again after a while, could not load URL :'.$url);
          return false;
    } else { return $file_contents; }
  }*/

	/**
	 * @param String $element
	 * @return String
	 */
	function getValue($element){
		$result = $this->doc->getElementsByTagName($element);
		if($result->length != null) return $this->doc->getElementsByTagName($element)->item(0)->nodeValue;
		else return null;
	}

	/**
	 * @param String $element
	 * @return String
	 */
	function getHrefValue($element){
		return $this->doc->getElementsByTagName($element)->item(0)->getAttribute('href');
	}

	/**
	 * @param String $element
	 * @param String $attribute
	 * @return String
	 */
	function getAttributeValue($element, $attribute){
		return $this->doc->getElementsByTagName($element)->item(0)->getAttribute($attribute);
	}

	/**
	 * @param String $element
	 * @return DOMNodeList
	 */
	function getNodes($element){
		return $this->doc->getElementsByTagName($element);
	}

	/**
	 * @param String $element
	 * @return String
	 */
	public static function getSetting($element){
		$xh = new XMLHandler("../Classes/settings.xml");
		return $xh->getValue($element);
	}
}

/**
 * Custom Exception XMLException
 * @package SamplePHPApi
 */
class XMLException extends Exception{}

/**
 * Custom Exception XMLLoadException thrown if an XML source file is not found
 * @package SamplePHPApi
 */
class XMLLoadException extends XMLException{
	function __construct($message, $code=""){
		$this->message = "Could not load URL: " . $message;
	}
}

/**
 * Custom Exception XMLNodeException thrown if a required XML element is not found
 * @package SamplePHPApi
 */
class XMLNodeException extends XMLException{
	function __construct($message, $code=""){
		$this->message = "Could not find XMLNode: " . $message;
	}
}
?>
