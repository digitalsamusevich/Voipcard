<?php
class ModelExtensionShippingdeliverydelauto extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/deliverydelauto');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_deliverydelauto_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('shipping_deliverydelauto_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['deliverydelauto'] = array(
				'code'         => 'deliverydelauto.deliverydelauto',
				'title'        => $this->language->get('text_description'),
				'cost'         => 0.00,
				'tax_class_id' => 0,
				'text'         => ''
			);

			$method_data = array(
				'code'       => 'deliverydelauto',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_deliverydelauto_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}