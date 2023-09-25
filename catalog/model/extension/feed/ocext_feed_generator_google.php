<?php
class ModelExtensionFeedOcextFeedGeneratorGoogle extends Model {
    
    /*
     * 
    public function getFilterData($key) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "ocext_feed_generator_google_filter_data` WHERE `key` = '".$key."' ";
        $query = $this->db->query($sql);
        $result = array();
        if($query->row){
            $result = json_decode($query->row['filter_data'], true);
        }
        return $result;
    }
     * 
     */
    
    private $model_oc = 'extension_feed';
    private $eol = "\n";
    private $head_script = array();
    private $body_script = array();
    private $elements = array();
    private $footer_script = array();
    
    public function getFilterData($key,$filter_data_group_id) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "ocext_feed_generator_google_filter_data` WHERE `key` = '".$key."' AND filter_data_group_id = ".$filter_data_group_id;
        $query = $this->db->query($sql);
        $result = array();
        if($query->row){
            $result = json_decode($query->row['filter_data'], true);
        }
        return $result;
    }
    
    
    
    
    public function getSettings($setting_type=0,$setting_id=FALSE,$setting_product_id=0) {
        
        $sql = "SELECT * FROM `" .   DB_PREFIX   . 'ocext_feed_generator_google_setting` ';
        
        $where = array();
        
        if($setting_type){
            $where[] = " setting_type = '".$setting_type."' ";
        }
        //$setting_id===0 - иначе придут все настройки, когда вызван новый фид, т.к. на $where[] = " setting_type = '".$setting_type."' "; много результатов
        if($setting_id || $setting_id===0){
            $where[] = " setting_id = ".$setting_id." ";
        }
        
        if($setting_product_id){
            
            $where[] = " setting LIKE '%\"setting_product_id\":\"".$setting_product_id."\"%' "; //"setting_product_id":"0"
            
        }
        
        if($where){
            $sql .= 'WHERE '.implode(' AND ', $where);
        }
        
        $result = $this->db->query($sql);
        
        return $result->rows;
        
    }
    
    public function getCategoriesAndProducts($ym_categories,$ym_manufacturers,$filter_data_group_id,$content_language_id) {
        $p2c = '';
        $ym_attributes = array();
        $all_yml_export_ocext_ym_filter_data_attributes = $this->{'model_'.$this->model_oc.'_ocext_feed_generator_google'}->getFilterData('ocext_feed_generator_google_ym_filter_attributes',$filter_data_group_id,$content_language_id);
        if($all_yml_export_ocext_ym_filter_data_attributes){
            $ym_attributes = $all_yml_export_ocext_ym_filter_data_attributes;
        }
        $ym_options = array();
        $all_yml_export_ocext_ym_filter_data_options = $this->{'model_'.$this->model_oc.'_ocext_feed_generator_google'}->getFilterData('ocext_feed_generator_google_ym_filter_options',$filter_data_group_id,$content_language_id);
        if($all_yml_export_ocext_ym_filter_data_options){
            $ym_options = $all_yml_export_ocext_ym_filter_data_options;
        }
        if($ym_categories){
            $sql_cats = array();
            foreach ($ym_categories as $category_id => $ym_category) {
                $sql_cats[] = " p2c.category_id = '".(int)$category_id."' ";
            }
            if($sql_cats){
                $p2c = ' AND ( '.implode(' OR ', $sql_cats).' ) ';
            }
        }
        
        $sql = "SELECT p.*, pd.name, pd.*, m.name AS manufacturer, p2c.category_id, ps.price AS special_price, pds.price AS discount_special_price FROM " . DB_PREFIX . "product p JOIN " . DB_PREFIX . "product_to_category AS p2c ON (p.product_id = p2c.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id) AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ps.date_start < NOW() AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()) LEFT JOIN " . DB_PREFIX . "product_discount pds ON (p.product_id = pds.product_id)  AND pds.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pds.date_start < NOW() AND (pds.date_end = '0000-00-00' OR pds.date_end > NOW())   WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND pd.language_id = '" . (int)$content_language_id . "' AND p.date_available <= NOW() AND p.status = '1' ".$p2c." GROUP BY p.product_id";
        
        if($this->config->get('ocext_feed_generator_google_ym_filter_prioritet')){
            $prioritet = $this->config->get('ocext_feed_generator_google_ym_filter_prioritet');
        }else{
            $prioritet['categories'] = 1;
            $prioritet['manufacturers'] = 2;
        }
        
        $query = $this->db->query($sql);
        $result['offers'] = array();
        $result['categories'] = array();
        $products_filtered = array();
        $ym_categories_whis_categories = $this->getYmCategoriesFromDb();
        if($query->rows){
            
            foreach ($query->rows as $product) {
                
                if($ym_categories){
                    foreach ($ym_categories as $category_id => $ym_category) {
                        if($category_id==$product['category_id'] && (!$ym_manufacturers || ($ym_manufacturers && isset($ym_manufacturers[$product['manufacturer_id']])))){
                            $products_filtered[$product['product_id']] = $product;
                            if(isset($ym_manufacturers[$product['manufacturer_id']]['setting_id']) && $ym_manufacturers[$product['manufacturer_id']]['setting_id']){
                                $products_filtered[$product['product_id']]['manufacturer_setting_id'] = $ym_manufacturers[$product['manufacturer_id']]['setting_id'];
                            }else{
                                $products_filtered[$product['product_id']]['manufacturer_setting_id'] = 0;
                            }
                            if(isset($ym_categories[$product['category_id']]['setting_id']) && $ym_categories[$product['category_id']]['setting_id']){
                                $products_filtered[$product['product_id']]['category_setting_id'] = $ym_categories[$product['category_id']]['setting_id'];
                            }else{
                                $products_filtered[$product['product_id']]['category_setting_id'] = 0;
                            }
                        }
                    }
                }elseif ($ym_manufacturers) {
                    foreach ($ym_manufacturers as $manufacturer_id => $ym_manufacturer) {
                        if($manufacturer_id==$product['manufacturer_id']  && (!$ym_categories || ($ym_categories && isset($ym_categories[$product['category_id']]))) ){
                            
                            $products_filtered[$product['product_id']] = $product;
                            if(isset($ym_manufacturers[$product['manufacturer_id']]['setting_id']) && $ym_manufacturers[$product['manufacturer_id']]['setting_id']){
                                $products_filtered[$product['product_id']]['manufacturer_setting_id'] = $ym_manufacturers[$product['manufacturer_id']]['setting_id'];
                            }else{
                                $products_filtered[$product['product_id']]['manufacturer_setting_id'] = 0;
                            }
                            if(isset($ym_categories[$product['category_id']]['setting_id']) && $ym_categories[$product['category_id']]['setting_id']){
                                $products_filtered[$product['product_id']]['category_setting_id'] = $ym_categories[$product['category_id']]['setting_id'];
                            }else{
                                $products_filtered[$product['product_id']]['category_setting_id'] = 0;
                            }
                            
                        }
                    }
                }
                
            }
            
            
            if($products_filtered){
                
                foreach ($products_filtered as $product) {
                    $set = array();
                    $setting = array();
                    if($prioritet['manufacturers']<$prioritet['categories']){

                        $set[$product['manufacturer_setting_id']] = $product['manufacturer_setting_id'];

                    }else{
                        
                        $set[$product['category_setting_id']] = $product['category_setting_id'];
                        
                    }

                    if($set){
                        $setting_row = $this->getSettings(0, end($set));
                        if($setting_row){
                            $setting = json_decode($setting_row[0]['setting'],TRUE);
                        }
                        if(!isset($setting['status']) || !$setting['status']){
                            $setting = array();
                        }
                    }

                    //получаем настройку продукта, если есть и меняем на неё шаблон продукта
                    $product_setting = $this->getSettings('product_setting', FALSE, $product['product_id']);
                    if($product_setting){
                        $set = array();
                        $set[$product_setting[0]['setting_id']] = $product_setting[0]['setting_id'];
                        $setting_update = json_decode($product_setting[0]['setting'],TRUE);

                        //если выключен и нет более верхнего шаблона, то обнуляем шаблон, иначе делаем более верхний
                        if(!$setting_update['status'] && !$setting){
                            $setting = array();
                        }elseif($setting_update['status']){
                            $setting = $setting_update;
                        }
                    }

                    if($set){
                        $result['offers'][$product['product_id']] = $product;
                        //добавлемя набор параметров по категории, если нет - нулевой шаблон
                        $result['offers'][$product['product_id']]['setting_id'] = $set;
                        $result['offers'][$product['product_id']]['ym_attributes'] = $this->getProductAttributes($product['product_id'], $ym_attributes,$content_language_id);
                        $result['offers'][$product['product_id']]['all_attributes'] = $this->getProductAttributes($product['product_id'],array(),$content_language_id);
                        $result['offers'][$product['product_id']]['ym_options'] = $this->getProductOptions($product['product_id'], $ym_options,$content_language_id);
                        $result['offers'][$product['product_id']]['all_options'] = $this->getProductOptions($product['product_id'],array(),$content_language_id);
                        $result['offers'][$product['product_id']]['images'] = $this->getProductImages($product['product_id']);
                        $result['offers'][$product['product_id']]['rec'] = $this->getProductRelated($product['product_id']);
                        $result['offers'][$product['product_id']]['option_url_param'] = '';
                        $result['offers'][$product['product_id']]['product_id_by_option_id'] = '';

                        $result['offers'][$product['product_id']]['setting'] = $setting;


                        $google_product_category = '';

                        if(isset($ym_categories_whis_categories[$product['category_id']])){
                            $result['offers'][$product['product_id']]['google_product_category'] = $ym_categories_whis_categories[$product['category_id']];
                        }else{
                            $result['offers'][$product['product_id']]['google_product_category'] = $google_product_category;
                        }
                        
                        //деление товаров по опциям
                        if(isset($setting['divide_on_option']) && $setting['divide_on_option'] && $result['offers'][$product['product_id']]['all_options']){

                            $option_id = 0;
                            if(isset($setting['divide_on_option_option_id']['field']['status']) && $setting['divide_on_option_option_id']['field']['status']){
                                $option_id = $setting['divide_on_option_option_id']['field']['option_id'];
                            }

                            $all_option = $result['offers'][$product['product_id']]['all_options'];

                            if($option_id){
                                foreach ($all_option as $key => $option) {

                                    if($option['option_id']!=$option_id){

                                        unset($all_option[$key]);

                                    }

                                }
                            }

                            if($all_option){
                                
                                $on_option_prefix = '-';
                                
                                if(isset($setting['divide_on_option_prefix'])){

                                    $setting['divide_on_option_prefix'] = trim($setting['divide_on_option_prefix']);

                                    if($setting['divide_on_option_prefix']){

                                        $on_option_prefix = $setting['divide_on_option_prefix'];

                                    }

                                }

                                foreach ($all_option as $key => $option) {

                                    foreach ($option['product_option_value'] as $product_option_value) {

                                        $result['offers'][$product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id']] = $result['offers'][$product['product_id']];

                                        if(isset($product_option_value['price']) && isset($product_option_value['price_prefix']) && $product_option_value['price_prefix']=='+'){

                                            $result['offers'][$product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id']]['price'] += $product_option_value['price'];

                                        }elseif (isset($product_option_value['price']) && isset($product_option_value['price_prefix']) && $product_option_value['price_prefix']=='-') {

                                            $result['offers'][$product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id']]['price'] -= $product_option_value['price'];

                                            if($result['offers'][$product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id']]['price']<0){

                                                $result['offers'][$product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id']]['price'] = 0.0;

                                            }

                                        }

                                        $result['offers'][$product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id']]['option_url_param'] = '&product_option_value_id='.$product_option_value['product_option_value_id'];

                                        $result['offers'][$product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id']]['option_add_model_name'] = $product_option_value['name'];

                                        $result['offers'][$product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id']]['product_id_by_option_id'] = $product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id'];

                                        if(isset($setting['divide_on_option_available_by_option_quantity']) && $setting['divide_on_option_available_by_option_quantity']){

                                            $result['offers'][$product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id']]['quantity'] = $product_option_value['quantity'];

                                        }

                                        foreach ($result['offers'][$product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id']]['all_options'] as $key => $all_option_row) {

                                            if($all_option_row['option_id']==$option_id){

                                                //unset($result['offers'][ $product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id'] ]['all_options'][$key]);
                                                //unset($result['offers'][ $product['product_id'].$on_option_prefix.$product_option_value['product_option_value_id'] ]['ym_options'][$key]);

                                            }

                                        }

                                    }

                                }

                                unset($result['offers'][$product['product_id']]);

                            }


                        }

                    }
                }
                
            }
            
            
            
            
            
            
        }
        
        //Если товары есть, соберем данные про категории
        if($result['offers']){
            $sql = "SELECT cd.name, c.category_id, c.parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE cd.language_id = '" . (int)$content_language_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' AND c.sort_order <> '-1'";
            $query = $this->db->query($sql);
            $result['categories'] = $query->rows;
        }
        return $result;
    }
    
    
    public function getYmCategoriesFromDb($data=array('category_id'=>1,'status'=>0)) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "ocext_feed_generator_google_ym_categories` ";
        $where = array();
        if ($data['category_id']==1) {
            $where[] = " category_id != '0' ";
        }
        
        if (!empty($data['ym_category_last_child'])) {
            $where[] = " ym_category_last_child LIKE '" . $this->db->escape($data['ym_category_last_child']) . "%' ";
        }
        
        if ($data['status']!='') {
            $where[] = " status = '" . (int)$data['status'] . "' ";
        }
        
        if($where){
            $where = ' WHERE '.implode(' AND ', $where);
        }else{
            $where = '';
        }
        
        $sql .= $where;
        
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                    $data['start'] = 0;
            }
            if ($data['limit'] < 1) {
                    $data['limit'] = 20;
            }
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        $query = $this->db->query($sql);
        
        $result = array();
        if($query->rows){
            foreach ($query->rows as $ym_category) {
                $categories = json_decode($ym_category['category_id'],TRUE);
                if($categories){
                    foreach ($categories as $category_id) {
                        //$result[$category_id] = $ym_category['ym_category_path'];
                        $result[$category_id] = $ym_category['google_category_id'];
                    }
                }
            }
        }
        
        return $result;
    }
    
    
    public function getProductAttributes($product_id,$ym_attributes=array(),$content_language_id) {
        
            $attributes_sql = array();
            //Вычеркнуть атрибуты
            if($ym_attributes){
                foreach ($ym_attributes as $attribute) {
                    $attributes_parts = explode('___', $attribute);
                    //0 - группа, 1 - id атрибута
                    $attributes_sql[$attributes_parts[0]][$attributes_parts[1]] = $attributes_parts[1];
                }
                
            }
            $product_attribute_group_data = array();

            $product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$content_language_id . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

            
            foreach ($product_attribute_group_query->rows as $product_attribute_group) {
                    $product_attribute_data = array();

                    $product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$content_language_id . "' AND pa.language_id = '" . (int)$content_language_id . "' ORDER BY a.sort_order, ad.name");

                    foreach ($product_attribute_query->rows as $product_attribute) {
                        if(!isset($attributes_sql[$product_attribute_group['attribute_group_id']][$product_attribute['attribute_id']])){
                            $product_attribute_data[] = array(
                                'attribute_id' => $product_attribute['attribute_id'],
                                'name'         => $product_attribute['name'],
                                'text'         => $product_attribute['text']
                            );
                        }
                    }

                    $product_attribute_group_data[] = array(
                            'attribute_group_id' => $product_attribute_group['attribute_group_id'],
                            'name'               => $product_attribute_group['name'],
                            'attribute'          => $product_attribute_data,
                            'unit'              => $this->getUnit($product_attribute_group['name'])
                    );
            }

            return $product_attribute_group_data;
    }
    
    public function getProductOptions($product_id,$ym_options=array(),$content_language_id) {
        
        
        $product_option_data = array();

        $product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$content_language_id . "' ORDER BY o.sort_order");

        foreach ($product_option_query->rows as $product_option) {
                $product_option_value_data = array();

                $product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$content_language_id . "' ORDER BY ov.sort_order");

                foreach ($product_option_value_query->rows as $product_option_value) {
                        $product_option_value_data[] = array(
                                'product_option_value_id' => $product_option_value['product_option_value_id'],
                                'option_value_id'         => $product_option_value['option_value_id'],
                                'name'                    => $product_option_value['name'],
                                'image'                   => $product_option_value['image'],
                                'quantity'                => $product_option_value['quantity'],
                                'subtract'                => $product_option_value['subtract'],
                                'price'                   => $product_option_value['price'],
                                'price_prefix'            => $product_option_value['price_prefix'],
                                'weight'                  => $product_option_value['weight'],
                                'weight_prefix'           => $product_option_value['weight_prefix']
                        );
                }
                if(!isset($ym_options[$product_option['option_id']])){
                    $product_option_data[] = array(
                        'product_option_id'    => $product_option['product_option_id'],
                        'product_option_value' => $product_option_value_data,
                        'option_id'            => $product_option['option_id'],
                        'name'                 => $product_option['name'],
                        'type'                 => $product_option['type'],
                        'value'                => $product_option['value'],
                        'required'             => $product_option['required'],
                        'unit' => $this->getUnit($product_option['name'])
                    );
                }
        }
        return $product_option_data;
}
    
public function getProductImages($product_id) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "product_image` WHERE product_id = '".$product_id."' AND image != 'no-image.jpg' AND image != 'no-image.png' AND image != 'no_image.png' AND image != 'no_image.jpg' ";
        $query = $this->db->query($sql);
        return $query->rows;
    }    
    
public function getProductRelated($product_id) {
            $product_related_data = array();

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");

            foreach ($query->rows as $result) {
                    $product_related_data[] = $result['related_id'];
            }

            return $product_related_data;
    }

private function getUnit($string){
        $units_parts = explode(' (', $string);
        $unit = '';
        if($units_parts && is_array($units_parts)){
            foreach ($units_parts as $units_part) {
                $parts = explode(')', $units_part);
                if($parts && count($parts)>1){
                    $unit = $parts[0];
                }
            }
        }
        return $unit;
    }



    
    
    
    
    
    
    
    
    
    
    
    
    /*
    public function getTemplateSetting($status=FALSE) {
        $sql = '';
        $result = array();
        if($status){

            $sql = "SELECT * FROM `" . DB_PREFIX . "ocext_all_yml_export_template_setting` WHERE status = 1";

        }else{

            $sql = "SELECT * FROM `" . DB_PREFIX . "ocext_all_yml_export_template_setting`";

        }
        if(!empty($sql)){
            $query = $this->db->query($sql);
            if($query->rows){
                foreach ($query->rows as $template_setting) {
                    $result[$template_setting['template_setting_id']] = json_decode($template_setting['template_setting'],TRUE);
                    $result[$template_setting['template_setting_id']]['template_setting_id'] = $template_setting['template_setting_id'];
                }
            }
        }
        return $result;
    }
    */
    public function getCategories() {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c  LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE  c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' AND c.sort_order <> '-1'");
            $categories = array();
            if($query->rows){
                foreach ($query->rows as $category){
                    $categories[ $category['cvategory_id'] ] = $category['cvategory_id'];
                }
            }
            return $categories;
    }
    
    public function getManufacturers() {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m  LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE  m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND m.status = '1' AND m.sort_order <> '-1'");
            $manufacturers = array();
            if($query->rows){
                foreach ($query->rows as $manufacturer){
                    $manufacturers[ $manufacturer['manufacturer_id'] ] = $manufacturer['manufacturer_id'];
                }
            }
            return $manufacturers;
    }
    
    
    
    
    
    
    
    public function showTable($table='ocext_all_yml_export_filter_data') {
        
        $check = $query = $this->db->query('SHOW TABLES from `'.DB_DATABASE.'` like "'.DB_PREFIX.$table.'" ');
        if(!$check->num_rows){
            return FALSE;
        }else{
            return TRUE;
        }
        
    }
    
    private function prepareField($field) {
        if(is_string($field)){
            $field = strip_tags(htmlspecialchars_decode($field));
            $from = array('"', '&', '>', '<', '\'','`','&acute;');
            $to = array('\"', '&amp;', '&gt;', '&lt;', '&apos;','','');
            $field = str_replace($from, $to, $field);
            $field = preg_replace('/[\r\n]+/s'," ", preg_replace('/[\r\n][ \t]+/s'," ",$field));
            $field = trim($field);
        }
        return $field;
    }
    
    public function addScript($data=array()) {
        
        $breadcrumb_status = $this->config->get('ocext_plugin_microdata_breadcrumps_status');
        $product_microdata_status = $this->config->get('ocext_plugin_microdata_product_status');
        if(isset($product_microdata_status) && $product_microdata_status){
            $product_microdata_status = 1;
        }else{
            $product_microdata_status = 0;
        }
        
        if(!$breadcrumb_status && !$product_microdata_status){
            return '';
        }
        
        if(!$data){
            return;
        }
        $result = $this->setJSONLD($data,$breadcrumb_status,$product_microdata_status);
        if($result){
            $this->addScriptToSession();
        }
        
    }
    

    public function addScriptToSession(){
        $jsonldmicrodata = '';
        foreach ($this->body_script as $type => $tmp){
            $jsonldmicrodata .= $this->head_script[$type];
            $jsonldmicrodata.= '{'.$this->eol;
            $jsonldmicrodata.= $this->body_script[$type];
            $jsonldmicrodata.= '}'.$this->eol;
            $jsonldmicrodata .= $this->footer_script[$type];
        }
        if($jsonldmicrodata){
            $this->session->data['microdataseourlgenerator'] = $jsonldmicrodata;
        }
    }
    
    public function getScript(){
        
        $breadcrumb_status = $this->config->get('ocext_plugin_microdata_breadcrumps_status');
        $product_microdata_status = $this->config->get('ocext_plugin_microdata_product_status');
        if(isset($product_microdata_status) && $product_microdata_status){
            $product_microdata_status = 1;
        }else{
            $product_microdata_status = 0;
        }
        
        if(!$breadcrumb_status && !$product_microdata_status){
            return '';
        }
        
        if(isset($this->session->data['microdataseourlgenerator'])){
            $script = $this->session->data['microdataseourlgenerator'];
            unset($this->session->data['microdataseourlgenerator']);
            return $script;
        }else{
            return '';
        }
    }
    
    private function setJSONLD($data,$breadcrumb_status,$product_microdata_status) {
        //breadcrumbs
        $result = FALSE;
        if(isset($data['breadcrumbs']) && count($data['breadcrumbs'])>1 && $breadcrumb_status){
            $breadcrumbs = $data['breadcrumbs'];
            if($this->setBreadCrumbs($breadcrumbs)){
                $result = TRUE;
            }
        }
        //product
        if($product_microdata_status && isset($this->request->get['route']) && $this->request->get['route']=='product/product' && isset($data['product_id'])){
            if($this->setProduct($data)){
                $result = TRUE;
            }
        }
        return $result;
    }
    
    private function setProduct($product) {
        $product['base_data'] = $this->getProduct($product['product_id']);
        $this->head_script['product'] = '<script type="application/ld+json">'.$this->eol;
        $this->footer_script['product'] = '</script>'.$this->eol;
        $this->body_script['product'] = '"@context":"http://schema.org",'.$this->eol;
        $this->body_script['product'] .= '"@type":"Product",'.$this->eol;
        $this->body_script['product'] .= '"name":"'.$this->prepareField($product['heading_title']).'",'.$this->eol;
        if(isset($product['popup']) && $product['popup']){
            $this->body_script['product'] .= '"image":"'.$this->prepareField($product['popup']).'",'.$this->eol;
        }
        
        if($product['description']){
            $this->body_script['product'] .= '"description":"'.$this->prepareField(strip_tags($product['description'])).'",'.$this->eol;
        }
        
        if(isset($product['manufacturer']) && $product['manufacturer']){
            $this->body_script['product'] .= '"brand":'.$this->eol;
            $this->body_script['product'] .= '{'.$this->eol;
            $this->body_script['product'] .= '"@type": "Brand",'.$this->eol;
            $this->body_script['product'] .= '"name": "'.$this->prepareField($product['manufacturer']).'"'.$this->eol;
            $this->body_script['product'] .= '},'.$this->eol;
        }
        
        if(isset($product['rating']) && $product['rating']){
            $this->body_script['product'] .= '"aggregateRating":'.$this->eol;
            $this->body_script['product'] .= '{'.$this->eol;
            $this->body_script['product'] .= '"@type": "AggregateRating",'.$this->eol;
            $this->body_script['product'] .= '"ratingValue": "'.(float)$product['rating'].'",'.$this->eol;
            $this->body_script['product'] .= '"reviewCount": "'.(int)$product['reviews'].'"'.$this->eol;
            $this->body_script['product'] .= '},'.$this->eol;
        }
        
        $this->body_script['product'] .= '"offers":'.$this->eol;
        $this->body_script['product'] .= '{'.$this->eol;
        $this->body_script['product'] .= '"@type": "Offer",'.$this->eol;
        if(isset($product['quantity']) && $product['quantity']){
            $this->body_script['product'] .= '"availability":"http://schema.org/InStock",'.$this->eol;
        }
        $this->body_script['product'] .= '"price": "'.(float)$product['base_data']['price'].'",'.$this->eol;
        $this->body_script['product'] .= '"priceCurrency": "'.$this->config->get('config_currency').'"'.$this->eol;
        $this->body_script['product'] .= '}';
        return TRUE;
    }
    
    private function setBreadCrumbs($breadcrumbs) {
        $result = FALSE;
        $this->head_script['breadcrumbs'] = '<script type="application/ld+json">'.$this->eol;
        $this->footer_script['breadcrumbs'] = '</script>'.$this->eol;
        $this->body_script['breadcrumbs'] = '"@context":"http://schema.org",'.$this->eol.'"@type":"BreadcrumbList",'.$this->eol.'"itemListElement":'.$this->eol."[";
        $this->elements['breadcrumbs'] = '';
        $number_element = 1;
        foreach ($breadcrumbs as $position => $breadcrumb) {
            $text = $this->prepareField($breadcrumb['text']);
            if($text==''){
                $this->load->language('common/header');
                $text = $this->language->get('text_home');
            }
            $this->elements['breadcrumbs'] .= "{".$this->eol;
                $this->elements['breadcrumbs'] .= '"@type":"ListItem",'.$this->eol;
                $this->elements['breadcrumbs'] .= '"position":'.$number_element.','.$this->eol;
                $this->elements['breadcrumbs'] .= '"item":';
                    $this->elements['breadcrumbs'] .= "{".$this->eol;
                        $this->elements['breadcrumbs'] .= '"@id":"'.$breadcrumb['href'].'",'.$this->eol;
                        $this->elements['breadcrumbs'] .= '"name":"'.$text.'"'.$this->eol;
                    $this->elements['breadcrumbs'] .= "}".$this->eol;
            if($number_element<count($breadcrumbs)){
                $this->elements['breadcrumbs'] .= "},".$this->eol;
            }else{
                $this->elements['breadcrumbs'] .= "}".$this->eol;
            }
            $number_element++;
        }
        if($this->elements['breadcrumbs']){
            $result = TRUE;
            $this->body_script['breadcrumbs'] .= $this->elements['breadcrumbs'];
        }
        $this->body_script['breadcrumbs'] .= ']'.$this->eol;
        return $result;
    }
    
    
    public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}
    
	
}
?>