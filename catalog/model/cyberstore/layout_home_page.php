<?php
class ModelCyberstoreLayoutHomePage extends Model {		
	public function getLayoutModulesHomePage($layout_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_module WHERE layout_id = '" . (int)$layout_id . "' ORDER BY sort_order");
		
		return $query->rows;
	}
}
?>
