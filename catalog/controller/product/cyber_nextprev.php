<?php
class ControllerProductCyberNextprev extends Controller {
	public function index() {
		$ns_show_nextprev_prod = (!empty($this->config->get('ns_show_nextprev_prod')) ? 1 : 0);
		if(isset($ns_show_nextprev_prod) && ($ns_show_nextprev_prod == 1)){
			$this->load->model('cyberstore/cyber');
			if (isset($this->request->get['product_id'])) {
				$product_id = $this->request->get['product_id'];
			} else {
				$product_id = false;
			}
			if (isset($this->request->get['path'])) {
				$path = false;
				foreach (explode('_', $this->request->get['path']) as $path_id) {
					if (!$path) {
						$path = $path_id;
					} else {
						$path = $path_id;
					}
					
				}
			}
			else {
				$path = $this->model_cyberstore_cyber->getPath($product_id);
			}
			
			$this->load->model('cyberstore/cyber');
			$data['products'] = array();		
			$data['products'] = $this->model_cyberstore_cyber->getPrevNextProduct($product_id,$path);
			
			
			return $this->load->view('product/nextprev', $data);
		}	
	}	
}
