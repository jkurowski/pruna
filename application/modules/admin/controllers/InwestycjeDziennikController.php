<?php
class Admin_InwestycjeDziennikController extends kCMS_Admin
{
    private $Investment;
    private $Translate;
    private $Diary;
    private $table;

    public function preDispatch() {
        $this->Investment = new Model_InvestmentModel();
        $this->Diary = new Model_InvestDiaryModel();
        $this->Translate = new Model_TranslateModel();
        $this->table = 'inwestycje_news';

        $array = array(
            'controlname' => 'Dziennik inwestycji'
        );
        $this->view->assign($array);
    }

    public function ustawAction() {
        $db = Zend_Registry::get('db');
        $tabela = $this->_request->getParam('co');
        $updateRecordsArray = $_POST['recordsArray'];
        $listingCounter = 1;
        foreach ($updateRecordsArray as $recordIDValue) {
            $data = array('sort' => $listingCounter);
            $db->update($tabela, $data, 'id = '.$recordIDValue);
            $listingCounter = $listingCounter + 1;
        }
    }

// Pokaz wszystkie wpisy
    public function showAction() {
        $i = (int)$this->getRequest()->getParam('id');
        $array = array(
            'inwestycja' => $this->Investment->find($i)->current(),
            'lista' => $this->Diary->fetchAll($this->Diary->select()->where('id_inwest =?', $i))
        );
        $this->view->assign($array);
    }

// Dodaj nowy wpis
    public function addAction() {
        $db = Zend_Registry::get('db');
        $this->_helper->viewRenderer('form', null, true);

        $id = (int)$this->getRequest()->getParam('id');

        $investmentModel = new Model_InvestmentModel();
        $inwestycja = $investmentModel->getInvestmentById($id);

        $form = new Form_NewsForm();

        $array = array(
            'form' => $form,
            'info' => '<div class="info">Wymiary miniaturki: <b>'.$this->Diary::IMG_WIDTH.'px</b> szerokości / <b>'.$this->Diary::IMG_HEIGHT.'px</b> wysokości</div>',
            'back' => '<div class="back"><a href="'.$this->view->baseUrl().'/admin/inwestycje-dziennik/show/id/'.$id.'/">Wróć do listy</a></div>',
            'pagename' => ' - '.$inwestycja->nazwa.' - Nowy wpis',
            'inwestycja' => $inwestycja,
            'tinymce' => 1
        );
        $this->view->assign($array);

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            //Odczytanie wartosci z inputów
            $status = $this->_request->getPost('status');
            $tytul = $this->_request->getPost('tytul');
            $meta_tytul = $this->_request->getPost('meta_tytul');
            $meta_slowa = $this->_request->getPost('meta_slowa');
            $meta_opis = $this->_request->getPost('meta_opis');
            $wprowadzenie = $this->_request->getPost('wprowadzenie');
            $tekst = $this->_request->getPost('tekst');
            $datadodania = $this->_request->getPost('data');
            $obrazek = $_FILES['obrazek']['name'];
            if($_FILES['obrazek']['size'] > 0) {
                $plik = slugImg($tytul, $obrazek);
            }
            $formData = $this->_request->getPost();

            $wpis = $db->fetchRow($db->select()->from($this->table)->where('tag = ?', slug($tytul)));
            if($wpis){

                $this->view->error = '<div class="blad">Artykuł o takiej nazwie już istnieje</div>';

            } else {
                //Sprawdzenie poprawnosci forma
                if ($form->isValid($formData)) {

                    $data = array(
                        'id_inwest' => $id,
                        'data' => $datadodania,
                        'tytul' => $tytul,
                        'wprowadzenie' => $wprowadzenie,
                        'tekst' => $tekst,
                        'meta_slowa' => $meta_slowa,
                        'meta_opis' => $meta_opis,
                        'meta_tytul' => $meta_tytul,
                        'tag' => slug($tytul),
                        'status' => $status
                    );

                    $db->insert($this->table, $data);
                    $lastId = $db->lastInsertId();

                    //Pomyslnie
                    if($_FILES['obrazek']['size'] > 0) {
                        move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/news/'.$plik);
                        $upfile = FILES_PATH.'/inwestycje/news/'.$plik;

                        if (file_exists($upfile)) {
                            $thumbs = FILES_PATH.'/inwestycje/news/thumbs/'.$plik;
                            $share = FILES_PATH.'/inwestycje/news/share/'.$plik;
                            chmod($upfile, 0755);

                            $options = array('jpegQuality' => 80);

                            PhpThumbFactory::create($upfile, $options)
                                ->adaptiveResizeQuadrant($this->Diary::IMG_WIDTH, $this->Diary::IMG_HEIGHT)
                                ->save($upfile);

                            PhpThumbFactory::create($upfile, $options)
                                ->adaptiveResizeQuadrant(600,315)
                                ->save($share);
                            chmod($share, 0755);

                            PhpThumbFactory::create($upfile, $options)
                                ->adaptiveResizeQuadrant(640, 370)
                                ->save($thumbs);
                            chmod($thumbs, 0755);

                            $dataImg = array('plik' => $plik);
                            $db->update($this->table, $dataImg, 'id = '.$lastId);

                        }
                    }

                    $this->_redirect('/admin/inwestycje-dziennik/show/id/'.$id.'/');

                } else {

                    //Wyswietl bledy
                    $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                    $form->populate($formData);

                }
            }
        }
    }

