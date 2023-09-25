<?php
    class ModelExtensionTimeslot extends Model{

        private $test_now = '';  // '2018-07-05 15:59:59'
        
        // Backend
        // Standard 
        // Delivery Time 17:00:00
        // Hours before Delivery Time 1
        // Cut Off Time = 16:00:00
        // Display Text: Standard_Test del time

        // Express
        // Delivery Time 15:00:00
        // Hours before Delivery Time 1
        // Cut Off Time = 14:00:00
        // Display Text: Express_Test del time

        // Assume now is '2018-07-05 15:59:59' (The text_now at the top)
        // For Standard
        /**
         * Array(
            *  0 => array(
            *       'timeslot_id'    =>  {THE_TIMESLOT_ID},
            *       'display_text'   =>  Standard_Test del time
            * )     
         * )
         */

         // For Express
        /**
         * Array(
            *  0 => array(
            *       'timeslot_id'    =>  {THE_TIMESLOT_ID},
            *       'display_text'   =>  Express_Test del time
            * )     
         * )
         */


        public function getEarliestAvailableDate($date, $type = 'standard'){

            $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'timeslot` WHERE type="'.$this->db->escape($type).'"');

            // No options
            if(!$query->num_rows){
                return false;
            }
            
            $now = date('Y-m-d H:i:s'); 

            if($this->test_now) $now = $this->test_now; // TESTING

            $no_slot_available = true;

            while($no_slot_available){

                foreach($query->rows as $slot){
                    
                    $slot_time = $date . ' ' . $slot['delivery_time'];

                    $slot_cutoff = date('Y-m-d H:i:s', strtotime('-' . $slot['hours_before_delivery_time'] . ' hours' . $slot_time));

                    if($slot_cutoff > $now){
                        return $date;
                    }

                } // End Foreach

                $date = date( 'Y-m-d', strtotime('+1 day ' . $date) );

            } // End While
        }

        public function getTimeslot($date = null){

            $now = date('Y-m-d H:i:s');
            
            if($this->test_now) $now = $this->test_now; // TESTING

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


        public function validateTimeslot($date, $timeslot = 0, $selected_type='standard'){
            if(
                !is_array($date) &&
                !is_array($date) &&
                $date && 
                (int)$timeslot
            ){
                // Validate Date
                $date = $this->db->escape($date); // Since not array so we can safely escape if got special characters

                $min = $this->config->get('timeslot_' . $selected_type . '_min'); //debug($data['min']);
                $max = $this->config->get('timeslot_' . $selected_type . '_max'); //debug($data['max']);

                $date_min = date( 'Y-m-d', strtotime( '+' . (int)$min . ' days ' . date('Y-m-d') ) );
                $date_max = date( 'Y-m-d', strtotime( '+' . (int)$max . ' days ' . date('Y-m-d') ) );

                if($date < $date_min || $date > $date_max){
                    return false;
                }
                // End Validate Date

                // Validate Time
                $now = date('Y-m-d H:i:s');

                $timeslot = (int)$timeslot;

                $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'timeslot`');

                $timeslot_available = array();

                foreach($query->rows as $slot){
                    $slot_time = $date . ' ' . $slot['delivery_time'];
                    $slot_cutoff = date('Y-m-d H:i:s', strtotime('-' . $slot['hours_before_delivery_time'] . ' hours' . $slot_time));

                    if($slot_cutoff > $now){
                        $timeslot_available[] =  $slot['timeslot_id'];
                    }
                }

                if(!$timeslot_available || !in_array($timeslot, $timeslot_available)){
                    return false;
                }
                // End Validate Time
                
                return true;
            }

            return false;
        }

    }
    