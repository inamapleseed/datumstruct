<?php
    class Craft{

        public function page($obj, $data = array(), $diff_page = ''){
            if(isset($obj->request->get['route'])){

                $page = $obj->request->get['route'];
                
                $token = $obj->session->data['token'];

                $heading_title = $obj->language->get('heading_title');

                $obj->document->setTitle($heading_title);
                
                $data['heading_title'] = $heading_title;
                
                $data['breadcrumbs'] = array();

                $data['breadcrumbs'][] = array(
                    'text' => $obj->language->get('text_home'),
                    'href' => $obj->url->link('common/dashboard', 'token=' . $token, true)
                );

                $is_ext = false;

                $route_parts = explode('/', $page);

                $data['form_placeholder_type'] = end($route_parts);

                foreach($route_parts as $index => $each){
                    if(!$index && $each =='extension'){
                        $is_ext = true; 
                    }

                    if($is_ext){
                        $data['breadcrumbs'][] = array(
                            'text' => $obj->language->get('text_extension'),
                            'href' => $obj->url->link('extension/extension', 'type=&'. $each .'token=' . $token, true)
                        );
                        break;
                    }
                }

                $data['breadcrumbs'][] = array(
                    'text' => $heading_title,
                    'href' => $obj->url->link($page, 'token=' . $token, true)
                );

                if(!isset($data['text_edit'])) $data['text_edit'] = 'Edit';

                // Response
                $data['button'] = $obj->load->view($page . '_button', $data);

                $data = $obj->load->controller('common/common', $data);

                if(!$diff_page)
                    $data['content'] =  $obj->load->view($page, $data);
                else
                    $data['content'] =  $obj->load->view($diff_page, $data);
                
                $obj->response->setOutput($obj->load->view('page', $data));
            }
        }


        public function pagination(&$data){
            if(!isset($data['pg']) || count($data['pg']) != 4){
                $data['pagination'] = '';
                $data['results'] = '';
                return;
            }
            
            $settings = $data['pg'];

            $pagination = new Pagination();
			$pagination->total  = $settings[0];          // Total
			$pagination->page   = $settings[1];          // Page
			$pagination->limit  = $settings[2];          // Limit
			$pagination->url    = $settings[3];          // Url
			
			$data['pagination'] = $pagination->render();
			
            $data['results'] = sprintf($data['text_pagination'], ($settings[0]) ? (($settings[1] - 1) * $settings[2]) + 1 : 0, ((($settings[1] - 1) * $settings[2]) > ($settings[0] - $settings[2])) ? $settings[0] : ((($settings[1] - 1) * $settings[2]) + $settings[2]), $settings[0], ceil($settings[0] / $settings[2]));

        }
    }