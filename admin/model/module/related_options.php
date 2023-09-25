<?php
//  Related Options / Связанные опции 
//  Support: support@liveopencart.com / Поддержка: help@liveopencart.ru

class ModelModuleRelatedOptions extends Model {
	
	private $extension_code = "ropro";
	private $export_field_options 					= 'options_values_ids';
	private $export_field_description_of_options 	= 'description_of_options_info_only';
	private $export_field_discounts 				= 'discounts';
	private $export_field_specials 					= 'specials';
	
	public function getExtensionCode() {
		return $this->extension_code;
	}
	
	public function getLanguageId($lang) {
		$query = $this->db->query('SELECT `language_id` FROM `pqw5hl6v9_5752e8a7_language` WHERE `code` = "'.$lang.'"');
		return $query->row['language_id'];
	}
	
	public function getMaxProductIdWithRO() {
		$query = $this->db->query("SELECT MAX(product_id) product_id FROM " . DB_PREFIX . "relatedoptions ");
		if ( $query->num_rows ) {
			return $query->row['product_id'];
		} else {
			return 0;
		}
	}
	
	public function getMinProductIdWithRO() {
		$query = $this->db->query("SELECT MIN(product_id) product_id FROM " . DB_PREFIX . "relatedoptions ");
		if ( $query->num_rows ) {
			return $query->row['product_id'];
		} else {
			return 0;
		}
	}
	
