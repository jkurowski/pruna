<?php
require_once 'kCMS/Thumbs/ThumbLib.inc.php';
class Admin_GaleriaController extends kCMS_Admin
{
    private $Gallery;
    private $GalleryForm;
    private $Photo;

    public function preDispatch() {
        $this->Gallery = new Model_GalleryModel();
        $this->GalleryForm = new Form_GaleriaForm();
        $this->Photo = new Model_PhotoModel();

        $array = array(
            'controlname' => 'Galeria'
        );
        $this->view->assign($array);
    }

// List
    public function indexAction() {
        $array = array(
            'katalog' => $this->Gallery->fetchAll($this->Gallery->select()->order('sort ASC'))
        );
        $this->view->assign($array);
    }

// Show
    public function showAction() {
        $array = array(
            'gallery_id' => $id = (int)$this->getRequest()->getParam('id'),
            'gallery' => $this->Gallery->find($id)->current(),
            'photos' => $this->Photo->fetchAll($this->Photo->select()->order('sort ASC')->where('id_gal =?', $id))
        );
        $this->view->assign($array);
    }

// New
    public function addAction() {
        $this->_helper->viewRenderer('form', null, true);

        $array = array(
            'back' => '<div class="back"><a href="/admin/galeria/">Wróć do listy galerii</a></div>',
            //'info' => '<div class="info">Obrazek o wymiarach: szerokość <b>'.$this->Gallery::IMG_WIDTH.'</b>px / wysokość <b>'.$this->Gallery::IMG_HEIGHT.'</b>px</div>',
            'pagename' => ' - Nowa galeria',
            'form' => $this->GalleryForm
        );

        $this->view->assign($array);

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            //Odczytanie wartosci z inputów
            $formData = $this->_request->getPost();
            unset($formData['MAX_FILE_SIZE']);
            unset($formData['obrazek']);
            unset($formData['submit']);

            //Sprawdzenie poprawnosci forma
            if ($this->GalleryForm->isValid($formData)) {

                $formData['slug'] = slug($formData['nazwa']);
                $lastId = $this->Gallery->insert($formData);

                if($_FILES['obrazek']['size'] > 0) {
                    $obrazek = $_FILES['obrazek']['name'];
                    $plik = slugImg($formData['nazwa'], $obrazek);

                    move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/galeria/'.$plik);
                    $upload_file = FILES_PATH.'/galeria/'.$plik;
                    chmod($upload_file, 0755);

                    $options = array('jpegQuality' => 90);
                    PhpThumbFactory::create($upload_file, $options)
                        ->adaptiveResizeQuadrant($this->Gallery::IMG_WIDTH, $this->Gallery::IMG_HEIGHT)
                        ->save($upload_file);

                    chmod($upload_file, 0755);

                    $dataImg = array('plik' => $plik);
                    $this->Gallery->update($dataImg, 'id = ' . $lastId);
                }

                $this->_redirect('/admin/galeria/');

            } else {

                //Wyswietl bledy
                $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                $this->GalleryForm->populate($formData);

            }
        }
    }

// Edit
    public function editAction() {
        $this->_helper->viewRenderer('form', null, true);

        $array = array(
            'back' => '<div class="back"><a href="/admin/galeria/">Wróć do listy galerii</a></div>',
            //'info' => '<div class="info">Obrazek o wymiarach: szerokość <b>'.$this->Gallery::IMG_WIDTH.'</b>px / wysokość <b>'.$this->Gallery::IMG_HEIGHT.'</b>px</div>',
            'pagename' => ' - Edytuj galerię',
            'form' => $this->GalleryForm
        );

        $this->view->assign($array);

        // Odczytanie id
        $id = (int)$this->getRequest()->getParam('id');
        $entry = $this->Gallery->find($id)->current();

        // Zaladowanie do forma
        $this->GalleryForm->populate($entry->toArray());

        //Akcja po wcisnieciu Submita
        if ($this->_request->getPost()) {

            //Odczytanie wartosci z inputów
            $formData = $this->_request->getPost();
            unset($formData['MAX_FILE_SIZE']);
            unset($formData['obrazek']);
            unset($formData['submit']);

            //Sprawdzenie poprawnosci forma
            if ($this->GalleryForm->isValid($formData)) {

                $formData['slug'] = slug($formData['nazwa']);
                $this->Gallery->update($formData, 'id = '.$id);
                $this->_redirect('/admin/galeria/');

            } else {

                //Wyswietl bledy
                $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                $this->GalleryForm->populate($formData);

            }
        }
    }

