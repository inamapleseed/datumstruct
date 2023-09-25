<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		/* extension bganycombi - Buy Any Get Any Product Combination Pack */
		$data['bganycombi_module'] = $this->load->controller('extension/bganycombi');
		// Echo mailchimp
		$data['mailchimp'] = ''	;
		if ($this->config->get('mailchimp_integration_status')) $data['mailchimp'] = $this->load->controller('module/mailchimp_integration');
			
		$this->load->language('common/footer');

		$data['scripts'] = $this->document->getScripts('footer');

		$data['text_address'] = $this->language->get('text_address');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');

		$data['store']		= $this->config->get('config_name'); // In Store Tab - Store Name
		$data['address']	= nl2br($this->config->get('config_address'));
		$data['telephone']	= $this->config->get('config_telephone');
		$data['fax']		= $this->config->get('config_fax');
		$data['email']		= $this->config->get('config_email');

		$data['text_information'] = $this->language->get('text_information');
		$data['text_service'] = $this->language->get('text_service');
		$data['text_extra'] = $this->language->get('text_extra');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_sitemap'] = $this->language->get('text_sitemap');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_special'] = $this->language->get('text_special');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_fcs'] = $this->language->get('text_fcs');

		$this->language->load('extension/module/news_latest');
		$data['blog_url'] = $this->url->link('news/ncategory');
		$data['blog_name'] = $this->language->get('text_blogpage');
		
		$this->load->model('catalog/information');

		if (isset($this->request->get['buy-humanscale-item']) && $this->request->get['buy-humanscale-item'] == 'humanscale') {
				$data['humanscale'] = true; 
			} else{
				$data['humanscale'] = false;
			}

		// if (isset($this->session->data['humanscale'])) {
		// 	$data['humanscale'] = $this->session->data['humanscale'];
		// 	// debug($data['humanscale']);
		// }

		
		$theme = $this->config->get('config_theme');
		$menu_id = $this->config->get($theme . "_footer");

		// echo $product_info;
		$data['menu'] = $this->load->controller('common/menu', $menu_id);

		$data['testimonial'] = array(
			'title' => 'Testimonials',
			'href'  => $this->url->link('testimonial/testimonial')
		);

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/account', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['powered'] = sprintf($this->language->get('text_powered'), date('Y', time()), $this->config->get('config_name'));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		// Social Media 

		$data['social_icons'] = $this->load->controller('component/social_icons');

		return $this->load->view('common/footer', $data);
	}
}
