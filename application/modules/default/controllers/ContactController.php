<?php

class Default_ContactController extends kCMS_Site
{

    private int $page_id;
    private int $validation;
    private $menuModel;

    public function preDispatch() {
        $this->_helper->layout->setLayout('page');

        $this->page_id = 1;
        $this->validation= 1;
        $this->menuModel = new Model_MenuModel();
    }

    public function indexAction() {
        $page = $this->menuModel->getById($this->page_id);

        if(!$page) {
            errorPage();
        } else {
            $breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'. $page->nazwa .'</b><meta itemprop="position" content="2" /></li>';

            $trySendEmail = '';

            if ($this->_request->isPost()) {
                $sendEmail = new Mails_ContactSend();
                $trySendEmail = $sendEmail->send($this->_request->getPost());
            }

            $array = array(
                'pageclass' => ' contact-page',
                'strona_id' => $this->page_id,
                'strona_h1' => $page->nazwa,
                'strona_tytul' => ' - '.$page->nazwa,
                'seo_tytul' => $page->meta_tytul,
                'seo_opis' => $page->meta_opis,
                'seo_slowa' => $page->meta_slowa,
                'content' => $page->tekst,
                'validation' => $this->validation,
                'breadcrumbs' => $breadcrumbs,
                'page' => $page,
                'message' => $trySendEmail
            );
            $this->view->assign($array);
        }
    }
}