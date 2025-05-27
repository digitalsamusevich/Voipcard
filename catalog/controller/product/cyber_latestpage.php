<?php
class ControllerProductCyberLatestpage extends Controller {

	public function index() {
		$data['setting_lp'] = $this->config->get('setting_lp');
		if(isset($data['setting_lp']['status_latest_page']) && ($data['setting_lp']['status_latest_page'] == 1)){
			$status_active_last_date = (!empty($data['setting_lp']['status_active_last_date']) ? 1 : 0);
			$this->load->language('product/cyber_latestpage');
			$data['heading_title'] = $this->language->get('heading_title');
			$this->document->setTitle($this->language->get('heading_title'));
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_refine'] = $this->language->get('text_refine');
			$data['text_quantity'] = $this->language->get('text_quantity');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');
			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_list'] = $this->language->get('button_list');
			$data['button_grid'] = $this->language->get('button_grid');
			$this->load->model('catalog/product');
			$this->load->model('catalog/cyber_latestpage');
			$this->load->model('tool/image');
			$data['show_attr_cpage'] = $this->config->get('show_attr_cpage');
			$data['cpage_attr_group_count'] = $this->config->get('cpage_attr_group_count');
			$data['cpage_attr_group_item_count'] = $this->config->get('cpage_attr_group_item_count');
			$data['show_subcategories'] = $this->config->get('show_subcategories');
			$megamenu_setting = $this->config->get('megamenu_setting');
			$data['menu_open_category'] = (!empty($megamenu_setting['config_menu_open_category']) ? $megamenu_setting['config_menu_open_category'] : 0);
			$data['main_menu'] = $megamenu_setting['main_menu_selection'];
			$this->load->language('cyberstore/lang');
			$data['lang_id'] = $this->config->get('config_language_id');

			$data['config_on_off_category_page_quickview'] = $this->config->get('config_on_off_category_page_quickview');
			$this->language->load('product/product');
			$data['icon_open_form_send_order'] = $this->config->get('config_icon_open_form_send_order');
			$data['config_text_open_form_send_order'] = $this->config->get('config_text_open_form_send_order');
			$data['text_reviews_title'] = $this->language->get('text_reviews_title');
			$data['button_price'] = $this->language->get('button_price');
			$data['config_quickview_btn_name'] = $this->config->get('config_quickview_btn_name');
			$data['show_stock_status'] = (!empty($this->config->get('config_show_stock_status')) ? 1 : 0);
			$config_disable_cart_button_text = $this->config->get('config_disable_cart_button_text');
			if(!empty($config_disable_cart_button_text[$this->config->get('config_language_id')]['disable_cart_button_text'])){
				$data['disable_cart_button_text'] = $config_disable_cart_button_text[$this->config->get('config_language_id')]['disable_cart_button_text'];
			} else {
				$data['disable_cart_button_text'] = $this->language->get('disable_cart_button_text');
			}
			$data['nst_data'] = $this->config->get('nst_data');
			if(isset($data['nst_data']['lazyload_page']) && ($data['nst_data']['lazyload_page'] == 1)){
				$data['lazyload_page'] = true;
				if (isset($data['nst_data']['lazyload_image']) && ($data['nst_data']['lazyload_image'] !='')) {
					$data['lazy_image'] = 'image/' . $data['nst_data']['lazyload_image'];
				} else {
					$data['lazy_image'] = 'image/catalog/lazyload/lazyload.jpg';
				}
			} else {
				$data['lazyload_page'] = false;
			}
			$data['text_home_ns'] = $this->language->get('text_home_ns');
			$data['text_select'] = $this->language->get('text_select');
			$data['config_additional_settings_newstore'] = $this->config->get('config_additional_settings_newstore');
			$data['required_text_option'] = $this->config->get('required_text_option');
			$data['change_text_cart_button_out_of_stock'] = (!empty($this->config->get('config_change_text_cart_button_out_of_stock')) ? 1 : 0);
			$data['show_special_timer_page'] = (!empty($this->config->get('config_show_special_timer_page')) ? 1 : 0);
			$data['disable_cart_button'] = (!empty($this->config->get('config_disable_cart_button')) ? 1 : 0);
			$data['disable_fastorder_button'] = (!empty($this->config->get('config_disable_fastorder_button')) ? 1 : 0);
			$data['show_options'] = (!empty($this->config->get('config_show_options_page')) ? 1 : 0);
			$show_options_required_featured = (!empty($this->config->get('config_show_options_required_page')) ? 1 : 0);
			$data['on_off_sticker_special'] = (!empty($this->config->get('on_off_sticker_special')) ? 1 : 0);
			$data['on_off_percent_discount'] = $this->config->get('on_off_percent_discount');
			$data['config_change_icon_sticker_special'] = $this->config->get('config_change_icon_sticker_special');
			$data['on_off_sticker_topbestseller'] = (!empty($this->config->get('on_off_sticker_topbestseller')) ? 1 : 0);
			$data['config_limit_order_product_topbestseller'] = $this->config->get('config_limit_order_product_topbestseller');
			$data['config_change_icon_sticker_topbestseller'] = $this->config->get('config_change_icon_sticker_topbestseller');
			$data['on_off_sticker_popular'] = (!empty($this->config->get('on_off_sticker_popular')) ? 1 : 0);
			$data['config_min_quantity_popular'] = $this->config->get('config_min_quantity_popular');
			$data['config_change_icon_sticker_popular'] = $this->config->get('config_change_icon_sticker_popular');
			$data['on_off_sticker_newproduct'] = (!empty($this->config->get('on_off_sticker_newproduct')) ? 1 : 0);
			$data['config_limit_day_newproduct'] = $this->config->get('config_limit_day_newproduct');
			$data['config_change_icon_sticker_newproduct'] = $this->config->get('config_change_icon_sticker_newproduct');
			$data['text_sticker_special'] = $this->config->get('config_change_text_sticker_special');
			$data['text_sticker_newproduct'] = $this->config->get('config_change_text_sticker_newproduct');
			$data['text_sticker_popular'] = $this->config->get('config_change_text_sticker_popular');
			$data['text_sticker_topbestseller'] = $this->config->get('config_change_text_sticker_topbestseller');
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
			} else {
				$sort = 'p.date_added';
			}

			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
			} else {
				$order = 'DESC';
			}

