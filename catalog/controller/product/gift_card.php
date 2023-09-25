<?php
class ControllerProductGiftCard extends Controller {
	private $error = array();

	public function index() {
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->language('product/gift_card');

		$voucher_theme_id = isset($this->request->get['voucher_theme_id']) ? $this->request->get['voucher_theme_id'] : 0;

		$this->load->model('extension/total/voucher_theme');
		$voucher_info = $this->model_extension_total_voucher_theme->getVoucherTheme($voucher_theme_id);

		$this->document->setTitle($this->language->get('heading_title'));

		if (!isset($this->session->data['vouchers'])) {
			$this->session->data['vouchers'] = array();
		}

		$data['action'] = $this->url->link('product/gift_card', 'voucher_theme_id='.$voucher_theme_id, true);

		if(isset($this->session->data['success_gift_card'])) {
			$data['success_message'] = $this->session->data['success_gift_card'];
			unset($this->session->data['success_gift_card']);
		}
		else {
			$data['success_message'] = false;
		}

		$data['success_title'] = $this->language->get('text_success');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$desc = sprintf($this->language->get('text_for'), $this->currency->format($voucher_info['amount'], $this->session->data['currency']), $this->request->post['to_name']);
			$success_desc = sprintf($this->language->get('text_success_message'), $this->currency->format($voucher_info['amount'], $this->session->data['currency']), $this->request->post['to_name']);
			$this->session->data['vouchers'][mt_rand()] = array(
				'description'      => $desc,
				'to_name'          => $this->request->post['to_name'],
				'to_email'         => $this->request->post['to_email'],
				'from_name'        => $this->request->post['from_name'],
				'from_email'       => $this->request->post['from_email'],
				'voucher_theme_id' => $voucher_theme_id,
				'message'          => $this->request->post['message'],
				'amount'           => $this->currency->convert($voucher_info['amount'], $this->session->data['currency'], $this->config->get('config_currency')),
				'image' => $voucher_info['image'],
				'delivery_date' => $this->request->post['delivery_date'],
				'headerline' => $this->request->post['headerline'],
			);

			$this->session->data['success_gift_card'] = $success_desc;

			$this->response->redirect($data['action']);
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_gift_card_category'),
			'href' => $this->url->link('product/gift_card_category', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('product/gift_card', '', true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_description'] = $this->language->get('text_description');
		$data['text_agree'] = $this->language->get('text_agree');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_from'] = $this->language->get('text_from');
		$data['text_dd'] = $this->language->get('text_dd');

		$data['entry_to_name'] = $this->language->get('entry_to_name');
		$data['entry_to_email'] = $this->language->get('entry_to_email');
		$data['entry_from_name'] = $this->language->get('entry_from_name');
		$data['entry_from_email'] = $this->language->get('entry_from_email');
		$data['entry_theme'] = $this->language->get('entry_theme');
		$data['entry_message'] = $this->language->get('entry_message');
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_headerline'] = $this->language->get('entry_headerline');

		$data['help_message'] = $this->language->get('help_message');
		$data['help_amount'] = sprintf($this->language->get('help_amount'), $this->currency->format($this->config->get('config_voucher_min'), $this->session->data['currency']), $this->currency->format($this->config->get('config_voucher_max'), $this->session->data['currency']));

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_cart'] = $this->language->get('button_cart');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['to_name'])) {
			$data['error_to_name'] = $this->error['to_name'];
		} else {
			$data['error_to_name'] = '';
		}

		if (isset($this->error['to_email'])) {
			$data['error_to_email'] = $this->error['to_email'];
		} else {
			$data['error_to_email'] = '';
		}

		if (isset($this->error['from_name'])) {
			$data['error_from_name'] = $this->error['from_name'];
		} else {
			$data['error_from_name'] = '';
		}

		if (isset($this->error['from_email'])) {
			$data['error_from_email'] = $this->error['from_email'];
		} else {
			$data['error_from_email'] = '';
		}

		if (isset($this->error['theme'])) {
			$data['error_theme'] = $this->error['theme'];
		} else {
			$data['error_theme'] = '';
		}

		if (isset($this->error['amount'])) {
			$data['error_amount'] = $this->error['amount'];
		} else {
			$data['error_amount'] = '';
		}

		if (isset($this->error['delivery_date'])) {
			$data['error_delivery_date'] = $this->error['delivery_date'];
		} else {
			$data['error_delivery_date'] = '';
		}

		if (isset($this->request->post['to_name'])) {
			$data['to_name'] = $this->request->post['to_name'];
		} else {
			$data['to_name'] = '';
		}

		if (isset($this->request->post['to_email'])) {
			$data['to_email'] = $this->request->post['to_email'];
		} else {
			$data['to_email'] = '';
		}

		if (isset($this->request->post['from_name'])) {
			$data['from_name'] = $this->request->post['from_name'];
		} elseif ($this->customer->isLogged()) {
			$data['from_name'] = $this->customer->getFirstName() . ' '  . $this->customer->getLastName();
		} else {
			$data['from_name'] = '';
		}

		if (isset($this->request->post['from_email'])) {
			$data['from_email'] = $this->request->post['from_email'];
		} elseif ($this->customer->isLogged()) {
			$data['from_email'] = $this->customer->getEmail();
		} else {
			$data['from_email'] = '';
		}

		$data['voucher_themes'] = $this->model_extension_total_voucher_theme->getVoucherThemes(array('type' => 2));

		//$data['voucher_theme_id'] = $voucher_theme_id;

		if (isset($this->request->post['message'])) {
			$data['message'] = $this->request->post['message'];
		} else {
			$data['message'] = '';
		}

		if (isset($this->request->post['headerline'])) {
			$data['headerline'] = $this->request->post['headerline'];
		} else {
			$data['headerline'] = '';
		}

		// if (isset($this->request->post['amount'])) {
		// 	$data['amount'] = $this->request->post['amount'];
		// } else {
		// 	$data['amount'] = $this->currency->format($this->config->get('config_voucher_min'), $this->config->get('config_currency'), false, false);
		// }

		//$data['amount'] = $this->currency->format($voucher_info['amount'], $this->config->get('config_currency'), false, false);

		// if (isset($this->request->post['agree'])) {
		// 	$data['agree'] = $this->request->post['agree'];
		// } else {
		// 	$data['agree'] = false;
		// }

		$data['name'] = $voucher_info['name'];

		$theme = $this->config->get('config_theme');
		$width = $this->config->get($theme . '_image_product_width');
		$height = $this->config->get($theme . '_image_product_height');	
		$data['width'] = $width;

		$this->load->model('tool/image');
		$data['image'] = $this->model_tool_image->resize('no_image.png', $width, $height);

		if (is_file(DIR_IMAGE . $voucher_info['image']) && $voucher_info['image']) 
			//$data['image'] = $this->model_tool_image->resize($voucher_info['image'], $width, $height);
			$data['image'] = 'image/'.$voucher_info['image'];

		$data['description'] = strip_tags(html_entity_decode($voucher_info['description'], ENT_QUOTES, 'UTF-8'));

		$data['price'] = false;
		$data['price_num'] = false;
		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$data['price'] = $this->currency->format($voucher_info['amount'], $this->session->data['currency']);
			$data['price_num'] =  '$'.round($voucher_info['amount'], 0);
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('product/gift_card', $data));
	}

