<?php

include_once dirname(__FILE__) . '/../../AdferoArticles/AdferoListBase.php';

/**
 * Represents a list of video outputs
 *
 */
class AdferoVideoOutputList extends AdferoListBase {
    
    /**
     * @var array
     */
    public $items = array();

    public function getItems() {
        return $this->items;
    }

    public function setItems($items) {
        $this->items = $items;
    }

}

?>
