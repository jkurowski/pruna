<?php
require_once 'Zend/Controller/Action.php';
abstract class kCMS_Site extends Zend_Controller_Action {

    public function init() {
        try {
            $db = Zend_Registry::get('db');
        } catch (Zend_Exception $e) {

        }

        $front = Zend_Controller_Front::getInstance();
        $request = $front->getRequest();

		$mainmenu = new kCMS_MenuBuilder();
		Zend_Registry::set('mainmenu', $mainmenu);

        $header = $db->fetchRow($db->select()->from('ustawienia'));
        $rodo = $db->fetchRow($db->select()->from('rodo_ustawienia')->where('id =?', 1));
        $rodo_rules = $db->fetchAll($db->select()->from('rodo_regulki')->order('sort ASC')->where('status = ?', 1));

        $sitearray = array(
            'header' => $header,
            'rodo' => $rodo,
            'rodo_rules' => $rodo_rules,
            'current_action' => $request->getActionName(),
            'current_controller' => $request->getControllerName()
        );
        $this->view->assign($sitearray);

		//******** RODO z -> ********//
		function historylog($nazwa, $mail, $ip, $przegladarka, $regulki) {
			$db = Zend_Registry::get('db');
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$klient = $db->fetchRow($db->select()->from('rodo_klient')->where('mail =?', $mail));
			if($klient){
				$historia = array(
					'data_aktualizacji' => date("Y-m-d H:i:s"),
					'ip' => $ip,
					'host' => gethostbyaddr($ip),
					'przegladarka' => $przegladarka,
				);

				$db->update('rodo_klient', $historia, 'id = '.$klient->id);
				
				foreach($regulki as $key => $number){
					$getId = preg_replace('/[^0-9]/', '', $number);

					$regulkaArchiv = $db->fetchRow($db->select()->from('rodo_regulki_klient')->where('id_regulka = ?', $getId)->where('id_klient = ?', $klient->id));

					if($regulkaArchiv){

						$arrayRegulka = json_decode(json_encode($regulkaArchiv),true);
						unset($arrayRegulka['id']);
						$arrayRegulka['data_anulowania'] = strtotime(date("Y-m-d H:i:s"));
		
						$db->insert('rodo_regulki_archiwum', $arrayRegulka);
		
						$regulka = $db->fetchRow($db->select()->from('rodo_regulki')->where('id = ?', $getId));
						$dataRodo = array(
							'id_regulka' => $getId,
							'id_klient' => $klient->id,
							'ip' => $ip,
							'data_podpisania' => strtotime(date("Y-m-d H:i:s")),
							'termin' => strtotime("+".$regulka->termin." months", strtotime(date("y-m-d"))),
							'miesiace' => $regulka->termin,
							'status' => 1
						);
						$where = array(
							'id_regulka = ?' => $getId,
							'id_klient = ?' => $klient->id
						);
						$db->update('rodo_regulki_klient', $dataRodo, $where);
				
					} else {

						$regulka = $db->fetchRow($db->select()->from('rodo_regulki')->where('id = ?', $getId));
						$dataRodo = array(
							'id_regulka' => $getId,
							'id_klient' => $klient->id,
							'ip' => $ip,
							'data_podpisania' => strtotime(date("Y-m-d H:i:s")),
							'termin' => strtotime("+".$regulka->termin." months", strtotime(date("y-m-d"))),
							'miesiace' => $regulka->termin,
							'status' => 1
						);	
						$db->insert('rodo_regulki_klient', $dataRodo);
					}
				}

			} else {
				$historia = array(
					'nazwa' => $nazwa,
					'mail' => $mail,
					'ip' => $ip,
					'host' => gethostbyaddr($ip),
					'przegladarka' => $przegladarka,
					'data_dodania' => date("Y-m-d H:i:s")
				);

				$db->insert('rodo_klient', $historia);
				$lastId = $db->lastInsertId();
				
				$newklient = $db->fetchRow($db->select()->from('rodo_klient')->where('id =?', $lastId));
				
				foreach($regulki as $key => $number){
					$getId = preg_replace('/[^0-9]/', '', $number);

					$regulkaArchiv = $db->fetchRow($db->select()->from('rodo_regulki_klient')->where('id_regulka = ?', $getId)->where('id_klient = ?', $newklient->id));

					if($regulkaArchiv){

						$arrayRegulka = json_decode(json_encode($regulkaArchiv),true);
						unset($arrayRegulka['id']);
						$arrayRegulka['data_anulowania'] = strtotime(date("Y-m-d H:i:s"));
		
						$db->insert('rodo_regulki_archiwum', $arrayRegulka);
		
						$regulka = $db->fetchRow($db->select()->from('rodo_regulki')->where('id = ?', $getId));
						$dataRodo = array(
							'id_regulka' => $getId,
							'id_klient' => $newklient->id,
							'ip' => $ip,
							'data_podpisania' => strtotime(date("Y-m-d H:i:s")),
							'termin' => strtotime("+".$regulka->termin." months", strtotime(date("y-m-d"))),
							'miesiace' => $regulka->termin,
							'status' => 1
						);

						$where = array(
							'id_regulka = ?' => $getId,
							'id_klient = ?' => $newklient->id
						);
						$db->update('rodo_regulki_klient', $dataRodo, $where);
				
					} else {

						$regulka = $db->fetchRow($db->select()->from('rodo_regulki')->where('id = ?', $getId));
						$dataRodo = array(
							'id_regulka' => $getId,
							'id_klient' => $newklient->id,
							'ip' => $ip,
							'data_podpisania' => strtotime(date("Y-m-d H:i:s")),
							'termin' => strtotime("+".$regulka->termin." months", strtotime(date("y-m-d"))),
							'miesiace' => $regulka->termin,
							'status' => 1
						);	
						$db->insert('rodo_regulki_klient', $dataRodo);
					}
				}

			}
		}
		//******** RODO ********//

		//******** Google reCAPTCHA ********//
		function getOptions()
		{
			$front = Zend_Controller_Front::getInstance();
			$bootstrap = $front->getParam('bootstrap');
			if (null === $bootstrap) {
				throw new Exception('Unable to find bootstrap');
			}

			return $bootstrap->getOptions();
		}
		
		function getRecaptchaBody(){
			$config = getOptions();
			$key = $config['google']['recaptcha']['pagekey'];
			if($key){
				$url = "<script src='https://www.google.com/recaptcha/api.js?render=".$key."'></script>";
				return $url;
			} else {
				throw new Exception('Unable to find pagekey in application.ini');
			}
		}
		
		function getRecaptchaForm($action){
			$config = getOptions();
			$key = $config['google']['recaptcha']['pagekey'];
			if($key){
				$script = "<script>grecaptcha.ready(function(){grecaptcha.execute(\"".$key."\",{action:\"".$action."\"}).then(function(a){document.getElementById(\"g-recaptcha-response\").value=a})});</script>";
				$script .= "<input type=\"hidden\" id=\"g-recaptcha-response\" name=\"g-recaptcha-response\">";
				return $script;
			} else {
				throw new Exception('Unable to find pagekey in application.ini');
			}
		}
				
		function getRecaptchaCheck($response){
			$config = getOptions();
			$key = $config['google']['recaptcha']['secret'];
			if($key){
				if($response){
					$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$key.'&response='.$response);
					$responseData = json_decode($verifyResponse);

					if($responseData->success) {
						return true;
					}
				} else {
					throw new Exception('Could not get recaptcha response');
				}
			} else {
				throw new Exception('Unable to find pagekey in application.ini');
			}
		}
		//******** Google reCAPTCHA ********//

