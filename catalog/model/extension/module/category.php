<?php
    class ModelExtensionModuleCategory extends Model{
        public function getCategories($parent_id = 0, $main_pcategories="") {
// Start products or gallery
        	if ($main_pcategories) {
			$filter_ncategories = "AND c.category_names='" . $main_pcategories ."'";
		}else{
			$filter_ncategories = "";
		}
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c.category_names = '" . $filter_ncategories . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");
// End products or gallery
            return $query->rows;
        }
    }