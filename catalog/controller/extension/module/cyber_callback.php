<?php
class ControllerExtensionModuleCyberCallback extends Controller {
	private $error = array();

	public function index() {
	if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		if ($this->config->get('config_callback_id')) {
			$this->load->model('catalog/information');
			$this->load->language('cyberstore/lang');
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_callback_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_callback_id'), true), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}
		$data['lang_datetimepicker'] = $this->session->data['language'];

		$this->load->model('extension/module/cyber_callback');
		$this->load->language('extension/module/cyber_callback');
		$data['sendthis'] = $this->language->get('sendthis');
     	$data['comment_buyer'] = $this->language->get('comment_buyer');
     	$data['email_buyer'] = $this->language->get('email_buyer');
     	$data['namew'] = $this->language->get('namew');
     	$data['phonew'] = $this->language->get('phonew');
     	$data['button_send'] = $this->language->get('button_send');
     	$data['cancel'] = $this->language->get('cancel');
     	$data['when_you_call_back'] = $this->language->get('when_you_call_back');
     	$data['text_you_comment'] = $this->language->get('text_you_comment');
		$data['callbackpro'] = $this->config->get('callbackpro');
		$data['select_design_theme_callback_left_or_right'] = $data['callbackpro']['select_design_theme_callback_left_or_right'];
		$json = array();
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['action'])) {
			if ($this->validate()) {
				$data = array();
				if (isset($this->request->post['name'])) {
  		    		$data['name'] = $this->request->post['name'];
				} else {
      				$data['name'] = '';
    			}
				if (isset($this->request->post['phone'])) {
      				$data['phone'] = $this->request->post['phone'];
				} else {
      				$data['phone'] = '';
    			}
				if (isset($this->request->post['comment_buyer'])) {
      				$data['comment_buyer'] = $this->request->post['comment_buyer'];
				} else {
      				$data['comment_buyer'] = '';
    			}
				if (isset($this->request->post['email_buyer'])) {
      				$data['email_buyer'] = $this->request->post['email_buyer'];
				} else {
      				$data['email_buyer'] = '';
    			}
				if (isset($this->request->post['date_callback'])) {
      				$data['date_callback'] = $this->request->post['date_callback'];
				} else {
      				$data['date_callback'] = '';
    			}
				if (isset($this->request->post['time_callback_on'])) {
      				$data['time_callback_on'] = $this->request->post['time_callback_on'];
				} else {
      				$data['time_callback_on'] = '';
    			}
				if (isset($this->request->post['time_callback_off'])) {
      				$data['time_callback_off'] = $this->request->post['time_callback_off'];
				} else {
      				$data['time_callback_off'] = '';
    			}
				if (isset($this->request->post['url_site'])) {
                    $data['url_site'] = $this->request->post['url_site'];
                } else {
                    $data['url_site'] = '';
                }
				if (isset($this->request->post['topic_callback_send'])) {
                    $data['topic_callback_send'] = $this->request->post['topic_callback_send'];
                } else {
                    $data['topic_callback_send'] = '';
                }
				$data['store_name'] = $this->config->get('config_name');
				$data['language_id'] = $this->config->get('config_language_id');
				$results = $this->model_extension_module_cyber_callback->addCallback($data);
				$callbackpro = $this->config->get('callbackpro');
				$config_on_off_send_me_mail_callback = (isset($callbackpro['config_on_off_send_me_mail_callback']) ? $callbackpro['config_on_off_send_me_mail_callback'] : '');
				$config_you_email_callback = $callbackpro['config_you_email_callback'];
					if($config_on_off_send_me_mail_callback =='1'){
						if($config_you_email_callback != ''){
							$this->sendMail($data);
						}
					}
					if(isset($callbackpro['config_send_sms_on_off'])){
						$this->sendSms($data);
					}
				$json['success'] = $this->language->get('ok');
			}else{
				$json['warning'] = $this->error;
			}

			return $this->response->setOutput(json_encode($json));
		}



			$data['lang_id'] = $this->config->get('config_language_id');

