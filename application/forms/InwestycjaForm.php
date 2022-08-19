<?php
class Form_InwestycjaForm extends Zend_Form
{
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('objekt');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('class', 'mainForm');

        $typ = new Zend_Form_Element_Select('typ');
        $typ->setLabel('Typ')
            ->addMultiOption('2','Inwestycja budynkowa')
            //->addMultiOption('3','Inwestycja z domami')
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $zakres_powierzchnia = new Zend_Form_Element_Text('zakres_powierzchnia');
        $zakres_powierzchnia->setLabel('Zakres powierzchni w wyszukiwarce xx-xx<br /><span style="font-size:11px;color:#A8A8A8">(liczby oddzielone przecinkiem)</span>')
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

        $zakres_pietra = new Zend_Form_Element_Text('zakres_pietra');
        $zakres_pietra->setLabel('Zakres pięter w wyszukiwarce<br /><span style="font-size:11px;color:#A8A8A8">(liczby oddzielone przecinkiem)</span>')
            ->setRequired(false)
            ->setAttrib('size', 83)
            ->setFilters(array('StripTags', 'StringTrim'))
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label', array('escape' => false)),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $nazwa = new Zend_Form_Element_Text('nazwa');
        $nazwa->setLabel('Nazwa inwestycji')
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

        $lista = new Zend_Form_Element_Text('lista');
        $lista->setLabel('Krótki opis na liście')
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

        $meta_tytul = new Zend_Form_Element_Text('meta_tytul');
        $meta_tytul->setLabel('Tytuł strony<br /><span style="font-size:11px;color:#A8A8A8">(Title)</span>')
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
        $meta_opis->setLabel('Opis strony<br /><span style="font-size:11px;color:#A8A8A8">(Description)</span>')
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

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Biuro: adres e-mail')
            ->setRequired(false)
            ->setAttrib('class', 'validate[required]')
            ->setAttrib('size', 47)
            ->addValidator('NotEmpty')
            ->setFilters(array('StripTags', 'StringTrim'))
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $obrazek_lista = new Zend_Form_Element_File('obrazek_lista');
        $obrazek_lista->setLabel('Miniaturka na liście<br /><span style="font-size:11px;color:#A8A8A8">(wymiary: 680px / 510px)</span>')
            ->setRequired(false)
            ->addValidator('NotEmpty')
            ->addValidator('Extension', false, 'jpg, png, jpeg, gif')
            ->setAttrib('class', 'validate[checkFileType[jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF]]')
            ->addValidator('Size', false, 4020400)
            ->setDecorators(array(
                'File',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label', array('escape' => false)),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $obrazek_header = new Zend_Form_Element_File('obrazek_header');
        $obrazek_header->setLabel('Obrazek nagłówka<br /><span style="font-size:11px;color:#A8A8A8">(wymiary: 2560px / 460px)</span>')
            ->setRequired(false)
            ->addValidator('NotEmpty')
            ->addValidator('Extension', false, 'jpg, png, jpeg, gif')
            ->setAttrib('class', 'validate[checkFileType[jpg|jpeg|png|gif|JPG|JPEG|PNG|GIF]]')
            ->addValidator('Size', false, 4020400)
            ->setDecorators(array(
                'File',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label', array('escape' => false)),
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

        $this->setDecorators(array('FormElements',array('HtmlTag'),'Form'));
        $this->addElements(array(
            $typ,
            $nazwa,
            //$lista,
            //$meta_tytul,
            //$meta_opis,
            //$zakres_powierzchnia,
            //$zakres_pokoje,
            //$zakres_pietra,
            //$email,
            //$obrazek_lista,
            //$obrazek_header,
            $submit
        ));
    }
}