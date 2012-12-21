<?php

include_once dirname(__FILE__) . '/AdferoCredentials.php';
include_once dirname(__FILE__) . '/Articles/AdferoArticlesClient.php';
include_once dirname(__FILE__) . '/ArticlePhotos/AdferoArticlePhotosClient.php';
include_once dirname(__FILE__) . '/Briefs/AdferoBriefsClient.php';
include_once dirname(__FILE__) . '/Categories/AdferoCategoriesClient.php';
include_once dirname(__FILE__) . '/Feeds/AdferoFeedsClient.php';

/**
 * Provides functions for accessing the DirectNews v2 XML API. Instantiate the
 * client with the domain, username and password provided by your account manager then 
 * you can access your data through this context.
 *
 */
class AdferoClient {

    protected $baseUri, $credentials;

    /**
     * Initialises a new instance of the Client class
     * @param string $baseUri Uri of the API provided by your account manager
     * @param string $publicKey 8 digit alpha numeric ID provided by your account manager
     * @param string $secretKey 32 digit password provided by your account manager
     * @throws \InvalidArgumentException 
     */
    function __construct($baseUri, $publicKey, $secretKey) {
        if (!preg_match('|^http://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $baseUri)) {
            throw new InvalidArgumentException('Not a valid uri');
        };
        if (!preg_match('/\/$/', $baseUri)) {
            $baseUri = $baseUri . '/';
        }
        $this->baseUri = $baseUri;
        $this->credentials = new AdferoCredentials($publicKey, $secretKey);
    }

    /**
     * Gets a new instance of the Articles client.
     * @return AdferoArticlesClient 
     */
    public function Articles() {
        return new AdferoArticlesClient($this->baseUri, $this->credentials);
    }

    /**
     * Gets a new instance of the ArticlePhotos client.
     * @return AdferoArticlePhotosClient 
     */
    public function ArticlePhotos() {
        return new AdferoArticlePhotosClient($this->baseUri, $this->credentials);
    }

    /**
     * Gets a new instance of the Briefs client.
     * @return AdferoBriefsClient 
     */
    public function Briefs() {
        return new AdferoBriefsClient($this->baseUri, $this->credentials);
    }

    /**
     * Gets a new instance of the Categories client.
     * @return AdferoCategoriesClient 
     */
    public function Categories() {
        return new AdferoCategoriesClient($this->baseUri, $this->credentials);
    }

    /**
     * Gets a new instance of the Feeds client.
     * @return AdferoFeedsClient 
     */
    public function Feeds() {
        return new AdferoFeedsClient($this->baseUri, $this->credentials);
    }

}