			$this->load->model('tool/image');
			if(!empty($data['callbackpro']['social_icon'])) {
				$data['social_data'] = $data['callbackpro']['social_icon'];
			} else {
				$data['social_data'] = array();
			}
			$data['social_icons_contact'] = array();
			foreach ($data['social_data'] as $result) {
				if (is_file(DIR_IMAGE . $result['image'])) {
					$thumb = $result['image'];
				} else {
					$thumb = '';
				}

				$data['social_icons_contact'][] = array(
					'name'      => $result['name'],
					'thumb'      => $this->model_tool_image->resize($thumb, 24, 24),
				);
			}
			if(!empty($data['callbackpro']['config_phones'])) {
				$config_phones = $data['callbackpro']['config_phones'];
			} else {
				$config_phones = array();
			}
			$data['config_telephones'] = array();
			if($config_phones){
				foreach ($config_phones as $config_phone) {
					if ($config_phone['image']) {
						$image = $this->model_tool_image->resize($config_phone['image'], 24, 24);
					} else {
						$image = '';
					}
					$data['config_telephones'][] = array(
						'phone'     => $config_phone['phone'],
						'thumb'     => $image,
					);
				}
			}
			$timenow = date("H:i");
			if(($timenow > $data['callbackpro']['skype_date_start']) && ($timenow < $data['callbackpro']['skype_date_end'])) {
				$data['skype_status'] = '1';
			} else {
				$data['skype_status'] = '0';
			}
			if(!empty($data['callbackpro']['call_topic'])) {
				$data['call_topic_data'] = $data['callbackpro']['call_topic'];
			} else {
				$data['call_topic_data'] = array();
			}
				if (is_file(DIR_IMAGE . $data['callbackpro']['main_image_callback'])) {
					$data['main_image_callback'] = $this->model_tool_image->resize($data['callbackpro']['main_image_callback'], 120, 120);
				} else {
					$data['main_image_callback'] = '';
				}

