<?php
class ControllerExtensionPaymentEnquiryCheckout extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm_enquiry');

		$data['text_loading'] = $this->language->get('text_loading');

		$data['continue'] = $this->url->link('enquiry/success');

		return $this->load->view('extension/payment/enquiry_checkout', $data);
	}

	public function confirm() {
		$this->load->model('checkout/enquiry');

		$this->model_checkout_enquiry->addOrderHistory($this->session->data['enquiry_order_id'], 2);
	}
}