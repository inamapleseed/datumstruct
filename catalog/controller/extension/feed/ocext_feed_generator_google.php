<?php
class ControllerExtensionFeedOcextFeedGeneratorGoogle extends Controller {
	private $shop = array();
	private $currencies = array();
	private $categories = array();
	private $offers = array();
        private $prices = array();
        private $delivery_option = array();
	private $eol = "\n";
        private $debug = 0;
        private $general_setting = array();
        private $path_oc = 'extension/feed';
        private $model_oc = 'extension_feed';
        private $version_oc = '2.3';


         public function index() {
            
                $error = array();
            
                if (!$this->config->get('ocext_feed_generator_google_status')) {
                    $error[] = 'The module is off';
                }
                
                $general_setting = $this->config->get('ocext_feed_generator_google_general_setting');
                
                $token_get = '';
                
                $filter_data_group_id = 0;
                
                $content_language_id = $this->config->get('config_language_id');
                
                if(isset($this->request->get['token'])){
                    
                    $token_get = $this->request->get['token'];
                    
                    if(isset($general_setting['filter_data'])){
                        
                        foreach ($general_setting['filter_data'] as $filter_data) {
                            
                            if($filter_data['path_token_export']==$token_get){
                                
                                $general_setting['path_token_export'] = $token_get;
                                $general_setting['filename_export'] = $filter_data['filename_export'];
                                $filter_data_group_id = $filter_data['filter_data_group_id'];
                                
                                if(isset($filter_data['content_language_id']) && $filter_data['content_language_id']){
                                    
                                    $content_language_id = $filter_data['content_language_id'];
                                    
                                }
                                
                            }
                            
                        }
                        
                    }
                    
                }
                
                if(!isset($general_setting['path_token_export']) || !$general_setting['path_token_export']){
                    $error[] = 'Unknown token';
                }
                
                if(isset($general_setting['path_token_export']) && $token_get!=$general_setting['path_token_export']){
                    $error[] = 'Invalid token';
                }
                
                //создаем список категорий
                $categories_end_offers = $this->getCategoriesAndOffers($filter_data_group_id,$content_language_id);
                if(!$categories_end_offers){
                    $error[] = 'Not found categories and / or products. Unable to create a file with no categories and / or products';
                }
                
                if(!isset($general_setting['name']) || !$general_setting['name']){
                    $error[] = 'It does not include the name of the store';
                }else{
                    $this->setShopAttributes('title', $general_setting['name']);
                }
                
                if(!isset($general_setting['store_url']) || !$general_setting['store_url']){
                    $error[] = 'Do not store a URL';
                }else{
                    $this->setShopAttributes('link', $general_setting['store_url']);
                }
                
                
                
                if($error){
                    $this->sendErrorXML($error);
                    return;
                }
                
                foreach ($categories_end_offers['categories'] as $category) {
                    $this->setCategoryAttrubite($category['name'], $category['category_id'], $category['parent_id']);
                }
                
                $this->setProductTypes();
                
                /*
                $this->delivery_option = $this->getShopDeliveryOptionAttrubite($categories_end_offers['offers']);
                 * 
                 */
                
                $this->general_setting = $general_setting;
                
                foreach ($categories_end_offers['offers'] as $product) {
                    $this->getOfferAttrubite($product);
                }
                if(!$this->debug){
                    $this->response->addHeader('Content-Type: application/xml');
                    $this->response->setOutput($this->getFeed());
                }
	}
        
        public function setProductTypes() {
            
            foreach ($this->categories as $category_id => $category) {
                
                if(isset($category['parentId']) && isset($this->categories[$category['parentId']])){
                    
                    $this->categories[$category_id]['product_type'] = $this->getParentCategoryName($category['parentId'],array($this->categories[$category_id]['name']));
                    
                }else{
                    
                    $this->categories[$category_id]['product_type'][] = $this->categories[$category_id]['name'];
                    
                }
                
            }
            
            foreach ($this->categories as $category_id => $category) {
                
                krsort($category['product_type']);
                
                $product_type = implode(' &gt; ', $category['product_type']);
                
                $this->categories[$category_id]['product_type'] = $product_type;
                
            }
        }
        
        public function getParentCategoryName($parent_id,$parent_category_names=array()) {
            
            if(isset($this->categories[$parent_id]['parentId'])){
                
                $parent_category_names = $this->getParentCategoryName($this->categories[$parent_id]['parentId'],  array_merge($parent_category_names,  array($this->categories[$parent_id]['name'])) );
                
            }elseif(isset($this->categories[$parent_id]['name']) && $this->categories[$parent_id]['name']){
                
                $parent_category_names = array_merge($parent_category_names,  array($this->categories[$parent_id]['name']));
                
            }
            
            return $parent_category_names;
            
        }
        /*
        public function getShopDeliveryOptionAttrubite($products) {
            
            $data = array();
            if($products){
                foreach ($products as $product) {
                    if(isset($product['setting']) && $product['setting'] && $product['setting']['delivery-options']['status']){
                        $delivery_options = $this->getDeliveryOption($product['setting']);
                        if($delivery_options){
                            $data['delivery-options'] = $delivery_options;
                        }
                    }
                }
            }
            return $data;
            
        }
         * 
         */
        
