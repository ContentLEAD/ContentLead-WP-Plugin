<?php

include_once dirname(__FILE__) . '/Photos/AdferoPhoto.php';
include_once dirname(__FILE__) . '/Photos/AdferoPhotosClient.php';
include_once dirname(__FILE__) . '/Photos/AdferoScaleAxis.php';

/**
 * Provides functions for accessing the Photo service API. Instantiate this
 * client with the domain, provided by your account manager.
 *
 */
class AdferoPhotoClient {

    protected $baseUri;

    /**
     * Initialises a new instance of the Client class
     * @param string $baseUri Uri of the API provided by your account manager
     * @throws \InvalidArgumentException 
     */
    function __construct($baseUri) {
        if (!preg_match('|^http://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $baseUri)) {
            throw new \InvalidArgumentException('Not a valid uri');
        };
        if (!preg_match('/\/$/', $baseUri)) {
            $baseUri = $baseUri . '/';
        }
        $this->baseUri = $baseUri;
    }

    /**
     * Gets a new instance of the AdferoPhotos client.
     * @return AdferoPhotossClient 
     */
    public function Photos() {
        return new AdferoPhotosClient($this->baseUri);
    }
}

?>
