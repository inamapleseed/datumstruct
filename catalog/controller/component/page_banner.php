<?php
    class ControllerComponentPagebanner extends Controller{
        private $route_n_query = array(
            'product/category'           =>  'path',
            'product/product'            =>  'product_id',

            'news/ncategory'             =>  'ncat',
            'news/article'               =>  'news_id',

            'product/special'            =>  '*',
            'product/search'             =>  '*',
            'product/shop_product'       =>  'path',

            'testimonial/testimonial'    =>  '*',
            'information/sitemap'        =>  '*',
            'information/faq'            =>  '*',
            
            'information/information'    =>  'information_id',
            'information/contact'        =>  '*',
            'information/%'              =>  '*',

            'checkout/%'                 =>  '*',
            'quickcheckout/%'            =>  '*',
            'account/%'                  =>  '*',
        );
        
        public function index(){

            $current_route = 'common/home';
            $current_query = '*';

            if( !isset($this->request->get['route']) || is_array($this->request->get['route'])){
                return '';
            }

            if(isset($this->route_n_query[$this->request->get['route']])){
                // Get Specific Page Banner
                $current_route = $this->request->get['route'];
                $key = $this->route_n_query[$this->request->get['route']];
                
                if( $key != "*" && isset($this->request->get[$key]) && !is_array($this->request->get[$key])){
                    // Specific Page
                    $current_query = $key . '=' . $this->db->escape($this->request->get[$key]);
                }
                else{
                    // General all page that shares the same tpl
                    $current_query = $key;
                }
            }
            else{
                // Get Shared Route Folder. (EG: All of account page, Or all of checkout page)
                // Eg: account/account or account/register or account/login ===> Share same route start  Folder of "account"
                $route_part = explode('/', $this->request->get['route']);
                if(count($route_part) > 1){
                    $shared_route_folder = $route_part[0] . '/%';
                    
                    // Fix Quickcheckout
                    if( strpos('_' . $shared_route_folder, 'quickcheckout') ){
                        $shared_route_folder = str_replace('quickcheckout', 'checkout', $shared_route_folder);
                    }

                    if(isset($this->route_n_query[$shared_route_folder])){
                        $current_route=$shared_route_folder;
                        $key = $this->route_n_query[$shared_route_folder];
                        if( $key != "*" && isset($this->request->get[$key]) && !is_array($this->request->get[$key])){
                            $current_query = $key . "=" . $this->db->escape($this->request->get[$key]);
                        }
                        else{
                            $current_query = $key;
                        }
                    }
                }
            } // End If ELSE

            if($current_route && $current_query){ 

                $additional_sub_conditions = '';

                if($current_route == 'product/category' && isset($this->request->get['path'])){
                    $category_id = (int)$this->request->get['path'];
                    $query_cat = $this->db->query('SELECT DISTINCT category_id FROM `' . DB_PREFIX . 'category_path` WHERE path_id="'.$category_id.'"');
                    foreach($query_cat->rows as $each){
                        $additional_sub_conditions = ' || query="path='.(int)$each['category_id'].'"'; // Get Parent Banner
                    }
                }

                if($current_route == 'news/ncategory' && isset($this->request->get['ncat'])){
                    $this->load->model('catalog/ncategory');
                    $ncategory_id = (int)$this->request->get['ncat'];
                    $ncategory_info = $this->model_catalog_ncategory->getncategory($ncategory_id);
                    if(isset($ncategory_info['parent_id']) && $ncategory_info['parent_id']){
                        $additional_sub_conditions = ' || query="ncat='.(int)$ncategory_info['parent_id'].'"'; // Get Parent Banner
                    }
                }

                $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'page_banner` WHERE route="'. $this->db->escape($current_route) . '" AND (query="'.$this->db->escape($current_query).'" || query="*" ' . $additional_sub_conditions . ') ORDER BY pb_id DESC');
                // debug($query->rows);
                
                $banner_info = $query->row;

                if($query->num_rows > 0){
                    foreach($query->rows as $each){
                        if($each['query'] != '*'){
                            $banner_info = $each;
                            break;
                        }
                    }
                }
                else{
                    // Inner content don't have it's own banner so, get parent category banner
                    if($current_route == 'news/article' && isset($this->request->get['ncat'])){
                        $this->load->model('catalog/ncategory');
                        $ncategory_id = (int)$this->request->get['ncat'];
                        $ncategory_info = $this->model_catalog_ncategory->getncategory($ncategory_id);
                    
                        if(isset($ncategory_info['parent_id']) && $ncategory_info['parent_id']){
                            $additional_sub_conditions = ' || query="ncat='.(int)$ncategory_info['parent_id'].'"'; // Get Parent Banner
                        }

                        $current_route = 'news/ncategory';

                        $current_query = 'ncat='.$ncategory_id;
                        
                        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'page_banner` WHERE route="'. $this->db->escape($current_route) . '" AND ( query="'.$this->db->escape($current_query).'" || query="*" ' . $additional_sub_conditions . ' ) ORDER BY pb_id DESC');

                        if($query->num_rows > 0){
                            foreach($query->rows as $each){
                                if($each['query'] != '*'){
                                    $banner_info = $each;
                                    break;
                                }
                            }
                        }
                    }

                    
    
                }
                
                if($banner_info && is_file( DIR_IMAGE . $banner_info['image'])){
                    $data = $this->processBanner($banner_info);
                    return $this->load->view('component/page_banner', $data);
                }
                else {
                    // if no specific banner then check 'all pages banner' except home page  
                    if($current_route != 'common/home') {
                        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'page_banner` WHERE route="all" AND query="*" ORDER BY pb_id DESC');
                        // debug($query->rows);
                        
                        $banner_info = $query->row;

                        if($banner_info && is_file( DIR_IMAGE . $banner_info['image'])){
                            $data = $this->processBanner($banner_info);
                            return $this->load->view('component/page_banner', $data);
                        }
                    }
                }
            }
                
            
        } // End Index

        private function processBanner($banner_info) {
            $this->load->model('tool/image');
            $data = array();
            $data['title']  =   $this->document->getTitle();
            $data['page_name'] = $banner_info['page_name'];
            $data['mobile_banner_image'] = $data['banner_image'] = 'image/' . $banner_info['image'];
            if(is_file( DIR_IMAGE . $banner_info['mobile_image'])){
                $data['mobile_banner_image'] = 'image/' . $banner_info['mobile_image'];
            }
            return $data;
        }
    }