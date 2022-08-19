<?php
class Admin_InwestycjeSliderController extends kCMS_Admin
{

    public function preDispatch() {
        $this->view->controlname = "Slider inwestycji";
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
        $investmentSlider = new Model_InvestSliderModel();
        $array = array(
            'inwestycja' => $investmentModel->getInvestmentById($id),
            'lista' => $investmentSlider->getAll($id)
        );
        $this->view->assign($array);
    }

// Dodaj nowy wpis
    public function addAction() {
        $db = Zend_Registry::get('db');
        $this->_helper->viewRenderer('form', null, true);

        $id = (int)$this->getRequest()->getParam('id');

        $investmentModel = new Model_InvestmentModel();
        $investmentSlider = new Model_InvestSliderModel();
        $inwestycja = $investmentModel->getInvestmentById($id);

        $form = new Form_SliderForm();
        $form->removeElement('tekst');
        $form->removeElement('link');
        $form->removeElement('link_tytul');

        $array = array(
            'form' => $form,
            'info' => '<div class="info">Wymiary miniaturki: <b>'.$investmentSlider::IMG_WIDTH.'px</b> szerokości / <b>'.$investmentSlider::IMG_HEIGHT.'px</b> wysokości</div>',
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

                $db->insert('inwestycje_slider', $formData);
                $lastId = $db->lastInsertId();

                if($_FILES['obrazek']['size'] > 0) {
                    $investmentSlider->makeSlider($lastId, $formData['tytul'], $_FILES['obrazek']);
                }

                $this->_redirect('/admin/inwestycje-slider/show/id/'.$id.'/');

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
        $investmentSlider = new Model_InvestSliderModel();
        $inwestycja = $investmentModel->getInvestmentById($idInvest);
        $atut = $investmentSlider->getById($id);

        $form = new Form_SliderForm();
        $form->removeElement('tekst');
        $form->removeElement('link');
        $form->removeElement('link_tytul');

        $array = array(
            'form' => $form,
            'info' => '<div class="info">Wymiary miniaturki: <b>'.$investmentSlider::IMG_WIDTH.'px</b> szerokości / <b>'.$investmentSlider::IMG_HEIGHT.'px</b> wysokości</div>',
            'back' => '<div class="back"><a href="'.$this->view->baseUrl().'/admin/inwestycje-atuty/show/id/'.$id.'/">Wróć do listy atutów</a></div>',
            'pagename' => ' - '.$inwestycja->nazwa.' - '.$atut->tytul,
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
                $db->update('inwestycje_slider', $formData, 'id = '.$id);

                if($_FILES['obrazek']['size'] > 0) {
                    $investmentSlider->makeSlider($id, $formData['tytul'], $_FILES['obrazek'], 1);
                }

                $this->_redirect('/admin/inwestycje-slider/show/id/'.$idInvest.'/');
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

        $investmentSlider = new Model_InvestSliderModel();
        $slider = $investmentSlider->getById($id);

        // Inwestycja
        unlink(FILES_PATH."/inwestycje/slider/".$slider->plik);
        unlink(FILES_PATH."/inwestycje/slider/thumbs/".$slider->plik);

        $where = $db->quoteInto('id = ?', $id);
        $db->delete('inwestycje_slider', $where);

        $this->_redirect('/admin/inwestycje-slider/show/id/'.$idInvest.'/');
    }
}