// Delete gallery
    public function deleteAction() {
        $db = Zend_Registry::get('db');
        $id = (int)$this->_request->getParam('id');
        $count = $db->fetchAll($db->select()->from('galeria_zdjecia')->where('id_gal = ?',$id));
        foreach($count as $element) {

            unlink(FILES_PATH."/galeria/big/".$element->plik);
            unlink(FILES_PATH."/galeria/thumbs/".$element->plik);
            $db->delete('galeria_zdjecia', $db->quoteInto('id = ?', $element->id));

        }

        $gallery = $this->Gallery->find($id)->current();
        unlink(FILES_PATH."/galeria/".$gallery->plik);
        $gallery->delete();

        $this->_redirect('/admin/galeria/');
    }

// Ustaw kolejność
    public function sortAction() {
        $db = Zend_Registry::get('db');
        $table = $this->_request->getParam('co');
        $updateRecordsArray = $_POST['recordsArray'];
        $listingCounter = 1;
        foreach ($updateRecordsArray as $recordIDValue) {
            $db->update($table, array('sort' => $listingCounter), 'id = '.$recordIDValue);
            $listingCounter = $listingCounter + 1;
        }
    }

################################################ PRODUKTY/ZDJĘCIA ################################################

// Upload obrazka
		public function uploadAction() {
			$this->_helper->layout()->disableLayout(); 
			$this->_helper->viewRenderer->setNoRender(true);
			$id = (int)$this->getRequest()->getParam('id');

			$db = Zend_Registry::get('db');

            $katalog = $db->fetchRow($db->select()->from('galeria')->where('id = ?',$id));

			$obrazek = $_FILES['qqfile']['name'];
            if($_FILES['qqfile']['size'] > 0) {
                $plik = time()."-".rand(1000, 9999)."-".slugImg($katalog->nazwa, $obrazek);
            }

			if (move_uploaded_file($_FILES['qqfile']['tmp_name'], FILES_PATH.'/galeria/big/'.$plik)) {
				$upfile = FILES_PATH.'/galeria/big/'.$plik;
				$thumbs = FILES_PATH.'/galeria/thumbs/'.$plik;
				chmod($upfile, 0755);

				$data = array(
				    'plik' => $plik,
                    'id_gal' => $id,
                    'nazwa' => $katalog->nazwa
                );

                $options = array('jpegQuality' => 80);
                $options2 = array('jpegQuality' => 60);

				PhpThumbFactory::create($upfile, $options)
                    ->resize(1024, 1024)
                    ->save($upfile);

				PhpThumbFactory::create($upfile, $options2)
                    ->adaptiveResizeQuadrant(480, 360, 'B')
                    ->save($thumbs);

				$db->insert('galeria_zdjecia', $data);

                echo Zend_Json_Encoder::encode(array(
                    'success' => true,
                    'data' => array(
                        'success' => true
                    )
                ));
			}
		}

// Usun zdjecie
		public function usunObrazekAction() {
			$db = Zend_Registry::get('db');

			// Odczytanie id obrazka
			$id = (int)$this->getRequest()->getParam('id');
			$pic = $db->fetchRow($db->select()->from('galeria_zdjecia')->where('id = ?',$id));
			
			unlink(FILES_PATH."/galeria/".$pic->plik);
			unlink(FILES_PATH."/galeria/thumbs/".$pic->plik);

			$where = $db->quoteInto('id = ?', $id);
			$db->delete('galeria_zdjecia', $where);
			$this->_redirect('/admin/galeria/show/id/'.$pic->id_gal.'/');
		}

// Usun kilka zdjęć
		public function kilkaAction() {
			$db = Zend_Registry::get('db');
			$checkbox = $_POST[checkbox];
			for($i=0;$i<count($_POST[checkbox]);$i++){
				$id = $checkbox[$i];
				$pic = $db->fetchRow($db->select()->from('galeria_zdjecia')->where('id = ?',$id));

				unlink(FILES_PATH."/galeria/".$pic->plik);
				unlink(FILES_PATH."/galeria/thumbs/".$pic->plik);

				$where = $db->quoteInto('id = ?', $id);
				$db->delete('galeria_zdjecia', $where);
			}
			$this->_redirect('/admin/galeria/show/id/'.$pic->id_gal.'/');
	}
}