        public function getOfferAttrubite($product) {
            $data = array();
            $template_setting = array();
            $skip_this_offer = FALSE;
            
            if(isset($product['setting']) && $product['setting']){
                $template_setting = $product['setting'];
            }
            
            $data['id'] = $product['product_id'];
            
            if($product['product_id_by_option_id']){
                
                $data['id'] = $product['product_id_by_option_id'];
                
            }
            
            if(isset($template_setting['product_id_from']) && $template_setting['product_id_from']!='' && $product['product_id']<$template_setting['product_id_from']){
                $skip_this_offer = TRUE;
            }
            
            if(isset($template_setting['product_id_to']) && $template_setting['product_id_to']!='' &&  $product['product_id']>$template_setting['product_id_to']){
                $skip_this_offer = TRUE;
            }
            
            if(isset($template_setting['disable_this_product']) && $template_setting['disable_this_product']){
                $skip_this_offer = TRUE;
            }
            
            
            //$data['link'] = $this->url->link('product/product', 'path='.$this->getPathWhisCategories($product['category_id']).'&product_id='.$product['product_id'].$product['option_url_param']);
            
            $data['link'] = $this->url->link('product/product', '&product_id='.$product['product_id']);
            
            $data['link'] .= $product['option_url_param'];
            
            //цены
            
            $product['price'] = $this->getPrice($product['price'],$product,$template_setting);
            
            if($product['special_price']>0){
                $product['special_price'] = $this->getPrice($product['special_price'],$product,$template_setting);
            }
            if($product['discount_special_price']>0){
                $product['discount_special_price'] = $this->getPrice($product['discount_special_price'],$product,$template_setting);
            }
            
            if(isset($template_setting['sale_price']) && $template_setting['sale_price'] && ($product['special_price'] || $product['discount_special_price']) > 0){
                if($product['special_price']>0 && $product['special_price'] < $product['price'] ){
                    /*
                     * 
                    $data['sale_price'] = $product['price'];
                    $data['price'] = $product['special_price'];
                    $product['sale_price'] = $product['price'];
                    $product['price'] = $product['special_price'];
                     * 
                     */
                    $data['sale_price'] = $product['special_price'];
                    $product['sale_price'] = $data['sale_price'];
                    $data['price'] = $product['price'];
                }elseif( $product['discount_special_price']>0 && $product['discount_special_price'] < $product['price']  ){
                    /*
                     * 
                    $data['sale_price'] = $product['price'];
                    $data['price'] = $product['discount_special_price'];
                    $product['sale_price'] = $product['price'];
                    $product['price'] = $product['discount_special_price'];
                     * 
                    */
                    $data['sale_price'] = $product['discount_special_price'];
                    $product['sale_price'] = $data['sale_price'];
                    $data['price'] = $product['price'];
                }else{
                    
                    $data['price'] = $product['price'];
                    
                }
            }
            
            if(isset($template_setting['price_currencies_to'])){
                $decimal_place = (int)$this->currency->getDecimalPlace($template_setting['price_currencies_to']);
            }else{
                $decimal_place = (int)$this->currency->getDecimalPlace(0);
            }
            
            $data['price'] = number_format(round($product['price'],$decimal_place),2, '.', '');
            
            if(isset($data['sale_price']) && $data['sale_price'] && isset($product['sale_price']) && $product['sale_price']){
                
                $data['sale_price'] = number_format(round($product['sale_price'],$decimal_place),2, '.', '');
                
            }
            
            if(isset($template_setting['price_from']) && $template_setting['price_from']!='' && $template_setting['price_from']>$data['price']){
                $skip_this_offer = TRUE;
            }
            
            if(isset($template_setting['price_to']) && $template_setting['price_to']!='' && $data['price'] > $template_setting['price_to']){
                $skip_this_offer = TRUE;
            }
            
            if(!$data['price'] || $data['price']==0.0){
                $skip_this_offer = TRUE;
            }else{
                //используется для составных заголовков
                $this->prices[$product['product_id']] = $data['price'];
            }
            
            
            if(isset($template_setting['currencies']) && $template_setting['currencies']){
                
                $currency_code = $template_setting['currencies'];
                
            }else{
                
                if($this->version_oc!='2.3'){
                    
                    $currency_code = $this->currency->getCode();
                    
                }else{
                    
                    $currency_code = $this->config->get('config_currency');
                    
                }
                
            }
            
            $data['price'] = $data['price'].' '.$currency_code;
                
            if(isset($data['sale_price']) && $data['sale_price']){

                $data['sale_price'] = $data['sale_price'].' '.$currency_code;

            }
            
            //категория
            $data['categoryId'] = $product['category_id'];
            
            if(isset($product['google_product_category']) && $product['google_product_category']){
                //категория market_category
                $data['google_product_category'] = $product['google_product_category'];
            }
            
            //если в шаблоне задана другая категория, затераем ту которая задана при сверке категорий
            if(isset($template_setting['google_product_category']['field']['status']) && $template_setting['google_product_category']['field']['status']){
                
                $data['google_product_category'] = $this->getNameAttributeForType($product,$template_setting,$template_setting['google_product_category']['field']['status'],'google_product_category');
                
            }
            
            //если в шаблоне задана другая категория, затераем ту которая задана при сверке категорий
            if(isset($template_setting['product_type']['field']['status']) && $template_setting['product_type']['field']['status']){
                
                $data['product_type'] = $this->getNameAttributeForType($product,$template_setting,$template_setting['product_type']['field']['status'],'product_type');
                
            }
            
            
            //изображения
            $data['image_link'] = $this->getPictureAttributes($product,$template_setting,TRUE);
            $data['additional_image_link'] = $this->getPictureAttributes($product,$template_setting);
            
            //если без картинок не выгружать, и картинок нет, то это предложение не делаем
            if(isset($template_setting['no_pictures']) && !$template_setting['no_pictures'] && !$data['image_link'] && !$data['additional_image_link']){
                $skip_this_offer = TRUE;
            }
            
            //delivery-options
            if(isset($template_setting['shipping']['status']) && $template_setting['shipping']['status']){
                $shipping = $this->getDeliveryOption($template_setting,$currency_code);
                if($shipping){
                    $data['shipping'] = $shipping;
                }
            }
            
            if((isset($template_setting['offer_name']['field']['status'])  && $template_setting['offer_name']['field']['status']) ){
                
                //название товара
                $data['title'] = $this->getNameAttribute($product,$template_setting,'offer_name');
                
            }elseif(!isset($template_setting['offer_name']['field']['status'])){
                
                $data['title'] = $this->prepareField($product['name']);
                
            }
            
            //описание
            if(isset($template_setting['offer_description']['field']) && $template_setting['offer_description']['field']){
                $data['description'] = $this->getDescriptionAttribute($product, $template_setting);
                
                if(isset($template_setting['add_option_descr']) && $template_setting['add_option_descr']){
                    
                    $data['description'] .= $this->eol.$this->getOptionOrAtributeData($product,$template_setting,'option_id');
                    
                }
                
                if(isset($template_setting['add_attribute_descr']) && $template_setting['add_attribute_descr']){
                    
                    $data['description'] .= $this->eol.$this->getOptionOrAtributeData($product,$template_setting,'attribute_id');
                    
                }
                
            }elseif(!isset($template_setting['offer_description']['field'])){
                
                $data['description'] = $this->getDescriptionAttribute($product, $template_setting);
                
            }
            
            //adult
            if(isset($template_setting['adult']) && $template_setting['adult']){
                $data['adult'] = 'true';
            }
            
            if(isset($template_setting['condition']) && $template_setting['condition']){
                $data['condition'] = $template_setting['condition'];
            }elseif(!isset ($template_setting['condition'])){
                $data['condition'] = 'new';
            }
            
            if(isset($template_setting['gtin']['field']['status']) && $template_setting['gtin']['field']['status']){
                $data['gtin'] = $this->getNameAttributeForType($product,$template_setting,$template_setting['gtin']['field']['status'],'gtin');
            }
            
            if(isset($template_setting['brand']['field']['status']) && $template_setting['brand']['field']['status']){
                $data['brand'] = $this->getNameAttributeForType($product,$template_setting,$template_setting['brand']['field']['status'],'brand');
            }elseif(!isset ($template_setting['brand']['field']['status'])){
                $data['brand'] = $this->getNameAttributeForType($product,$template_setting,'manufacturer_id','manufacturer_id');
            }
            
            if(isset($template_setting['mpn']['field']['status']) && $template_setting['mpn']['field']['status']){
                $data['mpn'] = $this->getNameAttributeForType($product,$template_setting,$template_setting['mpn']['field']['status'],'mpn');
            }
            
            if(isset($template_setting['attribute_age_group']['field']['status']) && $template_setting['attribute_age_group']['field']['status']){
                $data['age_group'] = $this->getNameAttributeForType($product,$template_setting,$template_setting['attribute_age_group']['field']['status'],'attribute_age_group');
            }elseif(!isset ($template_setting['age_group']['field']['status'])){
                //$data['age_group'] = 'adult';
            }
            
            
            
            if(isset($template_setting['attribute_gender']['field']['status']) && $template_setting['attribute_gender']['field']['status']){
                $data['gender'] = $this->getNameAttributeForType($product,$template_setting,$template_setting['attribute_gender']['field']['status'],'attribute_gender');
            }
            
            $tag_name_field = 'color';
            if(isset($template_setting[$tag_name_field]['field']['status']) && $template_setting[$tag_name_field]['field']['status']){
                $data[$tag_name_field] = $this->getNameAttributeForType($product,$template_setting,$template_setting[$tag_name_field]['field']['status'],$tag_name_field);
            }
            
            $tag_name_field = 'material';
            if(isset($template_setting[$tag_name_field]['field']['status']) && $template_setting[$tag_name_field]['field']['status']){
                $data[$tag_name_field] = $this->getNameAttributeForType($product,$template_setting,$template_setting[$tag_name_field]['field']['status'],$tag_name_field);
            }
            
            $tag_name_field = 'pattern';
            if(isset($template_setting[$tag_name_field]['field']['status']) && $template_setting[$tag_name_field]['field']['status']){
                $data[$tag_name_field] = $this->getNameAttributeForType($product,$template_setting,$template_setting[$tag_name_field]['field']['status'],$tag_name_field);
            }
            
            $tag_name_field = 'size_system';
            if(isset($template_setting[$tag_name_field]['field']['status']) && $template_setting[$tag_name_field]['field']['status']){
                $data[$tag_name_field] = $this->getNameAttributeForType($product,$template_setting,$template_setting[$tag_name_field]['field']['status'],$tag_name_field);
            }
            
            $tag_name_field = 'size_type';
            if(isset($template_setting[$tag_name_field]['field']['status']) && $template_setting[$tag_name_field]['field']['status']){
                $data[$tag_name_field] = $this->getNameAttributeForType($product,$template_setting,$template_setting[$tag_name_field]['field']['status'],$tag_name_field);
            }
            
            $tag_name_field = 'size';
            if(isset($template_setting[$tag_name_field]['field']['status']) && $template_setting[$tag_name_field]['field']['status']){
                $data[$tag_name_field] = $this->getNameAttributeForType($product,$template_setting,$template_setting[$tag_name_field]['field']['status'],$tag_name_field);
            }
            
            $tag_name_field = 'adwords_redirect';
            if(isset($template_setting[$tag_name_field]['field']['status']) && $template_setting[$tag_name_field]['field']['status']){
                $data[$tag_name_field] = $this->getNameAttributeForType($product,$template_setting,$template_setting[$tag_name_field]['field']['status'],$tag_name_field);
            }
            
            $tag_name_field = 'promotion_id';
            if(isset($template_setting[$tag_name_field]['field']['status']) && $template_setting[$tag_name_field]['field']['status']){
                $data[$tag_name_field] = $this->getNameAttributeForType($product,$template_setting,$template_setting[$tag_name_field]['field']['status'],$tag_name_field);
            }
            
            $tag_name_field = 'multipack';
            if(isset($template_setting[$tag_name_field]['field']['status']) && $template_setting[$tag_name_field]['field']['status']){
                $data[$tag_name_field] = $this->getNameAttributeForType($product,$template_setting,$template_setting[$tag_name_field]['field']['status'],$tag_name_field);
            }
            
            if(isset($template_setting['divide_on_option_option_id']['field']['status']) && $template_setting['divide_on_option_option_id']['field']['status'] && isset($product['option_add_model_name']) &&  $product['option_add_model_name'] && isset($template_setting['type_variation']) && $template_setting['type_variation']){
                
                $data[$template_setting['type_variation']] = $product['option_add_model_name'];
                
            }
            
            if(isset($template_setting['divide_on_option_option_id']['field']['status']) && $template_setting['divide_on_option_option_id']['field']['status'] && isset($product['product_id_by_option_id']) &&  $product['product_id_by_option_id'] != $product['product_id']){
                
                $data['item_group_id'] = $product['product_id'];
                
            }
            
            if(isset($template_setting['divide_on_option_option_id']['field']['status']) && $template_setting['divide_on_option_option_id']['field']['status'] && isset($template_setting['add_to_title_option_value_name']) && $template_setting['add_to_title_option_value_name']  && isset($product['option_add_model_name']) &&  $product['option_add_model_name']){
                
                $data['title'] .= ' '.$product['option_add_model_name'];
                
            }
            
            //
            
            $tag_name_field = 'adult';
            $tag_name_field_false = '';
            $tag_name_field_true = 'yes';
            if(isset($template_setting[$tag_name_field]) && $template_setting[$tag_name_field]){
                $data[$tag_name_field] = $tag_name_field_true;
            }elseif($tag_name_field_false){
                $data[$tag_name_field] = $tag_name_field_false;
            }
            //var_dump($data);exit();
            $tag_name_field = 'identifier_exists';
            $tag_name_field_false = 'no';
            $tag_name_field_true = 'yes';
            if(isset($template_setting[$tag_name_field]) && $template_setting[$tag_name_field]=='yes_to_tag'){
                $data[$tag_name_field] = $tag_name_field_true;
                
            }elseif(isset($template_setting[$tag_name_field]) && $template_setting[$tag_name_field]=='no_to_tag'){
                $data[$tag_name_field] = $tag_name_field_false;
            }
            if((!isset($data['gtin']) || !$data['gtin']) && (!isset($data['mpn']) || !$data['mpn'])){
                //$data[$tag_name_field] = $tag_name_field_false;
            }
            
            
            //available
            $stock_status_id = $product['stock_status_id'];
            $quantity = (int)$product['quantity'];
            $minimum = (int)$product['minimum'];
            $data['availability'] = 'out of stock';
            if(!$quantity || $quantity<$minimum){
                $data['availability'] = 'out of stock';
            }elseif($quantity || $quantity>=$minimum){
                $data['availability'] = 'in stock';
            }
            
            if($data['availability']=='out of stock' && isset ($template_setting['available_in_stock']) && $template_setting['available_in_stock']==$stock_status_id){
                $data['availability'] = 'in stock';
            }elseif($data['availability']=='out of stock' && isset ($template_setting['available_out_of_stock'][$stock_status_id])){
                $data['availability'] = 'out of stock';
            }elseif($data['availability']=='out of stock' && isset ($template_setting['available_preorder'][$stock_status_id])){
                $data['availability'] = 'preorder';
            }
            
            if(isset($template_setting['available_by_quantity']) && $template_setting['available_by_quantity']){
                
                $data['availability'] = 'out of stock';
                if($quantity || $quantity>=$minimum){
                    $data['availability'] = 'in stock';
                }
                
            }
            
            
            if(isset($template_setting['dispublic_quantity']) && !$template_setting['dispublic_quantity'] && ( !$quantity || $quantity<$minimum ) ){
                $skip_this_offer = TRUE;
            }
            
            //дополнительные элементы
            if(isset($this->general_setting['count_custom_elements'])){
                
                $count_custom_elements = (int)$this->general_setting['count_custom_elements'];
                
                if($count_custom_elements){
                    
                    for($i=0;$i<$count_custom_elements;$i++){
                        
                        $name_element_key = 'custom_elements_name_'.$i;
                        
                        $field_element_key = 'custom_elements_field_'.$i;
                        
                        if(isset($template_setting[$name_element_key]) && $template_setting[$name_element_key] && isset($template_setting[$field_element_key]) && $template_setting[$field_element_key]['field']['status']){
                            
                            $data['custom_elements'][$template_setting[$name_element_key]] = $this->getNameAttributeForType($product,$template_setting,$template_setting[$field_element_key]['field']['status'],$field_element_key);
                            
                        }
                        
                    }
                    
                }
                
            }
            if(!$skip_this_offer){
                $this->setOffer($data);
            }
            
            return;
        }
        
