<?php
class ControllerExtensionModuleCyberWallcategory extends Controller {
	public function index($setting) {
		$data['status_slider'] = $setting['status_slider'];
		if($data['status_slider'] !=1){
			$this->document->addStyle('catalog/view/theme/cyberstore/stylesheet/jquery.mCustomScrollbar.min.css');
			$this->document->addScript('catalog/view/theme/cyberstore/js/jquery.mCustomScrollbar.min.js');
			$this->document->addScript('catalog/view/theme/cyberstore/js/jquery.mousewheel-3.1.3.js');
		}
		if(isset($setting['width']) && !empty($setting['width'])){
			$width = $setting['width'];
			$data['width'] = $setting['width'];
		} else {
			$width = 150;
			$data['width'] = 150;
		}
		if(isset($setting['height']) && !empty($setting['height'])){
			$height = $setting['height'];
			$data['height'] = $setting['height'];
		} else {
			$height = 150;
			$data['height'] = 150;
		}
		$data['lazy_img'] = 'image/catalog/lazyload/lazyload1px.png';
		static $module = 0;
		$this->load->model('tool/image');
		$this->load->model('extension/module/cyber_wallcategory');
		$limit_sub_category = $setting['limit'];

		if (isset($setting['wall_category'])) {
			$categories = $setting['wall_category'];
		} else {
			$categories = array();
		}
		$data['categories'] = array();
		if (!empty($categories)){
			foreach ($categories as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
			array_multisort($sort_order, SORT_ASC, $categories);

		foreach($categories as $category){
            $category_info = $this->model_extension_module_cyber_wallcategory->getCategory($category['category']);

		if(!empty($category_info)){
			$data['subcategories'] = array();
			$subcategories = $this->model_extension_module_cyber_wallcategory->getCategories($category['category']);
			if($subcategories){
				$subcategories = array_slice($subcategories, 0, $limit_sub_category);

				foreach($subcategories as $subcategory){
					$path = $this->model_extension_module_cyber_wallcategory->getCategoryPath($subcategory['category_id']);
					$data['subcategories'][] = array(
						'category_id' 	=> $subcategory['category_id'],
						'name'        	=> $subcategory['name'],
						'href'  	    => $this->url->link('product/category', 'path=' . $path),
					);
				}
			}
			if ($category['image']) {
				$image_category = $this->model_tool_image->resize($category['image'], $width, $height);
			} else {
				$image_category = $this->model_tool_image->resize('placeholder.png', $width, $height);
			}
			$path = $this->model_extension_module_cyber_wallcategory->getCategoryPath($category['category']);
			$data['categories'][] = array(
				'subcategories' => $data['subcategories'],
				'category_id' => $category_info['category_id'],
				'name' 		  => $category_info['name'],
				'href'  	  => $this->url->link('product/category', 'path=' . $path),
				'image' 	  => $image_category,
			);
		}
        }
		}
		$data['module'] = $module++;
		$data['limit_sub_category'] = $limit_sub_category;
		$data['limit3lv'] = $setting['limit3lv'];
		$data['status_sub_image'] = $setting['status_sub_image'];
		$data['lang_id'] = $this->config->get('config_language_id');
		$data['heading_title'] = $setting['title_name'];
		return $this->load->view('extension/module/wallcategory', $data);
	}

	public function loadSubCateogry() {
		if (isset($this->request->post['category_id'])) {
			$html = '';

			$wall_cat_cache = $this->cache->get('wallcat.'. $this->request->post['category_id'] . $this->request->post['limit_sub_category'] . $this->request->post['limit3lv'] . '.' . (int)$this->config->get('config_language_id').'.'. (int)$this->config->get('config_store_id'));
			if (!empty($wall_cat_cache)) {
				$html = $wall_cat_cache;
			} else {

			$this->load->model('tool/image');
			$this->load->model('extension/module/cyber_wallcategory');
			$data['subcategories'] = array();
			$subcategories = $this->model_extension_module_cyber_wallcategory->getCategories($this->request->post['category_id']);

			if($subcategories){
				$subcategories = array_slice($subcategories, 0, $this->request->post['limit_sub_category']);
				$num_columns = 4;
				$limit3lv = $this->request->post['limit3lv'];
				foreach (array_chunk($subcategories, $num_columns) as $children) {
					$html .= '<div class="row-flex wall-scbox sub-category-box'. $this->request->post['category_id'] .'">';
					foreach($children as $subcategory){
						if ($subcategory['image']) {
							$imgsc = $this->model_tool_image->resize($subcategory['image'], 35, 35);
						} else {
							$imgsc = $this->model_tool_image->resize('placeholder.png', 35, 35);
						}
						$path_sub = $this->model_extension_module_cyber_wallcategory->getCategoryPath($subcategory['category_id']);
						$href_sub = $this->url->link('product/category', 'path=' . $path_sub);
						$html .= '<div class="col-xs-12 col-sm-6 col-md-3">';
						$html .= '<div class="sub-categoty-name"><img src="'. $imgsc .'" alt="'. $subcategory['name'].'"><a href="'. $href_sub .'">'. $subcategory['name'] .'</a></div>';
						$child_3level = $this->model_extension_module_cyber_wallcategory->getCategories($subcategory['category_id']);
							if($child_3level){
							$child_3level = array_slice($child_3level, 0, $limit3lv);
							$html .= '<ul class="list-unstyled box-c3level">';
								foreach ($child_3level as $c3level) {
									$path_3level = $this->model_extension_module_cyber_wallcategory->getCategoryPath($c3level['category_id']);
									$href_3level = $this->url->link('product/category', 'path=' . $path_3level);
									$html .= '<li class="c3level">';
									$html .= '<a href="'. $href_3level .'">'. $c3level['name'] .'</a>';
									$html .= '</li>';
								}
							$html .= '</ul>';
							}
						$html .= '</div>';
					}
					$html .= '</div>';
				}
				}


			$this->cache->set('wallcat.'. $this->request->post['category_id'] . $this->request->post['limit_sub_category'] . $this->request->post['limit3lv'] . '.' . (int)$this->config->get('config_language_id').'.'. (int)$this->config->get('config_store_id'), $html);

			}



		}
		$this->response->setOutput($html);
	}

}