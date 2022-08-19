<?php
class Admin_InwestycjeAtutyController extends kCMS_Admin
{

    public function preDispatch() {
        $this->view->controlname = "Atuty inwestycji";
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
        $id = (int)$this->getRequest()->getParam('id');
        $investmentModel = new Model_InvestmentModel();
        $array = array(
            'inwestycja' => $investmentModel->getInvestmentById($id),
            'lista' => $investmentModel->getAllFeatures($id)
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

        $form = new Form_AtutForm();

        $array = array(
            'form' => $form,
            'info' => '<div class="info">Wymiary miniaturki: <b>'.$investmentModel::FEATURE_WIDTH.'px</b> szerokości / <b>'.$investmentModel::FEATURE_HEIGHT.'px</b> wysokości</div>',
            'back' => '<div class="back"><a href="'.$this->view->baseUrl().'/admin/inwestycje-atuty/show/id/'.$id.'/">Wróć do listy atutów</a></div>',
            'pagename' => ' - '.$inwestycja->nazwa.' - Nowy atut',
            'inwestycja' => $inwestycja
        );
        $this->view->assign($array);

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            //Odczytanie wartosci z inputów
            $formData = $this->_request->getPost();
            unset($formData['MAX_FILE_SIZE']);
            unset($formData['obrazek']);
            unset($formData['submit']);

            $formData['id_inwest'] = $id;

            //Sprawdzenie poprawnosci forma
            if ($form->isValid($formData)) {

                $db->insert('inwestycje_atut', $formData);
                $lastId = $db->lastInsertId();

                if($_FILES['obrazek']['size'] > 0) {
                    $investmentModel->makeFeature($lastId, $formData['nazwa'], $_FILES['obrazek']);
                }

                $this->_redirect('/admin/inwestycje-atuty/show/id/'.$id.'/');

            } else {

                //Wyswietl bledy
                $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                $form->populate($formData);

            }
        }
    }

// Edytuj wpis
    public function editAction() {
        $db = Zend_Registry::get('db');
        $this->_helper->viewRenderer('form', null, true);

        $idInvest = (int)$this->getRequest()->getParam('inwest');
        $id = (int)$this->getRequest()->getParam('id');

        $investmentModel = new Model_InvestmentModel();
        $inwestycja = $investmentModel->getInvestmentById($idInvest);
        $atut = $investmentModel->getFeatureById($id);

        $form = new Form_AtutForm();

        $array = array(
            'form' => $form,
            'info' => '<div class="info">Wymiary miniaturki: <b>'.$investmentModel::FEATURE_WIDTH.'px</b> szerokości / <b>'.$investmentModel::FEATURE_HEIGHT.'px</b> wysokości</div>',
            'back' => '<div class="back"><a href="'.$this->view->baseUrl().'/admin/inwestycje-atuty/show/id/'.$idInvest.'/">Wróć do listy atutów</a></div>',
            'pagename' => ' - '.$inwestycja->nazwa.' - '.$atut->nazwa,
            'inwestycja' => $inwestycja
        );
        $this->view->assign($array);

        // Załadowanie do forma
        $array = json_decode(json_encode($atut), true);
        if($array){
            $form->populate($array);
        }

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            //Odczytanie wartosci z inputów
            $formData = $this->_request->getPost();
            unset($formData['MAX_FILE_SIZE']);
            unset($formData['obrazek']);
            unset($formData['submit']);

            $formData['id_inwest'] = $idInvest;

            //Sprawdzenie poprawnosci forma
            if ($form->isValid($formData)) {
                $db->update('inwestycje_atut', $formData, 'id = '.$id);

                if($_FILES['obrazek']['size'] > 0) {
                    $investmentModel->makeFeature($id, $formData['nazwa'], $_FILES['obrazek'], 1);
                }

                $this->_redirect('/admin/inwestycje-atuty/show/id/'.$idInvest.'/');
            } else {
                //Wyswietl bledy
                $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                $form->populate($formData);
            }
        }
    }

// Usun wpis
    public function deleteAction() {
        $db = Zend_Registry::get('db');
        $this->_helper->viewRenderer('form', null, true);

        $idInvest = (int)$this->getRequest()->getParam('inwest');
        $id = (int)$this->getRequest()->getParam('id');

        $investmentModel = new Model_InvestmentModel();
        $feature = $investmentModel->getFeatureById($id);

        // Inwestycja
        unlink(FILES_PATH."/inwestycje/atuty/".$feature->plik);
        $where = $db->quoteInto('id = ?', $id);
        $db->delete('inwestycje_atut', $where);

        $whereLang = array(
            'id_wpis = ?' => $id,
            'module = ?' => 'investfeature'
        );
        $db->delete('tlumaczenie_wpisy', $whereLang);

        $this->_redirect('/admin/inwestycje-atuty/show/id/'.$idInvest.'/');
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
            $this->_redirect('/admin/inwestycje-atuty/show/id/'.$idInvest.'/');
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
                $this->_redirect('/admin/inwestycje-atuty/show/id/'.$idInvest.'/');

            } else {

                //Wyswietl bledy
                $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                $form->populate($formData);

            }
        }
    }


}
