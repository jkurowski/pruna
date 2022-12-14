<?php
require_once 'Zend/Controller/Action.php';
abstract class kCMS_Admin extends Zend_Controller_Action {

    public function init() {
        try {
            $db = Zend_Registry::get('db');
        } catch (Zend_Exception $e) {

        }

        $header = $db->fetchRow($db->select()->from('ustawienia'));
        $site_array = array(
            'header' => $header,
            'user' => Zend_Auth::getInstance()->getIdentity()
        );
        $this->view->assign($site_array);

        //******** status inwestycji ********//
        function statusInwestycji($numer){
            switch ($numer) {
                case '1':
                    return "Inwestycja aktualna";
                case '2':
                    return "Inwestycja zrealizowana";
                case '3':
                    return "Inwestycja planowana";
				case '4':
                    return "Inwestycja ukryta";
            }
        }
        //******** status inwestycji ********//

        //******** typ inwestycji ********//
        function typ($numer){
            switch ($numer) {
                case '1':
                    return "Inwestycja osiedlowa";
                case '2':
                    return "Inwestycja budynkowa";
                case '3':
                    return "Inwestycja z domami";
                case '4':
                    return "Inna oferta";
            }
        }
        //******** typ inwestycji ********//

        //******** status ********//
        function status($status) {
            if($status == 1)
            { $result = "<span class=\"online\"></span>"; }
            else
            { $result = "<span class=\"offline\"></span>"; }
            return $result;
        }
        //******** status ********//

        //******** google preview ********//
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
                $result = substr($string, 0, $len) . '...';
            } else {
                $result = $string;
            }
            return $result;
        }
        //******** google preview ********//

        //******** slug ********//
        function slug($value) {
            $value = strtr($value, array('??' => 'a', '??' => 'c', '??' => 'e', '??' => 'l', '??' => 'n', '??' => 'o', '??' => 's', '??' => 'z', '??' => 'z', '??' => 'A', '??' => 'C', '??' => 'E', '??' => 'L', '??' => 'N', '??' => 'O', '??' => 'S', '??' => 'Z', '??' => 'Z'));
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
		
		function dostep($id) {
			$login = Zend_Auth::getInstance()->getIdentity();
			$inwestArray = explode(',', $login->inwestycje);
			if(!in_array($id, $inwestArray)){
	
				$_redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
				$_redirector->gotoUrl('/admin/inwestycje/'); 
			}
		}
	}
}

?>