<?php
    class ModelCatalogMenu extends Model{
        private $menu = DB_PREFIX . "menus";

        public function getMenu($menu_id = 0){
            $query = $this->db->query("SELECT * FROM $this->menu WHERE menu_id = '".(int)$menu_id."'");
            if($query->num_rows){
                return $query->row;
            }
            return false;
        }
    }