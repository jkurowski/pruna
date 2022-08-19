<?php

class Zend_View_Helper_InvestStatus extends Zend_View_Helper_Abstract {

    public function investStatus($numer){

        $translate = Zend_Registry::get('Zend_Translate');

        switch ($numer) {
            case '1':
                return $translate->translate("tl_inwest_wsprzedazy");
            case '2':
                return $translate->translate("tl_inwest_zakonczona");
            case '3':
                return $translate->translate("tl_inwest_planowana");
            case '4':
                return $translate->translate("tl_inwest_ukryta");
        }
    }
}