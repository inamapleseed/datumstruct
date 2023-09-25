<?php
    define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	
    class Excel{
        private $session;
        private $styleArray = array(
				'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
			),
				'font'  =>  array(
				'bold'  => true,
				'color' => array('rgb' => 'FFFFFF'),
			),
				'fill'  =>  array(
				'type'      => PHPExcel_Style_Fill::FILL_SOLID,
				'color'     =>  array('rgb' => '007AC2'),
			)
        );
		
        public function __construct($registry){  
            $this->session = $registry->get('session');
		}
		
        public function read($inputFileName, $selected_sheet = 0){
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
                $sheetNames = $objReader->listWorksheetNames($inputFileName);
				} catch(Exception $e) {
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			}
            
            $sheetCount = $objPHPExcel->getSheetCount();
			
            $data = array();
			
            if($sheetCount > 1 && $selected_sheet === 0){
                for($i = 0; $i < $sheetCount; $i++){
                    $data[generateSlug($sheetNames[$i])] = $this->getData($objPHPExcel, $i);
				}
				
                return $data;
			}
            else{
                return  $this->getData($objPHPExcel, $selected_sheet);
			}
            
		}
		
        private function getData($objPHPExcel, $sheet = 0){
            $sheet = $objPHPExcel->getSheet($sheet); 
            $highestRow = $sheet->getHighestRow(); 
            $highestColumn = $sheet->getHighestColumn();
            
            $data = array(); 
			
            $column = $sheet->rangeToArray('A1:' . $highestColumn . '1', NULL, TRUE, FALSE);
            $column = end($column);
			
            for ($row = 2; $row <= $highestRow; $row++){ 
                $sets = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                $sets = end($sets);
                
                $row_data = array();
                foreach($sets as $index => $value){
                    $slug = generateSlug($column[$index]);
                    $slug = strtolower($slug);
                    $row_data[$slug] = $value;
				}
                
				
                $data[] = $row_data;
			}
			
            return $data;
		}
		
		// Generate and download
		
        public function generate($dataset = array(), $multiSheet = false, $filename = "excel_"){ 
            if(
			$this->logged() &&
			$dataset
            ){
                $objPHPExcell = new PHPExcel();
				
                $filename = DIR_EXCEL . $filename . date('Y-m-d His') .".xlsx";
                
                if($multiSheet){
                    $sheet_count = count($dataset);
					
                    $now_sheet = 0;
					
                    foreach($dataset as $sheet => $new_dataset){
						
                        $sheet_name = ( (int)$sheet )?'Worksheet '.$sheet:ucfirst($sheet);
						
                        // Sheet 1 is auto created and select so only create from sheet 2 onward
                        if($now_sheet){
                            $objPHPExcell->createSheet($now_sheet);
                            $objPHPExcell->setActiveSheetIndex($now_sheet);
						}
						
                        $objPHPExcell->getActiveSheet()->setTitle($sheet_name);
                        
                        $cellColumn = $this->excelColumn(end($new_dataset));
                        $lastCol = end($cellColumn);
						
                        $objPHPExcell = $this->write($cellColumn, $objPHPExcell, $new_dataset);
						
                        // Theme header
                        $objPHPExcell->getActiveSheet()->getStyle('A1:'.$lastCol.'1')->applyFromArray($this->styleArray);
						
                        $now_sheet++;
					}
				}
                else{
                    $cellColumn = $this->excelColumn(end($dataset));
                    $lastCol = end($cellColumn);
                    
                    $objPHPExcell = $this->write($cellColumn, $objPHPExcell, $dataset);
					
                    // Theme header
                    $objPHPExcell->getActiveSheet()->getStyle('A1:'.$lastCol.'1')->applyFromArray($this->styleArray);
				}
				
                $objPHPExcell->setActiveSheetIndex(0);
				
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcell, 'Excel2007');
				
                $objWriter->save( $filename );
                
                return $filename;
			}
            else{
                return false;
			}
		}
		
		private $allowed = array(
			'application/vnd.ms-excel',
			'application/vnd.ms-excel',
			'application/vnd.ms-excel',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.template'
		);
		
		public function download($physical_path = ""){ 
			if( 
			$this->logged() &&
			is_file($physical_path) && in_array(mime_content_type($physical_path), $this->allowed) ){
				$filename = basename($physical_path);
				$mine = mime_content_type($physical_path);
				
				if (!headers_sent()) {
					header('Content-Type: ' . $mine);
					header('Content-Disposition: attachment; filename="' . $filename . '"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($physical_path));
					
					if (ob_get_level()) {
						ob_end_clean();
					}
					
					readfile($physical_path, 'rb');
					
					exit();
				} 
				else {
					exit('Error: Headers already sent out!');
				}
				
			}
		}
		
        private function write($cellColumn, $objWorkSheet = null, $dataset){
            // Fill data
            foreach($dataset as $row_number => $row){ 
                
                $seleced_row = $row_number + 1; // There's no row 0
                
                $select_column = 0; // Column index
				
                foreach($row as $value){
                    
                    $selected_cell = $cellColumn[$select_column] . $seleced_row;
					
                    $objWorkSheet->getActiveSheet()->setCellValue($selected_cell, $value);
                    
                    $select_column++; // To next column
					
				}
			}
			
            return $objWorkSheet;
		}
		
		
        private function excelColumn($column = array()){
            $cellColumn = array();
            
            if($column && is_array($column)){
                $char = "A";
                $i = 0;   
                while($i < count($column)){
                    $cellColumn[] = $char;
                    $char++;
                    $i++;
				}
			}
			
            return $cellColumn;
		}
		
		
		private function logged() {
			return (
			// Validate request from admin
			isset($this->session->data['user_token']) ||    // v3
			isset($this->session->data['token'])            // v2
			);
        }
        

        /** Update 27/3/2018 - write_to function to add content to template instead of generate */
        public function write_to( $template_to_use = "", $prefix = "excel_", $dataset = array(), $multiSheet = false ){

            $response = array(
                'status'    =>  false,
                'filename'  =>  '',
            );

            if( $template_to_use && strpos( $template_to_use, "xlsx" ) < -1 ){
                $template_to_use .= ".xlsx";
            }

            if(is_file(DIR_EXCEL_TPL . $template_to_use)){
                if($dataset){

                    $newfile = DIR_EXCEL_COPY . $prefix . date('Y-m-d His') .".xlsx";
                    $tplfile = DIR_EXCEL_TPL . $template_to_use;

                    if (!copy($tplfile, $newfile)) {
                        return "Application does not have permission to perform copy action.";
                        die();
                    }
                    else{
                        $excel_obj = PHPExcel_IOFactory::createReader('Excel2007');
                        $excel_obj = $excel_obj->load($newfile); // Empty Load Template

                        if($multiSheet){
                            $now_sheet = 0;
                            foreach($dataset as $subsets){
                                
                                if($now_sheet){                                    
                                    $excel_obj->setActiveSheetIndex($now_sheet);
                                }

                                $cellColumn = $this->excelColumn(end($subsets));
                                $excel_obj = $this->write($cellColumn, $excel_obj, $subsets, 1);

                                $now_sheet++;
                            } 
                        }
                        else{ 
                            $cellColumn = $this->excelColumn(end($dataset));
                            $excel_obj = $this->write($cellColumn, $excel_obj, $dataset, 1);
                        }

                        $objWriter = PHPExcel_IOFactory::createWriter($excel_obj, 'Excel2007');
                        $objWriter->save($newfile);

                        $response["status"] = true;
                        $response["filename"] = $newfile;

                        return $response;
                    }
                }

                $response["status"] = false;
                $response["filename"] = "There is nothing to generate.";

                return $response;
            }

            $response["status"] = false;
            $response["filename"] = "Template not found: " . $template_to_use;
            
            return $response;
        }
	}				