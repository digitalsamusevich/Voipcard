<?php
class ControllerExtensionModuleCyberProSticker extends Controller {
	public function index() {
		if (isset($this->request->get['product_id'])) {
			$data['product_id'] = $this->request->get['product_id'];
		} else {
			$data['product_id'] = '';
		}
		$this->document->addStyle('catalog/view/theme/cyberstore/stylesheet/prosticker.css');

		return $this->load->view('extension/module/pro_sticker', $data);

	}
	public function load_content() {
		$data['settings'] = $this->getSettings();
		if (isset($this->request->get['product_id'])) {
			$data['product_id'] = $this->request->get['product_id'];
		} else {
			$data['product_id'] = '';
		}
		$data['url_module'] = 'index.php?route=extension/module/cyber_pro_sticker/ProStickerLoad';
		$this->response->setOutput($this->load->view('extension/module/pro_sticker_load', $data));

	}
	public function ProStickerLoad() {
		$lang_id = $this->config->get('config_language_id');
		$settings = $this->getSettings();
		$products_id = $products_url_alias = $stikers_ajax_product = array ();

		if (isset($this->request->post['prod_id_ajax']) && is_array($this->request->post['prod_id_ajax'])) {
			foreach ($this->request->post['prod_id_ajax'] as $key => $value) {
				$products_id[$key] = (int)$value;
			}
		}

		if (isset($this->request->post['url_product']) && is_array($this->request->post['url_product'])) {
			foreach ($this->request->post['url_product'] as $key => $value) {
				$products_url_alias[$key] = $this->db->escape(utf8_strtolower(urldecode($value)));
			}
		}

		if ($products_url_alias) {
			$query = $this->db->query('SELECT query, LCASE(keyword) AS keyword FROM ' . DB_PREFIX . 'seo_url
			WHERE keyword IN ("' . implode ('","', $products_url_alias) . '")
			AND query LIKE "product_id=%"
			AND language_id = ' . (int)$this->config->get('config_language_id') . '');

			foreach ($query->rows as $data) {
				foreach ($products_url_alias as $index=>$keyword) {
					if ($keyword == $data['keyword']) {
						$products_id[$index] = (int) str_replace ('product_id=', '', $data['query']);
						unset ($products_url_alias[$index]);
					}
				}
			}
		}

		if ($products_id) {
			if ($this->customer->isLogged()) {
				$customer_group_id = $this->customer->getGroupId();
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			$query = $this->db->query('SELECT p.product_id AS product_id, p.sticker_id, p.price AS price,p.tax_class_id AS tax_class_id,p.viewed AS viewed, p.date_available
				AS date_available, p.quantity AS quantity, p.date_start_sticker AS date_start_sticker, p.date_end_sticker AS date_end_sticker,
				 m.image AS manufacturer, ps.price AS special FROM ' . DB_PREFIX . 'product p
				 LEFT JOIN ' . DB_PREFIX . 'manufacturer m ON (m.manufacturer_id = p.manufacturer_id)
				 LEFT JOIN ' . DB_PREFIX . 'product_special ps ON (ps.product_id = p.product_id
				 AND ps.customer_group_id = "' . (int) $customer_group_id . '"
				 AND ((ps.date_start = "0000-00-00" OR ps.date_start < NOW())
				 AND (ps.date_end = "0000-00-00" OR ps.date_end > NOW())))
				 WHERE p.product_id IN (' . implode (',', array_unique ($products_id)) . ') AND p.date_available <= NOW() AND p.status = "1" ORDER BY ps.priority DESC, ps.price DESC');

			$results = array ();

			foreach ($query->rows as $value) {

				$sticker_images_info = $this->getStickers($value['sticker_id']);

				$results[$value['product_id']] = array (
					'sticker_images_info'	=> $sticker_images_info,
					'quantity'       => $value['quantity'],
					'manufacturer'   => $value['manufacturer'],
					'special'        => $value['special'],
					'price'        	 => $value['price'],
					'tax_class_id'    => $value['tax_class_id'],
					'date_available' => $value['date_available'],
					'viewed' 		=> $value['viewed'],
					'date_start_sticker'     => $value['date_start_sticker'],
					'date_end_sticker'       => $value['date_end_sticker']
				);
			}

			if ($results) {
				$positions = array(
					'topleft' => 'top 5px left',
					'topcenter' => 'top 5px center',
					'topright' => 'top 5px right',
					'centerleft' => 'center left',
					'centercenter' => 'center center',
					'centerright' => 'center right',
					'bottomleft' => 'bottom 5px left',
					'bottomcenter' => 'bottom 5px center',
					'bottomright' => 'bottom 5px right'
				);

				$this->load->model('catalog/product');
				$this->load->model('tool/image');

				foreach ($products_id as $index=>$product_id) {
					$astickers = '';
					$sticker_image_height = '';
					if (isset ($results[$product_id])) {
						$positions_status = array ('topleft' => 1, 'topcenter' => 1, 'topright' => 1, 'centerleft' => 1, 'centercenter' => 1, 'centerright' => 1, 'bottomleft' => 1, 'bottomcenter' => 1, 'bottomright' => 1);
						$indent_px = array ('topleft' => 0, 'topcenter' => 0, 'topright' => 0, 'centerleft' => 0, 'centercenter' => 0, 'centerright' => 0, 'bottomleft' => 0, 'bottomcenter' => 0, 'bottomright' => 0);
						$margin_tb = array ('topleft' => 'margin-top:', 'topcenter' => 'margin-top:', 'topright' => 'margin-top:', 'centerleft' => 'margin-top:', 'centercenter' => 'margin-top:', 'centerright' => 'margin-top:', 'bottomleft' => 'margin-bottom:', 'bottomcenter' => 'margin-bottom:', 'bottomright' => 'margin-bottom:');

						if ($settings['special_status'] && ($settings['special_stiker_design'] == '0') && $results[$product_id]['special'] && is_file(DIR_IMAGE . $settings['special_image'])) {
							$sticker_image_height = getimagesize(DIR_IMAGE . $settings['special_image']);
							if(isset($settings['special_view']) && ($settings['special_view'] == 0)){
								$special_view = 'sticker_before_price ';
							} else {
								$special_view = '';
							}
							$astickers .= '<div data-position="'. $settings['special_position'] .'" data-img-height="'. $sticker_image_height[1] .'" class="pro_sticker '. $special_view .'' . $settings['special_position'] . '"><img src="' . '../image/' . $settings['special_image'] . '"/></div>';
							$indent_px[$settings['special_position']] += $sticker_image_height[1] + 5;
						}
						$text_label_ss = $settings['special_text_sticker'][$lang_id];

						$text_color_ss = $settings['special_text_color'][$lang_id];
						$text_bg_color_ss = $settings['special_bg_color'][$lang_id];
						$special_dp_color = $settings['special_dp_color'][$lang_id];
						$special_dp_bg_color = $settings['special_dp_bg_color'][$lang_id];
						if ($settings['special_status'] && ($settings['special_stiker_design'] == '1') && $results[$product_id]['special']) {

								$skidka = 0;
								if ((float)$results[$product_id]['special']) {
									$price2 = $this->tax->calculate($results[$product_id]['price'], $results[$product_id]['tax_class_id'], $this->config->get('config_tax'));
									$special2 = $this->tax->calculate($results[$product_id]['special'], $results[$product_id]['tax_class_id'], $this->config->get('config_tax'));
									if($price2 != $special2){
										$skidka = $special2/($price2/100)-100;
									}
								} else {
									$skidka = "";
								}
								if ($settings['special_status_dp'] && $skidka !=0) {
									$astickers .= '<div class="bsl box-sticker '. $settings['special_position'] .'"><span class="sticker_label" style="background:#'. $special_dp_bg_color .'; color:#'. $special_dp_color .'">'. round($skidka) .' %</span></div>';
								}
								if (isset($text_label_ss) && (!empty($text_label_ss))) {
										$astickers .= '<div class="box-sticker '. $settings['special_position'] .'"><span class="sticker_label" style="background:#'. $text_bg_color_ss .'; color:#'. $text_color_ss .'">'. $text_label_ss .'</span></div>';
										$positions_status[$settings['special_position']] = 0;
								}
						}



						if ($settings['new_status'] && ($settings['new_stiker_design'] == '0') && is_file(DIR_IMAGE . $settings['new_image'])) {
							$time = explode ('-', $results[$product_id]['date_available']);
							if (time () < (mktime (0, 0, 0, $time[1], $time[2], $time[0]) + ($settings['days_new'] * 86400))) {
								if(isset($settings['new_view']) && ($settings['new_view'] == 0)){
									$new_view = 'sticker_before_price ';
								} else {
									$new_view = '';
								}
								$astickers .= '<div class="pro_sticker '. $new_view .'' . $settings['new_position'] . '" style-mob="'. $margin_tb[$settings['new_position']] .'calc('. $indent_px[$settings['new_position']] .'px / 2);" style="'. $margin_tb[$settings['new_position']] . $indent_px[$settings['new_position']] .'px;"><img src="' . '../image/' . $settings['new_image'] . '"/></div>';
								$sticker_image_height = getimagesize(DIR_IMAGE . $settings['new_image']);
								$indent_px[$settings['new_position']] += $sticker_image_height[1] + 5;
							}
						}

						$text_label_ns = $settings['new_text_sticker'][$lang_id];
						$text_color_ns = $settings['new_text_color'][$lang_id];
						$text_bg_color_ns = $settings['new_bg_color'][$lang_id];

						if ($settings['new_status'] && ($settings['new_stiker_design'] == '1')) {
							$time = explode ('-', $results[$product_id]['date_available']);
							if (time () < (mktime (0, 0, 0, $time[1], $time[2], $time[0]) + ($settings['days_new'] * 86400))) {
								if (isset($text_label_ns) && (!empty($text_label_ns))) {
										$astickers .= '<div class="box-sticker '. $settings['new_position'] .'"><span class="sticker_label" style="background:#'. $text_bg_color_ns .'; color:#'. $text_color_ns .'">'. $text_label_ns .'</span></div>';
										$positions_status[$settings['new_position']] = 0;
								}
							}
						}

						if ($settings['bestseller_status'] && ($settings['bestseller_stiker_design'] == '0') && is_file(DIR_IMAGE . $settings['bestseller_image'])) {
							$bestsellers_total = $this->getTopSeller($product_id);
							if(($bestsellers_total >= $settings['limit_order'])) {
								if(isset($settings['bestseller_view']) && ($settings['bestseller_view'] == 0)){
									$bestseller_view = 'sticker_before_price ';
								} else {
									$bestseller_view = '';
								}
								$astickers .= '<div class="pro_sticker '. $bestseller_view .'' . $settings['bestseller_position'] . '" style-mob="'. $margin_tb[$settings['bestseller_position']] .'calc('. $indent_px[$settings['bestseller_position']] .'px / 2);" style="'. $margin_tb[$settings['bestseller_position']] . $indent_px[$settings['bestseller_position']] .'px;"><img src="' . '../image/' . $settings['bestseller_image'] . '"/></div>';
								$sticker_image_height = getimagesize(DIR_IMAGE . $settings['bestseller_image']);
								$indent_px[$settings['bestseller_position']] += $sticker_image_height[1] + 5;
							}
						}
						$text_label_bs = $settings['bestseller_text_sticker'][$lang_id];
						$text_color_bs = $settings['bestseller_text_color'][$lang_id];
						$text_bg_color_bs = $settings['bestseller_bg_color'][$lang_id];
						if ($settings['bestseller_status'] && ($settings['bestseller_stiker_design'] == '1')) {
						$bestsellers_total = $this->getTopSeller($product_id);
							if(($bestsellers_total >= $settings['limit_order'])) {
								if (isset($text_label_bs) && (!empty($text_label_bs))) {
									$astickers .= '<div class="box-sticker '. $settings['bestseller_position'] .'"><span class="sticker_label" style="background:#'. $text_bg_color_bs .'; color:#'. $text_color_bs .'">'. $text_label_bs .'</span></div>';
									$positions_status[$settings['bestseller_position']] = 0;
								}
							}
						}

						if ($settings['popular_status']  && ($settings['popular_stiker_design'] == '0') && is_file(DIR_IMAGE . $settings['popular_image'])) {
							$limit_viewed = $results[$product_id]['viewed'];
							if(($limit_viewed >= $settings['limit_viewed'])) {
								if(isset($settings['popular_view']) && ($settings['popular_view'] == 0)){
									$popular_view = 'sticker_before_price ';
								} else {
									$popular_view = '';
								}
								$astickers .= '<div class="pro_sticker '. $popular_view .'' . $settings['popular_position'] . '" style-mob="'. $margin_tb[$settings['popular_position']] .'calc('. $indent_px[$settings['popular_position']] .'px / 2);" style="'. $margin_tb[$settings['popular_position']] . $indent_px[$settings['popular_position']] .'px;"><img src="' . '../image/' . $settings['popular_image'] . '"/></div>';
								$sticker_image_height = getimagesize(DIR_IMAGE . $settings['popular_image']);
								$indent_px[$settings['popular_position']] += $sticker_image_height[1] + 5;
							}
						}
						$text_label_ps = $settings['popular_text_sticker'][$lang_id];
						$text_color_ps = $settings['popular_text_color'][$lang_id];
						$text_bg_color_ps = $settings['popular_bg_color'][$lang_id];
						if ($settings['popular_status'] && ($settings['popular_stiker_design'] == '1')) {
							$limit_viewed = $results[$product_id]['viewed'];
							if(($limit_viewed >= $settings['limit_viewed'])) {
								$astickers .= '<div class="box-sticker '. $settings['popular_position'] .'"><span class="sticker_label" style="background:#'. $text_bg_color_ps .'; color:#'. $text_color_ps .'">'. $text_label_ps .'</span></div>';
								$positions_status[$settings['popular_position']] = 0;
							}
						}



						if ($settings['quantity_status'] && $settings['quantity'] && ($settings['quantity_stiker_design'] == '0')) {
							foreach ($settings['quantity'] as $quantity) {
								if (($results[$product_id]['quantity'] >= $quantity['min']) && ($results[$product_id]['quantity'] <= $quantity['max']) && is_file(DIR_IMAGE . $quantity['image'])) {
									$astickers .= '<div class="pro_sticker ' . $settings['quantity_position'] . '" style-mob="'. $margin_tb[$settings['quantity_position']] .'calc('. $indent_px[$settings['quantity_position']] .'px / 2);" style="'. $margin_tb[$settings['quantity_position']] . $indent_px[$settings['quantity_position']] .'px;"><img src="' . '../image/' . $quantity['image'] . '"/></div>';
									$sticker_image_height = getimagesize(DIR_IMAGE . $quantity['image']);
									$indent_px[$settings['quantity_position']] += $sticker_image_height[1] + 5;
									break;
								}
							}
						}

						if ($settings['quantity_status'] && $settings['quantity'] && ($settings['quantity_stiker_design'] == '1')) {
							foreach ($settings['quantity'] as $quantity) {
								if (($results[$product_id]['quantity'] >= $quantity['min']) && ($results[$product_id]['quantity'] <= $quantity['max'])) {
									$astickers .= '<div class="box-sticker '. $settings['quantity_position'] .'"><span class="sticker_label" style="background:#'. $quantity['quantity_bg_color'][$lang_id] .'; color:#'. $quantity['quantity_text_color'][$lang_id] .'">'. $quantity['quantity_text_sticker'][$lang_id] .'</span></div>';
									$positions_status[$settings['quantity_position']] = 0;
									break;
								}
							}
						}

						if ($settings['manufacturer_status'] && is_file(DIR_IMAGE . $results[$product_id]['manufacturer'])) {
							$astickers .= '<div class="pro_sticker ' . $settings['manufacturer_position'] . '" style-mob="'. $margin_tb[$settings['manufacturer_position']] .'calc('. $indent_px[$settings['manufacturer_position']] .'px / 2);" style="'. $margin_tb[$settings['manufacturer_position']] . $indent_px[$settings['manufacturer_position']] .'px;"><img src="' . $this->model_tool_image->resize($results[$product_id]['manufacturer'], $settings['width'], $settings['height']) . '"/></div>';
							$indent_px[$settings['manufacturer_position']] += $settings['height'] + 5;
						}

						if ((date ('Y-m-d') >= $results[$product_id]['date_start_sticker']) && (date ('Y-m-d') <= $results[$product_id]['date_end_sticker']) || (($results[$product_id]['date_start_sticker'] == '0000-00-00') && ($results[$product_id]['date_end_sticker'] == '0000-00-00'))) {



						if($results[$product_id]['sticker_images_info']){
							foreach($results[$product_id]['sticker_images_info'] as $info_stickers){
									$images = json_decode($info_stickers['images'], true);
									$popover_text = json_decode($info_stickers['popover_text'], true);

									$text_label = json_decode($info_stickers['text_label'], true);
									if (isset($text_label[$lang_id])) {
										foreach ($text_label[$lang_id] as $label) {
											$astickers .= '<div class="box-sticker '. $label['position'] .'"><span class="sticker_label" style="background:#'. $label['bg_color'] .'; color:#'. $label['text_color'] .'">'. $label['text'] .'</span></div>';
										}
									}


									if(isset($info_stickers['view']) && ($info_stickers['view'] == 0)){
										$view_pp = 'sticker_before_price ';
									} else {
										$view_pp = '';
									}
									if ($images) {
										foreach ($positions as $position=>$value) {
											if ($positions_status[$position] && !empty($images[$lang_id][$position]) && is_file(DIR_IMAGE . $images[$lang_id][$position])) {
												$sticker_image_height = getimagesize(DIR_IMAGE . $images[$lang_id][$position]);

												$astickers .= '<div data-position="'. $position .'" data-img-height="'. $sticker_image_height[1] .'" style-mob="'. $margin_tb[$position] .'calc('. $indent_px[$position] .'px / 2);" style="'. $margin_tb[$position] . $indent_px[$position] .'px;" class="pro_sticker '. $view_pp .''. $position .'">
												<img data-toggle="popover" data-content="'. $popover_text[$lang_id][$position] .'" class="pop_sticker" src="' . '../image/' . $images[$lang_id][$position] . '"/></div>';
												$indent_px[$position] += $sticker_image_height[1] + 5;
											}
										}
									}
							}
						}

					}
					}

					$stikers_ajax_product[$index] = $astickers;
				}
			}
		}

		header ('Content-type: text/html; charset=utf-8');

		echo json_encode($stikers_ajax_product);
	}
	private function getTopSeller($product_id) {
		$query = $this->db->query("SELECT SUM(quantity) AS total FROM " . DB_PREFIX . "order_product op WHERE op.product_id = '". (int)$product_id ."'");
		return ($query->row['total'] ? $query->row['total'] : 0);
	}
	private function getStickers($stickers_id) {
		if(!empty($stickers_id)){
			$query = $this->db->query("SELECT images,text_label, popover_text, view FROM " . DB_PREFIX . "stickers_list WHERE sticker_id IN (" . $this->db->escape($stickers_id) . ") ORDER BY sort_order ASC");

			return $query->rows;
		}
		return false;
	}
	private function getSettings() {
		$settings = $this->config->get('module_cyber_pro_sticker_settings');
		$default = array (
			'class' => '.image',
			'class_main_image' => '#image-box .slider-main-img',
			'min_width' => 40,
			'min_height' => 40,
			'hide_hover' => 1,
			'show_effect' => 0,
			'z_index' => 0,
			'width' => 40,
			'height' => 40,
			'days_new' => 30,
			'popular_status' => 0,
			'popular_position' => 'bottomleft',
			'popular_image' => '',
			'popular_text_sticker' => '',
			'popular_text_color' => '',
			'popular_bg_color' => '',
			'popular_stiker_design' => 0,
			'popular_view' => 0,
			'limit_viewed' => 15,
			'bestseller_status' => 0,
			'bestseller_position' => 'topright',
			'bestseller_image' => '',
			'bestseller_text_sticker' => '',
			'bestseller_text_color' => '',
			'bestseller_bg_color' => '',
			'bestseller_stiker_design' => 0,
			'bestseller_view' => 0,
			'limit_order' => 10,
			'special_status' => 0,
			'special_position' => 'topleft',
			'special_image' => 'catalog/stickers_icon/special_top_left.png',
			'special_text_sticker' => '',
			'special_text_color' => '',
			'special_bg_color' => '',
			'special_stiker_design' => 0,
			'special_status_dp' => 0,
			'special_dp_color' => 0,
			'special_dp_bg_color' => 0,
			'special_view' => 0,
			'new_status' => 0,
			'new_position' => 'bottomright',
			'new_image' => 'catalog/stickers_icon/new_bottom_right.png',
			'new_text_sticker' => '',
			'new_text_color' => '',
			'new_bg_color' => '',
			'new_view' => '',
			'new_stiker_design' => 0,
			'quantity_status' => 0,
			'quantity_stiker_design' => 0,
			'quantity_position' => 'topright',
			'manufacturer_status' => 0,
			'manufacturer_position' => 'bottomleft',
			'quantity' => array(array('min' => -10, 'max' => 0, 'image' => ''), array ('min' => 1, 'max' => 50, 'image' => ''), array ('min' => 51, 'max' => 100, 'image' => ''), array ('min' => 101, 'max' => 150, 'image' => ''), array ('min' => 151, 'max' => 200, 'image' => ''), array ('min' => 201, 'max' => 1000, 'image' => '')));

		foreach ($default as $setting=>$value) {
			if (!isset($settings[$setting])) {
				$settings[$setting] = $value;
			}
		}

		return $settings;
	}
}