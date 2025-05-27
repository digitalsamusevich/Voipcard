<?php
class ModelExtensionModuleCyberNsmenu extends Model {
	public function getItemsMenu() {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "megamenuvh ORDER BY sort_menu ASC");

		return $query->rows;
	}
	public function getItemsDopMenu() {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "megamenuvh WHERE additional_menu='additional' ORDER BY sort_menu ASC");

		return $query->rows;
	}
	private $lang_id;

    public function __construct($registry) {
		parent::__construct($registry);
        $this->lang_id = $this->lang_id();
    }
    private function lang_id() {
        return (int)$this->config->get('config_language_id');
    }
	public function MegaMenuTypeFreeLink($data){

		$this->load->model('tool/image');
		$type_link_data['result_freelink'] = array();
		if(!empty($data['freelinks_setting'])){
			$freelinks_setting = json_decode($data['freelinks_setting'], true);
		} else {
			$freelinks_setting = '';
		}
		$widthfreelink = ((int)$freelinks_setting['freelink_img_width']>0)?(int)$freelinks_setting['freelink_img_width']:50;
		$heightfreelink = ((int)$freelinks_setting['freelink_img_height']>0)?(int)$freelinks_setting['freelink_img_height']:50;

		if($data['image']){
			$thumb_menu = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb_menu = "";
		}
		if($data['image_hover']){
			$thumb_menu_hover = $this->model_tool_image->resize($data['image_hover'], 25, 25);
		} else {
			$thumb_menu_hover = "";
		}
		if(isset($data['use_add_html']) && ($data['use_add_html'] !='0')){
			$add_html = json_decode($data['add_html'], true);
		} else {
			$add_html = false;
		}
		if(!empty($data['namemenu'])){
			$namemenu = json_decode($data['namemenu'], true);
		} else {
			$namemenu = '';
		}
		if(!empty($data['dop_info_vm'])){
			$dop_info_vm = json_decode($data['dop_info_vm'], true);
		} else {
			$dop_info_vm = '';
		}
		if(!empty($data['sticker_parent'])){
			$sticker_parent = json_decode($data['sticker_parent'], true);
		} else {
			$sticker_parent = '';
		}
		if(!empty($data['link'])){
			$link = json_decode($data['link'], true);
		} else {
			$link = '';
		}
		$result['freelink_item'] = array();
		if(!empty($freelinks_setting['freelink_item'][$this->lang_id])){
			foreach($freelinks_setting['freelink_item'][$this->lang_id] as $freelink_item){

				$thumb="";
				if($freelinks_setting['variant_category']=="full_image"){
					if (is_file(DIR_IMAGE . $freelink_item['image'])) {
						$thumb = $this->model_tool_image->resize($freelink_item['image'], $widthfreelink, $heightfreelink);
					} else {
						$thumb = $this->model_tool_image->resize('no_image.png', $widthfreelink, $heightfreelink);
					}
				}
				if(isset($freelink_item['subcat'])){
					$subcat = $freelink_item['subcat'];
				} else {
					$subcat = array();
				}

				$result['freelink_item'][]=array(
					'thumb'  	=> $thumb,
					'name'  		=> $freelink_item['title'],
					'href'   	=> (!empty($freelink_item['link'])) ? $freelink_item['link'] : "javascript:void(0);",
					'children'  => $subcat,
					'sort'   	=> $freelink_item['sort'],
				);

			}
		}
		if(!empty($result['freelink_item'])){
			foreach ($result['freelink_item'] as $key => $value) {
				$sort_freelink[$key] = $value['sort'];
			}
			array_multisort($sort_freelink, SORT_ASC, $result['freelink_item']);
		}
		$number_column_sc = (isset($freelinks_setting['number_column_sc'])) ? $freelinks_setting['number_column_sc']:4;
		if (isset($number_column_sc) && ($number_column_sc == 6)) {
			$ac_number = 2;
		} elseif($number_column_sc == 4) {
			$ac_number = 3;
		} elseif($number_column_sc == 3) {
			$ac_number = 4;
		} else {
			$ac_number = 5;
		}
		$type_freelink_data['result_freelink'] = array(
			'type' 				=> "freelink",
			'thumb' 			=> $thumb_menu,
			'thumb_hover' 		=> $thumb_menu_hover,
			'add_html' 			=> (isset($add_html[$this->lang_id])) ? html_entity_decode($add_html[$this->lang_id], ENT_QUOTES, 'UTF-8'):false,
			'children' 			=> $result['freelink_item'],
			'href' 				=> (!empty($link[$this->lang_id])) ? $link[$this->lang_id] : "javascript:void(0);",
			'name' 				=> $namemenu[$this->lang_id],
			'dop_info_vm' 		=> (isset($dop_info_vm[$this->lang_id])) ? $dop_info_vm[$this->lang_id]:'',
			'sticker_parent' 	=> (isset($sticker_parent[$this->lang_id])) ? $sticker_parent[$this->lang_id]:'',
			'spbg' 				=> $data['sticker_parent_bg'],
			'spctext' 			=> $data['spctext'],
			'subtype' 			=> $freelinks_setting['variant_category'],
			'additional_menu' 	=> $data['additional_menu'],
			'new_blank' 		=> (isset($data['new_blank']))?$data['new_blank']:'0',
			'number_column_sc'=> (isset($freelinks_setting['number_column_sc'])) ? $freelinks_setting['number_column_sc']:4,
			'ac_number' 		=> $ac_number,
		);

        return $type_freelink_data['result_freelink'];
	}
	public function MegaMenuTypeLink($data){

		$this->load->model('tool/image');
		$result_menu_link = array();
		if($data['image']){
			$thumb = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb = "";
		}
		if($data['image_hover']){
			$thumb_hover = $this->model_tool_image->resize($data['image_hover'], 25, 25);
		} else {
			$thumb_hover = "";
		}
		if(!empty($data['namemenu'])){
			$namemenu = json_decode($data['namemenu'], true);
		} else {
			$namemenu = '';
		}
		if(!empty($data['dop_info_vm'])){
			$dop_info_vm = json_decode($data['dop_info_vm'], true);
		} else {
			$dop_info_vm = '';
		}
		if(!empty($data['sticker_parent'])){
			$sticker_parent = json_decode($data['sticker_parent'], true);
		} else {
			$sticker_parent = '';
		}
		if(!empty($data['link'])){
			$link = json_decode($data['link'], true);
		} else {
			$link = '';
		}

		$type_link_data['result_menu_link'] = array(
			'type' 				=> "link",
			'thumb' 			=> $thumb,
			'thumb_hover' 		=> $thumb_hover,
			'children' 			=> false,
			'href' 				=> (trim(isset($link[$this->lang_id]))) ? $link[$this->lang_id] : "javascript:void(0);",
			'name' 				=> $namemenu[$this->lang_id],
			'dop_info_vm' 		=> (isset($dop_info_vm[$this->lang_id])) ? $dop_info_vm[$this->lang_id]:'',
			'sticker_parent' 	=> (isset($sticker_parent[$this->lang_id])) ? $sticker_parent[$this->lang_id]:'',
			'spbg' 				=> $data['sticker_parent_bg'],
			'spctext' 			=> $data['spctext'],
			'additional_menu' 	=> $data['additional_menu'],
			'new_blank' 		=> (isset($data['link_setting']))?$data['link_setting']:'0',
		);

		return $type_link_data['result_menu_link'];
	}
	public function MegaMenuTypeInformation($data){
		$this->load->model('tool/image');
		$this->load->model('catalog/information');
		$result_menu_information=array();
		if($data['image']){
			$thumb = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb = "";
		}
		if($data['image_hover']){
			$thumb_hover = $this->model_tool_image->resize($data['image_hover'], 25, 25);
		} else {
			$thumb_hover = "";
		}
		if(!empty($data['namemenu'])){
			$namemenu = json_decode($data['namemenu'], true);
		} else {
			$namemenu = '';
		}
		if(!empty($data['dop_info_vm'])){
			$dop_info_vm = json_decode($data['dop_info_vm'], true);
		} else {
			$dop_info_vm = '';
		}
		if(!empty($data['sticker_parent'])){
			$sticker_parent = json_decode($data['sticker_parent'], true);
		} else {
			$sticker_parent = '';
		}
		if(!empty($data['link'])){
			$link = json_decode($data['link'], true);
		} else {
			$link = '';
		}
		if(!empty($data['informations_list'])){
			$informations_list = json_decode($data['informations_list'], true);
		} else {
			$informations_list = '';
		}
		if(isset($data['use_add_html']) && ($data['use_add_html'] !='0')){
			$add_html = json_decode($data['add_html'], true);
		} else {
			$add_html = false;
		}


		$result['result_information'] = array();
		if(!empty($informations_list)){
			foreach($informations_list as $information_id){
				$information = $this->model_catalog_information->getInformation($information_id);
				if($information){
					$result['result_information'][]=array(
						'sort_order' => $information['sort_order'],
						'name'  => $information['title'],
						'href'  => $this->url->link('information/information', 'information_id=' . $information['information_id']),
					);
				}
			}
		}
		if(!empty($result['result_information'])){
			foreach ($result['result_information'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
			array_multisort($sort_order, SORT_ASC, $result['result_information']);
		}


		$type_link_data['result_menu_information'] = array(
			'type' 				=> "information",
			'thumb' 			=> $thumb,
			'thumb_hover' 		=> $thumb_hover,
			'children' 			=> $result['result_information'],
			'add_html' 			=> (isset($add_html[$this->lang_id])) ? html_entity_decode($add_html[$this->lang_id], ENT_QUOTES, 'UTF-8'):false,
			'href' 				=> (trim(isset($link[$this->lang_id]))) ? $link[$this->lang_id] : "javascript:void(0);",
			'name' 				=> $namemenu[$this->lang_id],
			'dop_info_vm' 		=> (isset($dop_info_vm[$this->lang_id])) ? $dop_info_vm[$this->lang_id]:'',
			'sticker_parent' 	=> (isset($sticker_parent[$this->lang_id])) ? $sticker_parent[$this->lang_id]:'',
			'spbg' 				=> $data['sticker_parent_bg'],
			'spctext' 			=> $data['spctext'],
			'additional_menu' 	=> $data['additional_menu'],
			'new_blank' 		=> (isset($data['new_blank']))?$data['new_blank']:'0',
		);

        return $type_link_data['result_menu_information'];
	}

	public function MegaMenuTypeManufacturer($data){
		$this->load->model('catalog/manufacturer');
		$this->load->model('tool/image');
		$result_menu_manufacturer=array();
		if($data['image']){
			$thumb = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb = "";
		}
		if($data['image_hover']){
			$thumb_hover = $this->model_tool_image->resize($data['image_hover'], 25, 25);
		} else {
			$thumb_hover = "";
		}
		if(!empty($data['namemenu'])){
			$namemenu = json_decode($data['namemenu'], true);
		} else {
			$namemenu = '';
		}
		if(!empty($data['dop_info_vm'])){
			$dop_info_vm = json_decode($data['dop_info_vm'], true);
		} else {
			$dop_info_vm = '';
		}
		if(!empty($data['sticker_parent'])){
			$sticker_parent = json_decode($data['sticker_parent'], true);
		} else {
			$sticker_parent = '';
		}
		if(!empty($data['link'])){
			$link = json_decode($data['link'], true);
		} else {
			$link = '';
		}
		if(!empty($data['manufacturers_setting'])){
			$manufacturers_setting = json_decode($data['manufacturers_setting'], true);
		} else {
			$manufacturers_setting = '';
		}
		if(isset($data['use_add_html']) && ($data['use_add_html'] !='0')){
			$add_html = json_decode($data['add_html'], true);
		} else {
			$add_html = false;
		}

		$data['result_manufacturer']=array();
		$data['result_manufacturer_a'] = array();
		if(!empty($manufacturers_setting['manufacturers_list'])){
			foreach($manufacturers_setting['manufacturers_list'] as $manufacturer_id){
				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
				if($manufacturer){
					$thumb_mr = "";
					if (is_file(DIR_IMAGE . $manufacturer['image'])) {
						$thumb_mr = $this->model_tool_image->resize($manufacturer['image'], 50, 50);
					} else {
						$thumb_mr = $this->model_tool_image->resize('no_image.png', 50, 50);
					}
					$name_m = $manufacturer['name'];

					if (is_numeric(utf8_substr($name_m, 0, 1))) {
						$key = '0 - 9';
					} else {
						$key = utf8_substr(utf8_strtoupper($name_m), 0, 1);
					}
					if (!isset($data['result_manufacturer_a'][$key])) {
						$data['result_manufacturer_a'][$key]['name'] = $key;
					}
					$data['result_manufacturer_a'][$key]['manufacturer'][] = array(
						'name' => $name_m,
						'thumb'	=>	$thumb_mr,
						'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'])
					);
					$data['result_manufacturer'][] = array(
						'name'  => 	$manufacturer['name'],
						'href'  => 	$this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']),
						'thumb'	=>	$thumb_mr
					);
				}
			}
		}
		if($manufacturers_setting['type_manuf']) {
			$type_manuf = $manufacturers_setting['type_manuf'];
		} else {
			$type_manuf = '';
		}
		$type_link_data['result_menu_manufacturer'] = array(
			'type' 					=> "manufacturer",
			'type_manuf' 			=> $type_manuf,
			'result_manufacturer_a' => $data['result_manufacturer_a'],
			'children' 				=> $data['result_manufacturer'],
			'thumb' 				=> $thumb,
			'thumb_hover' 			=> $thumb_hover,
			'add_html' 			=> (isset($add_html[$this->lang_id])) ? html_entity_decode($add_html[$this->lang_id], ENT_QUOTES, 'UTF-8'):false,
			'href' 					=> (trim(isset($link[$this->lang_id]))) ? $link[$this->lang_id] : "javascript:void(0);",
			'name' 					=> $namemenu[$this->lang_id],
			'dop_info_vm' 		=> (isset($dop_info_vm[$this->lang_id])) ? $dop_info_vm[$this->lang_id]:'',
			'sticker_parent' 	=> (isset($sticker_parent[$this->lang_id])) ? $sticker_parent[$this->lang_id]:'',
			'spbg' 				=> $data['sticker_parent_bg'],
			'spctext' 			=> $data['spctext'],
			'additional_menu' 		=> $data['additional_menu'],
			'new_blank' 			=> (isset($data['new_blank']))?$data['new_blank']:'0',
		);

		return $type_link_data['result_menu_manufacturer'];
	}
	public function MegaMenuTypeProduct($data){

		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		if(!empty($data['products_setting'])){
			$products_setting = json_decode($data['products_setting'], true);
		} else {
			$products_setting = '';
		}
		$width = ((int)$products_setting['product_width']>0)?(int)$products_setting['product_width']:50;
		$height = ((int)$products_setting['product_height']>0)?(int)$products_setting['product_height']:50;
		$result_menu_product=array();

		if($data['image']){
			$thumb_menu = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb_menu = "";
		}
		if($data['image_hover']){
			$thumb_menu_hover = $this->model_tool_image->resize($data['image_hover'], 25, 25);
		} else {
			$thumb_menu_hover = "";
		}
		if(!empty($data['namemenu'])){
			$namemenu = json_decode($data['namemenu'], true);
		} else {
			$namemenu = '';
		}
		if(!empty($data['dop_info_vm'])){
			$dop_info_vm = json_decode($data['dop_info_vm'], true);
		} else {
			$dop_info_vm = '';
		}
		if(!empty($data['sticker_parent'])){
			$sticker_parent = json_decode($data['sticker_parent'], true);
		} else {
			$sticker_parent = '';
		}
		if(!empty($data['link'])){
			$link = json_decode($data['link'], true);
		} else {
			$link = '';
		}

		if(isset($data['use_add_html']) && ($data['use_add_html'] !='0')){
			$add_html = json_decode($data['add_html'], true);
		} else {
			$add_html = false;
		}
		$data['result_product']=array();
		if(is_array($products_setting['products_list'])){
			foreach($products_setting['products_list'] as $product_id){
				$product_info = $this->model_catalog_product->getProduct($product_id);
				if($product_info){
					$thumb = "";
					if (is_file(DIR_IMAGE . $product_info['image'])) {
						$thumb = $this->model_tool_image->resize($product_info['image'], $width, $height);
					} else {
						$thumb = $this->model_tool_image->resize('no_image.png', $width, $height);
					}
					if (VERSION >= 2.2) {
						$currency = $this->session->data['currency'];
					} else {
						$currency = '';
					}
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $currency);
					} else {
						$data['price'] = false;
					}

					if ((float)$product_info['special']) {
						$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $currency);
					} else {
						$data['special'] = false;
					}
					$data['result_product'][]=array(
						'name'  => $product_info['name'],
						'href'  => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])	,
						'thumb'	=> $thumb,
						'price'	=> $data['price'],
						'special'=>$data['special']
					);
				}
			}
		}

		$type_link_data['result_menu_product'] = array(
			'type' 				=> "product",
			'children' 			=> $data['result_product'],
			'href' 				=> (trim(isset($link[$this->lang_id]))) ? $link[$this->lang_id] : "javascript:void(0);",
			'name' 				=> $namemenu[$this->lang_id],
			'dop_info_vm' 		=> (isset($dop_info_vm[$this->lang_id])) ? $dop_info_vm[$this->lang_id]:'',
			'sticker_parent' 	=> (isset($sticker_parent[$this->lang_id])) ? $sticker_parent[$this->lang_id]:'',
			'spbg' 				=> $data['sticker_parent_bg'],
			'spctext' 			=> $data['spctext'],
			'additional_menu' 	=> $data['additional_menu'],
			'new_blank' 		=> (isset($data['new_blank']))?$data['new_blank']:'0',
			'add_html' 			=> (isset($add_html[$this->lang_id])) ? html_entity_decode($add_html[$this->lang_id], ENT_QUOTES, 'UTF-8'):false,
			'thumb' 			=> $thumb_menu,
			'thumb_hover' 		=> $thumb_menu_hover,
		);

		return $type_link_data['result_menu_product'];
	}

	public function getCategoryPath($category_id){
		$path = '';
		$category = $this->db->query("SELECT c.`category_id`,c.`parent_id` FROM " . DB_PREFIX . "category c WHERE c.`category_id` = " .(int)$category_id."");
		if($category->row['parent_id'] != 0){
			$path .= $this->getCategoryPath($category->row['parent_id']) . '_';
		}
		$path .= $category->row['category_id'];

		return $path;
	}
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT c.`category_id`,c.`image`, cd2.`name` FROM " . DB_PREFIX . "category c
		LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.`category_id` = cd2.`category_id`) WHERE c.`category_id` = " . (int)$category_id . " AND cd2.language_id = " . $this->lang_id . "");
		return $query->row;
	}
	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT c.`category_id`, cd.`name` FROM " . DB_PREFIX . "category c
		LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)
		LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)
		WHERE c.parent_id = '" . (int)$parent_id . "'
		AND cd.language_id = '" . $this->lang_id . "'
		AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
		AND c.status = '1' ORDER BY sort_order ASC");
		return $query->rows;
	}
	public function MegaMenuTypeCategory($data){
		$this->load->model('catalog/category');
		$this->load->model('tool/image');
		if(!empty($data['category_setting'])){
			$category_setting = json_decode($data['category_setting'], true);
		} else {
			$category_setting = '';
		}
		$width = ((int)$category_setting['category_img_width']>0)?(int)$category_setting['category_img_width']:50;
		$height = ((int)$category_setting['category_img_height']>0)?(int)$category_setting['category_img_height']:50;

		if($data['image']){
			$thumb_menu = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb_menu = "";
		}
		if($data['image_hover']){
			$thumb_menu_hover = $this->model_tool_image->resize($data['image_hover'], 25, 25);
		} else {
			$thumb_menu_hover = "";
		}

		if(!empty($data['namemenu'])){
			$namemenu = json_decode($data['namemenu'], true);
		} else {
			$namemenu = '';
		}
		if(!empty($data['dop_info_vm'])){
			$dop_info_vm = json_decode($data['dop_info_vm'], true);
		} else {
			$dop_info_vm = '';
		}
		if(!empty($data['link'])){
			$link = json_decode($data['link'], true);
		} else {
			$link = '';
		}
		if(!empty($data['sticker_parent'])){
			$sticker_parent = json_decode($data['sticker_parent'], true);
		} else {
			$sticker_parent = '';
		}
		if(isset($data['use_add_html']) && ($data['use_add_html'] !='0')){
			$add_html = json_decode($data['add_html'], true);
		} else {
			$add_html = false;
		}

		$result_category=array();
		if(!empty($category_setting['category_list'])){
			$data_categories_list = $category_setting['category_list'];
		} else {
			$data_categories_list = '';
		}

		if(is_array($data_categories_list)){
			$category_list = array();
			foreach($data_categories_list as $cat){
				$category = $this->getCategory($cat);
				if($category){
					$category_list[]=$category;
				}

			}

			foreach($category_list as $category){
				if($category){
					$thumb = "";
					if($category_setting['variant_category']=="full_image"){
						if (is_file(DIR_IMAGE . $category['image'])) {
						$thumb = $this->model_tool_image->resize($category['image'], $width, $height);
						} else {
						$thumb = $this->model_tool_image->resize('no_image.png', $width, $height);
						}
					}

					$children_data=array();
					$showmore = 0;
					if($category_setting['show_sub_category']){
						$children = $this->getCategories($category['category_id']);
						if($children){
							if(isset($category_setting['count_sub_cat'])){
							$count_child = count($children);
								if($count_child > $category_setting['count_sub_cat']){
									$showmore = 1;
								} else {
									$showmore = 0;
								}
							}
							foreach ($children as $key => $child) {
								if(isset($category_setting['count_sub_cat'])){
								if($key == $category_setting['count_sub_cat']){
									break;
								}
								}
								$child_4level_data=array();
								$child_4level = $this->getCategories($child['category_id']);
								if($child_4level){
									foreach ($child_4level as $c4level) {
										$path_4level = $this->getCategoryPath($c4level['category_id']);

										$child_4level_data[] = array(
											'name'  => $c4level['name'],
											'href'  => $this->url->link('product/category', 'path=' . $path_4level)
										);
									}
								}
								$path=$this->getCategoryPath($child['category_id']);

								$children_data[] = array(
									'child_4level_data'  => $child_4level_data,
									'name'  => $child['name'],
									'href'  => $this->url->link('product/category', 'path=' . $path)
								);
							}

						}
					}

					$path = $this->getCategoryPath($category['category_id']);

					$result_category[]=array(
						'showmore'  		=> $showmore,
						'name'  			=> $category['name'],
						'href'  			=> $this->url->link('product/category', 'path=' . $path),
						'children'  		=> $children_data,
						'thumb'				=> $thumb
					);
				}
			}
		}


		$result_menu_category = array();

		$number_column_sc = (isset($category_setting['number_column_sc'])) ? $category_setting['number_column_sc']:4;
		if (isset($number_column_sc) && ($number_column_sc == 6)) {
			$ac_number = 2;
		} elseif($number_column_sc == 4) {
			$ac_number = 3;
		} elseif($number_column_sc == 3) {
			$ac_number = 4;
		} else {
			$ac_number = 5;
		}
		$type_link_data['result_menu_category'] = array(
			'type' 				=> "category",
			'thumb' 			=> $thumb_menu,
			'thumb_hover' 		=> $thumb_menu_hover,
			'children' 			=> $result_category,
			'subtype' 			=> $category_setting['variant_category'],
			'href' 				=> (trim(isset($link[$this->lang_id]))) ? $link[$this->lang_id] : "javascript:void(0);",
			'name' 				=> (isset($namemenu[$this->lang_id])) ? $namemenu[$this->lang_id]:'',
			'dop_info_vm' 		=> (isset($dop_info_vm[$this->lang_id])) ? $dop_info_vm[$this->lang_id]:'',
			'sticker_parent' 	=> (isset($sticker_parent[$this->lang_id])) ? $sticker_parent[$this->lang_id]:'',
			'spbg' 				=> $data['sticker_parent_bg'],
			'spctext' 			=> $data['spctext'],
			'additional_menu' 	=> $data['additional_menu'],
			'new_blank' 		=> '0',
			'number_column_sc'=> (isset($category_setting['number_column_sc'])) ? $category_setting['number_column_sc']:4,
			'ac_number' 		=> $ac_number,
			'add_html' 			=> (isset($add_html[$this->lang_id])) ? html_entity_decode($add_html[$this->lang_id], ENT_QUOTES, 'UTF-8'):false,
		);
		return $type_link_data['result_menu_category'];

	}
	public function MegaMenuTypeHtml($data){

		$this->load->model('tool/image');
		$result_menu_html = array();
		if($data['image']){
			$thumb_menu = $this->model_tool_image->resize($data['image'], 25, 25);
		} else {
			$thumb_menu = "";
		}
		if($data['image_hover']){
			$thumb_menu_hover = $this->model_tool_image->resize($data['image_hover'], 25, 25);
		} else {
			$thumb_menu_hover = "";
		}
		if(!empty($data['html_setting'])){
			$html_block = json_decode($data['html_setting'], true);
		} else {
			$html_block = '';
		}
		if(!empty($data['namemenu'])){
			$namemenu = json_decode($data['namemenu'], true);
		} else {
			$namemenu = '';
		}
		if(!empty($data['dop_info_vm'])){
			$dop_info_vm = json_decode($data['dop_info_vm'], true);
		} else {
			$dop_info_vm = '';
		}
		if(!empty($data['sticker_parent'])){
			$sticker_parent = json_decode($data['sticker_parent'], true);
		} else {
			$sticker_parent = '';
		}
		if(!empty($data['link'])){
			$link = json_decode($data['link'], true);
		} else {
			$link = '';
		}

		$type_link_data['result_menu_html'] = array(
			'type' 				=> "html",
			'children'			=> true,
			'href' 				=> (trim(isset($link[$this->lang_id]))) ? $link[$this->lang_id] : "javascript:void(0);",
			'name' 				=> $namemenu[$this->lang_id],
			'dop_info_vm' 		=> $dop_info_vm[$this->lang_id],
			'sticker_parent' 	=> (isset($sticker_parent[$this->lang_id])) ? $sticker_parent[$this->lang_id]:'',
			'spbg' 				=> $data['sticker_parent_bg'],
			'spctext' 			=> $data['spctext'],
			'additional_menu' 	=> $data['additional_menu'],
			'new_blank' 		=> (isset($data['new_blank']))?$data['new_blank']:'0',
			'html' 				=> html_entity_decode($html_block[$this->lang_id], ENT_QUOTES, 'UTF-8'),
			'thumb' 			=> $thumb_menu,
			'thumb_hover' 		=> $thumb_menu_hover,
		);
			return $type_link_data['result_menu_html'];
	}

}