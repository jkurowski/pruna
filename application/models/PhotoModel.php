<?php
class Model_PhotoModel  extends Zend_Db_Table_Abstract
{
    protected $_name = 'galeria_zdjecia';
    protected $_primary = 'id';

    function getByCategory(int $id) {
        return $this->fetchAll($this->select()->order('sort ASC')->where('id_gal =?', $id));
    }
}