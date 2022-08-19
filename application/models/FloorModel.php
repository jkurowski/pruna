<?php
class Model_FloorModel  extends Zend_Db_Table_Abstract
{
    public $_name = 'inwestycje_pietro';
    protected $_primary = 'id';

    const IMG_WIDTH = 1500;

    /**
     * Admin - Pokaz wybrane pietro po id
     * Do usuniecia
     * @param int $id
     * @return Object
     */
    public function get(int $id)
    {
        $floorQuery = $this->_db_table->select()
            ->from($this->_name)
            ->where('id = ?', $id);
        return $this->_db_table->fetchRow($floorQuery);
    }

    /**
     * Admin - Pokaz wybrane pietro po id
     * @param int $id
     * @return Object
     */
    public function getBuildingsFloor(int $id, int $building)
    {
        return $this->fetchAll($this->select()
            ->where('id_inwest = ?', $id)
            ->where('id_budynek = ?', $building)
            ->order('numer_lista DESC')
        );
    }

    /**
     * Front - Pokaz wszystkie pietra
     * @param int $id
     * @return Object
     */
    public function getAll(int $id, int $building = null)
    {
        if($building) {
            $pietra = $this->fetchAll(
                $this->select()
                    ->where('id_inwest = ?', $id)
                    ->where('id_budynek = ?', $building)
                    ->order('numer_lista DESC')
            );
        } else {
            $pietra = $this->fetchAll(
                $this->select()
                    ->where('id_inwest = ?', $id)
                    ->order('numer_lista DESC')
            );
        }
        return $pietra;
    }
	
    /**
     * Front - Pokaz wszystkie pietra
     * @param int $id
     * @return Object
     */
    public function getParkingFloors(int $id, int $building = null)
    {
        if($this->_locale == 'pl') {
            if($building) {
                $pietra = $this->fetchAll(
                    $this->select()
                        ->where('id_inwest = ?', $id)
                        ->where('id_budynek = ?', $building)
                        ->where('typ = ?', 4)
                        ->orWhere('typ = ?', 3)
						->order('numer_lista DESC')
                );
            } else {
                $pietra = $this->fetchAll(
                    $this->select()
                        ->where('id_inwest = ?', $id)
						->where('typ = ?', 4)
                        ->orWhere('typ = ?', 3)
						->order('numer_lista DESC')
				);
            }
        } else {
            $pietra = $this->getAllTranslated($building, $id);
        }
        return $pietra;
    }

    /**
     * Front - Pokaz pietro
     * @param int $id
     * @return Object
     */
    public function getFloor(int $id, string $numer, int $typ, int $building = null)
    {

		if($building) {
			$pietro = $this->fetchRow(
				$this->select()
					->where('id_inwest =?', $id)
					->where('numer =?', $numer)
					->where('id_budynek = ?', $building)
					->where('typ = ?', $typ)
			);
		} else {
			$pietro = $this->fetchRow(
				$this->select()
					->where('id_inwest =?', $id)
					->where('numer =?', $numer)
					->where('typ = ?', $typ)
			);
		}

        return $pietro;
    }

    public function getNextFloor(int $id, int $numer, int $building = null)
    {
		$query = $this->select()
                ->where('id_inwest =?', $id)
                ->where('numer_lista > ?', $numer)
                ->limit(1);
				
		if ($building) {
			$query->where('id_budynek =?', $building);
		}
		
        $pietro = $this->fetchRow($query);

        if ($pietro) {
            return $pietro;
        }
    }

    public function getPrevFloor(int $id, int $numer, int $building = null)
    {
		
		$query = $this->select()
                ->where('id_inwest =?', $id)
                ->where('numer_lista < ?', $numer)
                ->order('numer_lista DESC')
                ->limit(1);

		if ($building) {
			$query->where('id_budynek =?', $building);
		}
		
        $pietro = $this->fetchRow($query);

        if($pietro) {
            return $pietro;
        }
    }
}