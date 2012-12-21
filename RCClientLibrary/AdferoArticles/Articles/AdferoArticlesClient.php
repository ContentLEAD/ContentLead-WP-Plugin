<?php

include_once dirname(__FILE__) . '/../AdferoHelpers.php';
include_once dirname(__FILE__) . '/AdferoArticle.php';
include_once dirname(__FILE__) . '/AdferoArticleListItem.php';
include_once dirname(__FILE__) . '/AdferoArticleList.php';

/**
 *  Client that provides article related functions.
 */
class AdferoArticlesClient {

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var AdferoCredentials
     */
    private $credentials;

    /**
     * Initialises a new instance of the Articles Client
     * @param string $baseUri Uri of the API provided by your account manager
     * @param AdferoCredentials $credentials Credentials object containing publicKey and secretKey.
     */
    function __construct($baseUri, $credentials) {
        $this->baseUri = $baseUri;
        $this->credentials = $credentials;
    }

    /**
     * Gets the article with the provided article Id.
     *
     * @param int $id Id of the article to get
     * @return AdferoArticle 
     * @throws InvalidArgumentException 
     */
    public function Get($id) {
        if (!isset($id)) {
            throw new InvalidArgumentException("id is required");
        }

        return $this->GetArticle($id, null, null);
    }

    /**
     * Returns the raw response from the api for the article with the provided article Id.
     *     
     * @param int $id id of the article to get
     * @param string $format is either "xml" or "json"
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

        return $this->GetArticleRaw($id, null, null, $format);
    }

    /**
     * Lists the articles under a particular brief.
     *
     * @param int $briefId The brief to list articles for
     * @param string $state can be either "live" or "approval"
     * @param int $offset The offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @return AdferoArticleList 
     * @throws InvalidArgumentException 
     */
    public function ListForBrief($briefId, $state, $offset, $limit) {
        if (!isset($briefId)) {
            throw new InvalidArgumentException("briefId is required");
        }

        if (!isset($offset)) {
            throw new InvalidArgumentException("offset is required");
        }

        if (!isset($limit)) {
            throw new InvalidArgumentException("limit is required");
        }

        if (!isset($state)) {
            throw new InvalidArgumentException("state is required");
        }

        return $this->ListArticlesForBrief($briefId, $offset, $limit, $state, null, null);
    }

    /**
     * Gets the raw output from the api as a string.
     *
     * @param int $briefId brief to list articles for
     * @param string $state can be either "live" or "approval"
     * @param int $offset The offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param string $format can be either "xml" or "json"
     * @return AdferoArticleList
     * @throws InvalidArgumentException  
     */
    public function ListForBriefRaw($briefId, $state, $offset, $limit, $format) {
        if (!isset($briefId)) {
            throw new InvalidArgumentException("briefId is required");
        }

        if (!isset($offset)) {
            throw new InvalidArgumentException("offset is required");
        }

        if (!isset($limit)) {
            throw new InvalidArgumentException("limit is required");
        }

        if (!isset($state)) {
            throw new InvalidArgumentException("state is required");
        }

        if (!isset($format)) {
            throw new InvalidArgumentException("format is required");
        }

        return $this->ListArticlesForBriefRaw($briefId, $offset, $limit, $state, null, null, $format);
    }

    /**
     * Lists the articles under a particular feed.
     *
     * @param int feedId The feed to list articles for
     * @param string $state can be either "live" or "approval"
     * @param int $offset The offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @return AdferoArticleList 
     * @throws InvalidArgumentException 
     */
    public function ListForFeed($feedId, $state, $offset, $limit) {
        if (!isset($feedId)) {
            throw new InvalidArgumentException("feedId is required");
        }

        if (!isset($offset)) {
            throw new InvalidArgumentException("offset is required");
        }

        if (!isset($limit)) {
            throw new InvalidArgumentException("limit is required");
        }

        if (!isset($state)) {
            throw new InvalidArgumentException("state is required");
        }

        return $this->ListArticlesForFeed($feedId, $offset, $limit, $state, null, null);
    }

    /**
     * Gets the raw output from the api as a string.
     * 
     * @param int $feedId The feed to list articles for
     * @param string $state can be either "live" or "approval"
     * @param int $offset The offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param string $format can be either "xml" or "json"
     * @return string 
     * @throws InvalidArgumentException 
     */
    public function ListForFeedRaw($feedId, $state, $offset, $limit, $format) {
        if (!isset($feedId)) {
            throw new InvalidArgumentException("feedId is required");
        }

        if (!isset($offset)) {
            throw new InvalidArgumentException("offset is required");
        }

        if (!isset($limit)) {
            throw new InvalidArgumentException("limit is required");
        }

        if (!isset($state)) {
            throw new InvalidArgumentException("state is required");
        }

        if (!isset($format)) {
            throw new InvalidArgumentException("format is required");
        }

        return $this->ListArticlesForFeedRaw($feedId, $offset, $limit, $state, null, null, $format);
    }

