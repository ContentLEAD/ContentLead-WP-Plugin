<?php

include_once dirname(__FILE__) . '/../AdferoListBase.php';

/**
 * Represents a list of categories
 *
 */
class AdferoCategoryList extends AdferoListBase {

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
