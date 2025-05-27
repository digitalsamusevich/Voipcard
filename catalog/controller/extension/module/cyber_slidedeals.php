<?php
class ControllerExtensionModuleCyberSlidedeals extends Controller {
	public function index($setting) {
		$data['setting_module'] = $this->config->get('setting_module');
		$this->document->addScript('catalog/view/theme/cyberstore/js/slick/slick.min.js');
		$this->document->addStyle('catalog/view/theme/cyberstore/js/slick/slick.css');
		$data['lang_id'] = $this->config->get('config_language_id');
		$data['title'] = html_entity_decode($setting['title'][$this->config->get('config_language_id')]['text'], ENT_QUOTES, 'UTF-8');
		$data['description'] = $setting['description'];
		if(isset($setting['on_off_model_product'])){
			$data['on_off_model_product'] = $setting['on_off_model_product'];
		} else {
			$data['on_off_model_product'] = 0;
		}
		if(isset($setting['verdeals'])){
			$data['verdeals'] = $setting['verdeals'];
		} else {
			$data['verdeals'] = 1;
		}
		if($data['verdeals'] == 1){
			$this->document->addStyle('catalog/view/theme/cyberstore/stylesheet/slidedeals/style1.css');
		} else {
			$this->document->addStyle('catalog/view/theme/cyberstore/stylesheet/slidedeals/style2.css');
		}
		if(isset($setting['on_off_description'])){
			$data['on_off_description'] = $setting['on_off_description'];
		} else {
			$data['on_off_description'] = 0;
		}
		$data['on_off_slider_additional_image'] = $setting['on_off_slider_additional_image'];
		if(isset($setting['on_off_rating'])){
			$data['on_off_rating'] = $setting['on_off_rating'];
		} else {
			$data['on_off_rating'] = 0;
		}
		if(isset($setting['on_off_quantity_reviews'])){
			$data['on_off_quantity_reviews'] = $setting['on_off_quantity_reviews'];
		} else {
			$data['on_off_quantity_reviews'] = 0;
		}
		if(isset($setting['on_off_fastorder'])){
			$data['on_off_fastorder'] = $setting['on_off_fastorder'];
		} else {
			$data['on_off_fastorder'] = 0;
		}
		if(isset($setting['on_off_wishlist'])){
			$data['on_off_wishlist'] = $setting['on_off_wishlist'];
		} else {
			$data['on_off_wishlist'] = 0;
		}
		if(isset($setting['on_off_compare'])){
			$data['on_off_compare'] = $setting['on_off_compare'];
		} else {
			$data['on_off_compare'] = 0;
		}

		$data['bg_timer'] = $setting['bg_timer'];
		$data['status_timer_special'] = $setting['status_timer_special'];

		$this->load->language('cyberstore/lang');
		$data['required_text_option'] = $this->language->get('required_text_option');
		$data['text_select'] = $this->language->get('text_select');
		$data['config_additional_settings_newstore'] = $this->config->get('config_additional_settings_newstore');
		$data['on_off_sticker_special'] = (!empty($this->config->get('on_off_sticker_special')) ? 1 : 0);
		$data['config_change_icon_sticker_special'] = $this->config->get('config_change_icon_sticker_special');
		$data['on_off_percent_discount'] = $this->config->get('on_off_percent_discount');
		$data['on_off_sticker_topbestseller'] = (!empty($this->config->get('on_off_sticker_topbestseller')) ? 1 : 0);
		$data['config_limit_order_product_topbestseller'] = $this->config->get('config_limit_order_product_topbestseller');
		$data['config_change_icon_sticker_topbestseller'] = $this->config->get('config_change_icon_sticker_topbestseller');
		$data['on_off_sticker_popular'] = (!empty($this->config->get('on_off_sticker_popular')) ? 1 : 0);
		$data['config_min_quantity_popular'] = $this->config->get('config_min_quantity_popular');
		$data['config_change_icon_sticker_popular'] = $this->config->get('config_change_icon_sticker_popular');
		$data['on_off_sticker_newproduct'] = (!empty($this->config->get('on_off_sticker_newproduct')) ? 1 : 0);
		$data['config_limit_day_newproduct'] = $this->config->get('config_limit_day_newproduct');
		$data['config_change_icon_sticker_newproduct'] = $this->config->get('config_change_icon_sticker_newproduct');
		$data['config_text_open_form_send_order'] = $this->config->get('config_text_open_form_send_order');
		$data['icon_open_form_send_order'] = $this->config->get('icon_open_form_send_order');

		$this->load->language('extension/module/cyber_productany');
		$data['time_left_to_buy'] = $this->language->get('time_left_to_buy');
		$data['action_end'] = $this->language->get('action_end');
		$data['days'] = $this->language->get('days');
		$data['hours'] = $this->language->get('hours');
		$data['minutes'] = $this->language->get('minutes');
		$data['sec'] = $this->language->get('sec');

		$data['change_text_cart_button_out_of_stock'] = (!empty($this->config->get('config_change_text_cart_button_out_of_stock')) ? 1 : 0);

		$data['text_sticker_special'] = $this->config->get('config_change_text_sticker_special');
		$data['text_sticker_newproduct'] = $this->config->get('config_change_text_sticker_newproduct');
		$data['text_sticker_popular'] = $this->config->get('config_change_text_sticker_popular');
		$data['text_sticker_topbestseller'] = $this->config->get('config_change_text_sticker_topbestseller');

		$this->language->load('product/product');

		$data['text_reviews_title'] = $this->language->get('text_reviews_title');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['config_quickview_btn_name'] = $this->config->get('config_quickview_btn_name');
		$data['config_on_off_special_quickview'] = $this->config->get('config_on_off_special_quickview');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['show_stock_status'] = (!empty($this->config->get('config_show_stock_status')) ? 1 : 0);

		$config_disable_cart_button_text = $this->config->get('config_disable_cart_button_text');
		if(!empty($config_disable_cart_button_text[$this->config->get('config_language_id')]['disable_cart_button_text'])){
			$data['disable_cart_button_text'] = $config_disable_cart_button_text[$this->config->get('config_language_id')]['disable_cart_button_text'];
		} else {
			$data['disable_cart_button_text'] = $this->language->get('disable_cart_button_text');
		}
		$data['disable_cart_button'] = (!empty($this->config->get('config_disable_cart_button')) ? 1 : 0);
		$data['disable_fastorder_button'] = (!empty($this->config->get('config_disable_fastorder_button')) ? 1 : 0);
		$data['show_options'] = (!empty($this->config->get('config_show_options_module')) ? 1 : 0);
		$show_options_required_featured = (!empty($this->config->get('config_show_options_required_module')) ? 1 : 0);
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$data['products'] = array();
		$filter_data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getProductSpecials($filter_data);
		if ($results) {
			foreach ($results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}
					if ($result['image']) {
						$image_thumb = $this->model_tool_image->resize($result['image'], 70, 70);
					} else {
						$image_thumb = $this->model_tool_image->resize('placeholder.png', 70, 70);
					}
					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $result['rating'];
					} else {
						$rating = false;
					}
					if($data['on_off_slider_additional_image'] =='2'){
					$results_img = $this->model_catalog_product->getProductImages($result['product_id']);
					}

					$additional_image_hover = '';
					if($data['on_off_slider_additional_image'] =='2'){
						foreach ($results_img as $key => $result_img) {
							if ($key < 1) {
								$additional_image_hover = $this->model_tool_image->resize($result_img['image'], $setting['width'], $setting['height']);
							}
						}
					}
					if ((float)$result['special']) {
						$price2 = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
						$special2 = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
						$skidka = $special2/($price2/100)-100;
					} else {
						$skidka = "";
					}
					$top_bestsellers = $this->model_catalog_product->getTopSeller($result['product_id']);

					$product_quantity = $result['quantity'];
					$stock_status = $result['stock_status'];
					if((isset($result['date_available'])&&(round((strtotime(date("Y-m-d"))-strtotime($result['date_available']))/86400))<=$this->config->get('config_limit_day_newproduct'))) {
						$sticker_new_prod = true;
					} else {
						$sticker_new_prod = false;
					}
					if (VERSION >= 2.2) {
						$currency = $this->session->data['currency'];
					} else {
						$currency = '';
					}

					$options = array();
				if ($data['show_options']==1) {
					foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
						$product_option_value_data = array();

						foreach ($option['product_option_value'] as $option_value) {
							if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
								if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
									$option_price = $this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $currency);
								} else {
									$option_price = false;
								}
								$option_price = strip_tags($option_price);
								$product_option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'color'                   => $option_value['color'],
									'image'                   => $option_value['image'] ? $this->model_tool_image->resize($option_value['image'], 50, 50) : '',
									'price'                   => $option_price,
									'price_value'             => $this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false),
									'price_prefix'            => $option_value['price_prefix']
								);
							}
						}
						if($show_options_required_featured ==1) {
							if($option['required']) {
								$options[] = array(
									'product_option_id'    => $option['product_option_id'],
									'product_option_value' => $product_option_value_data,
									'option_id'            => $option['option_id'],
									'name'                 => $option['name'],
									'type'                 => $option['type'],
									'status_color_type'    => $option['status_color_type'],
									'value'                => $option['value'],
									'required'             => $option['required']
								);
							}
						} else {
							$options[] = array(
								'product_option_id'    => $option['product_option_id'],
								'product_option_value' => $product_option_value_data,
								'option_id'            => $option['option_id'],
								'name'                 => $option['name'],
								'type'                 => $option['type'],
								'status_color_type'    => $option['status_color_type'],
								'value'                => $option['value'],
								'required'             => $option['required']
							);
						}
					}
				}
					if ((float)$result['special']) {
						$special_date_end = $this->model_catalog_product->getDateEnd($result['product_id']);
					} else {
						$special_date_end = false;
					}
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price_no_format = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
					} else {
						$price_no_format = false;
					}

					if ((float)$result['special']) {
						$special_no_format = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
					} else {
						$special_no_format = false;
					}

					$data['products'][] = array(
						'sticker_new_prod'  => $sticker_new_prod,
						'date_end'	 		=> $special_date_end,
						'options'	  		=> $options,
						'additional_image_hover' => $additional_image_hover,
						'product_quantity' 	=> $product_quantity,
						'stock_status' 		=> $stock_status,
						'reviews'    		=> sprintf((int)$result['reviews']),
						'skidka'     		=> round($skidka),
						'model'     		=> $result['model'],
						'date_available'	=> $result['date_available'],
						'viewed'	 		=> $result['viewed'],
						'top_bestsellers'	=> $top_bestsellers['total'],
						'minimum'     		=> ($result['minimum'] > 0) ? $result['minimum'] : 1,
						'price_no_format' 	=> $price_no_format,
						'special_no_format' => $special_no_format,
						'product_id'  		=> $result['product_id'],
						'thumb'       		=> $image,
						'image_thumb'       => $image_thumb,
						'name'        		=> $result['name'],
						'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       		=> $price,
						'special'     		=> $special,
						'tax'         		=> $tax,
						'rating'      		=> $rating,
						'href'        		=> $this->url->link('product/product', 'product_id=' . $result['product_id'])
					);

			}
		}
		if ($data['products']) {
			return $this->load->view('extension/module/slidedeals', $data);
		}
	}
}