<?php

include_once dirname(__FILE__) . '/../AdferoHelpers.php';
include_once dirname(__FILE__) . '/AdferoCategory.php';
include_once dirname(__FILE__) . '/AdferoCategoryListItem.php';
include_once dirname(__FILE__) . '/AdferoCategoryList.php';

/**
 * Client that provides category related functions.
 *
 * 
 */
class AdferoCategoriesClient {

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var AdferoCredentials
     */
    private $credentials;

    /**
     * Initialises a new instance of the Categories Client
     * @param string $baseUri Uri of the API provided by your account manager
     * @param AdferoCredentials $credentials Credentials object containing publicKey and secretKey.
     */
    function __construct($baseUri, $credentials) {
        $this->baseUri = $baseUri;
        $this->credentials = $credentials;
    }

    /**
     * Gets the category with the provided id.
     *
     * @param int $id Id of the category to get
     * @return AdferoCategory 
     * @throws InvalidArgumentException 
     */
    public function Get($id) {
        if (!isset($id)) {
            throw new InvalidArgumentException("id is required");
        }

        return $this->GetCategory($id, null, null);
    }

    /**
     * Gets the raw output from the api as a string.
     *
     * @param int $id id of the category to get
     * @param string $format can be either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException 
     */
    public function GetRaw($id, $format) {
        if (!isset($id)) {
            throw new InvalidArgumentException("id is required");
        }

        if (!isset($format)) {
            throw new InvalidArgumentException("format is required");
        }

        return $this->GetCategoryRaw($id, null, null, $format);
    }

    /**
     * Lists the categories under a particular feed.
     *
     * @param int $feedId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @return AdferoCategoryList 
     * @throws InvalidArgumentException 
     */
    public function ListForFeed($feedId, $offset, $limit) {
        if (!isset($feedId)) {
            throw new InvalidArgumentException("feedId is required");
        }

        if (!isset($offset)) {
            throw new InvalidArgumentException("offset is required");
        }

        if (!isset($limit)) {
            throw new InvalidArgumentException("limit is required");
        }


        return $this->ListCategoriesForFeed($feedId, $offset, $limit, null, null);
    }

    /**
     * Gets the raw output from the api as a string.
     *
     * @param int $feedId
     * @param int $offset The offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param string $format can be either "xml" or "json"
     * @return AdferoCategoryList 
     * @throws InvalidArgumentException 
     */
    public function ListForFeedRaw($feedId, $offset, $limit, $format) {
        if (!isset($feedId)) {
            throw new InvalidArgumentException("feedId is required");
        }

        if (!isset($offset)) {
            throw new InvalidArgumentException("offset is required");
        }

        if (!isset($limit)) {
            throw new InvalidArgumentException("limit is required");
        }

        if (!isset($format)) {
            throw new InvalidArgumentException("format is required");
        }

        return $this->ListCategoriesForFeedRaw($feedId, $offset, $limit, null, null, $format);
    }

    /**
     * Lists the categories under a particular article.
     *
     * @param int $articleId
     * @param int $offset The offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @return AdferoCategoryList 
     * @throws InvalidArgumentException 
     */
    public function ListForArticle($articleId, $offset, $limit) {
        if (!isset($articleId)) {
            throw new InvalidArgumentException("articleId is required");
        }

        if (!isset($offset)) {
            throw new InvalidArgumentException("offset is required");
        }

        if (!isset($limit)) {
            throw new InvalidArgumentException("limit is required");
        }

        return $this->ListCategoriesForArticle($articleId, $offset, $limit, null, null);
    }

    /**
     * Gets the raw output from the api as a string.
     * $format is either "xml" or "json"
     *
     * @param int $feedId
     * @param int $offset The offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param string $format can be either "xml" or "json"
     * @return AdferoCategoryList 
     * @throws InvalidArgumentException 
     */
    public function ListForArticleRaw($feedId, $offset, $limit, $format) {
        if (!isset($feedId)) {
            throw new InvalidArgumentException("feedId is required");
        }

        if (!isset($offset)) {
            throw new InvalidArgumentException("offset is required");
        }

        if (!isset($limit)) {
            throw new InvalidArgumentException("limit is required");
        }

        if (!isset($format)) {
            throw new InvalidArgumentException("format is required");
        }

        return $this->ListCategoriesForArticleRaw($feedId, $offset, $limit, null, null, $format);
    }

    /**
     * Gets the category with the provided id 
     *
     * @param int $id Id of the category to get
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoCategoryList 
     */
    private function GetCategory($id, $properties, $fields) {
        $uri = $this->GetUri($id, null, "xml", $properties, $fields, null, null);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        return $this->GetCategoryFromXmlString($xmlString);
    }

    /**
     * Gets the response from the api in string format
     * 
     * @param int $id Id of the category to get
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @param string $format can be either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException 
     */
    private function GetCategoryRaw($id, $properties, $fields, $format) {
        switch ($format) {
            case "xml":
                $uri = $this->GetUri($id, null, "xml", $properties, $fields, null, null);
                break;
            case "json":
                $uri = $this->GetUri($id, null, "json", $properties, $fields, null, null);
                break;
            default:
                throw new InvalidArgumentException($format . 'format not supported');
                break;
        }
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        return AdferoHelpers::GetRawResponse($uri);
    }

