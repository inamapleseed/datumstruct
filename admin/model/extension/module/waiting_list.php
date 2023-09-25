<?php
    class ModelExtensionModuleWaitingList extends Model {
        public function getProductWaiting($page = 1, $limit){
            $page = ($page - 1) * $limit;

            $query = $this->db->query('SELECT pd.name, COUNT(pwl.email) as request 
            FROM `' . DB_PREFIX . 'product_waiting_list` pwl LEFT JOIN `' . DB_PREFIX . 'product_description` pd ON (pwl.product_id = pd.product_id) 
            WHERE pd.language_id=1 AND notified = 0
            GROUP BY pwl.product_id ORDER BY request DESC LIMIT '.$page.','.$limit);

            return $query->rows;
        }

        public function getTotalProductWaiting(){
            $query = $this->db->query('SELECT pd.name, COUNT(pwl.email) as request 
            FROM `' . DB_PREFIX . 'product_waiting_list` pwl LEFT JOIN `' . DB_PREFIX . 'product_description` pd ON (pwl.product_id = pd.product_id) 
            WHERE pd.language_id=1 AND notified = 0
            GROUP BY pwl.product_id ORDER BY request DESC');

            return $query->num_rows;
        }
    }