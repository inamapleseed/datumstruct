<?php
class ModelGkdExportProcessorCsv extends Model {
  
  public function getFile($file, $create = false) {
    if ($create) {
      $fh = fopen($file, 'w');
    } else {
      $fh = fopen($file, 'a');
    }
    
    return $fh;
  }
  
  public function closeFile($fh) {
    fclose($fh);
  }
  
  public function getTotalItems($config) {
    return $this->{'model_gkd_export_driver_'.$config['export_type']}->getTotalItems($config);
  }
  
  public function writeHeader($fh, $config) {
    $config['start'] = 0;
    $config['limit'] = 1;
    
    $columns = $this->{'model_gkd_export_driver_'.$config['export_type']}->getItems($config);
    
    if (isset($columns[0])) {
      $this->write_csv($fh, array_keys($columns[0]), ',');
    }
  }
  
  public function writeBody($fh, $config) {
    $items = $this->{'model_gkd_export_driver_'.$config['export_type']}->getItems($config);

    $row = 0;
    
    foreach ($items as $item) {
      $this->write_csv($fh, $item, ',');
      
      $row++;
    }
    
    // return false when no more items
    return !empty($items);
  }
  
  public function writeFooter($fh) {}
  
  private function write_csv($fh, array $fields, $delimiter = ',', $enclosure = '"', $mysql_null = false) {
    fputcsv($fh, array_map(array($this, 'escapeLineBreaks'), $fields), $delimiter, $enclosure);
    return;
    /* write method with delimiter for numbers starting by 0 (to be able to view in excel)
    $delimiter_esc = preg_quote($delimiter, '/');
    $enclosure_esc = preg_quote($enclosure, '/');

    $output = array();
    foreach ($fields as $field) {
        if ($field === null && $mysql_null) {
            $output[] = 'NULL';
            continue;
        }
        
        if (is_numeric($field) && $field[0] === '0') {
          $output[] = $enclosure . $field . $enclosure;
        } else {
          $output[] = preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field) ? (
              $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure
          ) : $field;
        }
    }

    fwrite($fh, join($delimiter, $output) . "\n");
    */
  }
  
  private function escapeLineBreaks($v) {
    return html_entity_decode(str_replace(array("\r\n","\n"), '', $v), ENT_QUOTES);
    //return preg_replace("/\r*\n/", "\\n", $v);
  }
}