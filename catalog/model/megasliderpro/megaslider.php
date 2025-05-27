<?php
class ModelMegasliderproMegaslider extends Model {

	public function getocslideshow($banner_id) {
		$select ="SELECT * FROM " . DB_PREFIX . "megasliderpro_image bi LEFT JOIN " . DB_PREFIX . "megasliderpro_image_description bid ON (bi.megasliderpro_image_id  = bid.megasliderpro_image_id) WHERE bi.megasliderpro_id = '" . (int)$banner_id . "' AND bid.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY bi.sort_order ASC";
		$query = $this->db->query($select);

		return $query->rows;
	}

	public function getSettingSlide($megasliderpro_id) {
		$query = "SELECT * FROM " . DB_PREFIX .  "megasliderpro WHERE megasliderpro_id = '" . (int)$megasliderpro_id . "'";
		$result = $this->db->query($query);

		return $result->rows;
	}
}
?>