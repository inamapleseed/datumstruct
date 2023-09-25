<?php
class ModelCatalogNcomments extends Model {
	public function addComment($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "sb_ncomments SET author = '" . $this->db->escape($data['author']) . "', news_id = '" . $this->db->escape($data['news_id']) . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '" . (int)$data['status'] . "', language_id = '" . (int)$data['language_id'] . "', date_added = NOW()");
	}
	
	public function editComment($ncomment_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "sb_ncomments SET author = '" . $this->db->escape($data['author']) . "', news_id = '" . $this->db->escape($data['news_id']) . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '" . (int)$data['status'] . "', language_id = '" . (int)$data['language_id'] . "', date_added = NOW() WHERE ncomment_id = '" . (int)$ncomment_id . "'");
	}
	
	public function deleteComment($ncomment_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_ncomments WHERE ncomment_id = '" . (int)$ncomment_id . "'");
	}
	
	public function getComment($ncomment_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT bd.title FROM " . DB_PREFIX . "sb_news_description bd WHERE bd.news_id = n.news_id AND bd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS article FROM " . DB_PREFIX . "sb_ncomments n WHERE n.ncomment_id = '" . (int)$ncomment_id . "'");
		
		return $query->row;
	}

	public function getComments($data = array()) {
		$sql = "SELECT n.ncomment_id, bd.title, n.author, n.status, n.date_added FROM " . DB_PREFIX . "sb_ncomments n LEFT JOIN " . DB_PREFIX . "sb_news_description bd ON (n.news_id = bd.news_id) WHERE bd.language_id = '" . (int)$this->config->get('config_language_id') . "'";																																					  
		
		$sort_data = array(
			'bd.title',
			'n.author',
			'n.status',
			'n.date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY n.date_added";	
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
	
	public function getTotalComments() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sb_ncomments");
		
		return $query->row['total'];
	}
	
	public function getTotalCommentsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sb_ncomments WHERE status = '0'");
		
		return $query->row['total'];
	}	
}
?>