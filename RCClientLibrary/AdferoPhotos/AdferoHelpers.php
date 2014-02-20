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
        //load wp_http class   
    if( !class_exists( 'WP_Http' ) )
        include_once( ABSPATH . WPINC . '/class-http.php' );

    $request = new WP_Http; 
    $result = $request->request( $uri );

    
        return (string) $result['body'];
    }

}

?>
