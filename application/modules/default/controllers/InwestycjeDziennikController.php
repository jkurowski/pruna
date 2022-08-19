<?php

class Default_InwestycjeDziennikController extends kCMS_Site
{

    private $news_count_pre_page;
    private $page_id;
    private $page_class;
    private $locale;
    private $Investment;
    private $Menu;

    public function preDispatch() {
        $this->Menu = new Model_MenuModel();
        $this->Investment = new Model_InvestmentModel();

        $this->page_id = 4;
        $this->page_class = 'news-page dziennik-page';
        $this->news_count_pre_page = 6;
        if($this->canbetranslate) {
            $this->locale = Zend_Registry::get('Zend_Locale')->getLanguage();
        } else {
            $this->locale = 'pl';
        }
    }

    public function indexAction() {
        $this->_helper->layout->setLayout('page');

        $newsModel = new Model_InvestDiaryModel();
        $page = $this->Menu->getPageById($this->page_id);

        if(!$page) {
            errorPage();
        } else {
            $tag = $this->getRequest()->getParam('tag');
            $inwestycja = $this->Investment->getInvest($tag);

            (isset($page->nazwa)) ? $breadPage = $page->nazwa : $breadPage = json_decode($page->json)->nazwa;

            //Schema breadcrumbs  inwestycje
            $breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->view->url(array('language'=> $this->locale), 'inwestycje').'"><span itemprop="name">'.$breadPage.'</span></a></li><li class="sep"></li>';
            $breadcrumbs .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->view->url(array('language'=> $this->locale, 'tag' => $inwestycja->slug), 'inwestycja').'"><span itemprop="name">'.$inwestycja->nazwa.'</span></a></li><li class="sep"></li>';
            $breadcrumbs .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$this->view->translate('tl_inwestycja_dziennik').'</b></li>';

            if($this->locale == 'pl') {
                $news = $newsModel->getAll(null, $inwestycja->id);
            } else {
                $news = $newsModel->getAllTranslated();
            }

            $pageNo = $this->_getParam('strona', 1);
            $paginator = Zend_Paginator::factory($news);
            $paginator->setItemCountPerPage(6);
            $paginator->setCurrentPageNumber($pageNo);

            $array = array(
                'pageclass' => $this->page_class,
                'strona_id' => $this->page_id,
                'strona_h1' => $this->view->translate('tl_inwestycja_dziennik'),
                'strona_tytul' => ' - '.$inwestycja->nazwa.' - '.$this->view->translate('tl_inwestycja_dziennik'),
//                'seo_tytul' => (isset($page->meta_tytul)) ? $page->meta_tytul : json_decode($page->json)->meta_tytul,
//                'seo_opis' => (isset($page->meta_opis)) ? $page->meta_opis : json_decode($page->json)->meta_opis,
//                'seo_slowa' => (isset($page->meta_slowa)) ? $page->meta_slowa : json_decode($page->json)->meta_slowa,
                'breadcrumbs' => $breadcrumbs,
                'inwestycja' => $inwestycja,
                'inwest_tag' => $inwestycja->slug,
                'notop' => 1,
                'news' => $paginator
            );
            $this->view->assign($array);
        }
    }

    public function showAction() {
        $this->_helper->layout->setLayout('page');
        $slug = $this->getRequest()->getParam('slug');

        $newsModel = new Model_InvestDiaryModel();
        $page = $this->Menu->getPageById($this->page_id);
        $news = $newsModel->getNews($slug);

        if(!$news) {
            errorPage();
        } else {
            $tag = $this->getRequest()->getParam('tag');
            $inwestycja = $this->Investment->getInvest($tag);

            $translate = $newsModel->getTranslate($news->id);

            if($translate && $this->locale != 'pl' && $page){
                $newsTl = json_decode($translate->json, true);
                $newsTl['data'] = $news->data;
                $newsTl['plik'] = $news->plik;
                if($newsTl){
                    $news = json_decode(json_encode($newsTl));
                }
            } else {
                if($this->locale != 'pl'){
                    header('Location: ' . $this->_helper->url->url(array('language'=> 'pl', 'tag' => $tag), 'news-show'), true, 301);
                }
            }

            (isset($page->nazwa)) ? $breadPage = $page->nazwa : $breadPage = json_decode($page->json)->nazwa;

            //Schema breadcrumbs  inwestycje
            $breadcrumbs = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->view->url(array('language'=> $this->locale), 'inwestycje').'"><span itemprop="name">'.$breadPage.'</span></a></li><li class="sep"></li>';
            $breadcrumbs .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="'.$this->view->url(array('language'=> $this->locale, 'tag' => $inwestycja->slug), 'inwestycja').'"><span itemprop="name">'.$inwestycja->nazwa.'</span></a></li><li class="sep"></li>';
            $breadcrumbs .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><b itemprop="item">'.$this->view->translate('tl_inwestycja_dziennik').'</b></li>';

            $config = Zend_Controller_Front::getInstance()->getParam('bootstrap');
            $configUrl = $config->getOption('resources');
            $baseUrl = $configUrl['frontController']['baseUrl'];

            $array = array(
                'pageclass' => $this->page_class,
                'strona_h1' => $this->view->translate('tl_inwestycja_dziennik'),
                'strona_tytul' => ' - '.$this->view->translate('tl_inwestycja_dziennik').' - '.$news->tytul,
                'seo_tytul' => (isset($news->meta_tytul)) ? $news->meta_tytul : json_decode($news->json)->meta_tytul,
                'seo_opis' => (isset($news->meta_opis)) ? $news->meta_opis : json_decode($news->json)->meta_opis,
                'seo_slowa' => (isset($news->meta_slowa)) ? $news->meta_slowa : json_decode($news->json)->meta_slowa,
                'share' => 1,
                'share_tytul' => $news->tytul,
                'share_desc' => $news->wprowadzenie,
                'share_image' => $baseUrl.'/files/news/share/'.$news->plik,
                'share_url' => $baseUrl.'/'.$this->locale.'/'.$page->tag.'/'.$news->tag.'/',
                'breadcrumbs' => $breadcrumbs,
                'inwestycja' => $inwestycja,
                'inwest_tag' => $inwestycja->slug,
                'news' => $news
            );
            $this->view->assign($array);
        }
    }
}