        public function getParamOption($ym_options) {
            $result = array();
            foreach ($ym_options as $key => $value) {
                $name = $this->prepareField($value['name']);
                $value['unit'] = $this->prepareField($value['unit']);
                $unit = '';
                if($value['unit']){
                    $unit = $value['unit'];
                }
                if(isset($value['product_option_value']) && $value['product_option_value']){
                    foreach ($value['product_option_value'] as $key => $product_option_value) {
                        if(!$unit){
                            $result[] = array('name'=>  $name,'value'=>  $this->prepareField($product_option_value['name']));
                        }else{
                            $result[] = array('name'=>  $name,'value'=>  $this->prepareField($product_option_value['name']),'unit'=>$unit);
                        }
                    }
                }
            }
            return $result;
        }
        
        public function getParamAttribute($ym_attributes,$template_setting=array()) {
            $result = array();
            foreach ($ym_attributes as $key => $value) {
                $value['unit'] = $this->prepareField($value['unit']);
                $unit = '';
                if($value['unit']){
                    $unit = $value['unit'];
                }
                if(isset($value['attribute']) && $value['attribute']){
                    foreach ($value['attribute'] as $key => $attribute) {
                        
                        
                         if(isset($template_setting['attribute_sintaxis']) && $template_setting['attribute_sintaxis']){
                    
                            $name_attribute = $this->prepareField($attribute['name']);
                            $value_attribute = $this->prepareField($attribute['text']);

                        }else{

                            $name_attribute = $this->prepareField($value['name']).': ';
                            $value_attribute = $this->prepareField($attribute['name'].' '.$attribute['text']);

                        }
                        
                        
                        
                        if(!$unit){
                            $result[] = array('name'=>  $name_attribute,'value'=>  $value_attribute);
                        }else{
                            $result[] = array('name'=>  $name_attribute,'value'=>  $value_attribute,'unit'=>$unit);
                        }
                    }
                }
            }
            return $result;
        }
        
