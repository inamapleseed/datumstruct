<?php
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

class ModelGkdExportProcessorSpout extends Model {
  
  public function __construct($registry) {
		parent::__construct($registry);
    require_once DIR_SYSTEM.'library/Spout/Autoloader/autoload.php';
  }
  
  public function getFile($file, $create = false) {
    return $file;
  }
  
  public function closeFile($file) {}
  
  public function getTotalItems($config) {
    return $this->{'model_gkd_export_driver_'.$config['export_type']}->getTotalItems($config);
  }
  
  public function writeHeader($file, $config) {
    switch (pathinfo($file, PATHINFO_EXTENSION)) {
      case 'csv' :
        $writer = WriterFactory::create(Type::CSV);
       break;
      case 'xls' :
      case 'xlsx' :
        $writer = WriterFactory::create(Type::XLSX);
       break;
      case 'ods' :
        $writer = WriterFactory::create(Type::ODS);
       break;
    }
    
    $writer->openToFile($file);
    
    $config['start'] = 0;
    $config['limit'] = 1;
    
    $columns = $this->{'model_gkd_export_driver_'.$config['export_type']}->getItems($config);
    
    $col = 0;
    
    if (isset($columns[0])) {
      $writer->addRow(array_keys($columns[0]));
    }

    $writer->close();
  }
  
  public function writeBody($file, $config) {
    $items = $this->{'model_gkd_export_driver_'.$config['export_type']}->getItems($config);
    
    switch (pathinfo($file, PATHINFO_EXTENSION)) {
      case 'csv' :
        $reader = ReaderFactory::create(Type::CSV);
        $writer = WriterFactory::create(Type::CSV);
       break;
      case 'xls' :
      case 'xlsx' :
        $reader = ReaderFactory::create(Type::XLSX);
        $writer = WriterFactory::create(Type::XLSX);
       break;
      case 'ods' :
        $reader = ReaderFactory::create(Type::ODS);
        $writer = WriterFactory::create(Type::ODS);
       break;
    }
    
    $reader->open($file);
    $reader->setShouldFormatDates(true);
    
    $writer->openToFile(DIR_CACHE.base64_encode($file));

    foreach ($reader->getSheetIterator() as $sheetIndex => $sheet) {
      if ($sheetIndex !== 1) {
        $writer->addNewSheetAndMakeItCurrent();
      }

      foreach ($sheet->getRowIterator() as $row) {
        $writer->addRow($row);
      }
    }

    $writer->addRows($items);
    
    $reader->close();
    $writer->close();
    
    unlink($file);
    rename(DIR_CACHE.base64_encode($file), $file);

    // return false when no more items
    return !empty($items);
  }
  
  public function writeFooter($fh) {}
}