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
    public static function GetXMLFromUri($uri) {
        $ch = curl_init();
       $timeout = 5;
       curl_setopt($ch, CURLOPT_URL, $uri);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
       $data = curl_exec($ch);
       curl_close($ch);
       return $data;
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
