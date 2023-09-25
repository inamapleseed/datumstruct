<?php
    class ControllerExtensionModuleWaitingList extends Controller{
        public function index(){

            if(!$this->config->get('waiting_list_status') || !isset($this->request->get['product_id'])) return '';

            $data = $this->load->language('extension/module/waiting_list');

            $this->load->model('catalog/product');

            $data['product_id'] = (int)$this->request->get['product_id'];

            $product_info = $this->model_catalog_product->getProduct($data['product_id']);

            if(!$product_info || $product_info['quantity'] > 0) return '';

            $data['description'] = '';

            if($this->config->get('waiting_list_description') && text($this->config->get('waiting_list_description')) != ''){
                $data['description'] = text($this->config->get('waiting_list_description'));
            }

            $data['email']  = $this->customer->getEmail();

            return $this->load->view('extension/module/waiting_list', $data);
        }

        public function add(){
            $this->load->language('extension/module/waiting_list');

            $json = array();

            if(isset($this->request->post['email']) 
            && isset($this->request->post['product_id'])
            && !is_array($this->request->post['email'])
            && !is_array($this->request->post['product_id'])
            && (int)$this->request->post['product_id']
            && filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)
            ){
                $this->load->model('extension/module/waiting_list');
                $email = $this->db->escape(text($this->request->post['email']));
                $product_id = (int)$this->request->post['product_id'];

                $response = $this->model_extension_module_waiting_list->add($email, $product_id);
                $json['test']= true;
                if($response['code'] == 1){
                    $json['error_title']      =   $this->language->get('error_title');
                    $json['error_general']    =   str_replace('[EMAIL]', $email, $this->config->get('waiting_list_error'));
                }
                else{
                    $json['success_title']      =   $this->language->get('success_title');
                    $json['success_general']    =   str_replace('[EMAIL]', $email, $this->config->get('waiting_list_success'));
                }
            }

            if(!$json){
                $json['error_title']      =   $this->language->get('error_title');
                $json['error_general']    =   $this->language->get('error_general');
            }

            $this->response->addHeader('Content-type: application/json');
            $this->response->setOutput(json_encode($json));
        }

        public function notify(){

            if(text($this->config->get('waiting_msg_title'))=='' || text($this->config->get('waiting_msg_description'))=='') return;

            $title = $this->config->get('waiting_msg_title');

            $this->load->language('extension/module/waiting_list');

            $this->load->model('extension/module/waiting_list');

            $notifications = $this->model_extension_module_waiting_list->getToNotified();

            if($notifications){
                $notified_ids = array();

                $mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html($this->config->get('config_mail_smtp_password'));
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html($this->config->get('config_name')));
                $mail->setSubject(html($title));
                
                $font_link = '<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">';
                $style_body = "font-family: 'Montserrat'; font-size: 12.5px; font-weight: 400;";
                $style_link = 'text-decoration: none; color: #2083b8;';

                $company_logo = $this->config->get('config_logo');
                if(is_file(DIR_IMAGE . $company_logo)){
                    $company_logo = '<br/><img src="' .HTTPS_SERVER . 'image/' . $company_logo . '" title="' . $this->config->get('config_name') . ' style="width: 80px;" />';
                }
                else{
                    $company_logo = false;
                }

                foreach($notifications as $notify){
                    if(isset($notify['email']) && text($notify['email']) != ''){

                        $products = ''; //'<ul><li>';
                        $product_names = explode(',', $notify['products']);
                        foreach(explode(',', $notify['product_ids']) as $index => $product_id){
                            if(isset($product_names[$index])){
                                $url = $this->url->link('product/product&product_id='.$product_id);
                                $name = $product_names[$index];
                                $products .= '<li><a href="' . $url . '" alt="' . $name . '" style="' . $style_link . '">'.$name.'</a></li>';
                            }
                        }
                        if(!$products) continue;

                        $products = '<ul>' . $products . '</ul>';

                        $description = str_replace('[PRODUCTS]', $products, text($this->config->get('waiting_msg_description')));
                        $description = nl2br($description);

                        $description = $font_link . '<div style="' . $style_body . '">' . $description . '</div>';

                        if($company_logo){
                            $description .= $company_logo;
                        }

                        $mail->setHtml($description);
                        $mail->setTo($notify['email']);
                        
                        $mail->send();

                        $notified_ids[] = $notify['waiting_ids'];
                    }
                }

                if($notified_ids){
                    $notified_ids = implode(',', $notified_ids);
                    $this->model_extension_module_waiting_list->updateNotifiedList($notified_ids);
                }
            }
        }
    }