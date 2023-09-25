<?php
//  Related Options / Связанные опции 
//  Support: support@liveopencart.com / Поддержка: help@liveopencart.ru

class ModelModuleRelatedOptions extends Model {

	var $cache_sets_by_poids = array();
	var $liveprice_settings = false;
	private $module_installed_status = null;
	
	public function getThemeName() {
    if ( VERSION >= '2.2.0.0' ) {
      if ($this->config->get('config_theme') == 'theme_default') {
        $theme_name = $this->config->get('theme_default_directory');
      } else {
        $theme_name = substr($this->config->get('config_theme'), 0, 6) == 'theme_' ? substr($this->config->get('config_theme'), 6) : $this->config->get('config_theme') ;
      }
    } else {  
      $theme_name = $this->config->get('config_template');
    }
		
		if ($theme_name == 'BurnEngine') {
			$theme_info = (array) $this->config->get( 'BurnEngine_theme');
			if ($theme_info && !empty($theme_info['id']) ) {
				$theme_name = $theme_name.'_'.$theme_info['id']; 
			}
		}
		return $theme_name;
  }
	
	public function getScriptPathWithVersion($p_path) {
		
		$path = $p_path;
		if ( substr($path, 0, strlen(DIR_APPLICATION)) == DIR_APPLICATION ) {
			$path = substr($path, strlen(DIR_APPLICATION));
		}
		
		$basic_dir = 'catalog/';
		$file_path = DIR_APPLICATION.$path;
		
		if ( file_exists($file_path) ) {
			return $basic_dir.$path.'?v='.filemtime($file_path);
		}
	}
	
	public function getProductPageScripts() {
		return array(
			$this->model_module_related_options->getScriptPathWithVersion('view/extension/related_options/js/liveopencart.select_option_toggle.js'),
			$this->model_module_related_options->getScriptPathWithVersion('view/extension/related_options/js/liveopencart.related_options.js'),
		);
	}
	
	public function getRODataForProductList($product_id, $for_all_themes=false) {
		
		if ( $this->installed() && ( $for_all_themes || $this->getThemeName() == 'themeXXX' || $this->getThemeName() == 'theme725' || $this->getThemeName() == 'BurnEngine' ) ) {
			return $this->get_ro_data($product_id, true);
		}
		
	}
	
	public function getROCombSelectedByDefault($product_id, $search_request='') {
		$ro_settings = $this->config->get('related_options');
		$ros_to_select = false;
		if ( $search_request && !empty($ro_settings['spec_model']) ) {
			$ros_to_select = $this->getRelatedOptionsIdsFromSearch($product_id, $search_request);
		} elseif ( isset($ro_settings['select_first']) && $ro_settings['select_first'] == 1 ) {
			$ros_to_select = $this->model_module_related_options->getRelatedOptionsIdsAutoSelectFirst($product_id);
		}
		return $ros_to_select;
	}
	
	// << orders editing 
	public function getOrderOptions($order_id, $order_product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		return $query->rows;
	}
	
	// return order quantity back to product quantity (on order delete)
	public function update_ro_quantity($product_id, $order_id, $order_product_id, $quantity, $sign='+') {
		
		if (!$this->installed()) {
			return;
		}
		
		$query = $this->db->query("SELECT subtract FROM `".DB_PREFIX."product` WHERE `product_id` = ".(int)$product_id." " );
		if ($query->num_rows && $query->row['subtract']) {
			
			$product_options = $this->model_module_related_options->getOrderOptions((int)$order_id, (int)$order_product_id);
			if ($product_options) {
				
				$options = array();
				foreach ($product_options as $product_option) {
					$options[$product_option['product_option_id']] = $product_option['product_option_value_id'];
				}
				
				$ro_combs = $this->get_related_options_sets_by_poids($product_id, $options, false, true);
				if ($ro_combs) {
					foreach ($ro_combs as $ro_comb) {
						
						$this->db->query("UPDATE `".DB_PREFIX."relatedoptions` SET quantity=(quantity".$sign."".(int)$quantity.") WHERE `relatedoptions_id` = ".(int)$ro_comb['relatedoptions_id']." " );
					}
				}
				$this->updatePOVQuantitiesByROIfNecessary($product_id);
			}	
		}
		
	}
	
