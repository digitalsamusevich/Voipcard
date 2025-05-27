<?php
class ModelCatalogCyberProductTabs extends Model {
    public function getTabs($product_id) {
        $product_tabs = array();
        $tabs_ns_id = '';
        $query = $this->db->query("SELECT ptn.text, tn.icon_tabs, tn.tabs_ns_id, tn.show_empty_tab,tdn.title FROM " . DB_PREFIX . "product_tabs_ns ptn LEFT JOIN " . DB_PREFIX . "tabs_ns tn ON (tn.tabs_ns_id = ptn.tabs_ns_id) LEFT JOIN " . DB_PREFIX . "tabs_description_ns tdn ON (tdn.tabs_ns_id = ptn.tabs_ns_id) WHERE ptn.product_id = '" . (int)$product_id . "' AND ptn.language_id = '" . (int)$this->config->get('config_language_id') . "' AND tdn.language_id = '" . (int)$this->config->get('config_language_id') . "' AND tn.status = '1' ORDER BY tn.sort_order");

        if ($query->rows) {
            foreach ($query->rows as $result) {

                if($result['show_empty_tab'] == '1'){
                    $product_tabs[] = array(
                        'tabs_ns_id' => $result['tabs_ns_id'],
                        'title' => $result['title'],
                        'text'  => html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8'),
                        'icon_tabs' => $result['icon_tabs'],
                    );
                } else {
                    $tab_text_info = strip_tags(html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8'),'<iframe>');
                    if($tab_text_info !=''){
                        $product_tabs[] = array(
                            'tabs_ns_id' => $result['tabs_ns_id'],
                            'title' => $result['title'],
                            'text'  => html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8'),
                            'icon_tabs' => $result['icon_tabs'],
                        );
                    }
                }

            }
        }

        $tabs_ns_id = array_map(function ($value) {return $value['tabs_ns_id'];}, $product_tabs);

        $tabs_all_product = $this->getTabsViewAll($tabs_ns_id,$product_id);
        $all_tabs = array_merge($product_tabs,$tabs_all_product);

        return $all_tabs;
    }

    private function getTabsViewAll($tabs_ns_id,$product_id) {

        $product_tabs = array();

        $sql = "SELECT tdn.text,tn.products_ignore, tn.status_view_all, tn.show_categories, tn.show_manufacturers, tn.show_attributes, tn.icon_tabs, tn.tabs_ns_id, tn.show_empty_tab,tdn.title FROM " . DB_PREFIX . "tabs_ns tn LEFT JOIN " . DB_PREFIX . "tabs_description_ns tdn ON (tn.tabs_ns_id = tdn.tabs_ns_id) WHERE tdn.language_id = '" . (int)$this->config->get('config_language_id') . "'AND tn.status = '1'";

        if(!empty($tabs_ns_id)){
            $implode = array();

            foreach ($tabs_ns_id as $tab_id) {
                $implode[] = (int)$tab_id;
            }

            $sql .= " AND tn.tabs_ns_id NOT IN (" . implode(',', $implode) . ")";
        }

        $sql .= " ORDER BY tn.sort_order";

        $query = $this->db->query($sql);

        if ($query->rows) {
            foreach ($query->rows as $result) {

                $status_tab = false;
                $show_categories = ($result['show_categories'] ? json_decode($result['show_categories']) : array());
                $show_manufacturers = ($result['show_manufacturers'] ? json_decode($result['show_manufacturers']) : array());
                $show_attributes = ($result['show_attributes'] ? json_decode($result['show_attributes']) : array());

                if($show_categories){
                    $implode_categories = array();

                    foreach ($show_categories as $category_id) {
                        $implode_categories[] = (int)$category_id;
                    }

                    $query_c = $this->db->query("SELECT * FROM `". DB_PREFIX ."product_to_category` WHERE category_id IN( ". implode(',', $implode_categories) ." ) AND product_id = '". (int)$product_id ."'");
                    if($query_c->row){
                        $status_tab = true;
                    }
                }

                if($show_manufacturers){
                    $query_m = $this->db->query("SELECT `manufacturer_id` FROM `". DB_PREFIX ."product` WHERE product_id = '". (int)$product_id ."'");
                    if($query_m->row){
                        if(!empty($query_m->row['manufacturer_id'])){
                            if(in_array($query_m->row['manufacturer_id'], $show_manufacturers)){
                                $status_tab = true;
                            }
                        }
                    }
                }

                if($show_attributes){
                    $implode_attributes = array();

                    foreach ($show_attributes as $attribute_id) {
                        $implode_attributes[] = (int)$attribute_id;
                    }
                    $query_a = $this->db->query("SELECT * FROM `". DB_PREFIX ."product_attribute` WHERE attribute_id IN( ". implode(',', $implode_attributes) ." ) AND product_id = '". (int)$product_id ."' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
                    if($query_a->row){
                        $status_tab = true;
                    }
                }

                if($result['status_view_all']){
                    $status_tab = true;
                }

                $products_ignore = ($result['products_ignore'] ? json_decode($result['products_ignore']) : array());

                if(in_array($product_id, $products_ignore)){
                    $status_tab = false;
                }


                if($status_tab){
                    if($result['show_empty_tab'] == '1'){
                        $product_tabs[] = array(
                            'tabs_ns_id' => $result['tabs_ns_id'],
                            'title' => $result['title'],
                            'text'  => html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8'),
                            'icon_tabs' => $result['icon_tabs'],
                        );
                    } else {
                        $tab_text_info = strip_tags(html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8'),'<iframe>');
                        if($tab_text_info !=''){
                            $product_tabs[] = array(
                                'tabs_ns_id' => $result['tabs_ns_id'],
                                'title' => $result['title'],
                                'text'   => html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8'),
                                'icon_tabs' => $result['icon_tabs'],
                            );
                        }
                    }
                }
            }
        }
        return $product_tabs;
    }
}