<?php

class Admin_StatystykiController extends kCMS_Admin
{
		public function preDispatch() {
			$this->view->controlname = "Statystyki";
			
			
			$user = Zend_Auth::getInstance()->getIdentity();
			if($user->role == 'user') { return $this->_redirect('/admin/inwestycje/'); }
			
		}
		
		public function cleanAction() {
			$db = Zend_Registry::get('db');
			$db->query('TRUNCATE TABLE statystyki');
			$this->_redirect('/admin/statystyki/');
		}
		
		public function indexAction() {
			$db = Zend_Registry::get('db');
		
			$this->view->etap = $id_inwest = $this->getRequest()->getParam('id_inwest');
		
			$select_statusy = $db->select()
			->from('inwestycje_powierzchnia', array('status',' count(inwestycje_powierzchnia.status) as num'))
			->group('status');
			
			
			if($id_inwest ){
				$select_statusy->where("id_inwest = ?", $id_inwest);
			}
			
			$statusy = $this->view->statusy = $db->fetchAll($select_statusy);
			
			foreach($statusy as $status){
				if($status->status == 1){ $this->view->dostepne = $status->num; } 
				if($status->status == 2){ $this->view->sprzedane = $status->num; } 
				if($status->status == 3){ $this->view->rezerwacja = $status->num; } 
			}
			
			$this->view->inwestlista = $db->fetchAll($db->select()->from('inwestycje')->order('sort ASC'));
		}
		
		public function formularzeAction() {
			$db = Zend_Registry::get('db');
		
			$this->view->data_start = $data_start = $this->getRequest()->getParam('data_start');
			$this->view->data_end = $data_end = $this->getRequest()->getParam('data_end');
			$this->view->etap = $id_inwest = $this->getRequest()->getParam('id_inwest');
			
			$time_end  = strtotime($data_end);
			$day_end   = date('d', $time_end);
			$month_end = date('m', $time_end);
			$year_end  = date('Y', $time_end);
			
			$time_start  = strtotime($data_start);
			$day_start   = date('d', $time_start);
			$month_start = date('m', $time_start);
			$year_start  = date('Y', $time_start);
			
			$this->view->inwestlista = $db->fetchAll($db->select()->from('inwestycje')->order('sort ASC'));
			$wszystkie = $db->select()->from('statystyki')->where('akcja =?', 1)->where('miejsce =?', 3);
			
			$query = $db->select()
			->from('statystyki', array('miejsce', 'akcja', 'id', 'godz', 'count(id) as num'))
			->where('miejsce = ?', 4);

			if($data_start && $data_end){
				$query->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$query->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}

			$query->orWhere('miejsce = ?', 3);

			if($data_start && $data_end){
				$query->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$query->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}

			$query->where('akcja = ?', 1)->order('num DESC')->group('godz');
			$this->view->godzinowo = $db->fetchAll($query);

			if($id_inwest ){
				$wszystkie->where("id_inwest = ?", $id_inwest);
			}
			
			if($data_start && $data_end){
				$wszystkie->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$wszystkie->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}
			$this->view->wszystkie = count($db->fetchAll($wszystkie));
			
			$kontakt = $db->select()->from('statystyki')->where('akcja =?', 1)->where('miejsce =?', 4);
			
			if($data_start && $data_end){
				$kontakt->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$kontakt->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}
			
			if($id_inwest ){
				$kontakt->where("id_inwest = ?", $id_inwest);
			}
			
			$this->view->kontakt = count($db->fetchAll($kontakt));
		
			$select_statusy = $db->select()
			->from('statystyki', array('akcja', 'mieszkanie', ' count(mieszkanie) as num'))
			->limit(20)
			->where('akcja =?', 1)->where('miejsce =?', 3)
			->order('num DESC')
			->group('mieszkanie');
			
			if($data_start && $data_end){
				$select_statusy->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$select_statusy->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}
			
			if($id_inwest ){
				$select_statusy->where("id_inwest = ?", $id_inwest);
			}
			
			$this->view->mieszkania = $db->fetchAll($select_statusy);
			
			$select_statusy2 = $db->select()
			->from('statystyki', array('akcja', 'mieszkanie', ' count(mieszkanie) as num'))
			->limit(20)
			->where('akcja =?', 1)->where('miejsce =?', 3)
			->order('num ASC')
			->group('mieszkanie');
			
			if($data_start && $data_end){
				$select_statusy2->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$select_statusy2->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}
			
			if($id_inwest ){
				$select_statusy2->where("id_inwest = ?", $id_inwest);
			}
			
			$this->view->mieszkania2 = $db->fetchAll($select_statusy2);
		}
		
