<?php
class Default_InwestycjePietroController extends kCMS_Site
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

        $this->page_id = 4;
        $this->_helper->layout->setLayout('page');
    }

    /* Pietro */
    public function indexAction() {
        $page = $this->Menu->getById($this->page_id);
        $tag = $this->getRequest()->getParam('tag');

        if(!$page) {
            errorPage();
        }
        else {

            $inwestycja = $this->Investment->getByTag($tag);

            if ($inwestycja) {
				
				$numer = (int)$this->getRequest()->getParam('numer');
				$typ = (int)$this->getRequest()->getParam('typ');
				$pietro = $this->Floor->getFloor($inwestycja->id, $numer, $typ);

                if(!$pietro){
                    errorPage();
                } else {

                    $s_action = $this->getRequest()->getParam('a');
                    $s_pokoje = $this->_request->getParam('s_pokoje');
                    $s_metry = $this->_request->getParam('s_metry');

                    $s_room = $this->_request->getParam('s_room');
                    $s_area = $this->_request->getParam('s_area');

                    $s_status = $this->_request->getParam('s_status');
                    $s_aneks = $this->_request->getParam('s_aneks');
                    $s_garden = $this->_request->getParam('s_garden');
                    $s_deck = $this->_request->getParam('s_deck');

                    $powierzchniaQuery = $this->Room->select()
                        ->where('id_inwest =?', (int)$inwestycja->id)
                        ->where('id_pietro =?', (int)$pietro->id);

                    if($s_metry) {
                        $area_pieces = explode("-", $s_metry);
                        $powierzchniaQuery->where('szukaj_metry >=?', $area_pieces[0]);
                        $powierzchniaQuery->where('szukaj_metry <=?', $area_pieces[1]);
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

					//dd($powierzchniaQuery->__toString());

                    $powierzchnia = $this->Room->fetchAll($powierzchniaQuery);

                }
            } else {
                errorPage();
            }

            //Schema breadcrumbs  inwestycje
            $breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->view->url(array(), 'inwestycje').'"><span itemprop="name">'.$page->nazwa.'</span></a></li><li class="sep"></li>';

            $breadcrumbs .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->view->url(array('tag' => $inwestycja->slug), 'inwestycja-plan').'"><span itemprop="name">'.$inwestycja->nazwa.'</span></a></li><li class="sep"></li>';

            $breadcrumbs .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$pietro->nazwa.'</b></li>';

            $next_pietro = $this->Floor->getNextFloor($inwestycja->id, $pietro->numer_lista);
            $prev_pietro = $this->Floor->getPrevFloor($inwestycja->id, $pietro->numer_lista);

            $array = array(
                'strona_id' => $this->page_id,
                'strona_h1' => $pietro->nazwa,
                'strona_tytul' => ' - '.$inwestycja->nazwa.' - '.$pietro->nazwa,
//                'seo_tytul' => (isset($page->meta_tytul)) ? $page->meta_tytul : json_decode($page->json)->meta_tytul,
//                'seo_opis' => (isset($page->meta_opis)) ? $page->meta_opis : json_decode($page->json)->meta_opis,
//                'seo_slowa' => (isset($page->meta_slowa)) ? $page->meta_slowa : json_decode($page->json)->meta_slowa,
                's_action' => $s_action,
                's_pokoje' => $s_pokoje,
                's_metry' => $s_metry,
                's_room' => $s_room,
                's_area' => $s_area,
                's_status' => $s_status,
                's_aneks' => $s_aneks,
                's_garden' => $s_garden,
                's_deck' => $s_deck,
                'listamieszkan' => 1,
                'notop' => 1,
                'floor' => 1,
                'tip' => 1,
                'page' => $page,
                'inwestycja' => $inwestycja,
                'pietro' => $pietro,
                'next_pietro' => $next_pietro,
                'prev_pietro' => $prev_pietro,
                'powierzchnia' => $powierzchnia,
                'breadcrumbs' => $breadcrumbs
            );
            $this->view->assign($array);
        }
    }

}