	private function getQueryROVP($product_id) {
		$query_rovp = $this->db->query("SELECT ROVP.*
																			FROM 	`" . DB_PREFIX . "relatedoptions_variant_product` ROVP
																					LEFT JOIN	`" . DB_PREFIX . "relatedoptions_variant` ROV ON (ROV.relatedoptions_variant_id = ROVP.relatedoptions_variant_id)
																			WHERE ROVP.product_id = " . (int)$product_id . "
																			ORDER BY ROV.sort_order, ROV.relatedoptions_variant_name, ROVP.relatedoptions_variant_id, ROVP.relatedoptions_variant_product_id
																			");
		return $query_rovp;
	}
	
	private function getAllCombinationsOfModels($rovps, $ro_models, $rovp_level=0) {
		
		if ( count($rovps) > $rovp_level) {
			
			$next_models = $this->getAllCombinationsOfModels($rovps, $ro_models, $rovp_level+1);
			$rovp_id = $rovps[$rovp_level];
			$models = array();
			
			if ( isset($ro_models[$rovp_id]) ) {
				foreach ($ro_models[$rovp_id] as $model_info) {
					
					
					if ($next_models) {
						
						foreach ($next_models as $next_model) {
							$ro_ids = array_merge( array($model_info['relatedoptions_id']), $next_model['ro_ids']) ;
							$models[] = array('model'=>$model_info['model'].$next_model['model'], 'ro_ids'=>$ro_ids);
						}
						
					} else {
						if ( $model_info['model'] ) {
							$ro_ids = array($model_info['relatedoptions_id']);
							$models[] = array('model'=>$model_info['model'], 'ro_ids'=>$ro_ids);
						}
					}
					
					
				}
			}
			return $models;
			
		}
		
		return false;
	}
	
	public function generateRelatedOptionsSearch($product_id) {
		
		$ro_settings = $this->config->get('related_options');
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "relatedoptions_search` WHERE product_id = ".(int)$product_id." ");
		
		if ( isset($ro_settings['spec_model']) && ($ro_settings['spec_model'] == 2 || $ro_settings['spec_model'] == 3) ) {
			
			
			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct( (int)$product_id );
			
			$ro_models = array();
			
			$rovps = array();
			$query = $this->getQueryROVP($product_id);
			foreach ($query->rows as $row) {
				$rovps[] = $row['relatedoptions_variant_product_id'];
			}
			
			$query = $this->db->query("	SELECT `model`, `relatedoptions_variant_product_id`, `relatedoptions_id`
																	FROM `" . DB_PREFIX . "relatedoptions` 
																	WHERE product_id = ".(int)$product_id." ");
			foreach ( $query->rows as $row ) {
				if ( !isset($ro_models[$row['relatedoptions_variant_product_id']]) ) {
					$ro_models[$row['relatedoptions_variant_product_id']] = array();
				}
				$ro_models[$row['relatedoptions_variant_product_id']][] = $row;
			}
			
			$model_start = ($ro_settings['spec_model'] == 3) ? $product_info['model'] : '';
			
			$models = $this->getAllCombinationsOfModels($rovps, $ro_models);
			
			// << trying to increase many rows inserting speed
			
			$model_i = 0;
			$insertLimit = 100;
			while ( $model_i<count($models)) {
				
				$sqlValues = '';
				for ($sub_i=0;$sub_i<$insertLimit;$sub_i++) {
					
					if ( ($model_i+$sub_i) < count($models) ) {
						$model = $models[$model_i+$sub_i];
						
						$new_model = $model_start.$model['model'];
						
						$sqlValues.= ($sqlValues ? "," : "") . "('".(int)$product_id."','".$this->db->escape($new_model)."','".implode(',',$model['ro_ids'])."')";
					}
				}
				
				$this->db->query("INSERT INTO `" . DB_PREFIX . "relatedoptions_search`
														(`product_id`,`model`,`ro_ids`)
													VALUES
														".$sqlValues."
												");
				
				$model_i+= $insertLimit;
			}
			
			// >> trying to increase many rows inserting speed
			
			/*
			foreach ($models as $model) {
				
				$new_model = $model_start.$model['model'];
				
				$this->db->query("INSERT INTO `" . DB_PREFIX . "relatedoptions_search`
													SET `product_id` = ".(int)$product_id."
														,	`model` = '".$this->db->escape($new_model)."'
														, `ro_ids` = '".implode(',',$model['ro_ids'])."'
													");
				
			}
			*/
		}
	}
	
	public function getExportData() {

		$lang_id = $this->config->get('config_language_id');

		$data = array();

		$options_cnt = 0;
		$options = array();

		$query_ro = $this->db->query('SELECT RO.*, P.model product_model FROM `pqw5hl6v9_5752e8a7_relatedoptions` RO, `pqw5hl6v9_5752e8a7_product` P WHERE P.product_id = RO.product_id ');
		foreach ($query_ro->rows as $row) {
			$data[$row['relatedoptions_id']] = array(	'relatedoptions_id'=>$row['relatedoptions_id']
																							 ,'product_id'=>$row['product_id']
																							 ,'product_model'=>$row['product_model']
																							 ,'relatedoptions_model'=>$row['model']
																							 ,'relatedoptions_sku'=>$row['sku']
																							 ,'relatedoptions_upc'=>$row['upc']
																							 ,'relatedoptions_ean'=>$row['ean']
																							 ,'stock_status_id'=>$row['stock_status_id']
																							 ,'weight_prefix'=>$row['weight_prefix']
																							 ,'weight'=>$row['weight']
																							 ,'quantity' => $row['quantity']
																							 ,'price_prefix' => $row['price_prefix']
																							 ,'price' => $row['price']
																							 
																							 );
		}
		unset($query_ro);

		// on first step let's select only names for all values of all options
		$query = $this->db->query('SELECT DISTINCT ROO.option_id, ROO.option_value_id, OD.name option_name, OVD.name option_value_name
																FROM 	`'.DB_PREFIX.'relatedoptions_option` ROO
																		LEFT JOIN `'.DB_PREFIX.'option_value` OV ON (OV.option_value_id = ROO.option_value_id)
																		LEFT JOIN `'.DB_PREFIX.'option_value_description` OVD ON (OVD.option_value_id = ROO.option_value_id	AND OVD.language_id = '.$lang_id.')
																		, `'.DB_PREFIX.'option` O
																		LEFT JOIN `'.DB_PREFIX.'option_description` OD ON (O.option_id = OD.option_id AND OD.language_id = '.$lang_id.')
																WHERE ROO.option_id = O.option_id
																ORDER BY O.sort_order	
															');
		
		$opts_names = array();
		foreach ($query->rows as $row) {
			if ( !isset($opts_names[$row['option_id']]) ) {
				$opts_names[$row['option_id']] = array('name'=>$row['option_name'], 'values'=>array(0=>''));
			}
			$opts_names[$row['option_id']]['values'][$row['option_value_id']] = $row['option_value_name'];
		}
		unset($query);

		$query = $this->db->query('SELECT ROO.*
																FROM 	`'.DB_PREFIX.'relatedoptions_option` ROO, `'.DB_PREFIX.'option` O
																WHERE ROO.option_id = O.option_id
																ORDER BY O.sort_order	
															');
		
		foreach ($query->rows as &$row) {
			if (!isset($options[$row['option_id']])) {
				$options_cnt++;
				$options[$row['option_id']] = $options_cnt;
			}
			
			$data[$row['relatedoptions_id']]['option_id'.$options[$row['option_id']]] = $row['option_id'];
			$data[$row['relatedoptions_id']]['option_name'.$options[$row['option_id']]] = isset($opts_names[$row['option_id']]['name']) ? $opts_names[$row['option_id']]['name'] : '';
			$data[$row['relatedoptions_id']]['option_value_id'.$options[$row['option_id']]] = $row['option_value_id'];
			$data[$row['relatedoptions_id']]['option_value_name'.$options[$row['option_id']]] = $opts_names[$row['option_id']]['values'][$row['option_value_id']];

			$row = ""; // memory opt
		}
		
		unset($query);

		return $data;
	}
	
	public function get_char_id($relatedoptions_id) {
		
		$query = $this->db->query('SELECT * FROM `pqw5hl6v9_5752e8a7_relatedoptions_to_char` WHERE `relatedoptions_id` = "'.$relatedoptions_id.'"');
		if ($query->num_rows) {
			return $query->row['char_id'];
		}
		return FALSE;
	}
	
	// find relevant related options combination for options values array (product_option_id => product_option_value_id)
	public function get_related_options_sets_by_poids($product_id, $options) {
		
		$variants = $this->get_product_variants($product_id);
		
		$ro_combinations = array();
		
		foreach ($variants as $variant) {
			$ro_combination = $this->get_related_options_set_by_poids_and_rov_id($product_id, $variant['relatedoptions_variant_product_id'], $variant['relatedoptions_variant_id'], $options);
			if ($ro_combination) {
				$ro_combinations[] = $ro_combination;
			}
		}
		
		return $ro_combinations;
		
	}
	
	public function get_related_options_set_by_poids_and_rov_id($product_id, $rovp_id, $rov_id, $options) {
		
		if (!is_array($options) || count($options)==0 ) {
			return FALSE;
		}
		
		$str_opts = "";
		foreach ($options as $product_option_id => $option_value) {
			$str_opts .= ",".$product_option_id;
		}
		$str_opts = substr($str_opts, 1);
		
		
		// check only options used in relateted options
		$pvo = $this->get_options_for_variant($rov_id); //returns option_ids
		
		if (count($pvo)>0 && count($options)>0) {
			
					
			$query = $this->db->query("	SELECT PO.product_option_id, PO.option_id
																	FROM 	" . DB_PREFIX . "product_option PO
																	WHERE PO.product_id = ".(int)$product_id."
																		AND PO.product_option_id IN ( ".$str_opts.")
																		AND PO.option_id IN (".join(",",$pvo).")
																	");
			
			$sql_from = "";
			$sql_where = "";
			$sql_cnt = 0;
			
			$povs = array();
			foreach ( $query->rows as $row ) {
				$povs[] = (int)$options[$row['product_option_id']];
			}
			if ( $povs ) {
				$query = $this->db->query("	SELECT POV.option_value_id
																		FROM 	" . DB_PREFIX . "product_option_value POV
																		WHERE POV.product_id = ".(int)$product_id."
																			AND POV.product_option_id IN ( ".$str_opts.")
																			AND POV.product_option_value_id IN (".implode(",",$povs).")
																		");
				foreach ( $query->rows as $row ) {
					$sql_cnt++;
					$sql_from .= ", ( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions_option WHERE option_value_id = ".(int)$row['option_value_id']." ) ROO".$sql_cnt;
					$sql_where .= " AND ROO".$sql_cnt.".relatedoptions_id = RO.relatedoptions_id ";
				}
			}
			
			if ($sql_from!="") {
				
				$query = $this->db->query("	SELECT RO.*
																		FROM 	".DB_PREFIX."relatedoptions RO
																					".$sql_from."
																		WHERE RO.relatedoptions_variant_product_id = ".(int)$rovp_id."
																					".$sql_where."
																		");
				if ($query->num_rows) {
					return $query->row;
				}
				
			}
		}
		
		return FALSE;
		
	}
	
	
	public function get_product_variants($product_id) {
		
		if (!$this->installed()) return false;
		
		$query = $this->db->query("	SELECT VP.*
																FROM 	`".DB_PREFIX."relatedoptions_variant_product` VP
																WHERE	VP.product_id = ".(int)$product_id."
																");
		if ($query->num_rows) {
			return $query->rows;
		} else {
			return false;
		}
	}	
	
	
	// set product variant (one of variants)
	public function set_product_variant($product_id, $data, $ro_use) {
		
															
		if (!isset($data['rovp_id']) || !$data['rovp_id'] ) {
			$query = $this->db->query("	INSERT INTO `".DB_PREFIX."relatedoptions_variant_product`
																	SET product_id = ".(int)$product_id."
																		, relatedoptions_use = ".(int)$ro_use."
																		, relatedoptions_variant_id = ".(int)$data['rov_id']."
																		");
			
			return  $this->db->getLastId();
			
		} else {
			
			$query = $this->db->query("	UPDATE `".DB_PREFIX."relatedoptions_variant_product`
																	SET product_id = ".(int)$product_id."
																		, relatedoptions_use = ".(int)$ro_use."
																		, relatedoptions_variant_id = ".(int)$data['rov_id']."
																	WHERE relatedoptions_variant_product_id = ".(int)$data['rovp_id']."
																		");
			return $data['rovp_id'];
		}
		
		
		
	}
	

	// get options that can be used in related options
	public function get_compatible_options() {
		
		if (!$this->installed()) {
			return array();
		}
		
		$lang_id = $this->config->get('config_language_id');
		
		$query = $this->db->query("SELECT O.option_id, OD.name FROM `".DB_PREFIX."option` O, `".DB_PREFIX."option_description` OD
															WHERE O.option_id = OD.option_id
																AND OD.language_id = ".$lang_id."
																AND O.type IN (".$this->get_option_types().")
															ORDER BY O.sort_order
															");
		
		return $query->rows;
	}
	
	// get all values for options that can be used in related options
	public function get_compatible_options_values() {
		
		if (!$this->installed()) {
			return array();
		}
		
		$lang_id = $this->config->get('config_language_id');
		
		$optsv = array();
		$compatible_options = $this->get_compatible_options();
		$str_opt = "";
		foreach ($compatible_options as $option) {
			$optsv[$option['option_id']] = array('name'=>$option['name'], 'values'=>array() );
			$str_opt .= ",".$option['option_id'];
		}
		if ($str_opt!="") {
			$str_opt = substr($str_opt, 1);
			$query = $this->db->query("	SELECT OV.option_id, OVD.name, OVD.option_value_id
																	FROM `".DB_PREFIX."option_value` OV, `".DB_PREFIX."option_value_description` OVD 
																	WHERE OV.option_id IN (".$str_opt.")
																		AND OVD.language_id = ".$lang_id."
																		AND OV.option_value_id = OVD.option_value_id
																	ORDER BY OV.sort_order, OVD.name
																	");
			foreach ($query->rows as $row) {
				$optsv[$row['option_id']]['values'][] = $row;
			}
		}
		
		return $optsv;
		
	}
	
	public function get_options_for_variant($relatedoptions_variant_id) {
		
		$options = array();
		if ($relatedoptions_variant_id == 0) {
			$copts = $this->get_compatible_options();
			$options = array();
			foreach ($copts as $option) {
				$options[] = $option['option_id'];
			}
		} else {
			$options = array();
			$query = $this->db->query("	SELECT VO.option_id
																	FROM `".DB_PREFIX."relatedoptions_variant_option` VO, `".DB_PREFIX."option` O
																	WHERE relatedoptions_variant_id = ".(int)$relatedoptions_variant_id." AND VO.option_id = O.option_id
																	ORDER BY O.sort_order
																	");
			foreach ($query->rows as $row) {
				$options[] = $row['option_id'];
			}
		}
		
		return $options;
		
	}
	
	// returns array of all related options variants and relevant options
	// $add_all - add default variant "all avalable options"
	public function get_variants_options($add_all = false, $return_sorted = false) {
		
		$lang_id = $this->config->get('config_language_id');
		
		$mod_settings = $this->config->get('related_options');
		
		$vopts = array();
		
		if ($this->installed()) {
			
			if ($add_all && empty($mod_settings['disable_all_options_variant']) ) {
				$comp_opts_order = array();
				$comp_opts = $this->get_compatible_options($comp_opts_order);
				$vopts[0] = array('options'=>$comp_opts, 'sort_order'=>$comp_opts_order, 'complete_sort_order'=>0, 'rov_id'=>0);
			}
			
			$query = $this->db->query("	SELECT V.relatedoptions_variant_name, V.relatedoptions_variant_id, V.sort_order
																	FROM `".DB_PREFIX."relatedoptions_variant` V
																	ORDER BY V.sort_order, V.relatedoptions_variant_name
																	");
			$cnt = count($vopts);
			foreach ($query->rows as $row) {
				$vopts[$row['relatedoptions_variant_id']] = array(	'options'=>array()
																													, 'name'=> $row['relatedoptions_variant_name']
																													, 'rov_id'=> $row['relatedoptions_variant_id']
																													, 'sort_order'=> $row['sort_order']
																													, 'options_order'=>array()
																													, 'complete_sort_order'=>$cnt
																													);
				$cnt++;
			}
			
			$query = $this->db->query("	SELECT VO.relatedoptions_variant_id, VO.option_id, OD.name
																	FROM `".DB_PREFIX."relatedoptions_variant_option` VO
																			,`".DB_PREFIX."relatedoptions_variant` V
																			,`".DB_PREFIX."option_description` OD
																			,`".DB_PREFIX."option` O
																	WHERE OD.option_id = VO.option_id
																		AND O.option_id = VO.option_id
																		AND OD.language_id = ".$lang_id."
																		AND V.relatedoptions_variant_id = VO.relatedoptions_variant_id
																	ORDER BY O.sort_order	
																	");
			
			foreach ($query->rows as $row) {
				$vopts[$row['relatedoptions_variant_id']]['options'][] = array('option_id'=>$row['option_id'], 'name'=>$row['name']);
			}
			
		}
		
		if ( $return_sorted ) {
			$sorted = array();
			foreach ($vopts as $vopt) {
				$sorted[$vopt['complete_sort_order']] = $vopt;
			}
			return array('sorted'=>$sorted, 'vopts'=>$vopts);
		}
			
		return $vopts;
		
	}
	
	// save related options variant with variant options
	// $clear_others - delete others variants
	public function set_variants_options($vo, $clear_others=true) {
		
		if ($clear_others) {
			$query = $this->db->query("	DELETE FROM `".DB_PREFIX."relatedoptions_variant_option` ");
		}
		$str_vo_id = "";
		
		$updated_vo = array();
		
		if (is_array($vo)) {
			
			foreach ($vo as $vo_arr) {
				
				if (is_array($vo_arr)) {
					
					$vo_id = (isset($vo_arr['id'])) ? $vo_arr['id'] : ""; 
					$vo_name = "";
					if (isset($vo_arr['name']) && $vo_arr['name'] != '' ) {
						$vo_name = $vo_arr['name'];
					} else {
						if (isset($vo_arr['options']) && is_array($vo_arr['options'])) {
							$lang_id = $this->config->get('config_language_id');
							$options_in = implode(",",array_values($vo_arr['options']));
							if ( $options_in ) {
								$query = $this->db->query("	SELECT * FROM `".DB_PREFIX."option_description` WHERE language_id = ".$lang_id." AND option_id IN (".$options_in.") ");
								if ($query->num_rows) {
									foreach ($query->rows as $row) {
										$vo_name .= " + ".$row['name'];
									}
									$vo_name = substr($vo_name, 3);
								}
							}
						}
					}
					
					
					if (!empty($vo_id)) {
						$query = $this->db->query("	UPDATE `".DB_PREFIX."relatedoptions_variant`
																				SET relatedoptions_variant_name='".$this->db->escape($vo_name)."'
																					, sort_order= ".(int)(isset($vo_arr['sort_order']) ? $vo_arr['sort_order'] : 0 )."
																				WHERE relatedoptions_variant_id = ".$vo_id." ");
					} else {
						$query = $this->db->query("	INSERT INTO `".DB_PREFIX."relatedoptions_variant`
																				SET relatedoptions_variant_name='".$this->db->escape($vo_name)."'
																					, sort_order= ".(int)(isset($vo_arr['sort_order']) ? $vo_arr['sort_order'] : 0 )."
																				");
						$vo_id = $this->db->getLastId();
					}
					$str_vo_id .= ",".$vo_id;
					$updated_vo[] = $vo_id; 
					
					if (isset($vo_arr['options'])) {
						$vo_opts = $vo_arr['options'];
						if (is_array($vo_opts)) {
							foreach ($vo_opts as $opts_key => $option_id) {
								$query = $this->db->query("	INSERT INTO `".DB_PREFIX."relatedoptions_variant_option` SET relatedoptions_variant_id=".$vo_id.", option_id = ".$option_id." ");
							}
						}
					}	
				}
			}
		}
		
		if ($clear_others) {
			$query = $this->db->query("	DELETE FROM `".DB_PREFIX."relatedoptions_variant` WHERE NOT relatedoptions_variant_id IN (0".$str_vo_id.") ");
			$query = $this->db->query("	DELETE FROM `".DB_PREFIX."relatedoptions_variant_product` WHERE NOT relatedoptions_variant_id IN (0".$str_vo_id.") ");
		}
		
		return $updated_vo;
		
	}
	
	public function get_ro_data($product_id, $with_char_id=false) {
		
		if (!$this->installed()) {
			return array();
		}
		
		$ro_data = array();
		
		$query = $this->getQueryROVP($product_id);
		
		$rovp_rows = $query->rows;
		
		foreach ($rovp_rows as $rovp_row) {
			
			$ro_data[] = array(	'rovp_id' => $rovp_row['relatedoptions_variant_product_id']
												,	'use' 		=> $rovp_row['relatedoptions_use']
												,	'rov_id' 	=> $rovp_row['relatedoptions_variant_id']
												, 'ro'			=> array()
												, 'options_ids'	=> array()
												);
			$cnt = count($ro_data)-1;
			$rovp_id = (int)$rovp_row['relatedoptions_variant_product_id'];
			
			
			$query = $this->db->query("	SELECT ROVO.*
																	FROM 	`" . DB_PREFIX . "relatedoptions_variant_option` ROVO
																	WHERE ROVO.relatedoptions_variant_id = " . (int)$rovp_row['relatedoptions_variant_id'] . "
																	ORDER BY ROVO.option_id
																	");
			foreach ($query->rows as $row) {
				$ro_data[$cnt]['options_ids'][] = $row['option_id'];
			}
			
			
			$query = $this->db->query("	SELECT RO.*
																	FROM 	`" . DB_PREFIX . "relatedoptions` RO
																	WHERE RO.relatedoptions_variant_product_id = " . (int)$rovp_id . "
																	ORDER BY RO.relatedoptions_id
																	");
			foreach ($query->rows as $row) {
				
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']] = $row;
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['options'] = array();
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['discounts'] = array();
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['specials'] = array();
				
			}
			
			$query = $this->db->query("	SELECT ROO.*
																	FROM 	`" . DB_PREFIX . "relatedoptions_option` ROO
																			,	`" . DB_PREFIX . "relatedoptions` RO
																			, `" . DB_PREFIX . "option` O
																			, `" . DB_PREFIX . "option_value` OV
																	WHERE ROO.product_id = " . (int)$product_id . "
																		AND RO.relatedoptions_id = ROO.relatedoptions_id
																		AND RO.relatedoptions_variant_product_id = ".(int)$rovp_id."
																		AND O.option_id = ROO.option_id
																		AND OV.option_value_id = ROO.option_value_id
																	ORDER BY ROO.relatedoptions_id, O.sort_order, OV.sort_order 
																	");
			
			foreach ($query->rows as $row) {
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['options'][$row['option_id']] = $row['option_value_id'];
			}
			
			
			$query = $this->db->query("	SELECT RD.*
																	FROM 	`" . DB_PREFIX . "relatedoptions` RO
																			, `" . DB_PREFIX . "relatedoptions_discount` RD
																	WHERE RO.product_id = " . (int)$product_id . "
																		AND RO.relatedoptions_id = RD.relatedoptions_id
																		AND RO.relatedoptions_variant_product_id = ".(int)$rovp_id."
																	ORDER BY RD.relatedoptions_id, RD.customer_group_id, RD.quantity 
																	");
			
			foreach ($query->rows as $row) {
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['discounts'][] = $row;
			}
			
			
			$query = $this->db->query("	SELECT RS.*
																	FROM 	`" . DB_PREFIX . "relatedoptions` RO
																			, `" . DB_PREFIX . "relatedoptions_special` RS
																	WHERE RO.product_id = " . (int)$product_id . "
																		AND RO.relatedoptions_id = RS.relatedoptions_id
																		AND RO.relatedoptions_variant_product_id = ".(int)$rovp_id."
																	ORDER BY RS.relatedoptions_id, RS.customer_group_id
																	");
			
			foreach ($query->rows as $row) {
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['specials'][] = $row;
			}
			
		}
		
		return $ro_data;
	}
	
	public function get_option_types() {
		return "'select', 'radio', 'image', 'block', 'color'";
	}
	
	private function search_ro_product_variant($product_id, $rov_id) {
		
		$query = $this->db->query("	SELECT relatedoptions_variant_product_id FROM `".DB_PREFIX."relatedoptions_variant_product`
																WHERE product_id = ".(int)$product_id."
																	AND relatedoptions_variant_id = ".$rov_id."
																");
		if ( $query->num_rows ) {
			return $query->row['relatedoptions_variant_product_id'];
		}
		return 0;
		
	}
	
	// find relevant related optiosn variant for product related options set, net found- create new
	private function search_ro_variant($data) {
		
		if (isset($data['ro']) && (is_array($data['ro']))) {
			
			$all_options = array();
			foreach ($data['ro'] as $relatedoptions) {
			
				if (isset($relatedoptions['options']) && is_array($relatedoptions['options'])) {
					$options = array_keys($relatedoptions['options']);
					foreach ($options as $option_id) {
						if (!in_array($option_id, $all_options)) {
							$all_options[] = $option_id;
						}
					}
				}
			
			}
			
			
			if (count($all_options)>0) {
				
				sort($all_options);
				
				$variants = $this->get_variants_options();
				
				foreach ($variants as $variant_id => $variant) {
					
					$vo_options = array();
					foreach ($variant['options'] as $option) {
						$vo_options[] = $option['option_id'];
					}
					//$vo_options = array_keys($variant['options']);
					sort($vo_options);
					if ($vo_options == $all_options) {
						return $variant_id;
					}
					
				}
			}
			
			// not found - create new
			$vo = array();
			$vo[] = array('options' => $all_options);
			$vo_added = $this->set_variants_options($vo, FALSE);
			if (is_array($vo_added) && count($vo_added) != 0) {
				return reset($vo_added);
			}
			
		}
		
		return 0;
	}
	
	private function getProductOptionsUsedInRO($product_id) {
		$result = array();
		$query = $this->db->query("
			SELECT DISTINCT PO.product_option_id
			FROM ".DB_PREFIX."relatedoptions_option ROO
					,".DB_PREFIX."product_option PO
			WHERE ROO.product_id = ".(int)$product_id."
				AND ROO.option_id = PO.option_id
				AND PO.product_id = ".(int)$product_id."
		");
		foreach ( $query->rows as $row ) {
			$result[] = $row['product_option_id'];
		}
		return $result;
	}
	
	public function set_ro_data($product_id, $data) {
		
		if ( (isset($data['ro_data_included']) && $data['ro_data_included']) || (isset($data['ro_data']) && $data['ro_data']) ) {
			
			$mod_settings = $this->config->get('related_options');
			$ro_quantity = false;
			$options = array();
			
			$ro_use = false;
			
			
			if ( $mod_settings && !empty($mod_settings['update_options']) && !empty($mod_settings['update_options_remove']) ) {
				$product_options_used_in_ro_before = $this->getProductOptionsUsedInRO($product_id);
			}
			
			
			if ( !isset($data['ro_data']) || !$data['ro_data'] ) {
				// remove all product related options
				$this->editRelatedOptions($product_id, 0);
				return;
				
			} else {
				
				$used_rovp_ids = array();
				
				foreach ($data['ro_data'] as $ro_dt) {
					
					$edited_data = $this->editRelatedOptions($product_id, $ro_dt, $options, $ro_quantity);
					if ( $edited_data ) {
						$options 			= $edited_data['product_options'];
						$ro_quantity 	= $edited_data['quantity_total'];
						$used_rovp_ids[] = $edited_data['rovp_id'];
					}
					
					$ro_use = $ro_use || (isset($ro_dt['use']) && $ro_dt['use']);
					
				}
				
				// remove unused ro_variants from the product
				$query = $this->db->query("SELECT * FROM `".DB_PREFIX."relatedoptions_variant_product` WHERE product_id = ".(int)$product_id." ");
				foreach ( $query->rows as $row ) {
					if ( !in_array($row['relatedoptions_variant_product_id'], $used_rovp_ids) ) {
						$this->editRelatedOptions($product_id, array('rovp_id'=>$row['relatedoptions_variant_product_id']));
					}
				}
				
				// additionally remove ROO (normally they should be removed automatically, but sometimes can stay not touched because of a bug or manunal work)
				$query = $this->db->query("
					DELETE FROM `".DB_PREFIX."relatedoptions_option`
					WHERE product_id = ".(int)$product_id."
						AND NOT relatedoptions_id IN ( SELECT relatedoptions_id FROM `".DB_PREFIX."relatedoptions` WHERE product_id = ".(int)$product_id." )
				");
				
			}
			
			
			
			// update options and  quantity only if related options enabled
			if ($ro_use) {
				
				// update product quantity
				if ( $mod_settings && isset($mod_settings['update_quantity']) && $mod_settings['update_quantity'] ) {
					$this->db->query("UPDATE ".DB_PREFIX."product SET quantity = ".(int)$ro_quantity." WHERE product_id = ".(int)$product_id." ");
				}
				
				// update options
				if ( $mod_settings && isset($mod_settings['update_options']) && $mod_settings['update_options'] ) {
					
					if ( !empty($mod_settings['update_options_remove']) ) {
						$product_options_used_in_ro_now = $this->getProductOptionsUsedInRO($product_id);
						foreach ( $product_options_used_in_ro_before as $product_option_id ) {
							if ( !in_array($product_option_id, $product_options_used_in_ro_now) ) {
								$this->db->query("DELETE FROM ".DB_PREFIX."product_option_value WHERE product_option_id = ".(int)$product_option_id );
								$this->db->query("DELETE FROM ".DB_PREFIX."product_option WHERE product_option_id = ".(int)$product_option_id );
							}
						}
					}
					
					$product_subtract = 0;
					$query = $this->db->query("SELECT subtract FROM " . DB_PREFIX . "product WHERE product_id = ".(int)$product_id);
					if ($query->num_rows) {
						$product_subtract = (int)$query->row['subtract'];
					}
					
					$subtract_stock = 0;
					$subtract_stock_only_first_time = false;
					if ( !isset($mod_settings['subtract_stock']) || $mod_settings['subtract_stock'] == 0 ) { // from product
						$subtract_stock = $product_subtract; 
					} elseif ( $mod_settings['subtract_stock'] == 1 ) { // from product only first time
						$subtract_stock = $product_subtract;
						$subtract_stock_only_first_time = true;
					} elseif ( $mod_settings['subtract_stock'] == 2 ) { // yes
						$subtract_stock = 1;
					} elseif ( $mod_settings['subtract_stock'] == 3 ) { // no
						$subtract_stock = 0;	
					}
					
					$required_setting = 1;
					$required_only_first_time = false;
					if ( isset($mod_settings['required']) ) {
						if ($mod_settings['required'] == 0) { //yes
							$required_setting = 1; 
						} elseif ($mod_settings['required'] == 1) { // no
							$required_setting = 0; 
						} elseif ($mod_settings['required'] == 2) { // yes only first time
							$required_setting = 1;
							$required_only_first_time = true;
						}
					}
					
					
					$product_options_saved = array();
					$product_options_values_saved = array();
					
					if (count($options)) {
						
						// update by options
						foreach ($options as $option_id => $option_values) {
							
							if ( !array_filter(array_keys($option_values)) ) { // skip options having no selected values
								continue;
							}
							
							if ( isset($product_options_saved[$option_id]))  {
								$product_option_id = $product_options_saved[$option_id];	
	
							} else {
								
								$query = $this->db->query("SELECT product_option_id, required FROM " . DB_PREFIX . "product_option
																					WHERE product_id = " . (int)$product_id . " AND option_id = ".$option_id."
																					");
								if ($query->num_rows) {
									$product_option_id = $query->row['product_option_id'];
									if ($query->row['required'] != $required_setting && !$required_only_first_time ) {
										$this->db->query("UPDATE " . DB_PREFIX . "product_option SET required = ".(int)$required_setting." WHERE product_option_id = " . $product_option_id . " ");
									}
									
								} else {
									$query = $this->db->query("INSERT INTO " . DB_PREFIX . "product_option
																					SET product_id = " . (int)$product_id . ", option_id = ".$option_id.", required = 1
																					");
									$product_option_id = $this->db->getLastId();
								}
								$product_options_saved[$option_id] = $product_option_id;
							
							}
							
							if (!isset($product_options_values_saved[$product_option_id])) {
								$product_options_values_saved[$product_option_id] = array();
							}
							
							foreach ($option_values as $option_value_id => $option_value_quantity) {
								
								if ( $option_value_id ) {
								
									$query = $this->db->query("SELECT product_option_value_id, subtract FROM " . DB_PREFIX . "product_option_value
																							WHERE product_option_id = " . $product_option_id . "
																								AND option_value_id = ".$option_value_id."
																							");
									if ($query->num_rows) {
										
										$product_option_value_id = $query->row['product_option_value_id'];
										
										$this->db->query("UPDATE " . DB_PREFIX . "product_option_value
																			SET quantity = ".$option_value_quantity."
																			WHERE product_option_value_id = ".$product_option_value_id."	
																			");
										
										if ($query->row['subtract'] != $subtract_stock && !$subtract_stock_only_first_time) {
											$this->db->query("UPDATE " . DB_PREFIX . "product_option_value
																				SET subtract = ".(int)$subtract_stock."
																				WHERE product_option_value_id = ".(int)$product_option_value_id."	
																				");
										}
										
									} else {
										
										$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value
																			SET product_id = " . (int)$product_id . ", option_id = ".$option_id."
																				, option_value_id = ".$option_value_id.", quantity = ".$option_value_quantity."
																				, product_option_id = ".$product_option_id.", subtract = ".$subtract_stock."
																			");
										$product_option_value_id = $this->db->getLastId();
										
									}
									
									$product_options_values_saved[$product_option_id][] = $product_option_value_id;
								}
							}
						}
						
						$sql_add = join(",", $product_options_saved);
						if ($sql_add != "") {
							$sql_add = "AND NOT product_option_id IN (".$sql_add.")";
						}
						
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_option
															WHERE product_id = " . (int)$product_id . "
																AND option_id IN (".join(",",array_keys($options)).")
																".$sql_add."
																");
						
						$sql_add = "";
						foreach ($product_options_values_saved as $product_option_id => $values) {
							if (count($values)!=0) {
								$sql_add .= ",".join(",",$values);
							}
						}
						if ($sql_add != "") {
							$sql_add = "AND NOT product_option_value_id IN (".substr($sql_add,1).")";
						}
						
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value
															WHERE product_id = " . (int)$product_id . "
																AND option_id IN (".join(",",array_keys($options)).")
																".$sql_add."
																");
					}
				}
			}
			
		}
		
	}
	
	

	public function editRelatedOptions($product_id, $data, $param_product_options=false, $quantity_total=false) {
		
		if (!$this->installed() || (int)$product_id == 0) {
			return;
		}
		
		if ($data === 0) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_discount WHERE relatedoptions_id IN ( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE product_id = " . (int)$product_id . ")");
			$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_special WHERE relatedoptions_id IN ( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE product_id = " . (int)$product_id . ")");
			$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions WHERE product_id = " . (int)$product_id . "");
			$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_option WHERE product_id = " . (int)$product_id . "");
			$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_variant_product WHERE product_id = " . (int)$product_id . "");
			$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_search WHERE product_id = " . (int)$product_id . "");
			
			return;
		}
		
		
		$ro_use 			= (isset($data['use']) && $data['use']);
		
		
		// for importing
		if ($ro_use && isset($data['ro']) && is_array($data['ro']) && count($data['ro'])>0 && isset($data['related_options_variant_search']) && $data['related_options_variant_search'] ) {
			$data['rov_id'] = $this->search_ro_variant($data);
			$data['rovp_id'] = $this->search_ro_product_variant($product_id, $data['rov_id']);
		}
		
		if ($ro_use) {
			$rovp_id = $this->set_product_variant($product_id, $data, $ro_use);
			
			// discounts
			if (isset($data['related_options_discount']) && $data['related_options_discount'] ) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_discount WHERE relatedoptions_id IN
													( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE relatedoptions_variant_product_id = " . (int)$rovp_id . ")");
			}
			
			// specials
			if (isset($data['related_options_special']) && $data['related_options_special'] ) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_special WHERE relatedoptions_id IN 
													( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE relatedoptions_variant_product_id = " . (int)$rovp_id . ")");
			}
			
		} else {
			
			if (isset($data['rovp_id']) && $data['rovp_id']) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_discount
													WHERE relatedoptions_id IN
														( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE relatedoptions_variant_product_id = ".(int)$data['rovp_id'].")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_special
													WHERE relatedoptions_id IN
														( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE relatedoptions_variant_product_id = ".(int)$data['rovp_id'].")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_option
													WHERE relatedoptions_id IN
														( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE relatedoptions_variant_product_id = ".(int)$data['rovp_id'].")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions
													WHERE relatedoptions_variant_product_id = ".(int)$data['rovp_id']." ");
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_variant_product
													WHERE relatedoptions_variant_product_id = ".(int)$data['rovp_id']." ");
			}	
			return; 
		}
			
			
		if ($ro_use && isset($data['rov_id']))	{
			
			$mod_settings = $this->config->get('related_options');
			
			// get existing related options
			$query = $this->db->query("SELECT relatedoptions_id
																	FROM " . DB_PREFIX . "relatedoptions
																	WHERE product_id = " . (int)$product_id . "
																		AND relatedoptions_variant_product_id = ".(int)$rovp_id."
																	");
			$rop_array = array();
			foreach ($query->rows as $row) {
				$rop_array[] = $row['relatedoptions_id'];
			}
			
			$ropupd_array = array();
			
			// to calculate options quantity
			$product_options = array();
			
			$options = $this->get_options_for_variant($data['rov_id']);
			
			$ro_quantity = 0;
			
			if ( isset($data['ro']) && (is_array($data['ro']))  ) {
			
				if (count($options) != 0) {
					
					// remove links from related options to options
					$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_option WHERE relatedoptions_id IN
															(SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE relatedoptions_variant_product_id = " . (int)$rovp_id . " ) ");
					
					foreach ($data['ro'] as $relatedoption) {
						
						if (!isset($relatedoption['model'])) $relatedoption['model'] = "";
						if (!isset($relatedoption['sku'])) $relatedoption['sku'] = "";
						if (!isset($relatedoption['upc'])) $relatedoption['upc'] = "";
						if (!isset($relatedoption['ean'])) $relatedoption['ean'] = "";
						if (!isset($relatedoption['location'])) $relatedoption['location'] = "";
						if (!isset($relatedoption['weight_prefix'])) $relatedoption['weight_prefix'] = "";
						if (!isset($relatedoption['stock_status_id'])) $relatedoption['stock_status_id'] = 0;
						if (!isset($relatedoption['weight'])) $relatedoption['weight'] = 0;
						if (!isset($relatedoption['price'])) $relatedoption['price'] = 0;
						if (!isset($relatedoption['price_prefix'])) $relatedoption['price_prefix'] = '=';
						if (!isset($relatedoption['defaultselect'])) $relatedoption['defaultselect'] = 0;
						if (!isset($relatedoption['defaultselectpriority'])) $relatedoption['defaultselectpriority'] = 0;
						
						$relatedoptions_id = '';
						// if this related options combnation exists, let it be, alse add new
						if ( isset($relatedoption['relatedoptions_id']) && !empty($relatedoption['relatedoptions_id']) ) {
							$query = $this->db->query("SELECT relatedoptions_id FROM " . DB_PREFIX . "relatedoptions
																				WHERE relatedoptions_variant_product_id = " . (int)$rovp_id . "
																					AND relatedoptions_id = " . (int)$relatedoption['relatedoptions_id'] . "
																				");
							
							if ($query->num_rows) {
								$relatedoptions_id = (int)$relatedoption['relatedoptions_id'];
								$ropupd_array[] = $relatedoptions_id;
							}
						}
						
						if ($relatedoptions_id == '') {
							$this->db->query("INSERT INTO " . DB_PREFIX . "relatedoptions
																SET product_id = " . (int)$product_id . "
																		,relatedoptions_variant_product_id = ".(int)$rovp_id."
																		,quantity = ".(int)$relatedoption['quantity']."
																		,model = '".$this->db->escape((string)$relatedoption['model'])."'
																		,sku = '".$this->db->escape((string)$relatedoption['sku'])."'
																		,upc = '".$this->db->escape((string)$relatedoption['upc'])."'
																		,ean = '".$this->db->escape((string)$relatedoption['ean'])."'
																		,location = '".$this->db->escape((string)$relatedoption['location'])."'
																		,stock_status_id = ".(int)$relatedoption['stock_status_id']."
																		,weight_prefix = '".$this->db->escape((string)$relatedoption['weight_prefix'])."'
																		,weight = ".(float)$relatedoption['weight']."
																		,price = ".(float)$relatedoption['price']."
																		,price_prefix = '".(string)$relatedoption['price_prefix']."'
																		,defaultselect = ".(int)$relatedoption['defaultselect']."
																		,defaultselectpriority = ".(float)$relatedoption['defaultselectpriority']."
																		");
							$relatedoptions_id = $this->db->getLastId();
						} else {
							$this->db->query("UPDATE ".DB_PREFIX."relatedoptions
																	SET	product_id = " . (int)$product_id . "
																			,relatedoptions_variant_product_id = ".(int)$rovp_id."
																			,quantity = ".(int)$relatedoption['quantity']."
																			,model = '".$this->db->escape((string)$relatedoption['model'])."'
																			,sku = '".$this->db->escape((string)$relatedoption['sku'])."'
																			,upc = '".$this->db->escape((string)$relatedoption['upc'])."'
																			,ean = '".$this->db->escape((string)$relatedoption['ean'])."'
																			,location = '".$this->db->escape((string)$relatedoption['location'])."'
																			,stock_status_id = ".(int)$relatedoption['stock_status_id']."
																			,weight_prefix = '".$this->db->escape((string)$relatedoption['weight_prefix'])."'
																			,weight = ".(float)$relatedoption['weight']."
																			,price = ".(float)$relatedoption['price']."
																			,price_prefix = '".(string)$relatedoption['price_prefix']."'
																			,defaultselect = ".(int)$relatedoption['defaultselect']."
																			,defaultselectpriority = ".(float)$relatedoption['defaultselectpriority']."
																WHERE relatedoptions_id = ".$relatedoptions_id." ");
						}
						
						if (isset($relatedoption['char_id'])) { // для 1с
							$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_to_char WHERE relatedoptions_id = " . $relatedoptions_id . " ");
							if ($relatedoption['char_id']) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "relatedoptions_to_char SET relatedoptions_id = " . $relatedoptions_id . ", char_id = '".$this->db->escape($relatedoption['char_id'])."' ");
							}
						}
						
						if ( isset($relatedoption['options']) && is_array($relatedoption['options']) ) {
							foreach ($relatedoption['options'] as $option_id => $option_value_id) {
								
								if (in_array($option_id, $options)) {
									
									$this->db->query("INSERT INTO " . DB_PREFIX . "relatedoptions_option
																	 SET product_id = " . (int)$product_id . "
																	 , relatedoptions_id = " . (int)$relatedoptions_id . "
																	 , option_id = " . (int)$option_id . "
																	 , option_value_id = " . (int)$option_value_id . "
																	 ");
									
									// total for product options quantity
									if ( !isset($product_options[$option_id])) {
										$product_options[$option_id] = array();
									}
									if ( !isset($product_options[$option_id][$option_value_id])) {
										$product_options[$option_id][$option_value_id] = 0;
									}
									$product_options[$option_id][$option_value_id] += (int)$relatedoption['quantity'];
								}
							}
						}
						
						$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_discount WHERE relatedoptions_id = " . (int)$relatedoptions_id . " ");
						if (isset($relatedoption['discounts']) && is_array($relatedoption['discounts'])) {
							foreach ($relatedoption['discounts'] as $ro_discount) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "relatedoptions_discount
																		SET relatedoptions_id = " . (int)$relatedoptions_id . "
																			, customer_group_id = " . (int)$ro_discount['customer_group_id'] . "
																			, quantity 					= " . (int)$ro_discount['quantity'] . "
																			, priority 					= " . (int) ( isset($ro_discount['priority']) ? $ro_discount['priority'] : 0 ) . "
																			, price 						= " . (float)$ro_discount['price'] . "
																			");
							}
						}
						
						$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_special WHERE relatedoptions_id = " . (int)$relatedoptions_id . " ");
						if (isset($relatedoption['specials']) && is_array($relatedoption['specials'])) {
							foreach ($relatedoption['specials'] as $ro_special) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "relatedoptions_special
																		SET relatedoptions_id = " . (int)$relatedoptions_id . "
																			, customer_group_id = " . (int)$ro_special['customer_group_id'] . "
																			, priority 					= " . (int) ( isset($ro_special['priority']) ? $ro_special['priority'] : 0 ) . "
																			, price 						= " . (float)$ro_special['price'] . "
																			");
							}
						}
						
						$ro_quantity+= $relatedoption['quantity'];
						
					}
					
				}
			}
			
			$str_del = '';
			foreach ($rop_array as $relatedoptions_id) {
				if ( !in_array($relatedoptions_id, $ropupd_array )) {
					$str_del .= (($str_del=='')?(''):(',')).$relatedoptions_id;
				}
			}
			
			if ($str_del != '') {
				
				// для 1с
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_to_char
													WHERE relatedoptions_id IN (".$str_del.")
														AND relatedoptions_id IN
															(SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE relatedoptions_variant_product_id = " . (int)$rovp_id . " )
												 ");
				
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions
													WHERE relatedoptions_variant_product_id = " . (int)$rovp_id . "
														AND relatedoptions_id IN (".$str_del.")
												 ");
				
				
			}
			
			$quantity_total = $quantity_total===false ? (int)$ro_quantity : MIN($quantity_total, (int)$ro_quantity);
			
			// save options quantities in common data
			if ($product_options) {
				foreach($product_options as $option_id => $option_values) {
					
					if (!isset($param_product_options[$option_id])) {
						$param_product_options[$option_id] = array();
					}
					
					foreach ($option_values as $option_value_id => $option_quantity) {
						if ( !isset($param_product_options[$option_id][$option_value_id]) ) {
							$param_product_options[$option_id][$option_value_id] = $option_quantity;
						} else {
							$param_product_options[$option_id][$option_value_id] = MIN($param_product_options[$option_id][$option_value_id], $option_quantity);
						}
					}
					
				}
			}
			
			
			
		}
		
		$this->generateRelatedOptionsSearch($product_id);
		
		return array('product_options'=>$param_product_options, 'quantity_total'=>$quantity_total, 'rovp_id'=>$rovp_id);
		
	}
	
	public function delete_all_related_options($product_id=false) {
		
		if ($this->installed()) {
			
			if ($product_id === false) {
			
				$this->db->query("TRUNCATE TABLE ".DB_PREFIX."relatedoptions ");
				$this->db->query("TRUNCATE TABLE ".DB_PREFIX."relatedoptions_option ");
				$this->db->query("TRUNCATE TABLE ".DB_PREFIX."relatedoptions_to_char ");
				$this->db->query("TRUNCATE TABLE ".DB_PREFIX."relatedoptions_discount ");
				$this->db->query("TRUNCATE TABLE ".DB_PREFIX."relatedoptions_special ");
				$this->db->query("TRUNCATE TABLE ".DB_PREFIX."relatedoptions_variant_product ");
				$this->db->query("TRUNCATE TABLE ".DB_PREFIX."relatedoptions_search ");
				
			} else {
				
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_discount WHERE relatedoptions_id IN ( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE product_id = " . (int)$product_id . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_special WHERE relatedoptions_id IN ( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE product_id = " . (int)$product_id . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_to_char WHERE relatedoptions_id IN ( SELECT relatedoptions_id FROM ".DB_PREFIX."relatedoptions WHERE product_id = " . (int)$product_id . ")");
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions WHERE product_id = " . (int)$product_id . "");
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_option WHERE product_id = " . (int)$product_id . "");
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_variant_product WHERE product_id = " . (int)$product_id . "");
				$this->db->query("DELETE FROM " . DB_PREFIX . "relatedoptions_search WHERE product_id = " . (int)$product_id . "");
				
			}
			
		}
		
	}
	
	
	public function getExportNewFields() {
		$fields = array( 	'product_id'
										, 'product_model'
										, $this->export_field_options
										, 'description_of_options_info_only'
										, 'quantity'
										, 'model'
										, 'sku'
										, 'upc'
										, 'ean'
										, 'location'
										, 'price_prefix'
										, 'price'
										, 'defaultselect'
										, 'defaultselectpriority'
										, 'weight_prefix'
										, 'weight'
										, 'stock_status_id'
										, $this->export_field_discounts
										, $this->export_field_specials
									);
		return $fields;
	}
	
	public function PHPExcelPath() {
		return DIR_SYSTEM . '/PHPExcel/Classes/PHPExcel.php';
	}
	public function PHPExcelExists() {
		return file_exists($this->PHPExcelPath());
	}
	
	// separate entry for standard fields: array('column'=>$columns_cnt)
	// one entry for all options fields: array( option_id => array('column=>'$columns_cnt, ... ) )
	private function makeExportGetListOfColumns($rov, $fields) {
		$columns = array();
		// show columns before options_values_ids
		$columns_cnt = 0;
		foreach ( $fields as $field ) { // header
			if ( $field != $this->export_field_discounts && $field != $this->export_field_specials ) {
				$columns[$field] = array('column'=>$columns_cnt);
				$columns_cnt++;
			}
		}
		return $columns;
	}
	
	private function makeExportPutHeader($sheet, $columns) {
		foreach ($columns as $field => $column) {
			$sheet->setCellValueByColumnAndRow($column['column'], 1, $field); // rows numeration starts from 1
		}
	}
	
	private function makeExportPutToSheet($rov, $sheet, $query, $fields, $ro_options) {
		
		$columns = $this->makeExportGetListOfColumns($rov, $fields);
		if ( $sheet->getHighestRow() == 1 ) {
			$this->makeExportPutHeader($sheet, $columns);
		}
		
		$row_num = $sheet->getHighestRow();
		foreach ( $query->rows as $row ) {
			$row_num++;
			
			foreach ( $fields as $field ) {
			
				$relatedoptions_id = $row['relatedoptions_id'];
				if ( isset($ro_options[$relatedoptions_id]) ) {
					$ro_option_ids = $ro_options[$relatedoptions_id]['option_ids'];
				} else {
					$ro_option_ids = array();
				}
			
				if ( $field == $this->export_field_options ) {
					// put options
					$options_values_ids = '';
					foreach ( $rov['data']['options'] as $rov_option ) {
						
						
						if ( isset($ro_option_ids[$rov_option['option_id']]) ) {
							// to place in the right order
							$options_values_ids.= ($options_values_ids=='' ? '' : ',').$rov_option['option_id'].":".$ro_option_ids[$rov_option['option_id']]; 
						}
					}
					$col_num = $columns[$field]['column'];
					$sheet->setCellValueByColumnAndRow($col_num, $row_num, $options_values_ids);
					
				} elseif ( $field == $this->export_field_description_of_options ) {
					
					$description_of_options = '';
					if ( !empty($ro_options[$relatedoptions_id]['description']) ) {
						$description_of_options = $ro_options[$relatedoptions_id]['description'];
					} else {
						$description_of_options = '';
					}
					$col_num = $columns[$field]['column'];
					$sheet->setCellValueByColumnAndRow($col_num, $row_num, $description_of_options);
					
				} elseif ( $field != $this->export_field_discounts && $field != $this->export_field_specials ) {
					// put other fields
					if (!empty($row[$field])) {
						$col_num = $columns[$field]['column'];
						$sheet->setCellValueByColumnAndRow($col_num, $row_num, $row[$field]);
					}
				}
			}
			
		}
		
	}
	
	private function makeExportForVariant($fields, $sheet, $sheet_discounts, $sheet_specials, $rov, $export_product_ids) {
		
		$rov_id = !empty($rov['rov_id']) ? $rov['rov_id'] : 0 ;
		
		$language_id = $this->config->get('config_language_id');
		
		// get ro options
		$query = $this->db->query("	SELECT ROO.*, IF(OD.name IS NULL, '?', OD.name) option_name, IF(OVD.name IS NULL, '?', OVD.name) value_name
																FROM `".DB_PREFIX."relatedoptions_option` ROO
																			LEFT JOIN `".DB_PREFIX."option` O ON (O.option_id = ROO.option_id)
																			LEFT JOIN `".DB_PREFIX."option_description` OD ON (OD.option_id = ROO.option_id AND OD.language_id = ".(int)$language_id.")
																			LEFT JOIN `".DB_PREFIX."option_value_description` OVD ON (OVD.option_value_id = ROO.option_value_id)
																WHERE ROO.relatedoptions_id IN
																	(	SELECT RO.relatedoptions_id
																		FROM ".DB_PREFIX."relatedoptions RO
																				,".DB_PREFIX."relatedoptions_variant_product ROVP
																		WHERE ROVP.relatedoptions_variant_id = ".(int)$rov_id."
																			AND ROVP.relatedoptions_variant_product_id = RO.relatedoptions_variant_product_id
																			".( $export_product_ids ? " AND RO.product_id BETWEEN ".(int)$export_product_ids['start']." AND ".(int)$export_product_ids['end']." " : "" )."
																	)
																ORDER BY ROO.relatedoptions_id ASC, O.sort_order ASC
															");
		
		$ro_options = array();
		foreach ( $query->rows as $row ) {
			$relatedoptions_id = $row['relatedoptions_id'];
			if ( !isset($ro_options[$relatedoptions_id]) ) {
				$ro_options[$relatedoptions_id] = array('option_ids'=>array(), 'description'=>'');
			}
			$ro_options[$relatedoptions_id]['option_ids'][$row['option_id']] = $row['option_value_id'];
			$ro_options[$relatedoptions_id]['description'].= ''.$row['option_name'].': '.$row['value_name'].'; ';
		}
		unset($query);
		
		
		// get data to export (ro combs)
		$query = $this->db->query("	SELECT RO.*, P.model product_model
																FROM ".DB_PREFIX."relatedoptions RO
																		,".DB_PREFIX."relatedoptions_variant_product ROVP
																		,".DB_PREFIX."product P
																WHERE ROVP.relatedoptions_variant_id = ".(int)$rov_id."
																	AND ROVP.relatedoptions_variant_product_id = RO.relatedoptions_variant_product_id
																	AND RO.product_id = P.product_id
																	".( $export_product_ids ? " AND RO.product_id BETWEEN ".(int)$export_product_ids['start']." AND ".(int)$export_product_ids['end']." " : "" )."
																ORDER BY P.sort_order ASC, P.product_id ASC, RO.relatedoptions_id ASC
																");
		
		$this->makeExportPutToSheet($rov, $sheet, $query, $fields, $ro_options);
		unset($query);
		
		// discounts
		if ( $sheet_discounts && in_array($this->export_field_discounts, $fields) ) {
			
			$query = $this->db->query("	SELECT RDS.*, RO.product_id
																	FROM `".DB_PREFIX."relatedoptions_discount` RDS
																			,`".DB_PREFIX."relatedoptions` RO
																	WHERE RDS.relatedoptions_id IN
																		(	SELECT RO.relatedoptions_id
																			FROM ".DB_PREFIX."relatedoptions RO
																					,".DB_PREFIX."relatedoptions_variant_product ROVP
																			WHERE ROVP.relatedoptions_variant_id = ".(int)$rov_id."
																				AND ROVP.relatedoptions_variant_product_id = RO.relatedoptions_variant_product_id
																				".( $export_product_ids ? " AND RO.product_id BETWEEN ".(int)$export_product_ids['start']." AND ".(int)$export_product_ids['end']." " : "" )."
																		)
																		AND RO.relatedoptions_id  = RDS.relatedoptions_id
																	ORDER BY RDS.relatedoptions_id ASC
																");
			
			$fields_d = array('product_id', $this->export_field_options, $this->export_field_description_of_options, 'customer_group_id', 'quantity', 'price');
			
			$this->makeExportPutToSheet($rov, $sheet_discounts, $query, $fields_d, $ro_options);
			unset($query);
			
		}
		
		// specials
		if ( $sheet_specials && in_array($this->export_field_specials, $fields) ) {
			
			$query = $this->db->query("	SELECT RDS.*, RO.product_id
																	FROM `".DB_PREFIX."relatedoptions_special` RDS
																			,`".DB_PREFIX."relatedoptions` RO
																	WHERE RDS.relatedoptions_id IN
																		(	SELECT RO.relatedoptions_id
																			FROM ".DB_PREFIX."relatedoptions RO
																					,".DB_PREFIX."relatedoptions_variant_product ROVP
																			WHERE ROVP.relatedoptions_variant_id = ".(int)$rov_id."
																				AND ROVP.relatedoptions_variant_product_id = RO.relatedoptions_variant_product_id
																				".( $export_product_ids ? " AND RO.product_id BETWEEN ".(int)$export_product_ids['start']." AND ".(int)$export_product_ids['end']." " : "" )."
																		)
																		AND RO.relatedoptions_id  = RDS.relatedoptions_id
																	ORDER BY RDS.relatedoptions_id ASC
																");
			
			$fields_s = array('product_id', $this->export_field_options, $this->export_field_description_of_options, 'customer_group_id', 'price');
			
			$this->makeExportPutToSheet($rov, $sheet_specials, $query, $fields_s, $ro_options);
			unset($query);
			
		}
		
	}
	
	public function PHPExcelFixSheetTitle($sheet, $title) {
		
		return mb_substr( str_replace($sheet->getInvalidCharacters(), ' ', htmlspecialchars_decode($title) ), 0, 31);
		
	}
	
	public function makeExport() {
		
		$ro_settings = $this->config->get('related_options');
		$export_fields = $this->request->post['export_fields'];
		
		$export_product_ids = array();
		if ( !empty($this->request->post['export_new_method']) ) {
			if ( $this->request->post['export_new_method'] == 1 ) {
				$export_product_ids['start'] = ( empty($this->request->post['export_new_start_product_id']) ? 0 : (int)$this->request->post['export_new_start_product_id'] );
				$export_product_ids['end'] = ( empty($this->request->post['export_new_end_product_id']) ? $this->getMaxProductIdWithRO() : (int)$this->request->post['export_new_end_product_id'] );
			} elseif ( $this->request->post['export_new_method'] == 2 && !empty($this->request->post['export_new_variant_id']) ) {
				$export_only_variant_id = (int)$this->request->post['export_new_variant_id'];
			}
		}
		
		require_once $this->PHPExcelPath();
		PHPExcel_Shared_File::setUseUploadTempDirectory(true);
		$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM; //PHPExcel_CachedObjectStorageFactory::cache_to_discISAM ; //
		$cacheSettings = array( 'memoryCacheSize' => '32MB');
		if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings)) {
			$this->log->write("Related options, PHPExcel cache error");
		}
		$objPHPExcel = new PHPExcel();
		
		// to get all variant options in necessary order
		$rovs_all = $this->get_variants_options(true);
		
		// to export only variants with data (two steps because of )
		// first all compatible options
		// others by sort order then name
		$query = $this->db->query("	SELECT VP.relatedoptions_variant_id rov_id, V.relatedoptions_variant_name name, COUNT(RO.relatedoptions_id) ro_count
																FROM `".DB_PREFIX."relatedoptions_variant_product` VP
																	LEFT JOIN `".DB_PREFIX."relatedoptions_variant` V ON ( VP.relatedoptions_variant_id = V.relatedoptions_variant_id )
																		,`".DB_PREFIX."relatedoptions` RO
																WHERE RO.relatedoptions_variant_product_id = VP.relatedoptions_variant_product_id
																			".( !empty($export_only_variant_id) ? " AND VP.relatedoptions_variant_id = ".(int)$export_only_variant_id." " : "" )."
																			".( $export_product_ids ? " AND RO.product_id BETWEEN ".(int)$export_product_ids['start']." AND ".(int)$export_product_ids['end']." " : "" )."
																GROUP BY VP.relatedoptions_variant_id, V.relatedoptions_variant_name
																ORDER BY (CASE WHEN V.relatedoptions_variant_id IS NOT NULL THEN 0 ELSE 1 END) ASC
																				,(CASE WHEN V.sort_order IS NOT NULL THEN V.sort_order ELSE 0 END) ASC
																				,(CASE WHEN V.relatedoptions_variant_name IS NOT NULL THEN V.relatedoptions_variant_name ELSE '' END)
																");
		$rovs = $query->rows;
		
		
		// add all sheets
		$sheets_cnt = 0;
		foreach ( $rovs as $rov ) {
			if ( $sheets_cnt == 0 ) { // use default sheet
				$objPHPExcel->setActiveSheetIndex(0);
				$current_sheet = $objPHPExcel->getActiveSheet();
			} else {
				$current_sheet = $objPHPExcel->createSheet($sheets_cnt);
			}
			$current_sheet->getStyle( $current_sheet->calculateWorksheetDimension() )->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			
			$current_sheet->setTitle( $this->PHPExcelFixSheetTitle( $current_sheet, 'RO '.( !empty($rov['name']) ? $rov['name'] : '' ) ) );
			$sheets_cnt++;
		}
		
		
		$sheet_discounts = false;
		if ( in_array('discounts', $export_fields) && !empty($ro_settings['spec_price_discount']) ) {
			$current_sheet = $objPHPExcel->createSheet($sheets_cnt);
			$current_sheet->getStyle( $current_sheet->calculateWorksheetDimension() )->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			$current_sheet->setTitle( $this->PHPExcelFixSheetTitle( $current_sheet, 'Discounts' ) );
			$sheet_discounts = $current_sheet;
			$sheets_cnt++;
		}
		$sheet_specials = false;
		if ( in_array('discounts', $export_fields) && !empty($ro_settings['spec_price_special']) ) {
			$current_sheet = $objPHPExcel->createSheet($sheets_cnt);
			$current_sheet->getStyle( $current_sheet->calculateWorksheetDimension() )->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			$current_sheet->setTitle( $this->PHPExcelFixSheetTitle( $current_sheet, 'Specials' ) );
			$sheet_specials = $current_sheet;
			$sheets_cnt++;
		}
		
		
		// fill data
		$sheets_cnt = 0;
		foreach ( $rovs as $rov ) {
			
			$objPHPExcel->setActiveSheetIndex($sheets_cnt);
			$sheet = $objPHPExcel->getActiveSheet();
			
			$rov['data'] = $rovs_all[$rov['rov_id']];
			
			$this->makeExportForVariant($export_fields, $sheet, $sheet_discounts, $sheet_specials, $rov, $export_product_ids);
			
			$sheet->getStyle( $sheet->calculateWorksheetDimension() )->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			if ( $sheet_discounts ) {
				$sheet_discounts->getStyle( $sheet_discounts->calculateWorksheetDimension() )->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			}
			if ( $sheet_specials ) {
				$sheet_specials->getStyle( $sheet_specials->calculateWorksheetDimension() )->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			}
			
			$sheets_cnt++;
		}
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=related_options.xls');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		
		$objWriter->save('php://output');
		exit();
	}
	
	// option_id:option_value_id, ...
	private function makeImportPrepareOptionsValues($values) {
		
		$arr = explode( ',', trim($values) );
		foreach ( $arr as &$val ) {
			$sub_arr = explode(':', trim($val));
			foreach ( $sub_arr as &$sub_val ) {
				$sub_val = (int)$sub_val;
			}
			unset($sub_val);
			
			$val = implode( ':', $sub_arr);
		}
		unset($val);
		
		return implode(',', $arr);
	}
	
	// option_id:option_value_id, ...
	private function makeImportParceOptions($values_string) {
		
		if ( $values_string ) {
		
			$options = array();
			$arr = explode( ',', trim($values_string) );
			foreach ($arr as $val) {
				$sub_arr = explode(':', $val);
				$option_id = 0;
				$option_value_id = 0;
				if ( count($sub_arr) ) {
					$option_id = (int)$sub_arr[0];
					if ( count($sub_arr) > 1 ) {
						$option_value_id = (int)$sub_arr[1];
					}
				}
				$options[$option_id] = $option_value_id;
			}
			
			return $options;
			
		} else {
			return array();
		}
		
	}
	
	private function makeImportReadHeader($sheet, $data, $requred_columns) {
		
		$result = array('error'=>array());
		
		foreach ( $data[0] as &$val ) {
			$val = utf8_strtolower( trim($val) );
		}
		unset($val);
		$head = array_flip($data[0]);
			
		foreach ( $requred_columns as $column ) {
			if ( !isset($head[$column]) ) {
				$result['error'] = '"'.$column.'" '.$this->language->get('entry_import_new_error_not_found').' "'.$sheet->getTitle().'" ';
			}
		}	
		
		$result['head'] = $head;
		return $result;
		
	}
	
	// read discounts or special
	private function makeImportReadDS($sheet, $products, $fields, $sheet_name) {
		
		$result = array('error'=>array());
		
		$requred_columns = array('product_id', $this->export_field_options);
		
		$data = $sheet->toArray();
		
		if (count($data) > 1) {
		
			$header_read_result = $this->makeImportReadHeader($sheet, $data, $requred_columns);
			$head = $header_read_result['head'];
			if ( $header_read_result['error'] ) {
				$result['error'][] = $header_read_result['error'];
			}
			
			if ( !$result['error'] ) {
				
				for ($i=1;$i<count($data);$i++) {
					$row = $data[$i];
					$ds = array();
					$ro_options_string = '';
					$product_id = 0;
					foreach ( $fields as $field ) {
						if ( isset($head[$field]) ) {
							if ( $field == $this->export_field_options ) {
								$ro_options_string = $this->makeImportPrepareOptionsValues( $row[(int)$head[$field]] );
							} elseif ( $field == 'product_id') {
								$product_id = (int)$row[(int)$head[$field]];
							} else {
								$ds[$field] = (string)$row[(int)$head[$field]];
							}
						}
					}
					
					if ( !isset($products[$product_id]) || !isset($products[$product_id][$ro_options_string]) ) {
						$result['error'][] = $this->language->get('entry_import_new_error_no_ro').' "'.$sheet->getTitle().'"  #'.($i+1).' ('.$product_id.': '.$ro_options_string.')';
					} else {
						$products[$product_id][$ro_options_string][$sheet_name][] = $ds;
					}
				}
			}
		
		} else {
			// it's not an error for discounts and specials
			//$result['error'][] = $this->language->get('entry_import_new_error_no_data').' "'.$sheet->getTitle().'" ';
		}
		
		$result['products'] = $products;
		return $result;
		
	}
	
	private function makeImportReadRO($sheet, $products) {
		
		$result = array('error'=>array(), 'warning'=>array());
		
		$requred_columns = array('product_id', $this->export_field_options);
		
		$data = $sheet->toArray();
		
		if (count($data) > 1) {
			
			$header_read_result = $this->makeImportReadHeader($sheet, $data, $requred_columns);
			$head = $header_read_result['head'];
			if ( $header_read_result['error'] ) {
				$result['error'][] = $header_read_result['error'];
			}
			
			// put to $products[] all combinations without separating by variants
			if ( !$result['error'] ) {
				
				for ($i=1;$i<count($data);$i++) {
					
					$row = $data[$i];
					$ro_comb = array();
					$ro_options_string = '';
					$product_id = 0;
					foreach ( $this->getExportNewFields() as $field ) {
						if ( isset($head[$field]) ) {
							if ( $field == $this->export_field_options ) {
								$ro_options_string = $this->makeImportPrepareOptionsValues( $row[(int)$head[$field]] );
								$ro_comb['options'] = $this->makeImportParceOptions($ro_options_string);
							} elseif ( $field == 'product_id') {
								$product_id = (int)$row[(int)$head[$field]];
							} elseif ( $field != $this->export_field_discounts && $field != $this->export_field_specials )  {
								$ro_comb[$field] = (string)$row[(int)$head[$field]];
							}
						}
					}
					
					if ( !isset($products[$product_id]) ) {
						$products[$product_id] = array();
					}
					$products[$product_id][$ro_options_string] = $ro_comb;
					
				}
				
			}
			
			
		} else {
			$result['warning'][] = $this->language->get('entry_import_new_error_no_data').' "'.$sheet->getTitle().'" ';
		}
		
		$result['products'] = $products;
		return $result;
	}
	
	public function makeImport() {
		
		$json = array('error'=>array(), 'warning'=>array());
		
		$this->load->language('module/related_options');
		
		if (!empty($this->request->files['file']['name']) && $this->request->files['file']['tmp_name'] ) {
			
			require_once $this->PHPExcelPath();
			$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
			$cacheSettings = array( 'memoryCacheSize' => '32MB');
			PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
			$excel = PHPExcel_IOFactory::load($this->request->files['file']['tmp_name']); // PHPExcel
			
			$sheet_discounts = false;
			$sheet_specials = false;
			
			$products = array();
			
			// read RO sheets
			foreach ($excel->getAllSheets() as $sheet) {
				$sheet_title = trim(utf8_strtolower($sheet->getTitle()));
				
				if ( $sheet_title == $this->export_field_discounts ) {
					$sheet_discounts = $sheet;
				} else if ( $sheet_title == $this->export_field_specials ) {
					$sheet_specials = $sheet;
				} elseif ( substr($sheet_title, 0, 2) == 'ro' ) {
					
					// import combination
					$result = $this->makeImportReadRO($sheet, $products);
					$products = $result['products'];
					if ( $result['error'] ) {
						$json['error']= $json['error']+$result['error'];
					}
					if ( $result['warning'] ) {
						$json['warning']= $json['warning']+$result['warning'];
					}
				} else {
					$json['error'][] = $this->language->get('entry_import_new_error_skipped');
				}
			}
			
			if ( $products ) {
			
				// read Discounts and Specials sheets
				if ( $sheet_discounts ) {
					$result = $this->makeImportReadDS($sheet_discounts, $products, array('product_id',$this->export_field_options,'customer_group_id','quantity','price'), 'discounts');
					$products = $result['products'];
					if ( $result['error'] ) {
						$json['error']= $json['error']+$result['error'];
					}
				}
				if ( $sheet_specials ) {
					$result = $this->makeImportReadDS($sheet_specials, $products, array('product_id',$this->export_field_options,'customer_group_id','price'), 'specials');
					$products = $result['products'];
					if ( $result['error'] ) {
						$json['error']= $json['error']+$result['error'];
					}
				}
			} else {
				$json['error'][] = $this->language->get('entry_import_new_error_no_sheets');
			}
			
			if ( !$json['error'] ) {
				
				if (isset($this->request->post['import_delete_before']) && $this->request->post['import_delete_before'] == 1) {
					$this->model_module_related_options->delete_all_related_options();
				}
				
				$ro_cnt = 0;
				foreach ($products as $product_id => $product) {
					$ro_cnt+= count($product);
					
					if (isset($this->request->post['import_delete_before']) && $this->request->post['import_delete_before'] == 2) {
						$this->model_module_related_options->delete_all_related_options($product_id);
					}
					
					$ro_data = $this->model_module_related_options->get_ro_data($product_id);
					
					$new_ro_combs = array();
					
					
					foreach ($product as $new_ro) {
						
						$new_options_ids = array();
						foreach ($new_ro['options'] as $option_id => $option_value_id) {
							$new_options_ids[] = $option_id;
						}
						
						$ro_found = false;
						foreach ($ro_data as &$ro_dt) {
							
							// combination set is relevant, let's find current new combination in this set
							if ( !array_diff($new_options_ids, $ro_dt['options_ids']) && !array_diff($ro_dt['options_ids'], $new_options_ids) && count($new_ro['options']) == count($ro_dt['options_ids']) ) {
							//if ( !array_diff_assoc($new_options_ids, $ro_dt['options_ids']) && count($new_ro['options']) == count($ro_dt['options_ids']) ) {
								
								foreach ($ro_dt['ro'] as &$ro_comb) {
									if ( !array_diff_assoc($new_ro['options'], $ro_comb['options']) && count($new_ro['options']) == count($ro_comb['options'])) {
										
										// always clean discounts and specials
										$ro_comb['discounts'] = array();
										$ro_comb['specials'] = array();
										
										// refresh relevant combination field accordingly to new combination
										foreach ($ro_comb as $ro_comb_key => &$ro_comb_value) {
											if (isset($new_ro[$ro_comb_key])) {
												$ro_comb_value = $new_ro[$ro_comb_key];
											}
										}
										
										unset($ro_comb_value);
										
										$ro_found = true;
										break;
									}
								}
								unset($ro_comb);
								
								// relevant combination is not found, but combination set is relevant, let's add this combination to this set
								if (!$ro_found) {
									$ro_dt['ro'][] = $new_ro;
									$ro_found = true;
								}
								
							}
						}
						unset($ro_dt);
						if ( !$ro_found ) { // if there's not relevant set of options combinations, let's add new set
							
							if ( count($ro_data) == 0 || $this->getExtensionCode() == 'ropro' ) { // import few ro variants per product only for ROPRO
							
								$new_ro_combs_set = array(	'rovp_id' => ''
																					,	'use' 		=> true
																					,	'related_options_variant_search' 	=> true
																					, 'ro'			=> array($new_ro)
																					, 'options_ids'	=> $new_options_ids
																					);
							
								$ro_data[] = $new_ro_combs_set;
							}
						}
						
					}
					
					$product_data = array('ro_data_included'=>true, 'ro_data'=>$ro_data);
					$this->model_module_related_options->set_ro_data($product_id, $product_data);	
					
				}
				$json['products'] = count($products);
				$json['relatedoptions'] = $ro_cnt;
				
				$json['success'] = $this->language->get('entry_import_new_ok');
				
			}
			
		} else {
			$json['error'] = $this->language->get('entry_import_new_error_not_uploaded');
		}
		
		return $json;
	}
	
	
	public function installed() {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'module' AND `code` = 'related_options'");
		return $query->num_rows;
		
	}
	
	
	
	public function current_version() {
		return "1.2.0";
	}
	
	
	public function install()
	{
		$this->uninstall();
    
    $this->db->query(
        'CREATE TABLE IF NOT EXISTS
          `pqw5hl6v9_5752e8a7_relatedoptions` (
            `relatedoptions_id` int(11) NOT NULL AUTO_INCREMENT,
						`relatedoptions_variant_product_id` int(11) NOT NULL,
            `product_id` int(11) NOT NULL,
            `quantity` int(4) NOT NULL,
						`model` varchar(64) NOT NULL,
						`sku` varchar(64) NOT NULL,
						`upc` varchar(12) NOT NULL,
						`ean` VARCHAR(14) NOT NULL,
						`location` varchar(128) NOT NULL,
						`stock_status_id` int(11) NOT NULL,
						`weight_prefix` varchar(1) NOT NULL,
						`weight` decimal(15,8) NOT NULL,
						`price_prefix` VARCHAR(1) NOT NULL,
						`price` decimal(15,4) NOT NULL,
						`defaultselect` tinyint(11) NOT NULL,
						`defaultselectpriority` int(11) NOT NULL,
            PRIMARY KEY (`relatedoptions_id`),
						KEY (`relatedoptions_variant_product_id`),
            FOREIGN KEY (product_id) REFERENCES pqw5hl6v9_5752e8a7_product(product_id) ON DELETE CASCADE,
						FOREIGN KEY (relatedoptions_variant_product_id) REFERENCES pqw5hl6v9_5752e8a7_relatedoptions_variant_product(relatedoptions_variant_product_id) ON DELETE CASCADE,
						KEY `quantity` (`quantity`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8'
    );
		
    $this->db->query(
        'CREATE TABLE IF NOT EXISTS
          `pqw5hl6v9_5752e8a7_relatedoptions_option` (
            `relatedoptions_id` int(11) NOT NULL,
            `product_id` int(11) NOT NULL,
            `option_id` int(11) NOT NULL,
            `option_value_id` int(11) NOT NULL,
            FOREIGN KEY (`relatedoptions_id`) 	REFERENCES `pqw5hl6v9_5752e8a7_relatedoptions`(`relatedoptions_id`) ON DELETE CASCADE,
            FOREIGN KEY (`option_value_id`) 	REFERENCES `pqw5hl6v9_5752e8a7_option_value`(`option_value_id`) ON DELETE CASCADE,
            FOREIGN KEY (`option_id`) 			REFERENCES `pqw5hl6v9_5752e8a7_option`(`option_id`) 			ON DELETE CASCADE,
            FOREIGN KEY (`product_id`) 			REFERENCES `pqw5hl6v9_5752e8a7_product`(`product_id`) 			ON DELETE CASCADE
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8'
    );
		
		$this->db->query(
        'CREATE TABLE IF NOT EXISTS
          `pqw5hl6v9_5752e8a7_relatedoptions_variant` (
            `relatedoptions_variant_id` int(11) NOT NULL AUTO_INCREMENT,
            `relatedoptions_variant_name` char(255) NOT NULL,
						`sort_order` int(3) NOT NULL,
            PRIMARY KEY (`relatedoptions_variant_id`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8'
    );
		
		$this->db->query(
        'CREATE TABLE IF NOT EXISTS
          `pqw5hl6v9_5752e8a7_relatedoptions_variant_option` (
            `relatedoptions_variant_id` int(11) NOT NULL,
            `option_id` int(11) NOT NULL,
            FOREIGN KEY (`option_id`) 			REFERENCES `pqw5hl6v9_5752e8a7_option`(`option_id`) 			ON DELETE CASCADE,
						FOREIGN KEY (`relatedoptions_variant_id`) 			REFERENCES `pqw5hl6v9_5752e8a7_relatedoptions_variant`(`relatedoptions_variant_id`) 			ON DELETE CASCADE
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8'
    );
		
		$this->db->query(
        'CREATE TABLE IF NOT EXISTS
          `pqw5hl6v9_5752e8a7_relatedoptions_variant_product` (
						`relatedoptions_variant_product_id` int(11) NOT NULL AUTO_INCREMENT,
            `relatedoptions_variant_id` int(11) NOT NULL,
            `product_id` int(11) NOT NULL,
						`relatedoptions_use` tinyint(1) NOT NULL,
						PRIMARY KEY (`relatedoptions_variant_product_id`),
            FOREIGN KEY (`product_id`) 			REFERENCES `pqw5hl6v9_5752e8a7_product`(`product_id`) 			ON DELETE CASCADE,
						FOREIGN KEY (`relatedoptions_variant_id`) 			REFERENCES `pqw5hl6v9_5752e8a7_relatedoptions_variant`(`relatedoptions_variant_id`) 			ON DELETE CASCADE
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8'
    );
		
		$this->db->query(
				'CREATE TABLE IF NOT EXISTS
					`pqw5hl6v9_5752e8a7_relatedoptions_to_char` (
						`relatedoptions_id` int(11) NOT NULL,
						`char_id` varchar(255) NOT NULL,
						KEY (`relatedoptions_id`),
						KEY `char_id` (`char_id`),
						FOREIGN KEY (relatedoptions_id) REFERENCES pqw5hl6v9_5752e8a7_relatedoptions(relatedoptions_id) ON DELETE CASCADE
					) ENGINE=MyISAM DEFAULT CHARSET=utf8'
		);
		
		$this->db->query(
				'CREATE TABLE IF NOT EXISTS
					`pqw5hl6v9_5752e8a7_relatedoptions_discount` (
						`relatedoptions_id` int(11) NOT NULL,
						`customer_group_id` int(11) NOT NULL,
						`quantity` int(4) NOT NULL,
						`priority` int(5) NOT NULL,
						`price` decimal(15,4) NOT NULL,
						KEY (`relatedoptions_id`),
						KEY (`customer_group_id`),
						KEY (`quantity`),
						FOREIGN KEY (relatedoptions_id) REFERENCES pqw5hl6v9_5752e8a7_relatedoptions(relatedoptions_id) ON DELETE CASCADE,
						FOREIGN KEY (customer_group_id) REFERENCES pqw5hl6v9_5752e8a7_customer_group(customer_group_id) ON DELETE CASCADE
					) ENGINE=MyISAM DEFAULT CHARSET=utf8'
		);
		
		$this->db->query(
				'CREATE TABLE IF NOT EXISTS
					`pqw5hl6v9_5752e8a7_relatedoptions_special` (
						`relatedoptions_id` int(11) NOT NULL,
						`customer_group_id` int(11) NOT NULL,
						`priority` int(5) NOT NULL,
						`price` decimal(15,4) NOT NULL,
						KEY (`relatedoptions_id`),
						KEY (`customer_group_id`),
						FOREIGN KEY (relatedoptions_id) REFERENCES pqw5hl6v9_5752e8a7_relatedoptions(relatedoptions_id) ON DELETE CASCADE,
						FOREIGN KEY (customer_group_id) REFERENCES pqw5hl6v9_5752e8a7_customer_group(customer_group_id) ON DELETE CASCADE
					) ENGINE=MyISAM DEFAULT CHARSET=utf8'
		);
		
		$this->install_additional_tables();
    
	}
	
	public function install_additional_tables() {
		
		$this->db->query(
        'CREATE TABLE IF NOT EXISTS
          `pqw5hl6v9_5752e8a7_relatedoptions_search` (
            `product_id` int(11) NOT NULL,
						`ro_ids` varchar(255) NOT NULL,
						`model` varchar(64) NOT NULL,
						`sku` varchar(64) NOT NULL,
            FOREIGN KEY (product_id) REFERENCES pqw5hl6v9_5752e8a7_product(product_id) ON DELETE CASCADE
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8'
    );
		
		$query = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."relatedoptions` WHERE field='stock_status_id' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `".DB_PREFIX."relatedoptions` ADD COLUMN `stock_status_id` int(11) NOT NULL " );
		}
		
		$this->checkAndMakeUpgrade();
		
	}
	
	// RO2 to ROPRO structure upgrade
	private function checkAndMakeUpgrade() {
		
		$tables_upgraded = $this->checkAndMakeUpgradeTables();
		if ( $tables_upgraded ) {
			$this->makeUpgrade();
		}
		
	}
	private function makeUpgrade() {
		
		$this->db->query("UPDATE `".DB_PREFIX."relatedoptions` RO
											SET RO.`relatedoptions_variant_product_id` =
												(	SELECT ROVP.`relatedoptions_variant_product_id` FROM `".DB_PREFIX."relatedoptions_variant_product` ROVP
													WHERE ROVP.product_id = RO.product_id
												)
											");
		
	}
	private function checkAndMakeUpgradeTables() {
		
		$tables_upgraded = false;
		
		$query = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."relatedoptions` WHERE field='relatedoptions_variant_product_id' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `".DB_PREFIX."relatedoptions` ADD COLUMN `relatedoptions_variant_product_id` INT(11) NOT NULL " );
			$tables_upgraded = true;
		}
		
		$query = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."relatedoptions_variant` WHERE field='sort_order' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `".DB_PREFIX."relatedoptions_variant` ADD COLUMN `sort_order` INT(3) NOT NULL " );
			$tables_upgraded = true;
		}
		
		$query = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."relatedoptions_variant_product` WHERE field='relatedoptions_variant_product_id' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `".DB_PREFIX."relatedoptions_variant_product` ADD COLUMN `relatedoptions_variant_product_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT " );
			$tables_upgraded = true;
		}
		
		return $tables_upgraded;
	}
	
	public function uninstall()
	{
		$this->db->query("DROP TABLE IF EXISTS 
			`" . DB_PREFIX . "relatedoptions`,
			`" . DB_PREFIX . "relatedoptions_to_char`,
			`" . DB_PREFIX . "relatedoptions_variant`,
			`" . DB_PREFIX . "relatedoptions_variant_option`,
			`" . DB_PREFIX . "relatedoptions_variant_product`,
			`" . DB_PREFIX . "relatedoptions_discount`,
			`" . DB_PREFIX . "relatedoptions_special`,
			`" . DB_PREFIX . "relatedoptions_option`,
			`" . DB_PREFIX . "relatedoptions_search`
			;");
	}

 
	
}
