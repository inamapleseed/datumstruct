<?php 
class ModelExtensionModuleAbandonedcarts extends Model {
	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "abandonedcarts` (
			`id` INT(11) NOT NULL AUTO_INCREMENT,
			`date_created` DATETIME NULL DEFAULT NULL, 
			`date_modified` DATETIME NULL DEFAULT NULL, 
			`cart` TEXT NULL DEFAULT NULL, 
			`customer_info` TEXT NULL DEFAULT NULL,
			`last_page` VARCHAR(255) NULL DEFAULT NULL,
			`restore_id` VARCHAR(255) NULL DEFAULT NULL,
			`ip` VARCHAR(100) NULL DEFAULT NULL,
			`notified` SMALLINT NOT NULL DEFAULT 0,
			`ordered` TINYINT NOT NULL DEFAULT 0,
			`store_id` TINYINT NOT NULL DEFAULT 0,  
			 PRIMARY KEY (`id`))
        ");
			 
		//$this->db->query("UPDATE `" . DB_PREFIX . "modification` SET status=1 WHERE `name` LIKE'%AbandonedCarts by iSenseLabs%'");
		//$modifications = $this->load->controller('extension/modification/refresh');
	}
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "abandonedcarts`");
	
		//$this->db->query("UPDATE `" . DB_PREFIX . "modification` SET status=0 WHERE `name` LIKE'%AbandonedCarts by iSenseLabs%'");
		//$modifications = $this->load->controller('extension/modification/refresh');
	}
	
	public function viewAbandonedCarts($page=1, $limit=8, $store=0, $notified=false, $ordered=false, $sort="id", $order="DESC") {	

		if (VERSION >= '2.1.0.1') {
			$this->load->model('customer/customer');	
		} else {
			$this->load->model('sale/customer');		
		}
	
		if ($page) {
			$start = ($page - 1) * $limit;
		}
		
		$query = "SELECT * FROM `" . DB_PREFIX . "abandonedcarts` WHERE `store_id`='".$store."' ";
		if ($notified) {
			$query .= "AND `notified` > 0 AND `ordered` = 0 ";
		} else if ($ordered) {
			$query .= "AND `ordered` = 1 ";
		} else {
			$query .= "AND `notified` = 0 ";	
		}
		
		$query .= "ORDER BY `id` DESC LIMIT ".$start.", ".$limit;
		$query = $this->db->query($query);
		
		$abandonedCarts = array();
		foreach ($query->rows as $row) {
			$row['customer_info'] = json_decode($row['customer_info'], true);
			
			if (isset($row['customer_info']['language'])) {
				$lang_image = $this->getLanguageImage($row['customer_info']['language']);
					if(version_compare(VERSION, '2.2.0.0', "<")){	
						if (isset($lang_image)) {
							$row['customer_info']['lang_image'] = 'view/image/flags/'.$lang_image;
						}
					} else {
							$row['customer_info']['lang_image'] = 'language/' . $row['customer_info']['language'] . '/' . $row['customer_info']['language'] . '.png';
					}
			}
			$row['customer_info'] = json_encode($row['customer_info']);
			$abandonedCarts[] = $row;

		}
		return $abandonedCarts; 
	}
	
	public function getTotalAbandonedCarts($store=0, $notified=false, $ordered=false) {
		$query = "SELECT COUNT(*) as `count`  FROM `" . DB_PREFIX . "abandonedcarts` WHERE `store_id`=".$store." ";
		
		if ($notified) {
			$query .= "AND `notified` > 0 AND `ordered` = 0";
		} else if ($ordered) {
			$query .= "AND `ordered` = 1 ";
		} else {
			$query .= "AND `notified` = 0 ";	
		}
		
		$query = $this->db->query($query);
        
		return $query->row['count']; 
	}
	
	public function getCartInfo($id) {	
		$query =  $this->db->query("SELECT * FROM `" . DB_PREFIX . "abandonedcarts`
			WHERE `id`=".$id);
		
		return $query->row; 
	}
	
	public function isUniqueCode($randomCode) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coupon` WHERE code='".$this->db->escape($randomCode)."'");
        
        if($query->num_rows == 0) {
            return true;
        } else {
            return false;
        }	
	}
	
	public function generateuniquerandomcouponcode() {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$couponCode = '';
		
        for ($i = 0; $i < 10; $i++) {	
			$couponCode .= $characters[rand(0, strlen($characters) - 1)]; 
		}
		
        if($this->isUniqueCode($couponCode)) {	
			return $couponCode;
		} else {	
			return $this->generateuniquerandomcouponcode();
		}
	}
	
	public function sendMail($data = array()) {
		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$store_info = $this->model_setting_store->getStore($data['store_id']);

		if ($store_info) {
			$store_name = $store_info['name'];
		} else {
			$store_name = $this->config->get('config_name');
		}
		
		if (VERSION >= '2.0.0.0' && VERSION < '2.0.2.0') {
			$mailToUser = new Mail($this->config->get('config_mail'));
		} else {
			$mailToUser = new Mail();
			$mailToUser->protocol = $this->config->get('config_mail_protocol');
			$mailToUser->parameter = $this->config->get('config_mail_parameter');
			if (VERSION >= '2.0.2.0') {
				$mailToUser->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mailToUser->smtp_username = $this->config->get('config_mail_smtp_username');
				$mailToUser->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mailToUser->smtp_port = $this->config->get('config_mail_smtp_port');
				$mailToUser->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			} else {
				$mailToUser->hostname = $this->config->get('config_mail_smtp_hostname');
				$mailToUser->username = $this->config->get('config_mail_smtp_username');
				$mailToUser->password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mailToUser->port = $this->config->get('config_mail_smtp_port');
				$mailToUser->timeout = $this->config->get('config_mail_smtp_timeout');
			}
		}
        
		$mailToUser->setTo($data['email']);
		$mailToUser->setFrom($data['store_email']);
		$mailToUser->setSender($store_name);
		$mailToUser->setSubject(html_entity_decode($data['subject'], ENT_QUOTES, 'UTF-8'));
		$mailToUser->setHtml($data['message']);
		
		$this->config->load('isenselabs/abandonedcarts');
		$abandonedCartsSettings = $this->model_setting_setting->getSetting($this->config->get('abandonedcarts_name'), $data['store_id']);
		if(isset($abandonedCartsSettings[$this->config->get('abandonedcarts_name')]['BCC']) && $abandonedCartsSettings[$this->config->get('abandonedcarts_name')]['BCC'] == 'yes') { 
			$mailToUser->setAbandonedCartsBcc($data['store_email']);
		}

		
		$mailToUser->send(); 

		if ($mailToUser) 
		    return true;
        else
		    return false;
	}

	public function getGivenCoupons($data=array()) {
		$givenCoupons = $this->db->query("	SELECT *
											FROM " . DB_PREFIX . "coupon
											WHERE name LIKE  '%AbCart [%'
											ORDER BY " . $data['sort'] . " ". $data['order'] . " 
											LIMIT " . $data['start'].", " . $data['limit'] );										 
		return $givenCoupons->rows;
	}

	public function getTotalGivenCoupons() {
		$givenCoupons = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "coupon WHERE name LIKE '%AbCart [%'"); 
        
		return $givenCoupons->row['count'];
	}
	
	public function getUsedCoupons($data=array()) {
		$usedCoupons = $this->db->query("SELECT c.*
		 								  FROM `" . DB_PREFIX . "coupon` AS c
										  JOIN `" . DB_PREFIX . "order_total` AS ot ON ot.title LIKE CONCAT('%(', c.code, ')')
										  WHERE name LIKE  '%AbCart [%'
										  ORDER BY " . $data['sort'] . " ". $data['order'] . " 
										  LIMIT " . $data['start'].", " . $data['limit'] );
                                          
		return $usedCoupons->rows;
	}
	
	public function getTotalUsedCoupons() {
		$givenCoupons = $this->db->query("SELECT COUNT(*) as count FROM `" . DB_PREFIX . "coupon` as c
											JOIN `" . DB_PREFIX . "order_total` AS ot ON ot.title LIKE CONCAT('%(', c.code, ')')
											WHERE name LIKE  '%AbCart [%'"); 
                                            
		return $givenCoupons->row['count'];
	}
	
	public function getLanguageImage($language_code) {
		$query = $this->db->query("SELECT image FROM " . DB_PREFIX . "language WHERE code = '" . $language_code . "'");
        
		return $query->row['image'];
	}
	
	public function getLanguageId($language_code) {
		$query = $this->db->query("SELECT language_id FROM " . DB_PREFIX . "language WHERE code = '" . $language_code . "'");
        
		return $query->row['language_id'];
	}

	public function loadLanguage($directory, $filename) {
		$this->config->load('isenselabs/abandonedcarts');	
		$target = $directory.'/'.$this->config->get('abandonedcarts_path');
		$data = array();

		$file = DIR_LANGUAGE . $target .'.php';

		if (file_exists($file)) {
			$_ = array();
			require($file);
			$data = array_merge($data, $_);
			return $data;
		}

		$target = 'english/'.$this->config->get('abandonedcarts_path');
		$file = DIR_LANGUAGE . $target .'.php';

		if (file_exists($file)) {
			$_ = array();
			require($file);
			$data = array_merge($data, $_);
			return $data;
		}

		return $this->load->language($this->config->get('abandonedcarts_path').'.php');
	}
	
	public function getTotalRegisteredCustomers() {
		$TotalRegisteredCustomers = $this->db->query("SELECT count(*) as `registered` FROM `" . DB_PREFIX . "abandonedcarts` WHERE `customer_info` LIKE '%email%'");
        
		return $TotalRegisteredCustomers->row['registered'];
	}
	
	public function getTotalCustomers() {
		$TotalCustomers = $this->db->query("SELECT count(*) as `count` FROM `" . DB_PREFIX . "abandonedcarts`"); 
        
		return $TotalCustomers->row['count'];
	}
	
	public function getMostVisitedPages() {
		$Pages = $this->db->query("SELECT `last_page`, count(`last_page`) as count FROM `" . DB_PREFIX . "abandonedcarts` GROUP BY `last_page` ORDER BY count DESC LIMIT 15"); 
        
		return $Pages->rows;
	}
	
}
