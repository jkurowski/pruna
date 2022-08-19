<?php
class Model_GalleryModel  extends Zend_Db_Table_Abstract
{
    protected $_name = 'galeria';
    protected $_primary = 'id';

    const IMG_WIDTH = 640;
    const IMG_HEIGHT = 480;

    function getAll(){
        return $this->fetchAll($this->select()->order('sort ASC')->where('status =?', '1'));
    }

    function getBySlug($slug){
        return $this->fetchRow($this->select()->where('slug =?', $slug));
    }
}