			if (isset($this->request->get['date_ave'])) {
				$date_ave = $this->request->get['date_ave'];
				$data['date_ave'] = $this->request->get['date_ave'];
			} else {
				$date_ave = null;
				$data['date_ave'] = false;
			}

			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}

			if (isset($this->request->get['limit'])) {
				$limit = (int)$this->request->get['limit'];
			} else {
				$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$active_last_date = '';
			$data['date_availeble'] = array();
			if(isset($data['setting_lp']['status_receipt_date']) && ($data['setting_lp']['status_receipt_date'] == 1)){
				$date_availeble = $this->model_catalog_cyber_latestpage->getDateAvaileble();
				if($date_availeble){
					foreach($date_availeble as $result_date){
						$data['date_availeble'][] = array(
							'text'  => $result_date['date_available'],
							'href'  => $this->url->link('product/cyber_latestpage', 'date_ave=' . $result_date['date_available'] . $url)
						);
					}
					if(isset($status_active_last_date) && ($status_active_last_date == 1)){
						$active_last_date_data = array_values($data['date_availeble'])[0];
						$active_last_date = $active_last_date_data['text'];
					}

				}
			}



			if (isset($this->request->get['date_ave'])) {
				$url .= '&date_ave=' . $this->request->get['date_ave'];
			} elseif(isset($status_active_last_date) && ($status_active_last_date == 1)){
				$url .= '&date_ave=' . $active_last_date;
				$data['date_ave'] = $active_last_date;
				$date_ave = $active_last_date;
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('product/cyber_latestpage', $url)
			);
			$data['products'] = array();

			$filter_data = array(
				'sort'  => $sort,
				'date_ave'  => $date_ave,
				'order' => $order,
				'start' => ($page - 1) * $limit,
				'limit' => $limit,

			);

			$product_total = $this->model_catalog_cyber_latestpage->getTotalProducts($filter_data);
			$results = $this->model_catalog_cyber_latestpage->getLatest($filter_data);
			$this->load->model('cyberstore/cyber');
			$data['all_prod'] = $this->model_cyberstore_cyber->multi_implode(",", $results);
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
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
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				$additional_image_hover = '';
				if(isset($data['setting_lp']['status_lp_dop_image_hover_cp']) && $data['setting_lp']['status_lp_dop_image_hover_cp'] =='1'){
					$results_img = $this->model_catalog_product->getProductImages($result['product_id']);
					foreach ($results_img as $key => $result_img) {
						if ($key < 1) {
							$additional_image_hover = $this->model_tool_image->resize($result_img['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
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
				if((isset($result['date_available'])&&(round((strtotime(date("Y-m-d"))-strtotime($result['date_available']))/86400))<=$this->config->get('config_limit_day_newproduct'))) {
					$sticker_new_prod = true;
				} else {
					$sticker_new_prod = false;
				}
				$this->load->model('tool/image');
						if (VERSION >= 2.2) {
							$currency = $this->session->data['currency'];
						} else {
							$currency = '';
						}
						if ((float)$result['special']) {
							$special_date_end = $this->model_catalog_product->getDateEnd($result['product_id']);
						} else {
							$special_date_end = false;
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

				$data['products'][] = array(
					'sticker_new_prod'  => $sticker_new_prod,
					'options'	  => $options,
					'date_end'	  => $special_date_end,
					'reviews'      		=> (int)$result['reviews'],
					'price_no_format' 	=> $price_no_format,
					'special_no_format' => $special_no_format,
					'product_quantity' 	=> $product_quantity,
					'stock_status' 		=> $stock_status,
					'skidka'     		=> round($skidka),
					'model'     		=> $result['model'],
					'date_available'	=> $result['date_available'],
					'viewed'	 		=> $result['viewed'],
					'top_bestsellers'	=> $top_bestsellers['total'],
					'additional_image_hover' 	=> $additional_image_hover,
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
				);
			}

			$url = '';

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
			if (isset($this->request->get['date_ave'])) {
				$url .= '&date_ave=' . $this->request->get['date_ave'];
			} elseif(isset($status_active_last_date) && ($status_active_last_date == 1)){
				$url .= '&date_ave=' . $active_last_date;
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.date_added-DESC',
				'href'  => $this->url->link('product/cyber_latestpage', 'sort=p.date_added&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/cyber_latestpage', 'sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/cyber_latestpage', 'sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/cyber_latestpage', 'sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/cyber_latestpage', 'sort=p.price&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/cyber_latestpage', 'sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/cyber_latestpage', 'sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if (isset($this->request->get['date_ave'])) {
				$url .= '&date_ave=' . $this->request->get['date_ave'];
			} elseif(isset($status_active_last_date) && ($status_active_last_date == 1)){
				$url .= '&date_ave=' . $active_last_date;
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/cyber_latestpage', $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['date_ave'])) {
				$url .= '&date_ave=' . $this->request->get['date_ave'];
			} elseif(isset($status_active_last_date) && ($status_active_last_date == 1)){
				$url .= '&date_ave=' . $active_last_date;
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/cyber_latestpage', $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			if ($page == 1) {
				$this->document->addLink($this->url->link('product/cyber_latestpage', '', true), 'canonical');
			} elseif ($page == 2) {
				$this->document->addLink($this->url->link('product/cyber_latestpage', '', true), 'prev');
			} else {
				$this->document->addLink($this->url->link('product/cyber_latestpage', 'page='. ($page - 1), true), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
				$this->document->addLink($this->url->link('product/cyber_latestpage', 'page='. ($page + 1), true), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('product/latestpage', $data));
		} else {
			$this->load->language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
}
