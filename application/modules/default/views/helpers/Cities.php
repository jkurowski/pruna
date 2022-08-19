<?php

class Zend_View_Helper_Cities extends Zend_View_Helper_Abstract {

    function cities(array $array, int $id){
		
		$locale = Zend_Registry::get('Zend_Locale')->getLanguage();

		if($locale == 'pl') {

			foreach($array as $a){
				if($id == $a['id']) {
					return $a['nazwa'];
				}
			}
			
		} else {

			foreach($array as $a){
				if($id == $a['id_wpis']) {
					return $a['nazwa'];
				}
			}
				
		}
    }
}