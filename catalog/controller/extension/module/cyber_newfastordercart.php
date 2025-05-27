<?php
class ControllerExtensionModuleCyberNewfastordercart extends Controller {
	private $error = array();

	public function index() {
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		if ($this->config->get('config_quickorder_id')) {
			$this->load->model('catalog/information');
			$this->load->language('cyberstore/lang');
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_quickorder_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_quickorder_id'), true), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}
			$this->language->load('extension/module/cyber_newfastorder');
			$data['button_remove'] = $this->language->get('button_remove');
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_continue'] = $this->language->get('text_continue');
			$data['title_shopping_cart'] = $this->language->get('title_shopping_cart');

			$this->load->model('tool/image');
			if (!empty($this->request->post['quantity'])) {
				foreach ($this->request->post['quantity'] as $key => $value) {
					$this->cart->update($key, $value);
				}
			}
			if ($this->config->get('config_cart_weight')) {
				$data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			} else {
				$data['weight'] = '';
			}
			$this->load->model('tool/image');
			$this->load->model('tool/upload');

			$data['products'] = array();

			$products = $this->cart->getProducts();

			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}

				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));
				} else {
					$image = '';
				}

				$option_data = array();

				foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));

					$price = $this->currency->format($unit_price, $this->session->data['currency']);
					$total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
				} else {
					$price = false;
					$total = false;
				}

				$recurring = '';

				if ($product['recurring']) {
					$frequencies = array(
						'day'        => $this->language->get('text_day'),
						'week'       => $this->language->get('text_week'),
						'semi_month' => $this->language->get('text_semi_month'),
						'month'      => $this->language->get('text_month'),
						'year'       => $this->language->get('text_year')
					);

					if ($product['recurring']['trial']) {
						$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
					}

					if ($product['recurring']['duration']) {
						$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					} else {
						$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
					}
				}

				$data['products'][] = array(
					'cart_id'   => $product['cart_id'],
					'thumb'     => $image,
					'name'      => $product['name'],
					'model'     => $product['model'],
					'option'    => $option_data,
					'recurring' => $recurring,
					'quantity'  => $product['quantity'],
					'stock'     => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'reward'    => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
					'price'     => $price,
					'total'     => $total,
					'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
				);
			}

			// Gift Voucher
			$data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$data['vouchers'][] = array(
						'key'         => $key,
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency']),
						'remove'      => $this->url->link('checkout/cart', 'remove=' . $key)
					);
				}
			}

			// Totals
			$this->load->model('setting/extension');

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$sort_order = array();

				$results = $this->model_setting_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				foreach ($results as $result) {
					if ($this->config->get('total_' . $result['code'] . '_status')) {
						$this->load->model('extension/total/' . $result['code']);

						// We have to put the totals in an array so that they pass by reference.
						$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
					}
				}

				$sort_order = array();

				foreach ($totals as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $totals);
			}
			$data['text_total_qucik_ckeckout'] = sprintf($this->language->get('text_total_qucik_ckeckout'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total, $this->session->data['currency']));

			$data['totals'] = array();
			$data['total_order'] = $total;
			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
				);
			}


			$data['lang_id'] 									= $this->config->get('config_language_id');
			$data['text_min_price'] 							= $this->language->get('text_min_price');
			$data['button_send'] 								= $this->language->get('button_send');
			$data['icon_send_fastorder'] 						= $this->config->get('config_icon_send_fastorder');
			$data['background_button_send_fastorder'] 			= $this->config->get('config_background_button_send_fastorder');
			$data['background_button_open_form_send_order_hover'] = $this->config->get('config_background_button_open_form_send_order_hover');
			$data['background_button_send_fastorder_hover'] 	= $this->config->get('config_background_button_send_fastorder_hover');
			$data['background_button_open_form_send_order'] 	= $this->config->get('config_background_button_open_form_send_order');
			$data['icon_open_form_send_order'] 					= $this->config->get('config_icon_open_form_send_order');
			$data['icon_open_form_send_order_size'] 			= $this->config->get('config_icon_open_form_send_order_size');
			$data['color_button_open_form_send_order'] 			= $this->config->get('config_color_button_open_form_send_order');
			$data['config_any_text_at_the_top'] 				= $this->config->get('config_any_text_at_the_top');
			$data['config_text_open_form_send_order'] 			= $this->config->get('config_text_open_form_send_order');
			$data['any_text_at_the_bottom_color'] 				= $this->config->get('config_any_text_at_the_bottom_color');
			$data['img_fastorder'] 								= $this->config->get('config_img_fastorder');
			$data['mask_phone_number'] 							= $this->config->get('config_mask_phone_number');
			$data['placeholder_phone_number'] 					= $this->config->get('config_placeholder_phone_number');
			$data['config_text_before_button_send'] 			= $this->config->get('config_text_before_button_send');
			$data['config_any_text_at_the_bottom'] 				= $this->config->get('config_any_text_at_the_bottom');
			$data['continue_shopping'] 							= $this->language->get('continue_shopping');
			$data['config_title_popup_quickorder'] 				= $this->config->get('config_title_popup_quickorder');
			$data['config_fields_firstname_requared'] 			= $this->config->get('config_fields_firstname_requared');
			$data['config_fields_phone_requared'] 				= $this->config->get('config_fields_phone_requared');
			$data['config_fields_email_requared'] 				= $this->config->get('config_fields_email_requared');
			$data['config_fields_comment_requared'] 			= $this->config->get('config_fields_comment_requared');
			$data['config_placeholder_fields_firstname'] 		= $this->config->get('config_placeholder_fields_firstname');
			$data['config_placeholder_fields_phone'] 			= $this->config->get('config_placeholder_fields_phone');
			$data['config_placeholder_fields_email'] 			= $this->config->get('config_placeholder_fields_email');
			$data['config_placeholder_fields_comment'] 			= $this->config->get('config_placeholder_fields_comment');

			$data['on_off_fields_firstname'] 					= $this->config->get('config_on_off_fields_firstname');
			$data['on_off_fields_phone'] 						= $this->config->get('config_on_off_fields_phone');
			$data['on_off_fields_comment'] 						= $this->config->get('config_on_off_fields_comment');
			$data['on_off_fields_email'] 						= $this->config->get('config_on_off_fields_email');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['action'])) {

			if ($this->validate()) {
				$data = array();
				if (isset($this->request->post['name_fastorder'])) {
  		    		$data['name_fastorder'] = $this->request->post['name_fastorder'];
				} else {
      				$data['name_fastorder'] = '';
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
				if (isset($this->request->post['url_site'])) {
      				$data['url_site'] = $this->request->post['url_site'];
				} else {
      				$data['url_site'] = '';
    			}
				if (isset($this->request->post['price_shipping_value'])) {
                    $data['price_shipping_value'] = $this->request->post['price_shipping_value'];
                } else {
                    $data['price_shipping_value'] = '';
                }
				if (isset($this->request->post['price_shipping_text'])) {
                    $data['price_shipping_text'] = $this->request->post['price_shipping_text'];
                } else {
                    $data['price_shipping_text'] = '';
                }
				if (isset($this->request->post['shipping_title'])) {
                    $data['shipping_title'] = $this->request->post['shipping_title'];
                } else {
                    $data['shipping_title'] = '';
                }
				if (isset($this->request->post['shipping_code_quickorder'])) {
                    $data['shipping_code_quickorder'] = $this->request->post['shipping_code_quickorder'];
                } else {
                    $data['shipping_code_quickorder'] = '';
                }
				if (isset($this->request->post['tax_class_id_total'])) {
                    $data['tax_class_id_total'] = $this->request->post['tax_class_id_total'];
                } else {
                    $data['tax_class_id_total'] = '';
                }
				if (isset($this->request->post['payment_title'])) {
                    $data['payment_title'] = $this->request->post['payment_title'];
                } else {
                    $data['payment_title'] = '';
                }
				if (isset($this->request->post['payment_code_quickorder'])) {
                    $data['payment_code_quickorder'] = $this->request->post['payment_code_quickorder'];
                } else {
                    $data['payment_code_quickorder'] = '';
                }

			$order_data = array();

			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			$this->load->model('setting/extension');

			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);

			$order_data['totals'] = $totals;


			$this->load->model('tool/image');

			foreach ($this->cart->getProducts() as $product) {
				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));
				} else {
					$image = '';
				}
				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}

				$products_data[] = array(
					'product_id' => $product['product_id'],
					'thumb'     => $image,
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'price_fast' => $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')),
					'total_fast' => $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']
				);
			}
				$data['products'] = $products_data;
				$data['total'] = $order_data;
				/*************************/
				$data['language_id'] = $this->config->get('config_language_id');
				$data['currency_id'] = $this->currency->getId($this->session->data['currency']);
				$data['currency_code'] = $this->session->data['currency'];
				$data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
				$data['ip'] = $this->request->server['REMOTE_ADDR'];
				$data['ip_store'] = $this->request->server['REMOTE_ADDR'];
				$data['store_id'] = $this->config->get('config_store_id');
				$data['store_name'] = $this->config->get('config_name');
				$data['customer_id'] = 0;
				$data['customer_group_id'] = 1;
				$data['config_tax'] = $this->config->get('config_tax');
				$data['store_url'] = HTTP_SERVER;

				if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
					$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
				} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
					$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
				} else {
					$data['forwarded_ip'] = '';
				}

				if (isset($this->request->server['HTTP_USER_AGENT'])) {
					$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
				} else {
					$data['user_agent'] = '';
				}

				if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
					$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
				} else {
					$data['accept_language'] = '';
				}
				if (isset($this->request->post['total_order'])) {
                    $data['total_order'] = $this->request->post['total_order'];
                } else {
                    $data['total_order'] = '';
                }


				$this->load->model('extension/module/cyber_newfastordercart');
				$results = $this->model_extension_module_cyber_newfastordercart->addOrder($data);
				$config_on_off_send_buyer_mail = $this->config->get('config_on_off_send_buyer_mail');
				if($config_on_off_send_buyer_mail =='1'){
					if($data['email_buyer'] !='') {
					$this->sendMailBuyer($data);
					}
				}
				$config_on_off_send_me_mail = $this->config->get('config_on_off_send_me_mail');
				if($config_on_off_send_me_mail =='1'){
					$this->sendMailMe($data);
				}
				if($this->config->get('config_send_sms_on_off_fastorder') == '1'){
					$this->sendSms($data);
				}
				$lang_id = $this->config->get('config_language_id');
				$config_complete_quickorder = $this->config->get('config_complete_quickorder');
				$ok = $config_complete_quickorder[$lang_id]['config_complete_quickorder'];
				if($ok !=''){
					$json['success'] = $ok;
				} else {
					$json['success'] = $this->language->get('ok');
				}
				$this->cache->delete('product.bestseller');
				$this->cart->clear();

			}else{
				$json['error'] = $this->error;
			}
			return $this->response->setOutput(json_encode($json));
		}


			$this->response->setOutput($this->load->view('extension/module/newfastordercart', $data));
		} else {
		  $this->response->redirect($this->url->link('error/not_found', '', true));
		}
  	}

  	private function validate() {
   		$this->language->load('extension/module/cyber_newfastorder');
			$config_fields_firstname_requared = $this->config->get('config_fields_firstname_requared');
			$config_on_off_fields_firstname = $this->config->get('config_on_off_fields_firstname');
			if(($config_fields_firstname_requared =='1') && $config_on_off_fields_firstname =='1'){
				if ((strlen(utf8_decode($this->request->post['name_fastorder'])) < 1) || (strlen(utf8_decode($this->request->post['name_fastorder'])) > 32)) {
					$this->error['name_fastorder'] = $this->language->get('mister');
				}
			}
			$config_fields_phone_requared = $this->config->get('config_fields_phone_requared');
			$config_on_off_fields_phone = $this->config->get('config_on_off_fields_phone');
			if(($config_fields_phone_requared =='1') && $config_on_off_fields_phone =='1'){
				if ((strlen(utf8_decode($this->request->post['phone'])) < 3) || (strlen(utf8_decode($this->request->post['phone'])) > 32)) {
					$this->error['phone'] = $this->language->get('error_phone');
				}
			}
			$config_fields_comment_requared = $this->config->get('config_fields_comment_requared');
			$config_on_off_fields_comment = $this->config->get('config_on_off_fields_comment');
			if(($config_fields_comment_requared =='1') && $config_on_off_fields_comment == '1'){
				if ((strlen(utf8_decode($this->request->post['comment_buyer'])) < 1) || (strlen(utf8_decode($this->request->post['comment_buyer'])) > 400)) {
					$this->error['comment_buyer'] = $this->language->get('comment_buyer_error');
				}
			}
			$config_fields_email_requared = $this->config->get('config_fields_email_requared');
			$config_on_off_fields_email = $this->config->get('config_on_off_fields_email');
			if(($config_fields_email_requared =='1') && $config_on_off_fields_email == '1'){
				if(!preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $this->request->post['email_buyer'])){
						$this->error['email_error'] =  $this->language->get('email_buyer_error');
				}
			}

			$this->load->model('catalog/product');
				// Agree to terms
			if ($this->config->get('config_quickorder_id')) {
				$this->load->model('catalog/information');
				$this->load->language('cyberstore/lang');
				$information_info = $this->model_catalog_information->getInformation($this->config->get('config_quickorder_id'));

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
  	private function sendMailBuyer($data) {

		$this->language->load('extension/module/cyber_newfastorder');
		$data['text_photo'] = $this->language->get('text_photo');
		$data['text_product'] = $this->language->get('text_new_product');
		$data['text_model'] = $this->language->get('text_new_model');
		$data['text_quantity'] = $this->language->get('text_new_quantity');
		$data['text_price'] = $this->language->get('text_new_price');
		$data['text_total'] = $this->language->get('text_new_total');
		foreach ($data['total']['totals'] as $result) {
			$data['totals'][] = array(
				'title' => $result['title'],
				'text'  => $this->currency->format($result['value'],$this->session->data['currency']),
			);
		}

		$text = '';
		$quickorder_subject = $this->config->get('quickorder_subject');
		$quickorder_description= $this->config->get('quickorder_description');
		$subject_buyer = $this->getCustomFields($data, $quickorder_subject [$data['language_id']]['text']);
		if ((strlen(utf8_decode($subject_buyer)) > 5)){
			$subject = $subject_buyer;
		} else {
			$subject = $this->language->get('subject');
		}
		$html = $this->getCustomFields($data, $quickorder_description[$data['language_id']]['text']). "\n";
		$config_buyer_html_products = $this->config->get('config_buyer_html_products');
		if($config_buyer_html_products =='1'){
				$html .= $this->load->view('mail/quickorder', $data);
		}
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($data['email_buyer']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($data['store_name'], ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml(html_entity_decode($html, ENT_QUOTES, 'UTF-8'));
		$mail->setText($text);
		$mail->send();
	}
	private function sendMailMe($data) {
		$this->language->load('extension/module/cyber_newfastorder');
		$data['text_photo'] = $this->language->get('text_photo');
		$data['text_product'] = $this->language->get('text_new_product');
		$data['text_model'] = $this->language->get('text_new_model');
		$data['text_quantity'] = $this->language->get('text_new_quantity');
		$data['text_price'] = $this->language->get('text_new_price');
		$data['text_total'] = $this->language->get('text_new_total');
		foreach ($data['total']['totals'] as $result) {
			$data['totals'][] = array(
				'title' => $result['title'],
				'text'  => $this->currency->format($result['value'],$this->session->data['currency']),
			);
		}

		$text = '';
		$quickorder_subject_me =$this->config->get('quickorder_subject_me');
		$quickorder_description_me=$this->config->get('quickorder_description_me');
		$subject_me = $this->getCustomFields($data, $quickorder_subject_me[$data['language_id']]['text']);
		if ((strlen(utf8_decode($subject_me)) > 5)){
			$subject = $subject_me;
		} else {
			$subject = $this->language->get('subject');
		}
		$html = $this->getCustomFields($data, $quickorder_description_me[$data['language_id']]['text']). "\n";
		$on_off_html_product_me = $this->config->get('config_me_html_products');
		if($on_off_html_product_me =='1'){
		$html .= $this->load->view('mail/quickorder', $data);

		}


		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($this->config->get('config_you_email_quickorder'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($data['store_name'], ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml(html_entity_decode($html, ENT_QUOTES, 'UTF-8'));
		$mail->setText($text);
		$mail->send();
	}
	private function sendSms($data) {
		if($this->config->get('config_send_sms_on_off_fastorder') == '1'){
			$login = $this->config->get('config_login_send_sms_fastorder');
			$password = $this->config->get('config_pass_send_sms_fastorder');
			$number = $this->config->get('config_phone_number_send_sms_fastorder');
			$text_sms 	= $this->language->get('text_1');
			foreach($data['products'] as $result){
				$data['products_sms'][] = $result['name'];
			}
			$implode_product = implode(" / ", $data['products_sms']);
			$message = $text_sms.$implode_product."\n" .$data['name_fastorder']."\n" .$data['phone'];
			$this->send($login, $password, $number, $message,'');
		}
	}

	public function send($login, $password, $number, $message, $sender, $query = '')
	{
			$res = $this->_read_url('http://my.smscab.ru/sys/send.php?login='.urlencode($login).'&psw='.md5(html_entity_decode($password)).
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
public function editCartQuick() {
	if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$this->load->language('checkout/cart');
		$this->load->model('tool/image');

		$json = array();

		// Update
		if (isset($this->request->post['quantity']) && isset($this->request->post['key'])) {

			$this->cart->update($this->request->post['key'], $this->request->post['quantity']);

			// Unset all shipping and payment methods
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);

				// Totals
				$this->load->model('setting/extension');

				$totals = array();
				$taxes = $this->cart->getTaxes();
				$total = 0;

				// Because __call can not keep var references so we put them into an array.
				$total_data = array(
					'totals' => &$totals,
					'taxes'  => &$taxes,
					'total'  => &$total
				);

				// Display prices
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$sort_order = array();

					$results = $this->model_setting_extension->getExtensions('total');

					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}

					array_multisort($sort_order, SORT_ASC, $results);

					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('extension/total/' . $result['code']);

							// We have to put the totals in an array so that they pass by reference.
							$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
						}
					}

					$sort_order = array();

					foreach ($totals as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}

					array_multisort($sort_order, SORT_ASC, $totals);
				}
		}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		} else {
		  $this->response->redirect($this->url->link('error/not_found', '', true));
		}
	}
}
?>
