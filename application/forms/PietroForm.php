<?php
class Form_PietroForm extends Zend_Form
{ 
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('pietro');
		$this->setAttrib('class', 'mainForm');

		$typ = new Zend_Form_Element_Select('typ');
        $typ->setLabel('Typ')
		->addMultiOption('1','Piętro mieszkalne')
		->addMultiOption('2','Piętro usługowe')
		->addMultiOption('3','Parking naziemny')
		->addMultiOption('4','Parking podziemny')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
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
        $nazwa->setLabel('Nazwa piętra *')
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
        $numer->setLabel('Numer piętra *')
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
		
        $numer_lista = new Zend_Form_Element_Text('numer_lista');
        $numer_lista->setLabel('Kolejność piętra *')
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
		$obrazek->setLabel('Rzut piętra')
		->setRequired(false)
		->addValidator('NotEmpty')
		->addValidator('Extension', false, 'jpg, png, jpeg, gif')
		->setAttrib('class', 'validate[checkFileType[jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF]]')
		->addValidator('Size', false, 4020400)
		->setDecorators(array(
		'File',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $tekst = new Zend_Form_Element_Textarea('tekst');
        $tekst->setLabel('Treść')
		->setRequired(false)
		->setAttrib('rows', 27)
		->setAttrib('cols', 100)
		->setAttrib('class', 'editor')
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRowtext')),
		array('Label'), array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'fullformRow'))));
		
	    $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel ('Zapisz')
		->setAttrib('class', 'greyishBtn')
		->setDecorators(array(
		'ViewHelper',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formSubmit'))));

		$this->setDecorators(array('FormElements',array('HtmlTag'),'Form',));
        $this->addElements(array(
			$cords,
			$html,
			$typ,
			$nazwa,
			$meta_tytul,
			$meta_slowa,
			$meta_opis,
			$numer,
			$numer_lista,
			$zakres_powierzchnia,
			$zakres_pokoje,
			$zakres_cen,
			$obrazek,
			//$tekst,
			$submit
		));
    }
}