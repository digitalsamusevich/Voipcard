<?php
class ControllerExtensionModuleCyberReviewsStore extends Controller {
	public function getNextPage() {
		if (isset($this->request->post['setting'])) {
			$setting = unserialize(base64_decode($this->request->post['setting']));
		}
		$result_html = $this->index($setting);


		$this->response->setOutput($result_html);
	}
	public function index($setting) {
		$this->load->language('product/cyber_reviews_store');
		$data['text_review_guest'] = sprintf($this->language->get('text_review_guest'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));

		$reviews_store_setting = $this->config->get('reviews_store_setting');
		if ((isset($reviews_store_setting['review_guest']) && ($reviews_store_setting['review_guest'] == 1)) || $this->customer->isLogged()) {
			$data['review_guest'] = true;
		} else {
			$data['review_guest'] = false;
		}

		if(isset($reviews_store_setting['status']) && ($reviews_store_setting['status'] == 1)){
		static $module = 0;
		$this->load->language('cyberstore/lang');
		$data['text_showmore'] = $this->language->get('text_showmore');
		$this->load->model('catalog/cyber_reviews_store');
		$data['reviews_theme_rating'] = $this->model_catalog_cyber_reviews_store->getReviewsThemeRating();
		$this->document->addStyle('catalog/view/theme/cyberstore/stylesheet/popup-reviews-store/stylers.css');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_sum_reviews'] = $this->language->get('text_sum_reviews');
		$data['btn_write_review'] = $this->language->get('btn_write_review');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['btn_all_review'] = $this->language->get('btn_all_review');
		$data['reviews_store_setting'] = $this->config->get('reviews_store_setting');
		$data['rating_store'] = $this->model_catalog_cyber_reviews_store->getSumAvgReviewsStore();
		$data['total_rs'] = $this->model_catalog_cyber_reviews_store->getTotalReviewsStore();
		$data['percent_rating'] = $this->model_catalog_cyber_reviews_store->getPercentReviewsStore();
		if(isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
		$data['status_showmore'] = isset($setting['status_showmore']) ? $setting['status_showmore'] : 0;
		$limit_max = isset($setting['limit_max']) ? $setting['limit_max'] : 12;
		if(deviceType == 'phone') {
			$setting['limit'] = $setting['limit_mob'];
		}
		if(deviceType == 'tablet') {
			$setting['limit'] = $setting['limit_tablet'];
		}
		if(deviceType == 'computer') {
			$setting['limit'] = $setting['limit'];
		}
		$filter_data = array(
			'start' => ($page - 1) * $setting['limit'],
			'limit' => $setting['limit'],
			'limit_max' => $limit_max
		);
		$reviews_store_total = $this->model_catalog_cyber_reviews_store->getTotalReviewsStore();
		if ($reviews_store_total > $limit_max) {
			$reviews_store_total = $limit_max;
		}
		$results = $this->model_catalog_cyber_reviews_store->getAllReviews($filter_data);
		$data['reviews_store'] = array();
		foreach ($results as $result) {
			$data['reviews_store'][] = array(
				'reviews_store_id'  	=> $result['reviews_store_id'],
				'admin_response'       	=> $result['admin_response'],
				'author'        		=> $result['author'],
				'like'   				=> $result['like'],
				'dislike'   			=> $result['dislike'],
				'avg_customer_rating'   => $this->model_catalog_cyber_reviews_store->getAvgRatingCustomer($result['reviews_store_id']),
				'description' 			=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 230) . '..',
				'admin_response' 			=> utf8_substr(strip_tags(html_entity_decode($result['admin_response'], ENT_QUOTES, 'UTF-8')), 0, 230) . '',
				'date_added' 			=> $result['date_added']

			);
		}
		$data['all_review_link'] = $this->url->link('product/cyber_reviews_store');

		$data['last_page'] = ceil($reviews_store_total / $setting['limit']);

		$data['nextPage'] = false;
		if ($page == 1) {
			if ($page == $data['last_page']) {
				$data['nextPage'] = false;
			} elseif($data['last_page'] == '0') {
				$data['nextPage'] = false;
			} else {
				$data['nextPage'] = $page + 1;
			}
		} elseif ($page == $data['last_page']) {
			$data['nextPage'] = false;
		}  else {
			$data['nextPage'] = $page + 1;
		}

		if(isset($this->request->post['module'])) {
			$data['module'] = $this->request->post['module'];
		} else {
			$data['module'] = $module++;
		}

		$setting['name'] = '';
		$data['setting'] = base64_encode(serialize($setting));
		return $this->load->view('extension/module/reviews_store', $data);
	}
	}
}