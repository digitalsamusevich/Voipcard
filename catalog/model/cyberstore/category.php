<?php
class ModelCyberstoreCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "cs_news n

			LEFT JOIN " . DB_PREFIX . "cs_news_description nd ON (n.category_id = nd.category_id)
			LEFT JOIN " . DB_PREFIX . "cs_news_to_store n2s ON (n.category_id = n2s.category_id)

			WHERE n.category_id = '" . (int)$category_id . "'
			AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'
			AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
			AND n.status = '1'");

		return $query->row;
	}

	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cs_news n LEFT JOIN " . DB_PREFIX . "cs_news_description nd ON (n.category_id = nd.category_id) LEFT JOIN " . DB_PREFIX . "cs_news_to_store n2s ON (n.category_id = n2s.category_id) WHERE n.parent_id = '" . (int)$parent_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND n.status = '1' ORDER BY n.sort_order, LCASE(nd.name)");

		return $query->rows;
	}


	public function getTotalArticles($data = array()) {

		$sql = "SELECT COUNT(DISTINCT na.article_id) AS total";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "cs_news_path np LEFT JOIN " . DB_PREFIX . "cs_news_articles_to_category na2c ON (np.category_id = na2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "cs_news_articles_to_category na2c";
			}
			$sql .= " LEFT JOIN " . DB_PREFIX . "cs_news_articles na ON (na2c.article_id = na.article_id)";
		} else {
			$sql .= " FROM " . DB_PREFIX . "cs_news_articles na";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "cs_news_articles_description nad ON (na.article_id = nad.article_id)

		LEFT JOIN " . DB_PREFIX . "cs_news_articles_to_store na2s ON (na.article_id = na2s.article_id) WHERE nad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND na.status = '1' AND na2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND np.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND na2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function getArticles($data = array()) {

		$sql = "SELECT *,
		(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "cs_article_review ar1 WHERE ar1.article_id = na.article_id AND ar1.status = '1' GROUP BY ar1.article_id) AS rating,
		(SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cs_article_review ar2 WHERE ar2.article_id = na.article_id AND ar2.status = '1' GROUP BY ar2.article_id) AS reviews FROM " . DB_PREFIX . "cs_news_articles na";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "cs_news_articles_to_category na2c ON (na.article_id = na2c.article_id)";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "cs_news_articles_description nad ON (na.article_id = nad.article_id)";

		$sql .= " LEFT JOIN " . DB_PREFIX . "cs_news_articles_to_store na2s ON (na.article_id = na2s.article_id) WHERE nad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND na.status = '1' AND na2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		if (!empty($data['filter_category_id'])) {
			$sql .= " AND na2c.category_id = '" . (int)$data['filter_category_id'] . "'";
		}

		if(!empty($data['order'])){
			$sql .= " ORDER BY date_added " . $data['order'];
		} else {
			$sql .= " ORDER BY date_added DESC";
		}

		if (isset($data['start']) || isset($data['limit'])) {

			if (!isset($data['start']) || $data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}


		$query = $this->db->query($sql);

		return $query->rows;
	}

}