<?php
class ControllerExtensionTotalReward extends Controller {
	public function index() {

		$this->load->model('extension/total/reward');

		$points = $this->customer->getRewardPoints();

		$subtotal = floor($this->cart->getSubTotal());

		$customer_group_id = $this->customer->getGroupId();

		$reward_info = $this->model_extension_total_reward->getRewardInfoByCustomerGroup($customer_group_id);

		if ($points && $subtotal && $this->config->get('reward_status') && $reward_info) {

			$rule_min = $reward_info['reward_point_step_spend'];

			$step = floor($points / $rule_min);

			$max = $step * $rule_min;

			if( $points < $rule_min ){
				return; exit;
			}

			$this->load->language('extension/total/reward');

			$data['heading_title'] = sprintf($this->language->get('heading_title'), $points);

			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_reward'] = sprintf($this->language->get('entry_reward'), $max);

			$data['button_reward'] = $this->language->get('button_reward');

			if (isset($this->session->data['reward'])) {
				$data['reward'] = $this->session->data['reward'];
			} else {
				$data['reward'] = '';
			}

			return $this->load->view('extension/total/reward', $data);

		}
	}

	public function reward() {

		$this->load->model('extension/total/reward');

		$this->load->language('extension/total/reward');

		$json = array();

		$points = $this->customer->getRewardPoints();

		$customer_group_id = $this->customer->getGroupId();

		$subtotal = floor($this->cart->getSubTotal());

		$reward_info = $this->model_extension_total_reward->getRewardInfoByCustomerGroup($customer_group_id);

		$max = 0;

		if( !$reward_info ){
			$json['error'] = $this->language->get('error_invalid');
		}
		else{
			$rule_min = $reward_info['reward_point_step_spend'];

			$step = floor($points / $rule_min);

			$max = $step * $rule_min;

			if( $rule_min > $this->request->post['reward'] ){
				$json['error'] = sprintf($this->language->get('error_min'), $rule_min);
			}

		}

		// No key in / all empty space
		if ( isset($this->request->post['reward']) && trim($this->request->post['reward']) == '') {
			$json['error'] = $this->language->get('error_reward');
		}

		// If key in more than available
		if ($this->request->post['reward'] > $points) {
			$json['error'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
		}

		if  ( $max && $this->request->post['reward'] > $max) {
			$json['error'] = sprintf($this->language->get('error_maximum'), $points_total);
		}

		if ($max && !$json) {
			$this->session->data['reward'] = abs($this->request->post['reward']);

			$this->session->data['success'] = $this->language->get('text_success');

			if (isset($this->request->post['redirect'])) {
				$json['redirect'] = $this->url->link($this->request->post['redirect']);
			} else {
				$json['redirect'] = $this->url->link('checkout/cart');	
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
