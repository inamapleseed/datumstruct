<?php
class ControllerReportSaleCoupon extends Controller {
	public function index() {
		$this->load->language('report/sale_coupon');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
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
			'href' => $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'] . $url, true)
		);

		$this->load->model('report/coupon');

		$data['coupons'] = array();

		$filter_data = array(
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$coupon_total = $this->model_report_coupon->getTotalCoupons($filter_data);

		$results = $this->model_report_coupon->getCoupons($filter_data);

		foreach ($results as $result) {
			$data['coupons'][] = array(
				'name'   => $result['name'],
				'code'   => $result['code'],
				'orders' => $result['orders'],
				'total'  => $this->currency->format($result['total'], $this->config->get('config_currency')),
				'edit'   => $this->url->link('marketing/coupon/edit', 'token=' . $this->session->data['token'] . '&coupon_id=' . $result['coupon_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_code'] = $this->language->get('column_code');
		$data['column_orders'] = $this->language->get('column_orders');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');

		$data['button_export'] = $this->language->get('button_export');
		$data['export'] = $this->url->link('report/sale_coupon/export', 'token=' . $this->session->data['token'] . $url, true);

		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($coupon_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($coupon_total - $this->config->get('config_limit_admin'))) ? $coupon_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $coupon_total, ceil($coupon_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/sale_coupon', $data));
	}

	// Export
	public function export() {
				
		$filter_date_start = '';
$filter_date_end = '';
$page = 1;
   $this->load->model('report/coupon');

   $coupons = array();

   $data = array(
	   
   );
   $limit=1000;

$limit=1000;

$filter_salecoupon = array(
'filter_date_start'	=> $filter_date_start,
'filter_date_end'	=> $filter_date_end,
'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
'limit'             => $limit
);


   $results = $this->model_report_coupon->getCoupons($filter_salecoupon);
   
   foreach ($results as $result) {
	   $coupons[] = array(
		   'name'   => $result['name'],
		   'code'   => $result['code'],
		   'orders' => $result['orders'],
		   'total'  => $this->currency->format($result['total'], $this->config->get('config_currency'))
	   );
   }
   
   $coupons_data = array();
   
   $coupons_column=array();
   
   $coupons_column = array('Coupon Name', 'Coupon Code', 'Orders', 'Total');
	   
   $coupons_data[0]=   $coupons_column;   
   
   foreach($coupons as $coupons_row)
   {
	   $coupons_data[]=   $coupons_row;            
   }     
   require_once(DIR_SYSTEM . 'library/excel_xml.php');
   $xls = new Excel_XML('UTF-8', false, 'Sales Coupons Report');
   
   $xls->addArray($coupons_data);
   
   $xls->generateXML('sales_coupons_report_'.date('Y-m-d _ H:i:s'));	

}
}