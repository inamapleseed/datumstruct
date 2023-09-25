<?php
	class ControllerCheckoutSuccess extends Controller {
		public function index() {
			
			$this->facebookcommonutils = new FacebookCommonUtils();
			$products = $this->cart->getProducts();
			if (sizeof($products)) {
				$params = new DAPixelConfigParams(array(
				'eventName' => 'Purchase',
				'products' => $products,
				'currency' => $this->currency,
				'currencyCode' => $this->session->data['currency'],
				'hasQuantity' => true));
				$facebook_pixel_event_params_FAE =
				$this->facebookcommonutils->getDAPixelParamsForProducts($params);
				// stores the pixel params in the session
				$this->request->post['facebook_pixel_event_params_FAE'] =
				addslashes(json_encode($facebook_pixel_event_params_FAE));
				// update the product availability on Facebook
				$this->facebookcommonutils->updateProductAvailability(
				$this->registry,
				$products);
			}

			// if (isset($this->request->get['buy-humanscale-item']) && $this->request->get['buy-humanscale-item'] == 'humanscale') {
			// 	$data['humanscale'] = true;
			// 	$urlAppend = '&buy-humanscale-item=humanscale'; 
			// } else{
			// 	$data['humanscale'] = false;
			// 	$urlAppend = '';
			// }

			// debug($data['humanscale']);
			
			$this->load->language('checkout/success');
			
			if (isset($this->session->data['order_id'])) {

				$this->session->data['latest_order'] = $this->session->data['order_id'];

				$this->cart->clear();
				
				// Add to activity log
				if ($this->config->get('config_customer_activity')) {
					$this->load->model('account/activity');
					
					if ($this->customer->isLogged()) {
						$activity_data = array(
						'customer_id' => $this->customer->getId(),
						'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
						'order_id'    => $this->session->data['order_id']
						);
						
						$this->model_account_activity->addActivity('order_account', $activity_data);
						} else {
						$activity_data = array(
						'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
						'order_id' => $this->session->data['order_id']
						);
						
						$this->model_account_activity->addActivity('order_guest', $activity_data);
					}
				}
				
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['guest']);
				
				unset($this->session->data['order_comment']);
				unset($this->session->data['delivery_date']);
				unset($this->session->data['delivery_time']);
				unset($this->session->data['survey']);
				unset($this->session->data['shipping_address']);
				unset($this->session->data['payment_address']);
				
				unset($this->session->data['comment']);
				unset($this->session->data['order_id']);
				unset($this->session->data['coupon']);
				unset($this->session->data['reward']);
				unset($this->session->data['voucher']);
				unset($this->session->data['vouchers']);
				unset($this->session->data['totals']);
			}
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$data['breadcrumbs'] = array();
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
			);
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_basket'),
			'href' => $this->url->link('checkout/cart')
			);
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
			);
			
			$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
			);
			
			$data['heading_title'] = $this->language->get('heading_title');
			
			if ($this->customer->isLogged()) {
				$data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', true), $this->url->link('account/order', '', true), $this->url->link('account/download', '', true), $this->url->link('information/contact'));
				} else {
				$data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
			}
			
			$data['button_continue'] = $this->language->get('button_continue');

			$data['button_to_home'] = $this->language->get('button_to_home');
			
			$data['continue'] = $this->url->link('common/home');
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');


			if (isset($this->session->data['humanscaleSession']) && $this->session->data['humanscaleSession'] == 2) {
				$data['humanscaleSession'] = $this->session->data['humanscaleSession'];
			// debug($data['humanscale']);
			}else{
				$data['humanscaleSession'] = '';	
			}


			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			
			/* AbandonedCarts - Begin */
			$this->load->model('setting/setting');
			
			$abandonedCartsSettings = $this->model_setting_setting->getSetting('abandonedcarts', $this->config->get('store_id'));
			
			if (isset($abandonedCartsSettings['abandonedcarts']['Enabled']) && $abandonedCartsSettings['abandonedcarts']['Enabled']=='yes') { 
				if (isset($this->session->data['abandonedCart_ID']) & !empty($this->session->data['abandonedCart_ID'])) {
					$id = $this->session->data['abandonedCart_ID'];
					} else if ($this->customer->isLogged()) {
					$id = (!empty($this->session->data['abandonedCart_ID'])) ? $this->session->data['abandonedCart_ID'] : $this->customer->getEmail();
					} else {
					$id = (!empty($this->session->data['abandonedCart_ID'])) ? $this->session->data['abandonedCart_ID'] : session_id();
				}
				
				$exists = $this->db->query("SELECT * FROM `" . DB_PREFIX . "abandonedcarts` WHERE `restore_id` = '$id' AND `ordered`=0");
				if (!empty($exists->rows)) {
					foreach ($exists->rows as $row) {
						if ($row['notified']!=0) {
							$this->db->query("UPDATE `" . DB_PREFIX . "abandonedcarts` SET `ordered` = 1 WHERE `id` = '".$row['id']."'");
							} else if ($row['notified']==0) {
							$this->db->query("DELETE FROM `" . DB_PREFIX . "abandonedcarts` WHERE `restore_id` = '$id' AND `id`='".$row['id']."'");
						}
					}
					$this->session->data['abandonedCart_ID']='';
					unset($this->session->data['abandonedCart_ID']);
				}
			}
			/* AbandonedCarts - End */
			
			$data['header'] = $this->load->controller('common/header');

			$data['print_receipt'] = $this->language->get('print_receipt');

			$data['print_receipt_url'] = $this->url->link('account/print');
			
			$latest_order = $this->session->data['latest_order'];

			$email = '';
			if($latest_order){
				$query = $this->db->query('SELECT email FROM `' . DB_PREFIX . 'order` WHERE order_id="'.(int)$latest_order.'"');
				if($query->num_rows){
					$email = $query->row['email'];
				}
			}

			$data['text_order_numrber_is'] = sprintf($this->language->get('text_order_numrber_is'), $this->session->data['latest_order'], '');

			$data['text_you_will_receive_an_email_confirmation_shortly_at'] = sprintf($this->language->get('text_you_will_receive_an_email_confirmation_shortly_at'), $email, '');
			
			$this->response->setOutput($this->load->view('checkout/success', $data));
		}
	}	