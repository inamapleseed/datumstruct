<?php

/**  Define a Read Filter class implementing PHPExcel_Reader_IReadFilter  */ 
class CustomReadFilter implements PHPExcel_Reader_IReadFilter 
{ 
	protected $rules = array();
	protected $lines_only = false;
	public function __construct($rules, $lines_only = false) {
		$this->rules = $rules;
		$this->lines_only = $lines_only;
	}
	
    public function readCell($column, $row, $worksheetName = '') { 
        if (!in_array($worksheetName, array_keys($this->rules))) return true;

		if ($row >= $this->rules[$worksheetName][1] && $row <= $this->rules[$worksheetName][3]) { 
			if ($this->lines_only) return true;
			else if (in_array($column,range($this->rules[$worksheetName][0],$this->rules[$worksheetName][2]))) return true; 
		}
		
        return false; 
    } 
}