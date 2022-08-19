<?php
class Default_InwestycjePlanController extends kCMS_Site
{
    private $page;
    private $investmentModel;
    private $menuModel;
    private $planModel;
    private $roomModel;
    private $floorModel;

    public function preDispatch() {

        $this->roomModel = new Model_RoomModel();
        $this->floorModel = new Model_FloorModel();
        $this->planModel = new Model_PlanModel();
        $this->menuModel = new Model_MenuModel();
        $this->investmentModel = new Model_InvestmentModel();
        $this->page = $this->menuModel->getById(4);

        $this->_helper->layout->setLayout('page');
    }

    /* Plan inwestycji */
    public function indexAction() {

        $tag = $this->getRequest()->getParam('tag');

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

        $inwestycja = $this->investmentModel->getByTag($tag);

        $planModel = new Model_PlanModel();
        $plan = $planModel->fetchRow($planModel->select()->where('id_inwest =?', $inwestycja->id));

        //Inwestycja budynkowa
        if($inwestycja->typ == 2) {
            $powierzchniaQuery = $this->roomModel->select()
                ->where('id_inwest = ?', $inwestycja->id);

            if($s_pokoje){
                $powierzchniaQuery->where('pokoje =?', $s_pokoje);
            }

            if($s_metry) {
                $areapieces = explode("-", $s_metry);
                $powierzchniaQuery->where('szukaj_metry >=?', $areapieces[0]);
                $powierzchniaQuery->where('szukaj_metry <=?', $areapieces[1]);
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

            $powierzchniaQuery->where('typ !=?', 4);

            $powierzchnia = $this->roomModel->fetchAll($powierzchniaQuery);

            $pietra = $this->floorModel->getAll($inwestycja->id);

            if($powierzchnia->count() > 0) {
                $rooms = array();
                foreach ($powierzchnia as $p) {
                    $rooms[$p->id_pietro] .= $p->status;
                }

                $pietro_stats = array();
                foreach ($pietra as $f) {
                    $pietro_stats[$f->id] = $rooms[$f->id];
                }
                $this->view->pietro_stats = $pietro_stats;
            }
        }

        //Inwestycja z domkami
        if($inwestycja->typ == 3) {
                $powierzchniaQuery = $this->roomModel->select()
                    ->where('id_inwest = ?', $inwestycja->id);

                if($s_pokoje){
                    $powierzchniaQuery->where('pokoje =?', $s_pokoje);
                }

                if($s_metry) {
                    $areapieces = explode("-", $s_metry);
                    $powierzchniaQuery->where('szukaj_metry >=?', $areapieces[0]);
                    $powierzchniaQuery->where('szukaj_metry <=?', $areapieces[1]);
                }

                if($s_status) {
                    $powierzchniaQuery->where('status =?', $s_status);
                }

                if($s_aneks) {
                    $powierzchniaQuery->where('kuchnia =?', $s_aneks);
                }

                if($s_garden) {
                    $powierzchniaQuery->where('ogrodek !=?', '');
                }

                if($s_deck) {
                    $powierzchniaQuery->where('balkon !=?', '');
                }

                if($s_room){
                    $powierzchniaQuery->order('pokoje '.$s_room);
                }

                if($s_area){
                    $powierzchniaQuery->order('szukaj_metry '.$s_area);
                }

                $powierzchniaQuery->where('typ !=?', 4);

                $powierzchnia = $this->roomModel->fetchAll($powierzchniaQuery);

                $pietra = '';
        }

        //Schema breadcrumbs  inwestycje
        $breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">Apartamenty</b></li>';

        $array = array(
            'strona_h1' => $inwestycja->nazwa,
            'strona_tytul' => ' - Apartamenty',
//            'seo_tytul' => (isset($page->meta_tytul)) ? $page->meta_tytul : json_decode($page->json)->meta_tytul,
//            'seo_opis' => (isset($page->meta_opis)) ? $page->meta_opis : json_decode($page->json)->meta_opis,
//            'seo_slowa' => (isset($page->meta_slowa)) ? $page->meta_slowa : json_decode($page->json)->meta_slowa,
            'inwestycja' => $inwestycja,
            'breadcrumbs' => $breadcrumbs,
            'plan' => $plan,
            'powierzchnia' => $powierzchnia,
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
            'page' => $this->page,
            'pietra' => $pietra,
            'listamieszkan' => 1,
            'building' => 1,
            'tip' => 1
        );

        $this->view->assign($array);
    }
}