// Edytuj wpis
    public function editAction() {
        $db = Zend_Registry::get('db');
        $this->_helper->viewRenderer('form', null, true);

        $idInvest = (int)$this->getRequest()->getParam('inwest');
        $id = (int)$this->getRequest()->getParam('id');

        $inwestycja = $this->Investment->find($idInvest)->current();
        $wpis = $this->Diary->get($id);

        $form = new Form_NewsForm();

        $array = array(
            'form' => $form,
            'info' => '<div class="info">Wymiary miniaturki: <b>'.$this->Diary::IMG_WIDTH.'px</b> szerokości / <b>'.$this->Diary::IMG_HEIGHT.'px</b> wysokości</div>',
            'back' => '<div class="back"><a href="'.$this->view->baseUrl().'/admin/inwestycje-dziennik/show/id/'.$idInvest.'/">Wróć do listy</a></div>',
            'pagename' => ' - '.$inwestycja->nazwa.' - '.$wpis->tytul,
            'inwestycja' => $inwestycja,
            'tinymce' => 1
        );
        $this->view->assign($array);

        // Załadowanie do forma
        $array = json_decode(json_encode($wpis), true);
        if($array){
            $form->populate($array);
        }

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            //Odczytanie wartosci z inputów $tytul, $data, $obrazek, $wprowadzenie, $tekst
            $status = $this->_request->getPost('status');
            $tytul = $this->_request->getPost('tytul');
            $meta_tytul = $this->_request->getPost('meta_tytul');
            $meta_slowa = $this->_request->getPost('meta_slowa');
            $meta_opis = $this->_request->getPost('meta_opis');
            $wprowadzenie = $this->_request->getPost('wprowadzenie');
            $tekst = $this->_request->getPost('tekst');
            $datadodania = $this->_request->getPost('data');
            $obrazek = $_FILES['obrazek']['name'];
            if($_FILES['obrazek']['size'] > 0) {
                $plik = slugImg($tytul, $obrazek);
            }

            $formData = $this->_request->getPost();

            if(slug($tytul) == $wpis->tag){

                //Sprawdzenie poprawnosci forma
                if ($form->isValid($formData)) {

                    $data = array(
                        'data' => $datadodania,
                        'tytul' => $tytul,
                        'wprowadzenie' => $wprowadzenie,
                        'tekst' => $tekst,
                        'meta_slowa' => $meta_slowa,
                        'meta_opis' => $meta_opis,
                        'meta_tytul' => $meta_tytul,
                        'tag' => slug($tytul),
                        'status' => $status
                    );

                } else {

                    //Wyswietl bledy
                    $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                    $form->populate($formData);

                }

                //echo 'to ten sam tag i ten sam post';

            } else {

                //tag jest inny
                //sprawdz czy ten istnieje w innym poscie
                $czyjest = $db->fetchRow($db->select()->from($this->table)->where('tag = ?', slug($tytul)));

                if($czyjest && $czyjest->id <> $id) {

                    $this->view->error = '<div class="blad">Artykuł o takiej nazwie już istnieje</div>';

                } else {

                    //echo 'to ten sam tag i nowy post';

                    //Sprawdzenie poprawnosci forma
                    if ($form->isValid($formData)) {

                        $data = array(
                            'data' => $datadodania,
                            'wprowadzenie' => $wprowadzenie,
                            'tekst' => $tekst,
                            'meta_slowa' => $meta_slowa,
                            'meta_opis' => $meta_opis,
                            'meta_tytul' => $meta_tytul,
                            'tytul' => $tytul,
                            'tag' => slug($tytul),
                            'status' => $status
                        );

                    } else {

                        //Wyswietl bledy
                        $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                        $form->populate($formData);

                    }
                }
            }

            if($_FILES['obrazek']['size'] > 0) {
                //Usuwanie starych zdjęć
                unlink(FILES_PATH."/inwestycje/news/".$wpis->plik);
                unlink(FILES_PATH."/inwestycje/news/thumbs/".$wpis->plik);
                unlink(FILES_PATH."/inwestycje/news/share/".$wpis->plik);

                move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/news/'.$plik);
                $upfile = FILES_PATH.'/inwestycje/news/'.$plik;
                $thumbs = FILES_PATH.'/inwestycje/news/thumbs/'.$plik;
                $share = FILES_PATH.'/inwestycje/news/share/'.$plik;
                require_once 'kCMS/Thumbs/ThumbLib.inc.php';

                $options = array('jpegQuality' => 80);

                PhpThumbFactory::create($upfile, $options)
                    ->adaptiveResizeQuadrant($this->Diary::IMG_WIDTH, $this->Diary::IMG_HEIGHT)
                    ->save($upfile);

                PhpThumbFactory::create($upfile, $options)
                    ->adaptiveResizeQuadrant(600,315)
                    ->save($share);
                chmod($share, 0755);

                PhpThumbFactory::create($upfile, $options)
                    ->adaptiveResizeQuadrant(640, 370)
                    ->save($thumbs);
                chmod($thumbs, 0755);

                $dataImg = array('plik' => $plik);
                $db->update($this->table, $dataImg, 'id = '.$id);

            }

            $db->update($this->table, $data, 'id = '.$id);
            $this->_redirect('/admin/inwestycje-dziennik/show/id/'.$idInvest.'/');

        }

    }

