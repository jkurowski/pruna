<?php
require_once 'kCMS/Thumbs/ThumbLib.inc.php';
class Admin_SliderController extends kCMS_Admin
{
    private $redirect;
    private $table;
    private $Slider;

    public function preDispatch() {
            $this->Slider= new Model_SliderModel();

			$controlname = "Slider";
            $back = '<div class="back"><a href="'.$this->view->baseUrl().'/admin/slider/">Wróć do listy paneli</a></div>';
            $info = '<div class="info">Obrazek o wymiarach: szerokość <b>'.$this->Slider::IMG_WIDTH.'</b>px / wysokość <b>'.$this->Slider::IMG_HEIGHT.'</b>px</div>';
			$this->redirect = 'admin/slider';
            $this->table = 'slider';
            $array = array(
                'controlname' => $controlname,
                'back' => $back,
                'info' => $info,
            );
            $this->view->assign($array);
		}
		
// Pokaz wszystkie panele
		public function indexAction() {
            $array = array(
                'lista' => $this->Slider->fetchAll($this->Slider->select()->order('sort ASC'))
            );
            $this->view->assign($array);
		}

// Dodaj nowy panel
		function nowyAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);
			$this->view->pagename = " - Dodaj panel";

			$form = new Form_SliderForm();
			$this->view->form = $form;

            //Akcja po wcisnieciu Submita
            if ($this->_request->getPost()) {

                //Odczytanie wartosci z inputów
                $formData = $this->_request->getPost();
                unset($formData['MAX_FILE_SIZE']);
                unset($formData['obrazek']);
                unset($formData['submit']);

                //Sprawdzenie poprawnosci forma
                if ($form->isValid($formData)) {

                    $db->insert($this->table, $formData);
                    $lastId = $db->lastInsertId();

                    if($_FILES['obrazek']['size'] > 0) {
                        $obrazek = $_FILES['obrazek']['name'];
                        $plik = slugImg($formData['tytul'], $obrazek);

                        move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/slider/'.$plik);
                        $upfile = FILES_PATH.'/slider/'.$plik;
                        $thumb = FILES_PATH.'/slider/thumbs/'.$plik;
                        chmod($upfile, 0755);

                        $options = array('jpegQuality' => 90);
                        PhpThumbFactory::create($upfile, $options)
                        ->adaptiveResizeQuadrant($this->Slider::IMG_WIDTH, $this->Slider::IMG_HEIGHT)
                        ->save($upfile);

                        PhpThumbFactory::create($upfile, $options)
                            ->resize(159, 159)
                            ->save($thumb);
                        chmod($upfile, 0755);
                        chmod($thumb, 0755);

                        $dataImg = array('plik' => $plik);
                        $db->update($this->table, $dataImg, 'id = ' . $lastId);
                    }

                    $this->_redirect($this->redirect);
                } else {
                    //Wyswietl bledy
                    $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
                }
            }
		}

// Edytuj panel
		function edytujAction() {
			$db = Zend_Registry::get('db');
			$this->_helper->viewRenderer('form', null, true);

			// Odczytanie id
			$id = (int)$this->_request->getParam('id');
			$slider = $db->fetchRow($db->select()->from($this->table)->where('id = ?', $id));

			$this->view->pagename = " - Edytuj panel: ".$slider->tytul;

			$form = new Form_SliderForm();
			$this->view->form = $form;

			// Załadowanie do forma
			$array = json_decode(json_encode($slider), true);
			if($array){
			    $form->populate($array);
			}

			if ($this->_request->isPost()) {

				//Odczytanie wartosci z inputów
				$formData = $this->_request->getPost();
				unset($formData['MAX_FILE_SIZE']);
				unset($formData['obrazek']);
				unset($formData['submit']);

                //Sprawdzenie poprawnosci forma
                if ($form->isValid($formData)) {

                    $db->update($this->table, $formData, 'id = '.$id);

                    if($_FILES['obrazek']['size'] > 0) {
                        $obrazek = $_FILES['obrazek']['name'];
                        $plik = slugImg($formData['tytul'], $obrazek);

                        unlink(FILES_PATH."/slider/".$slider->plik);
                        unlink(FILES_PATH."/slider/thumbs/".$slider->plik);

                        move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/slider/'.$plik);
                        $upfile = FILES_PATH.'/slider/'.$plik;
                        $thumb = FILES_PATH.'/slider/thumbs/'.$plik;
                        chmod($upfile, 0755);

                        $options = array('jpegQuality' => 90);
                        PhpThumbFactory::create($upfile, $options)
                        ->adaptiveResizeQuadrant($this->Slider::IMG_WIDTH, $this->Slider::IMG_HEIGHT)
                        ->save($upfile);

                        PhpThumbFactory::create($upfile, $options)
                        ->resize(159, 159)
                        ->save($thumb);
                        chmod($upfile, 0755);
                        chmod($thumb, 0755);

                        $dataImg = array('plik' => $plik);
                        $db->update($this->table, $dataImg, 'id = ' . $id);
                    }

                    $this->_redirect($this->redirect);
                } else {

                    //Wyswietl bledy
                    $this->view->message = '<div class="error">Formularz zawiera błędy</div>';

                }
			}
		}

