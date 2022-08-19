<?php
class Model_MenuModel  extends Zend_Db_Table_Abstract
{
    protected $_name = 'strony';
    protected $_primary = 'id';

    public function getByUri(string $uri)
    {
        return $this->fetchRow($this->select(array (
            'id',
            'uri',
            'nazwa',
            'tekst',
            'tag',
            'meta_slowa',
            'meta_opis',
            'meta_tytul',
            'plik'
        ))->where('uri = ?', $uri));
    }

    public function getById(int $id)
    {
        return $this->fetchRow($this->select(array (
            'id',
            'uri',
            'nazwa',
            'tekst',
            'tag',
            'meta_slowa',
            'meta_opis',
            'meta_tytul',
            'plik'
        ))->where('id = ?', $id));
    }
}