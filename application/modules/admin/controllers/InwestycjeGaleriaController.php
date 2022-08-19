<?php

require_once 'kCMS/Thumbs/ThumbLib.inc.php';

class Admin_InwestycjeGaleriaController extends kCMS_Admin
{
// Upload obrazka
    public function uploadAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $id = (int)$this->getRequest()->getParam('id');

        $db = Zend_Registry::get('db');

        $inwestycja = $db->fetchRow($db->select()->from('inwestycje')->where('id = ?',$id));

        $obrazek = $_FILES['qqfile']['name'];
        if($_FILES['qqfile']['size'] > 0) {
            $plik = time()."-".rand(1000, 9999)."-".slugImg($inwestycja->nazwa, $obrazek);
        }

        if (move_uploaded_file($_FILES['qqfile']['tmp_name'], FILES_PATH.'/inwestycje/galeria/'.$plik)) {
            $upfile = FILES_PATH.'/inwestycje/galeria/'.$plik;
            chmod($upfile, 0755);

            $data = array('plik' => $plik, 'inwest_id' => $id);
            $options = array('jpegQuality' => 80);

            PhpThumbFactory::create($upfile, $options)
                ->adaptiveResizeQuadrant(600, 800, 'B')
                ->save($upfile);

            $db->insert('inwestycje_galeria', $data);

            $response = array("success" => true);
            header("Content-Type: text/plain");
            echo Zend_Json::encode($response);
        }
    }

// Ustaw kolejność
    public function ustawAction() {
        $db = Zend_Registry::get('db');
        $table = $this->_request->getParam('co');
        $updateRecordsArray = $_POST['recordsArray'];
        $listingCounter = 1;
        foreach ($updateRecordsArray as $recordIDValue) {
            $data = array('sort' => $listingCounter);
            $db->update($table, $data, 'id = '.$recordIDValue);
            $listingCounter = $listingCounter + 1;
        }
    }

// Usun zdjecie
    public function usunObrazekAction() {
        $db = Zend_Registry::get('db');

        // Odczytanie id obrazka
        $id = (int)$this->getRequest()->getParam('id');
        $pic = $db->fetchRow($db->select()->from('inwestycje_galeria')->where('id = ?',$id));

        unlink(FILES_PATH."/inwestycje/galeria/".$pic->plik);

        $where = $db->quoteInto('id = ?', $id);
        $db->delete('inwestycje_galeria', $where);

        $this->_redirect('admin/inwestycje/edit/id/'.$pic->inwest_id.'/#gallery');
    }

}
