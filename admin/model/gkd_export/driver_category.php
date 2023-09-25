<?php
class ModelGkdExportDriverCategory extends Model {
  private $langIdToCode = array();

  public function getItems($data = array(), $count = false) {
    $select = ($count) ? 'COUNT(DISTINCT c.category_id) AS total' : '*';
    
    if (!empty($data['filter_store'])) {
      $store_id = $data['filter_store'];
      
      $this->load->model('setting/store');
      $store = $this->model_setting_store->getStore($data['filter_store']);
    } else {
      $store_id = 0;
      
      $store = array(
        'name' => $this->config->get('config_name'),
        'url' => HTTP_CATALOG,
        'ssl' => HTTPS_CATALOG,
      );
    }
    
    $description_field = '';
    if ($store_id && $this->config->get('mlseo_multistore')) {
      $description_field = 'seo_';
    }
    
    $sql = "SELECT ".$select." FROM " . DB_PREFIX . "category c";
    
    if (isset($data['filter_language']) && $data['filter_language'] !== '') {
      $sql .= " LEFT JOIN " . DB_PREFIX . $description_field . "category_description cd ON (c.category_id = cd.category_id)";
    }
    
    if (!empty($data['filter_store'])) {
      $sql .= " LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)";
    }
    
    $lgquery = $this->db->query("SELECT DISTINCT language_id, code FROM " . DB_PREFIX . "language WHERE status = 1")->rows;
    
    foreach ($lgquery as $lang) {
      $this->langIdToCode[$lang['language_id']] = substr($lang['code'], 0, 2);
    }
    
    // WHERE
    // languages
    if (isset($data['filter_language']) && $data['filter_language'] !== '') {
      $sql .= " WHERE cd.language_id = '" . (int)$data['filter_language'] . "'";
    } else {
      $sql .= " WHERE 1";
    }
    
    if (!empty($data['filter_parent'])) {
      $sql .= " c.parent_id = '" . (int)$data['filter_parent'] . "'";
    }
    
    if (!empty($data['filter_store'])) {
      $sql .= " AND c2s.store_id = '" . (int)$data['filter_store'] . "'";
      
      if (isset($data['filter_language']) && $data['filter_language'] !== '' && $store_id && $this->config->get('mlseo_multistore')) {
        $sql .= " AND cd.store_id = '" . (int)$data['filter_store'] . "'";
      }
    }
    
    if (!empty($data['filter_status'])) {
      $sql .= " AND c.status = '" . (int)$data['filter_status'] . "'";
    }
    
		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
    
    // return count
    if ($count) {
      return $this->db->query($sql)->row['total'];
    }
    
    $sql .= " ORDER BY c.category_id";
    
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

    foreach ($query->rows as &$row) {
      $row['store'] = $store['name'];
      if (!empty($data['filter_language'])) {
        $row['full_path'] = $this->getFullCategoryPath($row, $store_id, $data['filter_language']);
      }
      
      if (isset($data['filter_language']) && $data['filter_language'] === '') {
        foreach ($this->langIdToCode as $language_id => $language_code) {
          $row['full_path_'.$language_code] = $this->getFullCategoryPath($row, $store_id, $language_id);
        }
        $row += $this->getCategoryDescription($row['category_id'], $store_id);
      }
    }
		return $query->rows;
	}
  
  public function getCategoryDescription($category_id, $store_id) {
    if ($store_id && $this->config->get('mlseo_multistore')) {
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_category_description WHERE category_id = '" . (int)$category_id . "' AND store_id = '".(int) $store_id."' ORDER BY language_id ASC");
    } else {
      $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "' ORDER BY language_id ASC");
    }
    
    $res = array();
    
    foreach ($query->rows as &$row) {
      foreach ($row as $key => $val) {
        if (!in_array($key, array('language_id', 'category_id'))) {
          if (isset($this->langIdToCode[$row['language_id']])) {
            $res[$key.'_'.$this->langIdToCode[$row['language_id']]] = $val;
          }
        }
      }
    }
    
		return $res;
	}

  public function getFullCategoryPath($category, $store_id, $language_id) {
    $description_field = '';
    if ($store_id && $this->config->get('mlseo_multistore')) {
      $description_field = 'seo_';
    }
    
    if (!isset($category['name'])) {
      $category = $this->db->query("SELECT cd.name, c.category_id, c.parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . $description_field . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . $category['category_id']. "' AND cd.language_id = '".(int) $language_id."'")->row;
    }
    
    $path = '';
    
    $path = $category['name'];
    
    while (!empty($category['parent_id'])) {
      $category = $this->db->query("SELECT cd.name, c.category_id, c.parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . $description_field . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . $category['parent_id']. "' AND cd.language_id = '".(int) $language_id."'")->row;
      $path = $category['name'] . '>' . $path;
    }
    
    return $path;
	}
  
  public function getTotalItems($data = array()) {
    return $this->getItems($data, true);
  }
}