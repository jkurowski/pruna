<?php
class Default_InwestycjeController extends kCMS_Site
{

    private $page;
    private $investmentModel;
    private $menuModel;

    public function preDispatch() {
        $this->menuModel = new Model_MenuModel();
        $this->investmentModel = new Model_InvestmentModel();
        $this->page = $this->menuModel->getById(4);

        $this->_helper->layout->setLayout('page');
    }

    public function indexAction() {
        if(!$this->page) {
            errorPage();
        } else {

            $breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$this->page->nazwa .'</b><meta itemprop="position" content="2" /></li>';

            $array = array(
                'strona_id' => $this->page->id,
                'strona_h1' => 'Osiedle Sadyba',
                'strona_tytul' => ' - '.$this->page->nazwa,
                'seo_tytul' => $this->page->meta_tytul,
                'seo_opis' => $this->page->meta_opis,
                'seo_slowa' => $this->page->meta_slowa,
                'page' => $this->page,
                'breadcrumbs' => $breadcrumbs,
                'buildingi' => 1,
                'tip' => 1,
                'inwestycje' => $this->investmentModel->getAll()
            );
            $this->view->assign($array);
        }
    }
}