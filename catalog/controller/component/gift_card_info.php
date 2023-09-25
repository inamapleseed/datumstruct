<?php
    class ControllerComponentGiftCardInfo extends Controller{
        public function index($voucher_theme_id = 0){

            $url = '';

            if( is_array($voucher_theme_id) ){
                $url = $voucher_theme_id['url']; 
                $voucher_theme_id = $voucher_theme_id['voucher_theme_id']; 
            }

            $this->load->language('component/voucher_info');
            // Clean Value
            $voucher_theme_id = (int)$voucher_theme_id;

            // No id pass into this controller
            if(!$voucher_theme_id) return '';

            $this->load->model('extension/total/voucher_theme');

            $voucher_info = $this->model_extension_total_voucher_theme->getVoucherTheme($voucher_theme_id);

            // Product Disabled / Deleted
            if(!$voucher_info) return '';

            $theme = $this->config->get('config_theme');
            $width = $this->config->get($theme . '_image_product_width');
            $height = $this->config->get($theme . '_image_product_height');

            $image = $this->model_tool_image->resize('no_image.png', $width, $height);
                
            $price = false;
            $price_num = false;

            if (is_file(DIR_IMAGE . $voucher_info['image']) && $voucher_info['image']) 
                //$image = $this->model_tool_image->resize($voucher_info['image'], $width, $height);
                $image = 'image/'.$voucher_info['image'];
            
            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($voucher_info['amount'], $this->session->data['currency']);
                $price_num =  '$'.round($voucher_info['amount'], 0);
            }

            $voucher_url = $this->url->link('product/gift_card', 'voucher_theme_id=' . $voucher_info['voucher_theme_id']);

            if ($url) {
                $voucher_url = $this->url->link('product/gift_card', $url . '&voucher_theme_id=' . $voucher_info['voucher_theme_id']);
            }

            $info = array(
                'voucher_theme_id'  => $voucher_info['voucher_theme_id'],
				'thumb'             => $image, 
                'name'              => $voucher_info['name'],
				'description'       => utf8_substr(strip_tags(html_entity_decode($voucher_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($theme . '_product_description_length')),
                'price'             => $price,
                'price_num' => $price_num,
                'href'              => $voucher_url,
                'button_cart'		=> $this->language->get('button_cart'),
                'width' =>  $width,
            );
            //debug($info);

            return $this->load->view('component/gift_card_info', $info);
        }
    }