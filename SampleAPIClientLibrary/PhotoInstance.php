<?php
/**
 * @package SamplePHPApi
 */
/**
 * class PhotoInstance models a photo instance object and can parse an 
 * XML instance node into a PhotoInstance object
 * @package SamplePHPApi
 */
class PhotoInstance  {

    /**
     * @var int $width
     */
    private $width; 
    /**
     * @var int $width
     */
    private $height;
    /**
     * @var string $width
     */
    private $url;

    /**
     * @return PhotoItem
     */
    function __construct(){
        $this->width = "NULL";
        $this->height = "NULL";
        $this->url = "NULL";
    }
    
    /**
     * @param DOMNode $node
     */
    public function parsePhotoInstance($node){
    	$this->width = $node->getElementsByTagName("width")->item(0)->nodeValue;
        $this->height = $node->getElementsByTagName("height")->item(0)->nodeValue;
        $this->url = $node->getElementsByTagName("url")->item(0)->nodeValue;
    } 
    
    public function getWidth(){
    	return $this->width;
    }
    
	public function getHeight(){
    	return $this->height;
    }
    
	public function getUrl(){
    	return $this->url;
    }    
}
?>