        public function getNameAttributeForType($product,$template_setting,$composite_types,$field_name) {
            
            $result = '';
            switch ($composite_types){
                case 'attribute_id':
                    if(isset($template_setting[$field_name]['field'][$composite_types])){
                        
                        $attributes_parts = explode('___', $template_setting[$field_name]['field'][$composite_types]);
                        $attribute_group_id = $attributes_parts[0];
                        $attribute_id = $attributes_parts[1];
                        if($product['all_attributes']){
                            foreach ($product['all_attributes'] as $group_attributes) {
                                if($group_attributes['attribute_group_id'] == $attribute_group_id && $group_attributes['attribute']){
                                    foreach ($group_attributes['attribute'] as $attribute_group_value) {
                                        if($attribute_group_value['attribute_id']==$attribute_id){
                                            $result = trim($this->prepareField($attribute_group_value['text']));
                                        }
                                    }

                                }
                            }
                        }
                        
                    }
                    break;
                case 'option_id':
                    if(isset($template_setting[$field_name]['field'][$composite_types])){
                        $option_id = $template_setting[$field_name]['field'][$composite_types];
                        if($product['all_options']){
                            foreach ($product['all_options'] as $option) {
                                if($option['option_id'] == $option_id){

                                    $name_option = trim($this->prepareField($option['name']));
                                    $name_option_vields = array();
                                    foreach ($option['product_option_value'] as $product_option_value) {
                                        $name_option_vields[] = trim($this->prepareField($product_option_value['name']));
                                    }
                                    $result = implode('/', $name_option_vields);
                                }
                            }
                        }
                    }
                    break;
                case 'manufacturer_id':
                    $product['manufacturer'] = $this->prepareField($product['manufacturer']);
                    if($product['manufacturer']){
                        $result = $product['manufacturer'];
                    }
                    break;

                case 'price':
                    $result = $this->prices[$product['product_id']];
                    break;

                case 'weight':
                    if((float)$product['weight']>0){
                        $result = (float)$this->weight->format($product['weight'],$product['weight_class_id']);
                    }
                    break;

                case 'length_width_height':
                    $length_width_height = array();
                    if((float)$product['length']>0){
                        $length_width_height[] = (float)$this->length->format($product['length'],$product['length_class_id']);
                    }
                    if((float)$product['width']>0){
                        $length_width_height[] = (float)$this->length->format($product['width'],$product['length_class_id']);
                    }
                    if((float)$product['height']>0){
                        $length_width_height[] = (float)$this->length->format($product['height'],$product['length_class_id']);
                    }
                    if($length_width_height){
                        $result = implode('/', $length_width_height);
                    }
                    break;
                case 'category_id':
                    if(isset($this->categories[$product['category_id']]['name'])){
                        $result = $this->categories[$product['category_id']]['name'];
                    }
                    break;
                case 'product_type':
                    if(isset($this->categories[$product['category_id']]['product_type'])){
                        $result = $this->categories[$product['category_id']]['product_type'];
                    }
                    break;
                case 'text_field':
                    $result = $this->prepareField($template_setting[$field_name]['field']['text_field']);
                    break;
                    
                default:
                if(isset($product[$composite_types])){
                    $product[$composite_types] = $this->prepareField($product[$composite_types]);
                    if($product[$composite_types]){
                        $result = $product[$composite_types];
                    }
                }
                break;
            }
            return $result;
        }
        
