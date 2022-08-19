<?php

class Default_NewsController extends kCMS_Site
{
    private $page_id;
    private $pageModel;

    public function preDispatch() {
        $this->page_id = 1;
        $this->pageModel = new Model_MenuModel();
        $this->_helper->layout->setLayout('page');
    }

    public function indexAction() {
        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_Db::FETCH_OBJ);

        $page = $this->pageModel->getById($this->page_id);

        if(!$page) {
            errorPage();
        } else {

            $breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$page->nazwa .'</b><meta itemprop="position" content="2" /></li>';

            $array = array(
                'pageclass' => ' news-page',
                'strona_id' => $this->page_id,
                'strona_h1' => $page->nazwa,
                'strona_tytul' => ' - '.$page->nazwa,
                'seo_tytul' => $page->meta_tytul,
                'seo_opis' => $page->meta_opis,
                'seo_slowa' => $page->meta_slowa,
                'breadcrumbs' => $breadcrumbs,
                'page' => $page
            );
            $this->view->assign($array);
        }
    }
}