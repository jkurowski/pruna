<?php
class Form_SliderUstawieniaForm extends Zend_Form 
{
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('sliderustawienia');
		$this->setAttrib('class', 'mainForm');

		$efekt = new Zend_Form_Element_Select('efekt');
        $efekt->setLabel('Efekt')
		->addMultiOption('sliceDownRight','sliceDownRight')
		->addMultiOption('sliceDownLeft','sliceDownLeft')
		->addMultiOption('sliceUpRight','sliceUpRight')
		->addMultiOption('sliceUpLeft','sliceUpLeft')
		->addMultiOption('sliceUpDown','sliceUpDown')
		->addMultiOption('sliceUpDownLeft','sliceUpDownLeft')
		->addMultiOption('fold','fold')
		->addMultiOption('fade','fade')
		->addMultiOption('boxRandom','boxRandom')
		->addMultiOption('boxRain','boxRain')
		->addMultiOption('boxRainReverse','boxRainReverse')
		->addMultiOption('boxRainGrow','boxRainGrow')
		->addMultiOption('boxRainGrowReverse','boxRainGrow')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		$auto = new Zend_Form_Element_Select('auto');
        $auto->setLabel('Tryb automatyczny')
		->addMultiOption('true','Tak')
		->addMultiOption('false','Nie')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		$pause = new Zend_Form_Element_Select('pause');
        $pause->setLabel('Zatrzymaj po najechaniu')
		->addMultiOption('true','Tak')
		->addMultiOption('false','Nie')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		$nav = new Zend_Form_Element_Select('nav');
        $nav->setLabel('Pokaż strzałki')
		->addMultiOption('true','Tak')
		->addMultiOption('false','Nie')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		$pager = new Zend_Form_Element_Select('pager');
        $pager->setLabel('Pokaż kulki')
		->addMultiOption('true','Tak')
		->addMultiOption('false','Nie')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $speed = new Zend_Form_Element_Text('speed');
        $speed->setLabel('Szybkość przenikania obrazków<br /><span style="font-size:11px;color:#A8A8A8">1000 = 1s</span>')
		->setRequired(true)
		->setAttrib('size', 33)
		->addValidator('stringLength', false, array(1, 255))
		->setFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'validate[required]')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $timeout = new Zend_Form_Element_Text('timeout');
        $timeout->setLabel('Czas wyświetlania obrazka<br /><span style="font-size:11px;color:#A8A8A8">1000 = 1s</span>')
		->setRequired(true)
		->setAttrib('size', 33)
		->addValidator('stringLength', false, array(1, 255))
		->setFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'validate[required]')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

	    $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel ('Zapisz ustawienia')
		->setAttrib('class', 'greyishBtn')
		->setDecorators(array(
		'ViewHelper',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formSubmit'))));

		$this->setDecorators(array('FormElements',array('HtmlTag'),'Form',));
        $this->addElements(array(
            $efekt,
            $auto,
            $pause,
            $nav,
            $pager,
            $speed,
            $timeout,
            $submit
        ));

    }
}