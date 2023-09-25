<?php
class ModelGkdExportProcessorXml extends Model {
  
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
    fwrite($fh, '<?xml version="1.0"?>'."\n".
                '<itemlist>'."\n".
                '<title>XML Export - '.date($this->language->get('datetime_format')).'</title>');
  }
  
  public function writeBody($fh, $config) {
    $products = $this->{'model_gkd_export_driver_'.$config['export_type']}->getItems($config);

    $row = 0;
    
    foreach ($products as $product) {
      $output = "\n".'<item>';
      
      foreach ($product as $k => $v) {
        if ($v) {
          $output .= "\n\t".'<'.$k.'><![CDATA['.html_entity_decode($v, ENT_QUOTES).']]></'.$k.'>';
          //$output .= '<'.$k.'><![CDATA[' . htmlentities($v, ENT_QUOTES, 'UTF-8', 0) . ']]></'.$k.'>';
        } else {
          $output .= "\n\t".'<'.$k.'/>';
        }
      }
      
      $output .= "\n".'</item>';
      
      fwrite($fh, $output);
      
      $row++;
    }
    
    // return false when no more products
    return !empty($output);
  }
  
  public function writeFooter($fh) {
    fwrite($fh, '</itemlist>');
  }
  
}