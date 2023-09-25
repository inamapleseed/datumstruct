<?php
    class ControllerCommonMenu extends Controller{
        public function index($menu_id = 0){ 
            $menu_id = (int)$menu_id;

            $menus = "";

            if($menu_id){
                $this->load->model('common/menu');

                $menus = $this->model_common_menu->getMenu($menu_id);

                if($menus){
                    $raw = $menus;	//debug($raw);
                    $menus = array();
                    $this->arrangeMenu($menus, $raw);
                }

			}

            return $menus;
		}
		
        private function arrangeMenu(&$list, $menus, $level = 0) { 
			
			foreach($menus as $index => $menu) {

				if(!isset($menu['img']) || !is_file(DIR_IMAGE . $menu['img'])) $menu['img'] = false;
				
				if(!isset($menu['new_tab'])){
					$menu['new_tab'] = 0;
				}

				$menu['child'] = array();

				$this->setActive($menu);
				$this->setName($menu);

				//debug($menu);
				
				$check_href = '_' . $menu['query'];
				$check_href = strtolower($check_href);

				if( !strpos($check_href, 'http') ){
					$part = explode('&', $menu['query']);
					if(isset($part[1]) && trim($part[1]) != ''){
						$menu['href'] = $this->url->link($part[0], $part[1]);
					}
					else{
						$menu['href'] = $this->url->link($part[0]);
					}
				}
				else{
					$menu['href'] = $menu['query'];
				}

				if(!$menu['level']) {
					$list[] = $menu;
				}
				else{

					$last_key = 0;

					if( is_array($list) ) {
						$cache_list = $list; 
						end($cache_list);
						$last_key = key($cache_list);
					}

					$current_level = $menu['level'];
					$sub_menus = array();

					for($i = $index; $i < count($menus); $i++) { 
						if( !($current_level - $menus[$i]['level']) ) {
							$menus[$i]['level'] -= 1;
							$sub_menus[] = $menus[$i];	
						}
						else{
							break;
						}
					}

					$current_level -= 1;

					$this->arrangeMenu($list[$last_key]['child'], $sub_menus, $current_level);

					foreach($list[$last_key]['child'] as $child) {
						if($child['active']) {
							$list[$last_key]['active'] = "active";
						}
					}

				}
			}
        } // End function
        
        private function setName(&$current_menu) {
			$language_id = $this->config->get('config_language_id');
			$lang_indicator = 'L' . $language_id . '-';

			if( isset($current_menu['name']) && $current_menu['name'] ) {
				//debug($current_menu);
				
				if(is_array($current_menu['name'])){
					foreach($current_menu['name'] as $each){
						if(strpos('_' . $each, $lang_indicator)){
							$current_menu['name'] = str_replace($lang_indicator, '', $each);
						}
					}
				}
				else{
					$current_menu['name'] = $current_menu['label'];
				}

			}
		} // End function

		private function setActive(&$current_menu = array()) {
			$current_menu['active'] = '';

			if( isset($current_menu['query']) ) {
				$query = '_' . $current_menu['query'];
				$route = 'common/home';
				
				if( isset($this->request->get['route']) && !is_array($this->request->get['route']) ) {
					$route = $this->db->escape($this->request->get['route']);
				}
				
				if( $route && strpos($query, $route) ) {

					$query_url = explode('&', $query);

					if( isset($query_url[1]) && trim($query_url[1]) != '' ){
						
						$id_check = "";

						if( isset($this->request->get['product_id']) && !is_array($this->request->get['product_id']) ) {
							$id_check = 'product_id=' . (int)$this->request->get['product_id'];
						}
						elseif( isset($this->request->get['path']) && !is_array($this->request->get['path']) ) {
							$id_check = 'path=' . $this->db->escape($this->request->get['path']);
						}
						elseif( isset($this->request->get['information_id']) && !is_array($this->request->get['information_id']) ) {
							$id_check = 'information_id=' . (int)$this->request->get['information_id'];
						}
						elseif( isset($this->request->get['news_id']) && !is_array($this->request->get['news_id']) ) {
							$id_check = 'news_id=' . (int)$this->request->get['news_id'];
						}
						elseif( isset($this->request->get['ncat']) && !is_array($this->request->get['ncat']) ) {
							$id_check = 'ncat=' . (int)$this->request->get['ncat'];
						}

						if($id_check && in_array($id_check, $query_url)){
							$current_menu['active'] = 'active';	
						}
					}
					elseif(!strpos($query, '&')){
						$current_menu['active'] = 'active';
					}
				
				}
			}
		} // End function

    }