			$this->response->setOutput($this->load->view('extension/module/callback', $data));
		} else {
		  $this->response->redirect($this->url->link('error/not_found', '', true));
		}
  	}

  	private function validate() {
   		$this->load->language('extension/module/callback');
			$callbackpro = $this->config->get('callbackpro');
			$config_fields_firstname_requared_cb = (isset($callbackpro['config_fields_firstname_requared_cb']) ? 1 : 0);
			$config_on_off_fields_firstname_cb = (isset($callbackpro['config_on_off_fields_firstname_cb']) ? 1 : 0);
			if(($config_fields_firstname_requared_cb =='1') && $config_on_off_fields_firstname_cb =='1'){
				if ((strlen(utf8_decode($this->request->post['name'])) < 1) || (strlen(utf8_decode($this->request->post['name'])) > 32)) {
					$this->error['name'] = $this->language->get('mister');
				}
			}
			$config_fields_phone_requared_cb = (isset($callbackpro['config_fields_phone_requared_cb']) ? 1 : 0);
			$config_on_off_fields_phone_cb = (isset($callbackpro['config_on_off_fields_phone_cb']) ? 1 : 0);
			if(($config_fields_phone_requared_cb =='1') && $config_on_off_fields_phone_cb =='1'){
				if ((strlen(utf8_decode($this->request->post['phone'])) < 3) || (strlen(utf8_decode($this->request->post['phone'])) > 32)) {
					$this->error['phone'] = $this->language->get('wrongnumber');
				}
			}
			$config_fields_comment_requared_cb = (isset($callbackpro['config_fields_comment_requared_cb']) ? 1 : 0);
			$config_on_off_fields_comment_cb = (isset($callbackpro['config_on_off_fields_comment_cb']) ? 1 : 0);
			if(($config_fields_comment_requared_cb =='1') && $config_on_off_fields_comment_cb == '1'){
				if ((strlen(utf8_decode($this->request->post['comment_buyer'])) < 1) || (strlen(utf8_decode($this->request->post['comment_buyer'])) > 400)) {
					$this->error['comment_buyer'] = $this->language->get('comment_buyer_error');
				}
			}
			$config_fields_email_requared_cb = (isset($callbackpro['config_fields_email_requared_cb']) ? 1 : 0);
			$config_on_off_fields_email_cb = (isset($callbackpro['config_on_off_fields_email_cb']) ? 1 : 0);
			if(($config_fields_email_requared_cb =='1') && $config_on_off_fields_email_cb == '1'){
				if(!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $this->request->post['email_buyer'])){
						$this->error['email_error'] =  $this->language->get('email_buyer_error');
				}
			}
			// Agree to terms
			if ($this->config->get('config_callback_id')) {
				$this->load->model('catalog/information');
				$this->load->language('cyberstore/lang');
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_callback_id'));

				if ($information_info && !isset($this->request->post['agree'])) {
					$this->error['error_agree'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}
    		if (!$this->error) {
     	 		return true;
    		} else {
     			return false;
   	 	}
	}
	private function getCustomFields($order_info, $varabliesd) {
		$instros = explode('~', $varabliesd);
		$instroz = "";
		foreach ($instros as $instro) {
			if ($instro == 'totals' || isset($order_info[$instro]) ){
				if ($instro == 'totals'){
				    $instro_other = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], true);
				}
				if(isset($order_info[$instro])){
					$instro_other = $order_info[$instro];
				}
			}
			else {
			    $instro_other = nl2br(htmlspecialchars_decode($instro));
			}
			    $instroz .=  $instro_other;
		}
		return $instroz;
	}
	private function sendMail($data) {
		$this->language->load('extension/module/cyber_callback');
		$text = '';
		$callbackpro = $this->config->get('callbackpro');
		$subject_get = $this->getCustomFields($data, $callbackpro['quickorder_subject_me_callback'][$data['language_id']]);
		if ((strlen(utf8_decode($subject_get)) > 5)){
			$subject = $subject_get;
		} else {
			$subject = $this->language->get('subject');
		}

		$html = $this->getCustomFields($data, $callbackpro['quickorder_description_me_callback'][$data['language_id']]). "\n";

		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($callbackpro['config_you_email_callback']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($data['store_name'], ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml(html_entity_decode($html, ENT_QUOTES, 'UTF-8'));
		$mail->setText($text);
		$mail->send();
	}

	private function sendSms($data) {
		$callbackpro = $this->config->get('callbackpro');
		if(isset($callbackpro['config_send_sms_on_off'])){
			$login = $callbackpro['config_login_send_sms'];
			$password = $callbackpro['config_pass_send_sms'];
			$number = $callbackpro['config_phone_number_send_sms'];

			$message = $data['name']."\n".$data['phone'];
			$this->send($login, $password, $number, $message,'');
		}
	}

	public function send($login, $password, $number, $message, $sender, $query = '')
	{
			$res = $this->_read_url('http://my.smscab.ru/sys/send.php?login='.urlencode($login).'&psw='.html_entity_decode($password).
					'&phones='.urlencode($number).'&mes='.urlencode(html_entity_decode(str_replace('\n', "\n", $message), ENT_QUOTES, 'UTF-8')).
					($sender ? '&sender='.urlencode($sender) : '').'&maxsms='.$this->config->get('oc_smsc_maxsms').
					'&cost=3&fmt=1&charset=utf-8&userip='.$_SERVER['REMOTE_ADDR'].($query ? '&'.$query : ''));

		$log = fopen(DIR_LOGS . 'smsc.log', 'w');
		fwrite($log, ($res ? $res : 0)."\nlogin=$login\npassword=$password\nphone=$number\nsender=$sender\nmessage=$message");
		fclose($log);

		return $res;
	}



	private function _read_url($url)
	{
		$ret = "";

		if (function_exists("curl_init"))
		{
			static $c = 0; // keepalive

			if (!$c) {
				$c = curl_init();
				curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($c, CURLOPT_TIMEOUT, 10);
				curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
			}

			curl_setopt($c, CURLOPT_URL, $url);

			$ret = curl_exec($c);
		}
		elseif (function_exists("fsockopen") && strncmp($url, 'http:', 5) == 0) // not https
		{
			$m = parse_url($url);

			$fp = fsockopen($m["host"], 80, $errno, $errstr, 10);

			if ($fp) {
				fwrite($fp, "GET $m[path]?$m[query] HTTP/1.1\r\nHost: my.smscab.ru\r\nUser-Agent: PHP\r\nConnection: Close\r\n\r\n");

				while (!feof($fp))
					$ret = fgets($fp, 1024);

				fclose($fp);
			}
		}
		else
			$ret = file_get_contents($url);

		return $ret;
	}
}
?>
