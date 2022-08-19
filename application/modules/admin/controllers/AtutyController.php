<?php
class Admin_AtutyController extends kCMS_Admin
{
    private $redirect;
    private $table;
    private $model;
    private $form;

    function preDispatch() {
        $this->model = new Model_AtutModel();
        $this->form = new Form_AtutForm();

        $controlname = "Atuty inwestycji";
        $back = '<div class="back"><a href="'.$this->view->baseUrl().'/admin/atuty/">Wróć do listy</a></div>';
        $this->redirect = 'admin/atuty';
        $this->table = 'atuty';
        $array = array(
            'controlname' => $controlname,
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
            'pagename' => " - Nowy wpis",
            'form' => $this->form
        );
        $this->view->assign($array);

        if ($this->_request->getPost()) {

            $formData = $this->_request->getPost();
            unset($formData['MAX_FILE_SIZE']);
            unset($formData['submit']);

            if ($this->form->isValid($formData)) {

                $this->model->insert($formData);
                $this->_redirect($this->redirect);
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
            'pagename' => " - Edytuj wpis",
            'form' => $this->form
        );
        $this->view->assign($array);

        $id = (int)$this->_request->getParam('id');
        $entry = $this->model->find($id)->current();
        $this->form->populate($entry->toArray());

        if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();
            unset($formData['MAX_FILE_SIZE']);
            unset($formData['submit']);

            if ($this->form->isValid($formData)) {

                $this->model->update($formData, 'id = '.$id);
                $this->_redirect($this->redirect);
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

        $this->_redirect($this->redirect);
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