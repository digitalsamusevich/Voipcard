<?php
class ControllerCyberstoreArticle extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('cyberstore/lang');
		$this->load->language('product/product');
		$this->load->language('cyberstore/article');
		$data['lang_id'] = $this->config->get('config_language_id');
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$this->load->model('cyberstore/category');

		if (isset($this->request->get['cs_news_id'])) {

			$cs_news_id = '';

			$parts = explode('_', (string)$this->request->get['cs_news_id']);

			$cs_news_id = (int)array_pop($parts);

			foreach ($parts as $news_id) {
				if (!$cs_news_id) {
					$cs_news_id = $news_id;
				} else {
					$cs_news_id .= '_' . $news_id;
				}

				$category_info = $this->model_cyberstore_category->getCategory($news_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('cyberstore/category', 'cs_news_id=' . $cs_news_id)
					);
				}
			}

			$category_info = $this->model_cyberstore_category->getCategory($cs_news_id);

			if ($category_info) {

				$data['breadcrumbs'][] = array(
					'text' => $category_info['name'],
					'href' => $this->url->link('cyberstore/category', 'cs_news_id=' . $this->request->get['cs_news_id'])
				);
			}
		}

		if (isset($this->request->get['cs_news_article_id'])) {
			$article_id = (int)$this->request->get['cs_news_article_id'];
		} else {
			$article_id = 0;
		}


		$this->load->model('cyberstore/article');

		$info_article = $this->model_cyberstore_article->getArticle($article_id);

		if ($info_article) {

			$data['breadcrumbs'][] = array(
				'text' => $info_article['name'],
				'href' => $this->url->link('cyberstore/article', '&cs_news_article_id=' . $this->request->get['cs_news_article_id'])
			);

			if ($info_article['meta_title']) {
				$this->document->setTitle($info_article['meta_title']);
			} else {
				$this->document->setTitle($info_article['name']);
			}

			$this->document->setDescription($info_article['meta_description']);
			$this->document->setKeywords($info_article['meta_keyword']);
			$this->document->addLink($this->url->link('cyberstore/article', 'cs_news_article_id=' . $this->request->get['cs_news_article_id']), 'canonical');


			if ($info_article['meta_h1']) {
				$data['heading_title'] = $info_article['meta_h1'];
			} else {
				$data['heading_title'] = $info_article['name'];
			}

			$data['text_empty'] = $this->language->get('text_empty');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['text_reviews_title'] = $this->language->get('text_reviews_title');



			$data['article_id'] = (int)$this->request->get['cs_news_article_id'];
			$data['description'] = html_entity_decode($info_article['description'], ENT_QUOTES, 'UTF-8');
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($info_article['date_added']));
			$data['rating'] = $info_article['rating'];
			$data['total_reviews'] = (int)$info_article['reviews'];

			$this->load->model('tool/image');

			$cs_article_width = (!empty($this->config->get('cs_article_width')) ? $this->config->get('cs_article_width') : 400);
			$cs_article_height = (!empty($this->config->get('cs_article_height')) ? $this->config->get('cs_article_height') : 400);
			$data['article_width'] = $cs_article_width;
			$data['article_height'] = $cs_article_height;

			$data['all_rating_reviews_status'] = (!empty($this->config->get('all_rating_reviews_status')) ? $this->config->get('all_rating_reviews_status') : 0);
			$data['article_review_show_like_dislike'] = (!empty($this->config->get('article_review_show_like_dislike')) ? $this->config->get('article_review_show_like_dislike') : 0);


			if ($info_article['image']) {
				$data['thumb'] = $this->model_tool_image->resize($info_article['image'], $cs_article_width, $cs_article_height);
			} else {
				$data['thumb'] = '';
			}

			$data['text_tax'] = $this->language->get('text_tax');
			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['text_home_ns'] = $this->language->get('text_home_ns');


			$data['products'] = array();

			$featured_product_title = json_decode($info_article['featured_product_title'], true);
			$data['mod_title'] = (!empty($featured_product_title[$data['lang_id']]) ? $featured_product_title[$data['lang_id']] : '');

			$data['disable_cart_button'] = (!empty($this->config->get('config_disable_cart_button')) ? 1 : 0);
			$data['disable_fastorder_button'] = (!empty($this->config->get('config_disable_fastorder_button')) ? 1 : 0);
			$data['change_text_cart_button_out_of_stock'] = (!empty($this->config->get('config_change_text_cart_button_out_of_stock')) ? 1 : 0);
			$data['show_stock_status'] = (!empty($this->config->get('config_show_stock_status')) ? 1 : 0);
			$config_disable_cart_button_text = $this->config->get('config_disable_cart_button_text');
			if(!empty($config_disable_cart_button_text[$this->config->get('config_language_id')]['disable_cart_button_text'])){
				$data['disable_cart_button_text'] = $config_disable_cart_button_text[$this->config->get('config_language_id')]['disable_cart_button_text'];
			} else {
				$data['disable_cart_button_text'] = $this->language->get('disable_cart_button_text');
			}
			$data['setting_module'] = $this->config->get('setting_module');
			if(deviceType == 'phone') {
				if(isset($data['setting_module']['hidden_model']) && ($data['setting_module']['hidden_model'] == 1)){
					$data['setting_module']['status_model'] = 0;
				}
				if(isset($data['setting_module']['hidden_desc']) && ($data['setting_module']['hidden_desc'] == 1)){
					$data['setting_module']['status_description'] = 0;
				}
				if(isset($data['setting_module']['hidden_rating']) && ($data['setting_module']['hidden_rating'] == 1)){
					$data['setting_module']['status_rating'] = 0;
				}
				if(isset($data['setting_module']['hidden_actions']) && ($data['setting_module']['hidden_actions'] == 1)){
					$data['setting_module']['status_actions'] = 0;
				}
			}
			$data['config_on_off_featured_quickview'] = $this->config->get('config_on_off_featured_quickview');
			$data['text_reviews_title'] = $this->language->get('text_reviews_title');
			$data['nst_data'] = $this->config->get('nst_data');
			if(isset($data['nst_data']['lazyload_module']) && ($data['nst_data']['lazyload_module'] == 1)){
				$data['lazyload_module'] = true;
				if (isset($data['nst_data']['lazyload_image']) && ($data['nst_data']['lazyload_image'] !='')) {
					$data['lazy_image'] = 'image/' . $data['nst_data']['lazyload_image'];
				} else {
					$data['lazy_image'] = 'image/catalog/lazyload/lazyload.jpg';
				}
			} else {
				$data['lazyload_module'] = false;
			}
			$data['config_on_off_featured_quickview'] = $this->config->get('config_on_off_featured_quickview');
			$data['config_quickview_btn_name'] = $this->config->get('config_quickview_btn_name');
			$data['config_additional_settings_newstore'] = $this->config->get('config_additional_settings_newstore');
			$data['show_special_timer_module'] = (!empty($this->config->get('config_show_special_timer_module')) ? 1 : 0);
			$data['on_off_sticker_special'] = (!empty($this->config->get('on_off_sticker_special')) ? 1 : 0);
			$data['config_change_icon_sticker_special'] = $this->config->get('config_change_icon_sticker_special');
			$data['on_off_sticker_topbestseller'] = (!empty($this->config->get('on_off_sticker_topbestseller')) ? 1 : 0);
			$data['config_limit_order_product_topbestseller'] = $this->config->get('config_limit_order_product_topbestseller');
			$data['config_change_icon_sticker_topbestseller'] = $this->config->get('config_change_icon_sticker_topbestseller');
			$data['on_off_sticker_popular'] = (!empty($this->config->get('on_off_sticker_popular')) ? 1 : 0);
			$data['config_min_quantity_popular'] = $this->config->get('config_min_quantity_popular');
			$data['config_change_icon_sticker_popular'] = $this->config->get('config_change_icon_sticker_popular');
			$data['on_off_sticker_newproduct'] = (!empty($this->config->get('on_off_sticker_newproduct')) ? 1 : 0);
			$data['config_limit_day_newproduct'] = $this->config->get('config_limit_day_newproduct');
			$data['config_change_icon_sticker_newproduct'] = $this->config->get('config_change_icon_sticker_newproduct');
			$data['text_sticker_special'] = $this->config->get('config_change_text_sticker_special');
			$data['text_sticker_newproduct'] = $this->config->get('config_change_text_sticker_newproduct');
			$data['text_sticker_popular'] = $this->config->get('config_change_text_sticker_popular');
			$data['text_sticker_topbestseller'] = $this->config->get('config_change_text_sticker_topbestseller');
			$data['config_text_open_form_send_order'] = $this->config->get('config_text_open_form_send_order');
			$data['icon_open_form_send_order'] = $this->config->get('config_icon_open_form_send_order');
			$data['on_off_percent_discount'] = $this->config->get('on_off_percent_discount');
			$cs_article_fp_width = (!empty($this->config->get('cs_article_fp_width')) ? $this->config->get('cs_article_fp_width') : 200);
			$cs_article_fp_height = (!empty($this->config->get('cs_article_fp_height')) ? $this->config->get('cs_article_fp_height') : 200);
			$data['article_fp_width'] = $cs_article_fp_width;
			$data['article_fp_height'] = $cs_article_fp_height;
			$data['deviceType'] = deviceType;
			$data['text_instock'] = $this->language->get('text_instock');

			$results = $this->model_cyberstore_article->getProductFeatured($this->request->get['cs_news_article_id']);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $cs_article_fp_width, $cs_article_fp_height);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $cs_article_fp_width, $cs_article_fp_height);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				$top_bestsellers = $this->model_catalog_product->getTopSeller($result['product_id']);
				$additional_image_hover = '';
				if(isset($data['setting_module']['status_additional_image_hover']) && ($data['setting_module']['status_additional_image_hover'] == 1)){
					$results_img = $this->model_catalog_product->getProductImages($result['product_id']);
					foreach ($results_img as $key => $result_img) {
						if ($key < 1) {
							$additional_image_hover = $this->model_tool_image->resize($result_img['image'], $cs_article_fp_width, $cs_article_fp_height);
						}
					}
				}
				if ((float)$result['special']) {
					$price2 = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
					$special2 = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
					$skidka = $special2/($price2/100)-100;
				} else {
					$skidka = "";
				}
				$product_quantity = $result['quantity'];
				$stock_status = $result['stock_status'];
				if ((float)$result['special']) {
					$special_date_end = $this->model_catalog_product->getDateEnd($result['product_id']);
				} else {
					$special_date_end = false;
				}
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price_no_format = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$price_no_format = false;
				}

				if ((float)$result['special']) {
					$special_no_format = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$special_no_format = false;
				}

				$data['products'][] = array(
					'product_id'  			=> $result['product_id'],
					'price_no_format' 		=> $price_no_format,
					'special_no_format'		=> $special_no_format,
					'reviews'				=> sprintf((int)$result['reviews']),
					'date_end'				=> $special_date_end,
					'additional_image_hover'=> $additional_image_hover,
					'product_quantity'		=> $product_quantity,
					'stock_status'			=> $stock_status,
					'reviews'				=> sprintf((int)$result['reviews']),
					'skidka'				=> $skidka,
					'model'					=> $result['model'],
					'date_available'		=> $result['date_available'],
					'viewed'				=> $result['viewed'],
					'top_bestsellers'		=> $top_bestsellers['total'],
					'thumb'					=> $image,
					'name'					=> $result['name'],
					'description' 			=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       			=> $price,
					'special'     			=> $special,
					'tax'         			=> $tax,
					'minimum'     			=> $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      			=> $rating,
					'href'       	 		=> $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}


			$this->model_cyberstore_article->updateViewedArticle($this->request->get['cs_news_article_id']);


			$data['text_write'] = $this->language->get('text_write');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['text_note'] = $this->language->get('text_note');
			$data['text_no_article_reviews'] = $this->language->get('text_no_article_reviews');
			$data['btn_add_new_review'] = $this->language->get('btn_add_new_review');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));

			$data['article_review_status'] = (!empty($this->config->get('article_review_status')) ? $this->config->get('article_review_status') : 0);

			// Captcha
			if ($this->config->get('article_captcha_status')) {
				$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
			} else {
				$data['captcha'] = '';
			}

			$data['article_reviews'] = array();

			if ($this->config->get('article_review_guest') || $this->customer->isLogged()) {
				$data['article_review_guest'] = true;
			} else {
				$data['article_review_guest'] = false;
			}

			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['customer_name'] = '';
			}


			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('cyberstore/article', $data));
		} else {

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('cyberstore/article', '&cs_news_article_id=' . $cs_news_article_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
	public function article_review() {
		$this->load->language('cyberstore/article');

		$this->load->model('cyberstore/article');

		$data['text_no_article_reviews'] = $this->language->get('text_no_article_reviews');
		$data['text_admin_reply'] = $this->language->get('text_admin_reply');
		$data['article_review_show_like_dislike'] = (!empty($this->config->get('article_review_show_like_dislike')) ? $this->config->get('article_review_show_like_dislike') : 0);


		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['article_reviews'] = array();

		$review_total = $this->model_cyberstore_article->getTotalReviewsByArticleId($this->request->get['cs_news_article_id']);

		$results = $this->model_cyberstore_article->getReviewsByArticleId($this->request->get['cs_news_article_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['article_reviews'][] = array(
				'article_review_id'	=> $result['article_review_id'],
				'author'			=> $result['author'],
				'text'				=> nl2br($result['text']),
				'admin_reply'		=> $result['admin_reply'],
				'rating'     		=> (int)$result['rating'],
				'like'     			=> (int)$result['like'],
				'dislike'     		=> (int)$result['dislike'],
				'date_added' 		=> date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}


		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link('cyberstore/article/article_review', 'cs_news_article_id=' . $this->request->get['cs_news_article_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		$this->response->setOutput($this->load->view('cyberstore/article_review', $data));
	}
	public function write_review() {
		$this->load->language('cyberstore/article');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			// Captcha
			if ($this->config->get('article_captcha_status')) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('cyberstore/article');

				$this->model_cyberstore_article->addArticleReview($this->request->get['article_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function likeDislike() {
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && !empty($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

			$json = array();

			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				$this->load->model('cyberstore/article');
				$article_review_id = $this->model_cyberstore_article->addLikeDislike($this->request->post);
				$json = $this->model_cyberstore_article->getLikeDislike($article_review_id);
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		} else {
		  $this->response->redirect($this->url->link('error/not_found', '', true));
		}
	}
}
