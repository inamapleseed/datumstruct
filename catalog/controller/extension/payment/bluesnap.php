<?php
class ControllerExtensionPaymentBluesnap extends Controller {
	
	public function __construct($registry) {
                parent::__construct($registry);
                $this->registry = $registry;
                require_once(DIR_SYSTEM . "library/payment/bluesnap.php");
                $this->bluesnap = new Bluesnap($registry);
        }

	public function form() { 
		$this->load->language('extension/payment/bluesnap');
		$data['bluesnap_url'] = $this->bluesnap->get_url();
		$data['bluesnap_config_error'] = 0;

		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['placeholder_firstname'] = $this->language->get('placeholder_firstname');
		$data['error_firstname'] = $this->language->get('error_firstname');

		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['placeholder_lastname'] = $this->language->get('placeholder_lastname');
		$data['error_lastname'] = $this->language->get('error_lastname');

		$data['entry_card_number'] = $this->language->get('entry_card_number');
		$data['placeholder_card_number'] = $this->language->get('placeholder_card_number');
		$data['error_card_number'] = $this->language->get('error_card_number');

		$data['entry_expiry_date'] = $this->language->get('entry_expiry_date');
		$data['placeholder_expiry_date'] = $this->language->get('placeholder_expiry_date');
		$data['error_expiry_date'] = $this->language->get('error_expiry_date');

		$data['entry_security_code'] = $this->language->get('entry_security_code');
		$data['placeholder_security_code'] = $this->language->get('placeholder_security_code');
		$data['error_security_code'] = $this->language->get('error_security_code');

		$data['button_pay_now']	= $this->language->get('button_pay_now');
		try {
			unset($this->session->data['bluesnap_hosted_payments_field']);
			$pfToken = $this->bluesnap->get_payment_field_token();
			$this->session->data['bluesnap_hosted_payments_field'] 	= $pfToken;
			$this->session->data['bluesnap_fraud_session_id']	= $this->bluesnap->generate_fraud_session_id();
			$data['bluesnap_hosted_payments_field'] = $pfToken;
			$data['bluesnap_fraud_session_id']	= $this->session->data['bluesnap_fraud_session_id'];
		} catch (Exception $e) {
			$data['bluesnap_config_error'] = 1;
			$data['bluesnap_config_error_message'] =  $this->language->get("bluesnap_config_error_message");
		}
		$data['continue'] = $this->url->link('checkout/success');

		$this->response->setOutput($this->load->view('extension/payment/bluesnap_form', $data));
	}

	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');
		$this->load->language('extension/payment/bluesnap');
		$data['button_confirm'] = $this->language->get('button_confirm_bluesnap');
		$data['bluesnap_url'] = $this->bluesnap->get_url();
		return $this->load->view('extension/payment/bluesnap', $data);
	}

	public function confirm() {
		$this->load->language('extension/payment/bluesnap');
		$result_code = 0;
		$result_msg_code = '';
		$result_msg = '';
		$redirect_url = '';
		if ($this->session->data['payment_method']['code'] != 'bluesnap') {
			$result_code = 1;
			$result_msg_code = "MB-BP-001";
			$result_msg = $this->language->get('error_not_bluesnap_payment_method');
		} else if (!isset($this->session->data['bluesnap_hosted_payments_field']) || !isset($this->session->data['bluesnap_fraud_session_id'])) {
			$result_code = 1;
			$result_msg_code = "MB-BP-002";
			$result_msg = $this->language->get('error_not_bluesnap_hosted_payments_field_missing');
		} else if (new DateTime() > new DateTime($this->session->data['bluesnap_hosted_payments_field']['EXPIRY_DATE_TIME'])) {
			$result_code = 1;
			$result_msg_code = "MB-BP-003";
			$result_msg = $this->language->get('error_bluesnap_hosted_payment_field_expired');
			$redirect_url = $this->url->link('checkout/checkout');
		} else if (
			!isset($this->request->post['ccType']) || strlen($this->request->post['ccType']) == 0
			|| !isset($this->request->post['last4Digits']) || strlen($this->request->post['last4Digits']) == 0
			|| !isset($this->request->post['expiryDate']) || strlen($this->request->post['expiryDate']) == 0
		) {
			$result_code = 1;
			$result_msg_code = "MB-BP-004";
			$result_msg = $this->language->get('error_missing_credit_card_details'); 
		} else if ( !isset($this->request->post['cardholderFirstName']) || strlen($this->request->post['cardholderFirstName']) == 0) {
			$result_code = 1;
			$result_msg_code = "MB-BP-005";
			$result_msg = $this->language->get('error_firstname_on_card_missing');
		}  else if ( !isset($this->request->post['cardholderLastName']) || strlen($this->request->post['cardholderLastName']) == 0) {
			$result_code = 1;
			$result_msg_code = "MB-BP-006";
			$result_msg = $this->language->get('error_lastname_on_card_missing');
		} else if (!isset($this->session->data['order_id'])) {
			$result_code = 1;		
			$result_msg_code = "MB-BP-007";
			$result_msg = $this->language->get('error_orderid_not_found');
		} else { 
			$hosted_payments_field = $this->session->data['bluesnap_hosted_payments_field'];
			$fraud_session_id = $this->session->data['bluesnap_fraud_session_id'];
			$ccType = $this->request->post['ccType'];
			$last4Digits = $this->request->post['last4Digits'];
			$expiryDate = $this->request->post['expiryDate'];
			$order_id = $this->session->data['order_id'];
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);
			$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE code='total' and order_id = '" . (int)$order_id . "' ORDER BY sort_order ASC");
			$order_total = $order_total_query->row['value'];
			$credit_card_info = $this->request->post;
			$result = $this->bluesnap->auth_capture($order_info, $order_total, $hosted_payments_field, $fraud_session_id, $credit_card_info);
			$result_code = $result['result_code'];
			$result_msg = $result['result_msg'];
			$result_msg_code = $result['result_msg_code'];
			if ($result_code == 0)
			{
				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('bluesnap_order_status_id'));
				unset($this->session->data['bluesnap_hosted_payments_field']);
				$redirect_url =  $this->url->link('checkout/success');
			}
		}

		$json = array (
			'result_code' => $result_code,
			'result_msg_code' => $result_msg_code, 
			'result_msg' => $result_msg,
		);
		if ($redirect_url != '')
			$json['redirect_url'] = $redirect_url;
		$this->response->addHeader('Content-Type: application/json');
                $this->response->setOutput(json_encode($json));
	}
}
