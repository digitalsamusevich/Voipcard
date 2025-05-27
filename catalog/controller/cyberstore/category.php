<?php
class ControllerCyberstoreCategory extends Controller {
	public function index() {
		$this->load->language('cyberstore/lang');
		$this->load->language('cyberstore/category');

		$this->load->model('cyberstore/category');

		$this->load->model('tool/image');

		$data['text_home_ns'] = $this->language->get('text_home_ns');
		$data['button_continue'] = $this->language->get('button_continue');

		$data['news_show_subcategories'] = (!empty($this->config->get('news_show_subcategories')) ? $this->config->get('news_show_subcategories') : 0);
		$data['news_show_subcategories_image'] = (!empty($this->config->get('news_show_subcategories_image')) ? $this->config->get('news_show_subcategories_image') : 0);

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('cs_article_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['cs_news_id'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$cs_news_id = '';

			$parts = explode('_', (string)$this->request->get['cs_news_id']);

			$cs_news_id = (int)array_pop($parts);

			foreach ($parts as $news_id) {
				if (!$cs_news_id) {
					$cs_news_id = (int)$news_id;
				} else {
					$cs_news_id .= '_' . (int)$news_id;
				}

				$category_info = $this->model_cyberstore_category->getCategory($news_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('cyberstore/category', 'cs_news_id=' . $cs_news_id . $url)
					);
				}
			}
		} else {
			$cs_news_id = 0;
		}
		$cyber_news_status = $this->config->get('cyber_news_status');
		$category_info = $this->model_cyberstore_category->getCategory($cs_news_id);


		if(isset($cyber_news_status) && ($cyber_news_status == 1) && $category_info){

			if ($category_info['meta_title']) {
				$this->document->setTitle($category_info['meta_title']);
			} else {
				$this->document->setTitle($category_info['name']);
			}

			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			if ($category_info['meta_h1']) {
				$data['heading_title'] = $category_info['meta_h1'];
			} else {
				$data['heading_title'] = $category_info['name'];
			}
			$data['all_rating_reviews_status'] = (!empty($this->config->get('all_rating_reviews_status')) ? $this->config->get('all_rating_reviews_status') : 0);
			$data['article_review_status'] = (!empty($this->config->get('article_review_status')) ? $this->config->get('article_review_status') : 0);
			$data['text_reviews_title'] = $this->language->get('text_reviews_title');

			$data['text_refine'] = $this->language->get('text_refine');
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');

			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('cyberstore/category', 'cs_news_id=' . $this->request->get['cs_news_id'])
			);

			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
				//$this->document->setOgImage($data['thumb']);
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');


			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			$results = $this->model_cyberstore_category->getCategories($cs_news_id);

			$data['subcat_width'] = $this->config->get('news_subcat_width') ? $this->config->get('news_subcat_width') : 60;
			$data['subcat_height'] = $this->config->get('news_subcat_height') ? $this->config->get('news_subcat_height') : 60;

			foreach ($results as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);

				$data['categories'][] = array(
					'thumb' => $this->model_tool_image->resize(($result['image']=='' ? 'no_image.png' : $result['image']), $this->config->get('news_subcat_width') ? $this->config->get('news_subcat_width') : 60 , $this->config->get('news_subcat_height') ? $this->config->get('news_subcat_height') : 60),
					'name' => $result['name'] . ($this->config->get('articles_count') ? ' (' . $this->model_cyberstore_category->getTotalArticles($filter_data) . ')' : ''),
					'href' => $this->url->link('cyberstore/category', 'cs_news_id=' . $this->request->get['cs_news_id'] . '_' . $result['category_id'] . $url)
				);
			}

			$data['articles'] = array();

			$filter_data = array(
				'filter_category_id' => $cs_news_id,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$product_total = $this->model_cyberstore_category->getTotalArticles($filter_data);

			$results = $this->model_cyberstore_category->getArticles($filter_data);

			$data['article_list_width'] = $this->config->get('article_list_width') ? $this->config->get('article_list_width') : 100;
			$data['article_list_height'] = $this->config->get('article_list_height') ? $this->config->get('article_list_height') : 100;

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('article_list_width') ? $this->config->get('article_list_width') : 100 , $this->config->get('article_list_height') ? $this->config->get('article_list_height') : 100);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('article_list_width') ? $this->config->get('article_list_width') : 100 , $this->config->get('article_list_height') ? $this->config->get('article_list_height') : 100);
				}

				$data['articles'][] = array(
					'rating' 	  	=> $result['rating'],
					'reviews' 	  	=> (int)$result['reviews'],
					'article_id' 	=> $result['article_id'],
					'viewed'  	    => $result['viewed'],
					'image'			=> $image,
					'name'			=> $result['name'],
					'date_added'	=> $result['date_added'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('article_description_length')) . '..',
					'href'        => $this->url->link('cyberstore/article', 'cs_news_id=' . $this->request->get['cs_news_id'] . '&cs_news_article_id=' . $result['article_id'] . $url)

				);
			}

			$url = '';

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_date_added_desc'),
				'value' => 'date_added-DESC',
				'href'  => $this->url->link('cyberstore/category', 'cs_news_id=' . $this->request->get['cs_news_id'] . '&sort=date_added&order=DESC' . $url)
			);
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_date_added_asc'),
				'value' => 'date_added-ASC',
				'href'  => $this->url->link('cyberstore/category', 'cs_news_id=' . $this->request->get['cs_news_id'] . '&sort=date_added&order=ASC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('cs_article_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('cyberstore/category', 'cs_news_id=' . $this->request->get['cs_news_id'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('cyberstore/category', 'cs_news_id=' . $this->request->get['cs_news_id'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
			if ($page == 1) {
			    $this->document->addLink($this->url->link('cyberstore/category', 'cs_news_id=' . $category_info['category_id'], true), 'canonical');
			} elseif ($page == 2) {
			    $this->document->addLink($this->url->link('cyberstore/category', 'cs_news_id=' . $category_info['category_id'], true), 'prev');
			} else {
			    $this->document->addLink($this->url->link('cyberstore/category', 'cs_news_id=' . $category_info['category_id'] . '&page='. ($page - 1), true), 'prev');
			}

			if ($limit && ceil($product_total / $limit) > $page) {
			    $this->document->addLink($this->url->link('cyberstore/category', 'cs_news_id=' . $category_info['category_id'] . '&page='. ($page + 1), true), 'next');
			}

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;
			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('cyberstore/category', $data));
		} else {
			$url = '';

			if (isset($this->request->get['cs_news_id'])) {
				$url .= '&cs_news_id=' . $this->request->get['cs_news_id'];
			}


			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('cyberstore/category', $url)
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
}
