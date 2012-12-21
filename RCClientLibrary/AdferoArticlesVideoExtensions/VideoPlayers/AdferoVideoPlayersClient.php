<?php

require_once dirname(__FILE__) . '/../../AdferoArticles/AdferoHelpers.php';
include_once dirname(__FILE__) . '/AdferoVideoPlayer.php';
include_once dirname(__FILE__) . '/AdferoVersion.php';
include_once dirname(__FILE__) . '/AdferoPlayers.php';

/**
 * Client that provides video player related functions.
 *
 */
class AdferoVideoPlayersClient {

    /**
     * @var string
     */
    private $baseUri;

    /**
     * @var Credentials
     */
    private $credentials;

    /**
     * Initialises a new instance of the VideoPlayers Client
     * @param string $baseUri Uri of the API provided by your account manager
     * @param AdferoCredentials $credentials Credentials object containing publicKey and secretKey.
     */
    function __construct($baseUri, $credentials) {
        $this->baseUri = $baseUri;
        $this->credentials = $credentials;
    }

    /**
     * Gets the video player with the provided article Id.
     *
     * @param int $articleId Id of the article from which to get the video player
     * @param Players $playerName Name of the player to display embed code for
     * @param Version $playerVersion Version of the player to display embed code for
     * @return AdferoVideoPlayer 
     * @throws InvalidArgumentException 
     */
    public function Get($articleId, $playerName, $playerVersion) {
        if (!isset($articleId)) {
            throw new InvalidArgumentException("id is required");
        }

        if (!isset($playerName)) {
            throw new InvalidArgumentException("playerName is required");
        }

        if (!isset($playerVersion)) {
            throw new InvalidArgumentException("playerVersion is required");
        }

        return $this->GetVideoPlayer($articleId, $playerName, $playerVersion, null, null);
    }

    /**
     * Gets the video player with the provided article id.
     *
     * @param int $articleId Id of the article from which to get the video player
     * @param Players $playerName Name of the player to display embed code for
     * @param Version $playerVersion Version of the player to display embed code for
     * @param Players $fallbackPlayerName Name of the fallback player to display embed code for if the primary player isn't supported by the browser
     * @param Version $fallbackPlayerVersion Version of the fallback player to display embed code for if the primary player isn't supported by the browser
     * @return AdferoVideoPlayer 
     * @throws InvalidArgumentException 
     */
    public function GetWithFallback($articleId, $playerName, $playerVersion, $fallbackPlayerName, $fallbackPlayerVersion) {
        if (!isset($articleId)) {
            throw new InvalidArgumentException("articleId is required");
        }

        if (!isset($playerName)) {
            throw new InvalidArgumentException("playerName is required");
        }

        if (!isset($playerVersion)) {
            throw new InvalidArgumentException("playerVersion is required");
        }

        if (!isset($fallbackPlayerName)) {
            throw new InvalidArgumentException("fallbackPlayerName is required");
        }

        if (!isset($fallbackPlayerVersion)) {
            throw new InvalidArgumentException("fallbackPlayerVersion is required");
        }

        return $this->GetVideoPlayerWithFallback($articleId, $playerName, $playerVersion, $fallbackPlayerName, $fallbackPlayerVersion, null, null);
    }

    /**
     * Gets the raw output from the api as a string.
     * $format is either "xml" or "json"
     *
     * @param int $articleId Id of the article from which to get the video player
     * @param Players $playerName Name of the player to display embed code for
     * @param Version $playerVersion Version of the player to display embed code for
     * @param string $format can be either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException 
     */
    public function GetRaw($articleId, $playerName, $playerVersion, $format) {
        if (!isset($articleId)) {
            throw new InvalidArgumentException("articleId is required");
        }

        if (!isset($playerName)) {
            throw new InvalidArgumentException("playerName is required");
        }

        if (!isset($playerVersion)) {
            throw new InvalidArgumentException("playerVersion is required");
        }

        if (!isset($format)) {
            throw new InvalidArgumentException("format is required");
        }

        return $this->GetVideoPlayerRaw($articleId, $playerName, $playerVersion, null, null, null, null, $format);
    }

    /**
     * Gets the raw output from the api as a string.
     *
     * @param int $articleId Id of the article from which to get the video player
     * @param Players $playerName Name of the player to display embed code for
     * @param Version $playerVersion Version of the player to display embed code for
     * @param Players $fallbackPlayerName Name of the fallback player to display embed code for if the primary player isn't supported by the browser
     * @param Version $fallbackPlayerVersion Version of the fallback player to display embed code for if the primary player isn't supported by the browser
     * @param string $format can be either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException 
     */
    public function GetRawWithFallback($articleId, $playerName, $playerVersion, $fallbackPlayerName, $fallbackPlayerVersion, $format) {
        if (!isset($articleId)) {
            throw new InvalidArgumentException("articleId is required");
        }

        if (!isset($playerName)) {
            throw new InvalidArgumentException("playerName is required");
        }

        if (!isset($playerVersion)) {
            throw new InvalidArgumentException("playerVersion is required");
        }

        if (!isset($fallbackPlayerName)) {
            throw new InvalidArgumentException("fallbackPlayerName is required");
        }

        if (!isset($fallbackPlayerVersion)) {
            throw new InvalidArgumentException("fallbackPlayerVersion is required");
        }

        if (!isset($format)) {
            throw new InvalidArgumentException("format is required");
        }

        return $this->GetVideoPlayerRaw($articleId, $playerName, $playerVersion, $fallbackPlayerName, $fallbackPlayerVersion, null, null, $format);
    }

