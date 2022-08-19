<?php
class Model_BoxModel extends Zend_Db_Table_Abstract
{
    protected $_name = 'boksy';
    protected $_primary = 'id';

    const IMG_WIDTH = 80;
    const IMG_HEIGHT = 80;
}