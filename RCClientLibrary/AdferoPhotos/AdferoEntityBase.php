<?php

/**
 * Entity base class
 *
 * 
 */
abstract class AdferoEntityBase {

    /**
     * @var int
     */
    public $id;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

}

?>
