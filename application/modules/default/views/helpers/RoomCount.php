<?php

class Zend_View_Helper_RoomCount extends Zend_View_Helper_Abstract {

    public function roomCount($numer){

        $translate = Zend_Registry::get('Zend_Translate');

        switch ($numer) {
            case $numer == 1:
                return '1 pokój';
            case $numer >= 2 && $numer <= 4:
                return $numer .' pokoje';
            case $numer >= 5:
                return $numer .' pokoi';
        }
    }
}