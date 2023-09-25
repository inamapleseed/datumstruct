<?php
    class ModelExtensionTimeslot extends Controller{
        
        public function setTimeslot($timeslot){
            $this->db->query('TRUNCATE TABLE `' . DB_PREFIX . 'timeslot`');
            
            foreach($timeslot as $type => $slots){
                
                foreach($slots as $slot){

                    debug($slot);

                    $this->db->query('INSERT INTO `' . DB_PREFIX . 'timeslot` SET 
                    type="'.$this->db->escape($type).'", 
                    delivery_time="' . $this->db->escape($slot['delivery_time']) .'",
                    hours_before_delivery_time="'. (int)$slot['hours_before_delivery_time'] . '",
                    displayed_delivery_time="'. $this->db->escape($slot['displayed_delivery_time']) .'",
                    additional_cost="'. (float)$slot['additional_cost'] .'"
                    ');
                }         
                
            }
        }

        public function getTimeslot(){
            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'timeslot`');

            return $query->rows;
        }

    }