	private function updatePOVQuantitiesByROIfNecessary($product_id) {
		
		$ro_settings = $this->config->get('related_options');
		
		if ( !empty($ro_settings['update_options']) ) {
			
			$query = $this->db->query("
				SELECT ROO.option_value_id, SUM(RO.quantity) quantity
				FROM ".DB_PREFIX."relatedoptions_option ROO
						,".DB_PREFIX."relatedoptions RO
				WHERE RO.product_id = ".(int)$product_id."
					AND RO.relatedoptions_id = ROO.relatedoptions_id
				GROUP BY ROO.option_value_id
			");
			
			foreach ( $query->rows as $row ) {
				$this->db->query("
					UPDATE ".DB_PREFIX."product_option_value
					SET quantity = ".(int)$row['quantity']."
					WHERE product_id = ".(int)$product_id."
						AND option_value_id = ".(int)$row['option_value_id']."
				");
			}
			
		}
		
	}
	
	// >> orders editing
	

	// returns only switched-on additional fields (sku, upc, location)
	public function getAdditionalFields() {
		
		$fields = array();
		
		if ($this->installed()) {
			$ro_settings = $this->config->get('related_options');
			$std_fields = array('sku', 'upc', 'ean', 'location');
			foreach ($std_fields as $field) {
				if ( isset($ro_settings['spec_'.$field]) && $ro_settings['spec_'.$field] ) {
					$fields[] = $field;
				}
			}
		}
		
		return $fields;
	}
	
	public function getCustomFields($product_info, $ro_combs) {
		
		$ro_settings = $this->config->get('related_options');
		
		$ro_weight = $product_info['weight'];
		$ro_adds = array(
			'model'			=>	$product_info['model'],
			'sku'				=>	$product_info['sku'],
			'upc'				=>	$product_info['upc'],
			'ean'				=>	$product_info['ean'],
			'location'	=>	$product_info['location'],
		);
		
		$last_model_is_from_product = true;
		if ($ro_combs) {
			
			foreach ($ro_combs as $ro_comb) {
				
				foreach ($ro_adds as $ro_field_name => &$ro_field_value) {
					
					if ( isset($ro_settings['spec_'.$ro_field_name]) && $ro_settings['spec_'.$ro_field_name] ) {
						
						if ( $ro_comb[$ro_field_name] ) {
						
							if ($ro_field_name == 'model') {
								
								if ( $ro_settings['spec_'.$ro_field_name] == 1 ) {
									$ro_field_value = $ro_comb[$ro_field_name];
								} elseif ( $ro_settings['spec_'.$ro_field_name] == 2 ) {
									if ( $last_model_is_from_product ) {
										$ro_field_value = '';
										$last_model_is_from_product = false;
									}
									if ( $ro_field_value && isset($ro_settings['spec_model_delimiter_ro']) ) {
										$ro_field_value.= $ro_settings['spec_model_delimiter_ro'];
									}
									$ro_field_value.= $ro_comb[$ro_field_name];
								} elseif ( $ro_settings['spec_'.$ro_field_name] == 3 ) {
									/*
									if ($ro_field_value == '') {
										$ro_field_value = $product_info[$ro_field_name];
										$last_model_is_from_product = true;
									}
									*/
									
									if ( $last_model_is_from_product && isset($ro_settings['spec_model_delimiter_product']) ) {
										$ro_field_value.= $ro_settings['spec_model_delimiter_product'];
									} elseif ( !$last_model_is_from_product && isset($ro_settings['spec_model_delimiter_ro']) ) {
										$ro_field_value.= $ro_settings['spec_model_delimiter_ro'];
									}
									
									$ro_field_value.= $ro_comb[$ro_field_name];
									$last_model_is_from_product = false;
								}
								
							} else {
								
								if ($ro_comb[$ro_field_name]) {
									$ro_field_value = $ro_comb[$ro_field_name];
								}
							}
						}
					}
				}
				unset($ro_field_value);
				
				if (!empty($ro_settings['spec_weight'])) {
					
					if ( (float)$ro_comb['weight'] ) {
						if ($ro_comb['weight_prefix'] == '=') {
							$ro_weight = (float)$ro_comb['weight'];
						} elseif ($ro_comb['weight_prefix'] == '+') {
							$ro_weight+= (float)$ro_comb['weight'];
						} elseif ($ro_comb['weight_prefix'] == '-') {
							$ro_weight-= (float)$ro_comb['weight'];
						}
					}
				}
			}
		}
		
		return array('codes' => $ro_adds, 'weight' => $ro_weight);
	}
	
	public function updateOrderProductAdditionalFields($product, $order_product_id) {
		
		if ($this->installed()) {
			$this->check_order_product_table();
			$ro_settings = $this->config->get('related_options');
			$quantity = (int)$product['quantity'];
			
			$ro_options = array();
			foreach ($product['option'] as $option) {
				if (isset($option['product_option_value_id'])) {
					$ro_options[$option['product_option_id']] = $option['product_option_value_id'];
				}
			}
			
			$ro_combs = $this->get_related_options_sets_by_poids($product['product_id'], $ro_options);
			
			// to set values even product hasn't related options
				
			if ( !$this->model_catalog_product ) {
				$this->load->model('catalog/product');
			}
			
			$product = $this->model_catalog_product->getProduct($product['product_id']);
			
			$custom_fields = $this->getCustomFields($product, $ro_combs);
			
			foreach ($custom_fields['codes'] as $ro_field_name => $ro_field_value) {
				if (!empty($ro_settings['spec_'.$ro_field_name])) {
					$this->db->query("UPDATE " . DB_PREFIX . "order_product SET `".$ro_field_name."`='".$this->db->escape($ro_field_value)."' WHERE order_product_id = " . (int)$order_product_id . "");
				}
			}
			
			if (!empty($ro_settings['spec_weight'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "order_product SET `weight`='".(float)($custom_fields['weight']*$quantity)."' WHERE order_product_id = " . (int)$order_product_id . "");
			}
		}
	}
	
	public function get_related_options_model_sku($product_id, $ro_options, $product_model, $product_sku, $product_upc='') {
		
		
		if (!$this->installed()) return;
			
		$ro_settings = $this->config->get('related_options');
		
		$ro_combs = $this->get_related_options_sets_by_poids($product_id, $ro_options);
		
		$ro_model = '';
		$ro_sku = '';
		$ro_upc = '';
		
		$last_model_is_from_product = false;
		if ($ro_combs) {
			foreach ($ro_combs as $ro_comb) {
				
				if ( isset($ro_settings['spec_model']) && $ro_settings['spec_model'] ) {
					if ($ro_settings['spec_model'] == 1) {
						$ro_model = $ro_comb['model'];
					} elseif ($ro_settings['spec_model'] == 2) {
						if ($ro_model && isset($ro_settings['spec_model_delimiter_ro'])) {
							$ro_model.= $ro_settings['spec_model_delimiter_ro'];
						}
						$ro_model.= $ro_comb['model'];
					} elseif ($ro_settings['spec_model'] == 3) {
						if ($ro_model == '') {
							$ro_model = $product_model;
							$last_model_is_from_product = true;
						}
						if ( $last_model_is_from_product && isset($ro_settings['spec_model_delimiter_product']) ) {
							$ro_model.= $ro_settings['spec_model_delimiter_product'];
						} elseif ( !$last_model_is_from_product && isset($ro_settings['spec_model_delimiter_ro']) ) {
							$ro_model.= $ro_settings['spec_model_delimiter_ro'];
						}
						$ro_model.= $ro_comb['model'];
						$last_model_is_from_product = false;
					}
				}
				
				if ( isset($ro_settings['spec_sku']) && $ro_settings['spec_sku'] ) {
					if ($ro_settings['spec_sku'] == 1) {
						$ro_sku = $ro_comb['sku'];
					} elseif ($ro_settings['spec_sku'] == 2) {
						$ro_sku.= $ro_comb['sku'];
					} elseif ($ro_settings['spec_sku'] == 3) {
						if ($ro_sku == '') {
							$ro_sku = $product_sku;
						}
						$ro_sku.= $ro_comb['sku'];	
					}
				}
				
				if ( isset($ro_settings['spec_upc']) && $ro_settings['spec_upc'] ) {
					if ($ro_settings['spec_upc'] == 1) {
						$ro_upc = $ro_comb['upc'];
					} elseif ($ro_settings['spec_upc'] == 2) {
						$ro_upc.= $ro_comb['upc'];
					} elseif ($ro_settings['spec_upc'] == 3) {
						if ($ro_upc == '') {
							$ro_upc = $product_upc;
						}
						$ro_upc.= $ro_comb['upc'];	
					}
				}
				
			}
		}
		
		return array('model'=>$ro_model, 'sku'=>$ro_sku, 'upc'=>$ro_upc);
		
	}
	
	public function getRelatedOptionsIdsAutoSelectFirst($product_id) {
		
		$ro_ids = array();
		$ro_data = $this->get_ro_data($product_id);
		
		$existing_options = array();
		foreach ($ro_data as $ro_dt) {
			
			$ro_combs = array();
			if ( $existing_options ) { // filter combinations by option values from previous combinations
				
				foreach ( $ro_dt['ro'] as $ro ) {
					$all_values_equal = true;
					foreach ($ro['options_original'] as $option_id => $option_value_id) {
						if ( isset($existing_options[$option_id]) && $existing_options[$option_id] != $option_value_id ) {
							$all_values_equal = false;
							break;
						}
					}
					if ( $all_values_equal ) {
						$ro_combs[] = $ro;
					}
				}
				
			} else {
				$ro_combs = $ro_dt['ro'];
			}
			
			$ro_default = array();
			
			foreach ( $ro_combs as $ro ) {
				if ($ro['defaultselect']) {
					$ro_default[] = $ro;
				}
			}
			
			$ro_comb = false;
			if ( count($ro_default) == 0 ) {
				$ro_default = $ro_combs;
			}
			
			foreach ($ro_default as $ro) {
				if ($ro_comb === false || $ro_comb['defaultselectpriority'] > $ro['defaultselectpriority']) {
					$ro_comb = $ro;
				}
			}
			
			if ($ro_comb) {
				$ro_ids[] = $ro_comb['relatedoptions_id'];
				foreach ( $ro_comb['options_original'] as $option_id => $option_value_id ) {
					$existing_options[$option_id] = $option_value_id;
				}
			}
		}
		
		return $ro_ids;
	}
	
	public function getRelatedOptionsIdsFromSearch($product_id, $search_string) {
		
		$ro_settings = $this->config->get('related_options');
		
		if ( isset($ro_settings['spec_model']) ) {
			if ( $ro_settings['spec_model']==2 || $ro_settings['spec_model']==3 ) {
			
				$query = $this->db->query("	SELECT *
																		FROM 	`".DB_PREFIX."relatedoptions_search`
																		WHERE	product_id = ".(int)$product_id."
																			AND LCASE(`model`) = '" . $this->db->escape(utf8_strtolower($search_string)) . "'
																		");
				
				if ($query->num_rows) {
					return explode(',',$query->row['ro_ids']);
				}
				
			} elseif ( $ro_settings['spec_model']==1 ) {
				
				$query = $this->db->query("	SELECT *
																		FROM 	`".DB_PREFIX."relatedoptions`
																		WHERE	product_id = ".(int)$product_id."
																			AND LCASE(`model`) = '" . $this->db->escape(utf8_strtolower($search_string)) . "'
																		");
				
				$ro_ids = array();
				foreach ($query->rows as $row) {
					$ro_ids[] = $row['relatedoptions_id'];
				}
				return $ro_ids;
				
			}
		}
		return false;
	}
	
	// Live Price PRO has setting to calculate RO combination discounts as addition (+ or -) if RO combination price prefix is + (plus) or - (minus)
	// in this case RO discounts do not replace standard discounts, just go to ro_price_modificator
	private function calc_price_with_ro_get_discount($ro_comb, $quantity) {
		$discount_price = 0;
		if ($quantity !== false && $quantity >= 1 && !empty($ro_comb['discounts'])) {
			
			$discounts_reversed = array_reverse($ro_comb['discounts']); // change order to from the higher to the lower
			foreach ($discounts_reversed as $discount) { 
				if ($quantity >= $discount['quantity']) {
					
					$discount_price = $discount['price'];
					
					break; // array of discounts is ordered (DESC), thus first found occurrence is enough
				}
			}
		}
		return $discount_price;
	}
	
	// without discouts and specials ?
	public function calc_price_with_ro($product_price, $ro_combs, $special=0, $stock=false, $ro_price_modificator=0, $quantity=false) {
  		
		$ro_settings = $this->config->get('related_options');
		$lp_settings = $this->getLivePriceSettings();
		
		$ro_price = $product_price;
		$ro_discount_addition_total = 0;
		$ro_special_addition_total = 0;
		/*
		if ( !empty($ro_settings['spec_price']) ) {
			$ro_price = $product_price;
		} else {
			$ro_price = false;
		}
		*/
		$in_stock = null;
		
		if ($this->installed()) {
			
			foreach ($ro_combs as $ro_comb) {
				
				if ( !empty($ro_settings['spec_price']) ) {
					
					//if (isset($ro_comb['price']) && $ro_comb['price']!=0) {
						// "+" may has effect even without price (by discounts)
						if (isset($ro_settings['spec_price_prefix']) && $ro_settings['spec_price_prefix'] && ($ro_comb['price_prefix'] == '+' || $ro_comb['price_prefix'] == '-') ) {
							
							$ro_price_addition = $ro_comb['price'];
							
							if ( $ro_comb['price_prefix'] == '-' ) {
								$ro_price_addition = -$ro_price_addition;
							}
							
							if (!empty($ro_price_addition)) {
								//$ro_price+= $ro_price_addition;
								$ro_price_modificator+= $ro_price_addition;
							}
							
							if ( !empty($lp_settings['ropro_discounts_addition']) ) {
								if ( $ro_comb['price_prefix'] == '+' ) {
									$ro_discount_addition_total+= $this->calc_price_with_ro_get_discount($ro_comb, $quantity);
								} else { // -
									$ro_discount_addition_total-= $this->calc_price_with_ro_get_discount($ro_comb, $quantity);
								}
							}
							
							if ( !empty($lp_settings['ropro_specials_addition']) && $ro_comb['specials'] && $ro_comb['specials'][0] ) {
								$ro_special_row = $ro_comb['specials'][0];
								if ( $ro_comb['price_prefix'] == '+' ) {
									$ro_special_addition_total+= $ro_special_row['price'];
								} else { // -
									$ro_special_addition_total-= $ro_special_row['price'];
								}
							}
						
						} elseif ( !empty($ro_comb['price']) && (float)$ro_comb['price'] ) {
							$ro_price = $ro_comb['price'];
						}
					//}
					
					if (isset($ro_comb['current_customer_group_special_price']) && $ro_comb['current_customer_group_special_price']) {
						$special = $ro_comb['current_customer_group_special_price'];
					}
				}

				if ( !empty($ro_settings['spec_ofs']) ) {
					$stock = $ro_comb['stock'];
				} elseif ( $this->config->get('config_stock_display') ) {
					$stock = (int)$ro_comb['quantity'];
				} else {
					$stock = false;
				}
				$in_stock = $ro_comb['in_stock'];
			}
			$ro_price+= $ro_price_modificator; // apply + and - modifiers at the last step (after = )
		}
		return array('price'=>$ro_price,
								 'special'=>$special,
								 'stock'=>$stock,
								 'in_stock'=>$in_stock,
								 'price_modificator'=>$ro_price_modificator,
								 'discount_addition'=>$ro_discount_addition_total,
								 'special_addition'=>$ro_special_addition_total,
								 );
		
	}
	
	// get price and stock
  public function getJournal2Price($product_id, $price, $special=false) {
		
		if ($this->installed()) {
			
			$ro_settings = $this->config->get('related_options');
			if ( $ro_settings && is_array($ro_settings) ) {
				
				if ( !$this->model_catalog_product ) {
					$this->load->model('catalog/product');
				}
				$product_options = $this->model_catalog_product->getProductOptions($product_id);
				$options = array();
				foreach ($product_options as $option) {
					if (!in_array($option['type'], array('select', 'radio', 'image', 'block', 'color'))) continue;
								
					$option_ids = Journal2Utils::getProperty($this->request->post, 'option.' . $option['product_option_id'], array());
					
					if (is_scalar($option_ids)) {
						$options[$option['product_option_id']] = $option_ids;
					} elseif (is_array($option_ids) && count($option_ids) > 0) {
						$options[$option['product_option_id']] = $option_ids[0];
					}
				}
				
				if (count($options) > 0 ) {
					$ro_combs = $this->get_related_options_sets_by_poids($product_id, $options);
					$ro_price_data = $this->calc_price_with_ro($price, $ro_combs, $special);
					return $ro_price_data;
				}
			}	
		}
		return false;
	}
	
	
	// check is there enough product quantity for related options (for all products in cart)
	public function cart_ckeckout_stock($products) {
		
		if ($this->installed()) {
			if (is_array($products)) {
				foreach ($products as &$product) {
					if ($product['stock']) {
						if (isset($product['option'])&&is_array($product['option'])) {
							$poids = array();
							foreach ($product['option'] as $option) {
								if ($option) {
									$poids[$option['product_option_id']] = $option['product_option_value_id'];
								}
							}
							if (count($poids) > 0) {
								$product['stock'] = $this->cart_stock($product['product_id'], $poids, $product['quantity']);
							}
						}
					}
				}
				unset($product);
			}
		}
		return $products;
		
	}
	
	private function getROCombsWithQuantitiesInCartByProductId($p_product_id) {
		
		$qtys = array();
		
		$products = $this->cart->getProducts();
		foreach ($products as $product) {
			if ($product['product_id'] == $p_product_id) {
				$cart_options = array();
				foreach ($product['option'] as $option) {
					$cart_options[$option['product_option_id']] = $option['product_option_value_id'];
				}
				
				$ro_combs = $this->get_related_options_sets_by_poids($p_product_id, $cart_options, true, true);
				foreach ($ro_combs as $ro_comb) {
					if ( !isset($qtys[$ro_comb['relatedoptions_id']]) ) {
						$qtys[$ro_comb['relatedoptions_id']] = 0;
					}
					$qtys[$ro_comb['relatedoptions_id']]+= $product['quantity'];
				}
			}
		}
		return $qtys;
	}
	
	public function get_ro_free_quantity() {
		
		$json = array('quantity'=>false, 'call'=> (isset($this->request->get['call']) ? $this->request->get['call'] : 0) );
		
		$product_id = isset($this->request->get['product_id']) ? (int)$this->request->get['product_id'] : array();
		$options = isset($this->request->post['option']) ? $this->request->post['option'] : array();
		
		if ( $options && $product_id ) {
		
			$ro_combs_in_cart = $this->getROCombsWithQuantitiesInCartByProductId($product_id);
		
			$qtys = array();
			$ro_combs = $this->get_related_options_sets_by_poids($product_id, $options, true, true);
			foreach ($ro_combs as $ro_comb) {
				$qtys[$ro_comb['relatedoptions_id']] = MAX(0, $ro_comb['quantity']);
			}
			
			foreach ( $qtys as $relatedoptions_id => &$qty ) {
				if ( !empty($ro_combs_in_cart[$relatedoptions_id]) ) {
					$ro_in_cart_quantity = $ro_combs_in_cart[$relatedoptions_id];
					$qty = MAX(0, $qty-$ro_in_cart_quantity);
				}
			}
			unset($qty);
			/*
			foreach ( $ro_combs_in_cart as $relatedoptions_id => $ro_in_cart_quantity ) {
				if ( isset($qtys[$ro_comb['relatedoptions_id']]) ) {
					$qtys[$relatedoptions_id] = MAX(0, $qtys[$relatedoptions_id]-$ro_in_cart_quantity);
				}
			}
			*/
			$quantity = false;
			foreach ($qtys as $qty) {
				if ($quantity === false) {
					$quantity = $qty;
				} else {
					$quantity = MIN($quantity, $qty);
				}
			}
			$json['quantity'] = $quantity;
			
			// check for specific option view (separate quantity inputs/selects for option values )
			// should return quantities allowed to add to cart (available) only for option combs where customer is set greater quantity (to display only warnings)
			if ( !empty($this->request->post['quantity_per_option']) && is_array($this->request->post['quantity_per_option']) ) {
				
				
				
				$quantity_per_options = $this->request->post['quantity_per_option'];
				
				foreach ( $quantity_per_options as $product_option_id => $quantity_per_option ) { // generally, there should be only on product option (product_option_value_id)
					if ( $quantity_per_option ) {
						foreach ( $quantity_per_option as $product_option_value_id => $product_option_value_quantity ) {
							$product_option_value_quantity = (int)$product_option_value_quantity;
							if ( $product_option_value_quantity ) {
								$current_options = $options;
								$current_options[$product_option_id] = $product_option_value_id;
								$qtys = array();
								
								$ro_combs = $this->get_related_options_sets_by_poids($product_id, $current_options, true, true);
								foreach ($ro_combs as $ro_comb) {
									$qtys[$ro_comb['relatedoptions_id']] = MAX(0, $ro_comb['quantity']);
								}
								
								foreach ( $qtys as $relatedoptions_id => &$qty ) {
									if ( !empty($ro_combs_in_cart[$relatedoptions_id]) ) {
										$ro_in_cart_quantity = $ro_combs_in_cart[$relatedoptions_id];
										$qty = MAX(0, $qty-$ro_in_cart_quantity);
									}
								}
								unset($qty);
								if ( $qtys ) {
									$current_quantity = false;
									foreach ($qtys as $qty) {
										if ($current_quantity === false) {
											$current_quantity = $qty;
										} else {
											$current_quantity = MIN($current_quantity, $qty);
										}
									}
									if ( $product_option_value_quantity > $current_quantity ) {
										if ( !isset($json['quantity_per_option_value']) ) {
											$json['quantity_per_option_value'] = array();
										}
										$json['quantity_per_option_value'][$product_option_value_id] = $current_quantity;
									}
								}
							}
						}
					}
				}
			}
			
			
			/*
			$products = $this->cart->getProducts();
			foreach ($products as $product) {
				if ($product['product_id'] == $product_id) {
					$cart_options = array();
					foreach ($product['option'] as $option) {
						$cart_options[$option['product_option_id']] = $option['product_option_value_id'];
					}
					
					$ro_combs = $this->get_related_options_sets_by_poids($product_id, $cart_options, true, true);
					foreach ($ro_combs as $ro_comb) {
						if ( isset($qtys[$ro_comb['relatedoptions_id']]) ) {
							$qtys[$ro_comb['relatedoptions_id']] = MAX(0, $qtys[$ro_comb['relatedoptions_id']]-$product['quantity']);
						}
					}
				}
			}
			*/
			
		}
		
		return $json;
		
	}
	
	
	// check is there's enough quantity for related options
	public function cart_stock($product_id, $options, $quantity) {
		
		$ro_settings = $this->config->get('related_options');
		$ro_combs = $this->get_related_options_sets_by_poids($product_id, $options, true, true);
		//$ro_combs = $this->get_related_options_sets_by_poids($product_id, $options);
		$stock_ok = true;
		if ($ro_combs) {
			foreach ($ro_combs as $ro_comb) {
				$stock_ok = $stock_ok && ($quantity <= $ro_comb['quantity'] || !empty($ro_settings['allow_zero_select']));
			}
		}
		
		return $stock_ok;
		
	}
	
	
	// returns information for all relevant related options combinations
	// discounts and specials for current customer
	// if there's not price, discount or special for combination, this data takes from product 
	// all options values from related options combination should be equal to options given as parameter of function
	// (it's possible to have more options in parameter than in a related options combination)
	public function get_related_options_sets_by_poids($product_id, $param_options, $use_cache=false, $p_allow_zero_quantity=-1) {
		
		if (!$param_options || !is_array($param_options) || count($param_options)==0 ) {
			return array();
			//return FALSE;
		}
		
		$options = array();
		foreach ($param_options as $po_id => $pov_id) {
			if ( !is_array($pov_id) && !is_object($pov_id) ) {
				$options[(int)$po_id] = (int)$pov_id;
			}
		}
		
		$cache_key = $product_id.'_'.serialize($options).'_'.$p_allow_zero_quantity;
		//$cache_key = md5( $product_id.'_'.serialize($options).'_'.$p_allow_zero_quantity );
		
		if ( $use_cache && isset($this->cache_sets_by_poids[$cache_key]) ) {
			return $this->cache_sets_by_poids[$cache_key];
		}
		
		$matches = array();
		$ro_data = $this->get_ro_data($product_id, false, $p_allow_zero_quantity);
		
		foreach ($ro_data as $ro_dt) {
			
			$options_values_to_check = array();
			
			if ( $ro_dt['options_ids'] ) {
				
				foreach ($ro_dt['options_ids'] as $po_id) {
					if ( isset($options[$po_id]) && $options[$po_id] ) {
						$options_values_to_check[$po_id] = (int)$options[$po_id];
					}
				}
			}
			
			foreach ($ro_dt['ro'] as $ro_comb) {
				
				if ( !array_diff_assoc($options_values_to_check, $ro_comb['options']) && count($options_values_to_check) == count($ro_comb['options']) ) {
					$matches[] = $ro_comb;
					break;
				}
			}
		}
		
		$this->cache_sets_by_poids[$cache_key] = $matches;
		
		return $matches;
	}
	

  public function get_option_types() {
		return "'select', 'radio', 'image', 'block', 'color'";
	}
  
  public function get_compatible_options() {
		
		if (!$this->installed()) {
			return array();
		}
		
		$lang_id = $this->getLanguageId($this->config->get('config_language'));
		
		$query = $this->db->query("SELECT O.option_id, OD.name FROM `".DB_PREFIX."option` O, `".DB_PREFIX."option_description` OD
															WHERE O.option_id = OD.option_id
																AND OD.language_id = ".$lang_id."
																AND O.type IN (".$this->get_option_types().")
															ORDER BY O.sort_order
															");
		
		$opts = array();
		foreach ($query->rows as $row) {
			$opts[$row['option_id']] = $row['name'];
		}
		
		return $opts;
		
	}
  
  public function get_compatible_options_values() {
		
		if (!$this->installed()) {
			return array();
		}
		
		$lang_id = $this->getLanguageId($this->config->get('config_language'));
		
		$optsv = array();
		$compatible_options = $this->get_compatible_options();
		$str_opt = "";
		foreach ($compatible_options as $option_id => $option_name) {
			$optsv[$option_id] = array('name'=>$option_name, 'values'=>array());
			$str_opt .= ",".$option_id;
		}
		if ($str_opt!="") {
			$str_opt = substr($str_opt, 1);
			$query = $this->db->query("	SELECT OV.option_id, OVD.name, OVD.option_value_id
																	FROM `".DB_PREFIX."option_value` OV, `".DB_PREFIX."option_value_description` OVD 
																	WHERE OV.option_id IN (".$str_opt.")
																		AND OVD.language_id = ".$lang_id."
																		AND OV.option_value_id = OVD.option_value_id
																	ORDER BY OV.sort_order
																	");
			foreach ($query->rows as $row) {
				$optsv[$row['option_id']]['values'][$row['option_value_id']] = $row['name'];
			}
		}
		
		return $optsv;
		
	}
  
  public function get_options_for_variant($relatedoptions_variant_id) {
		
		$options = array();
		if ($relatedoptions_variant_id == 0) {
			$copts = $this->get_compatible_options();
			$options = array_keys($copts);
		} else {
			$options = array();
			$query = $this->db->query("	SELECT VO.option_id
																	FROM `".DB_PREFIX."relatedoptions_variant_option` VO
																	WHERE relatedoptions_variant_id = ".$relatedoptions_variant_id."
																	");
			foreach ($query->rows as $row) {
				$options[] = $row['option_id'];
			}
		}
		
		return $options;
		
	}
  
  
  public function getLanguageId($lang) {
		$query = $this->db->query('SELECT `language_id` FROM `' . DB_PREFIX . 'language` WHERE `code` = "'.$lang.'"');
		return $query->row['language_id'];
	}
  
  // option_id
  public function get_product_variant_options($product_id) {
		
		$options = array();
		
		$ro_variant_id = 0;
		$query = $this->db->query("	SELECT VP.relatedoptions_variant_id
																FROM 	" . DB_PREFIX . "relatedoptions_variant_product VP
																WHERE VP.product_id = ".(int)$product_id."
																");
		if ($query->num_rows) {
			$ro_variant_id = $query->row['relatedoptions_variant_id'];
		}
		
		$options = $this->get_options_for_variant($ro_variant_id);
		return $options;
		
	}
  
	private function check_order_product_table() {
		
		if (!$this->installed()) return;
		
		$ro_settings = $this->config->get('related_options');
		
		if (isset($ro_settings['spec_sku']) && $ro_settings['spec_sku']) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order_product` WHERE field='sku' ");
			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `".DB_PREFIX."order_product` ADD COLUMN `sku` varchar(64) NOT NULL " );
			}
		}
		
		if (isset($ro_settings['spec_upc']) && $ro_settings['spec_upc']) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order_product` WHERE field='upc' ");
			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `".DB_PREFIX."order_product` ADD COLUMN `upc` varchar(12) NOT NULL " );
			}
		}
		
		if (isset($ro_settings['spec_ean']) && $ro_settings['spec_ean']) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order_product` WHERE field='ean' ");
			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `".DB_PREFIX."order_product` ADD COLUMN `ean` varchar(14) NOT NULL " );
			}
		}
		
		if (isset($ro_settings['spec_location']) && $ro_settings['spec_location']) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order_product` WHERE field='location' ");
			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `".DB_PREFIX."order_product` ADD COLUMN `location` varchar(128) NOT NULL " );
			}
		}
		
		if (isset($ro_settings['spec_weight']) && $ro_settings['spec_weight']) {
			$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order_product` WHERE field='weight' ");
			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `".DB_PREFIX."order_product` ADD COLUMN `weight` decimal(15,8) NOT NULL " );
			}
		}
		
		
	}
	
	public function productHasRO($product_id) {
		
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."relatedoptions_variant_product WHERE product_id = ".(int)$product_id." AND relatedoptions_use = 1 ");
		return $query->num_rows>0;
	}
	
	public function get_ro_data($product_id, $for_front_end=false, $p_allow_zero_quantity=-1) {
		
		if (!$this->installed()) {
			return array();
		}
		
		$customer_group_id = (int)$this->config->get('config_customer_group_id');
		$lang_id = $this->getLanguageId($this->config->get('config_language'));
		
		$ro_settings = $this->config->get('related_options');
		
		$allow_zero_quantity = $p_allow_zero_quantity!==-1 ? $p_allow_zero_quantity : !empty($ro_settings['allow_zero_select']);
		
		$ro_data = array();
		
		$query = $this->db->query("	SELECT ROVP.*
																FROM 	`" . DB_PREFIX . "relatedoptions_variant_product` ROVP
																		LEFT JOIN	`" . DB_PREFIX . "relatedoptions_variant` ROV ON (ROVP.relatedoptions_variant_id = ROV.relatedoptions_variant_id)
																WHERE ROVP.product_id = " . (int)$product_id . "
																	AND ROVP.relatedoptions_use = 1
																ORDER BY ROV.sort_order, ROV.relatedoptions_variant_name, ROVP.relatedoptions_variant_id, ROVP.relatedoptions_variant_product_id
																");
		
		$rovp_rows = $query->rows;
		
		foreach ($rovp_rows as $rovp_row) {
			
			$ro_data[] = array(	'rovp_id' => $rovp_row['relatedoptions_variant_product_id']
												,	'use' 		=> $rovp_row['relatedoptions_use']
												,	'rov_id' 	=> $rovp_row['relatedoptions_variant_id']
												, 'ro'			=> array()
												, 'options_ids' => array()
												);
			$cnt = count($ro_data)-1;
			$rovp_id = (int)$rovp_row['relatedoptions_variant_product_id'];
			
			
			$query = $this->db->query("	SELECT PS.name product_stock_status
																	FROM `" . DB_PREFIX . "product` P
																			LEFT JOIN ".DB_PREFIX."stock_status PS ON (PS.stock_status_id = P.stock_status_id && PS.language_id = ".(int)$lang_id." )
																	WHERE P.product_id = ".(int)$product_id."
																	");
			$product_row = $query->row;
			
			$query = $this->db->query("	SELECT RO.*, SS.name stock_status
																	FROM ( 	SELECT * FROM `" . DB_PREFIX . "relatedoptions` RO
																					WHERE RO.relatedoptions_variant_product_id = " . (int)$rovp_id . "
																					".($allow_zero_quantity ? "" : " AND RO.quantity > 0 ")."
																				) RO
																			LEFT JOIN ".DB_PREFIX."stock_status SS ON (SS.stock_status_id = RO.stock_status_id && SS.language_id = ".(int)$lang_id." )
																	ORDER BY RO.relatedoptions_id
																	");
			
			foreach ($query->rows as $row) {
				
				$row['product_stock_status'] = !empty($product_row['product_stock_status']) ? $product_row['product_stock_status'] : '';
				
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']] = $row;
				
				if ($for_front_end) {
					//unset($ro_data[$cnt]['ro'][$row['relatedoptions_id']]['price']);
					unset($ro_data[$cnt]['ro'][$row['relatedoptions_id']]['quantity']);
				}
				
				$stock = '';
				$in_stock = '';
				if (isset($ro_settings['spec_ofs'])&& $ro_settings['spec_ofs']) {
					$in_stock = true;
					if ($row['quantity'] <= 0) {
						$stock = ($row['stock_status']) ? $row['stock_status'] : $row['product_stock_status'] ;
						$in_stock = false;
					} elseif ($this->config->get('config_stock_display')) {
						$stock = $row['quantity'];
					} else {
						$stock = $this->language->get('text_instock');
					}
				}
				
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['in_stock'] = $in_stock;
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['stock'] = $stock;
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['options'] = array();
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['discounts'] = array();
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['specials'] = array();
				
			}
			
			
			$query = $this->db->query("	SELECT ROO.*, POV.product_option_id, POV.product_option_value_id, POV.option_id, POV.option_value_id
																	FROM 	`" . DB_PREFIX . "relatedoptions_option` ROO
																			,	`" . DB_PREFIX . "relatedoptions` RO
																			, `" . DB_PREFIX . "product_option_value` POV
																	WHERE ROO.product_id = " . (int)$product_id . "
																		AND RO.relatedoptions_id = ROO.relatedoptions_id
																		AND RO.relatedoptions_variant_product_id = ".(int)$rovp_id."
																		AND ROO.option_id = POV.option_id
																		AND POV.product_id = " . (int)$product_id . "
																		AND ROO.option_value_id = POV.option_value_id
																		".($allow_zero_quantity ? "" : " AND RO.quantity > 0 ")."
																	
																	");
			
			foreach ($query->rows as $row) {
				$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['options'][$row['product_option_id']] = $row['product_option_value_id'];
				if (!$for_front_end) {
					$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['options_original'][$row['option_id']] = $row['option_value_id'];
				}
				if ( !in_array($row['product_option_id'], $ro_data[$cnt]['options_ids']) ) {
					$ro_data[$cnt]['options_ids'][] = $row['product_option_id'];
				}
			}
			
			if (!$for_front_end) {
				$query = $this->db->query("	SELECT RD.*
																		FROM 	`" . DB_PREFIX . "relatedoptions` RO
																				, `" . DB_PREFIX . "relatedoptions_discount` RD
																		WHERE RO.product_id = " . (int)$product_id . "
																			AND RO.relatedoptions_id = RD.relatedoptions_id
																			AND RO.relatedoptions_variant_product_id = ".(int)$rovp_id."
																			AND RD.customer_group_id = ".(int)$customer_group_id."
																			".($allow_zero_quantity ? "" : " AND RO.quantity > 0 ")."
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
																			AND RS.customer_group_id = ".(int)$customer_group_id."
																			".($allow_zero_quantity ? "" : " AND RO.quantity > 0 ")."
																		ORDER BY RS.relatedoptions_id, RS.customer_group_id
																		");
				
				foreach ($query->rows as $row) {
					$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['specials'][] = $row;
					$ro_data[$cnt]['ro'][$row['relatedoptions_id']]['current_customer_group_special_price'] = $row['price'];
				}
			}
		}
		
		return $ro_data;
		
	}
	

  public function installed() {
		
		if ( is_null($this->module_installed_status) ) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'module' AND `code` = 'related_options'");
			$this->module_installed_status = $query->num_rows;
		}
		return $this->module_installed_status;
	}
	
	private function getLivePriceSettings() {
		if ($this->liveprice_settings === false) {
			$this->liveprice_settings = array();
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'module' AND `code` = 'liveprice'");
			if ($query->num_rows) {
				$this->liveprice_settings = $this->config->get('liveprice_settings');
			}
		}
		return $this->liveprice_settings;
	}


}

