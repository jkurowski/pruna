<?php
class Form_SliderForm extends Zend_Form
{ 
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('nowypanel');
		$this->setAttrib('enctype', 'multipart/form-data');
		$this->setAttrib('class', 'mainForm');

        $tytul = new Zend_Form_Element_Text('tytul');
        $tytul->setLabel('Tytuł')
		->setRequired(true)
		->setAttrib('size', 33)
		->setFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'validate[required, maxSize[110]]')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $tekst = new Zend_Form_Element_Text('tekst');
        $tekst->setLabel('Tekst')
		->setRequired(true)
		->setAttrib('size', 103)
		->setFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'validate[required]')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		$obrazek = new Zend_Form_Element_File('obrazek');
		$obrazek->setLabel('Plik')
		->setRequired(false)
		->addValidator('NotEmpty')
		->addValidator('Extension', false, 'jpg, png, jpeg, bmp, gif')
		->addValidator('Size', false, 1402400)
		->setDecorators(array(
		'File',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $plik_alt = new Zend_Form_Element_Text('plik_alt');
        $plik_alt->setLabel('Atrybut ALT obrazka')
            ->setRequired(false)
            ->setAttrib('size', 33)
            ->setFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('class', 'validate[maxSize[130]]')
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

	    $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel ('Zapisz panel')
		->setAttrib('class', 'greyishBtn')
		->setDecorators(array(
		'ViewHelper',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formSubmit'))));

		// Polskie tlumaczenie errorów
		$polish = kCMS_Polish::getPolishTranslation();
		$translate = new Zend_Translate('array', $polish, 'pl');
		$this->setTranslator($translate);

		$this->setDecorators(array('FormElements',array('HtmlTag'),'Form',));
        $this->addElements(array(
			$tytul,
			//$tekst,
			$plik_alt,
			$obrazek,
			$submit
		));
    }
}