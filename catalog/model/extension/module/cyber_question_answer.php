<?php
class ModelExtensionModuleCyberQuestionAnswer extends Model {
public function SaveQuestionAnswer($data) { 
$this->db->query("INSERT INTO ". DB_PREFIX ."question_answer SET name_field = '".$this->db->escape(strip_tags($data['name_field']))."', telephone_field = '".$this->db->escape(strip_tags($data['telephone_field']))."', comment_field = '".$this->db->escape(strip_tags($data['comment_field']))."', email_field = '".$this->db->escape(strip_tags($data['email_field']))."', qa_product_id = '". (int)$data['qa_product_id'] ."', comment_manager = '', date_added = NOW(), date_modified =NOW(), status_id='0'");
 }
	
	public function getQuestionAnswer($product_id, $start = 0, $limit = 5) {
		$sql = "SELECT * FROM `".DB_PREFIX."question_answer` WHERE qa_product_id != '0'";	
		
		if (isset($product_id)) {
			$sql .= " AND qa_product_id = '".(int)$product_id."'";
		}
		
		$sql .= " AND status_id = '1'";
		$sql .= " ORDER BY date_added";	
		$sql .= " DESC";

		
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}
			$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		
		
		$query = $this->db->query($sql);	
		
		return $query->rows;
	}

	public function getTotalQuestionAnswer($product_id) {
		$sql = "SELECT COUNT(*) AS total FROM `".DB_PREFIX."question_answer` WHERE qa_product_id != '0'";
		
		if (isset($product_id)) {
			$sql .= " AND qa_product_id = '".(int)$product_id."'";
		}
		
		$sql .= " AND status_id = '1'";
		
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
}
?>