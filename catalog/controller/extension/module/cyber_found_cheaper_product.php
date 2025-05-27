<?php
class ControllerExtensionModuleCyberFoundCheaperProduct extends Controller {
	public function index() {
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$data = array();
			if ($this->config->get('config_foundcheaper_id')) {
				$this->load->model('catalog/information');
				$this->load->language('cyberstore/lang');
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_foundcheaper_id'));

				if ($information_info) {
					$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_foundcheaper_id'), true), $information_info['title'], $information_info['title']);
				} else {
					$data['text_agree'] = '';
				}
			} else {
				$data['text_agree'] = '';
			}
			if (isset($this->request->get['product_id'])) {
				$data['product_id'] = $this->request->get['product_id'];
			} else {
				$data['product_id'] = '0';
			}

			$this->load->language('extension/module/cyber_found_cheaper_product');
			$this->load->model('catalog/product');
			$data['lang_id'] = $this->config->get('config_language_id');
			$fcpdata = (array)$this->config->get('fcpdata');
			$data['fcpdata'] = $fcpdata;
			$data['button_send_found_cheaper'] = $this->language->get('button_send_found_cheaper');
			$data['name_field'] = ($this->customer->isLogged()) ? $this->customer->getFirstName() : '';
			$data['telephone_field'] = ($this->customer->isLogged()) ? $this->customer->getTelephone() : '';
			$data['email_field'] = ($this->customer->isLogged()) ? $this->customer->getEmail() : '';
		  $this->response->setOutput($this->load->view('extension/module/found_cheaper_product', $data));
		} else {
		  $this->response->redirect($this->url->link('error/not_found', '', true));
		}
	}

	public function found_cheaper_product_confirm() {
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$json = array();
			$this->language->load('extension/module/cyber_found_cheaper_product');
			$this->load->model('extension/module/cyber_found_cheaper_product');

			$fcpdata = (array)$this->config->get('fcpdata');
			if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['action'])) {

				if (isset($fcpdata['name_field']) && $fcpdata['name_field_required'] == 1) {
					if ((utf8_strlen(trim($this->request->post['name_field'])) < 1) || (utf8_strlen(trim($this->request->post['name_field'])) > 32)) {
					$json['error']['name_field'] = $this->language->get('error_name_field');
					}
				}
				if (isset($fcpdata['telephone_field']) && $fcpdata['telephone_field_required'] == 1) {
					if ((utf8_strlen($this->request->post['telephone_field']) < 3) || (utf8_strlen($this->request->post['telephone_field']) > 20)) {
					$json['error']['telephone_field'] = $this->language->get('error_telephone_field');
					}
				}
				if (isset($fcpdata['link_field']) && $fcpdata['link_field_required'] == 1) {
					if (empty($this->request->post['link_field'])) {
						$json['error']['link_field'] = $this->language->get('error_link_field');
					}
				}
				if (isset($fcpdata['email_field']) && $fcpdata['email_field_required'] == 1) {
					if (((utf8_strlen($this->request->post['email_field']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email_field']))) {
						$json['error']['email_field'] = $this->language->get('error_email_field');
					}
				}
				if (isset($fcpdata['comment_field']) && $fcpdata['comment_field_required'] == 1) {
					if ((utf8_strlen($this->request->post['comment_field']) < 3) || (utf8_strlen($this->request->post['comment_field']) > 350)) {
						$json['error']['comment_field'] = $this->language->get('error_comment_field');
					}
				}

				// Agree to terms
				if ($this->config->get('config_foundcheaper_id')) {
				  $this->load->model('catalog/information');
				  $this->load->language('cyberstore/lang');
				  $information_info = $this->model_catalog_information->getInformation($this->config->get('config_foundcheaper_id'));

				  if ($information_info && !isset($this->request->post['agree'])) {
					$json['error']['error_agree'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				  }
				}


				if (!isset($json['error'])) {
					if (isset($this->request->post['name_field'])) {
						$data_save_post['name_field'] = $this->request->post['name_field'];
					} else {
						$data_save_post['name_field'] = '';
					}
					if (isset($this->request->post['telephone_field'])) {
						$data_save_post['telephone_field'] = $this->request->post['telephone_field'];
					} else {
						$data_save_post['telephone_field'] = '';
					}
					if (isset($this->request->post['link_field'])) {
						$data_save_post['link_field'] = $this->request->post['link_field'];
					} else {
						$data_save_post['link_field'] = '';
					}
					if (isset($this->request->post['comment_field'])) {
						$data_save_post['comment_field'] = $this->request->post['comment_field'];
					} else {
						$data_save_post['comment_field'] = '';
					}
					if (isset($this->request->post['email_field'])) {
						$data_save_post['email_field'] = $this->request->post['email_field'];
					} else {
						$data_save_post['email_field'] = '';
					}
					if (isset($this->request->post['fcp_product_id'])) {
						$data_save_post['fcp_product_id'] = $this->request->post['fcp_product_id'];
					} else {
						$data_save_post['fcp_product_id'] = '';
					}

					$this->model_extension_module_cyber_found_cheaper_product->SaveFoundCheaper($data_save_post);

					$json['success'] = $this->language->get('text_success_send');

					if ($fcpdata['send_email_status']) {
						$html_fcpdata['date_added'] = date('m/d/Y h:i:s a', time());
						$html_fcpdata['text_dateadded'] = $this->language->get('text_dateadded');
						$html_fcpdata['logo'] = $this->config->get('config_url').'image/'.$this->config->get('config_logo');
						$html_fcpdata['store_name'] = $this->config->get('config_name');
						$html_fcpdata['store_url'] = $this->config->get('config_url');
						$html_fcpdata['text_buyer'] = $this->language->get('text_buyer');
						$html_fcpdata['text_name_fields'] = $this->language->get('text_name_fields');
						$html_fcpdata['text_telephone_field'] = $this->language->get('text_telephone_field');
						$html_fcpdata['text_link_field'] = $this->language->get('text_link_field');
						$html_fcpdata['text_comment_field'] = $this->language->get('text_comment_field');
						$html_fcpdata['text_email_field'] = $this->language->get('text_email_field');

						$html_fcpdata['data_buyer']   = $data_save_post;
						$html = $this->load->view('mail/found_cheaper_product_mail', $html_fcpdata);

						$mail = new Mail($this->config->get('config_mail_engine'));
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
						$mail->smtp_username = $this->config->get('config_mail_smtp_username');
						$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
						$mail->smtp_port = $this->config->get('config_mail_smtp_port');
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

						$mail->setTo($fcpdata['email_found_cheaper']);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($this->config->get('config_name'));
						$mail->setSubject($this->language->get('subject'));
						$mail->setHtml($html);
						$mail->send();
					}
				}
			}
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		} else {
			$this->response->redirect($this->url->link('error/not_found', '', true));
		}
	}
}
