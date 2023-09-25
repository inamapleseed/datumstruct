<?php
class ModelCatalogncategory extends Model {
	public function getncategory($ncategory_id) {

		$group_restriction = $this->config->get('ncategory_bnews_restrictgroup') ? " AND c2g.group_id = '" . (int)$this->config->get('config_customer_group_id') . "' " : '';

		$group_restriction_join = $this->config->get('ncategory_bnews_restrictgroup') ? " LEFT JOIN " . DB_PREFIX . "sb_ncategory_to_group c2g ON (c.ncategory_id = c2g.ncategory_id) " : '';

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "sb_ncategory c LEFT JOIN " . DB_PREFIX . "sb_ncategory_description cd ON (c.ncategory_id = cd.ncategory_id) LEFT JOIN " . DB_PREFIX . "sb_ncategory_to_store c2s ON (c.ncategory_id = c2s.ncategory_id)".$group_restriction_join." WHERE c.ncategory_id = '" . (int)$ncategory_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'" . $group_restriction);
		
		return $query->row;
	}
	
	public function getncategories($parent_id = 0) {

		$group_restriction = $this->config->get('ncategory_bnews_restrictgroup') ? " AND c2g.group_id = '" . (int)$this->config->get('config_customer_group_id') . "' " : '';

		$group_restriction_join = $this->config->get('ncategory_bnews_restrictgroup') ? " LEFT JOIN " . DB_PREFIX . "sb_ncategory_to_group c2g ON (c.ncategory_id = c2g.ncategory_id) " : '';

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_ncategory c LEFT JOIN " . DB_PREFIX . "sb_ncategory_description cd ON (c.ncategory_id = cd.ncategory_id) LEFT JOIN " . DB_PREFIX . "sb_ncategory_to_store c2s ON (c.ncategory_id = c2s.ncategory_id)".$group_restriction_join." WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ". $group_restriction ." ORDER BY c.sort_order, LCASE(cd.name)");
		
		return $query->rows;
	}

	public function getncategoriesByParentId($ncategory_id) {
		$ncategory_data = array();
		
		$ncategory_query = $this->db->query("SELECT ncategory_id FROM " . DB_PREFIX . "sb_ncategory WHERE parent_id = '" . (int)$ncategory_id . "'");
		
		foreach ($ncategory_query->rows as $ncategory) {
			$ncategory_data[] = $ncategory['ncategory_id'];
			
			$children = $this->getncategoriesByParentId($ncategory['ncategory_id']);
			
			if ($children) {
				$ncategory_data = array_merge($children, $ncategory_data);
			}			
		}
		
		return $ncategory_data;
	}
		
	public function getncategoryLayoutId($ncategory_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_ncategory_to_layout WHERE ncategory_id = '" . (int)$ncategory_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return $this->config->get('config_layout_ncategory');
		}
	}
					
	public function getTotalncategoriesByncategoryId($parent_id = 0) {

		$group_restriction = $this->config->get('ncategory_bnews_restrictgroup') ? " AND c2g.group_id = '" . (int)$this->config->get('config_customer_group_id') . "' " : '';

		$group_restriction_join = $this->config->get('ncategory_bnews_restrictgroup') ? " LEFT JOIN " . DB_PREFIX . "sb_ncategory_to_group c2g ON (c.ncategory_id = c2g.ncategory_id) " : '';

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sb_ncategory c LEFT JOIN " . DB_PREFIX . "sb_ncategory_to_store c2s ON (c.ncategory_id = c2s.ncategory_id)".$group_restriction_join." WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'" . $group_restriction);
		
		return $query->row['total'];
	}
}
?>