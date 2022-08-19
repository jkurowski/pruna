<?php
class Admin_InwestycjeController extends kCMS_Admin
{
    private $listaszerokosc;
    private $listawysokosc;

    public function preDispatch() {
			$this->view->controlname = "Inwestycje";
			$this->listaszerokosc = 680;
			$this->listawysokosc = 510;
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
	
################################################### INWESTYCJA ###################################################
// Pokaz wybrana inwestycje
    public function showAction() {
        $db = Zend_Registry::get('db');

        $id = (int)$this->getRequest()->getParam('id');

        $inwestycja = $this->view->inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));

        // Inwestycja osiedlowa
        if($inwestycja->typ == 1) {
            $this->view->lista = $db->fetchAll($db->select()->from('inwestycje_budynki')->where('id_inwest = ?', $id)->order('sort ASC'));
        }
        // Inwestycja budynkowa
        if($inwestycja->typ == 2) {
            $this->view->lista = $db->fetchAll($db->select()->from('inwestycje_pietro')->where('id_inwest = ?', $id)->order('numer DESC')->order('typ ASC'));
        }
        // Inwestycja domkowa
        if($inwestycja->typ == 3) {
            $lista = $this->view->lista = $db->fetchAll($db->select()->from('inwestycje_powierzchnia')->where('id_inwest = ?', $id)->order('numer DESC')->order('numer ASC'));
        }
    }

// Pokaz wszystkie inwestycje
		public function indexAction() {
			$db = Zend_Registry::get('db');
			$user = Zend_Auth::getInstance()->getIdentity();
			
			if($user->role == 'user') {
				$lista = $db->fetchAll($db->select()->from('inwestycje')->order('sort ASC')->where('id IN('.$user->inwestycje.')'));
			} else {
				$lista = $db->fetchAll($db->select()->from('inwestycje')->order('sort ASC'));
			}

            $array = array(
                'lista' => $lista
            );
            $this->view->assign($array);
		}

// Edytuj inwestycje
        public function editAction() {
            $db = Zend_Registry::get('db');
            $this->_helper->viewRenderer('form', null, true);

            $form = new Form_InwestycjaForm();

            // Odczytanie id i pobranie inwestycji
            $id = (int)$this->getRequest()->getParam('id');
            $inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?', $id));

            $array = array(
                'form' => $form,
                'back' => '<div class="back"><a href="'.$this->view->baseUrl().'/admin/inwestycje/">Wróć do listy inwestycji</a></div>',
                'pagename' => ' - Edytuj inwestycję: '.$inwestycja->nazwa,
                'inwestycja' => $inwestycja
            );
            $this->view->assign($array);

            // Załadowanie do forma
            $array = json_decode(json_encode($inwestycja), true);
            if($array) {
                $form->populate($array);
            }

            //Akcja po wcisnieciu Submita
            if ($this->_request->getPost()) {

                //Odczytanie wartosci z inputów
                $formData = $this->_request->getPost();
                unset($formData['MAX_FILE_SIZE']);
                unset($formData['obrazek_lista']);
                unset($formData['submit']);
                $formData['data_dodania'] = $inwestycja->data_dodania;
                $formData['slug'] = slug($formData['nazwa']);

                //Sprawdzenie poprawnosci forma
                if ($form->isValid($formData)) {

                    $db->update('inwestycje', $formData, 'id = '.$id);

                    if($_FILES['obrazek_lista']['size'] > 0) {
                        $investmentModel = new Model_InvestmentModel();
                        $investmentModel->makeThumb($id, $formData['nazwa'], $_FILES['obrazek_lista'], 1);
                    }

                    $this->_redirect('/admin/inwestycje/');

                } else {

                    //Wyswietl bledy
                    $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                    $form->populate($formData);

                }
            }
        }
}