	protected function validate() {
		if ((utf8_strlen($this->request->post['delivery_date']) < 1) || (utf8_strlen($this->request->post['delivery_date']) > 10)) {
			$this->error['delivery_date'] = $this->language->get('error_delivery_date');
		}

		if ((utf8_strlen($this->request->post['to_name']) < 1) || (utf8_strlen($this->request->post['to_name']) > 64)) {
			$this->error['to_name'] = $this->language->get('error_to_name');
		}

		if ((utf8_strlen($this->request->post['to_email']) > 96) || !filter_var($this->request->post['to_email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['to_email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['from_name']) < 1) || (utf8_strlen($this->request->post['from_name']) > 64)) {
			$this->error['from_name'] = $this->language->get('error_from_name');
		}

		if ((utf8_strlen($this->request->post['from_email']) > 96) || !filter_var($this->request->post['from_email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['from_email'] = $this->language->get('error_email');
		}

		// if (!isset($this->request->post['voucher_theme_id'])) {
		// 	$this->error['theme'] = $this->language->get('error_theme');
		// }

		// if (($this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency')) < $this->config->get('config_voucher_min')) || ($this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency')) > $this->config->get('config_voucher_max'))) {
		// 	$this->error['amount'] = sprintf($this->language->get('error_amount'), $this->currency->format($this->config->get('config_voucher_min'), $this->session->data['currency']), $this->currency->format($this->config->get('config_voucher_max'), $this->session->data['currency']));
		// }

		// if (!isset($this->request->post['agree'])) {
		// 	$this->error['warning'] = $this->language->get('error_agree');
		// }

		return !$this->error;
	}
}