        public function getOptionOrAtributeData($product,$template_setting,$key) {
            
                $result = '';
            
                switch ($key){
                    case 'option_id':
                    
                    $option = $this->getParamOption($product['ym_options']);
                    if($option){
                        foreach ($option as $value) {
                            $result .= ' '.implode(' ', $value);
                        }
                    }
                    break;

                    case 'attribute_id':
                    $attributes = $this->getParamAttribute($product['ym_attributes'],$template_setting);
                    if($attributes){
                        foreach ($attributes as $value) {
                            $result .= ' '.implode(' ', $value);
                        }
                    }
                    break;
                }
                
                return $result;
            
        }

        public function getDescriptionAttribute($product,$template_setting){
            
            if(!isset($template_setting['offer_description']['field']) || !$template_setting['offer_description']['field']){
                $description = $this->prepareField($product['description']);
                return $description;
            }
            else{
                
                $key = $template_setting['offer_description']['field'];
                
                switch ($key){
                    case 'option_id':
                    $description = '';
                    $option = $this->getParamOption($product['ym_options']);
                    if($option){
                        foreach ($option as $value) {
                            $description .= ' '.implode(' ', $value);
                        }
                    }
                    break;

                    case 'attribute_id':
                    $description = '';
                    $attributes = $this->getParamAttribute($product['ym_attributes']);
                    if($attributes){
                        foreach ($attributes as $value) {
                            $description .= ' '.implode(' ', $value);
                        }
                    }
                    break;

                    case 'meta_title':
                        if(isset($product['meta_title']))
                        $description = $this->prepareField($product['meta_title']);
                    break;
                
                    case 'meta_keyword':
                        if(isset($product['meta_keyword']))
                        $description = $this->prepareField($product['meta_keyword']);
                    break;

                    case 'meta_description':
                        if(isset($product['meta_description']))
                        $description = $this->prepareField($product['meta_description']);
                    break;
                    
                    case 'description':
                        if(isset($product['description']))
                        $description = $this->prepareField($product['description']);
                    break;
                    
                    default:
                        $description = '';
                    break;
                }
                return $description;
            }
            
            $result = $this->prepareField($product['name']);
            if(isset($template_setting['offer_name']['field']) && $template_setting['offer_name']['field']=='name'){
                return $result;
            }
            if(isset($template_setting['offer_name']['field']) && $template_setting['offer_name']['field']=='meta_title' && isset($product['meta_title'])){
                $result = $this->prepareField($product['meta_title']);
                return $result;
            }
            if(isset($template_setting['offer_name']['field']) && $template_setting['offer_name']['field']=='composite' && $template_setting['offer_name']['composite']){
                ksort($template_setting['offer_name']['composite']);
                $name = array();
                foreach ($template_setting['offer_name']['composite'] as $composite) {
                    //атрибут
                    if($composite['status']=='attribute_id'){
                        $attributes_parts = explode('___', $composite['attribute_id']);
                        $attribute_group_id = $attributes_parts[0];
                        $attribute_id = $attributes_parts[1];
                        if($product['all_attributes']){
                            foreach ($product['all_attributes'] as $group_attributes) {
                                if($group_attributes['attribute_group_id'] == $attribute_group_id && $group_attributes['attribute']){
                                    foreach ($group_attributes['attribute'] as $attribute_group_value) {
                                        if($attribute_group_value['attribute_id']==$attribute_id){
                                            $name[] = trim($this->prepareField($attribute_group_value['name'])).': '.trim($this->prepareField($attribute_group_value['text']));
                                        }
                                    }
                                    
                                }
                            }
                        }
                    }
                    //опция
                    elseif($composite['status']=='option_id'){
                        $option_id = $composite['option_id'];
                        if($product['all_options']){
                            foreach ($product['all_options'] as $option) {
                                if($option['option_id'] == $option_id){
                                    
                                    $name_option = trim($this->prepareField($option['name']));
                                    $name_option_vields = array();
                                    foreach ($option['product_option_value'] as $product_option_value) {
                                        $name_option_vields[] = trim($this->prepareField($product_option_value['name']));
                                    }
                                    if($name_option_vields){
                                        $name[] = $name_option.': '.  implode(', ', $name_option_vields);
                                    }else{
                                        $name[] = $name_option;
                                    }
                                }
                            }
                        }
                    }
                    //цена
                    elseif($composite['status']=='price'){
                        $name[] = $this->prices[$product['product_id']];
                    }
                    //вес
                    elseif($composite['status']=='weight'){
                        if((float)$product['weight']>0){
                            $name[] = $this->weight->format($product['weight'],$product['weight_class_id']);
                        }
                    }
                    //производитель
                    elseif($composite['status']=='manufacturer_id'){
                        $product['manufacturer'] = $this->prepareField($product['manufacturer']);
                        if($product['manufacturer']){
                            $name[] = $product['manufacturer'];
                        }
                    }
                    //категория
                    elseif($composite['status']=='category_id'){
                        $name[] = $this->categories[$product['category_id']]['name'];
                    }
                    //габариты
                    elseif($composite['status']=='length_width_height'){
                        $length_width_height = array();
                        if((float)$product['length']>0){
                            $length_width_height[] = $this->length->format($product['length'],$product['length_class_id']);
                        }
                        if((float)$product['width']>0){
                            $length_width_height[] = $this->length->format($product['width'],$product['length_class_id']);
                        }
                        if((float)$product['height']>0){
                            $length_width_height[] = $this->length->format($product['height'],$product['length_class_id']);
                        }
                        if($length_width_height){
                            $name[] = implode('/', $length_width_height);
                        }
                    }
                    //остальные
                    elseif (isset ($product[$composite['status']])) {
                        $product[$composite['status']] = $this->prepareField($product[$composite['status']]);
                        if($product[$composite['status']]){
                            $name[] = $product[$composite['status']];
                        }
                    }
                }
                $result = trim($this->prepareField(implode(' ', $name)));
                return $result;
            }
        }
        