    /**
     * Gets the video player with the provided id 
     *
     * @param int $articleId Id of the article from which to get the video player
     * @param Players $playerName Name of the player to display embed code for
     * @param Version $playerVersion Version of the player to display embed code for
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoVideoPlayer
     */
    private function GetVideoPlayer($articleId, $playerName, $playerVersion, $properties, $fields) {
        $uri = $this->GetUri($articleId, $playerName, $playerVersion, null, null, "xml", $properties, $fields);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        return $this->GetVideoPlayerFromXmlString($xmlString);
    }

    /**
     * Gets the video player with the provided id 
     *
     * @param int $articleId Id of the article from which to get the video player
     * @param Players $playerName Name of the player to display embed code for
     * @param Version $playerVersion Version of the player to display embed code for
     * @param Players $fallbackPlayerName Name of the fallback player to display embed code for if the primary player isn't supported by the browser
     * @param Version $fallbackPlayerVersion Version of the fallback player to display embed code for if the primary player isn't supported by the browser
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return AdferoVideoPlayer
     */
    private function GetVideoPlayerWithFallback($articleId, $playerName, $playerVersion, $fallbackPlayerName, $fallbackPlayerVersion, $properties, $fields) {
        $uri = $this->GetUri($articleId, $playerName, $playerVersion, $fallbackPlayerName, $fallbackPlayerVersion, "xml", $properties, $fields);
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        $xmlString = AdferoHelpers::GetXMLFromUri($uri);
        return $this->GetVideoPlayerFromXmlString($xmlString);
    }

    /**
     * Gets the response from the api in string format
     * 
     * @param int $articleId Id of the article from which to get the video player
     * @param Players $playerName Name of the player to display embed code for
     * @param Version $playerVersion Version of the player to display embed code for
     * @param Players $fallbackPlayerName Name of the fallback player to display embed code for if the primary player isn't supported by the browser
     * @param Version $fallbackPlayerVersion Version of the fallback player to display embed code for if the primary player isn't supported by the browser
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @param string $format can be either "xml" or "json"
     * @return string
     * @throws InvalidArgumentException 
     */
    private function GetVideoPlayerRaw($articleId, $playerName, $playerVersion, $fallbackPlayerName, $fallbackPlayerVersion, $properties, $fields, $format) {
        switch ($format) {
            case "xml":
                $uri = $this->GetUri($articleId, $playerName, $playerVersion, $fallbackPlayerName, $fallbackPlayerVersion, "xml", $properties, $fields);
                break;
            case "json":
                $uri = $this->GetUri($articleId, $playerName, $playerVersion, $fallbackPlayerName, $fallbackPlayerVersion, "json", $properties, $fields);
                break;
            default:
                throw new \InvalidArgumentException($format . 'format not supported');
                break;
        }
        $uri = "http://" . $this->credentials->getPublicKey() . ":" . $this->credentials->getSecretKey() . "@" . str_replace("http://", "", $uri);
        return AdferoHelpers::GetRawResponse($uri);
    }

    /**
     * Gets the video player from xml string
     *
     * @param string $xml
     * @return VideoPlayers\VideoPlayer
     */
    private function GetVideoPlayerFromXmlString($xml) {
        $xml = new \SimpleXMLElement($xml);
        $videoPlayer = new AdferoVideoPlayer();
        foreach ($xml->player->children() as $child) {
            switch ($child->getName()) {
                case "embedCode":
                    $videoPlayer->embedCode = (string) $child;
                    break;
            }
        }

        return $videoPlayer;
    }

    /**
     * Generates the URI from requested pararmeters.
     *
     * @param int $articleId Id of the article from which to get the video player
     * @param Players $playerName Name of the player to display embed code for
     * @param Version $playerVersion Version of the player to display embed code for
     * @param Players $fallbackPlayerName Name of the fallback player to display embed code for if the primary player isn't supported by the browser
     * @param Version $fallbackPlayerVersion Version of the fallback player to display embed code for if the primary player isn't supported by the browser
     * @param string $format can be either "xml" or "json"
     * @param array $properties array of properties to add to querystring
     * @param array $fields array of fields to add to querystring
     * @return string 
     */
    private function GetUri($articleId, $playerName, $playerVersion, $fallbackPlayerName, $fallbackPlayerVersion, $format, $properties, $fields) {
        $data = array();

        if ($properties != null) {
            $properties = implode(",", $properties);
            $data["properties"] = $properties;
        }

        if ($fields != null) {
            $fields = implode(",", $fields);
            $data["fields"] = $fields;
        }

        $data["articleId"] = $articleId;
        $data["playerVersion"] = (string) $playerVersion;

        if (isset($fallbackPlayerName) || !is_null($fallbackPlayerName)) {
            $data["fallbackPlayerName"] = $fallbackPlayerName;
        }

        if (isset($fallbackPlayerVersion) || !is_null($fallbackPlayerVersion)) {
            $data["fallbackPlayerVersion"] = (string) $fallbackPlayerVersion;
        }

        $querystring = urldecode(http_build_query($data));
        return $this->baseUri . "players" . "/" . $playerName . "." . $format . "?" .
                $querystring;
    }

}

?>