		public function mieszkaniaAction() {
			$db = Zend_Registry::get('db');
		
			$this->view->data_start = $data_start = $this->getRequest()->getParam('data_start');
			$this->view->data_end = $data_end = $this->getRequest()->getParam('data_end');
			$this->view->etap = $id_inwest = $this->getRequest()->getParam('id_inwest');
			$this->view->inwestlista = $db->fetchAll($db->select()->from('inwestycje')->order('sort ASC'));
			
			$time_end  = strtotime($data_end);
			$day_end   = date('d', $time_end);
			$month_end = date('m', $time_end);
			$year_end  = date('Y', $time_end);
			
			$time_start  = strtotime($data_start);
			$day_start   = date('d', $time_start);
			$month_start = date('m', $time_start);
			$year_start  = date('Y', $time_start);
		
			$select_statusy = $db->select()
			->from('statystyki', array('mieszkanie', ' count(mieszkanie) as num'))
			->limit(20)->where('akcja =?', 3)
			->order('num DESC')
			->group('mieszkanie');
			
			if($data_start && $data_end){
				$select_statusy->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$select_statusy->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}
			
			if($id_inwest ){
				$select_statusy->where("id_inwest = ?", $id_inwest);
			}
			
			$this->view->mieszkania = $db->fetchAll($select_statusy);
			
			$select_statusy2 = $db->select()
			->from('statystyki', array('mieszkanie', ' count(mieszkanie) as num'))
			->limit(20)->where('akcja =?', 3)
			->order('num ASC')
			->group('mieszkanie');
			
			if($data_start && $data_end){
				$select_statusy2->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$select_statusy2->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}
			
			if($id_inwest ){
				$select_statusy2->where("id_inwest = ?", $id_inwest);
			}
			
			$this->view->mieszkania2 = $db->fetchAll($select_statusy2);
			
		}
		
		public function kontaktAction() {
			$db = Zend_Registry::get('db');
		
			$this->view->data_start = $data_start = $this->getRequest()->getParam('data_start');
			$this->view->data_end = $data_end = $this->getRequest()->getParam('data_end');

			$time_end  = strtotime($data_end);
			$day_end   = date('d', $time_end);
			$month_end = date('m', $time_end);
			$year_end  = date('Y', $time_end);
			
			$time_start  = strtotime($data_start);
			$day_start   = date('d', $time_start);
			$month_start = date('m', $time_start);
			$year_start  = date('Y', $time_start);
			
			$query = $db->select()
			->from('statystyki')
			->where('miejsce = ?', 4);
			
			if($data_start && $data_end){
				$query->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$query->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}
			$query->where('akcja = ?', 1);
			$query->orWhere('miejsce = ?', 3);
			
			if($data_start && $data_end){
				$query->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$query->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}		
			$query->where('akcja = ?', 1);
			$query->orWhere('miejsce = ?', 5);
			
			if($data_start && $data_end){
				$query->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$query->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}
			$query->where('akcja = ?', 1);
			$query->order('id DESC');

			
			// echo '<pre>';
			// print_r($query);
			// echo '</pre>';
			
			$this->view->lista = $db->fetchAll($query);			
		}
				
