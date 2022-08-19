<?php
class Model_RoomModel  extends Zend_Db_Table_Abstract
{
    public $_name = 'inwestycje_powierzchnia';
    public $_module = 'investroom';
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
     * Pokaz przetlumaczony wpis
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
     * Pokaz przetlumaczone piętra
     * @param int $id
     * @return Object
     */
    public function getAllTranslated(int $building = null, int $investment = null)
    {
        $translatedQuery = $this->_db_table->select()
            ->from(array('t' => 'tlumaczenie_wpisy'))
            ->join(array('tl' => $this->_name), 't.id_wpis = tl.id', array(
                'numer',
                'typ',
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

    public function getPromotion()
    {
        $mieszkanie = $this->fetchRow(
            $this->select()
                ->where('promocja =?', 1)
        );
        $translateRoom = $this->getTranslate($mieszkanie->id);

        if($translateRoom && $this->_locale != 'pl'){
            $entryTl = json_decode($translateRoom->json, true);
            if($entryTl){
                $mieszkanie->nazwa = $entryTl['nazwa'];
            }
        }
        return $mieszkanie;
    }

    /**
     * Pokaz przetlumaczone piętra
     * @param int $id
     * @return Object
     */

    public function getRoom(int $id, string $numer, int $building = null)
    {
		
		$query = $this->select()
                ->where('id_inwest =?', $id)
                ->where('numer =?', $numer);
        
		if($building){
            $query->where('id_budynek = ?', $building);
        }
		
        $mieszkanie = $this->fetchRow($query);
        return $mieszkanie;
    }

    public function getNextRoom(int $id, int $numer, int $pietro = null, int $building = null)
    {

		$query = $this->select()
                ->where('id_inwest =?', $id)
                ->where('order_numer > ?', $numer)
                ->limit(1);
        $mieszkanie = $this->fetchRow($query);

        if ($mieszkanie) {
            return $mieszkanie;
        }
    }

    public function getPrevRoom(int $id, int $numer, int $pietro = null, int $building = null)
    {

		$query = $this->select()
			->where('id_inwest =?', $id)
			->where('order_numer < ?', $numer)
			->order('order_numer DESC')
			->limit(1);
        $mieszkanie = $this->fetchRow($query);

        if ($mieszkanie) {
            return $mieszkanie;
        }
    }
}