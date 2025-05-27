<?php
class ModelExtensionModuleCyberCallback extends Model {	
	public function addCallback($data) {
		$query = $this->db->query("INSERT INTO " . DB_PREFIX . "callback SET name = '" . $this->db->escape($data['name'])  . "', callback_url = '" . $this->db->escape($data['url_site'])  . "', comment_buyer = '" . $this->db->escape($data['comment_buyer'])  . "', email_buyer = '" . $this->db->escape($data['email_buyer'])  . "', topic_callback_send = '" . $this->db->escape($data['topic_callback_send'])  . "', time_callback_on = '" . $this->db->escape($data['time_callback_on'])  . "', time_callback_off = '" . $this->db->escape($data['time_callback_off'])  . "', telephone = '" . $this->db->escape($data['phone']) . "', date_added = NOW(), date_modified = NOW(), status_id = '0', comment = ''");
		return $this->db->getLastId();	
	}
}
?>
