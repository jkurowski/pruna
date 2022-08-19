<?php
require_once 'kCMS/Thumbs/ThumbLib.inc.php';
class Admin_InwestycjePietroController extends kCMS_Admin
{
    private $Investment;
    private $Floor;
    private $Room;

    public function preDispatch() {
        $this->Investment = new Model_InvestmentModel();
        $this->Floor = new Model_FloorModel();
        $this->Room = new Model_RoomModel();

        $array = array(
            'controlname' => 'Piętro inwestycji'
        );
        $this->view->assign($array);
    }

// Pokaz piętro
    public function showAction() {

        // Odczytanie id
        $id = (int)$this->getRequest()->getParam('id');
        $i = (int)$this->getRequest()->getParam('i');

        $inwestycja = $this->Investment->find($i)->current();
        $pietro = $this->Floor->find($id)->current();
        $lista = $this->Room->fetchAll($this->Room->select()
            ->where('id_pietro =?', $id)
        );

        $array = array(
            'inwestycja' => $inwestycja,
            'pietro' => $pietro,
            'lista' => $lista
        );
        $this->view->assign($array);
    }

// Nowe pietro
    public function addAction() {
        $db = Zend_Registry::get('db');
        $this->_helper->viewRenderer('pietro', null, true);

        $id = (int)$this->getRequest()->getParam('i');

        $floorModel = new Model_FloorModel();

        $investmentModel = new Model_InvestmentModel();
        $inwestycja = $investmentModel->getById($id);

        $planModel = new Model_PlanModel();
        $plan = $planModel->get($inwestycja->id);

        $form = new Form_PietroForm();

        $array = array(
            'form' => $form,
            'back' => '<div class="back"><a href="/admin/inwestycje/show/id/'.$id.'/">Wróć do listy</a></div>',
            'pagename' => 'Nowe pietro',
            'inwestycja' => $inwestycja,
            'plan' => $plan,
            'imageWidth' => $floorModel::IMG_WIDTH
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
            $formData['tag'] = slug($formData['nazwa']);

            $obrazek = $_FILES['obrazek']['name'];
            if($_FILES['obrazek']['size'] > 0) {
                $plik = date('mdhis').'-'.slugImg($formData['nazwa'], $obrazek);
            }

            //Sprawdzenie poprawnosci forma
            if ($form->isValid($formData)) {

                $db->insert('inwestycje_pietro', $formData);
                $lastId = $db->lastInsertId();

                if($_FILES['obrazek']['size'] > 0) {
                    move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/pietro/'.$plik);
                    $upfile = FILES_PATH.'/inwestycje/pietro/'.$plik;
                    chmod($upfile, 0755);
                    PhpThumbFactory::create($upfile)
                        ->resize($floorModel::IMG_WIDTH, $floorModel::IMG_WIDTH)
                        ->save($upfile);
                    $db->update($floorModel->_name, array('plik' => $plik), 'id = '.$lastId);
                }

                $this->_redirect('/admin/inwestycje/show/id/'.$id.'/');

            } else {

                //Wyswietl bledy
                $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                $form->populate($formData);

            }
        }
    }

// Edytuj pietro
    public function editAction() {
        $db = Zend_Registry::get('db');
        $this->_helper->viewRenderer('pietro', null, true);

        // Odczytanie id
        $id = (int)$this->getRequest()->getParam('id');
        $i = (int)$this->getRequest()->getParam('i');

        $investmentModel = new Model_InvestmentModel();
        $inwestycja = $investmentModel->getById($i);

        $planModel = new Model_PlanModel();
        $plan = $planModel->get($i);

        $floorModel = new Model_FloorModel();
        $floor = $floorModel->find($id)->current();

        $form = new Form_PietroForm();

        $array = array(
            'form' => $form,
            'back' => '<div class="back"><a href="'.$this->view->baseUrl().'/admin/inwestycje/show/id/'.$i.'/">Wróć do listy</a></div>',
            'pagename' => 'Edytuj piętro - '.$floor->nazwa,
            'inwestycja' => $inwestycja,
            'pietro' => $floor,
            'plan' => $plan,
            'imageWidth' => $floorModel::IMG_WIDTH
        );
        $this->view->assign($array);

        // Załadowanie do forma
        $form->populate($floor->toArray());

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            //Odczytanie wartosci z inputów
            $formData = $this->_request->getPost();
            unset($formData['MAX_FILE_SIZE']);
            unset($formData['obrazek']);
            unset($formData['submit']);
            $formData['id_inwest'] = $i;
            $formData['tag'] = slug($formData['nazwa']);

            $obrazek = $_FILES['obrazek']['name'];
            if($_FILES['obrazek']['size'] > 0) {
                $plik = date('mdhis').'-'.slugImg($formData['nazwa'], $obrazek);
            }

            //Sprawdzenie poprawnosci forma
            if ($form->isValid($formData)) {

                $db->update($floorModel->_name, $formData, 'id = '.$id);

                if($_FILES['obrazek']['size'] > 0) {
                    unlink(FILES_PATH."/inwestycje/pietro/".$floor->plik);

                    move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/pietro/'.$plik);

                    $upfile = FILES_PATH.'/inwestycje/pietro/'.$plik;
                    chmod($upfile, 0755);
                    PhpThumbFactory::create($upfile)
                        ->resize($floorModel::IMG_WIDTH, $floorModel::IMG_WIDTH)
                        ->save($upfile);
                    $db->update($floorModel->_name, array('plik' => $plik), 'id = '.$id);
                }

                $this->redirect('/admin/inwestycje/show/id/'.$i.'/');

            } else {

                //Wyswietl bledy
                $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                $form->populate($formData);

            }
        }
    }

