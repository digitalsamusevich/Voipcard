<?php
class ModelExtensionModuleCyberWallcategory extends Model {
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
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT c.`category_id`,c.`image`, cd.`name` FROM " . DB_PREFIX . "category c 
		LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
		LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
		WHERE c.category_id = '" . (int)$category_id . "' 
		AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

		return $query->row;
	}
	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT c.`category_id`,c.`image`, cd.`name` FROM " . DB_PREFIX . "category c 
		LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) 
		LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) 
		WHERE c.parent_id = '" . (int)$parent_id . "' 
		AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  
		AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}
}