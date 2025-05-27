<?php
class ControllerCommonCyberMenuvh extends Controller {
	public function index($load_mmenu = false) {
		$data['type_header'] = (!empty($this->config->get('type_header')) ? $this->config->get('type_header') : 1);
		$data['swap_search_dmenu'] = (!empty($this->config->get('swap_search_dmenu')) ? $this->config->get('swap_search_dmenu') : 0);
		$data['deviceType'] = deviceType;
		$data['lang_id'] = $this->config->get('config_language_id');
		$data['desc_info_mob'] = html_entity_decode($this->config->get('language_description_info_mob')[$data['lang_id']]['text'], ENT_QUOTES, 'UTF-8');
		$megamenu_setting = $this->config->get('megamenu_setting');
		$data['lazy_img'] = 'image/catalog/lazyload/lazyload1px.png';
		if($megamenu_setting['status']=='1'){
			$this->load->language('cyberstore/lang');
			$data['text_category'] = $this->language->get('text_category');
			$data['text_info_mob'] = $this->language->get('text_info_mob');
			$data['text_search'] = $this->language->get('text_search');
			$data['text_all_category'] = $this->language->get('text_all_category');
			$this->load->model('extension/module/cyber_nsmenu');
			$data['hmenu_type'] = $megamenu_setting['horizontal_menu_width'];
			$data['type_mobile_menu'] = $megamenu_setting['type_mobile_menu'];
			$data['main_menu_mask'] = $megamenu_setting['main_menu_mask'];
			$data['main_menu_fix_mobile'] = $megamenu_setting['main_menu_fix_mobile'];
			$data['config_main_menu_selection'] = $megamenu_setting['main_menu_selection'];
			$data['config_fixed_panel_top'] = $megamenu_setting['config_fixed_panel_top'];

			$data['items']=array();
			$data['additional']=array();
			$menu_items_cache = $this->cache->get('mmheader.' . (int)$this->config->get('config_language_id').'.'. (int)$this->config->get('config_store_id'));

				if (!empty($menu_items_cache)) {
					$data['items'] = $menu_items_cache;
					foreach($data['items'] as $datamenu){
						if($datamenu['additional_menu']=='additional')	{
							$data['additional'][] = 'additional';
							break;
						}
					}
					$data['megamenu_status']=true;
				} else {
					$config_menu_item = $this->model_extension_module_cyber_nsmenu->getItemsMenu();

					if(!empty($config_menu_item)) {
						$menu_items = $config_menu_item;
					} else {
						$menu_items = array();
					}

					foreach($menu_items as $datamenu){
						if($datamenu['additional_menu']=="additional" && $datamenu['status'] !='0')	{
							$data['additional'][] = 'additional';
						}
						if($datamenu['menu_type']=="link" && $datamenu['status'] !='0')	{
							$data['items'][]=$this->model_extension_module_cyber_nsmenu->MegaMenuTypeLink($datamenu);
						}
						if($datamenu['menu_type']=="information" && $datamenu['status'] !='0')	{
							$data['items'][]=$this->model_extension_module_cyber_nsmenu->MegaMenuTypeInformation($datamenu);
						}
						if($datamenu['menu_type']=="manufacturer" && $datamenu['status'] !='0')	{
							$data['items'][]=$this->model_extension_module_cyber_nsmenu->MegaMenuTypeManufacturer($datamenu);
						}
						if($datamenu['menu_type']=="product" && $datamenu['status'] !='0'){
							$data['items'][]=$this->model_extension_module_cyber_nsmenu->MegaMenuTypeProduct($datamenu);
						}
						if($datamenu['menu_type']=="category" && $datamenu['status'] !='0')	{
							$data['items'][] = $this->model_extension_module_cyber_nsmenu->MegaMenuTypeCategory($datamenu);
						}
						if($datamenu['menu_type']=="html" && $datamenu['status'] !='0')	{
							$data['items'][]=$this->model_extension_module_cyber_nsmenu->MegaMenuTypeHtml($datamenu);
						}
						if($datamenu['menu_type']=="freelink" && $datamenu['status'] !='0')	{
							$data['items'][]=$this->model_extension_module_cyber_nsmenu->MegaMenuTypeFreeLink($datamenu);
						}
					}

					$menu_items_cache = $data['items'];
					$this->cache->set('mmheader.' . (int)$this->config->get('config_language_id') . '.'. (int)$this->config->get('config_store_id'), $menu_items_cache);
					$data['megamenu_status']=true;

				}
			} else {
				$data['megamenu_status']=false;
			}
			if (isset($load_mmenu) && ($load_mmenu== 1)) {
				return $this->load->view('common/mob_menu_left', $data);
			}
			if($data['config_main_menu_selection'] =='0') {
				return $this->load->view('common/menu_h', $data);
			}
			if($data['config_main_menu_selection'] =='1') {
				return $this->load->view('common/menu_v', $data);
			}
	}
	public function load_mob_menu(){
		$this->response->setOutput($this->index($load_mmenu = true));
	}
}
