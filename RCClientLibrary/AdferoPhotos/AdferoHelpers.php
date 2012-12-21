<?php

/**
 * Description of Helpers
 *
 */
class AdferoHelpers {

    /**
     * Returns the xml as a string from the provided uri using SimpleXML
     * @param string $uri 
     * @return string 
     */
    public function GetXMLFromUri($uri) {
        $xml = simplexml_load_file($uri);
        return $xml->asXML();
    }

    /**
     * Gets the raw response from the API for the provided uri as a string
     * @param string $uri 
     * @return string 
     */
    public function GetRawResponse($uri) {
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return (string) curl_exec($ch);
    }

}

?>
