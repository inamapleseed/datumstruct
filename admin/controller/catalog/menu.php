<?php
    class ControllerCatalogMenu extends Controller{
        private $error = array();
        
        public function index(){

            $this->load->language('catalog/menu');

            $this->load->model('catalog/menu');

            $this->document->setTitle($this->language->get('heading_title'));

            $this->getList();
        }

        protected function getList(){

            $token = $this->session->data['token'];

            $data['button_add'] = $this->language->get('button_add');

            $data['button_edit'] = $this->language->get('button_edit');

            $data['button_delete'] = $this->language->get('button_delete');

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_list'] = $this->language->get('text_list');

            $data['col_title'] = $this->language->get('col_title');
            $data['col_id'] = $this->language->get('col_id');
            $data['col_action'] = $this->language->get('col_action');
            $data['col_status'] = $this->language->get('col_status');

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $token, true)
            );
                
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('catalog/menu', 'token='.$this->session->data['token'])
            );

            $data['add'] = $this->url->link('catalog/menu/add', 'token=' . $token);

            $data = $this->load->controller('common/common', $data);

            $data['warning'] = "";
            
            if( isset($this->session->data['warning']) ){
                $data['warning'] = $this->session->data['warning'];
                unset($this->session->data['warning']);
            } 

            $data['success'] = "";
            
            if( isset($this->session->data['success']) ){
                $data['success'] = $this->session->data['success'];
                unset($this->session->data['success']);
            } 

            $menus = $this->model_catalog_menu->getMenus();

            $data['menus'] = array();

            foreach($menus as $menu){
                $data['menus'][] = array(
                    'menu_id'   =>  $menu['menu_id'],
                    'title'     =>  $menu['title'],
                    'status'    =>  $menu['status'],
                    'edit'      =>  $this->url->link('catalog/menu/edit', 'token='.$token.'&menu_id='.$menu['menu_id']),
                    'delete'    =>  $this->url->link('catalog/menu/remove', 'token='.$token.'&menu_id='.$menu['menu_id']),
                );
            }

            $this->response->setOutput($this->load->view('catalog/menu_list', $data));
        }  

        public function remove(){
            $this->load->language('catalog/menu');

            if( isset($this->request->get['menu_id']) && $this->validated()){

                $this->load->model('catalog/menu');

                if($this->model_catalog_menu->deleteAble($this->request->get['menu_id'])){
                    $this->session->data['warning'] = $this->language->get('text_error_remove');
                }
                else{
                    $this->load->model("catalog/menu");

                    $menu_id = (int)$this->request->get['menu_id'];

                    $this->model_catalog_menu->removeMenu($menu_id);

                    $this->session->data['success'] = $this->language->get('text_success_remove');
                }

            }

            if( isset($this->error['warning']) ){
                $this->session->data['warning'] = $this->error['warning'];
            }

            $this->response->redirect($this->url->link('catalog/menu', 'token='.$this->session->data['token']));
        }

        public function add() {
            $this->load->language('catalog/menu');

            $this->load->model('catalog/menu');

            if( $this->request->server['REQUEST_METHOD'] == "POST" && $this->validateForm() ){
                $this->model_catalog_menu->addMenu($this->request->post);

                $this->session->data['success'] = "You have successfully added a new menu";

                $this->response->redirect($this->url->link('catalog/menu', 'token='.$this->session->data['token']));
            }

            $this->document->setTitle($this->language->get('heading_title'));

            $this->getForm();
        }

        public function edit(){
            $this->load->language('catalog/menu');

            $this->load->model('catalog/menu');

            if( $this->request->server['REQUEST_METHOD'] == "POST" && $this->validateForm() ){
                $this->model_catalog_menu->editMenu($this->request->get['menu_id'], $this->request->post);

                $this->session->data['success'] = "You have successfully modify a menu";

                $this->response->redirect($this->url->link('catalog/menu', 'token='.$this->session->data['token']));
            }

            $this->document->setTitle($this->language->get('heading_title'));

            $this->getForm();
        }

        protected function getForm(){

            $indent = $data['indent'] = 16;

            $this->document->addStyle('view/stylesheet/menu.css');

            $token = $data['token'] = $this->session->data['token'];

            $data['button_save'] = $this->language->get('button_save');

            $data['button_cancel'] = $this->language->get('button_cancel');

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_form'] = $this->language->get('text_form');

            $data['text_list'] = $this->language->get('text_list');

            $data['field_title'] = $this->language->get('field_title');

            $data['field_status'] = $this->language->get('field_status');

            $data['text_enabled'] = $this->language->get('text_enabled');
            
            $data['text_disabled'] = $this->language->get('text_disabled');

            $data['note_1'] = $this->language->get('note_1');

            $data['note_2'] = $this->language->get('note_2');

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $token, true)
            );
                
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('catalog/menu', 'token=' . $token, true)
            );

            $data['action'] = $this->url->link('catalog/menu/add', 'token=' . $token);
            $data['cancel'] = $this->url->link('catalog/menu', 'token=' . $token );

            $menu = array();

            if( isset($this->request->get['menu_id']) ){ 
                $menu_id = $this->request->get['menu_id'];

                $data['action'] = $this->url->link('catalog/menu/edit', 'token=' . $token . "&menu_id=" . $menu_id);

                $menu = $this->model_catalog_menu->getMenu($menu_id);
            }

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_form'),
                'href' =>  $data['action']
            );

            $data['warning'] = "";

            if( isset($this->session->data['warning']) ){
                $data['warning'] = $this->session->data['warning'];
                unset($this->session->data['warning']);
            } 

            $data['error_title'] = "";

            if( isset($this->error['title']) ){
                $data['error_title'] = $this->error['title'];
            }

            if(isset($this->request->post['title'])){
                $data['title'] = $this->request->post['title'];
            }
            elseif(isset($menu['title'])){
                $data['title'] = $menu['title'];
            }
            else{
                $data['title'] = "";
            }

            if(isset($this->request->post['status'])){
                $data['status'] = $this->request->post['status'];
            }
            elseif(isset($menu['status'])){
                $data['status'] = $menu['status'];
            }
            else{
                $data['status'] = "";
            }
            
            if(isset($this->request->post['menus'])){ 
                $data['menus'] = $this->request->post['menus'];
            }
            elseif(isset($menu['menus'])){
                $data['menus'] = html($menu['menus']);
            }
            else{
                $data['menus'] = '';
            }

            $menus = $data['menus'];

            $this->load->model('tool/image');

            $placeholder = $this->model_tool_image->resize('no_image.png', 40, 40);

            $data['placeholder'] = $placeholder;

            $data['menus_interface'] = array();

            if($menus && trim($menus) != '[]'){
                $menus = json_decode($menus, true);
                if($menus){
                    
                    // Adding new tab variable
                    foreach($menus as &$each){
                        if(!isset($each['new_tab'])) $each['new_tab'] = 0;

                        $each['thumb'] = $placeholder;
                        if(isset($each['img']) && is_file(DIR_IMAGE . $each['img'])){
                            $each['thumb'] = $this->model_tool_image->resize($each['img'], 40, 40);
                        }else{
                            $each['img'] = '';
                        }
                    }
                    // End

                    $data['menus_interface'] = $menus;
                }
            }

            //debug($menus);

            $filters = array(
                'sort'  => 'name',
                'order'  => 'ASC',
                'start' => 0,
                'limit' => 999999,
                'backend_only'  =>  0 // Category only
            );

            $this->load->model('localisation/language');

            $data['languages'] = $this->model_localisation_language->getLanguages(); //debug($data['languages']);

            $statics = array(
                'common/home'               => array(),
                'information/contact'       => array(),
                'information/sitemap'       => array(),
                'information/faq'           => array(),
                'testimonial/testimonial'   => array(),
                'product/special'           =>  array(),
                'product/gift_card_category' => array(),
            );

            $account = array(
                'account/login'         =>  array(),
                'account/register'       =>  array(),
                'account/account'       =>  array(),
                'account/order'         =>  array(),
                'account/wishlist'      =>  array(),
                'account/newsletter'    =>  array(),
                'account/return/add'    =>  array(),
                'account/voucher'       =>  array(),
            );

            $cart = array(
                'checkout/cart'         =>  array()
            );

            $blog = array(
                'news/ncategory'       =>  array()
            ); 

            $categories = array(
                'product/category'      =>  array()
            );

            $manufactures = array(
                'product/manufacturer'  =>  array()
            );

            $data["menu_options"] = array(
                'Static'        =>  $statics,
                'Account'       =>  $account,
                'Cart'          =>  $cart,
                'Categories'    =>  $categories,
                'Manufacturer'  =>  $manufactures,
                'Blog'          =>  $blog,
                'Information'   =>  array(),
            );

            foreach($data["menu_options"] as $type => $list){
                foreach($list as $route => $link){
                    $var = generateSlug($route);
                    $data["menu_options"][$type][$route] = array(
                        'name'  =>  $this->language->get($var),
                        'query' =>  $route,
                        'id'    =>  $var,
                    );
                }
            }

            $this->load->model('catalog/category');

            $categories = $this->model_catalog_category->getCategories($filters);

            foreach($categories as $category){

                $data["menu_options"]['Categories']['product/category&path=' . $category['category_id']] = array(
                    'name'  =>  $category['short_name'],
                    'query' =>  'product/category&path=' . $category['category_path'],
                    'id'    =>  generateSlug('product/category&path=' . $category['category_path']),
                );
            }

            $this->load->model('catalog/manufacturer');

            $manufacturers = $this->model_catalog_manufacturer->getManufacturers($filters);

            foreach ($manufacturers as $manufacturer) {
                $data["menu_options"]['Manufacturer']['product/category&manufacturer_ids[]=' . $manufacturer['manufacturer_id']] = array(     
                    'name'  => $manufacturer['name'],
                    'query' => 'product/category&manufacturer_ids[]=' . $manufacturer['manufacturer_id'],
                    'id'    =>  generateSlug('product/category&manufacturer_ids[]=' . $manufacturer['manufacturer_id']),                    
                );
            }

            $filters['sort'] = 'title';

            $this->load->model('catalog/information');
            $informations = $this->model_catalog_information->getInformations($filters);
           
            foreach($informations as $information){

                $data["menu_options"]['Information']['information/information&information_id=' . $information['information_id']] = array(
                    'name'  => $information['title'],
                    'query' =>  'information/information&information_id=' . $information['information_id'],
                    'id'    =>  generateSlug('information/information&information_id[]=' . $information['information_id']), 
                );
            }
           
            $this->load->model('catalog/ncategory');

            $ncategories = $this->model_catalog_ncategory->getncategories(0);
            
            foreach($ncategories as $ncategory){
                $data["menu_options"]['Blog']['news/ncategory&ncat=' . $ncategory['ncategory_id']] = array(
                    'name'  => $ncategory['name'],
                    'query' =>  'news/ncategory&ncat=' . $ncategory['ncategory_id'],
                    'id'    =>  generateSlug('news/ncategory&ncat[]=' . $ncategory['ncategory_id']), 
                );
            }

            $data = $this->load->controller('common/common', $data);

            $this->response->setOutput($this->load->view('catalog/menu_form', $data));
        }

        protected function validated(){
            if(!$this->user->hasPermission('access', 'catalog/menu')){
                $this->error['warning'] = "You don't have sufficient permission to modify this page";
            }

            return !$this->error;
        }

        public function validateForm(){
            $this->validated();

            if(utf8_strlen($this->request->post['title']) < 2 || utf8_strlen($this->request->post['title']) > 32){
                $this->error['title'] = "Title must be wihin 2 to 32 characters";
            }

            return !$this->error;
        }
    }
    