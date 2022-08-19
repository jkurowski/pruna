<?php
class Form_PietroCopyForm extends Zend_Form
{ 
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('strona');
		$this->setAttrib('class', 'mainForm');
		
        $front = Zend_Controller_Front::getInstance();
        $id = $front->getRequest()->getParam('id');
        $id_inwestycja = $front->getRequest()->getParam('inwestycja');
        $id_budynek = $front->getRequest()->getParam('budynek');

		$pietro = new Zend_Form_Element_Select('pietro');
        $pietro->setLabel('Skopiuj do piętra')
		->addMultiOption (NULL, 'Brak')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		$db = Zend_Registry::get('db');
		
		$inwest = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id_inwestycja));
		
		if($inwest->typ == 1) {
			$katalog = $db->fetchAll($db->select()->from('inwestycje_pietro')->order( 'nazwa ASC' )->where('id !=?', $id)->where('id_inwest =?', $id_inwestycja)->where('id_budynek =?', $id_budynek));
		} else {
			$katalog = $db->fetchAll($db->select()->from('inwestycje_pietro')->order( 'nazwa ASC' )->where('id !=?', $id)->where('id_inwest =?', $id_inwestycja));
		}
		
		
		foreach ($katalog as $listItem) {
			$pietro->addMultiOption($listItem->id, $listItem->nazwa);
		}

	    $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel ('Zapisz')
		->setAttrib('class', 'greyishBtn')
		->setDecorators(array(
		'ViewHelper',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formSubmit'))));

		$this->setDecorators(array('FormElements',array('HtmlTag'),'Form',));
        $this->addElements(array($pietro, $submit));
    }
}