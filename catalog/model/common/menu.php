<?php
    class ModelCommonMenu extends Model{
        public function getMenu($menu_id = 0){
            if($menu_id){
                $query = $this->db->query('SELECT list FROM `' . DB_PREFIX . 'menus` WHERE menu_id="'.(int)$menu_id.'"');

                $menus = array();

                if($query->num_rows){
                    $list = html($query->row['list']);
                    $menus = json_decode($list, true);
                }

                return $menus;
            }

            return array();
        }
    }