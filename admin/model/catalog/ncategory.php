<?php
class ModelCatalogncategory extends Model {
	public function addncategory($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "sb_ncategory SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");
	
		$ncategory_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "sb_ncategory SET image = '" . $this->db->escape($data['image']) . "' WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		}
		
		foreach ($data['ncategory_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "sb_ncategory_description SET ncategory_id = '" . (int)$ncategory_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		if (isset($data['ncategory_store'])) {
			foreach ($data['ncategory_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_ncategory_to_store SET ncategory_id = '" . (int)$ncategory_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['ncategory_group'])) {
			foreach ($data['ncategory_group'] as $group_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_ncategory_to_group SET ncategory_id = '" . (int)$ncategory_id . "', group_id = '" . (int)$group_id . "'");
			}
		}

		if (isset($data['ncategory_layout'])) {
			foreach ($data['ncategory_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_ncategory_to_layout SET ncategory_id = '" . (int)$ncategory_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}
						
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'ncategory_id=" . (int)$ncategory_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('ncategory');
	}
	
	public function editncategory($ncategory_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "sb_ncategory SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "sb_ncategory SET image = '" . $this->db->escape($data['image']) . "' WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_ncategory_description WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		foreach ($data['ncategory_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "sb_ncategory_description SET ncategory_id = '" . (int)$ncategory_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_ncategory_to_store WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		
		if (isset($data['ncategory_store'])) {		
			foreach ($data['ncategory_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_ncategory_to_store SET ncategory_id = '" . (int)$ncategory_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_ncategory_to_group WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		if (isset($data['ncategory_group'])) {
			foreach ($data['ncategory_group'] as $group_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_ncategory_to_group SET ncategory_id = '" . (int)$ncategory_id . "', group_id = '" . (int)$group_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_ncategory_to_layout WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		if (isset($data['ncategory_layout'])) {
			foreach ($data['ncategory_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_ncategory_to_layout SET ncategory_id = '" . (int)$ncategory_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}
						
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'ncategory_id=" . (int)$ncategory_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'ncategory_id=" . (int)$ncategory_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('ncategory');
	}
	
	public function deletencategory($ncategory_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_ncategory WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_ncategory_description WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_ncategory_to_store WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_ncategory_to_group WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_ncategory_to_layout WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'ncategory_id=" . (int)$ncategory_id . "'");
		
		$query = $this->db->query("SELECT ncategory_id FROM " . DB_PREFIX . "sb_ncategory WHERE parent_id = '" . (int)$ncategory_id . "'");

		foreach ($query->rows as $result) {
			$this->deletencategory($result['ncategory_id']);
		}
		
		$this->cache->delete('ncategory');
	} 

	public function getncategory($ncategory_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'ncategory_id=" . (int)$ncategory_id . "') AS keyword FROM " . DB_PREFIX . "sb_ncategory WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		
		return $query->row;
	} 
	
	public function getncategories($parent_id = 0) {
		$ncategory_data = $this->cache->get('ncategory.' . (int)$this->config->get('config_language_id') . '.' . (int)$parent_id);
	
		if (!$ncategory_data) {
			$ncategory_data = array();
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_ncategory c LEFT JOIN " . DB_PREFIX . "sb_ncategory_description cd ON (c.ncategory_id = cd.ncategory_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
			foreach ($query->rows as $result) {
				$ncategory_data[] = array(
					'ncategory_id' => $result['ncategory_id'],
					'name'        => $this->getPath($result['ncategory_id'], $this->config->get('config_language_id')),
					'status'  	  => $result['status'],
					'sort_order'  => $result['sort_order']
				);
			
				$ncategory_data = array_merge($ncategory_data, $this->getncategories($result['ncategory_id']));
			}	
	
			$this->cache->set('ncategory.' . (int)$this->config->get('config_language_id') . '.' . (int)$parent_id, $ncategory_data);
		}
		
		return $ncategory_data;
	}
	
	public function getPath($ncategory_id) {
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "sb_ncategory c LEFT JOIN " . DB_PREFIX . "sb_ncategory_description cd ON (c.ncategory_id = cd.ncategory_id) WHERE c.ncategory_id = '" . (int)$ncategory_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		if ($query->row['parent_id']) {
			return $this->getPath($query->row['parent_id'], $this->config->get('config_language_id')) . " > " . $query->row['name'];
		} else {
			return $query->row['name'];
		}
	}
	
	public function getncategoryDescriptions($ncategory_id) {
		$ncategory_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_ncategory_description WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		
		foreach ($query->rows as $result) {
			$ncategory_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		
		return $ncategory_description_data;
	}	
	
	public function getncategoryStores($ncategory_id) {
		$ncategory_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_ncategory_to_store WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		foreach ($query->rows as $result) {
			$ncategory_store_data[] = $result['store_id'];
		}
		
		return $ncategory_store_data;
	}
	public function getncategoryGroups($ncategory_id) {
		$ncategory_group_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_ncategory_to_group WHERE ncategory_id = '" . (int)$ncategory_id . "'");

		foreach ($query->rows as $result) {
			$ncategory_group_data[] = $result['group_id'];
		}
		
		return $ncategory_group_data;
	}

	public function getncategoryLayouts($ncategory_id) {
		$ncategory_layout_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_ncategory_to_layout WHERE ncategory_id = '" . (int)$ncategory_id . "'");
		
		foreach ($query->rows as $result) {
			$ncategory_layout_data[$result['store_id']] = $result['layout_id'];
		}
		
		return $ncategory_layout_data;
	}
		
	public function getTotalncategories() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sb_ncategory");
		
		return $query->row['total'];
	}	
		
	public function getTotalncategoriesByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sb_ncategory WHERE image_id = '" . (int)$image_id . "'");
		
		return $query->row['total'];
	}

	public function getTotalncategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sb_ncategory_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}		
}
?>