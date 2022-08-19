<?php
class Default_InwestycjeSzukajController extends kCMS_Site
{
    public function preDispatch() {

    }

    public function indexAction() {
        $this->_helper->layout->setLayout('page');
        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_Db::FETCH_OBJ);

        $params = $this->getRequest()->getParams();
        $inwestycja = $db->fetchRow($db->select()->from('inwestycje', array('id', 'slug'))->where('id = ?', $params['s_inwest']));
        $lang = $this->getRequest()->getParam('language');

        unset($params['module']);
        unset($params['controller']);
        unset($params['action']);
        unset($params['language']);
        unset($params['s_inwest']);

        $new_params =  http_build_query($params, '', '&');

        $this->_redirect('http://testy.4dl.pl/optimum/'.$lang.'/i/'.$inwestycja->slug.'/plan-inwestycji/?'.$new_params);

    }
}

