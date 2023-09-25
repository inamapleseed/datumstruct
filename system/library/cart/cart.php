<?php
namespace Cart;
class Cart {
        // << Related Options / Связанные опции 
			
        private $relatedoptions_model = false;
        private $ro_global_registry = false;

        // >> Related Options / Связанные опции
	private $data = array();

	public function __construct($registry) {
                // << Related Options
      
                $this->ro_global_registry = $registry;
			
                // >> Related Options
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');

		// Remove all the expired carts with no customer ID
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE (api_id > '0' OR customer_id = '0') AND date_added < DATE_SUB(NOW(), INTERVAL 1 HOUR)");

		if ($this->customer->getId()) {
			// We want to change the session ID on all the old items in the customers cart
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE api_id = '0' AND customer_id = '" . (int)$this->customer->getId() . "'");

			// Once the customer is logged in we want to update the customers cart
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '0' AND customer_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

			foreach ($cart_query->rows as $cart) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart['cart_id'] . "'");

				// The advantage of using $this->add is that it will check if the products already exist and increaser the quantity if necessary.
				$this->add($cart['product_id'], $cart['quantity'], json_decode($cart['option']), $cart['recurring_id']);
			}
		}
	}
        
        // << Related Options / Связанные опции 
			
        private function ro_calc_price($product_price, $ro_combs) {

                $ro_model = $this->ro_get_model();
                $ro_price_data = $ro_model->calc_price_with_ro($product_price, $ro_combs);

                return $ro_price_data;
                //return $ro_price_data['price'];
        }

        private function ro_get_model() {
                global $loader, $registry;

                if ( !$this->relatedoptions_model ) {

                        $current_loader = $loader;
                        if ( $this->ro_global_registry ) {
                                $current_loader = $this->ro_global_registry->get('load');
                                $current_registry = $this->ro_global_registry;
                        } else {
                                if (!$loader || !is_object($loader) || !method_exists($loader, 'model')) {
                                        $current_loader = new Loader($registry);
                                        $current_registry = $registry;
                                }
                        }

                        if ( !$current_registry->get('model_module_related_options') ) {
                                $current_loader->model('module/related_options');
                        }
                        $this->relatedoptions_model = $current_registry->get('model_module_related_options');
                }
                return $this->relatedoptions_model;

        }

        private function ro_get_products_data(&$ro_quantities) {

                $ro_model = $this->ro_get_model();

                $ro_for_products = array();
                $ro_quantities = array(); // total quantities by related options

                if (	$ro_model->installed() ) {
                        if (!$this->data) {

                                if ( VERSION >= '2.1.0.0' ) {
                                        $cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

                                        foreach ($cart_query->rows as $cart) {
                                                $key = $cart['cart_id'];
                                                $product_id = $cart['product_id'];
                                                $quantity = $cart['quantity'];

                                                if ($quantity > 0) {
                                                        $options = (array)json_decode($cart['option']);


                                                        $ro_for_products[$key] = $ro_model->get_related_options_sets_by_poids($product_id, $options, true, true);
                                                        //$ro_for_products[$key] = $ro_model->get_related_options_sets_by_poids($product_id, $options);

                                                        if ($ro_for_products[$key]) {
                                                                foreach ($ro_for_products[$key] as $ro_comb) {
                                                                        if (!isset($ro_quantities[$ro_comb['relatedoptions_id']])) {
                                                                                $ro_quantities[$ro_comb['relatedoptions_id']] = 0;
                                                                        }
                                                                        $ro_quantities[$ro_comb['relatedoptions_id']]+= $quantity;
                                                                }
                                                        }
                                                }
                                        }
                                } else {

                                        foreach ($this->session->data['cart'] as $key => $quantity) {
                                                $product = unserialize(base64_decode($key));

                                                $product_id = $product['product_id'];

                                                // Options
                                                if (!empty($product['option'])) {
                                                        $options = $product['option'];
                                                } else {
                                                        $options = array();
                                                }

                                                $ro_for_products[$key] = $ro_model->get_related_options_sets_by_poids($product_id, $options, true, true);
                                                //$ro_for_products[$key] = $ro_model->get_related_options_sets_by_poids($product_id, $options);

                                                if ($ro_for_products[$key]) {
                                                        foreach ($ro_for_products[$key] as $ro_comb) {
                                                                if (!isset($ro_quantities[$ro_comb['relatedoptions_id']])) {
                                                                        $ro_quantities[$ro_comb['relatedoptions_id']] = 0;
                                                                }
                                                                $ro_quantities[$ro_comb['relatedoptions_id']]+= $quantity;
                                                        }
                                                }
                                        }
                                }
                        }
                }

                return $ro_for_products;

        }

        // >> Related Options / Связанные опции
	public function getProducts() {
                // << Related Options / Связанные опции 
				
                $ro_quantities = array();
                $ro_for_products = $this->ro_get_products_data($ro_quantities);
                if ($ro_for_products) {
                        $ro_settings = $this->config->get('related_options');
                } else {
                        $ro_settings = false;
                }

                // >> Related Options / Связанные опции
		$product_data = array();

		$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		foreach ($cart_query->rows as $cart) {
			$stock = true;

			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store p2s LEFT JOIN " . DB_PREFIX . "product p ON (p2s.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2s.product_id = '" . (int)$cart['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");

			if ($product_query->num_rows && ($cart['quantity'] > 0)) {
                            // << Related Options / Связанные опции 
                            $ro_price = false;
                            if ( VERSION >= '2.1.0.0' ) {
                                    $key = $cart['cart_id'];
                                    $ro_cart_quantity = $cart['quantity'];
                            } else {
                                    $ro_cart_quantity = $quantity;
                            }

                            $ro_for_product = false;
                            if ($ro_for_products && isset($ro_for_products[$key]) ) {
                                    $ro_for_product = $ro_for_products[$key];
                            } elseif ( !$key && !empty($cart) ) {
                                    $ro_temp_options = json_decode($cart['option']);
                                    $ro_for_product = $this->relatedoptions_model->get_related_options_sets_by_poids($cart['product_id'], $ro_temp_options, true);

                            }
                            if ( $ro_for_product ) {
                                    $ro_model = '';
                                    $ro_weight = false;

                                    if ( isset($ro_settings['spec_price']) && $ro_settings['spec_price'] ) {
                                            $ro_price_data = $this->ro_calc_price($product_query->row['price'], $ro_for_product);
                                            //$ro_price_data = $this->ro_calc_price($product_query->row['price'], $ro_for_products[$key]);
                                    }

                                    $last_model_is_from_product = false;
                                    foreach ($ro_for_product as $ro_comb) {
                                    //foreach ($ro_for_products[$key] as $ro_comb) {
                                            if ($ro_comb['quantity'] < $ro_cart_quantity && ( empty($ro_settings['allow_zero_select']) || !$ro_settings['allow_zero_select']) ) {
                                                    $stock = false;
                                            }

                                            if ( isset($ro_settings['spec_model']) && $ro_settings['spec_model'] ) {
                                                    if ($ro_settings['spec_model'] == 1) {
                                                            $ro_model = $ro_comb['model'];
                                                    } elseif ($ro_settings['spec_model'] == 2) {
                                                            if ( $ro_model && isset($ro_settings['spec_model_delimiter_ro']) ) {
                                                                    $ro_model.= $ro_settings['spec_model_delimiter_ro'];
                                                            }
                                                            $ro_model.= $ro_comb['model'];
                                                    } elseif ($ro_settings['spec_model'] == 3) {
                                                            if ($ro_model == '') {
                                                                    $ro_model = $product_query->row['model'];
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

                                            // Related Options weight
                                            if (isset($ro_settings['spec_weight']) && $ro_settings['spec_weight'] ) {

                                                    if ( $ro_comb['weight'] != 0 ) {
                                                            if ($ro_comb['weight_prefix'] == '+') {
                                                                    if ($ro_weight === false) $ro_weight = $product_query->row['weight'];
                                                                    $ro_weight+= $ro_comb['weight'];
                                                            } elseif ($ro_comb['weight_prefix'] == '-') {
                                                                    if ($ro_weight === false) $ro_weight = -$product_query->row['weight'];
                                                                    $ro_weight-= $ro_comb['weight'];
                                                            } else { // =
                                                                    $ro_weight = $ro_comb['weight'];
                                                            }
                                                    }
                                            }

                                    }

                                    if ($ro_model) {
                                            $product_query->row['model'] = $ro_model;
                                    }

                                    if (isset($ro_settings['spec_weight']) && $ro_settings['spec_weight'] && $ro_weight !== false ) {
                                            $product_query->row['weight'] = $ro_weight;
                                    }

                                }

                                // >> Related Options / Связанные опции 

				$sku = $product_query->row['sku'];

				$option_price = 0;
				$option_points = 0;
				$option_weight = 0;

				$option_data = array();

				foreach (json_decode($cart['option']) as $product_option_id => $value) {
					$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$cart['product_id'] . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($option_query->num_rows) {
						if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio') {
							$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix, pov.sku FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

							if ($option_value_query->num_rows) {

								if( trim($option_value_query->row['sku']) != '' ){
									$sku = trim($option_value_query->row['sku']);
								}

								if ($option_value_query->row['price_prefix'] == '+') {
									$option_price += $option_value_query->row['price'];
								} elseif ($option_value_query->row['price_prefix'] == '-') {
									$option_price -= $option_value_query->row['price'];
								}

								if ($option_value_query->row['points_prefix'] == '+') {
									$option_points += $option_value_query->row['points'];
								} elseif ($option_value_query->row['points_prefix'] == '-') {
									$option_points -= $option_value_query->row['points'];
								}

								if ($option_value_query->row['weight_prefix'] == '+') {
									$option_weight += $option_value_query->row['weight'];
								} elseif ($option_value_query->row['weight_prefix'] == '-') {
									$option_weight -= $option_value_query->row['weight'];
								}

								if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
									$stock = false;
								}

								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => $value,
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => $option_value_query->row['option_value_id'],
									'name'                    => $option_query->row['name'],
									'value'                   => $option_value_query->row['name'],
									'type'                    => $option_query->row['type'],
									'quantity'                => $option_value_query->row['quantity'],
									'subtract'                => $option_value_query->row['subtract'],
									'price'                   => $option_value_query->row['price'],
									'price_prefix'            => $option_value_query->row['price_prefix'],
									'points'                  => $option_value_query->row['points'],
									'points_prefix'           => $option_value_query->row['points_prefix'],
									'weight'                  => $option_value_query->row['weight'],
									'weight_prefix'           => $option_value_query->row['weight_prefix']
								);
							}
						} elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
							foreach ($value as $product_option_value_id) {
								$option_value_query = $this->db->query("SELECT pov.option_value_id, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix, ovd.name, pov.sku FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {

									if( trim($option_value_query->row['sku']) != '' ){
										$sku = trim($option_value_query->row['sku']);
									}

									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
										$stock = false;
									}

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $product_option_value_id,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'value'                   => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);
								}
							}
						} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
							$option_data[] = array(
								'product_option_id'       => $product_option_id,
								'product_option_value_id' => '',
								'option_id'               => $option_query->row['option_id'],
								'option_value_id'         => '',
								'name'                    => $option_query->row['name'],
								'value'                   => $value,
								'type'                    => $option_query->row['type'],
								'quantity'                => '',
								'subtract'                => '',
								'price'                   => '',
								'price_prefix'            => '',
								'points'                  => '',
								'points_prefix'           => '',
								'weight'                  => '',
								'weight_prefix'           => ''
							);
						}
					}
				}

				$price = $product_query->row['price'];
                                // << Related Options / Связанные опции 
			
                                if ($ro_for_product && isset($ro_settings['spec_price']) && $ro_settings['spec_price'] && !empty($ro_price_data) ) {
                                //if ($ro_for_products && $ro_for_products[$key] && isset($ro_settings['spec_price']) && $ro_settings['spec_price'] && !empty($ro_price_data) ) {
                                        $price = $ro_price_data['price'];
                                }

                                // >> Related Options / Связанные опции 

				// Product Discounts
				if ($this->config->get('discounts_status')) {
					$override = $this->config->get('discounts_override_discount_price');
					if ($override == 'override') {
						$discount_status = true;
					}
				}

				$discount_quantity = 0;

				foreach ($cart_query->rows as $cart_2) {
					if ($cart_2['product_id'] == $cart['product_id']) {
						$discount_quantity += $cart_2['quantity'];
					}
				}

				$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

				if ($product_discount_query->num_rows) {
					if (empty($discount_status)) {
						$price = $product_discount_query->row['price'];
					}
				}
                                // << Related Options / Связанные опции 
                                // Related Options discounts
                                if ($ro_for_product
                                //if ($ro_for_products && $ro_for_products[$key]
                                && isset($ro_settings['spec_price']) && $ro_settings['spec_price']
                                && isset($ro_settings['spec_price_discount']) && $ro_settings['spec_price_discount'] ) {

                                        // get first option combination with discount
                                        foreach ($ro_for_products[$key] as $ro_comb) {
                                        //foreach ($ro_for_product as $ro_comb) {

                                                if ($ro_comb['discounts']) {
                                                        $ro_discount_quantity = $ro_quantities[$ro_comb['relatedoptions_id']];
                                                        $product_ro_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "relatedoptions_discount
                                                                                                                                                                                                                                                        WHERE relatedoptions_id = '" . (int)$ro_comb['relatedoptions_id'] . "'
                                                                                                                                                                                                                                                        AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
                                                                                                                                                                                                                                                        AND quantity <= '" . (int)$ro_discount_quantity . "'
                                                                                                                                                                                                                                                        ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
                                                        if ($product_ro_discount_query->num_rows) {
                                                                $product_discount_query = $product_ro_discount_query;
                                                                break;
                                                        }
                                                }
                                        }
                                }

                                // >> Related Options / Связанные опции 
                                if ($product_discount_query->num_rows) {
                                        $price = $product_discount_query->row['price'];

                                // << Related Options / Связанные опции 

                                        if ( !empty($ro_price_data['price_modificator']) ) {
                                                $price = $price + $ro_price_data['price_modificator'];
                                        }

                                // >> Related Options / Связанные опции
                                }
                                

				// Product Specials
				if ($this->config->get('discounts_status')) {
					$override = $this->config->get('discounts_override_special_price');
					if ($override == 'override') {
						$special_status = true;
					}
				}

				$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

				if ($product_special_query->num_rows) {
					if (empty($special_status)) {
						$price = $product_special_query->row['price'];
					}
				}
                                
                                // << Related Options / Связанные опции 
                                // related options specials

                                if ($ro_for_product
                                //if ($ro_for_products && $ro_for_products[$key]
                                && isset($ro_settings['spec_price']) && $ro_settings['spec_price']
                                && isset($ro_settings['spec_price_special']) && $ro_settings['spec_price_special'] ) {

                                        // get first option combination with special
                                        foreach ($ro_for_product as $ro_comb) {
                                        //foreach ($ro_for_products[$key] as $ro_comb) {

                                                if ($ro_comb['specials']) {
                                                        $product_ro_special_query = $this->db->query("SELECT price FROM ".DB_PREFIX."relatedoptions_special 
                                                                                                                                                                                                                                                WHERE relatedoptions_id = '" . (int)$ro_comb['relatedoptions_id'] . "'
                                                                                                                                                                                                                                                        AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
                                                                                                                                                                                                                                                ORDER BY priority ASC, price ASC LIMIT 1");
                                                        if ($product_ro_special_query->num_rows) {
                                                                $product_special_query = $product_ro_special_query;
                                                                break;
                                                        }
                                                }
                                        }
                                }
                                // >> Related Options / Связанные опции  
                                if ($product_special_query->num_rows) {
                                                $price = $product_special_query->row['price'];

                                // << Related Options / Связанные опции 

                                        if ( !empty($ro_price_data['price_modificator']) ) {
                                                $price = $price + $ro_price_data['price_modificator'];
                                        }

                                // >> Related Options / Связанные опции

                                }

				// Reward Points
				$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($product_reward_query->num_rows) {
					$reward = $product_reward_query->row['points'];
				} else {
					$reward = 0;
				}

				// Downloads
				$download_data = array();

				$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$cart['product_id'] . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

				foreach ($download_query->rows as $download) {
					$download_data[] = array(
						'download_id' => $download['download_id'],
						'name'        => $download['name'],
						'filename'    => $download['filename'],
						'mask'        => $download['mask']
					);
				}

				// Stock
				if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $cart['quantity'])) {
					$stock = false;
				}

				$recurring_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r LEFT JOIN " . DB_PREFIX . "product_recurring pr ON (r.recurring_id = pr.recurring_id) LEFT JOIN " . DB_PREFIX . "recurring_description rd ON (r.recurring_id = rd.recurring_id) WHERE r.recurring_id = '" . (int)$cart['recurring_id'] . "' AND pr.product_id = '" . (int)$cart['product_id'] . "' AND rd.language_id = " . (int)$this->config->get('config_language_id') . " AND r.status = 1 AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($recurring_query->num_rows) {
					$recurring = array(
						'recurring_id'    => $cart['recurring_id'],
						'name'            => $recurring_query->row['name'],
						'frequency'       => $recurring_query->row['frequency'],
						'price'           => $recurring_query->row['price'],
						'cycle'           => $recurring_query->row['cycle'],
						'duration'        => $recurring_query->row['duration'],
						'trial'           => $recurring_query->row['trial_status'],
						'trial_frequency' => $recurring_query->row['trial_frequency'],
						'trial_price'     => $recurring_query->row['trial_price'],
						'trial_cycle'     => $recurring_query->row['trial_cycle'],
						'trial_duration'  => $recurring_query->row['trial_duration']
					);
				} else {
					$recurring = false;
				}
						 // << Related Options / Связанные опции 
			
                                $ro_product_fields = array('sku'=>'','upc'=>'','ean'=>'','location'=>'');

                                if ($ro_for_product) {
                                //if ($ro_for_products && $ro_for_products[$key]) {

                                        foreach ($ro_for_product as $ro_comb) {
                                        //foreach ($ro_for_products[$key] as $ro_comb) {

                                                if (isset($ro_settings['spec_sku']) && $ro_settings['spec_sku'] && $ro_comb['sku']) {
                                                        $ro_product_fields['sku'] = $ro_comb['sku'];
                                                }
                                                if (isset($ro_settings['spec_upc']) && $ro_settings['spec_upc'] && $ro_comb['upc']) {
                                                        $ro_product_fields['upc'] = $ro_comb['upc'];
                                                }
                                                if (isset($ro_settings['spec_ean']) && $ro_settings['spec_ean'] && $ro_comb['ean']) {
                                                        $ro_product_fields['ean'] = $ro_comb['ean'];
                                                }
                                                if (isset($ro_settings['spec_location']) && $ro_settings['spec_location'] && $ro_comb['location']) {
                                                        $ro_product_fields['location'] = $ro_comb['location'];
                                                }
                                        }
                                }

                                // >> Related Options / Связанные опции 
				$product_data[] = array(
                                        // << Related Options / Связанные опции 
			
                                        'sku'         => $ro_product_fields['sku'],
                                        'upc'         => $ro_product_fields['upc'],
                                        'ean'         => $ro_product_fields['ean'],
                                        'location'    => $ro_product_fields['location'],

                                        // >> Related Options / Связанные опции 
					'cart_id'         => $cart['cart_id'],
					'product_id'      => $product_query->row['product_id'],
					'name'            => $product_query->row['name'],
					'model'           => $product_query->row['model'],
					'sku'			  => $sku,
					'shipping'        => $product_query->row['shipping'],
					'image'           => $product_query->row['image'],
					'option'          => $option_data,
					'download'        => $download_data,
					'quantity'        => $cart['quantity'],
					'minimum'         => $product_query->row['minimum'],
					'subtract'        => $product_query->row['subtract'],
					'stock'           => $stock,
					'price'           => ($price + $option_price),
					'total'           => ($price + $option_price) * $cart['quantity'],
					'reward'          => $reward * $cart['quantity'],
					'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $cart['quantity'] : 0),
					'tax_class_id'    => $product_query->row['tax_class_id'],
					'weight'          => ($product_query->row['weight'] + $option_weight) * $cart['quantity'],
					'weight_class_id' => $product_query->row['weight_class_id'],
					'length'          => $product_query->row['length'],
					'width'           => $product_query->row['width'],
					'height'          => $product_query->row['height'],
					'length_class_id' => $product_query->row['length_class_id'],
					'recurring'       => $recurring,
					'manufacturer_id'      => $product_query->row['manufacturer_id'],
				);
			} else {
				$this->remove($cart['cart_id']);
			}
		}

		return $product_data;
	}

	public function add($product_id, $quantity = 1, $option = array(), $recurring_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");

		if (!$query->row['total']) {
			$this->db->query("INSERT " . DB_PREFIX . "cart SET api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "', customer_id = '" . (int)$this->customer->getId() . "', session_id = '" . $this->db->escape($this->session->getId()) . "', product_id = '" . (int)$product_id . "', recurring_id = '" . (int)$recurring_id . "', `option` = '" . $this->db->escape(json_encode($option)) . "', quantity = '" . (int)$quantity . "', date_added = NOW()");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = (quantity + " . (int)$quantity . ") WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");
		}
	}

	public function update($cart_id, $quantity) {

		$query = $this->db->query('SELECT count(*) as total FROM `' . DB_PREFIX . 'product` p LEFT JOIN `' . DB_PREFIX . 'cart` c ON (p.product_id = c.product_id) WHERE c.cart_id="' . (int)$cart_id . '" AND p.minimum <= "' . (int)$quantity . '"');

		if($query->row['total']){
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = '" . (int)$quantity . "' WHERE cart_id = '" . (int)$cart_id . "' AND api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
		}
		
	}

	public function remove($cart_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart_id . "' AND api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function clear() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function getRecurringProducts() {
		$product_data = array();

		foreach ($this->getProducts() as $value) {
			if ($value['recurring']) {
				$product_data[] = $value;
			}
		}

		return $product_data;
	}

	public function getWeight() {
		$weight = 0;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}

	public function getSubTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $product['total'];
		}

		return $total;
	}

	public function getTaxes() {
		$tax_data = array();

		foreach ($this->getProducts() as $product) {
			if ($product['tax_class_id']) {
				$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	public function getTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
		}

		return $total;
	}

	public function countProducts() {
		$product_total = 0;

		$products = $this->getProducts();

		foreach ($products as $product) {
			$product_total += $product['quantity'];
		}

		return $product_total;
	}

	public function hasProducts() {
		return count($this->getProducts());
	}

	public function hasRecurringProducts() {
		return count($this->getRecurringProducts());
	}

	public function hasStock() {
		foreach ($this->getProducts() as $product) {
			if (!$product['stock']) {
				return false;
			}
		}

		return true;
	}

	public function hasShipping() {
		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				return true;
			}
		}

		return false;
	}

	public function hasDownload() {
		foreach ($this->getProducts() as $product) {
			if ($product['download']) {
				return true;
			}
		}

		return false;
	}
}
