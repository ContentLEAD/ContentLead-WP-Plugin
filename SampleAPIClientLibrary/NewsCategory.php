<?php
/**
 * @package SamplePHPApi
 */
/**
 * class NewsCategory models a category object and has a static method to parse 
 * a set of categories and return them as a collection of category objects
 * @package SamplePHPApi
 */
class NewsCategory {
    
    /**
     * @var int
     */
    private $id;
    /**
     * @var String
     */
    private $name;

    function __construct(){

    }

    /**
     * @param String $url
     * @return array[int]Category
     */
    public static function getCategories($url){
        $xh = new XMLHandler($url);

        $nl = $xh->getNodes("category");
        $catList = array();
        foreach ($nl as $n) {
            $c = new NewsCategory();
            $c->id = $n->getElementsByTagName("id")->item(0)->textContent;
            $c->name = $n->getElementsByTagName("name")->item(0)->textContent;
            $catList[]=$c;
        }
        return $catList;
    }
    
    public function getName(){
    	return $this->name;
    }
    
    public function getID(){
    	return $this->id;
    }
}
?>