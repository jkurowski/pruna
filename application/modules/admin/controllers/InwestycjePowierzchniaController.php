<?php
require_once 'kCMS/Thumbs/ThumbLib.inc.php';
class Admin_InwestycjePowierzchniaController extends kCMS_Admin
{
    private $Investment;
    private $Room;
    private $Plan;
    private $Form;
    private $Floor;

    public function preDispatch() {
        $this->Investment = new Model_InvestmentModel();
        $this->Room = new Model_RoomModel();
        $this->Plan = new Model_PlanModel();
        $this->Floor = new Model_FloorModel();
        $this->Form = new Form_PowierzchniaForm();
    }

// Dodaj powierzchnie
    public function addAction() {
        $this->_helper->viewRenderer('pomieszczenie', null, true);

        // Odczytanie id
        $i = (int)$this->getRequest()->getParam('i');
        $p = (int)$this->getRequest()->getParam('p');
        $typ = (int)$this->getRequest()->getParam('typ');

        $inwestycja = $this->Investment->find($i)->current();
        $pietro = $this->Floor->find($p)->current();
        $plan = $this->Plan->fetchRow($this->Plan->select()->where('id_inwest =?', $i));

        $array = array(
            'form' => $this->Form,
            'pagename' => 'Dodaj powierzchnię',
            'plan' => $plan,
            'pietro' => $pietro,
            'inwestycja' => $inwestycja,
            'tinymce' => 1
        );
        $this->view->assign($array);

        $this->view->back = '<div class="back"><a href="/admin/inwestycje/show/id/'.$i.'/">Wróć do listy</a></div>';

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            //Odczytanie wartosci z inputów
            $formData = $this->_request->getPost();
            unset($formData["MAX_FILE_SIZE"], $formData["submit"]);
            $formData += array(
                'id_inwest' => $i,
                'id_pietro' => $p,
                'typ' => $typ,
                'tag' => slug($formData['nazwa'])
            );

            $obrazek = $_FILES['obrazek']['name'];
            if($_FILES['obrazek']['size'] > 0) {
                $plik = date('Ymdhis').'-'.slugImg($formData['nazwa'], $obrazek);
            }

            $obrazek2 = $_FILES['obrazek2']['name'];
            if($_FILES['obrazek2']['size'] > 0) {
                $plik2 = date('Ymdhis').'-pietro_'.slugImg($formData['nazwa'], $obrazek2);
            }

            $obrazek3 = $_FILES['obrazek3']['name'];
            if($_FILES['obrazek3']['size'] > 0) {
                $plik3 = date('Ymdhis').'-plan_'.slugImg($formData['nazwa'], $obrazek3);
            }

            $pdf = $_FILES['pdf']['name'];
            if($_FILES['pdf']['size'] > 0) {
                $plikpdf = date('Ymdhis').'-pdf_'.slugImg($formData['nazwa'], $pdf);
            }

            //Sprawdzenie poprawnosci forma
            if ($this->Form->isValid($formData)) {

                $lastId = $this->Room->insert($formData);

                if($_FILES['obrazek']['size'] > 0) {
                    move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik);
                    $upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik;
                    $thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik;
                    $lista = FILES_PATH.'/inwestycje/pomieszczenie/lista/'.$plik;
                    chmod($upfile, 0755);
                    $options = array('jpegQuality' => 80);

                    PhpThumbFactory::create($upfile, $options)
                        ->resize(960, 960)
                        ->save($upfile)->resize(600, 600)
                        ->save($thumbs)->resize(180, 90)
                        ->save($lista);
                    chmod($thumbs, 0755);
                    chmod($lista, 0755);
                    $this->Room->update(array('plik' => $plik), 'id = '.$lastId);
                }

                if($_FILES['obrazek2']['size'] > 0) {
                    move_uploaded_file($_FILES['obrazek2']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik2);
                    $upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik2;
                    $thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik2;
                    chmod($upfile, 0755);
                    $options = array('jpegQuality' => 80);

                    PhpThumbFactory::create($upfile, $options)
                        ->resize(960, 960)
                        ->save($upfile)->resize(600, 600)
                        ->save($thumbs);
                    chmod($thumbs, 0755);
                    $this->Room->update(array('plik2' => $plik2), 'id = '.$lastId);
                }

                if($_FILES['obrazek3']['size'] > 0) {
                    move_uploaded_file($_FILES['obrazek3']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik3);
                    $upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik3;
                    $thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik3;
                    chmod($upfile, 0755);
                    $options = array('jpegQuality' => 80);

                    PhpThumbFactory::create($upfile, $options)
                        ->resize(960, 960)
                        ->save($upfile)->resize(600, 600)
                        ->save($thumbs);
                    chmod($thumbs, 0755);
                    $this->Room->update(array('plik3' => $plik3), 'id = '.$lastId);
                }

                if($_FILES['pdf']['size'] > 0) {
                    move_uploaded_file($_FILES['pdf']['tmp_name'], FILES_PATH.'/inwestycje/pdf/'.$plikpdf);
                    $upfile = FILES_PATH.'/inwestycje/pdf/'.$plikpdf;
                    chmod($upfile, 0755);
                    $this->Room->update(array('pdf' => $plikpdf), 'id = '.$lastId);
                }

                // Inwestycja osiedlow
                if($inwestycja->typ == 1) {
                    $this->_redirect('admin/inwestycje-budynek-pietro/show/id/'.$p.'/i/'.$i.'/b/'.$b.'/');
                }

                // Inwestycja budynkowa
                if($inwestycja->typ == 2) {
                    $this->_redirect('/admin/inwestycje-pietro/show/id/'.$p.'/i/'.$i.'/');
                }

                // Inwestycja z domkami
                if($inwestycja->typ == 3) {
                    $this->_redirect('/admin/inwestycje/show/id/'.$i.'/');
                }
            }
        }
    }

