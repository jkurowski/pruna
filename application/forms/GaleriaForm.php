<?php
class Form_GaleriaForm extends Zend_Form
{
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('galeria');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('class', 'mainForm');

        $status = new Zend_Form_Element_Select('status');
        $status->setLabel('Status')
            ->addMultiOption('1','Pokaż katalog w galerii')
            ->addMultiOption('0','Ukryj katalog')
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $nazwa = new Zend_Form_Element_Text('nazwa');
        $nazwa->setLabel('Nazwa')
            ->setRequired(true)
            ->setAttrib('size', 83)
            ->addValidator('stringLength', false, array(3, 255))
            ->setFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('class', 'validate[required]')
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $obrazek = new Zend_Form_Element_File('obrazek');
        $obrazek->setLabel('Miniaturka')
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
            //$status,
            $nazwa,
            //$obrazek,
            $submit
        ));
    }
}