        public function getNameAttribute($product,$template_setting,$field_name){
            
            $result = '';
            
            if(isset($template_setting[$field_name]['field']['status']) && $template_setting[$field_name]['field']['status']!='composite'){
                
                $result = $this->getNameAttributeForType($product,$template_setting,$template_setting[$field_name]['field']['status'],$field_name);
                
            }elseif(isset($template_setting[$field_name]['field']['status']) && $template_setting[$field_name]['field']['status']=='composite'){
                
                $field_type = 'composite';
                
                $count_composite_elements = 10;
                
                $result_parts = array();
                
                for($i=1;$i<$count_composite_elements;$i++){
                    
                    if(isset($template_setting[ $field_name.'_'.$field_type.'_'.$i ]) && $template_setting[ $field_name.'_'.$field_type.'_'.$i ]){
                        
                        $result_parts[] = $this->getNameAttributeForType($product,$template_setting,$template_setting[ $field_name.'_'.$field_type.'_'.$i ]['field']['status'],$field_name.'_'.$field_type.'_'.$i);
                        
                    }
                    
                }
                
                if($result_parts){
                    
                    $result = implode(' ', $result_parts);
                    
                }
                
                
            }
            return $result;
        }
        
        public function getTemplateSettingNameComposite() {
            $columns_product_description = $this->db->query('SHOW COLUMNS FROM '.DB_PREFIX.'product_description');
            $columns_product = $this->db->query('SHOW COLUMNS FROM '.DB_PREFIX.'product');

            $template_setting_name_composite['name'] = 'name';
            if($columns_product_description->rows){
                foreach($columns_product_description->rows as $column){
                    if($column['Field']=='meta_title'){
                        $template_setting_name_composite['meta_title'] = 'meta_title';
                    }
                }
            }
            $unset_product_fileds = array_flip(array('quantity','stock_status_id','image','shipping','points','tax_class_id','date_available','weight_class_id','length_class_id','subtract','minimum','sort_order','status','viewed','date_added','date_modified'));
            $product_fileds = array();
            if($columns_product->rows){
                foreach($columns_product->rows as $key=>$column){
                    if(!isset($unset_product_fileds[$column['Field']])){
                        $product_fileds[$column['Field']] = $column['Field'];
                    }
                }
            }
            if(isset($product_fileds['length']) && isset($product_fileds['width']) && isset($product_fileds['height'])){
                unset($product_fileds['length']);
                unset($product_fileds['width']);
                unset($product_fileds['height']);
                $product_fileds['length_width_height'] = 'length_width_height';
            }
            $template_setting_name_composite += $product_fileds;
            $template_setting_name_composite['category_id'] = 'category_id';
            $template_setting_name_composite['option_id'] = 'option_id';
            $template_setting_name_composite['attribute_id'] = 'attribute_id';
            return $template_setting_name_composite;
        }

        public function getDeliveryOption($tamplate_setting,$currency_code){
            $result = array();
            if(isset($tamplate_setting['shipping']['status'])){
                unset($tamplate_setting['shipping']['status']);
            }
            
            if(isset($tamplate_setting['price_currencies_to'])){
                $decimal_place = (int)$this->currency->getDecimalPlace($tamplate_setting['price_currencies_to']);
            }else{
                $decimal_place = (int)$this->currency->getDecimalPlace(0);
            }
            
            foreach ($tamplate_setting['shipping'] as $delivery_options) {
                $delivery_options['country'] = trim($delivery_options['country']);
                $delivery_options['service'] = trim($delivery_options['service']);
                //$delivery_options['price'] = (int)trim($delivery_options['price']);
                $delivery_options['price'] = number_format(round((float)trim($delivery_options['price']),$decimal_place),2, '.', '');
                if($delivery_options['price'] > 0){
                    $option['price'] = $delivery_options['price'].' '.$currency_code;
                    if($delivery_options['service']!=''){
                        $option['service'] = $delivery_options['service'];
                    }
                    if($delivery_options['country']!=''){
                        $option['country'] = $delivery_options['country'];
                    }
                    $result[] = $option;
                    unset($option);
                }
            }
            return $result;
        }
        
        public function getPrice($price,$product,$tamplate_setting){
            
            $result_price = 0.0;
            $price = $this->tax->calculate($price, $product['tax_class_id']);
            $new_price = (float)$price;
            if(isset($tamplate_setting['ymlprice']) && $tamplate_setting['ymlprice']){
                $tamplate_setting['ymlprice'] = (float)$tamplate_setting['ymlprice'];
                $new_price = $new_price - $tamplate_setting['ymlprice']/100*$price;
            }
            
            if($new_price==0.0){
                $result_price =  $price;
            }else{
                $result_price = $new_price;
            }
            
            if(isset($tamplate_setting['price_currencies_from']) && $tamplate_setting['price_currencies_from'] && isset($tamplate_setting['price_currencies_to']) && $tamplate_setting['price_currencies_to']){
                
                $result_price = $this->currency->convert($result_price,$tamplate_setting['price_currencies_from'],$tamplate_setting['price_currencies_to']);
                
            }
            
            return $result_price;
        }
        
