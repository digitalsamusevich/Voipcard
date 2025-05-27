<?php
class ModelCatalogCyberReviewscustomer extends Model
{
    public function getTotalReviews($data = array()) {
		$category_id = $data['category_id'];
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) ";
		$sql .= "LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) ";
		if($category_id !='0'){
			$sql .= "LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) ";
		}
		$sql .= "WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ";
		$sql .= "AND p.date_available <= NOW() ";
		if($category_id !='0'){
			$sql .= "AND p2c.category_id = '" . (int)$category_id . "'";
		}
		$sql .= "AND p.status = '1' AND r.status = '1'";
		$query = $this->db->query($sql);
        return $query->row['total'];
	}
	public function getLatestCustomerReviews($data = array()) {
		$limit = $data['limit'];
		$category_id = $data['category_id'];
       if($category_id !='0'){
		    $sql = "SELECT DISTINCT * FROM (SELECT DISTINCT r.*, pd.name, p.price, p.image  FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p2s.store_id = '0' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "'";
			$sql .= " ORDER BY date_added DESC";
			$sql .= "  LIMIT 0,". (int)$data['limit_max'] ."";
			$sql .= ")";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 5;
				}

				$sql .= " a LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
		} else {
			$sql = "SELECT DISTINCT * FROM (SELECT DISTINCT r.*, pd.name, p.price, p.image  FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p2s.store_id = '0' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			$sql .= " ORDER BY date_added DESC";
			$sql .= "  LIMIT 0,". (int)$data['limit_max'] ."";
			$sql .= ")";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 5;
				}

				$sql .= " a LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
		
		}
		return $query->rows;
    }

    public function getRandomCustomerReviews($data = array()) {
		$limit = $data['limit'];
		$category_id = $data['category_id'];
        if($category_id !='0'){
			$sql = "SELECT DISTINCT * FROM (SELECT DISTINCT r.*, pd.name, p.price, p.image  FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)  WHERE p2s.store_id = '0' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "'";
			$sql .= " ORDER BY RAND()";
			$sql .= "  LIMIT 0,". (int)$data['limit_max'] ."";
			$sql .= ")";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 5;
				}

				$sql .= " a LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
		} else {
			$sql = "SELECT DISTINCT * FROM (SELECT DISTINCT r.*, pd.name, p.price, p.image  FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p2s.store_id = '0' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			$sql .= " ORDER BY RAND()";
			$sql .= "  LIMIT 0,". (int)$data['limit_max'] ."";
			$sql .= ")";
			
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}

				if ($data['limit'] < 1) {
					$data['limit'] = 5;
				}

				$sql .= " a LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			$query = $this->db->query($sql);
		}
		return $query->rows;
    }
	
	 public function getAllCustomerReviews($start = 0, $limit = 12) {
        $query = $this->db->query("SELECT r.review_id, r.author, r.purchased, r.plus, r.minus, r.admin_reply, r.rating, r.text, p.product_id, pd.name, p.price, p.image, r.date_added FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY r.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

    public function getTotalCustomerReviews() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p.date_available <= NOW() AND p.status = '1' AND r.status = '1'");

        return $query->row['total'];
    }
}
?>