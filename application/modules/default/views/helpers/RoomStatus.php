<?php

class Zend_View_Helper_RoomStatus extends Zend_View_Helper_Abstract {

	public function roomStatus($numer){
		switch ($numer) {
			case '1':
				return 'Dostępne';
			case '2':
				return 'Sprzedane';
			case '3':
				return 'Rezerwacja';
			case '4':
                return 'Wkrótce w sprzedaży';
            case '5':
                return 'Przedsprzedaż';
		}
	}
}