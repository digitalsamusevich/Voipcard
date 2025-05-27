<?php
class ControllerExtensionModuleCyberQuestionAnswer extends Controller {
	public function index() {

	}
	public function getList() {
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$this->load->language('extension/module/cyber_question_answer');
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
		$data['lang_id'] = $this->config->get('config_language_id');
		$qadata = (array)$this->config->get('qadata');
		$data['qadata'] = $qadata;
		$data['add_question'] = $this->language->get('add_question');
		$data['text_answer_admin'] = $this->language->get('text_answer_admin');
		$data['text_write_a_question'] = $this->language->get('text_write_a_question');
		$data['entry_question'] = $this->language->get('entry_question');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_note_html'] = $this->language->get('text_note_html');
		$data['name_field'] = ($this->customer->isLogged()) ? $this->customer->getFirstName() : '';
		$data['telephone_field'] = ($this->customer->isLogged()) ? $this->customer->getTelephone() : '';
		$data['email_field'] = ($this->customer->isLogged()) ? $this->customer->getEmail() : '';
		if ($this->config->get($this->config->get('config_captcha') . '_status')) {
			$data['captcha_qa'] = $this->load->controller((VERSION >= 2.2) ? 'extension/captcha/' . $this->config->get('config_captcha') : 'captcha/' . $this->config->get('config_captcha'));
		} else {
			$data['captcha_qa'] = '';
		}
		if (isset($this->request->get['product_id'])) {
			$data['product_id'] = $this->request->get['product_id'];
		} else {
			$data['product_id'] = '0';
		}

		$this->load->language('product/product');

		$this->load->model('extension/module/cyber_question_answer');

		$data['text_no_question'] = $this->language->get('text_no_question');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['question_answer'] = array();

		$question_answer_total = $this->model_extension_module_cyber_question_answer->getTotalQuestionAnswer($this->request->get['product_id']);

		$results = $this->model_extension_module_cyber_question_answer->getQuestionAnswer($this->request->get['product_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['question_answer'][] = array(
				'name_field'     => $result['name_field'],
				'comment_field'  => $result['comment_field'],
				'comment_manager'=> $result['comment_manager'],
				'date_added' 	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
			);
		}

		$pagination = new Pagination();
		$pagination->total = $question_answer_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link('extension/module/cyber_question_answer/getList', 'product_id=' . $data['product_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($question_answer_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($question_answer_total - 5)) ? $question_answer_total : ((($page - 1) * 5) + 5), $question_answer_total, ceil($question_answer_total / 5));

		$this->response->setOutput($this->load->view('extension/module/question_answer', $data));
		} else {
		  $this->response->redirect($this->url->link('error/not_found', '', true));
		}
	}

  public function validateForm() {
  	if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$json = array();
		$this->language->load('extension/module/cyber_question_answer');
		$this->load->model('extension/module/cyber_question_answer');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$qadata = (array)$this->config->get('qadata');
			if (isset($qadata['name_field']) && $qadata['name_field_required'] == 1) {
				if ((utf8_strlen(trim($this->request->post['name_field'])) < 1) || (utf8_strlen(trim($this->request->post['name_field'])) > 32)) {
					$json['error']['name_field'] = $this->language->get('error_name_field');
				}
			}

			if (isset($qadata['telephone_field']) && $qadata['telephone_field_required'] == 1) {
				if ((utf8_strlen($this->request->post['telephone_field']) < 3) || (utf8_strlen($this->request->post['telephone_field']) > 20)) {
					$json['error']['telephone_field'] = $this->language->get('error_telephone_field');
				}
			}

			if (isset($qadata['email_field']) && $qadata['email_field_required'] == 1) {
				if (((utf8_strlen($this->request->post['email_field']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email_field']))) {
					$json['error']['email_field'] = $this->language->get('error_email_field');
				}
			}

			if ((utf8_strlen($this->request->post['comment_field']) < 3) || (utf8_strlen($this->request->post['comment_field']) > 350)) {
				$json['error']['comment_field'] = $this->language->get('error_comment_field');
			}

			if(isset($qadata['captcha_field'])){
				if ($this->config->get('captcha_' . $this->config->get('config_captcha') . '_status')) {
					$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');
					if ($captcha) {
					$json['error']['captcha'] = $captcha;
					}
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
				if (isset($this->request->post['qa_product_id'])) {
					$data_save_post['qa_product_id'] = $this->request->post['qa_product_id'];
				} else {
					$data_save_post['qa_product_id'] = '';
				}

			   $this->model_extension_module_cyber_question_answer->SaveQuestionAnswer($data_save_post);

			   $json['success'] = $this->language->get('text_success_send');

			  	if (isset($qadata['send_email_status'])) {
				  	if (!empty($qadata['email_question_answer'])) {
						$html_qadata['date_added'] = date('m/d/Y h:i:s a', time());
						$html_qadata['text_dateadded'] = $this->language->get('text_dateadded');
						$html_qadata['logo'] = $this->config->get('config_url').'image/'.$this->config->get('config_logo');
						$html_qadata['store_name'] = $this->config->get('config_name');
						$html_qadata['store_url'] = $this->config->get('config_url');
						$html_qadata['text_buyer'] = $this->language->get('text_buyer');
						$html_qadata['text_name_fields'] = $this->language->get('text_name_fields');
						$html_qadata['text_telephone_field'] = $this->language->get('text_telephone_field');
						$html_qadata['text_link_field'] = $this->language->get('text_link_field');
						$html_qadata['text_comment_field'] = $this->language->get('text_comment_field');
						$html_qadata['text_email_field'] = $this->language->get('text_email_field');

						$html_qadata['data_buyer'] = $data_save_post;
						$html = $this->load->view('mail/question_answer_mail', $html_qadata);
						$mail = new Mail($this->config->get('config_mail_engine'));
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
						$mail->smtp_username = $this->config->get('config_mail_smtp_username');
						$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
						$mail->smtp_port = $this->config->get('config_mail_smtp_port');
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
						$mail->setTo($qadata['email_question_answer']);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($this->config->get('config_name'));
						$mail->setSubject($this->language->get('subject'));
						$mail->setHtml($html);
						$mail->send();
					}
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