        public function getPictureAttributes($product,$tamplate_setting,$main_image=FALSE) {
            if(isset($tamplate_setting['count_pictures']) && !$tamplate_setting['count_pictures']){
                return array();
            }elseif(isset($tamplate_setting['count_pictures'])){
                $count_pictures = (int)$tamplate_setting['count_pictures'];
            }else{
                $count_pictures = 1;
            }
            
            $pictures_sizes = 500;
            
            if(isset($tamplate_setting['pictures_sizes'])){
                $tamplate_setting['pictures_sizes'] = (int)$tamplate_setting['pictures_sizes'];
                if($tamplate_setting['pictures_sizes']>0){
                    $pictures_sizes = $tamplate_setting['pictures_sizes'];
                }
            }
            $result = array();
            if ($product['image'] && $product['image']!='no_image.jpg' && $product['image']!='no_image.png' && $product['image']!='no-image.jpg' && $product['image']!='no-image.png') {
                
                
                $this->load->model('tool/image');
                
                if($main_image){
                    $result[] = $this->model_tool_image->resize($product['image'], $pictures_sizes, $pictures_sizes);
                }else{
                    if($product['images']){
                        for($i=0;($i<$count_pictures && isset($product['images'][$i]));$i++){
                            // чтобы не было дублежа картинок
                            $result[] = $this->model_tool_image->resize($product['images'][$i]['image'], $pictures_sizes, $pictures_sizes);
                        }
                    }
                    
                }
            }
            return $result;
        }

        public function getCategoriesAndOffers($filter_data_group_id,$content_language_id) {
            
            $ym_categories = array();
            $this->load->model($this->path_oc.'/ocext_feed_generator_google');
            
            $all_yml_export_ocext_ym_filter_data_categories = $this->{'model_'.$this->model_oc.'_ocext_feed_generator_google'}->getFilterData('ocext_feed_generator_google_ym_filter_category',$filter_data_group_id);
            if($all_yml_export_ocext_ym_filter_data_categories){
                $ym_categories = $all_yml_export_ocext_ym_filter_data_categories;
                if($ym_categories){
                    foreach ($ym_categories as $category_id=>$ym_category){
                        if(!isset($ym_category['category_id'])){
                            unset($ym_categories[$category_id]);
                        }
                    }
                    
                    //если опустел, то категории не отмечены - нужны все данные - возвращаем туда всё, что там лежало
                    if(!$ym_categories){
                        $ym_categories = $all_yml_export_ocext_ym_filter_data_categories;
                    }
                }
            }
            
            $ym_manufacturers = array();
            $all_yml_export_ocext_ym_filter_data_manufacturers = $this->{'model_'.$this->model_oc.'_ocext_feed_generator_google'}->getFilterData('ocext_feed_generator_google_ym_filter_manufacturers',$filter_data_group_id);
            if($all_yml_export_ocext_ym_filter_data_manufacturers){
                $ym_manufacturers = $all_yml_export_ocext_ym_filter_data_manufacturers;
                if($ym_manufacturers){
                    foreach ($ym_manufacturers as $manufacturer_id=>$ym_manufacturer){
                        if(!isset($ym_manufacturer['manufacturer_id'])){
                            unset($ym_manufacturers[$manufacturer_id]);
                        }
                    }
                    //если опустел, то категории не отмечены - нужны все данные - возвращаем туда всё, что там лежало
                    if(!$ym_manufacturers){
                        $ym_manufacturers = $all_yml_export_ocext_ym_filter_data_manufacturers;
                        $ym_manufacturers[''] = array("setting_id"=>"0");
                        $ym_manufacturers[0] = array("setting_id"=>"0");
                    }
                }
            }
            
            //Получаем список товаров для выгрузки и список категорий
            $this->load->model($this->path_oc.'/ocext_feed_generator_google');
            $categories_and_products = $this->{'model_'.$this->model_oc.'_ocext_feed_generator_google'}->getCategoriesAndProducts($ym_categories,$ym_manufacturers,$filter_data_group_id,$content_language_id);
            if(!$categories_and_products['offers'] || !$categories_and_products['categories']){
                return FALSE;
            }else{
                return $categories_and_products;
            }
        }
        
        private function prepareField($field) {
            if(is_string($field)){
                $field = strip_tags(htmlspecialchars_decode($field));
                $from = array('"', '&', '>', '<', '\'','`','&acute;');
                $to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;','','');
                $field = str_replace($from, $to, $field);
                $field = trim($field);
            }
            return $field;
	}
        
        private function setShopAttributes($name, $value) {
            $attributes = array('title', 'link');
            if (in_array($name, $attributes)) {
                    $this->shop[$name] = $this->prepareField($value);
            }
	}
        
        private function setCurrencyAttributes($currency, $rate = 'CBRF') {
            if($currency){
                if(!isset($this->currencies[0])){
                    $this->currencies[] = array(
                        'id'=>$currency,
                        'rate'=>1
                    );
                }else{
                    $this->currencies[] = array(
                        'id'=>$currency,
                        'rate'=>$rate
                    );
                }
                return TRUE;
            }else{
                return FALSE;
            }
	}
        
        private function setCategoryAttrubite($name, $category_id, $parent_id = 0) {
            $name = $this->prepareField($name);
            if(!$category_id || !$name) {
                return;
            }
            if((int)$parent_id > 0) {
                $this->categories[$category_id] = array(
                        'id'=>$category_id,
                        'parentId'=>(int)$parent_id,
                        'name'=>$this->prepareField($name)
                );
            }else{
                $this->categories[$category_id] = array(
                    'id'=>$category_id,
                    'name'=>$this->prepareField($name)
                );
            }
	}
        
