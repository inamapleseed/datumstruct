<?php
class Timeslot {
	private $day_range = 14;
	private $time1;
	private $time2;
	private $cur_time;
	
	public function __construct($registry) {
		date_default_timezone_set('Asia/Singapore');
		$this->db = $registry->get('db');
		$this->time1 = strtotime('00:00:00');
		$this->time2 = strtotime('00:59:59');
		$this->cur_time = strtotime(date('H:i:s'));
	}	
	
	public function getTimeslots() {
		$timeslot_data = array();
		
		$day_noun = $this->getDayNoun();
		$date_range = $this->createDateRange(date('Y-m-d', strtotime($day_noun)), date('Y-m-d', strtotime($day_noun.' + '.$this->day_range.' days')));
		
		if(!empty($date_range)) {
			foreach($date_range as $d) {
				// Check Mon - Sun Timeslots & Availability
				$day = date('N', strtotime($d));
				
				$apw_query = $this->db->query("SELECT time_slots_available_data, disabled FROM " . DB_PREFIX . "available_per_week WHERE day = '" . (int)$day . "'");
				$tsa_data = @unserialize($apw_query->row['time_slots_available_data']);
				$timeslots = array();
				// Build timeslots within date range
				if($tsa_data !== false) {
					foreach($tsa_data as $t) {
						// Check availability in special timeslots 1st
						$st_query = $this->db->query("SELECT DISTINCT availability FROM " . DB_PREFIX . "special_timeslot WHERE date = '".$d."' AND timeslot_id = '".(int)$t['timeslot']."'");
						if($st_query->num_rows) {
							$availability = (int)$st_query->row['availability'];
						}
						// Check in normal timeslots
						else {
							$availability = (int)$t['available'];
						}
						
						$ts_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "delivery_time_slots WHERE delivery_time_slots_id = '".(int)$t['timeslot']."'");
						$timeslot_text = $ts_query->row['time_start'].' - '.$ts_query->row['time_end'];
						$total = $this->getTotalOrderByTimeslot($timeslot_text, $d);
						if($total >= $availability) {
							$off = 1;
						}
						else {
							$off = 0;
						}
						
						$timeslots[] = array(	
							'timeslot' => $timeslot_text,
							'id' => (int)$t['timeslot'],
							'date' => $d,
							'off' => $off,
						);	
					}
				}
				
				$timeslot_data[] = array(
					'date' => $d,
					'day' => (int)$day,
					'day_text' => date('D', strtotime($d)),
					'timeslots' => $timeslots,
					'disabled' => $apw_query->row['disabled'],
				);
			}
		}
		
		return $timeslot_data;
	}
	
	public function getTotalOrderByTimeslot($timeslot, $delivery_date) {
		$query = $this->db->query("SELECT COUNT(timeslot) AS total FROM " . DB_PREFIX . "order_timeslot WHERE timeslot = '".$timeslot."' AND delivery_date = '".$delivery_date."'");
		return (int)$query->row['total'];
	}
	
	/**
	* Check availability in speical timeslots and normal timeslots
	*/
	public function availability($date, $timeslot_id) {
		// Check Mon - Sun Timeslots & Availability
		$day = date('N', strtotime($date));
		
		$apw_query = $this->db->query("SELECT time_slots_available_data, disabled FROM " . DB_PREFIX . "available_per_week WHERE day = '" . (int)$day . "'");
		$tsa_data = @unserialize($apw_query->row['time_slots_available_data']);
		$timeslots = array();
		// Build timeslots within date range
		if($tsa_data !== false) {
			foreach($tsa_data as $t) {
				if((int)$timeslot_id == (int)$t['timeslot']) {
					// Check availability in special timeslots 1st
					$st_query = $this->db->query("SELECT DISTINCT availability FROM " . DB_PREFIX . "special_timeslot WHERE date = '".$date."' AND timeslot_id = '".(int)$timeslot_id."'");
					if($st_query->num_rows) {
						$availability = (int)$st_query->row['availability'];
					}
					// Check in normal timeslots
					else {
						$availability = (int)$t['available'];
					}
					
					$ts_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "delivery_time_slots WHERE delivery_time_slots_id = '".(int)$timeslot_id."'");
					$timeslot_text = $ts_query->row['time_start'].' - '.$ts_query->row['time_end'];
					$total = $this->getTotalOrderByTimeslot($timeslot_text, $date);
					if($total >= $availability) {
						$off = 1;
					}
					else {
						$off = 0;
					}
					return $off;
				}
			}
		}
		return 0;
	}
	
	/**
	* Check whether the date is within the date range
	*/
	public function validateDate($date) {
		$day_noun = $this->getDayNoun();
		$date_range = $this->createDateRange(date('Y-m-d', strtotime($day_noun)), date('Y-m-d', strtotime($day_noun.' + '.$this->day_range.' days')));
		return in_array($date, array_values($date_range));
	}
	
	private function getDayNoun() {
		if($this->cur_time >= $this->time1 && $this->cur_time <= $this->time2) {
			return 'today';
		}
		else {
			return 'tomorrow';
		}
	}
	
	/**
	 * Returns every date between two dates as an array
	 * @param string $startDate the start of the date range
	 * @param string $endDate the end of the date range
	 * @param string $format DateTime format, default is Y-m-d
	 * @return array returns every date between $startDate and $endDate, formatted as "Y-m-d"
	 */
	private function createDateRange($startDate, $endDate, $format = "Y-m-d")
	{
		$begin = new DateTime($startDate);
		$end = new DateTime($endDate);

		$interval = new DateInterval('P1D'); // step 1 Day
		$dateRange = new DatePeriod($begin, $interval, $end);

		$range = [];
		foreach ($dateRange as $date) {
			$range[] = $date->format($format);
		}

		return $range;
	}
}