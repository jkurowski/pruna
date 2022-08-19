<?php
class Form_BudynekForm extends Zend_Form
{ 
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('budynek');
		$this->setAttrib('class', 'mainForm');

        $cords = new Zend_Form_Element_Textarea('cords');
        $cords->setLabel('Współrzędne punktów')
		->setRequired(false)
		->setAttrib('rows', 10)
		->setAttrib('cols', 100)
		->setAttrib('class', 'mappa-html')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow toggleRow'))));
		
        $html = new Zend_Form_Element_Textarea('html');
        $html->setLabel('Współrzędne punktów HTML')
		->setRequired(false)
		->setAttrib('rows', 10)
		->setAttrib('cols', 100)
		->setAttrib('class', 'mappa-area')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow toggleRow'))));
		
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

        $numer = new Zend_Form_Element_Text('numer');
        $numer->setLabel('Numer')
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
		
        $zakres_powierzchnia = new Zend_Form_Element_Text('zakres_powierzchnia');
        $zakres_powierzchnia->setLabel('Zakres powierzchni w wyszukiwarce xx-xx<br /><span style="font-size:11px;color:#A8A8A8">(zakresy oddzielone przecinkiem)</span>')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $zakres_pokoje = new Zend_Form_Element_Text('zakres_pokoje');
        $zakres_pokoje->setLabel('Zakres pokoi w wyszukiwarce<br /><span style="font-size:11px;color:#A8A8A8">(liczby oddzielone przecinkiem)</span>')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $zakres_cen = new Zend_Form_Element_Text('zakres_cen');
        $zakres_cen->setLabel('Zakres cen w wyszukiwarce xx-xx<br /><span style="font-size:11px;color:#A8A8A8">(zakresy oddzielone przecinkiem)</span>')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$obrazek = new Zend_Form_Element_File('obrazek');
		$obrazek->setLabel('Rzut budynku')
		->setRequired(false)
		->addValidator('NotEmpty')
		->addValidator('Extension', false, 'jpg, png, jpeg, gif, bmp')
		->addValidator('Size', false, 4020400)
		->setDecorators(array(
		'File',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
	    $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel ('Zapisz')
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
			$cords,
			$html,
			$nazwa,
			$meta_tytul,
			$meta_slowa,
			$meta_opis,
			$numer,
			$zakres_powierzchnia,
			$zakres_pokoje,
			$zakres_cen,
			$obrazek,
			$submit
		));
    }
}