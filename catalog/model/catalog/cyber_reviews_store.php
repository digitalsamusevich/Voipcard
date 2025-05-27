<?php
class ModelCatalogCyberReviewsStore extends Model {
	public function getReviewsTheme() {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "reviews_store_theme rst
		LEFT JOIN " . DB_PREFIX . "reviews_store_theme_desc rstd ON (rst.reviews_store_theme_id = rstd.reviews_store_theme_id) 
		WHERE rstd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND rst.status = 1 ORDER BY rst.sort_order ASC");

		return $query->rows;
	}
	public function getReviewsThemeRating() {
		$query = $this->db->query("SELECT rstd.theme_text, TRUNCATE(AVG(rsr.rating),1) AS avg_rating FROM " . DB_PREFIX . "reviews_store_theme rst
		LEFT JOIN " . DB_PREFIX . "reviews_store_theme_desc rstd ON (rst.reviews_store_theme_id = rstd.reviews_store_theme_id) 
		LEFT JOIN " . DB_PREFIX . "reviews_store_rating rsr ON (rst.reviews_store_theme_id = rsr.reviews_store_theme_id)
		WHERE rstd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND rst.status = 1 
		GROUP BY rst.reviews_store_theme_id
		ORDER BY rst.sort_order ASC");

		return $query->rows;
	}
	public function addReviewStore($data = array()) {
		$reviews_store_setting = $this->config->get('reviews_store_setting');
		if(isset($reviews_store_setting['automoderation']) && ($reviews_store_setting['automoderation'] == 1)){
			$status = 1;
		} else {
			$status = 0;
		}
	
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "reviews_store SET 
		author = '" . $this->db->escape($data['author']) . "', 
		description = '" . $this->db->escape($data['description']) . "', 
		admin_response = '', 
		status = '" . (int)$status . "',
		date_added = NOW()");

		$reviews_store_id = $this->db->getLastId();
		if (isset($data['rating'])) {
			foreach ($data['rating'] as $reviews_store_theme_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "reviews_store_rating SET 
				reviews_store_theme_id = '" . (int)$reviews_store_theme_id . "',
				reviews_store_id = '" . (int)$reviews_store_id . "',
				rating = '" . (int)$value . "'
				");				
			}
		}
	}
	public function getAllReviews($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "reviews_store WHERE status = '1'";
		
		$sort_data = array(
			'date_added_asc',
			'date_added_desc'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'date_added_asc') {
				$sql .= " ORDER BY date_added ASC";
			} else {
				$sql .= " ORDER BY date_added DESC";
			}
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
	
	public function getAvgRatingCustomer($reviews_store_id) {
		$sql = "SELECT TRUNCATE(AVG(rsr.rating),1) as avg_customer_rating
		FROM " . DB_PREFIX . "reviews_store rs 
		LEFT JOIN " . DB_PREFIX . "reviews_store_rating rsr ON (rs.reviews_store_id = rsr.reviews_store_id) 
		WHERE rs.status = '1' AND rs.reviews_store_id='" . (int)$reviews_store_id . "'";

		$query = $this->db->query($sql);

		return $query->row['avg_customer_rating'];
	}
	public function getSumAvgReviewsStore() {
		$sql = "SELECT TRUNCATE(AVG(rsr.rating),1) as avg_rating 
		FROM " . DB_PREFIX . "reviews_store rs 
		LEFT JOIN " . DB_PREFIX . "reviews_store_rating rsr ON (rs.reviews_store_id = rsr.reviews_store_id) 
		WHERE rs.status = '1'";

		$query = $this->db->query($sql);
		
		if($query->row['avg_rating'] !=''){
			return $query->row['avg_rating'];
		} else {
			return 0;
		}
	}
	
	public function getTotalReviewsStore() {
		$sql = "SELECT COUNT(reviews_store_id) as total_r FROM " . DB_PREFIX . "reviews_store  WHERE `status` = '1'";

		$query = $this->db->query($sql);
			
		return $query->row['total_r'];
	}
	public function getPercentReviewsStore() {
		$sql = "SELECT
			TRUNCATE(SUM(IF(rsr.rating=1,1,0)) * 100 / COUNT(rsr.rating),0) AS star1,
			TRUNCATE(SUM(IF(rsr.rating=2,1,0)) * 100 / COUNT(rsr.rating),0) AS star2,
			TRUNCATE(SUM(IF(rsr.rating=3,1,0)) * 100 / COUNT(rsr.rating),0) AS star3,
			TRUNCATE(SUM(IF(rsr.rating=4,1,0)) * 100 / COUNT(rsr.rating),0) AS star4,
			TRUNCATE(SUM(IF(rsr.rating=5,1,0)) * 100 / COUNT(rsr.rating),0) AS star5
		FROM " . DB_PREFIX . "reviews_store_rating rsr
		LEFT JOIN " . DB_PREFIX . "reviews_store rs ON (rsr.reviews_store_id = rs.reviews_store_id) 
		WHERE rs.status = '1'
		";

		$query = $this->db->query($sql);
		$datapercent = array();
		
		foreach($query->rows as $result){
			$datapercent = array(
				'star1' => $result['star1'] ? $result['star1'] : 0,
				'star2' => $result['star2'] ? $result['star2'] : 0,
				'star3' => $result['star3'] ? $result['star3'] : 0,
				'star4' => $result['star4'] ? $result['star4'] : 0,
				'star5' => $result['star5'] ? $result['star5'] : 0,
				
			);
		}
		return $datapercent;
	}
	public function getLikeDislike($reviews_store_id) {
		$sql = "SELECT rs.like,rs.dislike FROM " . DB_PREFIX . "reviews_store rs  WHERE `status` = '1' AND rs.reviews_store_id='" . (int)$reviews_store_id . "'";

		$query = $this->db->query($sql);
			
		return $query->row;
	}
	public function addLikeDislike($data) {
		$getld = $this->getLikeDislike($data['reviews_store_id']);
		
		$like = $getld['like'] + $data['like'];
			
		$dislike = $getld['dislike'] + $data['dislike'];
		
		$this->db->query("UPDATE " . DB_PREFIX . "reviews_store SET 
			`like` = '" . (int)$like . "',
			`dislike` = '" . (int)$dislike . "'
			 WHERE reviews_store_id = '" . (int)$data['reviews_store_id'] . "'");
		
			
		return $data['reviews_store_id'];
	}
}