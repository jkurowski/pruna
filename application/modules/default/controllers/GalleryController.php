<?php

class Default_GalleryController extends kCMS_Site
{
    private $menuModel;
    private $galleryModel;
    private $photoModel;
    private $page;

    public function preDispatch() {
        $this->menuModel = new Model_MenuModel();
        $this->galleryModel = new Model_GalleryModel();
        $this->photoModel = new Model_PhotoModel();

        $this->page = $this->menuModel->getById(3);
        $this->_helper->layout->setLayout('page');
    }

    public function indexAction() {
        if(!$this->page) {
            errorPage();
        } else {

            $breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'. $this->page->nazwa .'</b><meta itemprop="position" content="2" /></li>';

            $array = array(
                'pageclass' => ' gallery-page',
                'strona_id' => 3,
                'strona_h1' => $this->page->nazwa,
                'strona_tytul' => ' - '.$this->page->nazwa,
                'seo_tytul' => $this->page->meta_tytul,
                'seo_opis' => $this->page->meta_opis,
                'seo_slowa' => $this->page->meta_slowa,
                'breadcrumbs' => $breadcrumbs,
                'galeries' => $this->galleryModel->getAll(),
                'page' => $this->page
            );
            $this->view->assign($array);
        }
    }

    public function showAction() {
        if(!$this->page) {
            errorPage();
        } else {

            $slug = $this->getRequest()->getParam('slug');
            $gallery = $this->galleryModel->getBySlug($slug);

            if(!$gallery) {
                $this->_redirect('/galeria/');
            }

            $photos = $this->photoModel->getByCategory($gallery->id);

            $breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . $this->view->url(array(), 'gallery') . '"><span itemprop="name">' . $this->page->nazwa . '</span></a></li><li class="sep"></li>';
            $breadcrumbs .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$gallery->nazwa.'</b></li>';

            $array = array(
                'pageclass' => ' gallery-show-page',
                'strona_id' => 3,
                'strona_h1' => $this->page->nazwa,
                'strona_tytul' => ' - '.$this->page->nazwa.' - '.$gallery->nazwa,
                'seo_tytul' => $this->page->meta_tytul,
                'seo_opis' => $this->page->meta_opis,
                'seo_slowa' => $this->page->meta_slowa,
                'breadcrumbs' => $breadcrumbs,
                'photos' => $photos,
                'page' => $this->page
            );
            $this->view->assign($array);
        }
    }
}