// Edytuj powierzchnie
    public function editAction() {
        $this->_helper->viewRenderer('pomieszczenie', null, true);

        // Odczytanie id
        $i = (int)$this->getRequest()->getParam('i');
        $b = (int)$this->getRequest()->getParam('b');
        $p = (int)$this->getRequest()->getParam('p');
        $id = (int)$this->getRequest()->getParam('id');

        $inwestycja = $this->Investment->find($i)->current();
        $pietro = $this->Floor->find($p)->current();
        $powierzchnia = $this->Room->find($id)->current();
        $plan = $this->Plan->fetchRow($this->Plan->select()->where('id_inwest =?', $i));

        $array = array(
            'form' => $this->Form,
            'pagename' => 'Edytuj powierzchnię - '.$powierzchnia->nazwa,
            'inwestycja' => $inwestycja,
            'plan' => $plan,
            'pietro' => $pietro,
            'powierzchnia' => $powierzchnia,
            'tinymce' => 1
        );
        $this->view->assign($array);

        $this->view->back = '<div class="back"><a href="/admin/inwestycje/show/id/'.$i.'/">Wróć do listy</a></div>';

        // Załadowanie do forma
        $this->Form->populate($powierzchnia->toArray());

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            //Odczytanie wartosci z inputów
            $formData = $this->_request->getPost();
            unset($formData["MAX_FILE_SIZE"], $formData["submit"]);
            $formData += array(
                'tag' => slug($formData['nazwa'])
            );
            $formData['okno'] = implode(',', $this->_request->getPost('okno'));

            $obrazek = $_FILES['obrazek']['name'];
            if($_FILES['obrazek']['size'] > 0) {
                $plik = date('Ymdhis').'-'.slugImg($formData['nazwa'], $obrazek);
            }

            $obrazek2 = $_FILES['obrazek2']['name'];
            if($_FILES['obrazek2']['size'] > 0) {
                $plik2 = date('Ymdhis').'-pietro_'.slugImg($formData['nazwa'], $obrazek2);
            }

            $obrazek3 = $_FILES['obrazek3']['name'];
            if($_FILES['obrazek3']['size'] > 0) {
                $plik3 = date('Ymdhis').'-plan_'.slugImg($formData['nazwa'], $obrazek3);
            }

            $pdf = $_FILES['pdf']['name'];
            if($_FILES['pdf']['size'] > 0) {
                $plikpdf = date('Ymdhis').'-pdf_'.slugImg($formData['nazwa'], $pdf);
            }

            //Sprawdzenie poprawnosci forma
            if ($this->Form->isValid($formData)) {

                $this->Room->update($formData, 'id = '.$id);

                if($_FILES['obrazek']['size'] > 0) {
                    unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik);
                    unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik);
                    unlink(FILES_PATH."/inwestycje/pomieszczenie/lista/".$powierzchnia->plik);

                    move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik);
                    $upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik;
                    $thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik;
                    $lista = FILES_PATH.'/inwestycje/pomieszczenie/lista/'.$plik;
                    chmod($upfile, 0755);
                    $options = array('jpegQuality' => 80);

                    PhpThumbFactory::create($upfile, $options)
                        ->resize(960, 960)
                        ->save($upfile)->resize(600, 600)
                        ->save($thumbs)->resize(180, 90)
                        ->save($lista);
                    chmod($thumbs, 0755);
                    chmod($lista, 0755);
                    $this->Room->update(array('plik' => $plik), 'id = '.$id);
                }

                if($_FILES['obrazek2']['size'] > 0) {
                    unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik2);
                    unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik2);

                    move_uploaded_file($_FILES['obrazek2']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik2);
                    $upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik2;
                    $thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik2;
                    chmod($upfile, 0755);
                    $options = array('jpegQuality' => 80);

                    PhpThumbFactory::create($upfile, $options)
                        ->resize(960, 960)
                        ->save($upfile)->resize(600, 600)
                        ->save($thumbs);
                    chmod($thumbs, 0755);
                    $this->Room->update(array('plik2' => $plik2), 'id = '.$id);
                }

                if($_FILES['obrazek3']['size'] > 0) {
                    unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik3);
                    unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik3);

                    move_uploaded_file($_FILES['obrazek3']['tmp_name'], FILES_PATH.'/inwestycje/pomieszczenie/'.$plik3);
                    $upfile = FILES_PATH.'/inwestycje/pomieszczenie/'.$plik3;
                    $thumbs = FILES_PATH.'/inwestycje/pomieszczenie/thumbs/'.$plik3;
                    chmod($upfile, 0755);
                    $options = array('jpegQuality' => 80);

                    PhpThumbFactory::create($upfile, $options)
                        ->resize(960, 960)
                        ->save($upfile)->resize(600, 600)
                        ->save($thumbs);
                    chmod($thumbs, 0755);
                    $this->Room->update(array('plik3' => $plik3), 'id = '.$id);
                }

                if($_FILES['pdf']['size'] > 0) {
                    unlink(FILES_PATH."/inwestycje/pdf/".$powierzchnia->pdf);
                    move_uploaded_file($_FILES['pdf']['tmp_name'], FILES_PATH.'/inwestycje/pdf/'.$plikpdf);
                    $upfile = FILES_PATH.'/inwestycje/pdf/'.$plikpdf;
                    chmod($upfile, 0755);
                    $this->Room->update(array('pdf' => $plikpdf), 'id = '.$id);
                }

                // Inwestycja osiedlow
                if($inwestycja->typ == 1) {
                    $this->_redirect('admin/inwestycje-budynek-pietro/show/id/'.$p.'/i/'.$i.'/b/'.$b.'/');
                }

                // Inwestycja budynkowa
                if($inwestycja->typ == 2) {
                    $this->_redirect('/admin/inwestycje-pietro/show/id/'.$p.'/i/'.$i.'/');
                }

                // Inwestycja z domkami
                if($inwestycja->typ == 3) {
                    $this->_redirect('/admin/inwestycje/show/id/'.$i.'/');
                }
            }
        }
    }

// Usun powierzchnie
    public function deleteAction() {
        // Odczytanie id
        $id = (int)$this->getRequest()->getParam('id');
        $i = (int)$this->getRequest()->getParam('i');
        $powierzchnia = $this->Room->find($id)->current();

        unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik);
        unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik);
        unlink(FILES_PATH."/inwestycje/pomieszczenie/lista/".$powierzchnia->plik);
        unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik2);
        unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik2);
        unlink(FILES_PATH."/inwestycje/pomieszczenie/".$powierzchnia->plik3);
        unlink(FILES_PATH."/inwestycje/pomieszczenie/thumbs/".$powierzchnia->plik3);

        $powierzchnia->delete();
        $this->_redirect('/admin/inwestycje/show/id/'.$i.'/');
    }
}
