<?php
require_once 'kCMS/Thumbs/ThumbLib.inc.php';
class Admin_InwestycjeBudynekController extends kCMS_Admin
{
    private $Building;
    private $Investment;
    private $Plan;
    private $Floor;
    private $Form;
    private $Translate;

    public function preDispatch() {
        $this->Building= new Model_BuildingModel();
        $this->Investment = new Model_InvestmentModel();
        $this->Plan = new Model_PlanModel();
        $this->Translate = new Model_TranslateModel();
        $this->Floor = new Model_FloorModel();

        $this->Form = new Form_BudynekForm();

        $array = array(
            'controlname' => 'Budynek inwestycji'
        );
        $this->view->assign($array);
    }

// Pokaz pietra
    public function showAction(){

        // Odczytanie id
        $id = (int)$this->getRequest()->getParam('id');
        $i = (int)$this->getRequest()->getParam('i');

        $floors = $this->Floor->getBuildingsFloor($i, $id);
        $inwestycja = $this->Investment->getInvestmentById($i);
        $budynek = $this->Building->find($id)->current();

        $array = array(
            'floors' => $floors,
            'inwestycja' => $inwestycja,
            'budynek' => $budynek
        );
        $this->view->assign($array);
    }

// Nowy budynek
    public function addAction() {
        $this->_helper->viewRenderer('budynek', null, true);

        $id = (int)$this->getRequest()->getParam('i');

        $investment = $this->Investment->getInvestmentById($id);
        $plan = $this->Plan->get($id);

        $array = array(
            'form' => $this->Form,
            'imageWidth' => $this->Building::IMG_WIDTH,
            'pagename' => 'Nowy budynek',
            'inwestycja' => $investment,
            'plan' => $plan,
        );
        $this->view->assign($array);

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            //Odczytanie wartosci z inputów
            $formData = $this->_request->getPost();
            unset($formData["MAX_FILE_SIZE"], $formData["submit"]);
            $formData += array(
                'id_inwest' => $id,
                'tag' => slug($formData['nazwa'])
            );
            $obrazek = $_FILES['obrazek']['name'];
            if($_FILES['obrazek']['size'] > 0) {
                $plik = date('mdhis').'-'.slugImg($formData['nazwa'], $obrazek);
            }

            //Sprawdzenie poprawnosci forma
            if ($this->Form->isValid($formData)) {

                $lastId = $this->Building->insert($formData);

                if($_FILES['obrazek']['size'] > 0) {
                    move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/budynek/'.$plik);
                    $upfile = FILES_PATH.'/inwestycje/budynek/'.$plik;
                    chmod($upfile, 0755);
                    PhpThumbFactory::create($upfile)
                        ->resize($this->Building::IMG_WIDTH, $this->Building::IMG_WIDTH)
                        ->save($upfile);
                    $this->Building->update(array('plik' => $plik), 'id = '.$lastId);
                }

                $this->_redirect('/admin/inwestycje/show/id/'.$id.'/');
            }
        }
    }

// Edytuj budynek
    public function editAction() {
        $this->_helper->viewRenderer('budynek', null, true);

        // Odczytanie id
        $id = (int)$this->getRequest()->getParam('id');
        $i = (int)$this->getRequest()->getParam('i');

        $inwestycja = $this->Investment->getInvestmentById($i);
        $plan = $this->Plan->get($i);
        $budynek = $this->Building->find($id)->current();

        $array = array(
            'form' => $this->Form,
            'pagename' => 'Edytuj budynek',
            'inwestycja' => $inwestycja,
            'budynek' => $budynek,
            'plan' => $plan,
        );
        $this->view->assign($array);

        // Załadowanie do forma
        $this->Form->populate($budynek->toArray());

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            //Odczytanie wartosci z inputów
            $formData = $this->_request->getPost();
            unset($formData["MAX_FILE_SIZE"], $formData["submit"]);
            $formData += array(
                'tag' => slug($formData['nazwa'])
            );
            $obrazek = $_FILES['obrazek']['name'];
            if($_FILES['obrazek']['size'] > 0) {
                $plik = date('mdhis').'-'.slugImg($formData['nazwa'], $obrazek);
            }

            //Sprawdzenie poprawnosci forma
            if ($this->Form->isValid($formData)) {

                $this->Building->update($formData, 'id = '.$id);

                if($_FILES['obrazek']['size'] > 0) {
                    unlink(FILES_PATH."/inwestycje/budynek/".$budynek->plik);

                    move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/budynek/'.$plik);

                    $upfile = FILES_PATH.'/inwestycje/budynek/'.$plik;
                    chmod($upfile, 0755);
                    PhpThumbFactory::create($upfile)
                        ->resize($this->Building::IMG_WIDTH, $this->Building::IMG_WIDTH)
                        ->save($upfile);
                    $this->Building->update(array('plik' => $plik), 'id = '.$id);
                }

                $this->_redirect('/admin/inwestycje/show/id/'.$i.'/');

            }
        }
    }

