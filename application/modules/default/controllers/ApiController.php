<?php
class Default_ApiController extends kCMS_Site
{
	public function allroomsAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$this->getResponse()->setHeader('Content-Type', 'application/json');
		$db = Zend_Registry::get('db');
		
		$inwest_id = (int)$this->getRequest()->getParam('inwest_id');

		$select_pomieszczenia = $db->select()
		->from('inwestycje_powierzchnia', array('id', 'numer', 'id_inwest', 'numer_pietro', 'id_budynek', 'numer_budynek', 'metry', 'pokoje', 'cena', 'szukaj_cena', 'status', 'pdf', 'plik', 'nazwa'))
		->where('id_inwest = ?', $inwest_id);
		$select_pomieszczenia = $db->fetchAll($select_pomieszczenia);

		$json = json_encode($select_pomieszczenia); 
		print_r($json);
	}

	public function roomAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		$this->getResponse()->setHeader('Content-Type', 'application/json');
		$db = Zend_Registry::get('db');
		$id = (int)$this->getRequest()->getParam('id');

		$select_pomieszczenie = $db->select()
		->from(array('p' => 'inwestycje_powierzchnia'), array('id', 'status', 'cena', 'szukaj_cena'))
		->where('p.id = ?', $id);
		$select_pomieszczenie = $db->fetchRow($select_pomieszczenie);

		$json = json_encode($select_pomieszczenie); 
		print_r($json);
	}
	
	public function updateroomAction() {
		$this->getHelper('Layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$formData = $this->_request->getPost();
		$room_id = $formData['id'];
		unset($formData['key']);
		unset($formData['id']);

		$db = Zend_Registry::get('db');
		$db->update('inwestycje_powierzchnia', $formData, 'id = '.$room_id);
		echo 'Zapisałem zmiany dla mieszkania id: '.$room_id;

		header('WWW-Authenticate: Basic realm="Akuku Panie Kruku"');
		header('HTTP/1.0 401 Unauthorized');
		exit;
	}

}