<?php
class Default_IndexController extends kCMS_Site
{

    private $photoModel;
    private $menuModel;
    private $atutModel;

    public function preDispatch() {
        $this->photoModel = new Model_PhotoModel();
        $this->menuModel = new Model_MenuModel();
        $this->atutModel = new Model_AtutModel();
    }

    public function indexAction() {
        $trySendEmail = '';

        if ($this->_request->isPost()) {
            $sendEmail = new Mails_ContactSend();
            $trySendEmail = $sendEmail->send($this->_request->getPost());
        }

        $array = array(
            'photos' => $this->photoModel->fetchAll($this->photoModel->select()->order('sort ASC')->where('id_gal =?', 1)),
            'contact' => $this->menuModel->getById(3),
            'atuty' => $this->atutModel->fetchAll($this->atutModel->select()->order('sort ASC')),
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