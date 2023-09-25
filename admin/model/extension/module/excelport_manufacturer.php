<?php 
class ModelExtensionModuleExcelportmanufacturer extends ModelExtensionModuleExcelport {
    public function importXLSManufacturers($file, $importLimit = 100, $addAsNew = false) {
        $this->language->load('extension/module/excelport');
        if (!is_numeric($importLimit) || $importLimit < 10 || $importLimit > 800) throw new Exception($this->language->get('excelport_import_limit_invalid'));
        
        $default_language = $this->config->get('config_language_id');
        $language = $default_language;
        $this->config->set('config_language_id', $language);
        
        $progress = $this->getProgress();
        $progress['importedCount'] = !empty($progress['importedCount']) ? $progress['importedCount'] : 0;
        $progress['done'] = false;
        
        require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
        // Create new PHPExcel object
        
        require_once(IMODULE_ROOT.'vendors/phpexcel/CustomReadFilter.php');
        $chunkFilter = new CustomReadFilter(array('Manufacturers' => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1)), 'manufacturers' => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1))), true); 
        
        $madeImports = false;
        $objReader = new PHPExcel_Reader_Excel2007();
        $objReader->setReadFilter($chunkFilter);
        $objReader->setReadDataOnly(true);
        $objReader->setLoadSheetsOnly(array("Manufacturers", "manufacturers"));
        $objPHPExcel = $objReader->load($file);
        $progress['importingFile'] = substr($file, strripos($file, '/') + 1);
        $manufacturersSheet = 0;
        
        $manufacturersSheetObj = $objPHPExcel->setActiveSheetIndex($manufacturersSheet);
        
        $progress['all'] = -1; //(int)(($manufacturersSheetObj->getHighestRow() - 2)/$this->productSize);
        $this->setProgress($progress);
        
        $manufacturer_map = array(
            'manufacturer_id'   => 0,
            'name'              => 1,
            'stores'            => 2,
            'keyword'           => 3,
            'image'             => 4,
            'sort_order'        => 5
        );
        
        $source = array(0,2 + ($progress['importedCount']));
        
        $this->load->model('setting/store');
        $stores = array_merge(array(0 => array('store_id' => 0, 'name' => 'Default', 'url' => NULL, 'ssl' => NULL)),$this->model_setting_store->getStores());
        
        do {
            $this->custom_set_time_limit();
            $manufacturer_name = strval($manufacturersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $manufacturer_map['name']) . ($source[1]))->getValue());
            if (!empty($manufacturer_name)) {
                $manufacturer_id = (int)$manufacturersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $manufacturer_map['manufacturer_id']) . ($source[1]))->getValue();
                
                $manufacturer_sort_order = (int)str_replace(' ', '', $manufacturersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $manufacturer_map['sort_order']) . ($source[1]))->getValue());
                
                $manufacturer_store = array();
                $manufacturer_stores = explode(',', str_replace('.', ',', strval($manufacturersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $manufacturer_map['stores']) . ($source[1]))->getValue())));
                foreach ($manufacturer_stores as $store) {
                    $store = trim($store);
                    if ($store !== '') $manufacturer_store[] = $store;
                }
                
                $manufacturer = array(
                    'name' => $manufacturer_name,
                    'manufacturer_store' => $manufacturer_store,
                    'keyword' => $manufacturersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $manufacturer_map['keyword']) . ($source[1]))->getValue(),
                    'image' => trim($manufacturersSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $manufacturer_map['image']) . ($source[1]))->getValue()),
                    'sort_order' => $manufacturer_sort_order
                );
                
                // Extras
                foreach ($this->extraGeneralFields['Manufacturers'] as $extra) {
                    if (!empty($extra['name']) && !empty($extra['column_light'])) {
                        $manufacturer[$extra['name']] = $manufacturersSheetObj->getCell($extra['column_light'] . $source[1])->getValue();  
                    }
                }
                
                if (!$addAsNew) {
                    $exists = false;
                    $existsQuery = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = ".$manufacturer_id);
                    
                    $exists = $existsQuery->num_rows > 0;
                            
                    if ($exists) {
                        $this->editManufacturer($manufacturer_id, $manufacturer);
                    } else {
                        $this->addManufacturer($manufacturer_id, $manufacturer);
                    }
                } else {
                    $this->addManufacturer('', $manufacturer);
                }
                
                $progress['current']++;
                $progress['importedCount']++;
                $madeImports = true;
                $this->setProgress($progress);
            }
            $source[1] += 1;
        } while (!empty($manufacturer_name));
        $progress['done'] = true;
        if (!$madeImports) {
            $progress['importedCount'] = 0;
            array_shift($this->session->data['uploaded_files']);
        }
        $this->setProgress($progress);
        
        $this->config->set('config_language_id', $default_language);
    }
    
    public function exportXLSManufacturers($store, $destinationFolder = '', $manufacturerNumber = 800, $export_filters = array()) {
        $this->language->load('extension/module/excelport');
        $this->folderCheck($destinationFolder);
    
        $progress = $this->getProgress();
        $progress['done'] = false;
        
        $file = IMODULE_ROOT . 'vendors/excelport/template_manufacturer.xlsx';
        
        $default_language = $this->config->get('config_language_id');
        $language = $default_language;
        $this->config->set('config_language_id', $language);
        require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
        
        if (!empty($progress['populateAll'])) {
            $all = $this->db->query($this->getQuery($export_filters, $store, $language, true));
            $progress['all'] = $all->num_rows ? (int)$all->row['count'] : 0;
            unset($progress['populateAll']);
            $this->setProgress($progress);
        }
        
        $this->setData('Manufacturers', $destinationFolder, $language);
        
        $manufacturersSheet = 0;
        $manufacturersMetaSheet = 1;
        
        $storesStart = array(0,3);
        $this->load->model('setting/store');
        $stores = array_merge(array(0 => array('store_id' => 0, 'name' => 'Default', 'url' => NULL, 'ssl' => NULL)),$this->model_setting_store->getStores());
        
        $manufacturers_generals = array(
            'manufacturer_id'   => 0,
            'name'              => 1,
            'stores'            => 2,
            'keyword'           => 3,
            'image'             => 4,
            'sort_order'        => 5
        );
        
        // Extra fields
        $extras = array();
        foreach ($this->extraGeneralFields['Manufacturers'] as $extra) {
            if (!empty($extra['name']) && !empty($extra['column_light'])) {
                $extras[$extra['name']] = $extra['column_light'];
            }
        }
        
        $manufacturers_target = array(0,2);
        
        $this->load->model('localisation/language');
        $languageQuery = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
        
        $name = 'manufacturers_excelport_' . $languageQuery['code'] . '_' . str_replace('/', '_', substr(HTTP_CATALOG, 7, strlen(HTTP_CATALOG) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
        $resultName = $name . '.xlsx';
        $result = $destinationFolder . '/' . $name . '.xlsx';
    
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        
        // Set document properties
        $objPHPExcel->getProperties()
                    ->setCreator($this->user->getUserName())
                    ->setLastModifiedBy($this->user->getUserName())
                    ->setTitle($name)
                    ->setSubject($name)
                    ->setDescription("Backup for Office 2007 and later, generated using PHPExcel and ExcelPort.")
                    ->setKeywords("office 2007 2010 2013 xlsx openxml php phpexcel excelport")
                    ->setCategory("Backup");
        
        $objPHPExcel->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        
        $manufacturersMetaSheetObj = $objPHPExcel->setActiveSheetIndex($manufacturersMetaSheet);
        
        for ($i = 0; $i < count($stores); $i++) {
            $manufacturersMetaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($storesStart[0]) . ($storesStart[1] + $i), $stores[$i]['store_id'], PHPExcel_Cell_DataType::TYPE_STRING);
            $manufacturersMetaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($storesStart[0] + 1) . ($storesStart[1] + $i), $stores[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
        }
        
        $this->load->model('catalog/manufacturer');
        
        $extra_select = "";
        
        $this->db->query("SET SESSION group_concat_max_len = 1000000;");
        
        $manufacturers_result = $this->db->query($this->getQuery($export_filters, $store, $language) . " ORDER BY m.manufacturer_id LIMIT ". $progress['current'] . ", " . $manufacturerNumber);
        
        $manufacturersSheetObj = $objPHPExcel->setActiveSheetIndex($manufacturersSheet);
        
        foreach ($this->extraGeneralFields['Manufacturers'] as $extra) {
            if (!empty($extra['title']) && !empty($extra['column_light'])) {
                $manufacturersSheetObj->setCellValueExplicit($extra['column_light'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
            }
        }
        
        if ($manufacturers_result->num_rows > 0) {
            foreach ($manufacturers_result->rows as $myManufacturersIndex => $manufacturer_row) {
                $this->getData('Manufacturers', $manufacturer_row);
                
                // Prepare data
                $manufacturer_row['sort_order'] = empty($manufacturer_row['sort_order']) ? '0' : $manufacturer_row['sort_order'];
                if (empty($manufacturer_row['name'])) $manufacturer_row['name'] = '-';
                
                // Add data
                // Extras
                foreach ($extras as $name => $position) {
                    $manufacturersSheetObj->setCellValueExplicit($position . ($manufacturers_target[1]), empty($manufacturer_row[$name]) ? '' : $manufacturer_row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
                }
                // General
                foreach ($manufacturers_generals as $name => $position) {
                    $manufacturersSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($manufacturers_target[0] + $position) . ($manufacturers_target[1]), empty($manufacturer_row[$name]) && $manufacturer_row[$name] !== '0' ? '' : $manufacturer_row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
                }
                
                $manufacturers_target[1] = $manufacturers_target[1] + 1;
                $progress['current']++;
                $progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
                $progress['percent'] = 100 / ($manufacturers_result->num_rows / $progress['current']);
                
                $this->setProgress($progress);
            }
            
        } else {
            $progress['done'] = true;
        }
        
        $this->config->set('config_language_id', $default_language);
        
        $this->session->data['generated_file'] = $result;
        $this->session->data['generated_files'][] = $resultName;
        $this->setProgress($progress);

        try {
            $this->custom_set_time_limit();
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->setPreCalculateFormulas(false);
            
            $objWriter->save($result);
            
            $progress['done'] = true;
        } catch (Exception $e) {
            $progress['message'] = $e->getMessage();
            $progress['error'] = true;
            $progress['done'] = false;
            $this->setProgress($progress);
        }
        $objPHPExcel->disconnectWorksheets();
        unset($manufacturersMetaSheetObj);
        unset($objWriter);
        unset($manufacturersSheetObj);
        unset($objPHPExcel);
        
        $progress['done'] = true;
        $this->setProgress($progress);
        
        return true;
    }
    public function getQuery($filters = array(), $store = 0, $language = 1, $count = false) {
        if (empty($filters) || !in_array($filters['Conjunction'], array('AND', 'OR'))) $filters['Conjunction'] = 'OR';
        
        $join_rules = array(
            'manufacturer_to_store' => "LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id AND m2s.store_id = '" . $store . "')",
            'url_alias' => "LEFT JOIN " . DB_PREFIX . "url_alias ua ON (ua.query = CONCAT('manufacturer_id=', m.manufacturer_id))"
        );
        
        $joins = array();
        $joins['manufacturer_to_store'] = $join_rules['manufacturer_to_store'];
        
        $wheres = array();
        
        foreach ($filters as $i => $filter) {
            if (is_array($filter)) {
                if (!array_key_exists($this->conditions['Manufacturers'][$filter['Field']]['join_table'], $joins) && array_key_exists($this->conditions['Manufacturers'][$filter['Field']]['join_table'], $join_rules)) {
                    $joins[$this->conditions['Manufacturers'][$filter['Field']]['join_table']] = $join_rules[$this->conditions['Manufacturers'][$filter['Field']]['join_table']];
                }
                $condition = str_replace(array('{FIELD_NAME}', '{WORD}'), array($this->conditions['Manufacturers'][$filter['Field']]['field_name'], stripos($this->conditions['Manufacturers'][$filter['Field']]['type'], 'number') !== FALSE ? (int)$this->db->escape($filter['Value']) : $this->db->escape($filter['Value'])), $this->operations[$filter['Condition']]['operation']);
                if (!in_array($condition, $wheres)) $wheres[] = $condition;
            }
        }
        
        $select = $count ? "COUNT(*)" : "*";
        
        $query = ($count ? "SELECT COUNT(*) as count FROM (" : "") . "SELECT " . $select . " FROM " . DB_PREFIX . "manufacturer m " . implode(" ", $joins) . " WHERE m2s.store_id = '" . $store . "' " . (!empty($wheres) ? " AND (" . implode(" " . $filters['Conjunction'] . " ", $wheres) . ")" : "") . " GROUP BY m.manufacturer_id" . ($count ? ") as count_table" : "");
        
        return $query;
    }
    public function addManufacturer($manufacturer_id = '', $data) {
        $manufacturer_id = trim($manufacturer_id);

        $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET ".(!empty($manufacturer_id) ? "manufacturer_id = '" . (int)trim($manufacturer_id) . "', " : "")."name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "'");

        $manufacturer_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

        if (isset($data['manufacturer_store'])) {
            foreach ($data['manufacturer_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id. "'");

        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $seo_url_alias_table_query = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '" . DB_DATABASE . "' AND table_name = '" . DB_PREFIX . "seo_url_alias'");

        if ($seo_url_alias_table_query->num_rows) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id. "'");

            if ($data['keyword']) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "', language_id='" . (int)$this->config->get('config_language_id') . "'");
            }
        }

        // Extras
        foreach ($this->extraGeneralFields['Manufacturers'] as $extra) {
            if (!empty($extra['eval_add'])) {
                eval($extra['eval_add']);
            }
        }
        
        $this->cache->delete('manufacturer');
    }
    
    public function editManufacturer($manufacturer_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE manufacturer_id='" . (int)$manufacturer_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

        if (isset($data['manufacturer_store'])) {
            foreach ($data['manufacturer_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id. "'");

        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
        }

        $seo_url_alias_table_query = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '" . DB_DATABASE . "' AND table_name = '" . DB_PREFIX . "seo_url_alias'");

        if ($seo_url_alias_table_query->num_rows) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id. "'");

            if ($data['keyword']) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "', language_id='" . (int)$this->config->get('config_language_id') . "'");
            }
        }

        // Extras
        foreach ($this->extraGeneralFields['Manufacturers'] as $extra) {
            if (!empty($extra['eval_add'])) {
                eval($extra['eval_add']);
            }
        }
        
        $this->cache->delete('manufacturer');
    }
    
    public function deleteManufacturers() {
        $this->load->model('catalog/manufacturer');
        
        $ids = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer m");
        
        foreach ($ids->rows as $row) {
            $this->model_catalog_manufacturer->deleteManufacturer($row['manufacturer_id']); 
        }
    }
}
?>