        //******** Parse html ********//
        function gallery($input) {
            $db = Zend_Registry::get('db');
            $images = $db->fetchAll($db->select()->from('galeria_zdjecia')->order( 'sort ASC' )->where('id_gal =?', $input[2]));
            $front = Zend_Controller_Front::getInstance();
            $baseUrl = $front->getRequest()->getBaseUrl();

            if($input[1] == 'galeria') {
                $html = '<div class="row justify-content-center gallery-thumbs">';
                foreach ($images as $value) {
                    $html.= '<div class="col-6 col-sm-4 col-lg-3 col-3-gallery"><div class="col-gallery-thumb"><a href="'.$baseUrl.'/files/galeria/big/'.$value->plik.'" class="swipebox" rel="gallery-1'.$input[2].'" title=""><img src="'.$baseUrl.'/files/galeria/thumbs/'.$value->plik.'"><div></div></a></div></div>';
                }
                $html.= '</div>';
            }
            if($input[1] == 'slider') {
                $html= '<div class="row"><div class="col-12"><div class="sliderWrapper"><ul class="list-unstyled mb-0 clearfix">';
                foreach ($images as $value) {
                    $html.= '<li><a href="'.$baseUrl.'/files/galeria/big/'.$value->plik.'" title="" class="swipebox" rel="gallery-2'.$input[2].'"><img src="'.$baseUrl.'/files/galeria/big/'.$value->plik.'" alt="" /></a></li>';
                }
                $html.= '</ul></div></div></div>';
            }
            if($input[1] == 'karuzela') {
                $html= '<div class="carouselWrapper"><ul class="list-unstyled mb-0 clearfix" data-slick=\'{"slidesToShow": 4}\'>';
                foreach ($images as $value) {
                    $html.= '<li><a href="'.$baseUrl.'/files/galeria/big/'.$value->plik.'" title="" class="swipebox" rel="gallery-3'.$input[2].'"><img src="'.$baseUrl.'/files/galeria/thumbs/'.$value->plik.'" alt="" /></a></li>';
                }
                $html.= '</ul></div>';
            }
            return($html);
        }

