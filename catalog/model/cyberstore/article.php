<?php
class ModelCyberstoreArticle extends Model {
	public function updateViewedArticle($article_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "cs_news_articles SET viewed = (viewed + 1) WHERE article_id = '" . (int)$article_id . "'");
	}
	public function getProductFeatured($article_id) {
		$this->load->model('catalog/product');
		$product_data = array();

		$query = $this->db->query("SELECT `featured_product` FROM " . DB_PREFIX . "cs_news_articles WHERE article_id = '" . (int)$article_id . "'");

		if(!empty($query->row['featured_product'])){
			$products = json_decode($query->row['featured_product'], true);


			foreach ($products as $product_id) {
				$product_data[] = $this->model_catalog_product->getProduct($product_id);
			}
		}
		return $product_data;
	}
	public function getArticle($article_id) {

		$query = $this->db->query("SELECT *,
		(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "cs_article_review ar1 WHERE ar1.article_id = na.article_id AND ar1.status = '1' GROUP BY ar1.article_id) AS rating,
		(SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cs_article_review ar2 WHERE ar2.article_id = na.article_id AND ar2.status = '1' GROUP BY ar2.article_id) AS reviews
		FROM " . DB_PREFIX . "cs_news_articles na
		LEFT JOIN " . DB_PREFIX . "cs_news_articles_description nad ON (na.article_id = nad.article_id)
		LEFT JOIN " . DB_PREFIX . "cs_news_articles_to_store na2s ON (na.article_id = na2s.article_id)

		WHERE nad.language_id = '" . (int)$this->config->get('config_language_id') . "'
		AND na.status = '1'
		AND na.article_id = '" . (int)$article_id . "'
		AND na2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		return $query->row;
	}
	public function addArticleReview($article_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "cs_article_review SET author = '" . $this->db->escape($data['name']) . "', customer_id = '" . (int)$this->customer->getId() . "', article_id = '" . (int)$article_id . "', text = '" . $this->db->escape($data['text']) . "', rating = '" . (int)$data['rating'] . "', date_added = NOW()");

	}
	public function getReviewsByArticleId($article_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT ar.article_review_id, ar.author, ar.dislike, ar.like, ar.rating, ar.admin_reply, ar.text, na.article_id, nad.name, ar.date_added FROM " . DB_PREFIX . "cs_article_review ar
			LEFT JOIN " . DB_PREFIX . "cs_news_articles na ON (ar.article_id = na.article_id)
			LEFT JOIN " . DB_PREFIX . "cs_news_articles_description nad ON (na.article_id = nad.article_id)
			WHERE na.article_id = '" . (int)$article_id . "'
			AND na.date_added <= NOW()
			AND na.status = '1'
			AND ar.status = '1'
			AND nad.language_id = '" . (int)$this->config->get('config_language_id') . "'
			ORDER BY ar.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReviewsByArticleId($article_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cs_article_review ar
			LEFT JOIN " . DB_PREFIX . "cs_news_articles na ON (ar.article_id = na.article_id)
			LEFT JOIN " . DB_PREFIX . "cs_news_articles_description nad ON (na.article_id = nad.article_id)
			WHERE na.article_id = '" . (int)$article_id . "'
			AND na.date_added <= NOW()
			AND na.status = '1'
			AND ar.status = '1'
			AND nad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row['total'];
	}
	public function getLikeDislike($article_review_id) {
		$sql = "SELECT ar.like,ar.dislike FROM " . DB_PREFIX . "cs_article_review ar WHERE `status` = '1' AND ar.article_review_id='" . (int)$article_review_id . "'";

		$query = $this->db->query($sql);

		return $query->row;
	}
	public function addLikeDislike($data) {
		$getld = $this->getLikeDislike($data['article_review_id']);

		$like = $getld['like'] + $data['like'];

		$dislike = $getld['dislike'] + $data['dislike'];

		$this->db->query("UPDATE " . DB_PREFIX . "cs_article_review SET
			`like` = '" . (int)$like . "',
			`dislike` = '" . (int)$dislike . "'
			 WHERE article_review_id = '" . (int)$data['article_review_id'] . "'");


		return $data['article_review_id'];
	}
}