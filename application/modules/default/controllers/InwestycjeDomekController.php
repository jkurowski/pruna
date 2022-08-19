<?php
class Default_InwestycjeDomekController extends kCMS_Site
{
    private $page_id;
    private $Investment;
    private $Menu;
    private $Room;

    public function preDispatch() {
        $this->Menu = new Model_MenuModel();
        $this->Investment = new Model_InvestmentModel();
        $this->Room = new Model_RoomModel();

        $this->page_id = 4;
        $this->_helper->layout->setLayout('page');
    }

    public function indexAction() {
        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_Db::FETCH_OBJ);

        $numer = $this->getRequest()->getParam('numer');
        $tag = $this->getRequest()->getParam('tag');
        $inwestycja = $this->Investment->getByTag('sucharskiego');

        if($inwestycja){
            $mieszkanie = $this->Room->getRoom($inwestycja->id, $numer);
            $next_mieszkanie = $this->Room->getNextRoom($inwestycja->id, $mieszkanie->order_numer);
            $prev_mieszkanie = $this->Room->getPrevRoom($inwestycja->id, $mieszkanie->order_numer);

            if(!$mieszkanie){
                errorPage();
            } else {
                $this->_helper->viewRenderer('inwestycje/powierzchnia', null, true);

                $stat = array(
                    'akcja' => 3,
                    'miejsce' => 3,
                    'id_inwest' => $inwestycja->id,
                    'mieszkanie' => $mieszkanie->nazwa,
                    'pokoje' => $mieszkanie->pokoje,
                    'timestamp' => date("d-m-Y"),
                    'data' => date("d.m.Y - H:i:s"),
                    'godz' => date("H"),
                    'dzien' => date("d"),
                    'msc' => date("m"),
                    'rok' => date("Y"),
                    'ip' => $_SERVER['REMOTE_ADDR']
                );
                $db->insert('statystyki', $stat);
                $message = '';

                if ($this->_request->isPost()) {

                    $ip = $_SERVER['REMOTE_ADDR'];
                    $adresip = $db->fetchRow($db->select()->from('blokowanie')->where('ip = ?', $ip));

                    $grecaptcha = $this->_request->getPost('g-recaptcha-response');
                    // unset($formData['g-recaptcha-response']);
                    //if(getRecaptchaCheck($grecaptcha) === true){
                    if(!$adresip) {

                        $formData = $this->_request->getPost();
                        if($formData['imie'] && $formData['email']) {

                            $imie = $this->_request->getPost('imie');
                            $email = $this->_request->getPost('email');
                            $telefon = $this->_request->getPost('telefon');
                            $wiadomosc = $this->_request->getPost('wiadomosc');
                            $ip = $_SERVER['REMOTE_ADDR'];

                            $ustawienia = $db->fetchRow($db->select()->from('ustawienia'));

                            $emailarray = array(
                                'nazwa_strony' => $ustawienia->nazwa,
                                'imie' => $imie,
                                'email' => $email,
                                'telefon' => $telefon,
                                'wiadomosc' => $wiadomosc,
                                'dom_nazwa' => $mieszkanie->numer,
                                'inwest_nazwa' => $inwestycja->nazwa,
                                'ip' => $ip
                            );

                            $view = new Zend_View();
                            $view->setScriptPath( APPLICATION_PATH . '/modules/default/views/scripts/email/' );
                            $view->assign($emailarray);

                            $mail = new Zend_Mail('UTF-8');
                            $mail
                                ->setFrom($ustawienia->email, $imie)
                                ->addTo($ustawienia->email, 'Adres odbiorcy')
                                ->setReplyTo($email, $imie)
                                ->setSubject($ustawienia->domena.' - Zapytanie ze strony www - Kontakt');
                            $mail->setBodyHtml($view->render( 'domek.phtml'));
                            $mail->setBodyText($view->render( 'domek-txt.phtml'));

                            try {
                                $mail->send();
                            } catch (Zend_Exception $e) {
                                echo $e->getMessage();
                                exit;
                            }

                            //Zapisz statystyki
                            $stat = array(
                                'akcja' => 1,
                                'miejsce' => 3,
                                'id_inwest' => $mieszkanie->id_inwest,
                                'data' => date("d.m.Y - H:i:s"),
                                'godz' => date("H"),
                                'dzien' => date("d"),
                                'msc' => date("m"),
                                'rok' => date("Y"),
                                'mieszkanie' => $mieszkanie->nazwa,
                                'tekst' => $wiadomosc,
                                'email' => $email,
                                'telefon' => $telefon,
                                'timestamp' => date("d-m-Y"),
                                'ip' => $_SERVER['REMOTE_ADDR']
                            );
                            $db->insert('statystyki', $stat);

                            //Zapisz klienta
                            $formData = $this->_request->getPost();
                            $checkbox = preg_grep("/zgoda_([0-9])/i", array_keys($formData));
                            $przegladarka = $_SERVER['HTTP_USER_AGENT'];
                            historylog($imie, $email, $ip, $przegladarka, $checkbox);

                            $message = 1;

                        } else {
                            $message = 2;
                        }
                    }
                    //}
                }

            }
        } else {
            errorPage();
        }

        $array = array(
            'strona_id' => $this->page_id,
            'strona_h1' => $mieszkanie->nazwa,
            'strona_tytul' => ' - '.$inwestycja->nazwa.' - '.(isset($mieszkanie->nazwa)) ? ' - '.$mieszkanie->nazwa : ' - '.json_decode($mieszkanie->json)->nazwa,
            'seo_tytul' => (isset($mieszkanie->meta_tytul)) ? $mieszkanie->meta_tytul : json_decode($mieszkanie->json)->meta_tytul,
            'seo_opis' => (isset($mieszkanie->meta_opis)) ? $mieszkanie->meta_opis : json_decode($mieszkanie->json)->meta_opis,
            'seo_slowa' => (isset($mieszkanie->meta_slowa)) ? $mieszkanie->meta_slowa : json_decode($mieszkanie->json)->meta_slowa,
            'validation' => 1,
            'message' => $message,
            'inwestycja' => $inwestycja,
            'mieszkanie' => $mieszkanie,
            'next_mieszkanie' => $next_mieszkanie,
            'prev_mieszkanie' => $prev_mieszkanie,
            'oknoArray' => explode(',',$mieszkanie->okno)
        );
        $this->view->assign($array);
    }

}