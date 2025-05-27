<?php
class ControllerExtensionModuleCyberAutosearch extends Controller {
    private $error = array();

    public function index() {

        $data['css'] = '';

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/autosearch.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/extension/module/autosearch.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/extension/module/autosearch.tpl', $data));
        }
    }
    public function ajaxLiveSearch() {
        $json = array();
        if(!empty($this->request->get['filter_name'])){
            $this->load->model('extension/module/cyber_autosearch');
            $this->load->model('tool/image');
            $this->load->language('product/product');
            $ns_autosearch_data = $this->config->get('ns_autosearch_data');

            $filter_manufacturer = ($ns_autosearch_data['search_manufacturer_on_off']=='1') ? true : false;
            $filter_upc = ($ns_autosearch_data['search_upc_on_off']=='1') ? true : false;
            $filter_sku = ($ns_autosearch_data['search_sku_on_off']=='1') ? true : false;
            $filter_model = ($ns_autosearch_data['search_model_on_off']=='1') ? true : false;
            $filter_tag = ($ns_autosearch_data['search_tag_on_off']=='1') ? true : false;

            $filterdata=array(
                'filter_name' => $this->request->get['filter_name'],
                'filter_manufacturer' => $filter_manufacturer,
                'filter_upc' => $filter_upc,
                'filter_sku' => $filter_sku,
                'filter_model' => $filter_model,
                'filter_tag' => $filter_tag,
                'start' => 0,
                'limit' => 15,
            );

            $results = (array) $this->model_extension_module_cyber_autosearch->ajaxLiveSearch($filterdata);

            $width = 50;
            $height = 50;
            foreach($results as $result){
                if($ns_autosearch_data['image_search_width']!='' && $ns_autosearch_data['image_search_height']!=''){
                    $width = $ns_autosearch_data['image_search_width'];
                    $height = $ns_autosearch_data['image_search_height'];
                }

                if(!empty($result['image'])&&file_exists(DIR_IMAGE .$result['image'])){
                    $image = $this->model_tool_image->resize($result['image'],$width,$height);
                } else{
                    $image = $this->model_tool_image->resize('no_image.png',$width,$height);
                }

                if ($result['quantity'] <= 0) {
                    $stock_result = $result['stock_status'];
                } else {
                    $stock_result = $this->language->get('text_instock');
                }

                $name='';
                $model='';
                $manufacturer='';
                $result['name'] = html_entity_decode ($result['name'], ENT_QUOTES, 'UTF-8');
                $this->request->get['filter_name'] = html_entity_decode ($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8');
                $name = $result['name'];
                $result['model'] = html_entity_decode ($result['model'], ENT_QUOTES, 'UTF-8');
                $model = $result['model'];
                if(!empty($result['manufacturer'])){
                    $result['manufacturer'] = html_entity_decode($result['manufacturer'], ENT_QUOTES, 'UTF-8');
                    $manufacturer=$result['manufacturer'];
                }
                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                } else {
                    $price = false;
                }

                $special = false;
                if(!empty($result['special'])){
                    if ((float)$result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    }
                }

                $rating = false;
                if ($this->config->get('config_review_status')) {
                    if(!empty($result['rating'])){
                        $rating = (int)$result['rating'];
                    }
                }

                $json[] = array(
                    'product_id'    => $result['product_id'],
                    'name'          => $name,
                    'name1'         => $result['name'],
                    'quantity'      => $result['quantity'],
                    'model'         => ($ns_autosearch_data['display_model_on_off'] =='1') ? $model: false ,
                    'stock_status'  => ($ns_autosearch_data['display_stock_on_off'] =='1') ? $stock_result: false ,
                    'image'         => ($ns_autosearch_data['display_image_on_off'] =='1') ? $image: false ,
                    'manufacturer'  => ($ns_autosearch_data['display_manufacturer_on_off'] =='1') ? $manufacturer: false ,
                    'price'         => ($ns_autosearch_data['display_price_on_off'] =='1') ? $price: false ,
                    'special'       => ($ns_autosearch_data['display_price_on_off'] =='1') ? $special: false ,
                    'rating'        => ($ns_autosearch_data['display_rating_on_off'] =='1') ? $rating: false ,
                    'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])

                );

            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
?>