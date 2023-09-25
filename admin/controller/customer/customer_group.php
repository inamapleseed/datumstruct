<?php
class ControllerCustomerCustomerGroup extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('customer/customer_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer_group');

		$this->getList();
	}

	public function add() {
		$this->load->language('customer/customer_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_customer_group->addCustomerGroup($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('customer/customer_group', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('customer/customer_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_customer_group->editCustomerGroup($this->request->get['customer_group_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('customer/customer_group', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('customer/customer_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer_group');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_group_id) {
				$this->model_customer_customer_group->deleteCustomerGroup($customer_group_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('customer/customer_group', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'cgd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('customer/customer_group/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('customer/customer_group/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['customer_groups'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$customer_group_total = $this->model_customer_customer_group->getTotalCustomerGroups();

		$results = $this->model_customer_customer_group->getCustomerGroups($filter_data);

		foreach ($results as $result) {
			$data['customer_groups'][] = array(
				'customer_group_id' => $result['customer_group_id'],
				'name'              => $result['name'] . (($result['customer_group_id'] == $this->config->get('config_customer_group_id')) ? $this->language->get('text_default') : null),
				'sort_order'        => $result['sort_order'],
				'edit'              => $this->url->link('customer/customer_group/edit', 'token=' . $this->session->data['token'] . '&customer_group_id=' . $result['customer_group_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'] . '&sort=cgd.name' . $url, true);
		$data['sort_sort_order'] = $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'] . '&sort=cg.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_group_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_group_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_group_total - $this->config->get('config_limit_admin'))) ? $customer_group_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_group_total, ceil($customer_group_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/customer_group_list', $data));
	}

	protected function getForm() {

		$data['is_dev'] = $this->user->is_dev()?'':'hidden';

		$data['heading_title'] = $this->language->get('heading_title');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_reward'] = $this->language->get('tab_reward');
		$data['tab_discount'] = $this->language->get('tab_discount');

		$data['text_form'] = !isset($this->request->get['customer_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_cron_note'] = $this->language->get('text_cron_note');
		$data['text_not_same_dates'] = $this->language->get('text_not_same_dates');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_approval'] = $this->language->get('entry_approval');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['entry_earn'] = $this->language->get('entry_earn');
		$data['entry_spend'] = $this->language->get('entry_spend');
		$data['entry_amount'] = $this->language->get('entry_amount');

		$data['entry_start_date'] = $this->language->get('entry_start_date');
		$data['entry_end_date'] = $this->language->get('entry_end_date');
		$data['entry_clear_date'] = $this->language->get('entry_clear_date');
		$data['entry_important'] = $this->language->get('entry_important');


		$data['help_approval'] = $this->language->get('help_approval');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['customer_group_id'])) {
			$data['action'] = $this->url->link('customer/customer_group/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('customer/customer_group/edit', 'token=' . $this->session->data['token'] . '&customer_group_id=' . $this->request->get['customer_group_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['customer_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($this->request->get['customer_group_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['customer_group_description'])) {
			$data['customer_group_description'] = $this->request->post['customer_group_description'];
		} elseif (isset($this->request->get['customer_group_id'])) {
			$data['customer_group_description'] = $this->model_customer_customer_group->getCustomerGroupDescriptions($this->request->get['customer_group_id']);
		} else {
			$data['customer_group_description'] = array();
		}

		if (isset($this->request->post['approval'])) {
			$data['approval'] = $this->request->post['approval'];
		} elseif (!empty($customer_group_info)) {
			$data['approval'] = $customer_group_info['approval'];
		} else {
			$data['approval'] = '';
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($customer_group_info)) {
			$data['sort_order'] = $customer_group_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if (isset($this->request->post['reward_point_earn_rate'])) {
			$data['reward_point_earn_rate'] = $this->request->post['reward_point_earn_rate'];
		} elseif (!empty($customer_group_info)) {
			$data['reward_point_earn_rate'] = $customer_group_info['reward_point_earn_rate'];
		} else {
			$data['reward_point_earn_rate'] = 1;
		}

		if (isset($this->request->post['reward_point_step_spend'])) {
			$data['reward_point_step_spend'] = $this->request->post['reward_point_step_spend'];
		} elseif (!empty($customer_group_info)) {
			$data['reward_point_step_spend'] = $customer_group_info['reward_point_step_spend'];
		} else {
			$data['reward_point_step_spend'] = 100;
		}


		if (isset($this->request->post['reward_point_spend_rate'])) {
			$data['reward_point_spend_rate'] = $this->request->post['reward_point_spend_rate'];
		} elseif (!empty($customer_group_info)) {
			$data['reward_point_spend_rate'] = $customer_group_info['reward_point_spend_rate'];
		} else {
			$data['reward_point_spend_rate'] = 5;
		}

		// if (isset($this->request->post['start_date'])) {
		// 	$data['start_date'] = $this->request->post['start_date'];
		// } elseif (!empty($customer_group_info)) {
		// 	$data['start_date'] = $customer_group_info['start_date'];
		// } else {
		// 	$data['start_date'] = '';
		// }

		// if (isset($this->request->post['end_date'])) {
		// 	$data['end_date'] = $this->request->post['end_date'];
		// } elseif (!empty($customer_group_info)) {
		// 	$data['end_date'] = $customer_group_info['end_date'];
		// } else {
		// 	$data['end_date'] = '';
		// }

		// if (isset($this->request->post['clear_date'])) {
		// 	$data['clear_date'] = $this->request->post['clear_date'];
		// } elseif (!empty($customer_group_info)) {
		// 	$data['clear_date'] = $customer_group_info['clear_date'];
		// } else {
		// 	$data['clear_date'] = '';
		// }

		if (isset($this->request->post['reward_dates'])) {
			$data['reward_dates'] = $this->request->post['reward_dates'];
		} elseif (isset($this->request->get['customer_group_id'])) {
			$data['reward_dates'] = $this->model_customer_customer_group->getCustomerGroupRewardDates($this->request->get['customer_group_id']);
		} else {
			$data['reward_dates'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/customer_group_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'customer/customer_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['customer_group_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if(strlen( trim($this->request->post['reward_point_earn_rate']) ) == 0 || (int)$this->request->post['reward_point_earn_rate'] < 0){
			$this->request->post['reward_point_earn_rate'] = 0;
		}
		
		if(strlen( trim($this->request->post['reward_point_step_spend']) ) == 0 || (int)$this->request->post['reward_point_step_spend'] < 0){
			$this->request->post['reward_point_step_spend'] = 0;
		}
		
		if(strlen( trim($this->request->post['reward_point_spend_rate']) ) == 0 || (int)$this->request->post['reward_point_spend_rate'] < 0){
			$this->request->post['reward_point_spend_rate'] = 0;
		}
		

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'customer/customer_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		$this->load->model('customer/customer');

		foreach ($this->request->post['selected'] as $customer_group_id) {
			if ($this->config->get('config_customer_group_id') == $customer_group_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}

			$store_total = $this->model_setting_store->getTotalStoresByCustomerGroupId($customer_group_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}

			$customer_total = $this->model_customer_customer->getTotalCustomersByCustomerGroupId($customer_group_id);

			if ($customer_total) {
				$this->error['warning'] = sprintf($this->language->get('error_customer'), $customer_total);
			}
		}

		return !$this->error;
	}
}