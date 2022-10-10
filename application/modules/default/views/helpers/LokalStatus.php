<?php

class Zend_View_Helper_LokalStatus extends Zend_View_Helper_Abstract {

    public function lokalStatus($numer){
        switch ($numer) {
            case '1':
                return 'Dostępny';
            case '2':
                return 'Sprzedany';
            case '3':
                return 'Rezerwacja';
            case '4':
                return 'Wynajęty';
            case '5':
                return 'Do wynajęcia';
        }
    }
}