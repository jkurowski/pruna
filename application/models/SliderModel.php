<?php
class Model_SliderModel  extends Zend_Db_Table_Abstract
{
    protected $_name = 'slider';
    protected $_primary = 'id';

    const IMG_WIDTH = 1920;
    const IMG_HEIGHT = 750;

    public function getAll()
    {
        return $this->fetchAll(
            $this->select()->order('sort ASC')
        );
    }
}