    /**
     * Lists the articles under a particular brief.
     * 
     * @param int $briefId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param string $state can be either "live" or "approval"
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoArticleList 
     */
    private function ListArticlesForBrief($briefId, $offset, $limit, $state, $properties, $fields) {
        $uri = $this->GetUri($briefId, "briefId", "xml", $properties, $fields, $offset, $limit);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri . "&state=" . $state);
        $articles = $this->ListArticlesFromXmlString($xmlString);
        $articles->limit = $limit;
        $articles->offset = $offset;
        return $articles;
    }

    /**
     * Gets the response from the api in string format
     *
     * @param int $briefId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param string $state can be either "live" or "approval"
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @param array $format either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException 
     */
    private function ListArticlesForBriefRaw($briefId, $offset, $limit, $state, $properties, $fields, $format) {
        switch ($format) {
            case "xml":
                $uri = $this->GetUri($briefId, "briefId", "xml", $properties, $fields, $offset, $limit);
                break;
            case "json":
                $uri = $this->GetUri($briefId, "briefId", "json", $properties, $fields, $offset, $limit);
                break;
            default:
                throw new InvalidArgumentException($format . 'format not supported');
                break;
        }
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        return AdferoHelpers::GetRawResponse($uri . "&state=" . $state);
    }

    /**
     *  Lists the articles under a particular feed.
     *
     * @param int feedId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param string $state can be either "live" or "approval"
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoArticleList 
     */
    private function ListArticlesForFeed($feedId, $offset, $limit, $state, $properties, $fields) {
        $uri = $this->GetUri($feedId, "feedId", "xml", $properties, $fields, $offset, $limit);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri . "&state=" . $state);
        $articles = $this->ListArticlesFromXmlString($xmlString);
        $articles->limit = $limit;
        $articles->offset = $offset;
        return $articles;
    }

    /**
     * Gets the response from the api in string format
     * 
     * @param int $feedId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param string $state can be either "live" or "approval"
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return string
     * @throws InvalidArgumentException
     */
    private function ListArticlesForFeedRaw($feedId, $offset, $limit, $state, $properties, $fields, $format) {
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
        return AdferoHelpers::GetRawResponse($uri . "&state=" . $state);
    }

    /**
     * Gets the article with the provided id 
     *
     * @param int $id
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoArticleList 
     */
    private function GetArticle($id, $properties, $fields) {
        $uri = $this->GetUri($id, null, "xml", $properties, $fields, null, null);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        return $this->GetArticleFromXmlString($xmlString);
    }

    /**
     * Gets the response from the api in string format
     * 
     * @param int $id
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @param string $format either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException 
     */
    private function GetArticleRaw($id, $properties, $fields, $format) {
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
     * Gets the article from xml string
     *
     * @param string $xml
     * @return AdferoArticle 
     */
    private function GetArticleFromXmlString($xml) {
        $xml = new SimpleXMLElement($xml);
        $article = new AdferoArticle();
        $fields = array();
        foreach ($xml->article->children() as $child) {
            switch ($child->getName()) {
                case "id": $article->id = intval($child);
                    break;
                case "briefId":
                    $article->briefId = intval($child);
                    break;
                case "feedId":
                    $article->feedId = intval($child);
                    break;
                case "state":
                    $article->state = (string) $child;
                    break;
                case "fields":
                    foreach ($child->children() as $field) {
                        switch ((string) $field['name']) {
                            default :
                                $fields[(string) $field['name']] = (string) $field;
                                break;
                        }
                    };
            }
        }

        $article->setFields($fields);
        return $article;
    }

    /**
     * Gets article listing from xml string.
     *
     * @param string $xml
     * @return AdferoArticleList 
     */
    private function ListArticlesFromXmlString($xml) {
        $xml = new SimpleXMLElement($xml);
        $totalCount = intval($xml->articles['totalCount']);
        $articleItems = array();

        foreach ($xml->articles->article as $child) {
            foreach ($child->id as $articleId) {
                $article = new AdferoArticleListItem();
                $article->id = intval($articleId);
                array_push($articleItems, $article);
            }
        }

        $articles = new AdferoArticleList();
        $articles->items = $articleItems;
        $articles->totalCount = $totalCount;
        return $articles;
    }

    /**
     * Generates the URI from requested pararmeters.
     *
     * @param int $id id of the item to get or id of identifier for listing
     * @param string $identifier name of the identifier used for listing, use null for a get
     * @param string $format either "xml" or "json"
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @param int $offset offset to skip
     * @param int $limit either "xml" or "json"
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
            return $this->baseUri . "articles." . $format . "?" . $querystring;
        } else {
            $querystring = urldecode(http_build_query($data));
            return $this->baseUri . "articles" . "/" . $id . "." . $format . "?" .
                    $querystring;
        }
    }

}

?>
