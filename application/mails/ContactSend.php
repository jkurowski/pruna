<?php
class Mails_ContactSend extends Zend_Db_Table_Abstract
{
    protected $_db_table;

    public function init()
    {
        try {
            $this->_db_table = Zend_Registry::get('db');
            $this->_db_table->setFetchMode(Zend_Db::FETCH_OBJ);
        } catch (Zend_Exception $e) {}
    }

    public function send($formData) {
        $remoteAddress = $_SERVER['REMOTE_ADDR'];
        $browser = $_SERVER['HTTP_USER_AGENT'];

        $ipQuery = $this->_db_table->select()->from('blokowanie')->where('ip = ?', $remoteAddress);
        $ip = $this->_db_table->fetchRow($ipQuery);

        if(!$ip) {

            if($formData['imie'] && $formData['email']) {

                $name = $formData['imie'];
                $email = $formData['email'];
                $phone = $formData['telefon'];
                $message = $formData['wiadomosc'];

                $ustawieniaQuery = $this->_db_table->select()->from('ustawienia');
                $ustawienia = $this->_db_table->fetchRow($ustawieniaQuery);

                $emailarray = array(
                    'nazwa_strony' => $ustawienia->nazwa,
                    'imie' => $name,
                    'email' => $email,
                    'telefon' => $phone,
                    'wiadomosc' => $message,
                    'ip' => $remoteAddress
                );

                $view = new Zend_View();
                $view->setScriptPath( APPLICATION_PATH . '/modules/default/views/scripts/email/' );
                $view->assign($emailarray);

                $mail = new Zend_Mail('UTF-8');
                $mail
                    ->setFrom($ustawienia->email, $name)
                    ->addTo($ustawienia->email, 'Adres odbiorcy')
                    ->setReplyTo($email, $name)
                    ->setSubject($ustawienia->domena.' - Zapytanie ze strony www - Kontakt');
                $mail->setBodyHtml($view->render( 'kontakt.phtml'));
                $mail->setBodyText($view->render( 'kontakt-txt.phtml'));

                $mail->send();

                //Zapisz statystyki
                $stat = array(
                    'akcja' => 1,
                    'miejsce' => 4,
                    'data' => date("d.m.Y - H:i:s"),
                    'timestamp' => date("d-m-Y"),
                    'godz' => date("H"),
                    'dzien' => date("d"),
                    'msc' => date("m"),
                    'rok' => date("Y"),
                    'tekst' => $message,
                    'email' => $email,
                    'telefon' => $phone,
                    'ip' => $remoteAddress
                );
                $this->_db_table->insert('statystyki', $stat);

                //Zapisz klienta
                $checkbox = preg_grep("/zgoda_([0-9])/i", array_keys($formData));
                historylog($name, $email, $remoteAddress, $browser, $checkbox);

                return 1;

            } else {
                return 2;
            }
        }
    }
}