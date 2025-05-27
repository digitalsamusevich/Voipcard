<?php
class ControllerExtensionModuleCyberEditorplus extends Controller {
	public function index() {	
		if (isset($this->request->get['product_id'])) {
			$data['product_id'] = (int)$this->request->get['product_id'];
		} else {
			$data['product_id'] = '';
		}
		if (isset($this->request->get['route'])) {
			$data['route'] = (string)$this->request->get['route'];
		} else {
			$data['route'] = '';
		}
		if (isset($this->request->get['path'])) {
			$seo_parts = explode('_', (string)$this->request->get['path']);
			$data['seo_category_id'] = (int)array_pop($seo_parts);
		} else {
			$data['seo_category_id'] = 0;
		}
		$data['preg1'] = '/\/[a-zA-Zа-яА-ЯёЁ0-9\-\_\%]*[\.]{0,1}[a-zA-Z]{0,}\?|$/';
		$data['preg2'] = '/\/([a-zA-Zа-яА-ЯёЁ0-9\-\_\%]*)[^]{0}[a-zA-Z]{0,}(?:\?|$)/';
		if(isset($this->session->data['user_token'])){
			return $this->load->view('extension/module/editorplus', $data);	
		} else {
			return false;
		}
	}
	
	public function getSettingEditorplus() {	
		if(isset($this->session->data['user_token'])){
			$data['user_token'] = $this->session->data['user_token'];
		} else {
			$data['user_token'] = false;
		}

		$this->registry->set('user', new Cart\User($this->registry));				
		if ($this->user->isLogged()){ 
			$editorusergroupid = $this->user->getGroupId();
			$data['user_logged'] = true;
		} else {
			$data['user_logged'] = false;
		}		
		
	if($this->request->post['route_mod'] == 'product/category'){
		if(isset($this->session->data['user_token'])){
			if($data['user_logged']) { 
				$data['seo_category_id'] = $this->request->post['seo_category_id'];
				$data['user_token'] = $this->session->data['user_token'];
				$this->load->model('catalog/product_quick');
				$this->language->load('module/groupeditor/btngroupeditor');		
				$data['pge_text_admin_setting'] = $this->language->get('pge_text_admin_setting');
				$data['pge_text_description'] = $this->language->get('pge_text_description');
				$data['pge_text_categories'] = $this->language->get('pge_text_categories');
				$data['pge_text_images_product'] = $this->language->get('pge_text_images_product');
				$data['pge_text_images_product_url'] = $this->language->get('pge_text_images_product_url');
				$data['pge_text_price'] = $this->language->get('pge_text_price');
				$data['pge_text_special'] = $this->language->get('pge_text_special');
				$data['pge_text_catalog_prod_edit'] = $this->language->get('pge_text_catalog_prod_edit');
				$data['pge_text_images_product_google'] = $this->language->get('pge_text_images_product_google');
				$data['pge_text_price_setting'] = $this->language->get('pge_text_price_setting');
				$data['pge_text_related'] = $this->language->get('pge_text_related');
				$data['pge_text_code'] = $this->language->get('pge_text_code');
				$data['pge_text_attribute'] = $this->language->get('pge_text_attribute');
				$data['pge_text_option'] = $this->language->get('pge_text_option');
				$data['pge_text_discount_title'] = $this->language->get('pge_text_discount_title');
				
				$data['pge_text_reset'] = $this->language->get('pge_text_reset');
				$data['pge_text_select_all_product'] = $this->language->get('pge_text_select_all_product');
				$data['pge_text_none_editor'] = $this->language->get('pge_text_none_editor');
				$data['pge_text_batch_edit'] = $this->language->get('pge_text_batch_edit');
				$data['pge_text_opt_general_data'] = $this->language->get('pge_text_opt_general_data');
				$data['pge_text_opt_name_product'] = $this->language->get('pge_text_opt_name_product');
				$data['pge_text_opt_decription'] = $this->language->get('pge_text_opt_decription');
				$data['pge_text_opt_meta_title'] = $this->language->get('pge_text_opt_meta_title');
				$data['pge_text_opt_meta_h1'] = $this->language->get('pge_text_opt_meta_h1');
				$data['pge_text_opt_meta_descripton'] = $this->language->get('pge_text_opt_meta_descripton');
				$data['pge_text_opt_meta_keyword'] = $this->language->get('pge_text_opt_meta_keyword');
				$data['pge_text_opt_tag'] = $this->language->get('pge_text_opt_tag');
				$data['pge_text_opt_data'] = $this->language->get('pge_text_opt_data');
				$data['pge_text_opt_price'] = $this->language->get('pge_text_opt_price');
				$data['pge_text_opt_quantity'] = $this->language->get('pge_text_opt_quantity');
				$data['pge_text_opt_minquantity'] = $this->language->get('pge_text_opt_minquantity');
				$data['pge_text_opt_mlt'] = $this->language->get('pge_text_opt_mlt');
				$data['pge_text_opt_code'] = $this->language->get('pge_text_opt_code');
				$data['pge_text_opt_date_status'] = $this->language->get('pge_text_opt_date_status');
				$data['pge_text_opt_stockshipping'] = $this->language->get('pge_text_opt_stockshipping');
				$data['pge_text_opt_weightdimensions'] = $this->language->get('pge_text_opt_weightdimensions');
				$data['pge_text_opt_links'] = $this->language->get('pge_text_opt_links');
				$data['pge_text_opt_manufacturer'] = $this->language->get('pge_text_opt_manufacturer');
				$data['pge_text_opt_category'] = $this->language->get('pge_text_opt_category');
				$data['pge_text_opt_filter'] = $this->language->get('pge_text_opt_filter');
				$data['pge_text_opt_related'] = $this->language->get('pge_text_opt_related');
				$data['pge_text_opt_attribute_options'] = $this->language->get('pge_text_opt_attribute_options');
				$data['pge_text_opt_attribute'] = $this->language->get('pge_text_opt_attribute');
				$data['pge_text_opt_options'] = $this->language->get('pge_text_opt_options');
				$data['pge_text_opt_special_discount'] 	= $this->language->get('pge_text_opt_special_discount');
				$data['pge_text_opt_discount'] = $this->language->get('pge_text_opt_discount');
				$data['pge_text_opt_special'] = $this->language->get('pge_text_opt_special');
				$data['pge_text_opt_url'] = $this->language->get('pge_text_opt_url');
				$data['pge_text_select_checkbox'] = $this->language->get('pge_text_select_checkbox');
				if(!empty($editorusergroupid)){
					$user_groups_view = $this->model_catalog_product_quick->getUserGroupsEditView($editorusergroupid);	
					$data['view_open_description_edit'] = $user_groups_view['description_edit'];
					$data['view_open_category_edit'] = $user_groups_view['category_edit'];
					$data['view_open_image_edit'] = $user_groups_view['image_edit'];
					$data['view_open_image_url_edit'] = $user_groups_view['image_url_edit'];
					$data['view_open_image_google_edit'] = $user_groups_view['image_google_edit'];
					$data['view_open_price_edit'] = $user_groups_view['price_edit'];
					$data['view_open_special_edit'] = $user_groups_view['special_edit'];
					$data['view_open_related_edit'] = $user_groups_view['related_edit'];
					$data['view_open_code_edit'] = $user_groups_view['code_edit'];
					$data['view_open_attribute_edit'] = $user_groups_view['attribute_edit'];
					$data['view_open_option_edit'] = $user_groups_view['option_edit'];
					$data['link_module_edit_admin'] = $user_groups_view['link_module_edit_admin'];
					$data['link_product_admin'] = $user_groups_view['link_product_admin'];
					$data['group_editor'] = $user_groups_view['group_editor'];
				}			
			}
		}
		
		$json = array();
		
		$products_id = $products_url_alias = $json['btn_product'] = array ();
		
		if (isset($this->request->post['prod_id_edit']) && is_array($this->request->post['prod_id_edit'])) {
			foreach ($this->request->post['prod_id_edit'] as $key => $value) {
				$products_id[$key] = (int)$value;
			}
		}
		
		if (isset($this->request->post['url_product_edit']) && is_array($this->request->post['url_product_edit'])) {
			foreach ($this->request->post['url_product_edit'] as $key => $value) {
				$products_url_alias[$key] = $this->db->escape(utf8_strtolower(urldecode($value)));
			}
		}
		
		
		if ($products_url_alias) {
			$query = $this->db->query('SELECT query, LCASE(keyword) AS keyword FROM ' . DB_PREFIX . 'seo_url WHERE keyword IN ("' . implode ('","', $products_url_alias) . '") AND query LIKE "product_id=%" AND language_id = ' . (int)$this->config->get('config_language_id') . '');
			foreach ($query->rows as $result_db) {
				foreach ($products_url_alias as $index=>$keyword) {
					if ($keyword == $result_db['keyword']) {
						$products_id[$index] = (int)str_replace('product_id=', '', $result_db['query']);
						unset ($products_url_alias[$index]);
					}
				}
			}
		}
		
		
		if(!empty($products_id)){
			$json['group_btn'] = $this->load->view('extension/module/editorplus_group', $data);
		} else {
			$json['group_btn'] = array();
		}
		
		foreach ($products_id as $index=>$product_id) {
			$astickers = '';
			$data['product_id'] = $product_id;
			$astickers .= $this->load->view('extension/module/editorplus_category', $data);
					
			$json['btn_product'][$index] = $astickers;
		}
		
		
		header ('Content-type: text/html; charset=utf-8');
		
		echo json_encode($json);
	}
	
		/*Product load*/
		if($this->request->post['route_mod'] == 'product/product'){
			$json = array();
			if(isset($this->session->data['user_token'])){
				if (isset($this->request->post['product_id'])) {
					$data['product_id'] = (int)$this->request->post['product_id'];
				} else {
					$data['product_id'] = '';
				}
					if($data['user_logged']) { 
						$this->load->model('catalog/product_quick');
						$this->language->load('module/groupeditor/btngroupeditor');		
						$data['pge_text_admin_setting'] = $this->language->get('pge_text_admin_setting');
						$data['pge_text_description'] = $this->language->get('pge_text_description');
						$data['pge_text_categories'] = $this->language->get('pge_text_categories');
						$data['pge_text_images_product'] = $this->language->get('pge_text_images_product');
						$data['pge_text_images_product_url'] = $this->language->get('pge_text_images_product_url');
						$data['pge_text_price'] = $this->language->get('pge_text_price');
						$data['pge_text_special'] = $this->language->get('pge_text_special');
						$data['pge_text_catalog_prod_edit'] = $this->language->get('pge_text_catalog_prod_edit');
						$data['pge_text_images_product_google'] = $this->language->get('pge_text_images_product_google');
						$data['pge_text_price_setting'] = $this->language->get('pge_text_price_setting');
						$data['pge_text_related'] = $this->language->get('pge_text_related');
						$data['pge_text_code'] = $this->language->get('pge_text_code');
						$data['pge_text_attribute'] = $this->language->get('pge_text_attribute');
						$data['pge_text_option'] = $this->language->get('pge_text_option');
						$data['pge_text_discount_title'] = $this->language->get('pge_text_discount_title');
						$data['user_token'] = $this->session->data['user_token'];	
						if(!empty($editorusergroupid)){
							$user_groups_view = $this->model_catalog_product_quick->getUserGroupsEditView($editorusergroupid);
							$data['view_open_description_edit'] = $user_groups_view['description_edit'];
							$data['view_open_category_edit'] = $user_groups_view['category_edit'];
							$data['view_open_image_edit'] = $user_groups_view['image_edit'];
							$data['view_open_image_url_edit'] = $user_groups_view['image_url_edit'];
							$data['view_open_image_google_edit'] = $user_groups_view['image_google_edit'];
							$data['view_open_price_edit'] = $user_groups_view['price_edit'];
							$data['view_open_special_edit'] = $user_groups_view['special_edit'];
							$data['view_open_related_edit'] = $user_groups_view['related_edit'];
							$data['view_open_code_edit'] = $user_groups_view['code_edit'];
							$data['view_open_attribute_edit'] = $user_groups_view['attribute_edit'];
							$data['view_open_option_edit'] = $user_groups_view['option_edit'];
							$data['link_module_edit_admin'] = $user_groups_view['link_module_edit_admin'];
							$data['link_product_admin'] = $user_groups_view['link_product_admin'];
						}			
					}
					$json['edit_prod'] = $this->load->view('extension/module/editorplus_product', $data);
					header ('Content-type: text/html; charset=utf-8');	
					echo json_encode($json);						
			}
		}
	}	
}	
	
