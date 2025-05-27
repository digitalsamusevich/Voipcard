<?php
class ControllerCommonCyberDopMenu extends Controller {
	public function index() {

		$data['type_header'] = (!empty($this->config->get('type_header')) ? $this->config->get('type_header') : 1);
		$data['lazy_img'] = 'image/catalog/lazyload/lazyload1px.png';
		$megamenu_setting = $this->config->get('megamenu_setting');

		if($megamenu_setting['status']=='1'){

			$this->load->language('cyberstore/lang');
			$this->load->model('extension/module/cyber_nsmenu');
			$data['main_menu_mask'] = $megamenu_setting['main_menu_mask'];
			$data['main_menu_fix_mobile'] = $megamenu_setting['main_menu_fix_mobile'];
			$data['lang_id'] = $this->config->get('config_language_id');
			$data['items']=array();
			$data['additional']=array();
			$dopmenu_items_cache = $this->cache->get('dopmmheader.' . (int)$this->config->get('config_language_id').'.'. (int)$this->config->get('config_store_id'));

				if (!empty($dopmenu_items_cache)) {
					$data['items'] = $dopmenu_items_cache;
					foreach($data['items'] as $datamenu){
						if($datamenu['additional_menu']=='additional')	{
							$data['additional'][] = 'additional';
							break;
						}
					}
				} else {
					$config_menu_item = $this->model_extension_module_cyber_nsmenu->getItemsDopMenu();

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

					$dopmenu_items_cache = $data['items'];
					$this->cache->set('dopmmheader.' . (int)$this->config->get('config_language_id') . '.'. (int)$this->config->get('config_store_id'), $dopmenu_items_cache);
					$data['megamenu_status']=true;

				}
			} else {
				$data['megamenu_status']=false;
			}
			return $this->load->view('common/dopmenu', $data);
	}
}
