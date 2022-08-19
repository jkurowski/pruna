<?php

class Admin_InwestycjeBudynekPietroController extends kCMS_Admin
{
    private $Building;
    private $Investment;
    private $Floor;
    private $Room;
    private $Translate;

    private $Form;

    public function preDispatch() {
        $this->Building= new Model_BuildingModel();
        $this->Investment = new Model_InvestmentModel();
        $this->Translate = new Model_TranslateModel();
        $this->Floor = new Model_FloorModel();
        $this->Room = new Model_RoomModel();

        $this->Form = new Form_PietroForm();

        $array = array(
            'controlname' => 'Piętro inwestycji'
        );
        $this->view->assign($array);
    }

// Pokaz pietra
    public function showAction(){
        $id = (int)$this->getRequest()->getParam('id');
        $i = (int)$this->getRequest()->getParam('i');
        $b = (int)$this->getRequest()->getParam('b');

        $inwestycja = $this->Investment->find($i)->current();
        $budynek = $this->Building->find($b)->current();
        $pietro = $this->Floor->find($id)->current();

        $lista = $this->Room->fetchAll($this->Room->select()->where('id_pietro =?', $id));

        $array = array(
            'inwestycja' => $inwestycja,
            'budynek' => $budynek,
            'pietro' => $pietro,
            'lista' => $lista
        );
        $this->view->assign($array);
    }

// Nowe pietro w budynku
    public function addAction() {
        $this->_helper->viewRenderer('pietro', null, true);

        $i = (int)$this->getRequest()->getParam('i');
        $b = (int)$this->getRequest()->getParam('b');

        $inwestycja = $this->Investment->getInvestmentById($i);
        $budynek = $this->Building->find($b)->current();

        $array = array(
            'form' => $this->Form,
            'pagename' => 'Dodaj piętro',
            'inwestycja' => $inwestycja,
            'budynek' => $budynek,
            'imageWidth' => $this->Floor::IMG_WIDTH
        );
        $this->view->assign($array);

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            //Odczytanie wartosci z inputów
            $formData = $this->_request->getPost();
            unset($formData["MAX_FILE_SIZE"], $formData["submit"]);
            $formData += array(
                'id_inwest' => $i,
                'id_budynek' => $b,
                'tag' => slug($formData['nazwa'])
            );
            $obrazek = $_FILES['obrazek']['name'];
            if($_FILES['obrazek']['size'] > 0) {
                $plik = date('mdhis').'-'.slugImg($formData['nazwa'], $obrazek);
            }

            //Sprawdzenie poprawnosci forma
            if ($this->Form->isValid($formData)) {

                $lastId = $this->Floor->insert($formData);

                if($_FILES['obrazek']['size'] > 0) {
                    move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/pietro/'.$plik);
                    $upfile = FILES_PATH.'/inwestycje/pietro/'.$plik;
                    chmod($upfile, 0755);
                    PhpThumbFactory::create($upfile)
                        ->resize($this->Floor::IMG_WIDTH, $this->Floor::IMG_WIDTH)
                        ->save($upfile);
                    $this->Floor->update(array('plik' => $plik), 'id = '.$lastId);
                }

                $this->_redirect('/admin/inwestycje-budynek/show/id/'.$b.'/i/'.$i.'/');
            }
        }
    }

// Edytuj pietro w budynku
    public function editAction() {
        $this->_helper->viewRenderer('pietro', null, true);

        // Odczytanie id
        $id = (int)$this->getRequest()->getParam('id');
        $i = (int)$this->getRequest()->getParam('i');
        $b = (int)$this->getRequest()->getParam('b');

        $inwestycja = $this->Investment->getInvestmentById($i);
        $budynek = $this->Building->find($b)->current();
        $pietro = $this->Floor->find($id)->current();

        $array = array(
            'form' => $this->Form,
            'pagename' => 'Edytuj piętro',
            'inwestycja' => $inwestycja,
            'budynek' => $budynek,
            'pietro' => $pietro,
            'imageWidth' => $this->Floor::IMG_WIDTH
        );
        $this->view->assign($array);

        // Załadowanie do forma
        $this->Form->populate($pietro->toArray());

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

                $this->Floor->update($formData, 'id = '.$id);

                if($_FILES['obrazek']['size'] > 0) {
                    unlink(FILES_PATH."/inwestycje/pietro/".$pietro->plik);

                    move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/pietro/'.$plik);

                    $upfile = FILES_PATH.'/inwestycje/pietro/'.$plik;
                    chmod($upfile, 0755);
                    PhpThumbFactory::create($upfile)
                        ->resize($this->Floor::IMG_WIDTH, $this->Floor::IMG_WIDTH)
                        ->save($upfile);
                    $this->Floor->update(array('plik' => $plik), 'id = '.$id);
                }

                $this->_redirect('/admin/inwestycje-budynek/show/id/'.$b.'/i/'.$i.'/');

            }
        }
    }

// Usun pietro w budynku
    public function deleteAction() {
        $id = (int)$this->getRequest()->getParam('id');
        $i = (int)$this->getRequest()->getParam('i');
        $b = (int)$this->getRequest()->getParam('b');

        $pietro = $this->Floor->find($id)->current();
        unlink(FILES_PATH."/inwestycje/pietro/".$pietro->plik);
        $pietro->delete();

        $this->_redirect('/admin/inwestycje-budynek/show/id/'.$b.'/i/'.$i.'/');
    }
}
