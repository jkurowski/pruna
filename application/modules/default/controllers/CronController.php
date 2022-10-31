<?php
class Default_CronController extends kCMS_Site
{

    public function preDispatch() {

        function statusCRM($numer){
            switch ($numer) {
                case '1':
                    return 1;
                case '2':
                case '3':
                    return 2;
                case '4':
                case '5':
                case '6':
                case '7':
                case '8':
                    return 3;
                case '9':
                    return 4;
            }
        }

    }

    public function indexAction() {
        $this->getHelper('Layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $db = Zend_Registry::get('db');

        $url = 'https://dvme.voxdeveloper.com/webservice/realestatestatuslist/api_key/f7b41fd37fd18cda37d9ed1a58b5c7992471b4e8/investment_id/11';

        $headers = get_headers($url, 1);
        if ($headers[0] == 'HTTP/1.1 200 OK') {
            $feed = file_get_contents($url);
            $xml = simplexml_load_string($feed);

            foreach($xml as $a){
                if($a->type_id == 1){

                    $room_data = array(
                        'id' => (int) $a->id,
                        'metry' => (string) $a->area,
                        'ogrodek' => (string) $a->ogrod,
                        'szukaj_metry' => (string) round($a->area),
                        'cena' => (string) $a->price,
                        'szukaj_cena' => round($a->price),
                        'cena_m' => (string) $a->pricemkw,
                        'cena_netto' => (string) $a->price_net,
                        'pokoje' => (int) $a->rooms,
                        'status' => statusCRM($a->status_id)
                    );

//                     echo '<pre>';
//                     print_r($room_data);
//                     echo '</pre>';

                    $db->update('inwestycje_powierzchnia', $room_data, 'id_crm = '.(int) $a->id);
                }
            }

            error_log(date('Y-m-d h:i:s').' - Cron 1 wykonany'.PHP_EOL, 3, "cron-jobs.log");

        } else {

            error_log(date('Y-m-d h:i:s').' - Brak połączenia z CRM'.PHP_EOL, 3, "cron-jobs.log");
            echo 'Brak połączenia z CRM';
        }
    }
}