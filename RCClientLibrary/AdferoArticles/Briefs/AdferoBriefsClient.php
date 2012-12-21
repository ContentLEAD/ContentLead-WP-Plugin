<?php

include_once dirname(__FILE__) . '/../AdferoHelpers.php';
include_once dirname(__FILE__) . '/AdferoBrief.php';
include_once dirname(__FILE__) . '/AdferoBriefListItem.php';
include_once dirname(__FILE__) . '/AdferoBriefList.php';

/**
 * Client that provides brief related functions.
 *
 */
class AdferoBriefsClient {

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var AdferoCredentials
     */
    private $credentials;

    /**
     * Initialises a new instance of the Briefs Client
     * @param string $baseUri Uri of the API provided by your account manager
     * @param AdferoCredentials $credentials Credentials object containing publicKey and secretKey.
     */
    function __construct($baseUri, $credentials) {
        $this->baseUri = $baseUri;
        $this->credentials = $credentials;
    }

    /**
     * Gets the brief with the provided id.
     *
     * @param int $id Id of the brief to get
     * @return AdferoBrief 
     * @throws InvalidArgumentException 
     */
    public function Get($id) {
        if (!isset($id)) {
            throw new InvalidArgumentException("id is required");
        }

        return $this->GetBrief($id, null, null);
    }

    /**
     * Gets the raw output from the api as a string.
     * $format is either "xml" or "json"
     *
     * @param int $id Id of the brief to get
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

        return $this->GetBriefRaw($id, null, null, $format);
    }

    /**
     * Lists the briefs under a particular feed.
     *
     * @param int $feedId The feed to list briefs for
     * @param int $offset The offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @return AdferoBriefList 
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

        return $this->ListBriefsForFeed($feedId, $offset, $limit, null, null);
    }

    /**
     * Gets the raw output from the api as a string.
     * $format is either "xml" or "json"
     *
     * @param int $feedId The feed to list briefs for
     * @param int $offset  The offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param string $format can be either "xml" or "json"
     * @return AdferoBriefList 
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

        return $this->ListBriefsForFeedRaw($feedId, $offset, $limit, null, null, $format);
    }

    /**
     * Gets the brief with the provided id 
     *
     * @param int $id Id of the brief to get
     * @param array $properties array of properties to add to querystring 
     * @param array $fields array of fields to add to querystring
     * @return AdferoBriefList 
     */
    private function GetBrief($id, $properties, $fields) {
        $uri = $this->GetUri($id, null, "xml", $properties, $fields, null, null);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        return $this->GetBriefFromXmlString($xmlString);
    }

    /**
     * Gets the response from the api in string format
     * 
     * @param int $id Id of the brief to get
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @param string $format either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException 
     */
    private function GetBriefRaw($id, $properties, $fields, $format) {
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
     * @param string $format either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException
     */
    private function ListBriefsForFeedRaw($feedId, $offset, $limit, $properties, $fields, $format) {
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
     * Gets the brief from xml string
     *
     * @param string $xml
     * @return AdferoBrief
     */
    private function GetBriefFromXmlString($xml) {
        $xml = new SimpleXMLElement($xml);
        $brief = new AdferoBrief();
        foreach ($xml->brief->children() as $child) {
            switch ($child->getName()) {
                case "id": $brief->id = intval($child);
                    break;
                case "name":
                    $brief->name = (string) $child;
                    break;
                case "feedId":
                    $brief->feedId = intval($child);
                    break;
            }
        }

        return $brief;
    }

    /**
     *  Lists the briefs under a particular feed.
     *
     * @param int feedId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @returnBriefList 
     */
    private function ListBriefsForFeed($feedId, $offset, $limit, $properties, $fields) {
        $uri = $this->GetUri($feedId, "feedId", "xml", $properties, $fields, $offset, $limit);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        $briefs = $this->ListBriefsFromXmlString($xmlString);
        $briefs->limit = $limit;
        $briefs->offset = $offset;
        return $briefs;
    }

    /**
     * Gets brief listing from xml string.
     *
     * @param string $xml
     * @return AdferoBriefList 
     */
    private function ListBriefsFromXmlString($xml) {
        $xml = new SimpleXMLElement($xml);
        $totalCount = intval($xml->briefs['totalCount']);
        $briefItems = array();

        foreach ($xml->briefs->brief as $child) {
            foreach ($child->id as $briefId) {
                $brief = new AdferoBriefListItem();
                $brief->id = intval($briefId);
                array_push($briefItems, $brief);
            }
        }

        $briefs = new AdferoBriefList();
        $briefs->items = $briefItems;
        $briefs->totalCount = $totalCount;
        return $briefs;
    }

    /**
     * Generates the URI from requested pararmeters.
     * @param int $id id of the item to get or id of identifier for listing
     * @param string $identifier name of the identifier used for listing, use null for a get
     * @param string $format either "xml" or "json"
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
            return $this->baseUri . "briefs." . $format . "?" . $querystring;
        } else {
            $querystring = urldecode(http_build_query($data));
            return $this->baseUri . "briefs" . "/" . $id . "." . $format . "?" .
                    $querystring;
        }
    }

}

?>
