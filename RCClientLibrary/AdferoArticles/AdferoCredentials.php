<?php

/**
 * Stores network credentials to use on the api
 *
 * 
 */
class AdferoCredentials {

    /**
     * @var string
     */
    public $publicKey;

    /**
     * @var string
     */
    public $secretKey;

    /**
     * Initialises the Credentials class
     * @param string $publicKey 8 digit alpha numeric ID
     * @param string $secretKey 32 digit GUID
     */
    function __construct($publicKey, $secretKey) {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
    }

    public function getPublicKey() {
        return $this->publicKey;
    }

    public function setPublicKey($publicKey) {
        $this->publicKey = $publicKey;
    }

    public function getSecretKey() {
        return $this->secretKey;
    }

    public function setSecretKey($secretKey) {
        $this->secretKey = $secretKey;
    }

}

?>
