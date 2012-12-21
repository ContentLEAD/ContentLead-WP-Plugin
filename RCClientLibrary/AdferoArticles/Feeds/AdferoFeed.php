<?php

include_once dirname(__FILE__) . '/../AdferoEntityBase.php';

/**
 * Represents a feed.
 *
 * 
 */
class AdferoFeed extends AdferoEntityBase {

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $state;

    /*
     * @var string
     */
    public $timeZone;

}

?>
