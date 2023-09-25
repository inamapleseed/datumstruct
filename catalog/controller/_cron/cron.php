<?php
class ControllerCronCron extends Controller {

	public function clearCustomerReward() {

		$this->load->model('account/customer_group');

		$this->load->model('account/customer');

		$this->load->model('account/customer_group');

		$this->load->language('account/account');

		$customer_groups = $this->model_account_customer_group->getCustomerGroups();

		foreach ($customer_groups as $customer_group) {

			$today = date('Y-m-d');
			$customer_group_id = $customer_group['customer_group_id'];

			// Retrieves customer group reward point dates (start date, end date, clear date) by customer group
			$reward_dates = $this->model_account_customer_group->getCustomerGroupRewardDates($customer_group_id);

			foreach($reward_dates as $reward_date) {
				$start_date = $reward_date['start_date'];
				$end_date = $reward_date['end_date'];
				$clear_date = $reward_date['clear_date'];

				// $start_date = $customer_group['start_date'];
				// $end_date = $customer_group['end_date'];
				// $clear_date = $customer_group['clear_date'];

				if ($today == $clear_date) {
					/* clear reward from date1 to date2 for customer who is within the customer group */

					$customers = $this->model_account_customer->getCustomersByCustomerGroup($customer_group_id);

					foreach ($customers as $customer) {
						
						$customer_id = $customer['customer_id'];
						
						$points = $this->model_account_customer->getRewardTotalByCustomerId($customer_id, $start_date, $end_date);

						if ($points > 0) {
							$this->model_account_customer->addReward($customer_id, $points, $this->language->get('text_clear_reward'));
						}

					} /* foreach customer */

				} /* if today = clear date*/
			
			} /* foreach reward dates */

		} /* foreach customer */

	}

}
