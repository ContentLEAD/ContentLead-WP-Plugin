<?php

include_once dirname(__FILE__) . '/../AdferoHelpers.php';
include_once dirname(__FILE__) . '/AdferoFeed.php';
include_once dirname(__FILE__) . '/AdferoFeedListItem.php';
include_once dirname(__FILE__) . '/AdferoFeedList.php';

/**
 * Client that provides feed related functions.
 *
 * 
 */
class AdferoFeedsClient {

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var AdferoCredentials
     */
    private $credentials;

    /**
     * Initialises a new instance of the Feeds Client
     * 
     * @param string $baseUri Uri of the API provided by your account manager
     * @param AdferoCredentials $credentials Credentials object containing publicKey and secretKey.
     */
    function __construct($baseUri, $credentials) {
        $this->baseUri = $baseUri;
        $this->credentials = $credentials;
    }

    /**
     * Gets the feed with the provided id.
     *
     * @param int $id Id of the Feed to get
     * @return AdferoFeed 
     * @throws InvalidArgumentException 
     */
    public function Get($id) {
        if (!isset($id)) {
            throw new InvalidArgumentException("id is required");
        }

        return $this->GetFeed($id, null, null);
    }

    /**
     * Gets the raw output from the api as a string.
     * $format is either "xml" or "json"
     *
     * @param int $id Id of the Feed to get
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

        return $this->GetFeedRaw($id, null, null, $format);
    }

    /**
     * Lists the feeds under a particular feed.
     *
     * @param int $offset offset to apply to the list
     * @param int $limit The limit to apply to the list (maximum 100)
     * @return AdferoFeedList 
     * @throws InvalidArgumentException 
     */
    public function ListFeeds($offset, $limit) {
        if (!isset($offset)) {
            throw new InvalidArgumentException("offset is required");
        }

        if (!isset($limit)) {
            throw new InvalidArgumentException("limit is required");
        }

        return $this->ListFeedsForFeed($offset, $limit, null, null);
    }

    /**
     * Gets the raw output from the api as a string.
     * $format is either "xml" or "json"
     *
     * @param int $offset offset to apply to the list
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param string $format can be either "xml" or "json"
     * @return AdferoFeedList 
     * @throws InvalidArgumentException 
     */
    public function ListFeedsRaw($offset, $limit, $format) {
        if (!isset($offset)) {
            throw new InvalidArgumentException("offset is required");
        }

        if (!isset($limit)) {
            throw new InvalidArgumentException("limit is required");
        }

        if (!isset($format)) {
            throw new InvalidArgumentException("format is required");
        }

        return $this->ListFeedsForFeedRaw($offset, $limit, null, null, $format);
    }

    /**
     * Gets the feed with the provided id 
     *
     * @param int $id Id of the Feed to get
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoFeedList 
     */
    private function GetFeed($id, $properties, $fields) {
        $uri = $this->GetUri($id, null, "xml", $properties, $fields, null, null);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        return $this->GetFeedFromXmlString($xmlString);
    }

    /**
     * Gets the response from the api in string format
     * 
     * @param int $id Id of the Feed to get
     * @param array $properties array of fields to add to querystring
     * @param array $fields array of fields to add to querystring
     * @param string $format can be either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException 
     */
    private function GetFeedRaw($id, $properties, $fields, $format) {
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
     * @param int $offset offset to apply to the list
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @param string $format can be either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException
     */
    private function ListFeedsForFeedRaw($offset, $limit, $properties, $fields, $format) {
        switch ($format) {
            case "xml":
                $uri = $this->GetUri(null, null, "xml", $properties, $fields, $offset, $limit);
                break;
            case "json":
                $uri = $this->GetUri(null, null, "json", $properties, $fields, $offset, $limit);
                break;
            default:
                throw new InvalidArgumentException($format . 'format not supported');
                break;
        }
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        return AdferoHelpers::GetRawResponse($uri);
    }

    /**
     * Gets the feed from xml string
     *
     * @param string $xml
     * @return AdferoFeed
     */
    private function GetFeedFromXmlString($xml) {
        $xml = new SimpleXMLElement($xml);
        $feed = new AdferoFeed();
        foreach ($xml->feed->children() as $child) {
            switch ($child->getName()) {
                case "id": $feed->id = intval($child);
                    break;
                case "name":
                    $feed->name = (string) $child;
                    break;
                case "state":
                    $feed->state = (string) $child;
                    break;
                case "timeZone":
                    $feed->timeZone = (string) $child;
                    break;
            }
        }

        return $feed;
    }

    /**
     *  Lists the feeds.
     *
     * @param int $offset offset to apply to the list
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoFeedList 
     */
    private function ListFeedsForFeed($offset, $limit, $properties, $fields) {
        $uri = $this->GetUri(null, null, "xml", $properties, null, $offset, $limit);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        $feeds = $this->ListFeedsFromXmlString($xmlString);
        $feeds->limit = $limit;
        $feeds->offset = $offset;
        return $feeds;
    }

    /**
     * Gets feed listing from xml string.
     *
     * @param string $xml
     * @return AdferoFeedList 
     */
    private function ListFeedsFromXmlString($xml) {
        $xml = new SimpleXMLElement($xml);
        $totalCount = intval($xml->feeds['totalCount']);
        $feedItems = array();

        foreach ($xml->feeds->feed as $child) {
            foreach ($child->id as $feedId) {
                $feed = new AdferoFeedListItem();
                $feed->id = intval($feedId);
                array_push($feedItems, $feed);
            }
        }

        $feeds = new AdferoFeedList();
        $feeds->items = $feedItems;
        $feeds->totalCount = $totalCount;
        return $feeds;
    }

    /**
     * Generates the URI from requested pararmeters.
     * 
     * @param int $id id of the item to get or id of identifier for listing
     * @param string $identifier name of the identifier used for listing, use null for a get
     * @param string $format can be either "xml" or "json"
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @param int $offset offset to apply to the list
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

        if (($id == null && $identifier == null) || ($offset != null || $limit !=
                null)) {

            $data["offset"] = $offset;
            $data["limit"] = $limit;
            $querystring = urldecode(http_build_query($data));
            return $this->baseUri . "feeds." . $format . "?" . $querystring;
        } else {
            $querystring = urldecode(http_build_query($data));
            return $this->baseUri . "feeds" . "/" . $id . "." . $format . "?" .
                    $querystring;
        }
    }

}

?>
