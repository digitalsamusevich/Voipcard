<?php
class ModelExtensionModuleCyberNewfastorder extends Model {
	public function addOrder($data, $product_options) {
		if($data['config_tax'] =='1'){
			$tax_rates_f_p = $this->tax->getRates($data['price_no_tax'], $data['tax_class_id_total']);
		} else {
			$tax_rates_f_p = array();
		}

		$this_product = $this->db->query("SELECT * FROM ". DB_PREFIX ."product WHERE product_id = '". (int)$data['this_prod_id'] ."'");
		$this_product_info = $this_product->row;
		if($this_product_info['subtract'] =='1'){
			$quantity_new = $this_product_info['quantity'] - $data['quantity'];
			$this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '". (int)$quantity_new ."' WHERE product_id = '". (int)$data['this_prod_id'] ."'");
		}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET
				invoice_prefix 			= 'fastorder-',
				store_id 				= '" . (int)$data['store_id'] . "',
				store_name 				= '" . $this->db->escape($data['store_name']) . "',
				customer_id 			= '" . (int)$data['customer_id'] . "',
				customer_group_id 		= '" . (int)$data['customer_group_id'] . "',
				firstname 				= '" . $this->db->escape($data['name_fastorder']) . "',
				email 					= '" .  $this->db->escape($data['email_buyer']) . "',
				language_id 			= '" . (int)$data['language_id'] . "',
				currency_code 			= '" .  $this->db->escape($data['currency_code']) . "',
				currency_value 			= '" .  $this->db->escape($data['currency_value']) . "',
				currency_id 			= '" . (int)$data['currency_id'] . "',
				payment_method 			= '" . $this->db->escape($data['payment_title']) . "',
				payment_code 			= '" . $this->db->escape($data['payment_code_quickorder']) . "',
				shipping_method 		= '" . $this->db->escape($data['shipping_title']) . "',
				shipping_code 			= '" . $this->db->escape($data['shipping_code_quickorder']) . "',
				store_url 				= '" . $this->db->escape($data['store_url']) . "',
				telephone 				= '" . $this->db->escape($data['phone']) . "',
				payment_firstname 		= '" . $this->db->escape($data['name_fastorder']) . "',
				shipping_firstname 		= '" . $this->db->escape($data['name_fastorder']) . "',
				comment 				= '" . $this->db->escape($data['comment_buyer']) . "',
				total 					= '" . (float)$data['total_fast'] . "',
				order_status_id 		= '" . $this->config->get('config_order_status_id')."',
				ip 						= '" . $this->db->escape($data['ip']) . "',
				forwarded_ip 			= '" .  $this->db->escape($data['forwarded_ip']) . "',
				user_agent 				= '" . $this->db->escape($data['user_agent']) . "',
				accept_language 		= '" . $this->db->escape($data['accept_language']) . "',
				date_added 				= NOW(),
				date_modified 			= NOW()");

				$order_id = $this->db->getLastId();

				$this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET
				order_id 		= '" . (int)$order_id . "',
				product_id 		= '" . (int)$data['this_prod_id'] . "',
				name 			= '" . $this->db->escape($data['prod_name']) . "',
				model 			= '" . $this->db->escape($this_product_info['model']) . "',
				quantity 		= '" . (int)$data['quantity'] . "',
				price 			= '" . (float)$data['price_no_tax'] . "',
				total 			= '" . (float)$data['price_no_tax']*$data['quantity']. "',
				tax 			= '" . (float)$this->tax->getTax($data['price_no_tax'], $data['tax_class_id_total']) . "',
				reward = 		''");


			$order_product_id = $this->db->getLastId();

			foreach($product_options as $product_option){
				$this->db->query("INSERT INTO " . DB_PREFIX . "order_option SET
					order_id 				= '". (int)$order_id."',
					order_product_id 		= '". (int)$order_product_id."',
					product_option_id 		= '". (int)$product_option['product_option_id'] . "',
					product_option_value_id = '". (int)$product_option['product_option_value_id'] . "',
					type 					= '".  $this->db->escape($product_option['type']) ."',
					name 					= '".  $this->db->escape($product_option['name']) ."',
					`value` 				= '".  $this->db->escape($product_option['value']) . "'");
			}

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET
			order_id 			= '" . (int)$order_id . "',
			code 				= 'sub_total',
			title 				= 'Sub-Total',
			`value` 			= '" . (float)$data['price_no_tax']*(int)$data['quantity'] . "',
			sort_order 			= '1'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET
			order_id 			= '" . (int)$order_id . "',
			code 				= 'shipping',
			title 				= '" . $this->db->escape($data['shipping_title']) . "',
			`value` 			= '" . (float)$data['price_shipping_value'] . "',
			sort_order 			= '3'");

			if($data['config_tax'] =='1'){
				foreach($tax_rates_f_p as $tax_rate){
					$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET
					order_id 	= '" . (int)$order_id . "',
					code 		= 'tax',
					title 		= '". $this->db->escape($tax_rate['name']) ."',
					`value` 	= '". (float)$tax_rate['amount']*(int)$data['quantity'] ."',
					sort_order 	= '5'");
				}
			}

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET
				order_id 		= '" . (int)$order_id . "',
				code 			= 'total',
				title 			= 'Total',
				`value` 		= '" . (float)$data['total_fast'] . "',
				sort_order 		= '9'");

			/*FAST ADD*/
			if ($this->config->get('config_tax')) {
				$data['price_no_tax_qucik_list'] = $data['price_tax'];
			} else {
				$data['price_no_tax_qucik_list'] = $data['price_no_tax'];
			}
			$query = $this->db->query("INSERT INTO " . DB_PREFIX . "newfastorder_product SET
				product_name = '" . $this->db->escape($data['prod_name'])  . "',
				order_id 			= '" . (int)$order_id . "',
				product_id 			= '" . (int)$data['this_prod_id'] ."',
				price 				= '" . (float)$data['price_no_tax_qucik_list'] ."',
				total 				= '" . (float)$data['total_fast'] . "',
				currency_code 		= '" . $this->db->escape($data['currency_code']) . "',
				currency_value 		= '" . $this->db->escape($data['currency_value']) . "',
				model 				= '" . $this->db->escape($this_product_info['model']) . "',
				quantity 			= '" . (int)$data['quantity'] ."'");

			foreach($product_options as $product_option){
				$this->db->query("INSERT INTO " . DB_PREFIX . "newfastorder_product_option SET
					order_id 				= '". (int)$order_id."',
					order_product_id 		= '". (int)$data['this_prod_id']."',
					product_option_id 		= '". (int)$product_option['product_option_id'] . "',
					product_option_value_id = '". (int)$product_option['product_option_value_id'] . "',
					type 					= '". $this->db->escape($product_option['type']) ."',
					name 					= '". $this->db->escape($product_option['name']) ."',
					`value` 				= '". $this->db->escape($product_option['value']) . "'");
			}

			$query = $this->db->query("INSERT INTO " . DB_PREFIX . "newfastorder SET
				name 				= '" . $this->db->escape($data['name_fastorder'])  . "',
				email_buyer 		= '" . $this->db->escape($data['email_buyer'])  . "',
				newfastorder_url 	= '" . $this->db->escape($data['url_site'])  . "',
				comment_buyer 		= '" . $this->db->escape($data['comment_buyer'])  . "',
				telephone 			= '" . $this->db->escape($data['phone']) . "',
				total = '" . $this->db->escape($this->currency->format($data['total_fast'], $this->session->data['currency'])) . "',
				order_id 			= '" . (int)$order_id . "',
				date_added 			= NOW(),
				date_modified 		= NOW(),
				status_id 			= '0',
				comment 			= ''");


	}
}
?>
