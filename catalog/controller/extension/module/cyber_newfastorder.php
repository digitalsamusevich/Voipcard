<?php
class ControllerExtensionModuleCyberNewfastorder extends Controller {
	private $error = array();
	public function index() {
	if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$data['config_additional_settings_newstore'] = $this->config->get('config_additional_settings_newstore');
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
			if (isset($this->request->get['product_id'])) {
				$product_id = (int)$this->request->get['product_id'];
				$data['product_id'] = (int)$this->request->get['product_id'];
			} else {
				$product_id = 0;
				$data['product_id'] = 0;
			}
			$this->language->load('product/product');
			$data['lang_id'] = $this->config->get('config_language_id');
			$data['buying'] = $this->language->get('buying');
			$data['byprice'] = $this->language->get('byprice');
			$data['quantity_buy'] = $this->language->get('quantity_buy');

			$data['text_option'] = $this->language->get('text_option');
			$data['text_select'] = $this->language->get('text_select');
			$data['button_upload'] = $this->language->get('button_upload');
			$data['comment_buyer'] = $this->language->get('comment_buyer');
			$data['text_min_price'] = $this->language->get('text_min_price');
			$data['text_column_name'] = $this->language->get('text_column_name');
			$data['text_column_price'] = $this->language->get('text_column_price');
			$data['text_column_quantity'] = $this->language->get('text_column_quantity');
			$data['min_price_fastorder'] = $this->config->get('config_min_price_fastorder');
			$data['email_buyer'] = $this->language->get('email_buyer');
			$data['namew'] = $this->language->get('namew');
			$data['phonew'] = $this->language->get('phonew');
			$data['button_send'] = $this->language->get('button_send');
			$data['continue_shopping'] = $this->language->get('continue_shopping');
			$data['title_fastorder'] = $this->language->get('title_fastorder');
			$data['text_quick_order_enter_name_phone'] = $this->language->get('text_quick_order_enter_name_phone');
			$data['text_you_order'] = $this->language->get('text_you_order');
			$data['icon_send_fastorder'] = $this->config->get('config_icon_send_fastorder');
			$data['background_button_send_fastorder'] = $this->config->get('config_background_button_send_fastorder');
			$data['background_button_open_form_send_order_hover'] = $this->config->get('config_background_button_open_form_send_order_hover');
			$data['background_button_send_fastorder_hover'] = $this->config->get('config_background_button_send_fastorder_hover');
			$data['background_button_open_form_send_order'] = $this->config->get('config_background_button_open_form_send_order');
			$data['icon_open_form_send_order'] = $this->config->get('config_icon_open_form_send_order');
			$data['icon_open_form_send_order_size'] = $this->config->get('config_icon_open_form_send_order_size');
			$data['color_button_open_form_send_order'] = $this->config->get('config_color_button_open_form_send_order');
			$data['config_any_text_at_the_top'] = $this->config->get('config_any_text_at_the_top');
			$data['config_text_open_form_send_order'] = $this->config->get('config_text_open_form_send_order');
			$data['any_text_at_the_bottom_color'] = $this->config->get('config_any_text_at_the_bottom_color');
			$data['img_fastorder'] = $this->config->get('config_img_fastorder');
			$data['mask_phone_number'] = $this->config->get('config_mask_phone_number');
			$data['placeholder_phone_number'] = $this->config->get('config_placeholder_phone_number');
			$data['config_text_before_button_send'] = $this->config->get('config_text_before_button_send');
			$data['config_any_text_at_the_bottom'] = $this->config->get('config_any_text_at_the_bottom');
			$data['config_title_popup_quickorder'] = $this->config->get('config_title_popup_quickorder');

			$data['config_fields_firstname_requared'] = $this->config->get('config_fields_firstname_requared');
			$data['config_fields_phone_requared'] = $this->config->get('config_fields_phone_requared');
			$data['config_fields_email_requared'] = $this->config->get('config_fields_email_requared');
			$data['config_fields_comment_requared'] = $this->config->get('config_fields_comment_requared');
			$data['config_placeholder_fields_firstname'] = $this->config->get('config_placeholder_fields_firstname');
			$data['config_placeholder_fields_phone'] = $this->config->get('config_placeholder_fields_phone');
			$data['config_placeholder_fields_email'] = $this->config->get('config_placeholder_fields_email');
			$data['config_placeholder_fields_comment'] = $this->config->get('config_placeholder_fields_comment');

			$data['on_off_fields_firstname'] = $this->config->get('config_on_off_fields_firstname');
			$data['on_off_fields_phone'] = $this->config->get('config_on_off_fields_phone');
			$data['on_off_fields_comment'] = $this->config->get('config_on_off_fields_comment');
			$data['on_off_fields_email'] = $this->config->get('config_on_off_fields_email');
			$data['config_general_image_product_popup'] = $this->config->get('config_general_image_product_popup');
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			$product_info = $this->model_catalog_product->getProduct($product_id);
			if ($product_info['image']) {
				$data['popup'] = $this->model_tool_image->resize($product_info['image'], 228, 228);
			} else {
				$data['popup'] = $this->model_tool_image->resize('no_image.jpg', 228, 228);
			}

			$data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($product_id);

			foreach ($results as $result) {
				$data['images'][] = array(
					'popup' => $this->model_tool_image->resize($result['image'], 228, 228),
					'thumb' => $this->model_tool_image->resize($result['image'], 74, 74)
				);
			}

			$data['points'] = $product_info['points'];
			$data['heading_title'] = $product_info['name'];

			if ($product_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($product_info['image'], 228, 228);
				$data['small'] = $this->model_tool_image->resize($product_info['image'], 74, 74);
				$data['thumb_small'] = $this->model_tool_image->resize($product_info['image'], 60, 60);
			} else {
				$data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 228, 228);
				$data['thumb_small'] = $this->model_tool_image->resize('no_image.jpg', 60, 60);
			}
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$data['price'] = false;
			}

			if ((float)$product_info['special']) {
				$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$data['special'] = false;
			}

			if ($this->config->get('config_tax')) {
				$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
			} else {
				$data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($product_id);

			$data['discounts'] = array();

			foreach ($discounts as $discount) {
				$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
				);
			}
		  $data['price_value'] = $product_info['price'];
          $data['special_value'] = $product_info['special'];
          $data['tax_value'] = (float)$product_info['special'] ? $product_info['special'] : $product_info['price'];

        $var_currency = array();
		$var_currency['value'] = $this->currency->getValue($this->session->data['currency']);
		$var_currency['symbol_left'] = $this->currency->getSymbolLeft($this->session->data['currency']);
		$var_currency['symbol_right'] = $this->currency->getSymbolRight($this->session->data['currency']);
		$var_currency['currency_code'] = $this->session->data['currency'];
		$var_currency['decimals'] = $this->currency->getDecimalPlace($this->session->data['currency']);
		$var_currency['decimal_point'] = $this->language->get('decimal_point');
		$var_currency['thousand_point'] = $this->language->get('thousand_point');
		$data['currency'] = $var_currency;

          $data['dicounts_unf'] = $discounts;

          $data['tax_class_id'] = $product_info['tax_class_id'];
		  $data['model'] = $product_info['model'];

		  $data['tax_rates'] = $this->tax->getRates(0, $product_info['tax_class_id']);
			$data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($product_id) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
						} else {
							$price = false;
						}
						$price = strip_tags($price);
						$product_option_value_data[] = array(
							'price_value'                   => $option_value['price'],
							'points_value'                  => intval($option_value['points_prefix'].$option_value['points']),
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'color'                   => $option_value['color'],
							'image'                   => $option_value['image'] ? $this->model_tool_image->resize($option_value['image'], 50, 50) : '',
							'price'                   => $price,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'status_color_type'    => $option['status_color_type'],
					'required'             => $option['required']
				);
			}
			$data['lang_datetimepicker'] = $this->session->data['language'];
			$data['load_datetimepicker'] = false;
			foreach ($data['options'] as $option) {
				if ($option['type'] == 'time' || $option['type'] == 'date' || $option['type'] == 'datetime') {
					$data['load_datetimepicker'] = true;
					break;
				}
			}

			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} else {
				$data['minimum'] = 1;
			}

			$data['ip_store'] = $this->request->server['REMOTE_ADDR'];



			$data['getid'] = $this->currency->getId($this->session->data['currency']);
			$data['getcode'] = $this->session->data['currency'];
			$data['getvalue'] = $this->currency->getValue($this->session->data['currency']);

			$this->language->load('module/cyber_newfastorder');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['action'])) {

			if ($this->validate()) {
				$data = array();
				if (isset($this->request->post['this_prod_id'])) {
                    $data['this_prod_id'] = $this->request->post['this_prod_id'];
                } else {
                    $data['this_prod_id'] = '';
                }
				$product_info = $this->model_catalog_product->getProduct($this->request->post['this_prod_id']);
				$data['model'] = $product_info['model'];
				$data['language_id'] = $this->config->get('config_language_id');
				$data['store_id'] = $this->config->get('config_store_id');
				$data['store_name'] = $this->config->get('config_name');
				$data['customer_id'] = 0;
				$data['customer_group_id'] = 1;
				$data['config_tax'] = $this->config->get('config_tax');
				$data['store_url'] = HTTP_SERVER;
				$data['ip'] = $this->request->server['REMOTE_ADDR'];

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
				$data['tax_class_id_total'] = $product_info['tax_class_id'];
				$data['currency_code'] = $this->session->data['currency'];
				$data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
                $data['currency_id'] = $this->currency->getId($this->session->data['currency']);
				$data['ip_store'] = $this->request->server['REMOTE_ADDR'];
				if ($product_info['image']) {
					$data['prod_image'] = $this->model_tool_image->resize($product_info['image'], 228, 228);
					$data['small_image'] = $this->model_tool_image->resize($product_info['image'], 74, 74);
				} else {
					$data['prod_image'] = $this->model_tool_image->resize('no_image.jpg', 228, 228);
					$data['small_image'] = $this->model_tool_image->resize('no_image.jpg', 74, 74);
				}
                $data['prod_name'] = $product_info['name'];
                $data['price_shipping_value'] 		= '';
				$data['price_shipping_text'] 		= '';
                $data['shipping_title'] 			= '';
                $data['shipping_code_quickorder'] 	= '';
                $data['payment_title'] 				= '';
                $data['payment_code_quickorder'] 	= '';
				$data['prod_name'] = $product_info['name'];
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
				if (isset($this->request->post['price_tax'])) {
                    $data['price_tax'] = $this->request->post['price_tax'];
                } else {
                    $data['price_tax'] = '';
                }
				if (isset($this->request->post['price_no_tax'])) {
                    $data['price_no_tax'] = $this->request->post['price_no_tax'];
                } else {
                    $data['price_no_tax'] = '';
                }
				if (isset($this->request->post['quantity'])) {
                    $data['quantity'] = $this->request->post['quantity'];
                } else {
                    $data['quantity'] = '';
                }
				if (isset($this->request->post['total_fast'])) {
                    $data['total_fast'] = $this->request->post['total_fast'];
                } else {
                    $data['total_fast'] = '';
                }

				if (isset($this->request->post['option-fast'])) {
                    $data['option-fast'] = $this->request->post['option-fast'];
                } else {
                    $data['option-fast'] = '';
                }
				$this->load->model('extension/module/cyber_newfastorder');
				if (!empty($this->request->post['option-fast'])) {
					$product_options = $this->getProductsOptionsFastorder($this->request->post['this_prod_id'], $this->request->post['option-fast']);
				} else {
					$product_options = array();
				}

				$results = $this->model_extension_module_cyber_newfastorder->addOrder($data, $product_options);
				$lang_id = $this->config->get('config_language_id');
				$config_complete_quickorder = $this->config->get('config_complete_quickorder');
				$ok = $config_complete_quickorder[$lang_id]['config_complete_quickorder'];
				if($ok !=''){
					$json['success'] = $ok;
				} else {
					$json['success'] = $this->language->get('ok');
				}
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
				$this->cache->delete('product.bestseller');
			} else {
				$json['error'] = $this->error;

			}

			return $this->response->setOutput(json_encode($json));
		}


     		$data['sendthis'] = $this->language->get('sendthis');
     		$data['comment_buyer'] = $this->language->get('comment_buyer');
     		$data['email_buyer'] = $this->language->get('email_buyer');
     		$data['namew'] = $this->language->get('namew');
     		$data['phonew'] = $this->language->get('phonew');
     		$data['button_send'] = $this->language->get('button_send');
     		$data['cancel'] = $this->language->get('cancel');
			$data['lang_id'] = $this->config->get('config_language_id');


		$design_fastorder = $this->config->get('config_select_design_fastorder');
		if($design_fastorder == '2'){
			$this->response->setOutput($this->load->view('extension/module/newfastorder2', $data));
		} elseif($design_fastorder == '3') {
			$this->response->setOutput($this->load->view('extension/module/newfastorder3', $data));
		} elseif($design_fastorder == '4'){
			$this->response->setOutput($this->load->view('extension/module/newfastorder4', $data));
		} else {
			$this->response->setOutput($this->load->view('extension/module/newfastorder', $data));
		}
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

			if (isset($this->request->post['option-fast'])) {
				$option = array_filter($this->request->post['option-fast']);
			} else {
				$option = array();
			}
			$this->load->model('catalog/product');
			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['this_prod_id']);
			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$this->error['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}
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
		if($data['config_tax'] =='1'){
			$data['tax_rates_f_p'] = $this->tax->getRates($data['price_no_tax'], $data['tax_class_id_total']);
		} else {
			$data['tax_rates_f_p'] = '';
		}
		$data['total'] = $data['price_tax']*$data['quantity'];
		$data['total_all'] = $this->currency->format($data['total_fast'],$this->session->data['currency']);
		$this->language->load('module/cyber_newfastorder');
		$data['text_photo'] = $this->language->get('text_photo');
		$data['text_product'] = $this->language->get('text_new_product');
		$data['text_model'] = $this->language->get('text_new_model');
		$data['text_quantity'] = $this->language->get('text_new_quantity');
		$data['text_price'] = $this->language->get('text_new_price');
		$data['text_total'] = $this->language->get('text_new_total');

		if (!empty($data['option-fast'])) {
			$data['option_send_mail'] = $this->getProductsOptionsFastorder($this->request->post['this_prod_id'], $data['option-fast']);
		} else {
			$data['option_send_mail'] = array();
		}
		$text = '';
		$quickorder_subject = $this->config->get('quickorder_subject');
		$quickorder_description= $this->config->get('quickorder_description');
		$subject_buyer = $this->getCustomFields($data, $quickorder_subject[$data['language_id']]['text']);
		if ((strlen(utf8_decode($subject_buyer)) > 5)){
			$subject = $subject_buyer;
		} else {
			$subject = $this->language->get('subject');
		}
		$html = $this->getCustomFields($data, $quickorder_description[$data['language_id']]['text']). "\n";
		$config_buyer_html_products = $this->config->get('config_buyer_html_products');
		if($config_buyer_html_products =='1'){
		$html .= $this->load->view('mail/quickorderone', $data);
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
		if($data['config_tax'] =='1'){
			$data['tax_rates_f_p'] = $this->tax->getRates($data['price_no_tax'], $data['tax_class_id_total']);
		}else {
			$data['tax_rates_f_p'] = '';
		}
		$data['total'] = $data['price_tax']*$data['quantity'];
		$data['total_all'] = $this->currency->format($data['total_fast'],$this->session->data['currency']);
		$this->language->load('module/cyber_newfastorder');
		$data['text_photo'] = $this->language->get('text_photo');
		$data['text_product'] = $this->language->get('text_new_product');
		$data['text_model'] = $this->language->get('text_new_model');
		$data['text_quantity'] = $this->language->get('text_new_quantity');
		$data['text_price'] = $this->language->get('text_new_price');
		$data['text_total'] = $this->language->get('text_new_total');

		if (!empty($data['option-fast'])) {
			$data['option_send_mail'] = $this->getProductsOptionsFastorder($this->request->post['this_prod_id'], $data['option-fast']);
		} else {
			$data['option_send_mail'] = array();
		}
		$text = '';
		$quickorder_subject_me = $this->config->get('quickorder_subject_me');
		$quickorder_description_me = $this->config->get('quickorder_description_me');
		$subject_me = $this->getCustomFields($data, $quickorder_subject_me[$data['language_id']]['text']);
		if ((strlen(utf8_decode($subject_me)) > 5)){
			$subject = $subject_me;
		} else {
			$subject = $this->language->get('subject');
		}
		$html = $this->getCustomFields($data, $quickorder_description_me[$data['language_id']]['text']). "\n";
		$config_me_html_products = $this->config->get('config_me_html_products');
		if($config_me_html_products =='1'){
		$html .= $this->load->view('mail/quickorderone', $data);
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
			$message = $text_sms.$data['prod_name']."\n" .$data['name_fastorder']."\n" .$data['phone'];
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
		private function getProductsOptionsFastorder($this_prod_id, $option_fast) {
		$product_id = $this_prod_id;
				if (isset($option_fast)) {
					$options = $option_fast;
				} else {
					$options = array();
				}


				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p
				LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
				WHERE p.product_id = '" . (int)$product_id . "'
				AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
				AND p.date_available <= NOW() AND p.status = '1'");

				$option_data = array();
				foreach ($options as $product_option_id => $value) {
					$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po
					LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id)
					LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id)
					WHERE po.product_option_id = '" . (int)$product_option_id . "'
					AND po.product_id = '" . (int)$product_id . "'
					AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

						if ($option_query->num_rows) {
							if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio' || $option_query->row['type'] == 'image') {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $value,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'value'                   => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
									);
								}
							} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
								foreach ($value as $product_option_value_id) {
									$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

									if ($option_value_query->num_rows) {
										$option_data[] = array(
											'product_option_id'       => $product_option_id,
											'product_option_value_id' => $product_option_value_id,
											'option_id'               => $option_query->row['option_id'],
											'option_value_id'         => $option_value_query->row['option_value_id'],
											'name'                    => $option_query->row['name'],
											'value'                   => $option_value_query->row['name'],
											'type'                    => $option_query->row['type'],
										);
									}
								}
							} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => '',
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => '',
									'name'                    => $option_query->row['name'],
									'value'                   => $value,
									'type'                    => $option_query->row['type'],
								);
							}
						}

			}
	return $option_data;
}
}
?>
