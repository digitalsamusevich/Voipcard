<?php
class ModelExtensionModuleCyberFoundCheaperProduct extends Model {
	public function SaveFoundCheaper($data) {
		$this->db->query("INSERT INTO ".DB_PREFIX."found_cheaper SET name_field = '".$this->db->escape($data['name_field'])."', telephone_field = '".$this->db->escape($data['telephone_field'])."', link_field = '".$this->db->escape($data['link_field'])."', comment_field = '".$this->db->escape($data['comment_field'])."', email_field = '".$this->db->escape($data['email_field'])."', fcp_product_id = '". (int)$data['fcp_product_id'] ."', comment_manager = '', date_added = NOW(), date_modified =NOW(), status_id='0'");
	}
}
?>