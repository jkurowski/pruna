<?php

class Zend_View_Helper_TakNie extends Zend_View_Helper_Abstract {

	function takNie($numer){
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		switch ($numer) {
			case '1':
				return $translate->translate("tl_option_tak");
			case '0':
				return $translate->translate("tl_option_nie");
		}
	}
}