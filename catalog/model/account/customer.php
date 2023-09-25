<?php
	class ModelAccountCustomer extends Model {
		
		public function addCustomer($data) {

			if(!isset($data['newsletter'])) $data['newsletter'] = 0;

			if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $data['customer_group_id'];
			}
			else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
			
			$this->load->model('account/customer_group');
			
			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? json_encode($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");
			
			$customer_id = $this->db->getLastId();
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', unit_no = '" . $this->db->escape($data['unit_no']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? json_encode($data['custom_field']['address']) : '') . "'");
			
			$address_id = $this->db->getLastId();

			// Clear Thinking: mailchimp_integration.xml
			if (!empty($data['newsletter'])) {
				if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
				$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session, $this->url);
				$mailchimp_integration->send(array_merge($data, array('customer_id' => $customer_id, 'customer_newsletter' => 0)));
			}
			// end: mailchimp_integration.xml
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
			
			$this->load->language('mail/customer');
			
			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			
			$message = sprintf($this->language->get('text_welcome'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
			
			if (!$customer_group_info['approval']) {
				$message .= $this->language->get('text_login') . "\n";
			}
			else {
				$message .= $this->language->get('text_approval') . "\n";
			}
			
			$message .= $this->url->link('account/login', '', true) . "\n\n";
			$message .= $this->language->get('text_services') . "\n\n";
			$message .= $this->language->get('text_thanks') . "\n";
			$message .= html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
			
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
			$mail->setTo($data['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			// $mail->send();

			// Pro email Template Mod
			if($this->config->get('pro_email_template_status')){
				$this->load->model('tool/pro_email');

				$email_params = array(
					'type' => 'customer.register',
					'mail' => $mail,
					'data' => $data,
					'customer_id' => isset($customer_id) ? $customer_id : false,
					'conditions' => array('approval' => $customer_group_info['approval']),
				);
				
				$this->model_tool_pro_email->generate($email_params);
			}
			else{
				$mail->send();
			}
			// End Pro email Template Mod
			
			// Send to main admin email if new account email is enabled
			if (in_array('account', (array)$this->config->get('config_mail_alert'))) {
				$message  = $this->language->get('text_signup') . "\n\n";
				$message .= $this->language->get('text_website') . ' ' . html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8') . "\n";
				$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
				$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
				$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
				$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
				$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";
				
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
				$mail->setText($message);
				//$mail->send();

				// Pro email Template Mod
				if($this->config->get('pro_email_template_status')){
					$this->load->model('tool/pro_email');

					$email_params = array(
						'type' => 'admin.customer.register',
						'mail' => $mail,
						'reply_to' => $data['email'],
						'customer_id' => isset($customer_id) ? $customer_id : false,
						'data' => array(),
					);
					
					$this->model_tool_pro_email->generate($email_params);
				}
				else{
					$mail->send();
				}
				
				// Send to additional alert emails if new account email is enabled
				$emails = explode(',', $this->config->get('config_alert_email'));
				
				foreach ($emails as $email) {
					if (utf8_strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$mail->setTo($email);
						//$mail->send();

						// Pro email Template Mod
						if($this->config->get('pro_email_template_status')){
							$this->load->model('tool/pro_email');

							$email_params = array(
								'type' => 'admin.customer.register',
								'mail' => $mail,
								'reply_to' => $data['email'],
								'customer_id' => isset($customer_id) ? $customer_id : false,
								'data' => array(),
							);
							
							$this->model_tool_pro_email->generate($email_params);
						}
						else{
							$mail->send();
						}
					}
				}
			}
			
			return $customer_id;
		}
		
		public function editCustomer($data) {

			if(!isset($data['newsletter'])) $data['newsletter'] = 0;
		
			// Clear Thinking: mailchimp_integration.xml
			if ($this->customer->getNewsletter()) {
				if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
				$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session, $this->url);
				$mailchimp_integration->send(array_merge($data, array('newsletter' => 1, 'customer_id' => $this->customer->getId())));
			}
			// end: mailchimp_integration.xml

			$customer_id = $this->customer->getId();
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "' WHERE customer_id = '" . (int)$customer_id . "'");
		
			$this->editNewsletter($data['newsletter']);
		}
		
		public function editPassword($email, $password) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		}
		
		public function editCode($email, $code) {
			$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		}
		
		public function editNewsletter($newsletter) {
			// Clear Thinking: mailchimp_integration.xml
			if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
			$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session, $this->url);
			$mailchimp_integration->send(array('newsletter' => $newsletter, 'customer_id' => $this->customer->getId()));
			// end: mailchimp_integration.xml
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		}
		
		public function getCustomer($customer_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row;
		}
		
		public function getCustomerByEmail($email) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			
			return $query->row;
		}
		
		public function getCustomerByCode($code) {
			$query = $this->db->query("SELECT customer_id, firstname, lastname, email FROM `" . DB_PREFIX . "customer` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");
			
			return $query->row;
		}
		
		public function getCustomerByToken($token) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");
			
			return $query->row;
		}
		
		public function getTotalCustomersByEmail($email) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			
			return $query->row['total'];
		}
		
		public function getRewardTotal($customer_id) {
			$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row['total'];
		}
		
		public function getIps($customer_id) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->rows;
		}
		
		public function addLoginAttempt($email) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
			
			if (!$query->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_login SET email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
			}
			else {
				$this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$query->row['customer_login_id'] . "'");
			}
		}
		
		public function getLoginAttempts($email) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			
			return $query->row;
		}
		
		public function deleteLoginAttempts($email) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		}

		public function getCustomersByCustomerGroup($customer_group_id) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_group_id = '" . (int)$customer_group_id . "'");

			return $query->rows;
		}
		
		public function getRewardTotalByCustomerId($customer_id, $start_date, $end_date) {
			$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "' AND (date_added BETWEEN '".$this->db->escape($start_date . ' 00:00:00'). "' AND '".$this->db->escape($end_date . ' 23:59:59'). "' )");

			return $query->row['total'];
		}

		public function addReward($customer_id, $points, $description) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '0', points = '" . (int)$points*-1 . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");
		}
	}
