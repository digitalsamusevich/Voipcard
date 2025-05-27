<?php
class ControllerExtensionModuleCyberReviewscustomer extends Controller {
	public function getNextPage() {
		if (isset($this->request->post['setting'])) {
			$setting = unserialize(base64_decode($this->request->post['setting']));
		}
		$result_html = $this->index($setting);


		$this->response->setOutput($result_html);
	}
    public function index($setting) {
		$data['nst_data'] = $this->config->get('nst_data');
		if(isset($data['nst_data']['lazyload_module']) && ($data['nst_data']['lazyload_module'] == 1)){
			$data['lazyload_module'] = true;
			if (isset($data['nst_data']['lazyload_image']) && ($data['nst_data']['lazyload_image'] !='')) {
				$data['lazy_image'] = 'image/' . $data['nst_data']['lazyload_image'];
			} else {
				$data['lazy_image'] = 'image/catalog/lazyload/lazyload1px.jpg';
			}
		} else {
			$data['lazyload_module'] = false;
		}

		if(isset($setting['reviewscustomer'])){
		$setting = $setting['reviewscustomer'];
		}
		static $module = 0;
		if(deviceType == 'phone') {
			$setting['limit'] = $setting['limit_mob'];
		}
		if(deviceType == 'tablet') {
			$setting['limit'] = $setting['limit_tablet'];
		}
		if(deviceType == 'computer') {
			$setting['limit'] = $setting['limit'];
		}
        $this->language->load('extension/module/cyber_reviewscustomer');
		$this->load->language('cyberstore/lang');
		$data['text_showmore'] = $this->language->get('text_showmore');
        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $this->load->model('catalog/cyber_reviewscustomer');

        $data['module'] = 'reviews';

        $data['module_header'] = isset($setting['module_header'][$this->config->get('config_language_id')]) ? $setting['module_header'][$this->config->get('config_language_id')] : '';

        $data['reviews'] = array();

        if (!$setting['limit']) {
			$setting['limit'] = 3;
		}
		$data['status_showmore'] = isset($setting['status_showmore']) ? $setting['status_showmore'] : 0;
		$limit_max = isset($setting['limit_max']) ? $setting['limit_max'] : 12;


        if (isset($setting['category_sensitive']) && !empty($this->request->get['path'])){
            $categories = explode('_', $this->request->get['path']);
            $category_id = (int) array_pop($categories);
        } else {
            $category_id = 0;
        }
		if(isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
		$filter_data = array(
			'category_id' => $category_id,
			'start' => ($page - 1) * $setting['limit'],
			'limit' => $setting['limit'],
			'limit_max' => $limit_max
		);

		$reviews_total = $this->model_catalog_cyber_reviewscustomer->getTotalReviews($filter_data);
		if ($reviews_total > $limit_max) {
			$reviews_total = $limit_max;
		}
        if ($setting['order_type'] == 'last') {
            $results = $this->model_catalog_cyber_reviewscustomer->getLatestCustomerReviews($filter_data);
        } else {
            $results = $this->model_catalog_cyber_reviewscustomer->getRandomCustomerReviews($filter_data);
        }

        foreach ($results as $result) {
					if ($result['image']) {
						$thumb = $this->model_tool_image->resize($result['image'], 75, 75);
					} else {
						$thumb = $this->model_tool_image->resize('placeholder.png', 75, 75);
					}

            $data['reviews'][] = array(
					 'product_id'  => $result['product_id'],
                'prod_thumb'  => $thumb,
                'prod_name'   => $result['name'],
                'review_id'   => $result['review_id'],
                'rating'      => $result['rating'],
					 'description' => utf8_substr(strip_tags(html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8')), 0, 200),
                'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                'author'      => $result['author'],
                'prod_href'   => $this->url->link('product/product', 'product_id=' . $result['product_id'])
            );
        }

        $data['text_all_reviews'] = $this->language->get('text_all_reviews');
		$data['link_all_reviews'] = $this->url->link('product/cyber_reviewscustomer');
		$data['show_all_button'] = '';
		if(isset($setting['show_all_button'])) {
			$data['show_all_button'] = $setting['show_all_button'];
		}
		$data['last_page'] = ceil($reviews_total / $setting['limit']);

		$data['nextPage'] = false;
		if ($page == 1) {
			if ($page == $data['last_page']) {
				$data['nextPage'] = false;
			} else {
				$data['nextPage'] = $page + 1;
			}
		} elseif ($page == $data['last_page']) {
			$data['nextPage'] = false;
		} else {
			$data['nextPage'] = $page +1;
		}
		if(isset($this->request->post['module'])) {
			$data['module'] = $this->request->post['module'];
		} else {
			$data['module'] = $module++;
		}

		$setting['module_header'] = '';
		$setting['name'] = '';
		$setting['order_type'] = $setting['order_type'];
		$data['setting'] = base64_encode(serialize($setting));

		if($data['reviews']){
			return $this->load->view('extension/module/reviewscustomer', $data);
		}
    }
}