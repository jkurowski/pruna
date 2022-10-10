<?php
class Default_IndexController extends kCMS_Site
{

    private $inlineModel;
    private $roomModel;
    private $planModel;
    private $photoModel;
    private $sliderModel;
    private $menuModel;
    private $atutModel;
    private $boxModel;
    private $floorModel;

    public function preDispatch() {
        $this->inlineModel = new Model_InlineModel();
        $this->roomModel = new Model_RoomModel();
        $this->planModel = new Model_PlanModel();
        $this->photoModel = new Model_PhotoModel();
        $this->sliderModel = new Model_SliderModel();
        $this->menuModel = new Model_MenuModel();
        $this->atutModel = new Model_AtutModel();
        $this->boxModel = new Model_BoxModel();
        $this->floorModel = new Model_FloorModel();
    }

    public function indexAction() {
//        $floors = $this->floorModel->fetchAll($this->floorModel->select()->where('id_inwest = ?', 1)->order('numer_lista ASC')->order('typ ASC'));
//
//        $powierzchniaQuery = $this->roomModel->select()
//            ->where('id_inwest = ?', 1);
//        $powierzchniaQuery->where('typ !=?', 4);
//        $domki = $this->roomModel->fetchAll($powierzchniaQuery);

        $trySendEmail = '';

        if ($this->_request->isPost()) {
            $sendEmail = new Mails_ContactSend();
            $trySendEmail = $sendEmail->send($this->_request->getPost());
        }

        $array = array(
//            'inline' => $this->inlineModel->getInlineList(1),
//            'floors' => $floors,
//            'domki' => $domki,
//            'editinline' => 1,
//            'plan' => $this->planModel->fetchRow($this->planModel->select()->where('id_inwest =?', 1)),
            'photos' => $this->photoModel->fetchAll($this->photoModel->select()->order('sort ASC')->where('id_gal =?', 1)),
//            'slider' => $this->sliderModel->fetchRow($this->sliderModel->select()->order('sort ASC')),
            'contact' => $this->menuModel->getById(3),
           'atuty' => $this->atutModel->fetchAll($this->atutModel->select()->order('sort ASC')),
//            'boksy' => $this->boxModel->fetchAll($this->boxModel->select()->order('sort ASC')),
            'message' => $trySendEmail
        );

        $this->view->assign($array);
    }

    public function menuAction() {
        $this->_helper->layout->setLayout('page');
        $uri = $this->getRequest()->getParam('uri');

        $pageModel = new Model_MenuModel();
        $page = $pageModel->getByUri($uri);

        $breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$page->nazwa .'</b><meta itemprop="position" content="2" /></li>';

        if(!$page) {
            errorPage();
        }
        $array = array(
            'strona_nazwa' => $page->nazwa,
            'strona_h1' => $page->nazwa,
            'strona_tytul' => $page->nazwa,
            'seo_tytul' => $page->meta_tytul,
            'seo_opis' => $page->meta_opis,
            'seo_slowa' => $page->meta_slowa,
            'breadcrumbs' => $breadcrumbs,
            'page' => $page
        );
        $this->view->assign($array);
    }
}