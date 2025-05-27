<?php
class ControllerExtensionModuleCyberWallbrand extends Controller {
	public function index($setting) {
		$data['status_slider'] = $setting['status_slider'];
		if($data['status_slider'] !=1){
			$this->document->addScript('catalog/view/theme/cyberstore/js/jquery.scrollpanel-0.5.0.min.js');
			$this->document->addScript('catalog/view/theme/cyberstore/js/jquery.mousewheel-3.1.3.js');
		}
		if(isset($setting['width']) && !empty($setting['width'])){
			$width = $setting['width'];
		} else {
			$width = 150;
		}
		if(isset($setting['height']) && !empty($setting['height'])){
			$height = $setting['height'];
		} else {
			$height = 150;
		}
		static $module = 0;
		$this->load->model('tool/image');
		
		$this->load->model('catalog/manufacturer');
		
		if (isset($setting['wall_manufactures'])) {
			$wall_manufactures = $setting['wall_manufactures'];
		} else {
			$wall_manufactures = '';
		}
		if (!empty($wall_manufactures)){
			foreach ($wall_manufactures as $key => $value) {
				$sort_order_manufactures[$key] = $value['sort_order'];
			} 
			array_multisort($sort_order_manufactures, SORT_ASC, $wall_manufactures);
		}
		$data['manufacturers'] = array();

		if (!empty($wall_manufactures)){
			foreach ($wall_manufactures as $result) {
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($result['manufacturer_id']);
				if($manufacturer_info) {
					$data['manufacturers'][] = array(
						'manufacturer_id' => $manufacturer_info['manufacturer_id'],
						'name'            => $manufacturer_info['name'],
						'href'            => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_info['manufacturer_id']),
						'thumb'           => $this->model_tool_image->resize(($result['image']=='' ? 'no_image.jpg' : $result['image']), $width, $height)
						);
				}
			}
		}
				
		$data['module'] = $module++;
		$data['lang_id'] = $this->config->get('config_language_id');
		$data['heading_title'] = $setting['title_name'];
		return $this->load->view('extension/module/wallbrand', $data);
	} 
}