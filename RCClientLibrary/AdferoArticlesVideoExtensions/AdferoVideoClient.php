<?php

include_once dirname(__FILE__) . '/../AdferoArticles/AdferoCredentials.php';
include_once dirname(__FILE__) . '/../AdferoArticles/AdferoClient.php';
include_once dirname(__FILE__) . '/VideoOutputs/AdferoVideoOutputsClient.php';
include_once dirname(__FILE__) . '/VideoPlayers/AdferoVideoPlayersClient.php';


/**
 * Provides functions for accessing the ReelContent v2 XML API. Instantiate the
 * client with the domain, username and password provided by your account manager then 
 * you can access your data through this context.
 *
 */
class AdferoVideoClient extends AdferoClient{

    /**
     * Initialises a new instance of the Client class
     * @param string $baseUri Uri of the API provided by your account manager
     * @param string $publicKey 8 digit alpha numeric ID provided by your account manager
     * @param string $secretKey 32 digit password provided by your account manager
     * @throws \InvalidArgumentException 
     */
    function __construct($baseUri, $publicKey, $secretKey) {
        if (!preg_match('|^http://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $baseUri)) {
            throw new \InvalidArgumentException('Not a valid uri');
        };
        if (!preg_match('/\/$/', $baseUri)) {
            $baseUri = $baseUri . '/';
        }
        $this->baseUri = $baseUri;
        $this->credentials = new AdferoCredentials($publicKey, $secretKey);
    }

    /**
     * Gets a new instance of the VideoOutputs client.
     * @return AdferoVideoOutputsClient 
     */
    public function VideoOutputs() {
        return new AdferoVideoOutputsClient($this->baseUri, $this->credentials);
    }

    /**
     * Gets a new instance of the VideoPlayers client.
     * @return AdferoVideoPlayersClient 
     */
    public function VideoPlayers() {
        return new AdferoVideoPlayersClient($this->baseUri, $this->credentials);
    }
    
}