// Usun piętro
    public function deleteAction() {
        $id = (int)$this->getRequest()->getParam('id');
        $i = (int)$this->getRequest()->getParam('i');

        $floorModel = new Model_FloorModel();
        $floor = $floorModel->find($id)->current();
        unlink(FILES_PATH."/inwestycje/pietro/".$floor->plik);
        $floor->delete();

        $this->_redirect('/admin/inwestycje/show/id/'.$i.'/');
    }

// Edytuj języki
    public function tlumaczenieAction() {
        $db = Zend_Registry::get('db');
        $this->_helper->viewRenderer('form', null, true);

        // Odczytanie id
        $i = (int)$this->getRequest()->getParam('i');
        $id = (int)$this->getRequest()->getParam('id');
        $lang = $this->getRequest()->getParam('lang');
        if(!$id || !$lang){
            $this->_redirect('/admin/inwestycje/show/id/'.$i.'/');
        }
        $floorModel = new Model_FloorModel();
        $floor = $floorModel->get($id);

        $tlumaczenieQuery = $db->select()
            ->from('tlumaczenie_wpisy')
            ->where('module = ?', $floorModel->_module)
            ->where('id_wpis = ?', $id)
            ->where('lang = ?', $lang);
        $tlumaczenie = $db->fetchRow($tlumaczenieQuery);

        // Laduj form
        $form = new Form_PietroForm();
        $form->removeElement('obrazek');
        $form->removeElement('numer');
        $form->removeElement('zakres_powierzchnia');
        $form->removeElement('zakres_pokoje');
        $form->removeElement('zakres_cen');
        $form->removeElement('typ');

        $array = array(
            'form' => $form,
            'back' => '<div class="back"><a href="'.$this->view->baseUrl().'/admin/inwestycje/show/id/'.$i.'/">Wróć do listy</a></div>',
            'pagename' => ' - Edytuj tłumaczenie: '.$floor->nazwa
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
                $translateModel->saveTranslate($formData, $floorModel->_module, $floor->id, $lang);
                $this->_redirect('/admin/inwestycje/show/id/'.$i.'/');

            } else {

                //Wyswietl bledy
                $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                $form->populate($formData);

            }
        }
    }
}
