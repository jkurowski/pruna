<?php
class Model_BuildingModel  extends Zend_Db_Table_Abstract
{
    public $_name = 'inwestycje_budynki';
    public $_module = 'investbuilding';
    protected $_primary = 'id';
    protected $_db_table;
    protected $_locale;
    private $canbetranslate;

    const IMG_WIDTH = 1170;

    public function init()
    {
        try {
            $this->_db_table = Zend_Registry::get('db');
            $this->_db_table->setFetchMode(Zend_Db::FETCH_OBJ);
        } catch (Zend_Exception $e) {
        }
        try {
            $this->canbetranslate = Zend_Registry::get('canbetranslate');
            if($this->canbetranslate) {
                $this->_locale = Zend_Registry::get('Zend_Locale')->getLanguage();
            } else {
                $this->_locale = 'pl';
            }
        } catch (Zend_Exception $e) {
        }
    }

    /**
     * Model - Pokaz przetlumaczony wpis
     * @param int $id
     * @return Object
     */
    public function getTranslate(int $id)
    {
        $translateQuery = $this->_db_table->select()
            ->from('tlumaczenie_wpisy')
            ->where('module = ?', $this->_module)
            ->where('id_wpis = ?', $id)
            ->where('lang = ?', $this->_locale);
        return $this->_db_table->fetchRow($translateQuery);
    }

    /**
     * Pokaz przetlumaczone budynki
     * @param int $id
     * @return Object
     */
    public function getAllTranslated(int $building = null, int $investment = null)
    {
        $translatedQuery = $this->_db_table->select()
            ->from(array('t' => 'tlumaczenie_wpisy'))
            ->join(array('tl' => $this->_name), 't.id_wpis = tl.id', array(
                'numer',
                'id',
                'html',
                'cords'
            ))
            ->where('module = ?', $this->_module)
            ->where('lang = ?', $this->_locale);
        if($investment){
            $translatedQuery->where('id_inwest = ?', $investment);
        }
        return $this->_db_table->fetchAll($translatedQuery);
    }

    /**
     * Front - Pokaz wszystkie budynki dla inwestycji
     * @param int $id
     * @return Object
     */
    public function getBuildings(int $id)
    {
        if($this->_locale == 'pl') {
            $budynki = $this->fetchAll(
                $this->select()
                    ->where('id_inwest = ?', $id)
            );
        } else {
            $budynki = $this->getAllTranslated(null, $id);
        }
        return $budynki;
    }

    /**
     * Front - Pokaz budynek dla inwestycji
     * @param int $id
     * @return Object
     */
    public function getBuilding(int $id, string $numer)
    {
        $budynek = $this->fetchRow(
            $this->select()
                ->where('id_inwest =?', $id)
                ->where('id =?', $numer)
        );
        return $budynek;
    }

    public function getNextBuilding(int $id, $numer)
    {
		$query = $this->select()
                ->where('id_inwest =?', $id)
                ->where('numer > ?', $numer)
                ->limit(1);

        $building = $this->fetchRow($query);

        if ($building) {
            return $building;
        }
    }

    public function getPrevBuilding(int $id, $numer)
    {
		
		$query = $this->select()
                ->where('id_inwest =?', $id)
                ->where('numer < ?', $numer)
                ->order('numer DESC')
                ->limit(1);

        $building = $this->fetchRow($query);

        if($building) {
            return $building;
        }
    }
}