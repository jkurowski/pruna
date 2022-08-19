<?php

class Default_InwestycjeWynikiController extends kCMS_Site
{
    private $page_id;
    private $Page;
    private $Room;
    private $Investment;

    public function preDispatch() {
        $this->page_id = 16;
        $this->_helper->layout->setLayout('page');
        $this->Page = new Model_MenuModel();
        $this->Room = new Model_RoomModel();
        $this->Investment = new Model_InvestmentModel();
    }

    public function indexAction()
    {
        $page = $this->Page->getPageById($this->page_id);

        if(!$page) {
            errorPage();
        } else {
            $pageName = (isset($page->nazwa)) ? $page->nazwa : json_decode($page->json)->nazwa;
            $breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$pageName .'</b><meta itemprop="position" content="2" /></li>';

            $powierzchniaQuery = $this->Room->select();

            $s_status = $this->view->s_status = (int)$this->_request->getParam('s_status');
            $s_city = $this->view->s_city = (int)$this->_request->getParam('s_city');
            $s_inwest = $this->view->s_inwest = (int)$this->_request->getParam('s_inwest');

            $s_pokoje = $this->view->s_pokoje = $this->_request->getParam('s_pokoje');
            $s_metry = $this->view->s_metry = $this->_request->getParam('s_metry');

            $s_room = $this->view->s_room = $this->_request->getParam('s_room');
            $s_area = $this->view->s_area = $this->_request->getParam('s_area');

            $s_aneks = $this->view->s_aneks = $this->_request->getParam('s_aneks');
            $s_garden = $this->view->s_garden = $this->_request->getParam('s_garden');
            $s_deck = $this->view->s_deck = $this->_request->getParam('s_deck');
			
            $s_typ = $this->view->s_typ = $this->_request->getParam('s_typ');


            if($s_inwest) {
                $powierzchniaQuery->where('id_inwest =?', $s_inwest);
            }

            if($s_status) {
                $powierzchniaQuery->where('status =?', $s_status);
            }

            if($s_metry) {
                $areapieces = explode("-", $s_metry);
                $powierzchniaQuery->where('szukaj_metry >=?', $areapieces[0]);
                $powierzchniaQuery->where('szukaj_metry <=?', $areapieces[1]);
            }

            if($s_pokoje) {
                $powierzchniaQuery->where('pokoje =?', $s_pokoje);
            }

            if($s_aneks) {
                $powierzchniaQuery->where('kuchnia =?', $s_aneks);
            }

            if($s_garden) {
                $powierzchniaQuery->where('ogrodek !=?', '');
            }

            if($s_deck) {
                $powierzchniaQuery->where('taras !=?', '');
            }

            if($s_room){
                $powierzchniaQuery->order('pokoje '.$s_room);
            }

            if($s_area){
                $powierzchniaQuery->order('szukaj_metry '.$s_area);
            }

            if($s_typ && $s_typ == 2) {
                $powierzchniaQuery->where('uslugowy =?', 1);
            }
			
            if($s_typ && $s_typ == 1) {
                $powierzchniaQuery->where('uslugowy =?', 0);
            }
			
			// $sql = $powierzchniaQuery->__toString();
			// echo "$sql\n";
			
            $powierzchnia = $this->Room->fetchAll($powierzchniaQuery);

            $inwestycjeQuery = $this->Investment->select();

            if($s_city){
                $inwestycjeQuery->where('miasto =?', $s_city);
            }
            if($s_inwest) {
                $inwestycjeQuery->where('id =?', $s_inwest);
            }
			
			$inwestycjeQuery->where('status =?', 1);
            $inwestycje = $this->Investment->fetchAll($inwestycjeQuery);

            $inwestycjeSelectQuery = $this->Investment->select();
            $selectinwestycje = $this->Investment->fetchAll($inwestycjeSelectQuery);

            $array = array(
                'strona_id' => $this->page_id,
                'strona_h1' => $pageName,
                'strona_tytul' => ' - '.$pageName,
                'seo_tytul' => (isset($page->meta_tytul)) ? $page->meta_tytul : json_decode($page->json)->meta_tytul,
                'seo_opis' => (isset($page->meta_opis)) ? $page->meta_opis : json_decode($page->json)->meta_opis,
                'seo_slowa' => (isset($page->meta_slowa)) ? $page->meta_slowa : json_decode($page->json)->meta_slowa,
                'page' => $page,
                'content' => (isset($page->tekst)) ? $page->tekst : json_decode($page->json)->tekst,
                'breadcrumbs' => $breadcrumbs,
                'inwestycje' => $inwestycje,
                'selectinwestycje' => $selectinwestycje,
                'powierzchnia' => $powierzchnia,
                'listamieszkan' => 1
            );
            $this->view->assign($array);
        }
    }


}

