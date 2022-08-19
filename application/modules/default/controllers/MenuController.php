<?php
class Default_MenuController extends kCMS_Site
{
    private $locale;

    public function preDispatch() {
        $this->locale = Zend_Registry::get('Zend_Locale')->getLanguage();
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('page');
        $uri = $this->getRequest()->getParam('uri');

        $pageModel = new Model_MenuModel();
        $page = $pageModel->getPageByUri($uri);
		
		$pageName = (isset($page->nazwa)) ? $page->nazwa : json_decode($page->json)->nazwa;
		$breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$pageName .'</b><meta itemprop="position" content="2" /></li>';

        if(!$page) {
            errorPage();
        }
        $array = array(
            'strona_nazwa' => (isset($page->nazwa)) ? $page->nazwa : json_decode($page->json)->nazwa,
            'strona_h1' => (isset($page->nazwa)) ? $page->nazwa : json_decode($page->json)->nazwa,
            'strona_tytul' => (isset($page->nazwa)) ? ' - '.$page->nazwa : ' - '.json_decode($page->json)->nazwa,
            'seo_tytul' => (isset($page->meta_tytul)) ? $page->meta_tytul : json_decode($page->json)->meta_tytul,
            'seo_opis' => (isset($page->meta_opis)) ? $page->meta_opis : json_decode($page->json)->meta_opis,
            'seo_slowa' => (isset($page->meta_slowa)) ? $page->meta_slowa : json_decode($page->json)->meta_slowa,
            'breadcrumbs' => $breadcrumbs,
            'page' => $page,
        );
        $this->view->assign($array);
    }
}