        function parse($input) {
            $input = preg_replace_callback('/\[galeria=(.*)](.*)\[\/galeria\]/', 'gallery', $input);
            $input = str_replace("</div></p>","</div>",$input);
            $input = str_replace("<p><div","<div",$input);
            return $input;
        }
        //******** Parse html ********//

        //******** 404 redirect ********//
        function errorPage()
        {
            $front = Zend_Controller_Front::getInstance()->getRequest();
            $response = Zend_Controller_Front::getInstance()->getResponse();

            $layout = Zend_Layout::getMvcInstance();
            $view = $layout->getView();
            $array = array(
                'seo_tytul' => "Strona nie została znaleziona - błąd 404",
                'strona_nazwa' => "Błąd 404",
                'nofollow' => 1,
            );
            $view ->assign($array);

            $front->setModuleName('default')->setControllerName('error')->setActionName('error');
            $response->setHttpResponseCode(404)->setRawHeader('HTTP/1.1 404 Not Found');
        }
        //******** 404 redirect ********//

        //******** dd ********//
        function dd($code)
        {
            $code = Zend_Debug::dump($code, $label = null, $echo = false);
            $code = html_entity_decode($code);
            $str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $code);
            $str = str_replace(array('<?', '?>', '<%', '%>', '\\', '</script>'), array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'), $str);
            $str = '<?php ' . $str . ' ?>';
            $str = highlight_string($str, TRUE);
            if (abs(PHP_VERSION) < 5) {
                $str = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $str);
                $str = preg_replace('#color="(.*?)"#', 'style="color: \\1"', $str);
            }
            $str = preg_replace('/<span style="color: #([A-Z0-9]+)">&lt;\\?php(&nbsp;| )/i', '<span style="color: #$1">', $str);
            $str = preg_replace('/(<span style="color: #[A-Z0-9]+">.*?)\\?&gt;<\\/span>\\n<\\/span>\\n<\\/code>/is', "\$1</span>\n</span>\n</code>", $str);
            $str = preg_replace('/<span style="color: #[A-Z0-9]+"\\><\\/span>/i', '', $str);
            $str = str_replace(array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'), array('&lt;?', '?&gt;', '&lt;%', '%&gt;', '\\', '&lt;/script&gt;'), $str);
            echo $str;
            exit;
        }
        //******** dd ********//

        //******** cut the words ********//
        function previewParser($string, $len) {
            $pattern_clear = array(
                '@(\[)(.*?)(\])@si',
                '@(\[/)(.*?)(\])@si'
            );

            $replace_clear = array(
                '',
                ''
            );

            $string = preg_replace($pattern_clear, $replace_clear, $string);
            if (strlen($string) > $len) {
                $result = mb_substr($string, 0, $len, "UTF-8") . ' ...';
            } else {
                $result = $string;
            }
            return $result;
        }
        //******** cut the words ********//

        //******** slug ********//
        function slug($value) {
            $value = strtr($value, array('ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z', 'ż' => 'z', 'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'E', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'O', 'Ś' => 'S', 'Ź' => 'Z', 'Ż' => 'Z'));
            $value = str_replace(' ', '-', trim($value));
            $value = preg_replace('/[^a-zA-Z0-9\-_]/', '', (string) $value);
            $value = preg_replace('/[\-]+/', '-', $value);
            $value = stripslashes($value);
            return urlencode(strtolower($value));
        }
        //******** slug ********//

        //******** image slug ********//
        function slugImg($title, $file) {
            $slug = slug($title);
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            return $slug.'.'.$ext;
        }
        //******** image slug ********//
	}
}
?>