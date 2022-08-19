<?php
require_once 'kCMS/Thumbs/ThumbLib.inc.php';
class Model_PlanModel  extends Zend_Db_Table_Abstract
{
    protected $_name = 'inwestycje_plan';
    protected $_primary = 'id';
    protected $_db_table;

    const IMG_WIDTH = 1500;
    const IMG_HEIGHT = 933;

    public function init()
    {
        try {
            $this->_db_table = Zend_Registry::get('db');
            $this->_db_table->setFetchMode(Zend_Db::FETCH_OBJ);
        } catch (Zend_Exception $e) {
        }
    }

    /**
     * Pokaz wybrana inwestycje po id
     * @param int $id
     * @return Object
     */
    public function get(int $id)
    {
        $investmentsQuery = $this->_db_table->select()
            ->from($this->_name)
            ->where('id_inwest = ?', $id);
        return $this->_db_table->fetchRow($investmentsQuery);
    }
}