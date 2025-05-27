<?php
class ModelCyberstoreCyber extends Model {
	public function getManufacturerImage($manufacturer_id) {
		$query = $this->db->query("SELECT m.image FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m.manufacturer_id = '" . (int)$manufacturer_id . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		return $query->row;
	}
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'image'            => $query->row['image'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'tax_class_id'     => $query->row['tax_class_id'],
			);
		} else {
			return false;
		}
	}
	public function multi_implode($glue, $array) {
		$_array=array();
		foreach($array as $val){
			if(isset($val['product_id'])){
				$_array[] = is_array($val['product_id']) ? $this->multi_implode($glue, $val['product_id']) : $val['product_id'];
			} else { 
				$_array[] = is_array($val) ? $this->multi_implode($glue, $val) : $val;
			}
		}
		return implode($glue, $_array);
	}
	public function getPath($productid) {
			$sql = "SELECT p2c.category_id as category_id FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p2s.product_id = ".(int)$productid.")"; 
			$sql .= " WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"; 
			$sql .= " AND p2c.product_id = '" . (int)$productid . "'";
			$sql .= " GROUP BY p2c.category_id";
			$sql .= " LIMIT 1";
			$query = $this->db->query($sql);
			if(isset($query->row['category_id'])){
				return $query->row['category_id'];
			} else {
				return 0;
			}
		
	}
	public function getCategoryPath($category_id){
		$path = '';
		$category = $this->db->query("SELECT c.`category_id`,c.`parent_id` FROM " . DB_PREFIX . "category c WHERE c.`category_id` = " .(int)$category_id."");
		if(isset($category->row['parent_id']) && ($category->row['parent_id'] != 0)){
			$path .= $this->getCategoryPath($category->row['parent_id']) . '_';
		}
		if(isset($category->row['category_id'])){
			$path .= $category->row['category_id'];
		}
 
		return $path;
	}
	public function getPrevNextProduct($productid, $category_id) {
			if ($this->customer->isLogged()) {
			  $customer_group_id = $this->customer->getGroupId();
			} else {
			  $customer_group_id = $this->config->get('config_customer_group_id');
			}
			if (VERSION >= 2.2) {
				$currency = $this->session->data['currency'];
			} else {
				$currency = '';
			}
			$product_data = array();
			$path = $this->getCategoryPath($category_id);
			$sql_next = "SELECT p2c.product_id,p2c.category_id, pd.name,p.image,p.tax_class_id,p.price, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM ". DB_PREFIX . "product_to_category p2c 
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p2s.product_id = p2c.product_id)";
			$sql_next .= " LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = p2c.product_id) ";
			$sql_next .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (pd.product_id = p.product_id) ";
			$sql_next .= " WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$sql_next .= " AND p2c.category_id = '" . (int)$category_id . "'";
			$sql_next .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			$sql_next .= " AND p2c.product_id > '" . (int)$productid . "' ORDER BY p2c.product_id LIMIT 1";
			
			
			$query_next = $this->db->query($sql_next);
			
			if($query_next->row){
				$product_data['next']['name'] = $query_next->row['name'];
				$product_data['next']['image'] = isset($query_next->row['image']) ? $this->model_tool_image->resize($query_next->row['image'], 100, 100) : $this->model_tool_image->resize('no_image.png', 100, 100);
				$product_data['next']['product_id'] = $query_next->row['product_id'];
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$product_data['next']['price'] = $this->currency->format($this->tax->calculate($query_next->row['price'], $query_next->row['tax_class_id'], $this->config->get('config_tax')), $currency);
				} else {
					$product_data['next']['price'] = false;
				}

				if ((float)$query_next->row['special']) {
					$product_data['next']['special'] = $this->currency->format($this->tax->calculate($query_next->row['special'], $query_next->row['tax_class_id'], $this->config->get('config_tax')), $currency);
				} else {
					$product_data['next']['special'] = false;
				}
				$product_data['next']['href']= $this->url->link('product/product','path=' . $path . '&product_id=' . $query_next->row['product_id']);
			}
			
			$sql_prev = "SELECT p2c.product_id,p2c.category_id, pd.name,p.image,p.tax_class_id,p.price, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM ". DB_PREFIX . "product_to_category p2c 
			LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p2s.product_id = p2c.product_id)";
			$sql_prev .= " LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = p2c.product_id) ";
			$sql_prev .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (pd.product_id = p.product_id) ";
			$sql_prev .= " WHERE p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
			$sql_prev .= " AND p2c.category_id = '" . (int)$category_id . "'";
			$sql_prev .= " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
			$sql_prev .= " AND p2c.product_id < '" . (int)$productid . "'  ORDER BY p2c.product_id DESC LIMIT 1";
			
			$query_prev = $this->db->query($sql_prev);
			
			if($query_prev->row){
				$product_data['prev']['name'] = $query_prev->row['name'];
				$product_data['prev']['image'] = isset($query_prev->row['image']) ? $this->model_tool_image->resize($query_prev->row['image'], 100, 100) : $this->model_tool_image->resize('no_image.png', 100, 100);
				$product_data['prev']['product_id'] = $query_prev->row['product_id'];
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$product_data['prev']['price'] = $this->currency->format($this->tax->calculate($query_prev->row['price'], $query_prev->row['tax_class_id'], $this->config->get('config_tax')), $currency);
				} else {
					$product_data['prev']['price'] = false;
				}

				if ((float)$query_prev->row['special']) {
					$product_data['prev']['special'] = $this->currency->format($this->tax->calculate($query_prev->row['special'], $query_prev->row['tax_class_id'], $this->config->get('config_tax')), $currency);
				} else {
					$product_data['prev']['special'] = false;
				}
				$product_data['prev']['href']= $this->url->link('product/product','path=' . $path . '&product_id=' . $query_prev->row['product_id']);
			}
		return $product_data;
	}
	public function getLatest($data = array()) {
		$product_data = $this->cache->get('product.latest_grid.' . (int)$data['filter_category_id'] . '.' . (int)$data['filter_sub_category'] . '.'. (int)$data['limit_max'] . '.' . (int)$data['start'] . '.'. (int)$data['limit'] . '.'. (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'));
		if (!$product_data) {
		$sql = "SELECT * FROM (SELECT p.product_id, p.sort_order, p.model, pd.name, p.quantity, p.price,p.date_added, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";
		
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}
			
			$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}
		}
		$sql .= " GROUP BY p.product_id";
		$sql .= " ORDER BY (p.price>0) DESC,(p.image>'') DESC,(p.quantity>0) DESC,p.date_added DESC, p.sort_order DESC, LCASE(pd.name) DESC";
		$sql .= " LIMIT  0," . (int)$data['limit_max'];
		$sql .= ") p ORDER BY p.date_added DESC, p.sort_order DESC, LCASE(p.name) DESC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 5;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		
		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}
		$this->cache->set('product.latest_grid.' . (int)$data['filter_category_id'] . '.' . (int)$data['filter_sub_category'] . '.'. (int)$data['limit_max'] . '.' . (int)$data['start'] . '.'. (int)$data['limit'] . '.'. (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id'), $product_data);
		}
		return $product_data;
	}
}
?>
