<?php
class Default_InwestycjeBudynekController extends kCMS_Site
{
    private $page_id;
    private $Investment;
    private $Menu;
    private $Room;
    private $Floor;
    private $Building;

    public function preDispatch() {
        $this->Menu = new Model_MenuModel();
        $this->Investment = new Model_InvestmentModel();
        $this->Room = new Model_RoomModel();
        $this->Floor = new Model_FloorModel();
        $this->Building = new Model_BuildingModel();

        $this->page_id = 3;
        $this->_helper->layout->setLayout('page');
    }

    /* Budynek */
    public function indexAction() {
        $page = $this->Menu->getPageById($this->page_id);

        if(!$page) {
            errorPage();
        }
        else {

            $tag = $this->getRequest()->getParam('tag');
            $inwestycja = $this->Investment->getInvest('stegna');

            if ($inwestycja) {
                $b = (int)$this->getRequest()->getParam('budynek');
                $budynek = $this->Building->getBuilding($inwestycja->id, $b);

                $next_budynek = $this->Building->getNextBuilding($inwestycja->id, $budynek->numer);
                $prev_budynek = $this->Building->getPrevBuilding($inwestycja->id, $budynek->numer);

                $pietra = $this->Floor->getFloors($inwestycja->id, $b);

                $powierzchnia = $this->Room->fetchAll(
                    $this->Room->select()
                        ->where('id_inwest = ?', $inwestycja->id)
                        ->where('id_budynek = ?', $b)
                );

                if(!$budynek){
                    errorPage();
                } else {

                    $s_action = $this->getRequest()->getParam('a');
                    $s_pokoje = $this->_request->getParam('s_pokoje');
                    $s_metry = $this->_request->getParam('s_metry');
                    $s_pietro = $this->_request->getParam('s_pietro');
                    $s_typ = $this->_request->getParam('s_typ');

                    $s_room = $this->_request->getParam('s_room');
                    $s_area = $this->_request->getParam('s_area');

                    $s_status = $this->_request->getParam('s_status');
                    $s_aneks = $this->_request->getParam('s_aneks');
                    $s_garden = $this->_request->getParam('s_garden');
                    $s_deck = $this->_request->getParam('s_deck');

                    $powierzchniaQuery = $this->Room->select()
                        ->where('id_inwest = ?', $inwestycja->id)
                        ->where('id_budynek = ?', $budynek->id);

                    if($s_pietro){
                        $powierzchniaQuery->where('numer_pietro =?', $s_pietro);
                    }

                    if($s_typ){
                        $powierzchniaQuery->where('typ =?', $s_typ);
                    }

                    if($s_metry) {
                        $areapieces = explode("-", $s_metry);
                        $powierzchniaQuery->where('szukaj_metry >=?', $areapieces[0]);
                        $powierzchniaQuery->where('szukaj_metry <=?', $areapieces[1]);
                    }

                    if($s_pokoje) {
                        $powierzchniaQuery->where('pokoje =?', $s_pokoje);
                    }

                    if($s_status) {
                        $powierzchniaQuery->where('status =?', $s_status);
                    }

                    if($s_aneks) {
                        $powierzchniaQuery->where('kuchnia =?', $s_aneks);
                    }

                    if($s_garden) {
                        $powierzchniaQuery->where('ogrodek !=?', '');
                        $powierzchniaQuery->where('typ =?', 1);
                    }

                    if($s_deck) {
                        $powierzchniaQuery->where('balkon !=?', '');
                        $powierzchniaQuery->where('typ =?', 1);
                    }

                    if($s_room){
                        $powierzchniaQuery->order('pokoje '.$s_room);
                    }

                    if($s_area){
                        $powierzchniaQuery->order('szukaj_metry '.$s_area);
                    }

                    $powierzchnia = $this->Room->fetchAll($powierzchniaQuery);

                    $rooms = array();
                    foreach($powierzchnia as $p){
                        $rooms[$p->id_pietro] .= $p->status;
                    }

                    $pietro_stats = array();
                    foreach($pietra as $f){
                        $pietro_stats[$f->id] = $rooms[$f->id];
                    }
                    $this->view->pietro_stats = $pietro_stats;
                }
            } else {
                errorPage();
            }

            //Schema breadcrumbs  inwestycje
            $breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . $this->view->url(array('budynek' => $budynek->id), 'inwestycja-budynek') . '"><span itemprop="name">' . $budynek->nazwa . '</span></a>';


            $array = array(
                'strona_id' => $this->page_id,
                'strona_h1' => $budynek->nazwa,
                'strona_tytul' => ' - '.$inwestycja->nazwa.' - '.$budynek->nazwa,
//                'seo_tytul' => (isset($page->meta_tytul)) ? $page->meta_tytul : json_decode($page->json)->meta_tytul,
//                'seo_opis' => (isset($page->meta_opis)) ? $page->meta_opis : json_decode($page->json)->meta_opis,
//                'seo_slowa' => (isset($page->meta_slowa)) ? $page->meta_slowa : json_decode($page->json)->meta_slowa,
                's_action' => $s_action,
                's_pietro' => $s_pietro,
                's_typ' => $s_typ,
                's_pokoje' => $s_pokoje,
                's_metry' => $s_metry,
                's_room' => $s_room,
                's_area' => $s_area,
                's_status' => $s_status,
                's_aneks' => $s_aneks,
                's_garden' => $s_garden,
                's_deck' => $s_deck,
                'listamieszkan' => 1,
				'buildingi' => 1,
                'notop' => 1,
                'tip' => 1,
                'page' => $page,
                'inwestycja' => $inwestycja,
                'budynek' => $budynek,
                'pietra' => $pietra,
                'next_budynek' => $next_budynek,
                'prev_budynek' => $prev_budynek,
                'powierzchnia' => $powierzchnia,
                'breadcrumbs' => $breadcrumbs
            );
            $this->view->assign($array);
        }
    }

}