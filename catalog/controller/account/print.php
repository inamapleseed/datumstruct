<?php
    class ControllerAccountPrint extends Controller{
        public function index(){
            $latest_order = 0;
            if(isset($this->session->data['latest_order'])){
                $latest_order = $this->session->data['latest_order'];
            }
            else{
                $this->response->redirect($this->url->link('error/not_found'));
            }

            $this->load->model('account/order');

            $this->load->language('account/order');

            $data['logo'] = 'image/' . $this->config->get('config_logo');

            $data['desktop_flag'] = 'mobile-version';
			$detect = new Mobile_Detect;
			
			
			if ( !$detect->isMobile() && !$detect->isTablet()) {
				$data['desktop_flag'] = "desktop-version";
			}
			
			if($detect->isiOS()){
				$data['desktop_flag'] .= " ios ";
			}

            $order_info = $this->model_account_order->getOrderPrint($latest_order);

            if($order_info){


                $data['heading_title'] = $this->language->get('text_order');

                $data['text_order_detail'] = $this->language->get('text_order_detail');
                $data['text_invoice_no'] = $this->language->get('text_invoice_no');
                $data['text_order_id'] = $this->language->get('text_order_id');
                $data['text_date_added'] = $this->language->get('text_date_added');
                $data['text_shipping_method'] = $this->language->get('text_shipping_method');
                $data['text_shipping_address'] = $this->language->get('text_shipping_address');
                $data['text_payment_method'] = $this->language->get('text_payment_method');
                $data['text_payment_address'] = $this->language->get('text_payment_address');
                $data['text_history'] = $this->language->get('text_history');
                $data['text_comment'] = $this->language->get('text_comment');
                $data['text_no_results'] = $this->language->get('text_no_results');
                $data['text_email'] = $this->language->get('text_email');
                $data['text_telephone'] = $this->language->get('text_telephone');

                $data['column_name'] = $this->language->get('column_name');
                $data['column_model'] = $this->language->get('column_model');
                $data['column_quantity'] = $this->language->get('column_quantity');
                $data['column_price'] = $this->language->get('column_price');
                $data['column_total'] = $this->language->get('column_total');
                $data['column_action'] = $this->language->get('column_action');
                $data['column_date_added'] = $this->language->get('column_date_added');
                $data['column_status'] = $this->language->get('column_status');
                $data['column_comment'] = $this->language->get('column_comment');

                if ($order_info['invoice_no']) {
                    $data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
                } else {
                    $data['invoice_no'] = '';
                }

                $data['order_id'] = $latest_order;
                $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

                if ($order_info['payment_address_format']) {
                    $format = $order_info['payment_address_format'];
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
                    'firstname' => $order_info['payment_firstname'],
                    'lastname'  => $order_info['payment_lastname'],
                    'company'   => $order_info['payment_company'],
                    'address_1' => $order_info['payment_address_1'],
                    'address_2' => $order_info['payment_address_2'],
                    'city'      => $order_info['payment_city'],
                    'postcode'  => $order_info['payment_postcode'],
                    'zone'      => $order_info['payment_zone'],
                    'zone_code' => $order_info['payment_zone_code'],
                    'country'   => $order_info['payment_country']
                );

                $data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

                $data['email'] = $order_info['email'];
                $data['telephone'] = $order_info['telephone'];

                $data['payment_method'] = $order_info['payment_method'];

                if ($order_info['shipping_address_format']) {
                    $format = $order_info['shipping_address_format'];
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
                    'firstname' => $order_info['shipping_firstname'],
                    'lastname'  => $order_info['shipping_lastname'],
                    'company'   => $order_info['shipping_company'],
                    'address_1' => $order_info['shipping_address_1'],
                    'address_2' => $order_info['shipping_address_2'],
                    'city'      => $order_info['shipping_city'],
                    'postcode'  => $order_info['shipping_postcode'],
                    'zone'      => $order_info['shipping_zone'],
                    'zone_code' => $order_info['shipping_zone_code'],
                    'country'   => $order_info['shipping_country']
                );

                $data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

                $data['shipping_method'] = $order_info['shipping_method'];

                $this->load->model('catalog/product');
                $this->load->model('tool/upload');

                // Products
                $data['products'] = array();

                $products = $this->model_account_order->getOrderProducts($latest_order);

                foreach ($products as $product) {
                    $option_data = array();

                    $options = $this->model_account_order->getOrderOptions($latest_order, $product['order_product_id']);

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
                        'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                        'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
                    );
                }

                // Voucher
                $data['vouchers'] = array();

                $vouchers = $this->model_account_order->getOrderVouchers($latest_order);

                foreach ($vouchers as $voucher) {
                    $data['vouchers'][] = array(
                        'description' => $voucher['description'],
                        'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
                    );
                }

                // Totals
                $data['totals'] = array();

                $totals = $this->model_account_order->getOrderTotals($latest_order);

                foreach ($totals as $total) {
                    $data['totals'][] = array(
                        'title' => $total['title'],
                        'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
                    );
                }

                $data['comment'] = nl2br($order_info['comment']);

                // History
                $data['histories'] = array();

                $results = $this->model_account_order->getOrderHistories($latest_order);

                foreach ($results as $result) {
                    $data['histories'][] = array(
                        'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                        'status'     => $result['status'],
                        'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
                    );
                }

                $this->response->setOutput($this->load->view('account/print', $data));
            }
            else{
                $this->response->redirect($this->url->link('error/not_found'));
            }

        }
    }