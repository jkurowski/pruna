<?php
class Zend_View_Helper_RoomStatus extends Zend_View_Helper_Abstract {

    public function roomStatus(int $id) {
        switch ($id) {
            case '1':
                return '<span class="mieszkanie-wolne">Na sprzedaż</span>';
            case '2':
                return '<span class="mieszkanie-sprzedane">Sprzedane</span>';
            case '3':
                return '<span class="mieszkanie-rezerwacja">Rezerwacja</span>';
            case '4':
                return '<span class="mieszkanie-wynajete">Wynajęte</span>';
        }
    }
}