// Usun wpis
    public function deleteAction() {
        $db = Zend_Registry::get('db');
        $this->_helper->viewRenderer('form', null, true);

        $idInvest = (int)$this->getRequest()->getParam('inwest');
        $id = (int)$this->getRequest()->getParam('id');

        $wpis = $this->Diary->get($id);

        // Inwestycja
        unlink(FILES_PATH."/inwestycje/news/".$wpis->plik);
        unlink(FILES_PATH."/inwestycje/news/thumbs/".$wpis->plik);
        unlink(FILES_PATH."/inwestycje/news/share/".$wpis->plik);

        $where = $db->quoteInto('id = ?', $id);
        $db->delete($this->table, $where);

        $whereLang = array(
            'id_wpis = ?' => $id,
            'module = ?' => 'investnews'
        );
        $db->delete('tlumaczenie_wpisy', $whereLang);

        $this->_redirect('/admin/inwestycje-dziennik/show/id/'.$idInvest.'/');
    }

// Edytuj języki
    public function tlumaczenieAction() {
        $db = Zend_Registry::get('db');
        $this->_helper->viewRenderer('form', null, true);

        // Odczytanie id
        $idInvest = (int)$this->getRequest()->getParam('inwest');
        $id = (int)$this->getRequest()->getParam('id');
        $lang = $this->getRequest()->getParam('lang');
        if(!$id || !$lang){
            $this->_redirect('/admin/inwestycje-dziennik/show/id/'.$idInvest.'/');
        }
        $investmentModel = new Model_InvestmentModel();
        $feature = $investmentModel->getFeatureById($id);

        $tlumaczenieQuery = $db->select()
            ->from('tlumaczenie_wpisy')
            ->where('module = ?', 'investfeature')
            ->where('id_wpis = ?', $id)
            ->where('lang = ?', $lang);
        $tlumaczenie = $db->fetchRow($tlumaczenieQuery);

        // Laduj form
        $form = new Form_AtutForm();
        $form->removeElement('obrazek');

        $array = array(
            'form' => $form,
            'back' => '<div class="back"><a href="'.$this->view->baseUrl().'/admin/inwestycje-atuty/show/id/'.$idInvest.'/">Wróć do listy atutów</a></div>',
            'pagename' => ' - Edytuj tłumaczenie: '.$feature->nazwa
        );
        $this->view->assign($array);

        if($tlumaczenie) {
            $array = json_decode($tlumaczenie->json, true);
            $form->populate($array);
        }

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            $formData = $this->_request->getPost();

            //Sprawdzenie poprawnosci forma
            if ($form->isValid($formData)) {

                $translateModel = new Model_TranslateModel();
                $translateModel->saveTranslate($formData, 'investfeature', $feature->id, $lang);
                $this->_redirect('/admin/inwestycje-dziennik/show/id/'.$idInvest.'/');

            } else {

                //Wyswietl bledy
                $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                $form->populate($formData);

            }
        }
    }

}