    /**
     * Gets the response from the api in string format
     * 
     * @param int $feedId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return string
     * @throws InvalidArgumentException
     */
    private function ListCategoriesForFeedRaw($feedId, $offset, $limit, $properties, $fields, $format) {
        switch ($format) {
            case "xml":
                $uri = $this->GetUri($feedId, "feedId", "xml", $properties, $fields, $offset, $limit);
                break;
            case "json":
                $uri = $this->GetUri($feedId, "feedId", "json", $properties, $fields, $offset, $limit);
                break;
            default:
                throw new InvalidArgumentException($format . 'format not supported');
                break;
        }
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        return AdferoHelpers::GetRawResponse($uri);
    }

    /**
     * Gets the response from the api in string format
     * 
     * @param int $articleId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return string
     * @throws InvalidArgumentException
     */
    private function ListCategoriesForArticleRaw($articleId, $offset, $limit, $properties, $fields, $format) {
        switch ($format) {
            case "xml":
                $uri = $this->GetUri($articleId, "articleId", "xml", $properties, $fields, $offset, $limit);
                break;
            case "json":
                $uri = $this->GetUri($articleId, "articleId", "json", $properties, $fields, $offset, $limit);
                break;
            default:
                throw new InvalidArgumentException($format . 'format not supported');
                break;
        }
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        return AdferoHelpers::GetRawResponse($uri);
    }

    /**
     * Gets the category from xml string
     *
     * @param string $xml
     * @return AdferoCategory
     */
    private function GetCategoryFromXmlString($xml) {
        $xml = new SimpleXMLElement($xml);
        $category = new AdferoCategory();
        foreach ($xml->category->children() as $child) {
            switch ($child->getName()) {
                case "id": $category->id = intval($child);
                    break;
                case "name":
                    $category->name = (string) $child;
                    break;
                case "parentId":
                    $category->parentId = intval($child);
                    break;
            }
        }

        return $category;
    }

    /**
     *  Lists the categories under a particular feed.
     *
     * @param int feedId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoCategoryList 
     */
    private function ListCategoriesForFeed($feedId, $offset, $limit, $properties, $fields) {
        $uri = $this->GetUri($feedId, "feedId", "xml", $properties, $fields, $offset, $limit);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        $categories = $this->ListCategoriesFromXmlString($xmlString);
        $categories->limit = $limit;
        $categories->offset = $offset;
        return $categories;
    }

    /**
     *  Lists the categories under a particular article.
     *
     * @param int feedId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoCategoryList 
     */
    private function ListCategoriesForArticle($articleId, $offset, $limit, $properties, $fields) {
        $uri = $this->GetUri($articleId, "articleId", "xml", $properties, $fields, $offset, $limit);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        $categories = $this->ListCategoriesFromXmlString($xmlString);
        $categories->limit = $limit;
        $categories->offset = $offset;
        return $categories;
    }

    /**
     * Gets category listing from xml string.
     *
     * @param string $xml
     * @return AdferoCategoryList 
     */
    private function ListCategoriesFromXmlString($xml) {
        $xml = new SimpleXMLElement($xml);
        $totalCount = intval($xml->categories['totalCount']);
        $categoryItems = array();

        foreach ($xml->categories->category as $child) {
            foreach ($child->id as $categoryId) {
                $category = new AdferoCategoryListItem();
                $category->id = intval($categoryId);
                array_push($categoryItems, $category);
            }
        }

        $categories = new AdferoCategoryList();
        $categories->items = $categoryItems;
        $categories->totalCount = $totalCount;
        return $categories;
    }

    /**
     * Generates the URI from requested pararmeters.
     *
     * @param int $id id of the item to get or id of identifier for listing
     * @param string $identifier name of the identifier used for listing, use null for a get
     * @param string $format can be either "xml" or "json"
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @param int $offset offset to skip
     * @param int $limit limit to apply to the list (maximum 100)
     * @return string 
     */
    private function GetUri($id, $identifier, $format, $properties, $fields, $offset, $limit) {
        $data = array();

        if ($properties != null) {
            $properties = implode(",", $properties);
            $data["properties"] = $properties;
        }

        if ($fields != null) {
            $fields = implode(",", $fields);
            $data["fields"] = $fields;
        }

        if (($identifier != null || $identifier != "") || ($offset != null || $limit !=
                null)) {

            $data["offset"] = $offset;
            $data["limit"] = $limit;
            $data = array_merge(array($identifier => $id), $data);
            $querystring = urldecode(http_build_query($data));
            return $this->baseUri . "categories." . $format . "?" . $querystring;
        } else {
            $querystring = urldecode(http_build_query($data));
            return $this->baseUri . "categories" . "/" . $id . "." . $format . "?" .
                    $querystring;
        }
    }

}

?>
