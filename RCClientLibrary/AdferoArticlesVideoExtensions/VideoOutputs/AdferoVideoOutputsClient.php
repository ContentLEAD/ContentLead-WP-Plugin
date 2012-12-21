<?php

require_once dirname(__FILE__) . '/../../AdferoArticles/AdferoHelpers.php';
require_once dirname(__FILE__) . '/AdferoVideoOutput.php';
require_once dirname(__FILE__) . '/AdferoVideoOutputListItem.php';
require_once dirname(__FILE__) . '/AdferoVideoOutputList.php';

/**
 * Client that provides video output related functions.
 *
 */
class AdferoVideoOutputsClient {

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var Credentials
     */
    private $credentials;

    /**
     * Initialises a new instance of the Video Outputs Client
     * @param string $apiRoot Uri of the API provided by your account manager
     * @param AdferoCredentials $credentials Credentials object containing publicKey and secretKey.
     */
    function __construct($baseUri, $credentials) {
        $this->baseUri = $baseUri;
        $this->credentials = $credentials;
    }

    /**
     * Gets the video output with the provided id.
     *
     * @param int $id Id of the video output to get
     * @return AdferoVideoOutput 
     * @throws InvalidArgumentException 
     */
    public function Get($id) {
        if (!isset($id)) {
            throw new InvalidArgumentException("id is required");
        }

        return $this->GetVideoOutput($id, null, null);
    }

    /**
     * Gets the raw output from the api as a string.
     *
     * @param int $id Id of the video output to get
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

        return $this->GetVideoOutputRaw($id, null, null, $format);
    }

    /**
     * Lists the video outputs under a particular article.
     *
     * @param int $articleId 
     * @param int $offset offset to skip
     * @param int $limit limit to apply to the list (maximum 100)
     * @return AdferoVideoOutputList 
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
        
        return $this->ListVideoOutputsForArticle($articleId, $offset, $limit, null, null);
    }

    /**
     * Gets the raw output from the api as a string.
     * $format is either "xml" or "json"
     *
     * @param int $articleId
     * @param int $offset offset to skip offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param string $format can be either "xml" or "json"
     * @return AdferoVideoOutputList 
     * @throws InvalidArgumentException 
     */
    public function ListForArticleRaw($articleId, $offset, $limit, $format) {
        if (!isset($articleId)) {
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
        
        return $this->ListVideoOutputsForArticleRaw($articleId, $offset, $limit, null, null, $format);
    }

    /**
     * Gets the video output with the provided id 
     *
     * @param int $id Id of the video output to get
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoVideoOutput
     */
    private function GetVideoOutput($id, $properties, $fields) {
        $uri = $this->GetUri($id, null, "xml", $properties, $fields, null, null);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        return $this->GetVideoOutputFromXmlString($xmlString);
    }

    /**
     * Gets the response from the api in string format
     * 
     * @param int $id Id of the video output to get
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @param string $format can be either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException 
     */
    private function GetVideoOutputRaw($id, $properties, $fields, $format) {
        switch ($format) {
            case "xml":
                $uri = $this->GetUri($id, null, "xml", $properties, $fields, null, null);
                break;
            case "json":
                $uri = $this->GetUri($id, null, "json", $properties, $fields, null, null);
                break;
            default:
                throw new \InvalidArgumentException($format . 'format not supported');
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
     * @param string $format can be either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException
     */
    private function ListVideoOutputsForArticleRaw($articleId, $offset, $limit, $properties, $fields, $format) {
        switch ($format) {
            case "xml":
                $uri = $this->GetUri($articleId, "articleId", "xml", $properties, $fields, $offset, $limit);
                break;
            case "json":
                $uri = $this->GetUri($articleId, "articleId", "json", $properties, $fields, $offset, $limit);
                break;
            default:
                throw new \InvalidArgumentException($format . 'format not supported');
                break;
        }

        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        return AdferoHelpers::GetRawResponse($uri);
    }

    /**
     * Gets the video output from xml string
     *
     * @param string $xml
     * @return AdferoVideoOutput
     */
    private function GetVideoOutputFromXmlString($xml) {
        $xml = new \SimpleXMLElement($xml);
        $videoOutput = new AdferoVideoOutput();
        foreach ($xml->videoOutput->children() as $child) {
            switch ($child->getName()) {
                case "id":
                    $videoOutput->id = intval($child);
                    break;
                case "type":
                    $videoOutput->type = (string) $child;
                    break;
                case "width":
                    $videoOutput->width = intval($child);
                    break;
                case "height":
                    $videoOutput->height = intval($child);
                    break;
                case "path":
                    $videoOutput->path = (string) $child;
                    break;
            }
        }

        return $videoOutput;
    }

    /**
     *  Lists the video outputs under a particular article.
     *
     * @param int articleId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param array $properties array of properties to add to querystring
     * @param array $array of fields to add to querystring
     * @return AdferoVideoOutputList 
     */
    private function ListVideoOutputsForArticle($articleId, $offset, $limit, $properties, $fields) {
        $uri = $this->GetUri($articleId, "articleId", "xml", $properties, $fields, $offset, $limit);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        $videoOutputs = $this->ListVideoOutputsFromXmlString($xmlString);
        $videoOutputs->limit = $limit;
        $videoOutputs->offset = $offset;
        return $videoOutputs;
    }

    /**
     * Gets video output listing from xml string.
     *
     * @param string $xml
     * @return AdferoVideoOutputList 
     */
    private function ListVideoOutputsFromXmlString($xml) {
        $xml = new \SimpleXMLElement($xml);
        $totalCount = intval($xml->videoOutputs['totalCount']);
        $videoOutputItems = array();

        foreach ($xml->videoOutputs->videoOutput as $child) {
            foreach ($child->id as $videoOutputId) {
                $videoOutput = new AdferoVideoOutputListItem();
                $videoOutput->id = intval($videoOutputId);
                array_push($videoOutputItems, $videoOutput);
            }
        }

        $videoOutputs = new AdferoVideoOutputList();
        $videoOutputs->items = $videoOutputItems;
        $videoOutputs->totalCount = $totalCount;
        return $videoOutputs;
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
            return $this->baseUri . "videoOutputs." . $format . "?" . $querystring;
        } else {
            $querystring = urldecode(http_build_query($data));
            return $this->baseUri . "videoOutputs" . "/" . $id . "." . $format . "?" .
                    $querystring;
        }
    }

}

?>
