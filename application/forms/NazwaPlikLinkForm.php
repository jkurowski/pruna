<?php
class Form_NazwaPlikLinkForm extends Zend_Form
{
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('nazwapliklink');
        $this->setAttrib('class', 'mainForm');

        $nazwa = new Zend_Form_Element_Text('nazwa');
        $nazwa->setLabel('Nazwa')
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

        $link = new Zend_Form_Element_Text('link');
        $link->setLabel('Link URL')
            ->setRequired(false)
            ->setAttrib('size', 83)
            ->addValidator('NotEmpty')
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

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel ('Zapisz')
            ->setAttrib('class', 'greyishBtn')
            ->setDecorators(array(
                'ViewHelper',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formSubmit'))));

        // Polskie tlumaczenie error??w
        $polish = kCMS_Polish::getPolishTranslation();
        $translate = new Zend_Translate('array', $polish, 'pl');
        $this->setTranslator($translate);

        $this->setDecorators(array('FormElements',array('HtmlTag'),'Form',));
        $this->addElements(array(
            $nazwa,
            $link,
            $obrazek,
            $submit
        ));
    }
}