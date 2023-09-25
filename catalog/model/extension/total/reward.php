<?php
class ModelExtensionTotalReward extends Model {
	public function getTotal($total) {
		if (isset($this->session->data['reward'])) {
			$this->load->language('extension/total/reward');

			$points = $this->customer->getRewardPoints();

			if ($this->session->data['reward'] <= $points) {

				$this->load->model('extension/total/reward');

				$discount_total = 0;

				$customer_group_id = $this->customer->getGroupId();

				$reward_info = $this->model_extension_total_reward->getRewardInfoByCustomerGroup($customer_group_id);

				if($reward_info){

					$points_in_use = $this->session->data['reward'];

					$one_per_point = $points_in_use / $reward_info['reward_point_step_spend'];

					$points_to_cost = $reward_info['reward_point_spend_rate'] * $one_per_point;

					$total['totals'][] = array(
						'code'       => 'reward',
						'title'      => sprintf($this->language->get('text_reward'), $this->session->data['reward']),
						'value'      => -$points_to_cost,
						'sort_order' => $this->config->get('reward_sort_order')
					);
	
					$total['total'] -= $points_to_cost;
				}
			}
		}
	}

	public function confirm($order_info, $order_total) { 
		$this->load->language('extension/total/reward');

		$points = 0;

		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');

		if ($start && $end) {
			$points = substr($order_total['title'], $start, $end - $start);
		}

		$this->load->model('account/customer');

		if ($this->model_account_customer->getRewardTotal($order_info['customer_id']) >= $points) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$order_info['customer_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', description = '" . $this->db->escape(sprintf($this->language->get('text_order_id'), (int)$order_info['order_id'])) . "', points = '" . (float)-$points . "', date_added = NOW()");
		} else {
			return $this->config->get('config_fraud_status_id');
		}
	}

	public function unconfirm($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "' AND points < 0");
	}

	public function getRewardInfoByCustomerGroup($customer_group_id=0){
		$query = $this->db->query('SELECT reward_point_earn_rate, reward_point_step_spend, reward_point_spend_rate FROM `' . DB_PREFIX . 'customer_group` WHERE customer_group_id="'. (int)$customer_group_id . '"');
		return $query->row;
	}
}
