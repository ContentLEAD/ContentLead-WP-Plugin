<?php

/**
 * Description of Version
 *
 */
class AdferoVersion {

    /**
     *
     * @var int 
     */
    public $major;

    /**
     *
     * @var int 
     */
    public $minor;

    /**
     *
     * @var int 
     */
    public $build;

    function __construct($major, $minor, $build) {
        $this->major = $major;
        $this->minor = $minor;
        $this->build = $build;
    }

    function __toString() {
        return $this->major . "." . $this->minor . "." . $this->build;
    }

}

?>
