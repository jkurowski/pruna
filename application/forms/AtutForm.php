<?php
class Form_AtutForm extends Zend_Form
{
    /**
     * @throws Zend_Translate_Exception
     * @throws Zend_Form_Exception
     */
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('nazwaplik');
        $this->setAttrib('class', 'mainForm');

        $cyfra = new Zend_Form_Element_Text('cyfra');
        $cyfra->setLabel('Cyfra')
            ->setRequired(true)
            ->setAttrib('size', 83)
            ->setAttrib('class', 'validate[required]')
            ->addValidator('NotEmpty')
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $jednostka = new Zend_Form_Element_Text('jednostka');
        $jednostka->setLabel('Jednostka')
            ->setRequired(true)
            ->setAttrib('size', 83)
            ->setAttrib('class', 'validate[required]')
            ->addValidator('NotEmpty')
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $tekst = new Zend_Form_Element_Text('tekst');
        $tekst->setLabel('Tekst')
            ->setRequired(true)
            ->setAttrib('size', 83)
            ->setAttrib('class', 'validate[required]')
            ->addValidator('NotEmpty')
            ->setDecorators(array(
                'ViewHelper',
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

        $polish = kCMS_Polish::getPolishTranslation();
        $translate = new Zend_Translate('array', $polish, 'pl');
        $this->setTranslator($translate);

        $this->setDecorators(array('FormElements',array('HtmlTag'),'Form',));
        $this->addElements(array(
            $cyfra,
            $jednostka,
            $tekst,
            $submit
        ));
    }
}