// Usun budynek
    public function deleteAction() {
        $db = Zend_Registry::get('db');

        // Odczytanie id
        $id = (int)$this->getRequest()->getParam('id');
        $i = (int)$this->getRequest()->getParam('i');

        $inwestycja = $this->Investment->getInvestmentById($i);
        $budynek = $this->Building->find($id)->current();

        if($inwestycja->typ == 1) {
            //Inwestycja osiedlowa

            $floorsQuery = $db->select()
                ->from('inwestycje_pietro')
                ->where('id_inwest = ?', $i)
                ->where('id_budynek = ?', $id);
            $floors = $db->fetchAll($floorsQuery);

            foreach($floors as $f) {
                try {
                    //unlink(FILES_PATH."/inwestycje/pietro/".$f->plik);
                    echo 'usuwam plik: '.$f->plik;
                    echo '<br>';
                }
                catch (Exception $e) { echo $e->getMessage(); }

                $whereFloor = $db->quoteInto('id = ?', $f->id);
                //$db->delete('inwestycje_pietro', $whereFloor);
            }

            $roomsQuery = $db->select()
                ->from('inwestycje_powierzchnia')
                ->where('id_inwest = ?', $i)
                ->where('id_budynek = ?', $id);
            $rooms = $db->fetchAll($roomsQuery);

            foreach($rooms as $r) {
                try {
                    //unlink(FILES_PATH."/inwestycje/pomieszczenie/".$r->plik);
                    echo 'usuwam plik: '.$r->plik;
                    echo '<br>';
                }
                catch (Exception $e) { echo $e->getMessage(); }
                try {
                    //unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$r->plik);
                    echo 'usuwam plik: '.$r->plik;
                    echo '<br>';
                }
                catch (Exception $e) { echo $e->getMessage(); }
                try {
                    //unlink(FILES_PATH."/inwestycje/pdf/".$r->pdf);
                    echo 'usuwam pdf: '.$r->plik;
                    echo '<br>';
                }
                catch (Exception $e) { echo $e->getMessage(); }

                $whereRoom = $db->quoteInto('id = ?', $r->id);
//                $db->delete('inwestycje_powierzchnia', $whereRoom);
            }

            unlink(FILES_PATH."/inwestycje/budynek/".$budynek->plik);
            $budynek->delete();
            $this->_redirect('/admin/inwestycje/show/id/'.$i.'/');
        }
    }

// Edytuj języki
    public function tlumaczenieAction() {
        $this->_helper->viewRenderer('form', null, true);

        // Odczytanie id
        $i = (int)$this->getRequest()->getParam('i');
        $id = (int)$this->getRequest()->getParam('id');
        $lang = $this->getRequest()->getParam('lang');
        if(!$id || !$lang){
            $this->_redirect('/admin/inwestycje/show/id/'.$i.'/');
        }
        $budynek = $this->Building->find($id)->current();
        $tlumaczenie = $this->Translate->getTranslate($this->Building->_module, $id, $lang);

        // Laduj form
        $this->Form->removeElement('obrazek');
        $this->Form->removeElement('numer');
        $this->Form->removeElement('zakres_powierzchnia');
        $this->Form->removeElement('zakres_pokoje');
        $this->Form->removeElement('zakres_cen');

        $array = array(
            'form' => $this->Form,
            'back' => '<div class="back"><a href="'.$this->view->baseUrl().'/admin/inwestycje/show/id/'.$i.'/">Wróć do listy</a></div>',
            'pagename' => ' - Edytuj tłumaczenie: '.$budynek->nazwa
        );
        $this->view->assign($array);

        if($tlumaczenie) {
            $arrayForm = json_decode($tlumaczenie->json, true);
            $this->Form->populate($arrayForm);
        }

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            $formData = $this->_request->getPost();

            //Sprawdzenie poprawnosci forma
            if ($this->Form->isValid($formData)) {

                $this->Translate->saveTranslate($formData, $this->Building->_module, $budynek->id, $lang);
                $this->_redirect('/admin/inwestycje/show/id/'.$i.'/');

            }
        }
    }
}
