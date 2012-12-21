<?php

include_once dirname(__FILE__) . '/../AdferoEntityBase.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticlePhoto
 *
 */
class AdferoArticlePhoto extends AdferoEntityBase {

    /**
     *
     * @var int 
     */
    public $sourcePhotoId;

    /**
     * @var array
     */
    public $fields = array();

    public function getFields() {
        return $this->fields;
    }

    public function setFields($fields) {
        $this->fields = $fields;
    }

}

?>
