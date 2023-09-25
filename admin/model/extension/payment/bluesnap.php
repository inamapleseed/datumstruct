<?php
class ModelExtensionPaymentBluesnap extends Model {
	public function __construct($registry) {
                 parent::__construct($registry);
                $this->registry = $registry;
                require_once(DIR_SYSTEM . "library/payment/bluesnap.php");
                $this->bluesnap = new Bluesnap($registry);
        }
	

	public function install() {
		$this->bluesnap->install();
	}


	protected function get_where_clause($data) {
		$implode = array();
		$sql = '';
                if (!empty($data['filter_order_id'])) {
                        $implode[] = "order_id = '" . (int) $data['filter_order_id'] . "'";
                }

                if (!empty($data['filter_total'])) {
                        $implode[] = "amount like '%" . $this->db->escape($data['filter_total']) . "%'";
                }

                if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
                        $implode[] = "DATE(date_added) = '" . $this->db->escape($data['filter_date_added']) . "'";
                }

                if (!empty($data['filter_ip'])) {
                        $implode[] = "remote_ip like '%". $this->db->escape($data['filter_remote_ip']) ."%'";
                }

                if (strlen($data['filter_result_code']) > 0) {
                        $implode[] = "result_code = '". (int) $this->db->escape($data['filter_result_code']) ."'";
                }

                if (!empty($data['filter_remote_ip'])) {
                        $implode[] = "remote_ip like '%". $this->db->escape($data['filter_remote_ip']) ."%'";
                }


                if (!empty($data['filter_date_added'])) {
                        $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
                }

                if ($implode) {
                        $sql .= " AND " . implode(" AND ", $implode);
                }
		return $sql;
	}

	public function get_total_entries($data) { 
		$sql = "select count(*) as total from `" . DB_PREFIX . "bluesnap_audit_trail` where 1 = 1 " . $this->get_where_clause($data);
		$result = $this->db->query($sql);
		return $result->row['total'];

	}

	public function get_entry($bluesnap_audit_id) {
		$sql = "select * from `" . DB_PREFIX . "bluesnap_audit_trail` where bluesnap_audit_id = '" . (int) $bluesnap_audit_id . "'";
		$result = $this->db->query($sql);
		if (isset($result->row['bluesnap_audit_id'])) {
			return $result->row;
		} else { 
			return null;
		}
	}	


	public function get_entries($data) {
		$sql = "select * from `" . DB_PREFIX . "bluesnap_audit_trail` where 1 = 1 " . $this->get_where_clause($data);
		$sort_data = array(
			'name',
			'amount',
			'result_code',
			'approved',
			'remote_ip',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY order_id";
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
}
