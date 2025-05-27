<?php
class ControllerExtensionModuleCyberBannerBlocks extends Controller {
	public function index($setting) {
		$data['banner_column'] = isset($setting['banner_column']) ? $setting['banner_column'] : 4;
		$this->load->language('extension/module/banner_blocks');

		$this->load->model('tool/image');

		$data['language_id'] = $this->config->get('config_language_id');

		$results = $setting['banner_item'];

		$data['width'] = 55;
		$data['height'] = 55;

		foreach ($results as $result) {
			if(isset($result['popup'])){
				$result_popup = $result['popup'];
			} else {
				$result_popup = '0';
			}
			$data['blocks'][] = array(
				'image' => $this->model_tool_image->resize($result['image'], 55, 55),
				'title' => $result['title'],
				'description' => $result['description'],
				'link'  => $result['link'],
				'popup'  => $result_popup,
				'sort'  => $result['sort']
			);
		}

		if (!empty($data['blocks'])){
			foreach ($data['blocks'] as $key => $value) {
				$sort[$key] = $value['sort'];
			}
			array_multisort($sort, SORT_ASC, $data['blocks']);
		}

		return $this->load->view('extension/module/banner_blocks', $data);
	}
}