<?php
    class ModelExtensionModuleTimeslot extends Model{

        public function getTimeslot($date = null){

            $now = date('Y-m-d H:i:s');

            if(!$date) $date = date('Y-m-d');

            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'timeslot`');

            $timeslots = array();

            foreach($query->rows as $slot){
                $slot_time = $date . ' ' . $slot['delivery_time'];
                $slot_cutoff = date('Y-m-d H:i:s', strtotime('-' . $slot['hours_before_delivery_time'] . ' hours' . $slot_time));

                if($slot_cutoff > $now){
                    $timeslots[] =  $slot;
                }
            }

            return $timeslots;
            
        }


        public function validTimeslot($timeslots){

        }

    }
    