		public function wyszukiwanieAction() {
			$db = Zend_Registry::get('db');
		
			$this->view->data_start = $data_start = $this->getRequest()->getParam('data_start');
			$this->view->data_end = $data_end = $this->getRequest()->getParam('data_end');
			$this->view->etap = $id_inwest = $this->getRequest()->getParam('id_inwest');
			$this->view->inwestlista = $db->fetchAll($db->select()->from('inwestycje')->order('sort ASC'));
			
			$time_end  = strtotime($data_end);
			$day_end   = date('d', $time_end);
			$month_end = date('m', $time_end);
			$year_end  = date('Y', $time_end);
			
			$time_start  = strtotime($data_start);
			$day_start   = date('d', $time_start);
			$month_start = date('m', $time_start);
			$year_start  = date('Y', $time_start);
		
			$select_statusy = $db->select()
			->from('statystyki', array('pokoje', ' count(pokoje) as num'))
			->limit(20)->where('akcja =?', 2)
			->order('num DESC')
			->group('pokoje');
			
			if($data_start && $data_end){
				$select_statusy->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$select_statusy->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}
			
			if($id_inwest ){
				$select_statusy->where("id_inwest = ?", $id_inwest);
			}
			
			$this->view->pokoje = $db->fetchAll($select_statusy);
			
			$select_statusy2 = $db->select()
			->from('statystyki', array('kuchnia_aneks', ' count(kuchnia_aneks) as num'))
			->where('akcja =?', 2)
			->order('num DESC')
			->group('kuchnia_aneks');
			
			if($data_start && $data_end){
				$select_statusy2->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$select_statusy2->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}
			
			if($id_inwest ){
				$select_statusy2->where("id_inwest = ?", $id_inwest);
			}
			
			$this->view->kuchnia_aneks = $db->fetchAll($select_statusy2);
			
			$select_statusy3 = $db->select()
			->from('statystyki', array('pietro', ' count(pietro) as num'))
			->where('akcja =?', 2)
			->order('num DESC')
			->group('pietro');
			
			if($data_start && $data_end){
				$select_statusy3->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$select_statusy3->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}
			
			if($id_inwest ){
				$select_statusy3->where("id_inwest = ?", $id_inwest);
			}
			
			$this->view->pietro = $db->fetchAll($select_statusy3);
			
			
			$select_statusy4 = $db->select()
			->from('statystyki', array('pow_od', 'pod_do', ' count(*) as num'))
			->where('akcja =?', 2)
			->order('num DESC')
			->group(array('pow_od', 'pod_do'));
			
			if($data_start && $data_end){
				$select_statusy4->where("dzien >= ?", $day_start)->where("msc >= ?", $month_start)->where("rok >= ?", $year_start);
				$select_statusy4->where("dzien <= ?", $day_end)->where("msc <= ?", $month_end)->where("rok <= ?", $year_end);
			}
			
			if($id_inwest ){
				$select_statusy4->where("id_inwest = ?", $id_inwest);
			}
			
			$this->view->pow = $db->fetchAll($select_statusy4);
			
		}
		
		public function maileAction() {
			$db = Zend_Registry::get('db');
			
			$wszystkie = $db->select()->from('statystyki', array('tekst'))->where('tekst IS NOT NULL');
			$maile = $db->fetchAll($wszystkie);
			// echo count($maile);
			// echo '<pre>';
			//print_r($maile);

			$items = array();
			foreach($maile as $mail) {
				$pattern = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
				preg_match_all($pattern, $mail->tekst, $matches);
				if($matches[0]){
					$items[] = $matches[0][0];
				}
			}
			$result = array_unique($items);

			foreach($result as $adres) {
				$csv_output .= $adres.";".$adres."\r\n";
			}
				$filename = "newsletter_".date("Y-m-d_H-i",time());
				header("Content-type: text/csv");
				header("Content-disposition: filename=".$filename.".csv");
				print $csv_output;
				exit;	
	
		}
		
		public function usunWiadomoscAction() {
			$db = Zend_Registry::get('db');
			
			$id = (int)$this->_request->getParam('id');
			$where = $db->quoteInto('id = ?', $id);
			$db->delete('statystyki', $where);
			
			$this->_redirect('/admin/statystyki/kontakt/');
		}
}