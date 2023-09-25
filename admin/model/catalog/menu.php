<?php
    class ModelCatalogMenu extends Model{ 

        public function getMenus(){
            $query  =  $this->db->query("SELECT * FROM `". DB_PREFIX . "menus`");
            return $query->rows;
        }

        public function getMenu($menu_id = 0){
            $menu_id = (int)$menu_id;
            $menu = array();

            $query = $this->db->query("SELECT * FROM `". DB_PREFIX . "menus` WHERE menu_id=$menu_id");
            
            if($query->num_rows == 1){ 
                
                $menu = array(
                    'menu_id'   =>  $menu_id,
                    'title'     =>  $query->row['title'],
                    'status'    =>  $query->row['status'],
                    'menus'     =>  $query->row['list']
                );
            }

            return $menu;
        }

        public function removeMenu($menu_id = 0){
            if($menu_id){
                $this->db->query("DELETE FROM `". DB_PREFIX . "menus` WHERE menu_id='".(int)$menu_id."'");
            }
        }

        public function addMenu($data = array()){

            $this->db->query("INSERT INTO `". DB_PREFIX . "menus` SET title = '" . $this->db->escape( $data['title'] ) . "', status = '" .(int)$data['status'] . "', list='".$this->db->escape($data['menus'])."'");

        }

        public function editMenu($menu_id= 0, $data = array()){

            $menu_id = (int)$menu_id;

            $this->db->query("UPDATE `". DB_PREFIX . "menus` SET title = '" . $this->db->escape( $data['title'] ) . "', status = '" .(int)$data['status'] . "', list='".$this->db->escape($data['menus'])."' WHERE menu_id='".(int)$menu_id."'");
        }
        
        public function deleteAble($menu_id = 0){
            $menu_id = (int)$menu_id;
            $query = $this->db->query('SELECT COUNT(*) as total FROM `' . DB_PREFIX . 'setting` WHERE (`key` LIKE "%_header" OR `key` LIKE "%_footer") AND `code` <> "quickcheckout" AND `value`="'.$menu_id.'"');

            return $query->row['total'];
        }
    }