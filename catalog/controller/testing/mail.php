<?php 
    class ControllerTestingMail extends controller{

        # Run / Trigger Function
        public function quickenquiry(){

            $this->load->model('checkout/enquiry');

            $enquiry_order_id = 4;

            $enquiry_info = $this->model_checkout_enquiry->getOrder($enquiry_order_id);

            // Comment
            $comment = '';
            if ($enquiry_info['comment']) {
                $comment = $enquiry_info['comment'] . "\n\n";
            }

            $enquiry_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_product WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "'");

            // HTML Mail
            $data = array();

            $data['download'] = '';

            if ($enquiry_info['payment_address_format']) {
                $format = $enquiry_info['payment_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no} {address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{unit_no}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $enquiry_info['payment_firstname'],
                'lastname'  => $enquiry_info['payment_lastname'],
                'company'   => $enquiry_info['payment_company'],
                'address_1' => $enquiry_info['payment_address_1'],
                'address_2' => $enquiry_info['payment_address_2'],
                'unit_no'   => $enquiry_info['payment_unit_no']?$enquiry_info['payment_unit_no'].', ':'',
                'city'      => $enquiry_info['payment_city'],
                'postcode'  => $enquiry_info['payment_postcode'],
                'zone'      => $enquiry_info['payment_zone'],
                'zone_code' => $enquiry_info['payment_zone_code'],
                'country'   => $enquiry_info['payment_country']
            );

            $data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            if ($enquiry_info['shipping_address_format']) {
                $format = $enquiry_info['shipping_address_format'];
            } else {
                $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{unit_no}{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
            }

            $find = array(
                '{firstname}',
                '{lastname}',
                '{company}',
                '{address_1}',
                '{address_2}',
                '{unit_no}',
                '{city}',
                '{postcode}',
                '{zone}',
                '{zone_code}',
                '{country}'
            );

            $replace = array(
                'firstname' => $enquiry_info['shipping_firstname'],
                'lastname'  => $enquiry_info['shipping_lastname'],
                'company'   => $enquiry_info['shipping_company'],
                'address_1' => $enquiry_info['shipping_address_1'],
                'address_2' => $enquiry_info['shipping_address_2'],
                'unit_no' 	=> $enquiry_info['shipping_unit_no']?$enquiry_info['shipping_unit_no'] .', ':'',
                'city'      => $enquiry_info['shipping_city'],
                'postcode'  => $enquiry_info['shipping_postcode'],
                'zone'      => $enquiry_info['shipping_zone'],
                'zone_code' => $enquiry_info['shipping_zone_code'],
                'country'   => $enquiry_info['shipping_country']
            );

            $data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

            $this->load->model('tool/upload');

            // Products
            $data['products'] = array();

            foreach ($enquiry_product_query->rows as $product) {
                $option_data = array();

                $enquiry_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquiry_order_option WHERE enquiry_order_id = '" . (int)$enquiry_order_id . "' AND enquiry_order_product_id = '" . (int)$product['enquiry_order_product_id'] . "'");

                foreach ($enquiry_option_query->rows as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = array(
                        'name'  => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                $data['products'][] = array(
                    'name'     => $product['name'],
                    'model'    => $product['model'],
                    'option'   => $option_data,
                    'quantity' => $product['quantity'],
                    'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']),
                    'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value'])
                );
            }
            
            // $this->testEnquiryConfirm($enquiry_info, $comment);

             $this->testAdminEnquiryOrderConfirm($enquiry_info, $comment);


        }

        #Test Functions
            private function testEnquiryUpdate($enquiry_info, $comment){
                $mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
				$mail->setTo($enquiry_info['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($enquiry_info['store_name'], ENT_QUOTES, 'UTF-8'));
				
				
				// $mail->send();

				// Pro email Template Mod
				if($this->config->get('pro_email_template_status')){
					$this->load->model('tool/pro_email');

					$email_params = array(
						'type' => 'enquiry.update',
						'mail' => $mail,
						'enquiry_order_info' => $enquiry_info,
						'enquiry_order_status_id' => (int)$enquiry_order_status_id,
						'enquiry_order_status_name' => $enquiry_status_query->num_rows ? $enquiry_status_query->row['name'] : '',
						'data' => array(
							'enquiry_order_status' => $enquiry_status_query->num_rows ? $enquiry_status_query->row['name'] : '',
							'message' => nl2br($comment),
						),
						'conditions' => array(
							'message' => $comment ? 1 : 0,
						),
					);
					
					$this->model_tool_pro_email->generate($email_params);
				}
				else{
					$mail->send();
				}
            }
            private function testAdminEnquiryOrderConfirm($enquiry_info, $comment){

                    $mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                    $mail->setSubject(html_entity_decode('testAdminEnquiryOrderConfirm', ENT_QUOTES, 'UTF-8'));
					$mail->setTo($this->config->get('config_email'));
					$mail->setFrom($this->config->get('config_email'));
                    $mail->setSender(html_entity_decode($enquiry_info['store_name'], ENT_QUOTES, 'UTF-8'));
                    
                    debug($enquiry_info);

					// Pro email Template Mod
					if($this->config->get('pro_email_template_status')){
						$this->load->model('tool/pro_email');

						$email_params = array(
						'type' => 'admin.enquiry.confirm',
						'mail' => &$mail,
						'reply_to' => $enquiry_info['email'],
						'enquiry_order_info' => $enquiry_info,
						'enquiry_order_status_id' => 2,
						'enquiry_order_comment' => nl2br($comment),
						);
						
						$this->model_tool_pro_email->generate($email_params);
					}
					else{
						$mail->send();
					}
            }


            private function testEnquiryConfirm($enquiry_info, $comment){
                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
                
    
                $mail->setFrom($this->config->get('config_email'));
                $mail->setTo($enquiry_info['email']);
                $mail->setSender(html_entity_decode($enquiry_info['store_name'], ENT_QUOTES, 'UTF-8'));
                /*
                $mail->setTo($enquiry_info['email']);
                
                $mail->setSubject(html_entity_decode('', ENT_QUOTES, 'UTF-8'));
                $mail->setHtml($this->load->view('mail/order', $data));
                $mail->setText($text);
                */
                // Pro email Template Mod
                if($this->config->get('pro_email_template_status')){
                    $this->load->model('tool/pro_email');
    
                    if (!empty($data['payment_instruction']) && strpos($enquiry_info['payment_code'],'xpayment.') !== false) {
                    $comment = $data['payment_instruction'];
                    }
                    
                    $email_params = array(
                        'type' => 'enquiry.confirm',
                        'mail' => $mail,
                        'enquiry_order_info' => $enquiry_info,
                        'enquiry_order_status_id' => 2,
                        'enquiry_order_comment' => nl2br($comment),
                    );
                    
                    $this->model_tool_pro_email->generate($email_params);
                }
                else{
                    $mail->send();
                }
            }
    }