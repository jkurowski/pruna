<?php
require_once 'kCMS/Thumbs/ThumbLib.inc.php';
class Admin_BoksyController extends kCMS_Admin
{
    private $redirect;
    private $model;
    private $form;

    function preDispatch() {
        $this->model = new Model_BoxModel();
        $this->form = new Form_BoxForm();

        $this->redirect = '/admin/boksy/';

        $back = '<div class="back"><a href="'.$this->redirect.'">Wróć do listy</a></div>';
        $info = '<div class="info">Obrazek o wymiarach: szerokość <b>'.$this->model::IMG_WIDTH.'</b>px / wysokość <b>'.$this->model::IMG_HEIGHT.'</b>px</div>';

        $array = array(
            'module' => 'boksy',
            'module_name' => 'Boksy z ikonkami',
            'info' => $info,
            'back' => $back
        );
        $this->view->assign($array);
    }

    function indexAction() {
        $array = array(
            'lista' => $this->model->fetchAll($this->model->select()->order('sort ASC'))
        );
        $this->view->assign($array);
    }

    function addAction() {
        $this->_helper->viewRenderer('form', null, true);

        $array = array(
            'pagename' => ' - Nowy wpis',
            'form' => $this->form
        );
        $this->view->assign($array);

        if ($this->_request->getPost()) {

            $formData = $this->_request->getPost();
            unset($formData['MAX_FILE_SIZE'], $formData['obrazek'], $formData['submit']);

            if ($this->form->isValid($formData)) {
                $id = $this->model->insert($formData);

                if($_FILES['obrazek']['size'] > 0) {
                    $file = $_FILES['obrazek']['name'];
                    $file_name = slugImg($formData['nazwa'], $file);

                    move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/box/'.$file_name);
                    $upload_file = FILES_PATH.'/box/'.$file_name;
                    chmod($upload_file, 0755);

//                    $options = array('jpegQuality' => 90);
//                    PhpThumbFactory::create($upload_file)
//                        ->adaptiveResizeQuadrant($this->model::IMG_WIDTH, $this->model::IMG_HEIGHT)
//                        ->save($upload_file);
//                    chmod($upload_file, 0755);

                    $data = array('plik' => $file_name);
                    $this->model->update($data, 'id = ' . $id);
                }

                $this->redirect($this->redirect);
            } else {
                $array = array(
                    'message' => '<div class="error">Formularz zawiera błędy</div>'
                );
                $this->view->assign($array);
            }
        }
    }

    function editAction() {
        $this->_helper->viewRenderer('form', null, true);

        $array = array(
            'pagename' => ' - Edytuj wpis',
            'form' => $this->form
        );
        $this->view->assign($array);

        $id = (int)$this->_request->getParam('id');
        $entry = $this->model->find($id)->current();
        $this->form->populate($entry->toArray());

        if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();
            unset($formData['MAX_FILE_SIZE'], $formData['obrazek'], $formData['submit']);

            if ($this->form->isValid($formData)) {

                $this->model->update($formData, 'id = '.$id);

                if($_FILES['obrazek']['size'] > 0) {
                    $file = $_FILES['obrazek']['name'];
                    $file_name = slugImg($formData['nazwa'], $file);

                    unlink(FILES_PATH."/box/".$entry->plik);

                    move_uploaded_file($_FILES['obrazek']['tmp_name'], FILES_PATH.'/box/'.$file_name);
                    $upload_file = FILES_PATH.'/box/'.$file_name;
                    chmod($upload_file, 0755);

//                    $options = array('jpegQuality' => 90);
//                    PhpThumbFactory::create($upload_file)
//                        ->adaptiveResizeQuadrant($this->model::IMG_WIDTH, $this->model::IMG_HEIGHT)
//                        ->save($upload_file);
//                    chmod($upload_file, 0755);

                    $data = array('plik' => $file_name);
                    $this->model->update($data, 'id = ' . $id);
                }

                $this->redirect($this->redirect);
            } else {

                $array = array(
                    'message' => '<div class="error">Formularz zawiera błędy</div>'
                );
                $this->view->assign($array);
            }
        }
    }

    function deleteAction() {
        $id = (int)$this->_request->getParam('id');
        $entry = $this->model->find($id)->current();
        $entry->delete();
        $this->redirect($this->redirect);
    }

    function sortAction() {
        $updateRecordsArray = $_POST['recordsArray'];
        $listingCounter = 1;
        foreach ($updateRecordsArray as $recordIDValue) {
            $data = array('sort' => $listingCounter);
            $this->model->update($data, 'id = '.$recordIDValue);
            $listingCounter = $listingCounter + 1;
        }
    }
}