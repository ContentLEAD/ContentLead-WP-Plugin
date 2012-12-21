<?php

include_once dirname(__FILE__) . '/../AdferoHelpers.php';
include_once dirname(__FILE__) . '/AdferoArticlePhoto.php';
include_once dirname(__FILE__) . '/AdferoArticlePhotoListItem.php';
include_once dirname(__FILE__) . '/AdferoArticlePhotoList.php';

/**
 * Client that provides article photo related functions
 *
 */
class AdferoArticlePhotosClient {

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var AdferoCredentials
     */
    private $credentials;

    /**
     * Initialises a new instance of the ArticlePhotos Client
     * @param string $baseUri Uri of the API provided by your account manager
     * @param AdferoCredentials $credentials Credentials object containing publicKey and secretKey.
     */
    function __construct($baseUri, $credentials) {
        $this->baseUri = $baseUri;
        $this->credentials = $credentials;
    }

    /**
     * Gets the articlePhoto with the provided articlePhoto Id.
     *
     * @param int $id Id of the articlePhoto to get
     * @return AdferoArticlePhoto 
     * @throws InvalidArgumentException 
     */
    public function Get($id) {
        if (!isset($id)) {
            throw new InvalidArgumentException("id is required");
        }

        return $this->GetArticlePhoto($id, null, null);
    }

    /**
     * Returns the raw response from the api for the articlePhoto with the provided article photo Id. 
     *
     * @param int $id Id of the articlePhoto to get
     * @param string $format either "xml" or "json"
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

        return $this->GetArticlePhotoRaw($id, null, null, $format);
    }

    /**
     * Lists the articlePhotos on a particular article.
     *
     * @param int $articleId The article to list articlePhotos for
     * @param int $offset The offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @return AdferoArticlePhotoList 
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

        return $this->ListArticlePhotos($articleId, $offset, $limit, null, null);
    }

    /**
     * Returns the raw response from the api for the articlePhotos on a particular article.
     *
     * @param int $articleId The article to list articlePhotos for
     * @param int $offset The offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param string $format can be either "xml" or "json"
     * @return AdferoArticlePhotoList 
     * @throws InvalidArgumentException 
     */
    public function ListForArticlePhotosRaw($articleId, $offset, $limit, $format) {
        if (!isset($articleId)) {
            throw new InvalidArgumentException("articleId is required");
        }

        if (!isset($offset)) {
            throw new InvalidArgumentException("offset is required");
        }

        if (!isset($limit)) {
            throw new InvalidArgumentException("limit is required");
        }

        if (!isset($limit)) {
            throw new InvalidArgumentException("limit is required");
        }

        if (!isset($format)) {
            throw new InvalidArgumentException("format is required");
        }

        return $this->ListArticlesPhotosRaw($articleId, $offset, $limit, null, null, $format);
    }

    /**
     * Lists the article photos under a particular article.
     * 
     * @param int $articleId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoArticlePhotoList 
     */
    private function ListArticlePhotos($articleId, $offset, $limit, $properties, $fields) {
        $uri = $this->GetUri($articleId, "articleId", "xml", $properties, $fields, $offset, $limit);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        $articlePhotos = $this->ListArticlePhotosFromXmlString($xmlString);
        $articlePhotos->limit = $limit;
        $articlePhotos->offset = $offset;
        return $articlePhotos;
    }

    /**
     * Gets the response from the api in string format
     *
     * @param int articleId
     * @param int $offset offset to skip
     * @param int $limit The limit to apply to the list (maximum 100)
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @param array $format either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException 
     */
    private function ListArticlesPhotosRaw($articleId, $offset, $limit, $properties, $fields, $format) {
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
     * Gets the article photo with the provided id 
     *
     * @param int $id
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoArticlePhotoList 
     */
    private function GetArticlePhoto($id, $properties, $fields) {
        $uri = $this->GetUri($id, null, "xml", $properties, $fields, null, null);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        return $this->GetArticlePhotoFromXmlString($xmlString);
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
    private function GetArticlePhotoRaw($id, $properties, $fields, $format) {
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
     * Gets the article photo from xml string
     *
     * @param string $xml
     * @return AdferoArticlePhoto
     */
    private function GetArticlePhotoFromXmlString($xml) {
        $xml = new SimpleXMLElement($xml);
        $articlePhoto = new AdferoArticlePhoto();
        $fields = array();
        foreach ($xml->articlePhoto->children() as $child) {
            switch ($child->getName()) {
                case "id": $articlePhoto->id = intval($child);
                    break;
                case "sourcePhotoId":
                    $articlePhoto->sourcePhotoId = intval($child);
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

        $articlePhoto->setFields($fields);
        return $articlePhoto;
    }

    /**
     * Gets article listing from xml string.
     *
     * @param string $xml
     * @return ArticleList 
     */
    private function ListArticlePhotosFromXmlString($xml) {
        $xml = new SimpleXMLElement($xml);
        $totalCount = intval($xml->articlePhotos['totalCount']);
        $articlePhotoItems = array();

        foreach ($xml->articlePhotos->articlePhoto as $child) {
            foreach ($child->id as $articleId) {
                $article = new AdferoArticlePhotoListItem();
                $article->id = intval($articleId);
                array_push($articlePhotoItems, $article);
            }
        }

        $articlesPhotos = new AdferoArticlePhotoList();
        $articlesPhotos->items = $articlePhotoItems;
        $articlesPhotos->totalCount = $totalCount;
        return $articlesPhotos;
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

        if (($identifier != null || $identifier != "") || ($offset != null || $limit != null)) {
            $data["offset"] = $offset;
            $data["limit"] = $limit;
            $data = array_merge(array($identifier => $id), $data);
            $querystring = urldecode(http_build_query($data));
            return $this->baseUri . "articlePhotos." . $format . "?" . $querystring;
        } else {
            $querystring = urldecode(http_build_query($data));
            return $this->baseUri . "articlePhotos" . "/" . $id . "." . $format . "?" . $querystring;
        }
    }

}

?>
