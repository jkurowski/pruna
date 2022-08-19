<?php
class Form_MieszkaniaForm extends Zend_Form
{ 
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('mieszkania');
		$this->setAttrib('class', 'mainForm');

        $nazwa = new Zend_Form_Element_Text('nazwa');
        $nazwa->setLabel('Nazwa')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $pokoje = new Zend_Form_Element_Text('pokoje');
        $pokoje->setLabel('Pokoje')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $meta_slowa = new Zend_Form_Element_Text('meta_slowa');
        $meta_slowa->setLabel('Słowa kluczowe<br /><span style="font-size:11px;color:#A8A8A8">Meta tag - Keywords</span>')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $meta_tytul = new Zend_Form_Element_Text('meta_tytul');
        $meta_tytul->setLabel('Nagłówek<br /><span style="font-size:11px;color:#A8A8A8">Meta tag - Title</span>')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $meta_opis = new Zend_Form_Element_Text('meta_opis');
        $meta_opis->setLabel('Opis strony<br /><span style="font-size:11px;color:#A8A8A8">Meta tag - Description</span>')
		->setRequired(false)
		->setAttrib('size', 123)
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $lista = new Zend_Form_Element_Textarea('lista');
        $lista->setLabel('Lista')
		->setRequired(true)
		->setAttrib('rows', 7)
		->setAttrib('cols', 100)
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRowtext')),
		array('Label'), array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRow'))));
		
        $tekst = new Zend_Form_Element_Textarea('tekst');
        $tekst->setLabel('Treść')
		->setRequired(true)
		->setAttrib('rows', 27)
		->setAttrib('cols', 100)
		->setAttrib('class', 'editor')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRowtext')),
		array('Label'), array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRow'))));
		
		$obrazek = new Zend_Form_Element_File('obrazek');
		$obrazek->setLabel('Obrazek nagłówka')
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

		// Polskie tlumaczenie errorów
		$polish = kCMS_Polish::getPolishTranslation();
		$translate = new Zend_Translate('array', $polish, 'pl');
		$this->setTranslator($translate);
		
	    $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel ('Zapisz')
		->setAttrib('class', 'greyishBtn')
		->setDecorators(array(
		'ViewHelper',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formSubmit'))));

		$this->setDecorators(array('FormElements',array('HtmlTag'),'Form',));
        $this->addElements(array(
			$nazwa, 
			$meta_tytul, 
			$meta_slowa, 
			$meta_opis, 
			$pokoje, 
			$obrazek, 
			$lista, 
			$tekst, 
			$submit
		));
    }
}