// Usuń panel
		function usunAction() {
			$db = Zend_Registry::get('db');
			// Odczytanie id obrazka
			$id = (int)$this->_request->getParam('id');
			$slider = $db->fetchRow($db->select()->from($this->table)->where('id = ?', $id));
							
			unlink(FILES_PATH."/slider/".$slider->plik);
			unlink(FILES_PATH."/slider/thumbs/".$slider->plik);

			$where = $db->quoteInto('id = ?', $id);
			$db->delete($this->table, $where);

			$this->_redirect($this->redirect);
		}

// Ustaw kolejność
		public function ustawAction() {
			$db = Zend_Registry::get('db');
			$updateRecordsArray = $_POST['recordsArray'];
			$listingCounter = 1;
			foreach ($updateRecordsArray as $recordIDValue) {
				$data = array('sort' => $listingCounter);
				$db->update($this->table, $data, 'id = '.$recordIDValue);
				$listingCounter = $listingCounter + 1;
				}
		}

// Usun kilka paneli
		public function kilkaAction() {
			$db = Zend_Registry::get('db');
			$checkbox = $_POST[checkbox];
			for($i=0;$i<count($_POST[checkbox]);$i++){
				$id = $checkbox[$i];
				$where = $db->quoteInto('id = ?', $id);
				$slider = $db->fetchRow($db->select()->from('slider')->where('id = ?', $id));
				
				unlink(FILES_PATH."/slider/".$slider->plik);
				unlink(FILES_PATH."/slider/thumbs/".$slider->plik);
							
				$db->delete($this->table, $where);
			}
			$this->_redirect($this->redirect);
	}
	
// Ustawienia slidera
        public function ustawieniaAction() {
            $db = Zend_Registry::get('db');

            $form = new Form_SliderUstawieniaForm();
            $this->view->form = $form;

            // Polskie tlumaczenie errorów
            $polish = kCMS_Polish::getPolishTranslation();
            $translate = new Zend_Translate('array', $polish, 'pl');
            $form->setTranslator($translate);

            $form->getElement('speed')->getDecorator('label')->setOption('escape', false);
            $form->getElement('timeout')->getDecorator('label')->setOption('escape', false);

            $ustawienia = $db->fetchRow($db->select()->from('ustawienia'));

            $form->auto->setvalue($ustawienia->slider_auto);
            $form->pause->setvalue($ustawienia->slider_pause);
            $form->nav->setvalue($ustawienia->slider_nav);
            $form->pager->setvalue($ustawienia->slider_pager);
            $form->speed->setvalue($ustawienia->slider_speed);
            $form->timeout->setvalue($ustawienia->slider_timeout);
            $form->efekt->setvalue($ustawienia->slider_efekt);

            //Akcja po wcisnieciu Submita
            if ($this->_request->getPost()) {

                //Odczytanie wartosci z inputów $auto, $pause, $nav, $pager, $speed, $timeout
                $auto = $this->_request->getPost('auto');
                $pause = $this->_request->getPost('pause');
                $nav = $this->_request->getPost('nav');
                $pager = $this->_request->getPost('pager');
                $speed = $this->_request->getPost('speed');
                $timeout = $this->_request->getPost('timeout');
                $efekt = $this->_request->getPost('efekt');
                $formData = $this->_request->getPost();

                //Sprawdzenie poprawnosci forma
                if ($form->isValid($formData)) {

                    $data = array(
                    'slider_auto' => $auto,
                    'slider_pause' => $pause,
                    'slider_nav' => $nav,
                    'slider_pager' => $pager,
                    'slider_speed' => $speed,
                    'slider_timeout' => $timeout,
                    'slider_efekt' => $efekt,
                    );

                }

                $db->update('ustawienia', $data);
                $this->_redirect('/admin/slider/ustawienia/');
            } else {

                //Wyswietl bledy
                $this->view->message = '<div class="error">Formularz zawiera błędy</div>';
            }
        }
}