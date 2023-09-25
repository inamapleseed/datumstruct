<?php
    class ControllerAccountPrintEnquiry extends Controller{
        public function index(){

            $latest_enquiry_order = 0;

            if(isset($this->session->data['latest_enquiry_order'])){
                $latest_enquiry_order = $this->session->data['latest_enquiry_order'];
            }
            else{
                $this->response->redirect($this->url->link('error/not_found'));
            }

            $this->load->model('account/enquiry_order');

            $this->load->language('account/enquiry_order');

            $data['logo'] = 'image/' . $this->config->get('config_logo');

            $data['desktop_flag'] = 'mobile-version';
			$detect = new Mobile_Detect;
			
			
			if ( !$detect->isMobile() && !$detect->isTablet()) {
				$data['desktop_flag'] = "desktop-version";
			}
			
			if($detect->isiOS()){
				$data['desktop_flag'] .= " ios ";
			} 

            $enquiry_info = $this->model_account_enquiry_order->getOrderPrint($latest_enquiry_order);
            // debug($enquiry_info); exit;
            if($enquiry_info){

                $data['heading_title'] = $this->language->get('text_enquiry');

                $data['text_enquiry_detail'] = $this->language->get('text_enquiry_detail');
                $data['text_invoice_no'] = $this->language->get('text_invoice_no');
                $data['text_enquiry_id'] = $this->language->get('text_enquiry_id');
                $data['text_date_added'] = $this->language->get('text_date_added');
                $data['text_shipping_method'] = $this->language->get('text_shipping_method');
                $data['text_shipping_address'] = $this->language->get('text_shipping_address');
                $data['text_payment_method'] = $this->language->get('text_payment_method');
                $data['text_payment_address'] = $this->language->get('text_payment_address');
                $data['text_history'] = $this->language->get('text_history');
                $data['text_comment'] = $this->language->get('text_comment');
                $data['text_no_results'] = $this->language->get('text_no_results');

                $data['column_name'] = $this->language->get('column_name');
                $data['column_model'] = $this->language->get('column_model');
                $data['column_quantity'] = $this->language->get('column_quantity');
                $data['column_price'] = $this->language->get('column_price');
                $data['column_total'] = $this->language->get('column_total');
                $data['column_action'] = $this->language->get('column_action');
                $data['column_date_added'] = $this->language->get('column_date_added');
                $data['column_status'] = $this->language->get('column_status');
                $data['column_comment'] = $this->language->get('column_comment');

                if ($enquiry_info['invoice_no']) {
                    $data['invoice_no'] = $enquiry_info['invoice_prefix'] . $enquiry_info['invoice_no'];
                } else {
                    $data['invoice_no'] = '';
                }

                $data['enquiry_order_id'] = $latest_enquiry_order;
                $data['date_added'] = date($this->language->get('date_format_short'), strtotime($enquiry_info['date_added']));

                if ($enquiry_info['payment_address_format']) {
                    $format = $enquiry_info['payment_address_format'];
                } else {
                    $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
                }

                $find = array(
                    '{firstname}',
                    '{lastname}',
                    '{company}',
                    '{address_1}',
                    '{address_2}',
                    '{city}',
                    '{postcode}',
                    '{zone}',
                    '{zone_code}',
                    '{country}'
                );

                $replace = array(
                    'firstname' => $enquiry_info['payment_firstname'],
                    'lastname'  => $enquiry_info['payment_lastname'],
                    'company'   => $enquiry_info['payment_company'],
                    'address_1' => $enquiry_info['payment_address_1'],
                    'address_2' => $enquiry_info['payment_address_2'],
                    'city'      => $enquiry_info['payment_city'],
                    'postcode'  => $enquiry_info['payment_postcode'],
                    'zone'      => $enquiry_info['payment_zone'],
                    'zone_code' => $enquiry_info['payment_zone_code'],
                    'country'   => $enquiry_info['payment_country']
                );

                $data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

                $data['payment_method'] = $enquiry_info['payment_method'];

                if ($enquiry_info['shipping_address_format']) {
                    $format = $enquiry_info['shipping_address_format'];
                } else {
                    $format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
                }

                $find = array(
                    '{firstname}',
                    '{lastname}',
                    '{company}',
                    '{address_1}',
                    '{address_2}',
                    '{city}',
                    '{postcode}',
                    '{zone}',
                    '{zone_code}',
                    '{country}'
                );

                $replace = array(
                    'firstname' => $enquiry_info['shipping_firstname'],
                    'lastname'  => $enquiry_info['shipping_lastname'],
                    'company'   => $enquiry_info['shipping_company'],
                    'address_1' => $enquiry_info['shipping_address_1'],
                    'address_2' => $enquiry_info['shipping_address_2'],
                    'city'      => $enquiry_info['shipping_city'],
                    'postcode'  => $enquiry_info['shipping_postcode'],
                    'zone'      => $enquiry_info['shipping_zone'],
                    'zone_code' => $enquiry_info['shipping_zone_code'],
                    'country'   => $enquiry_info['shipping_country']
                );

                $data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

                $data['shipping_method'] = $enquiry_info['shipping_method'];

                $this->load->model('catalog/product');
                $this->load->model('tool/upload');

                // Products
                $data['products'] = array();

                $products = $this->model_account_enquiry_order->getOrderProducts($latest_enquiry_order);

                foreach ($products as $product) {
                    $option_data = array();

                    $options = $this->model_account_enquiry_order->getOrderOptions($latest_enquiry_order, $product['enquiry_order_product_id']);

                    foreach ($options as $option) {
                        if ($option['type'] != 'file') {
                            $value = $option['value'];
                        } else {
                            $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                            if ($upload_info) {
                                $value = $upload_info['name'];
                            } else {
                                $value = '';
                            }
                        }

                        $option_data[] = array(
                            'name'  => $option['name'],
                            'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                        );
                    }

                    $product_info = $this->model_catalog_product->getProduct($product['product_id']);

                    $data['products'][] = array(
                        'name'     => $product['name'],
                        'model'    => $product['model'],
                        'option'   => $option_data,
                        'quantity' => $product['quantity'],
                        'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']),
                        'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $enquiry_info['currency_code'], $enquiry_info['currency_value']),
                    );
                }

                // Voucher
                /*
                $data['vouchers'] = array();

                $vouchers = $this->model_account_enquiry_order->getOrderVouchers($latest_enquiry_order);

                foreach ($vouchers as $voucher) {
                    $data['vouchers'][] = array(
                        'description' => $voucher['description'],
                        'amount'      => $this->currency->format($voucher['amount'], $enquiry_info['currency_code'], $enquiry_info['currency_value'])
                    );
                }
                */
                // Totals
                $data['totals'] = array();

                $totals = $this->model_account_enquiry_order->getOrderTotals($latest_enquiry_order);

                foreach ($totals as $total) {
                    $text = $this->currency->format($total['value'], $enquiry_info['currency_code'], $enquiry_info['currency_value']);
                    $data['totals'][] = array(
                        'title' => $total['title'],
                        'text'  => (int)$total['value'],
                    );
                }

                $data['comment'] = nl2br($enquiry_info['comment']);

                // History
                $data['histories'] = array();

                $results = $this->model_account_enquiry_order->getOrderHistories($latest_enquiry_order);

                foreach ($results as $result) {
                    $data['histories'][] = array(
                        'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                        'status'     => $result['status'],
                        'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
                    );
                }

                $this->response->setOutput($this->load->view('account/print_enquiry', $data));
            }
            else{
                $this->response->redirect($this->url->link('error/not_found'));
            }

        }
    }