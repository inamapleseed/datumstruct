<?php 
    class ModelSettingSeoUrl extends Model {
        
        public function getSeourls() {
            
			$sql = "SELECT * FROM ".DB_PREFIX."seo_url  ORDER BY query ASC";
			$query = $this->db->query($sql);
            
			return $query->rows;
        }
    }