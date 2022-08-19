<?php
class Form_PowierzchniaForm extends Zend_Form
{ 
    public function __construct($options = null)
    {
        $this->addElementPrefixPath('App', 'App/');
        parent::__construct($options);
        $this->setName('pomieszczenie');
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
        $nazwa->setLabel('Pełna nazwa')
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

        $nazwa_lista = new Zend_Form_Element_Text('nazwa_lista');
        $nazwa_lista->setLabel('Nazwa na liście<br /><span style="font-size:11px;color:#A5A5A5">Mieszkanie, Apartament, Dom itd</span>')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required]')
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

        $numer_pietro = new Zend_Form_Element_Text('numer_pietro');
        $numer_pietro->setLabel('Poziom')
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

        $order_numer = new Zend_Form_Element_Text('order_numer');
        $order_numer->setLabel('Numer (tylko cyfry)')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required,custom[onlyNumberSp]]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $metry = new Zend_Form_Element_Text('metry');
        $metry->setLabel('Powierzchnia bez m2 (wyświetlana)')
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
		
        $szukaj_metry = new Zend_Form_Element_Text('szukaj_metry');
        $szukaj_metry->setLabel('Powierzchnia bez m2 (szukana)<br /><span style="font-size:11px;color:#A5A5A5">tylko liczby</span>')
		->setRequired(true)
		->setAttrib('size', 83)
		->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));	
		
        $ogrodek = new Zend_Form_Element_Text('ogrodek');
        $ogrodek->setLabel('Ogródek bez m2')
		->setRequired(false)
		->setAttrib('size', 83)
		//->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $balkon = new Zend_Form_Element_Text('balkon');
        $balkon->setLabel('Balkon bez m2')
		->setRequired(false)
		->setAttrib('size', 83)
		//->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $taras = new Zend_Form_Element_Text('taras');
        $taras->setLabel('Taras bez m2')
		->setRequired(false)
		->setAttrib('size', 83)
		//->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
				
        $loggia = new Zend_Form_Element_Text('loggia');
        $loggia->setLabel('Loggia bez m2')
		->setRequired(false)
		->setAttrib('size', 83)
		//->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $cena_netto = new Zend_Form_Element_Select('cena_netto');
        $cena_netto->setLabel('Cena netto')
            ->addMultiOption('0','Nie')
            ->addMultiOption('1','Tak')
            ->setDecorators(array(
                'ViewHelper',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
                array('Label'),
                array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $cena = new Zend_Form_Element_Text('cena');
        $cena->setLabel('Cena bez waluty (wyświetlana)')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $cena_promocja = new Zend_Form_Element_Text('cena_promocja');
        $cena_promocja->setLabel('Cena promocyjna bez waluty')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $cena_m = new Zend_Form_Element_Text('cena_m');
        $cena_m->setLabel('Cena za m2 bez waluty')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
        $cena_m_promocja = new Zend_Form_Element_Text('cena_m_promocja');
        $cena_m_promocja->setLabel('Cena promocyjna za m2 bez waluty')
		->setRequired(false)
		->setAttrib('size', 83)
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $szukaj_cena = new Zend_Form_Element_Text('szukaj_cena');
        $szukaj_cena->setLabel('Cena bez waluty (szukana)<br /><span style="font-size:11px;color:#A5A5A5">tylko liczby, bez spacji, bez przecinka</span>')
		->setRequired(false)
		->setAttrib('size', 83)
		//->setAttrib('class', 'validate[required]')
		->setFilters(array('StripTags', 'StringTrim'))
		->addValidator('NotEmpty')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label', array('escape' => false)),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $obrazek = new Zend_Form_Element_File('obrazek');
        $obrazek->setLabel('Plan mieszkania')
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

        $obrazek2 = new Zend_Form_Element_File('obrazek2');
        $obrazek2->setLabel('Plan piętra')
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

        $obrazek3 = new Zend_Form_Element_File('obrazek3');
        $obrazek3->setLabel('Plan inwestycji')
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

        $status = new Zend_Form_Element_Select('status');
        $status->setLabel('Status')
		->addMultiOption('1','Na sprzedaż')
		->addMultiOption('2','Sprzedane')
		->addMultiOption('3','Rezerwacja')
		->addMultiOption('4','Wynajęte')
		->addMultiOption('5','Do wynajęcia')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$uslugowy = new Zend_Form_Element_Select('uslugowy');
        $uslugowy->setLabel('Lokal usługowy')
		->addMultiOption('0','Nie')
		->addMultiOption('1','Tak')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
				
		$promocja = new Zend_Form_Element_Select('promocja');
        $promocja->setLabel('Promocja')
		->addMultiOption('0','Nie')
		->addMultiOption('1','Tak')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$stan = new Zend_Form_Element_Select('stan');
        $stan->setLabel('Stan wykończenia')
		->addMultiOption (0, '-- wybierz --')
		->addMultiOption (1, '-- do zamieszkania --')
		->addMultiOption (2, '-- stan surowy otwarty --')
		->addMultiOption (3, '-- stan surowy zamknięty --')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$glowna = new Zend_Form_Element_Select('glowna');
        $glowna->setLabel('Pokaż na stronie głównej')
		->addMultiOption('0','Nie')
		->addMultiOption('1','Tak')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		$pokoje = new Zend_Form_Element_Select('pokoje');
        $pokoje->setLabel('Pokoje')
		->addMultiOption('1','1')
		->addMultiOption('2','2')
		->addMultiOption('3','3')
		->addMultiOption('4','4')
		->addMultiOption('5','5')
		->addMultiOption('6','6')
		->addMultiOption('7','7')
		->addMultiOption('8','8')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$okno = new Zend_Form_Element_MultiCheckbox('okno');
        $okno->setLabel('Wystawa okna')
		->addMultiOption (1, 'Północ')
		->addMultiOption (2, 'Południe')
		->addMultiOption (3, 'Wschód')
		->addMultiOption (4, 'Zachód')
		->addMultiOption (5, 'Północny wschód')
		->addMultiOption (6, 'Północny zachód')
		->addMultiOption (7, 'Południowy wschód')
		->addMultiOption (8, 'Południowy zachód')
		->setSeparator('')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$typ_okno = new Zend_Form_Element_Select('typ_okno');
        $typ_okno->setLabel('Rodzaj okna')
		->addMultiOption (0, '-- wybierz --')
		->addMultiOption (1, '-- plastikowe --')
		->addMultiOption (2, '-- drewniane --')
		->addMultiOption (3, '-- aluminiowe --')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$ogrzewanie = new Zend_Form_Element_Select('ogrzewanie');
        $ogrzewanie->setLabel('Ogrzewanie')
		->addMultiOption (0, '-- wybierz --')
		->addMultiOption (1, '-- miejskie --')
		->addMultiOption (2, '-- gazowe --')
		->addMultiOption (3, '-- piece kaflowe --')
		->addMultiOption (4, '-- elektryczne --')
		->addMultiOption (5, '-- kotłownia --')
		->addMultiOption (6, '-- inne --')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		$material = new Zend_Form_Element_Select('material');
        $material->setLabel('Materiał budynku')
		->addMultiOption (0, '-- wybierz --')
		->addMultiOption (1, '-- cegła --')
		->addMultiOption (2, '-- drewno --')
		->addMultiOption (3, '-- pustak --')
		->addMultiOption (4, '-- keramzyt --')
		->addMultiOption (5, '-- wielka płyta --')
		->addMultiOption (6, '-- beton --')
		->addMultiOption (7, '-- beton komórkowy --')
		->addMultiOption (8, '-- żelbet --')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$media = new Zend_Form_Element_MultiCheckbox('media');
        $media->setLabel('Media')
		->addMultiOption (1, 'Internet')
		->addMultiOption (2, 'Telewizja kablowa')
		->addMultiOption (3, 'Telefon')
		->setSeparator('')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$zabezpieczenia = new Zend_Form_Element_MultiCheckbox('zabezpieczenia');
        $zabezpieczenia->setLabel('Zabezpieczenia')
		->addMultiOption (1, 'Rolety antywłamaniowe')
		->addMultiOption (2, 'Domofon / wideofon')
		->addMultiOption (3, 'Monitoring / ochrona')
		->addMultiOption (4, 'System alarmowy')
		->addMultiOption (5, 'Teren zamknięty')
		->addMultiOption (6, 'Telefon')
		->setSeparator('')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$info_dodatkowe = new Zend_Form_Element_MultiCheckbox('info_dodatkowe');
        $info_dodatkowe->setLabel('Informacje dodatkowe')
		->addMultiOption (1, 'Pom. użytkowe')
		->addMultiOption (2, 'Garaż/miejsce parkingowe')
		->addMultiOption (3, 'Piwnica')
		->addMultiOption (4, 'Ogródek')
		->addMultiOption (5, 'Loggia/taras/balkon')
		->addMultiOption (6, 'Winda')
		->addMultiOption (8, 'Klimatyzacja')
		->setSeparator('')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));
		
		$kuchnia = new Zend_Form_Element_Select('kuchnia');
        $kuchnia ->setLabel('Kuchnia / Aneks')
		->addMultiOption (0, '-- wybierz --')
		->addMultiOption (1, 'Kuchnia')
		->addMultiOption (2, 'Aneks kuchenny')
		->setDecorators(array(
		'ViewHelper',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

		$pdf = new Zend_Form_Element_File('pdf');
		$pdf->setLabel('Plik .pdf')
		->setRequired(false)
		->addValidator('NotEmpty')
		->addValidator('Extension', false, 'pdf')
		->addValidator('Size', false, 4020400)
		->setDecorators(array(
		'File',
		'Errors',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRight')),
		array('Label'),
		array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formRow'))));

        $meta_slowa = new Zend_Form_Element_Text('meta_slowa');
        $meta_slowa->setLabel('Słowa kluczowe<br /><span style="font-size:11px;color:#A8A8A8">(Keywords)</span>')
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
		
        $tekst = new Zend_Form_Element_Textarea('tekst');
        $tekst->setLabel('Opis lokalu (Nagłówek 3 jako sekcja)')
		->setRequired(false)
		->setAttrib('rows', 22)
		->setAttrib('cols', 100)
		->addValidator('NotEmpty')
		->setAttrib('class', 'editor')
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

		$this->setDecorators(array('FormElements',array('HtmlTag'),'Form',));
        $this->addElements(array(
			$cords,
			$html,
			$status,
			$promocja,
			$nazwa,
			$nazwa_lista,
			//$uslugowy,
			$meta_tytul,
			$meta_slowa,
			$meta_opis,
			$numer,
            $order_numer,
            $numer_pietro,
			$pokoje,
			//$okno,
			// $typ_okno,
			// $ogrzewanie,
			// $material,
			// $media,
			// $zabezpieczenia,
			// $info_dodatkowe,
			$kuchnia,
			$metry,
			$szukaj_metry,
            //$cena_netto,
			$cena,
			//$cena_promocja,
			// $cena_m,
			// $cena_m_promocja,
			// $szukaj_cena,
			$ogrodek,
			//$balkon,
			//$taras,
			//$loggia,
			$obrazek,
			//$obrazek2,
			//$obrazek3,
			$pdf,
			$tekst,
			$submit));
    }
}