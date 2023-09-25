<?php

class ModelCSVPriceProLibProductFilter extends Model {

	// Static
	private static $LanguageID = NULL;
	private static $Languages = NULL;

	//-------------------------------------------------------------------------
	// PRODUCT EXPORT - Get Product Filters
	//-------------------------------------------------------------------------
	public function getProductFilters($product_id) {

		$filters = array();

		$sql = "SELECT CONCAT(fgd.name, '|', fd.name) AS p_filters
			FROM `" . DB_PREFIX . "product_filter` pf
			LEFT JOIN `" . DB_PREFIX . "filter_description` fd ON (pf.filter_id = fd.filter_id AND fd.language_id = '" . (int) $this->LanguageID . "')
			LEFT JOIN `" . DB_PREFIX . "filter_group_description` fgd ON (fd.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int) $this->LanguageID . "')
		  	WHERE pf.product_id = " . (int) $product_id;
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$filters[] = html_entity_decode($result['p_filters']);
		}

		return implode("\n", $filters);
	}
	
	//-------------------------------------------------------------------------
	// CATEGORY EXPORT - get Category Filters
	//-------------------------------------------------------------------------
	public function getCategoryFilters($catgeory_id) {
		$filters = array();
		
		$sql = "SELECT CONCAT(fgd.name, '|', fd.name) AS c_filters
			FROM `" . DB_PREFIX . "category_filter` cf
			LEFT JOIN `" . DB_PREFIX . "filter_description` fd ON (cf.filter_id = fd.filter_id AND fd.language_id = '" . (int) $this->LanguageID . "')
			LEFT JOIN `" . DB_PREFIX . "filter_group_description` fgd ON (fd.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int) $this->LanguageID . "')
		  	WHERE cf.category_id = " . (int) $catgeory_id;
		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$filters[] = html_entity_decode($result['c_filters']);
		}

		return implode("\n", $filters);
	}

	//-------------------------------------------------------------------------
	// Add Product Filters
	//-------------------------------------------------------------------------
	public function addProductFilters($product_id, $filters, $language_id) {
		$this->LanguageID = $language_id;

		// Delete old product filters
		//-------------------------------------------------------------------------
		$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_filter` WHERE  product_id = \'' . (int) $product_id . '\'');

		if (empty($filters)) {
			return;
		}
		$data_a = explode("\n", $filters);
		unset($filters);

		if (!empty($data_a)) {
			foreach ($data_a as $filters_string) {
				$filter = explode('|', $filters_string);

				if (empty($filter) || count($filter) < 2) {
					continue;
				}

				$filter_id = $this->getFilterIdByName($filter[1], $filter[0]);
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_filter` SET  product_id = \'' . (int) $product_id . '\', filter_id = \'' . (int) $filter_id . '\' ON DUPLICATE KEY UPDATE filter_id = \'' . (int) $filter_id . '\'');
			}
		}
	}

	//-------------------------------------------------------------------------
	// Add Category Filters
	//-------------------------------------------------------------------------
	public function addCategoryFilters($category_id, $filters, $language_id) {
		$this->LanguageID = $language_id;

		// Delete old category filters
		//-------------------------------------------------------------------------
		$this->db->query('DELETE FROM `' . DB_PREFIX . 'category_filter` WHERE  category_id = \'' . (int) $category_id . '\'');

		if (empty($filters)) {
			return;
		}
		$data = explode("\n", $filters);
		unset($filters);

		if (!empty($data)) {
			foreach ($data as $filters_string) {
				$filter = explode('|', $filters_string);

				if (empty($filter) || count($filter) < 2) {
					continue;
				}

				$filter_id = $this->getFilterIdByName($filter[1], $filter[0]);
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'category_filter` SET  category_id = \'' . (int) $category_id . '\', filter_id = \'' . (int) $filter_id . '\'');
			}
		}
	}

	//-------------------------------------------------------------------------
	// Get Filter ID By Name
	//-------------------------------------------------------------------------
	private function getFilterIdByName($filter_name, $group_name) {

		$filter_group_id = $this->getFilterGroupIdByName($group_name);

		$result = $this->db->query('SELECT filter_id FROM `' . DB_PREFIX . 'filter_description` WHERE LOWER(name) = LOWER(\'' . $this->db->escape(htmlspecialchars(trim($filter_name))) . '\') AND language_id = \'' . (int) $this->LanguageID . '\' AND filter_group_id = \'' . $filter_group_id . '\' LIMIT 1');

		if ($result->num_rows > 0 && isset($result->row['filter_id'])) {
			return $result->row['filter_id'];
		} else {
			return $this->addFilter($filter_group_id, $filter_name);
		}
	}

	//-------------------------------------------------------------------------
	// Get Filter Group ID By Name
	//-------------------------------------------------------------------------
	private function getFilterGroupIdByName($group_name) {
		$result = $this->db->query('SELECT filter_group_id FROM `' . DB_PREFIX . 'filter_group_description` WHERE LOWER(name) = LOWER(\'' . $this->db->escape(htmlspecialchars(trim($group_name))) . '\') AND language_id = \'' . (int) $this->LanguageID . '\' LIMIT 1');
		if ($result->num_rows > 0) {
			return $result->row['filter_group_id'];
		} else {
			return $this->addFilterGroup($group_name);
		}
	}

	//-------------------------------------------------------------------------
	// Create Filter Group
	//-------------------------------------------------------------------------
	private function addFilterGroup($group_name, $sort_order = 0) {

		$this->db->query('INSERT INTO `' . DB_PREFIX . 'filter_group` SET sort_order = ' . $sort_order);

		$filter_group_id = $this->db->getLastId();

		$this->db->query('INSERT INTO `' . DB_PREFIX . 'filter_group_description` SET language_id = \'' . (int) $this->LanguageID . '\', filter_group_id = \'' . $filter_group_id . '\',  name = \'' . $this->db->escape(htmlspecialchars($group_name)) . '\'');

		return $filter_group_id;
	}

	//-------------------------------------------------------------------------
	// Create Filter
	//-------------------------------------------------------------------------
	private function addFilter($filter_group_id, $filter_name, $sort_order = 0) {
		$this->db->query('INSERT INTO `' . DB_PREFIX . 'filter` SET filter_group_id = ' . $filter_group_id . ',  sort_order = ' . $sort_order);

		$filter_id = $this->db->getLastId();

		$this->db->query('INSERT INTO `' . DB_PREFIX . 'filter_description` SET language_id = \'' . (int) $this->LanguageID . '\', filter_id = \'' . $filter_id . '\', filter_group_id = \'' . $filter_group_id . '\',  name = \'' . $this->db->escape(htmlspecialchars($filter_name)) . '\'');

		return $filter_id;
	}

	//-------------------------------------------------------------------------
	// Set Language Id
	//-------------------------------------------------------------------------
	public function setLanguageId($language_id) {
		$this->LanguageID = $language_id;
		$this->setCountLanguages();
	}

	//-------------------------------------------------------------------------
	// Get Language Count
	//-------------------------------------------------------------------------
	private function setCountLanguages() {
		$result = $this->db->query("SELECT COUNT(`language_id`) AS count_languages FROM `" . DB_PREFIX . "language` WHERE `status` = 1");

		if (isset($result->num_rows) AND $result->num_rows > 0) {
			$this->CountLanguages = $result->row['count_languages'];
		} else {
			$this->CountLanguages = 1;
		}

		return $this->CountLanguages;
	}

}
