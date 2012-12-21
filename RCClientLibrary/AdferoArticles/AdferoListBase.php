<?php

/**
 * Base propeties and methods for list entities
 * 
 */
abstract class AdferoListBase {

    /**
     * @var int
     */
    public $totalCount;

    /**
     * @var int
     */
    public $limit;

    /**
     * @var int
     */
    public $offset;

    public function getTotalCount() {
        return $this->totalCount;
    }

    public function setTotalCount($totalCount) {
        $this->totalCount = $totalCount;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function getOffset() {
        return $this->offset;
    }

    public function setOffset($offset) {
        $this->offset = $offset;
    }

}

?>
