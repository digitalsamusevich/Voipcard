<?php
class ControllerCommonLoginModal extends Controller {
	private $error = array();

	public function index() {
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->language('cyberstore/lang');
			$data['text_login'] = $this->language->get('text_login');
			$data['entry_email'] = $this->language->get('entry_email');
			$data['entry_password'] = $this->language->get('entry_password');
			$data['text_register'] = $this->language->get('text_register');
			$data['text_forgotten'] = $this->language->get('text_forgotten');
			$data['button_login'] = $this->language->get('button_login');

			$data['register'] = $this->url->link('account/register', '', true);
			$data['forgotten'] = $this->url->link('account/forgotten', '', true);

			$this->response->setOutput($this->load->view('common/login_modal', $data));
		} else {
		  $this->response->redirect($this->url->link('error/not_found', '', true));
		}
  	}
	public function login_validate($data = array()) {
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->load->language('checkout/checkout');

			$json = array();
			$this->load->model('account/customer');

			if ($this->customer->isLogged()) {
				$json['islogged'] = true;
			}else if(isset($this->request->post)) {
				if (!$this->customer->login($this->request->post['emailpopup'], $this->request->post['passwordpopup'])) {
					$json['error'] = $this->language->get('error_login');
				}
				$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['emailpopup']);
				if ($customer_info && !$customer_info['status']) {
					$json['error'] = $this->language->get('error_approved');
				}
			} else {
				$json['error'] = $this->language->get('error_warning');
			}

			if(!$json) {
				$json['success'] = true;
				unset($this->session->data['guest']);
				$this->load->model('account/address');

				if ($this->config->get('config_tax_customer') == 'payment') {
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}

				if ($this->config->get('config_tax_customer') == 'shipping') {
					$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}

				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
				);

				$this->model_account_activity->addActivity('login', $activity_data);
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		} else {
		  $this->response->redirect($this->url->link('error/not_found', '', true));
		}
	}
}
?>
