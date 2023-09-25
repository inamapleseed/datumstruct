<?php
class ModelCatalogPagebanner extends Model {

	public function addPageBanner($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "page_banner SET page_name = '" . $this->db->escape($data['page_name']) . "', 
		status = '" . (int)$data['status'] . "', 
		route = '" . $this->db->escape($data['route']) . "',
		query = '" . $this->db->escape($data['query']) . "',
		image = '" . $this->db->escape($data['image']) . "',
		mobile_image = '" . $this->db->escape($data['mobile_image']) . "'");

		$pb_id = $this->db->getLastId();

		return $pb_id;
	}

	public function editPageBanner($pb_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "page_banner SET 
		page_name = '" . $this->db->escape($data['page_name']) . "', 
		status = '" . (int)$data['status'] . "', 
		route = '" . $this->db->escape($data['route']) . "',
		query = '" . $this->db->escape($data['query']) . "',
		image = '" . $this->db->escape($data['image']) . "',
		mobile_image = '" . $this->db->escape($data['mobile_image']) . "'

		WHERE pb_id = '" . (int)$pb_id . "'");

	}

	public function deletePageBanner($pb_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "page_banner WHERE pb_id = '" . (int)$pb_id . "'");
	}

	public function getPageBanner($pb_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "page_banner WHERE pb_id = '" . (int)$pb_id . "'");

		return $query->row;
	}

	public function getPageBanners($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "page_banner";

		$sort_data = array(
			'page_name',
			'status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY page_name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalPageBanners() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "page_banner");

		return $query->row['total'];
	}
}