        protected function getPathWhisCategories($category_id,$old_path = '') {
            if (isset($this->categories[$category_id])) {
                if (!$old_path) {
                    $new_path = $this->categories[$category_id]['id'];
                } else {
                    $new_path = $this->categories[$category_id]['id'].'_' .$old_path;
                }	
                if (isset($this->categories[$category_id]['parentId'])) {
                    return $this->getPathWhisCategories($this->categories[$category_id]['parentId'], $new_path);
                } else {
                    return $new_path;
                }
            }
	}
        
        
        private function setOffer($data) {
            
            $custom_elements = array();
            if(isset($data['custom_elements'])){
                
                $custom_elements = $data['custom_elements'];
                
            }
            
            $offer = array();
            /*
            if(isset($data['sale_price']) && $data['sale_price']){
                $finded_tags['sale_price'] = 0;
            }
            */
            $finded_tags = array('google_product_category'=>0, 'title'=>0,'link'=>0, 'price'=>0,'id'=>0,'availability'=>0, 'condition'=>0,'product_type'=>0, 'description'=>0,'additional_image_link'=>0, 'image_link'=>0,'shipping'=>0,'adult'=>0,'gtin'=>0,'mpn'=>0,'brand'=>0,'age_group'=>0,'gender'=>0,
                'identifier_exists'=>0,
                'color'=>0,
                'material'=>0,
                'pattern'=>0,
                'size_system'=>0,
                'size_type'=>0,
                'size'=>0,
                'adwords_redirect'=>0,
                'promotion_id'=>0,
                'multipack'=>0,
                'item_group_id'=>0
            );
            
            if(isset($data['sale_price']) && $data['sale_price']){
                $finded_tags['sale_price'] = 0;
            }
            
            $requiredes = array_filter($finded_tags);
            if (sizeof(array_intersect_key($data, $requiredes)) != sizeof($requiredes)) {
                    return;
            }
            $data = array_intersect_key($data, $finded_tags);
            $finded_tags = array_intersect_key($finded_tags, $data);
            $offer['data'] = array();
            foreach ($finded_tags as $key => $value) {
                    $offer['data'][$key] = $this->prepareField($data[$key]);
            }
            
            if($custom_elements){
                foreach ($custom_elements as $key => $value) {
                    $offer['data'][$key] = $value;
                }
            }
            $this->offers[] = $offer;
	}
        
        
        private function getFeed() {
		$yml  = '<?xml version="1.0"?>' . $this->eol;
		$yml .= '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0">' . $this->eol;
		$yml .= $this->createTag($this->shop,array('link'=>array('rel'=>'alternate','type'=>'text/html')));
                $yml .= '<updated>' . date('Y-m-d').'T'.date('h:i:s') . 'Z</updated>' . $this->eol;
		foreach ($this->offers as $offer) {
			$rows = $this->createTag($offer['data'],array(),'g:');
			unset($offer['data']);
			$yml .= $this->getElement($offer, 'entry', $rows);
		}
		$yml .= '</feed>';
                //пишем файл
                $rootPath = realpath(DIR_APPLICATION . '..'); 
                
                if(isset($this->general_setting['filename_export']) && $this->general_setting['filename_export']){
                    
                    
                    $file_name_and_path = $rootPath.'/'.$this->general_setting['filename_export'].'.xml';
                
                    $dirname = dirname($file_name_and_path);

                    $handle = FALSE;

                    if($file_name_and_path && !file_exists($file_name_and_path)){

                        if(!is_dir($dirname)){

                            mkdir($dirname,0777,TRUE);

                        }

                        $handle = fopen($file_name_and_path, "w");

                    }elseif(file_exists($file_name_and_path)){

                        $handle = fopen($file_name_and_path, "w");

                    }

                    if($handle){
                        fwrite($handle, $yml);
                        fclose($handle);
                    }
                    
                }
		return $yml;
	}

	private function getElement($attributes, $element_name, $element_value = '') {
            $retval = '<'.$element_name.' ';
            foreach ($attributes as $key => $value) {
                $retval .= $key .'="'.$value.'" ';
            }
            $retval .= $element_value ? '>' .$this->eol. $element_value.'</'.$element_name.'>' : '/>';
            $retval .= $this->eol;
            return $retval;
	}
        
	private function createTag($tags,$attributes=array(),$tag_add='') {
            
            $retval = '';
            foreach ($tags as $key => $value) {
                
                $attribute = '';
                
                if(isset($attributes[$key])){
                    
                    foreach ($attributes[$key] as $attribute_name => $attribute_value) {
                        
                        $attribute .= ' '.  $attribute_name.'="'.  $attribute_value.'"';
                        
                    }
                    
                }
                
                if($tag_add){
                    $key = $tag_add.$key;
                }
                
                
                if(!is_array($value) && $value){
                    $retval .= '<'.$key.$attribute.'>'.$value.'</'.$key .'>'.$this->eol;
                }elseif (is_array($value) && $key == $tag_add.'shipping') {
                    $retval .= $this->createDeliveryOptions($value); 
                }elseif (is_array($value)) {
                    foreach ($value as $key_two=>$value_two) {
                        $retval .= '<'.$key.$attribute.'>'.$value_two.'</'.$key.'>' . $this->eol;
                    }
                }
                
                
                
                /*
                if($tag_add){
                    $key = $tag_add.$key;
                }
                
                
                
                if(!is_array($value) && $value){
                    $retval .= '<'.$key.$attribute.'>'.$value.'</'.$key .'>'.$this->eol;
                }elseif (is_array($value) && $key == $tag_add.'shipping') {
                    $retval .= $this->createDeliveryOptions($value); 
                }elseif (is_array($value)) {
                    $retval .= $this->createTag($value,$attributes,$tag_add) . $this->eol;
                }
                 * 
                 */
                
                
                
                
                
                
            }
            return $retval;
	}
        
	private function createParam($params) {
            $retval = '';
            foreach ($params as $param) {
                $retval .= '<param name="'.$this->prepareField($param['name']);
                if (isset($param['unit'])) {
                        $retval .= '" unit="'.$this->prepareField($param['unit']);
                }
                $retval .= '">'.$this->prepareField($param['value']) . '</param>'.$this->eol;
            }
            return $retval;
	}
        
        private function createDeliveryOptions($delivery_options) {
            $retval = '';
            if($delivery_options){
                foreach ($delivery_options as $option) {
                    $retval .= '<g:shipping>'.$this->eol;
                    $retval .= $this->createTag($option,array(),'g:').$this->eol;
                    $retval .= '</g:shipping>'.$this->eol;
                }
            }
            return $retval;
	}
        
        public function sendErrorXML($errors) {
            $yml  = '<?xml version="1.0" encoding="UTF-8"?>' . $this->eol;
            $yml .= '<errors date="' . date('Y-m-d H:i') . '">' . $this->eol;
            foreach ($errors as $error) {
                $yml .= $this->createTag(array('error'=>$error)) . $this->eol;
            }
            $yml .= '</errors>';
            $this->response->addHeader('Content-Type: application/xml');
            $this->response->setOutput($yml);
        }
}
?>