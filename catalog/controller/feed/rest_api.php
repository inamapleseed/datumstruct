<?php
/**
 * rest_api.php
 *
 * Custom rest services
 *
 * @author     Opencart-api.com
 * @copyright  2017
 * @license    License.txt
 * @version    2.0
 * @link       https://opencart-api.com/product/shopping-cart-rest-api/
 * @documentations https://opencart-api.com/opencart-rest-api-documentations/
 */
require_once(DIR_SYSTEM . 'engine/restcontroller.php');

class ControllerFeedRestApi extends RestController
{

    /*Get Oauth token*/
    public function getToken()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            /*check rest api is enabled*/
            if (!$this->config->get('rest_api_status')) {
                $this->json["error"][] = 'Shopping Cart Rest API is disabled. Enable it!';
            } else {

                $input = file_get_contents('php://input');
                $post = json_decode($input, true);

                $oldToken = isset($post['old_token']) ? $post['old_token'] : null;
                $oldTokenData = null;
                $this->load->model('account/customer');

                if (!empty($oldToken)) {
                    $oldTokenData = $this->model_account_customer->loadOldToken($oldToken);
                }

                $server = $this->getOauthServer();
                $token = $server->handleTokenRequest(OAuth2\Request::createFromGlobals())->getParameters();

                if (!empty($oldTokenData)) {
                    $this->model_account_customer->loadSessionToNew($oldTokenData['data'], $token['access_token']);
                    $this->model_account_customer->deleteOldToken($oldToken);
                }
			
                if (isset($token['access_token'])) {
                    //clear token table
                    $this->clearTokensTable($token['access_token'], session_id());

                    unset($token['scope']);

                    $token['expires_in'] = (int)$token['expires_in'];

                    $this->json['data'] = $token;

                    $oldSession = $this->session->data;

                    $this->customer->logout();
                    $this->session->destroy(session_id());
                    $this->session->start(0);

                    $this->session->data['token_id'] = $token['access_token'];
                    $this->session->data['language'] = $oldSession['language'];
                    $this->session->data['currency'] = $oldSession['currency'];

                } else {

                    if (isset($token['error_description'])) {
                        $this->json["error"][] = $token['error_description'];
                    } else {
                        $this->json["error"][] = "Token problem. Invalid token.";
                    }

                    $this->statusCode = 400;
                }
            }

        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("POST");
        }

        return $this->sendResponse();
    }

    /*check database modification*/
    public function getchecksum()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $this->load->model('catalog/product');

            $checksum = $this->model_catalog_product->getChecksum();

            for ($i = 0; $i < count($checksum); $i++) {
                $this->json["data"][] = array('table' => $checksum[$i]['Table'], 'checksum' => $checksum[$i]['Checksum']);
            }

        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    /*
    * PRODUCT FUNCTIONS
    */
    public function products()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get product details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getProduct($this->request->get['id']);
            } else {
                //get products list
                if (isset($this->request->get['category']) && ctype_digit($this->request->get['category'])) {
                    $category_id = $this->request->get['category'];
                } else {
                    $category_id = 0;
                }

                $this->listProducts($category_id, $this->request);
            }

        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    public function getProduct($id)
    {

        $this->load->model('catalog/product');

        $products = $this->model_catalog_product->getProductsByIds(array($id), $this->customer);
        if (!empty($products)) {
            $this->json["data"] = $this->getProductInfo(reset($products));
        } else {
            $this->json['error'][] = 'Product not found';
            $this->statusCode = 404;
        }
    }


    private function getProductInfo($product, $simpleList = false, $customFields = false)
    {

        $this->load->model('tool/image');
        $this->load->model('catalog/category');

        //product image
        if (isset($product['image']) && !empty($product['image']) && file_exists(DIR_IMAGE . $product['image'])) {
            $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
            $original_image = $this->urlPrefix . 'image/' . $product['image'];
        } else {
            $image = $this->model_tool_image->resize('no_image.png', $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
            $original_image = $this->urlPrefix . 'image/no_image.png';
        }

        //additional images
        $images = array();
        $original_images = array();
        $reviews = array();
        $special = false;
        $special_excluding_tax = false;
        $special_formated = false;
        $discounts = array();
        $options = array();
        $productCategories = array();

        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $hidePrices = false;
        } else {
            $hidePrices = true;
        }

        if (!$simpleList) {
            $additional_images = $this->model_catalog_product->getProductImages($product['product_id']);

            foreach ($additional_images as $additional_image) {
                if (isset($additional_image['image']) && !empty($additional_image['image']) && file_exists(DIR_IMAGE . $additional_image['image'])) {
                    $images[] = $this->model_tool_image->resize($additional_image['image'], $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
                    $original_images[] = $this->urlPrefix . 'image/' . $additional_image['image'];
                } else {
                    $images[] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
                    $original_images[] = $this->urlPrefix . 'image/no_image.png';
                }
            }

            //special
            if ((float)$product['special'] && empty($hidePrices)) {
                $special_excluding_tax = $this->currency->format($product['special'], $this->currency->getRestCurrencyCode(), '', false);
                $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode(), '', false);
                $special_formated = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode());
            }

            //discounts
            $data_discounts = $this->model_catalog_product->getProductDiscounts($product['product_id']);

            foreach ($data_discounts as $discount) {
                $discounts[] = array(
                    'quantity' => $discount['quantity'],
                    'price_excluding_tax' => empty($hidePrices) ? $this->currency->format($discount['price'], $this->currency->getRestCurrencyCode(), '', false) : false,
                    'price' => empty($hidePrices) ? $this->currency->format($this->tax->calculate($discount['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode(), '', false) : false,
                    'price_formated' => empty($hidePrices) ? $this->currency->format($this->tax->calculate($discount['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode()) : false
                );
            }

            //options
            foreach ($this->model_catalog_product->getProductOptions($product['product_id']) as $option) {
                $product_option_value_data = array();

                foreach ($option['product_option_value'] as $option_value) {
                    if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
                        if ((($this->customer->isLogged() && $this->config->get('config_customer_price')) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
                            $price = $this->currency->format($this->tax->calculate($option_value['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode(), '', false);
                            $price_excluding_tax = $this->currency->format($option_value['price'], $this->currency->getRestCurrencyCode(), '', false);
                            $price_formated = $this->currency->format($this->tax->calculate($option_value['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode());
                        } else {
                            $price = false;
                            $price_excluding_tax = false;
                            $price_formated = false;
                        }
                        if (isset($option_value['image']) && file_exists(DIR_IMAGE . $option_value['image'])) {
                            $option_image = $this->model_tool_image->resize($option_value['image'], $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
                        } else {
                            $option_image = $this->model_tool_image->resize('no_image.png', $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
                        }

                        $product_option_value_data[] = array(
                            'image' => $option_image,
                            'price' => $price,
                            'price_excluding_tax' => $price_excluding_tax,
                            'price_formated' => $price_formated,
                            'price_prefix' => $option_value['price_prefix'],
                            'product_option_value_id' => $option_value['product_option_value_id'],
                            'option_value_id' => $option_value['option_value_id'],
                            'name' => $option_value['name'],
                            'quantity' => !empty($option_value['quantity']) ? $option_value['quantity'] : 0
                        );
                    }
                }

                $options[] = array(
                    'product_option_id' => $option['product_option_id'],
                    'option_value' => $product_option_value_data,
                    'option_id' => $option['option_id'],
                    'name' => $option['name'],
                    'type' => $option['type'],
                    'value' => $option['value'],
                    'required' => $option['required']
                );
            }

            $product_category = $this->model_catalog_product->getCategories($product['product_id']);

            foreach ($product_category as $prodcat) {
                $category_info = $this->model_catalog_category->getCategory($prodcat['category_id']);
                if ($category_info) {
                    $productCategories[] = array(
                        'name' => $category_info['name'],
                        'id' => $category_info['category_id']
                    );
                }
            }

            /*reviews*/
            $this->load->model('catalog/review');

            $reviews["review_total"] = $this->model_catalog_review->getTotalReviewsByProductId($product['product_id']);

            $reviewList = $this->model_catalog_review->getReviewsByProductId($product['product_id'], 0, 1000);

            foreach ($reviewList as $review) {
                $reviews['reviews'][] = array(
                    'author' => $review['author'],
                    'text' => nl2br($review['text']),
                    'rating' => (int)$review['rating'],
                    'date_added' => date($this->language->get('date_format_short'), strtotime($review['date_added']))
                );
            }
        }

        $retval = array(
            'id' => $product['product_id'],
            //'seo_h1' => (!empty($product['seo_h1']) ? $product['seo_h1'] : ""),
            'name' => $product['name'],
            'manufacturer' => $product['manufacturer'],
            'sku' => (!empty($product['sku']) ? $product['sku'] : ""),
            'model' => $product['model'],
            'image' => $image,
            'images' => $images,
            'original_image' => $original_image,
            'original_images' => $original_images,
            'price_excluding_tax' => empty($hidePrices) ? $this->currency->format($product['price'], $this->currency->getRestCurrencyCode(), '', false) : false,
            'price' => empty($hidePrices) ? $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode(), '', false) : false,
            'price_formated' => empty($hidePrices) ? $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode()) : false,
            'rating' => (int)$product['rating'],
            'description' => html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'),
            'attribute_groups' => empty($simpleList) ? $this->model_catalog_product->getProductAttributes($product['product_id']) : array(),
            'special' => $special,
            'special_excluding_tax' => $special_excluding_tax,
            'special_formated' => $special_formated,
            'special_start_date' => (!empty($product['special_start_date']) ? $product['special_start_date'] : ""),
            'special_end_date' => (!empty($product['special_end_date']) ? $product['special_end_date'] : ""),
            'discounts' => $discounts,
            'options' => $options,
            'minimum' => isset($product['minimum']) ? $product['minimum'] : 1,
            'meta_title' => $product['meta_title'],
            'meta_description' => $product['meta_description'],
            'meta_keyword' => $product['meta_keyword'],
            'tag' => $product['tag'],
            'upc' => $product['upc'],
            'ean' => $product['ean'],
            'jan' => $product['jan'],
            'isbn' => $product['isbn'],
            'mpn' => $product['mpn'],
            'location' => $product['location'],
            'stock_status' => $product['stock_status'],
            'stock_status_id' => isset($product['stock_status_id']) ? $product['stock_status_id'] : '',
            'manufacturer_id' => $product['manufacturer_id'],
            'tax_class_id' => $product['tax_class_id'],
            'date_available' => $product['date_available'],
            'weight' => $product['weight'],
            'weight_class_id' => $product['weight_class_id'],
            'length' => $product['length'],
            'width' => $product['width'],
            'height' => $product['height'],
            'length_class_id' => $product['length_class_id'],
            'subtract' => $product['subtract'],
            'sort_order' => $product['sort_order'],
            'status' => $product['status'],
            'date_added' => $product['date_added'],
            'date_modified' => $product['date_modified'],
            'viewed' => $product['viewed'],
            'weight_class' => isset($product['weight_class']) ? $product['weight_class'] : '',
            'length_class' => isset($product['length_class']) ? $product['length_class'] : '',
            'shipping' => isset($product['shipping']) ? $product['shipping'] : '',
            'reward' => $product['reward'],
            'points' => $product['points'],
            'category' => $productCategories,
            'quantity' => !empty($product['quantity']) ? $product['quantity'] : 0,
            'reviews' => $reviews
        );

        if (!empty($customFields)) {
            $fields = explode(',', $customFields);
            $modRetval = array();
            if (is_array($fields)) {
                foreach ($fields as $field) {
                    $trimmed = trim($field);
                    if (isset($retval[$trimmed])) {
                        $modRetval[$trimmed] = $retval[$trimmed];
                    }
                }

                return $modRetval;
            }
        }

        return $retval;
    }

    public function listProducts($category_id, $request)
    {

        $this->load->model('catalog/product');

        $parameters = array(
            "limit" => 100,
            "start" => 1,
            "sort"  => "id",
            'filter_category_id' => $category_id
        );

        /*check limit parameter*/
        if (isset($request->get['limit']) && ctype_digit($request->get['limit'])) {
            $parameters["limit"] = $request->get['limit'];
        }

        /*check page parameter*/
        if (isset($request->get['page']) && ctype_digit($request->get['page'])) {
            $parameters["start"] = $request->get['page'];
        }

        /*check search parameter*/
        if (isset($request->get['search']) && !empty($request->get['search'])) {
            $parameters["filter_name"] = $request->get['search'];
            $parameters["filter_tag"] = $request->get['search'];
        }

        /*check sort parameter*/
        if (isset($request->get['sort']) && !empty($request->get['sort'])) {
            $parameters["sort"] = $request->get['sort'];
        }

        /*check order parameter*/
        if (isset($request->get['order']) && !empty($request->get['order'])) {
            $parameters["order"] = $request->get['order'];
        }

        /*check filters parameter*/
        if (isset($request->get['filters']) && !empty($request->get['filters'])) {
            $parameters["filter_filter"] = $request->get['filters'];
        }

        /*check manufacturer parameter*/
        if (isset($request->get['manufacturer']) && !empty($request->get['manufacturer'])) {
            $parameters["filter_manufacturer_id"] = $request->get['manufacturer'];
        }

        /*check category id parameter*/
        if (isset($request->get['category']) && !empty($request->get['category'])) {
            $parameters["filter_category_id"] = $request->get['category'];
        }

        /*check subcategory id parameter*/
        if (isset($request->get['subcategory']) && !empty($request->get['subcategory'])) {
            $parameters["filter_sub_category"] = $request->get['subcategory'];
        }

        /*check tag parameter*/
        if (isset($request->get['tag']) && !empty($request->get['tag'])) {
            $parameters["filter_tag"] = $request->get['tag'];
        }

        /*check description parameter*/
        if (isset($request->get['filter_description']) && !empty($request->get['filter_description'])) {
            $parameters["filter_description"] = $request->get['filter_description'];
        }

        if (isset($this->request->get['filter_date_added_from'])) {
            $date_added_from = date('Y-m-d H:i:s', strtotime($this->request->get['filter_date_added_from']));
            if ($this->validateDate($date_added_from)) {
                $parameters["filter_date_added_from"] = $date_added_from;
            }
        } else {
            $parameters["filter_date_added_from"] = null;
        }

        if (isset($this->request->get['filter_date_added_on'])) {
            $date_added_on = date('Y-m-d', strtotime($this->request->get['filter_date_added_on']));
            if ($this->validateDate($date_added_on, 'Y-m-d')) {
                $parameters["filter_date_added_on"] = $date_added_on;
            }
        } else {
            $parameters["filter_date_added_on"] = null;
        }

        if (isset($this->request->get['filter_date_added_to'])) {
            $date_added_to = date('Y-m-d H:i:s', strtotime($this->request->get['filter_date_added_to']));
            if ($this->validateDate($date_added_to)) {
                $parameters["filter_date_added_to"] = $date_added_to;
            }
        } else {
            $parameters["filter_date_added_to"] = null;
        }

        if (isset($this->request->get['filter_date_modified_on'])) {
            $date_modified_on = date('Y-m-d', strtotime($this->request->get['filter_date_modified_on']));
            if ($this->validateDate($date_modified_on, 'Y-m-d')) {
                $parameters["filter_date_modified_on"] = $date_modified_on;
            }
        } else {
            $parameters["filter_date_modified_on"] = null;
        }

        if (isset($this->request->get['filter_date_modified_from'])) {
            $date_modified_from = date('Y-m-d H:i:s', strtotime($this->request->get['filter_date_modified_from']));
            if ($this->validateDate($date_modified_from)) {
                $parameters["filter_date_modified_from"] = $date_modified_from;
            }
        } else {
            $parameters["filter_date_modified_from"] = null;
        }

        if (isset($this->request->get['filter_date_modified_to'])) {
            $date_modified_to = date('Y-m-d H:i:s', strtotime($this->request->get['filter_date_modified_to']));
            if ($this->validateDate($date_modified_to)) {
                $parameters["filter_date_modified_to"] = $date_modified_to;
            }
        } else {
            $parameters["filter_date_modified_to"] = null;
        }

        /*check simple list parameter*/
        $simpleList = false;
        if (isset($request->get['simple'])) {
            if (!empty($request->get['simple'])) {
                $simpleList = true;
            }
        }

        /*check custom list parameter*/
        $customFields = false;
        if (isset($request->get['custom_fields'])) {
            if (!empty($request->get['custom_fields'])) {
                $customFields = $request->get['custom_fields'];
            }
        }

        $parameters["start"] = ($parameters["start"] - 1) * $parameters["limit"];

        $products = $this->model_catalog_product->getProductsAllData($parameters, $this->customer);

        if (!empty($products)) {
            foreach ($products as $product) {
                $this->json['data'][] = $this->getProductInfo($product, $simpleList, $customFields);
            }
        }
    }

    private function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function search()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = $this->getPost();
            $this->searchService($post);
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("POST");
        }

        return $this->sendResponse();
    }

    public function searchService($post)
    {

        $this->load->model('catalog/product');

        $parameters = array(
            "limit" => 100,
            "start" => 1
        );

        /*check limit parameter*/
        if (isset($this->request->get['limit']) && ctype_digit($this->request->get['limit'])) {
            $parameters["limit"] = $this->request->get['limit'];
        }

        /*check page parameter*/
        if (isset($this->request->get['page']) && ctype_digit($this->request->get['page'])) {
            $parameters["start"] = $this->request->get['page'];
        }

        $parameters["start"] = ($parameters["start"] - 1) * $parameters["limit"];

        $products = $this->model_catalog_product->search($parameters, $post, $this->customer);

        if (!empty($products)) {
            foreach ($products as $product) {
                $this->json['data'][] = $this->getProductInfo($product);
            }
        }
    }


    public function categories()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get category details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getCategory($this->request->get['id']);
            } else {
                /*check parent parameter*/
                if (isset($this->request->get['parent'])) {
                    $parent = $this->request->get['parent'];
                } else {
                    $parent = 0;
                }

                /*check level parameter*/
                if (isset($this->request->get['level'])) {
                    $level = $this->request->get['level'];
                } else {
                    $level = 1;
                }

                $this->listCategories($parent, $level);
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    public function getCategory($id, $level = 10)
    {

        $this->load->model('catalog/category');
        $this->load->model('tool/image');

        if (ctype_digit($id)) {
            $category_id = $id;
        } else {
            $category_id = 0;
        }

        $category = $this->model_catalog_category->getCategory($category_id);

        if (isset($category['category_id'])) {

            if (isset($category['image']) && !empty($category['image']) && file_exists(DIR_IMAGE . $category['image'])) {
                $image = $this->model_tool_image->resize($category['image'], $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
                $original_image = $this->urlPrefix . 'image/' . $category['image'];
            } else {
                $image = $this->model_tool_image->resize('no_image.png', $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
                $original_image = $this->urlPrefix . 'image/no_image.png';
            }

            $this->json['data'] = array(
                'id' => $category['category_id'],
                'name' => $category['name'],
                'description' => $category['description'],
                'image' => $image,
                'original_image' => $original_image,
                'filters' => $this->getCategoryFilters($category_id),
                'sub_categories' => $this->loadCatTree($category['category_id'], $level)
            );
        } else {
            $this->statusCode = 404;
            $this->json['error'][] = "The specified category does not exist.";
        }
    }

    private function getCategoryFilters($category_id)
    {
        $this->load->language('module/filter');
        $this->load->model('catalog/product');

        $data['filter_groups'] = array();

        $filter_groups = $this->model_catalog_category->getCategoryFilters($category_id);

        if ($filter_groups) {
            foreach ($filter_groups as $filter_group) {
                $childen_data = array();

                foreach ($filter_group['filter'] as $filter) {
                    $filter_data = array(
                        'filter_category_id' => $category_id,
                        'filter_filter' => $filter['filter_id']
                    );

                    $childen_data[] = array(
                        'filter_id' => $filter['filter_id'],
                        'name' => $filter['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : '')
                    );
                }

                $data['filter_groups'][] = array(
                    'filter_group_id' => $filter_group['filter_group_id'],
                    'name' => $filter_group['name'],
                    'filter' => $childen_data
                );
            }
        }

        return $data;
    }

    public function loadCatTree($parent = 0, $level = 1)
    {

        $this->load->model('catalog/category');
        $this->load->model('tool/image');

        $result = array();

        $categories = $this->model_catalog_category->getCategories($parent);

        if ($categories && $level > 0) {
            $level--;

            foreach ($categories as $category) {

                if (isset($category['image']) && !empty($category['image']) && file_exists(DIR_IMAGE . $category['image'])) {
                    $image = $this->model_tool_image->resize($category['image'], $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
                    $original_image = $this->urlPrefix . 'image/' . $category['image'];
                } else {
                    $image = $this->model_tool_image->resize('no_image.png', $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
                    $original_image = $this->urlPrefix . 'image/no_image.png';
                }

                $result[] = array(
                    'category_id' => $category['category_id'],
                    'parent_id' => $category['parent_id'],
                    'name' => $category['name'],
                    'image' => $image,
                    'original_image' => $original_image,
                    'filters' => $this->getCategoryFilters($category['category_id']),
                    'categories' => $this->loadCatTree($category['category_id'], $level)
                );
            }
        }

        return $result;
    }


    public function listCategories($parent, $level)
    {

        $this->load->model('catalog/category');

        $data = $this->loadCatTree($parent, $level);

        if (!empty($data)) {
            $this->json['data'] = $data;
        }
    }


    public function productclasses()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->load->model('catalog/product');

            $this->json['data']['stock_statuses'] = $this->model_catalog_product->getStockStatuses();
            $this->json['data']['length_classes'] = $this->model_catalog_product->getLengthClasses();
            $this->json['data']['weight_classes'] = $this->model_catalog_product->getWeightClasses();
            $stores_result = $this->model_catalog_product->getStores();

            $stores = array();

            foreach ($stores_result as $result) {
                $stores[] = array(
                    'store_id' => $result['store_id'],
                    'name' => $result['name']
                );
            }

            $default_store[] = array(
                'store_id' => 0,
                'name' => $this->config->get('config_name')
            );

            $this->json['data']['stores'] = array_merge($default_store, $stores);

            $this->json['data']['recurrings'] = $this->model_catalog_product->getRecurrings();

            $this->load->model('localisation/currency');

            $this->json['data']['currencies'] = array();

            $results = $this->model_localisation_currency->getCurrencies();

            foreach ($results as $result) {
                if ($result['status']) {
                    $this->json['data']['currencies'][] = array(
                        'title' => $result['title'],
                        'code' => $result['code'],
                        'symbol_left' => $result['symbol_left'],
                        'symbol_right' => $result['symbol_right']
                    );
                }
            }

            $this->load->model('localisation/return_reason');
            $this->json['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();

            $this->json['data']['customer_groups'] = array();

            if (is_array($this->config->get('config_customer_group_display'))) {
                $this->load->model('account/customer_group');

                $customer_groups = $this->model_account_customer_group->getCustomerGroups();

                foreach ($customer_groups as $customer_group) {
                    if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                        $this->json['data']['customer_groups'][] = $customer_group;
                    }
                }
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    public function manufacturers()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get manufacturer details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getManufacturer($this->request->get['id']);
            } else {
                //get manufacturers list
                $this->listManufacturers();
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    public function getManufacturer($id)
    {

        $this->load->model('catalog/manufacturer');
        $this->load->model('tool/image');

        if (ctype_digit($id)) {
            $manufacturer = $this->model_catalog_manufacturer->getManufacturer($id);
            if ($manufacturer) {
                $this->json['data'] = $this->getManufacturerInfo($manufacturer);
            } else {
                $this->statusCode = 404;
                $this->json['error'][] = "The specified manufacturer does not exist.";
            }
        } else {
            $this->statusCode = 400;
            $this->json['error'][] = "Invalid category id";
        }
    }

    private function getManufacturerInfo($manufacturer)
    {

        if (isset($manufacturer['image']) && !empty($manufacturer['image']) && file_exists(DIR_IMAGE . $manufacturer['image'])) {
            $image = $this->model_tool_image->resize($manufacturer['image'], $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
            $original_image = $this->urlPrefix . 'image/' . $manufacturer['image'];
        } else {
            $image = $this->model_tool_image->resize('no_image.png', $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
            $original_image = $this->urlPrefix . 'image/no_image.png';
        }

        return array(
            'manufacturer_id' => $manufacturer['manufacturer_id'],
            'name' => $manufacturer['name'],
            'image' => $image,
            'original_image' => $original_image,
            'sort_order' => $manufacturer['sort_order']
        );
    }

    public function listManufacturers()
    {

        $this->load->model('catalog/manufacturer');
        $this->load->model('tool/image');

        $data['start'] = 0;
        $data['limit'] = 1000;

        $results = $this->model_catalog_manufacturer->getManufacturers($data);

        if (!empty($results)) {
            foreach ($results as $manufacturer) {
                $this->json["data"][] = $this->getManufacturerInfo($manufacturer);
            }
        }
    }


    public function orders()
    {

        $this->checkPlugin();

        $this->returnDeprecated();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get order details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getOrder($this->request->get['id']);
            } else {
                //get orders list
                $this->listOrders();
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    public function getOrder($order_id)
    {

        $this->load->model('checkout/order');
        $this->load->model('account/order');

        if (ctype_digit($order_id)) {
            $order_info = $this->model_checkout_order->getOrder($order_id);

            if (!empty($order_info)) {
                $this->json['data'] = $this->getOrderDetailsToOrder($order_info);

            } else {
                $this->json['error'][] = "Order not found";
                $this->statusCode = 404;

            }
        } else {
            $this->json['error'][] = "Invalid order id.";
            $this->statusCode = 400;

        }
    }

    private function getOrderDetailsToOrder($order_info)
    {

        $this->load->model('catalog/product');

        $orderData = array();

        if (!empty($order_info)) {
            foreach ($order_info as $key => $value) {
                $orderData[$key] = $value;
            }

            $orderData['products'] = array();

            $products = $this->model_account_order->getOrderProducts($orderData['order_id']);

            foreach ($products as $product) {
                $option_data = array();

                $options = $this->model_account_order->getOrderOptionsMod($orderData['order_id'], $product['order_product_id']);

                foreach ($options as $option) {
                    if ($option['type'] != 'file') {
                        $option_data[] = array(
                            'name' => $option['name'],
                            'value' => $option['value'],
                            'type' => $option['type'],
                            'product_option_id' => isset($option['product_option_id']) ? $option['product_option_id'] : "",
                            'product_option_value_id' => isset($option['product_option_value_id']) ? $option['product_option_value_id'] : "",
                            'option_id' => isset($option['option_id']) ? $option['option_id'] : "",
                            'option_value_id' => isset($option['option_value_id']) ? $option['option_value_id'] : ""
                        );
                    } else {
                        $option_data[] = array(
                            'name' => $option['name'],
                            'value' => utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.')),
                            'type' => $option['type']
                        );
                    }
                }

                $origProduct = $this->model_catalog_product->getProduct($product['product_id']);

                $orderData['products'][] = array(
                    'order_product_id' => $product['order_product_id'],
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'model' => $product['model'],
                    'sku' => (!empty($origProduct['sku']) ? $origProduct['sku'] : ""),
                    'option' => $option_data,
                    'quantity' => $product['quantity'],
                    'price_excluding_tax' => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
                    'price' => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
                    'total' => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
                );
            }
        }

        $orderData['histories'] = array();

        $histories = $this->model_account_order->getOrderHistoriesRest($orderData['order_id'], 0, 1000);

        foreach ($histories as $result) {
            $orderData['histories'][] = array(
                'notify' => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
                'status' => $result['status'],
                'comment' => nl2br($result['comment']),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        // Voucher
        $orderData['vouchers'] = array();

        $vouchers = $this->model_account_order->getOrderVouchers($orderData['order_id']);

        foreach ($vouchers as $voucher) {
            $orderData['vouchers'][] = array(
                'description' => $voucher['description'],
                'amount' => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
            );
        }

        // Totals
        $orderData['totals'] = array();

        $totals = $this->model_account_order->getOrderTotals($orderData['order_id']);

        foreach ($totals as $total) {
            $orderData['totals'][] = array(
                'title' => $total['title'],
                'text' => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
            );
        }

        return $orderData;
    }

    public function listOrders()
    {

        $this->load->model('account/order');

        /*check offset parameter*/
        if (isset($this->request->get['offset']) && $this->request->get['offset'] != "" && ctype_digit($this->request->get['offset'])) {
            $offset = $this->request->get['offset'];
        } else {
            $offset = 0;
        }

        /*check limit parameter*/
        if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = 10000;
        }

        /*get all orders of user*/
        $results = $this->model_account_order->getAllOrders($offset, $limit);

        $orders = array();

        if (!empty($results)) {
            foreach ($results as $result) {

                $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
                $voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

                $orders[] = array(
                    'order_id' => $result['order_id'],
                    'name' => $result['firstname'] . ' ' . $result['lastname'],
                    'status' => $result['status'],
                    'date_added' => $result['date_added'],
                    'products' => ($product_total + $voucher_total),
                    'total' => $result['total'],
                    'currency_code' => $result['currency_code'],
                    'currency_value' => $result['currency_value'],
                );
            }

            if (!empty($orders)) {
                $this->json['data'] = $orders;
            }

        }
    }


    public function listorderswithdetails()
    {

        $this->checkPlugin();

        $this->returnDeprecated();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $this->load->model('account/order');

            /*check limit parameter*/
            if (isset($this->request->get['limit']) && $this->request->get['limit'] != "" && ctype_digit($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 100000;
            }

            if (isset($this->request->get['filter_date_added_from'])) {
                $date_added_from = date('Y-m-d H:i:s', strtotime($this->request->get['filter_date_added_from']));
                if ($this->validateDate($date_added_from)) {
                    $filter_date_added_from = $date_added_from;
                }
            } else {
                $filter_date_added_from = null;
            }

            if (isset($this->request->get['filter_date_added_on'])) {
                $date_added_on = date('Y-m-d', strtotime($this->request->get['filter_date_added_on']));
                if ($this->validateDate($date_added_on, 'Y-m-d')) {
                    $filter_date_added_on = $date_added_on;
                }
            } else {
                $filter_date_added_on = null;
            }


            if (isset($this->request->get['filter_date_added_to'])) {
                $date_added_to = date('Y-m-d H:i:s', strtotime($this->request->get['filter_date_added_to']));
                if ($this->validateDate($date_added_to)) {
                    $filter_date_added_to = $date_added_to;
                }
            } else {
                $filter_date_added_to = null;
            }

            if (isset($this->request->get['filter_date_modified_on'])) {
                $date_modified_on = date('Y-m-d', strtotime($this->request->get['filter_date_modified_on']));
                if ($this->validateDate($date_modified_on, 'Y-m-d')) {
                    $filter_date_modified_on = $date_modified_on;
                }
            } else {
                $filter_date_modified_on = null;
            }

            if (isset($this->request->get['filter_date_modified_from'])) {
                $date_modified_from = date('Y-m-d H:i:s', strtotime($this->request->get['filter_date_modified_from']));
                if ($this->validateDate($date_modified_from)) {
                    $filter_date_modified_from = $date_modified_from;
                }
            } else {
                $filter_date_modified_from = null;
            }

            if (isset($this->request->get['filter_date_modified_to'])) {
                $date_modified_to = date('Y-m-d H:i:s', strtotime($this->request->get['filter_date_modified_to']));
                if ($this->validateDate($date_modified_to)) {
                    $filter_date_modified_to = $date_modified_to;
                }
            } else {
                $filter_date_modified_to = null;
            }

            if (isset($this->request->get['page'])) {
                $page = $this->request->get['page'];
            } else {
                $page = 1;
            }

            if (isset($this->request->get['filter_order_status_id'])) {
                $filter_order_status_id = $this->request->get['filter_order_status_id'];
            } else {
                $filter_order_status_id = null;
            }

            $data = array(
                'filter_date_added_on' => $filter_date_added_on,
                'filter_date_added_from' => $filter_date_added_from,
                'filter_date_added_to' => $filter_date_added_to,
                'filter_date_modified_on' => $filter_date_modified_on,
                'filter_date_modified_from' => $filter_date_modified_from,
                'filter_date_modified_to' => $filter_date_modified_to,
                'filter_order_status_id' => $filter_order_status_id,
                'start' => ($page - 1) * $limit,
                'limit' => $limit
            );


            $results = $this->model_account_order->getOrdersByFilter($data);
            /*get all orders*/
            $orders = array();

            if (count($results)) {

                foreach ($results as $result) {

                    $orderData = $this->getOrderDetailsToOrder($result);

                    if (!empty($orderData)) {
                        $orders[] = $orderData;
                    }
                }

                $this->json['data'] = $orders;

            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }


    public function userorders()
    {

        $this->checkPlugin();

        $this->returnDeprecated();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $user = null;

            /*check user parameter*/
            if (isset($this->request->get['user']) && $this->request->get['user'] != ""
                && ctype_digit($this->request->get['user'])
            ) {

                $user = $this->request->get['user'];

                $orderData['orders'] = array();

                $this->load->model('account/order');

                /*get all orders of user*/
                $results = $this->model_account_order->getOrdersByUser($user);

                if (!empty($results)) {

                    foreach ($results as $result) {

                        $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
                        $voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

                        $this->json['data'][] = array(
                            'order_id' => $result['order_id'],
                            'name' => $result['firstname'] . ' ' . $result['lastname'],
                            'status' => $result['status'],
                            'date_added' => $result['date_added'],
                            'products' => ($product_total + $voucher_total),
                            'total' => $result['total'],
                            'currency_code' => $result['currency_code'],
                            'currency_value' => $result['currency_value'],
                        );
                    }
                }
            }

        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    public function customers()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get customer details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getCustomer($this->request->get['id']);
            } else {
                //get customers list
                $this->listCustomers();
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    private function getCustomer($id)
    {

        $this->load->model('account/customer');

        if (ctype_digit($id)) {
            $customer = $this->model_account_customer->getCustomer($id);
            if (!empty($customer['customer_id'])) {
                $this->json['data'] = $this->getCustomerInfo($customer);
            } else {
                $this->json['error'][] = "Customer not found";
                $this->statusCode = 404;
            }
        } else {
            $this->json['error'][] = "Invalid customer id";
            $this->statusCode = 400;
        }
    }

    private function getCustomerInfo($customer)
    {
        // Custom Fields
        $this->load->model('account/custom_field');

        $custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

        if ($this->opencartVersion < 2100) {
            $account_custom_field = unserialize($customer['custom_field']);
        } else {
            $account_custom_field = json_decode($customer['custom_field'], true);
        }

        return array(
            'store_id' => $customer['store_id'],
            'customer_id' => $customer['customer_id'],
            'firstname' => $customer['firstname'],
            'lastname' => $customer['lastname'],
            'telephone' => $customer['telephone'],
            'fax' => $customer['fax'],
            'email' => $customer['email'],
            'account_custom_field' => $account_custom_field,
            'custom_fields' => $custom_fields

        );
    }


    private function listCustomers()
    {

        $this->load->model('account/customer');

        $results = $this->model_account_customer->getCustomersMod();

        if (!empty($results)) {
            foreach ($results as $customer) {
                $this->json['data'][] = $this->getCustomerInfo($customer);
            }
        }
    }


    public function reviews()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //add review
            $post = $this->getPost();

            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->addReview($this->request->get['id'], $post);
            } else {
                $this->statusCode = 400;
                $this->json['error'][] = "Invalid identifier.";
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("POST");
        }

        return $this->sendResponse();

    }


    public function addReview($id, $post)
    {

        $this->load->language('product/product');

        if (!isset($post['name']) || (utf8_strlen($post['name']) < 3) || (utf8_strlen($post['name']) > 25)) {
            $this->json['error']['name'] = $this->language->get('error_name');
        }

        if (!isset($post['text']) || (utf8_strlen($post['text']) < 25) || (utf8_strlen($post['text']) > 1000)) {
            $this->json['error']['review_text'] = $this->language->get('error_text');
        }

        if (!isset($post['rating']) || empty($post['rating']) || $post['rating'] < 0 || $post['rating'] > 5) {
            $this->json['error']['rating'] = $this->language->get('error_rating');
        }

        if (empty($this->json["error"])) {
            $this->load->model('catalog/review');
            $this->model_catalog_review->addReview($id, $post);
        } else {
            $this->statusCode = 400;
        }
    }


    public function languages()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get language details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getLanguage($this->request->get['id']);
            } else {
                //get languages list
                $this->listLanguages();
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }


    private function getLanguage($id)
    {

        $this->load->model('localisation/language');

        if (ctype_digit($id)) {
            $result = $this->model_localisation_language->getLanguage($id);
        } else {
            $this->statusCode = 400;
            $this->json['error'][] = "Invalid identifier.";
        }

        if (empty($this->json['error']) && !empty($result)) {
            $this->json['data'] = array(
                'language_id' => $result['language_id'],
                'name' => $result['name'],
                'code' => $result['code'],
                'locale' => $result['locale'],
                'image' => $result['image'],
                'directory' => $result['directory'],
                'filename' => isset($result['filename']) ? $result['filename'] : '',
                'sort_order' => $result['sort_order'],
                'status' => $result['status']
            );
        } else {
            $this->json['error'][] = "Language not found";
            $this->statusCode = 404;
        }

    }

    private function listLanguages()
    {

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();

        if (!empty($languages)) {
            $this->json['data'] = array_values($languages);
        }

    }


    public function order_statuses()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get order statuses list
            $this->listOrderStatuses();
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    private function listOrderStatuses()
    {

        $this->load->model('account/order');

        $statuses = $this->model_account_order->getOrderStatuses();

        if (!empty($statuses)) {
            $this->json['data'] = $statuses;
        }
    }


    public function stores()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get store details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getStore($this->request->get['id']);
            } else {
                //get stores list
                $this->listStores();
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }


    private function getStore($id)
    {

        $this->load->model('catalog/product');
        $result = array();

        if (ctype_digit($id)) {
            $result = $this->model_catalog_product->getStore($id);
        } else {
            $this->statusCode = 400;
            $this->json['error'][] = "Invalid identifier.";
        }
        if (!empty($result) && empty($this->json['error'])) {
            // Store
            $this->json['data']['store_id'] = $id; // Store id


            foreach ($result as $setting) {
                switch ($setting['key']) {
                    case 'config_name':
                        $this->json['data']['store_name'] = $setting['value']; // Store title
                        break;
                    case 'config_owner':
                        $this->json['data']['store_owner'] = $setting['value']; // Store owner
                        break;
                    case 'config_geocode':
                        $this->json['data']['store_geocode'] = $setting['value']; // Store geocode
                        break;
                    case 'config_address':
                        $this->json['data']['store_address'] = $setting['value']; // Store address
                        break;
                    case 'config_email':
                        $this->json['data']['store_email'] = $setting['value']; // Store email
                        break;
                    case 'config_telephone':
                        $this->json['data']['store_telephone'] = $setting['value']; // Store telephone
                        break;
                    case 'config_fax':
                        $this->json['data']['store_fax'] = $setting['value']; // Store fax
                        break;
                    case 'config_open':
                        $this->json['data']['store_open'] = $setting['value']; // Store open
                        break;
                    case 'config_comment':
                        $this->json['data']['store_comment'] = $setting['value']; // Store comment
                        break;
                    case 'config_language':
                        $this->json['data']['store_language'] = $setting['value']; // Store language
                        break;
                    case 'config_url':
                        $this->json['data']['store_url'] = $setting['value']; // Store url
                        break;
                    case 'config_checkout_guest':
                        $this->json['data']['config_checkout_guest'] = $setting['value']; // Store config_checkout_guest
                        break;
                    case 'config_tax':
                        $this->json['data']['config_tax'] = $setting['value']; // Store config_tax
                        break;
                    case 'config_customer_online':
                        $this->json['data']['config_customer_online'] = $setting['value']; // Store config_customer_online
                        break;
                    case 'config_image':
                        $this->json['data']['store_image'] = $setting['value']; // Store image
                        $this->load->model('tool/image');
                        if (!empty($setting['value']) && is_file(DIR_IMAGE . $setting['value'])) {
                            $this->json['data']['thumb'] = $this->model_tool_image->resize($setting['value'], $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
                        } else {
                            $this->json['data']['thumb'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
                        }
                        break;
                }

            }

        } else {
            $this->json['error'][] = "Store not found";
            $this->statusCode = 404;
        }

    }

    private function listStores()
    {

        $this->load->model('catalog/product');

        $results = $this->model_catalog_product->getStores();

        if (!empty($results)) {
            foreach ($results as $result) {
                $this->json['data'][] = array(
                    'store_id' => $result['store_id'],
                    'name' => $result['name']
                );
            }
        }

        $this->json['data'][] = array(
            'store_id' => 0,
            'name' => $this->config->get('config_name')
        );
    }

    public function countries()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get country details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getCountry($this->request->get['id']);
            } else {
                $this->listCountries();
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    public function getCountry($country_id)
    {

        $this->load->model('localisation/country');

        $country_info = $this->model_localisation_country->getCountry($country_id);

        if (!empty($country_info)) {
            $this->json["data"] = $this->getCountryInfo($country_info);
        } else {
            $this->json['error'][] = "Country not found";
            $this->statusCode = 404;
        }

    }

    private function getCountryInfo($country_info, $addZone = true)
    {
        $this->load->model('localisation/zone');
        $info = array(
            'country_id' => $country_info['country_id'],
            'name' => $country_info['name'],
            'iso_code_2' => $country_info['iso_code_2'],
            'iso_code_3' => $country_info['iso_code_3'],
            'address_format' => $country_info['address_format'],
            'postcode_required' => $country_info['postcode_required'],
            'status' => $country_info['status']
        );
        if ($addZone) {
            $info['zone'] = $this->model_localisation_zone->getZonesByCountryId($country_info['country_id']);
        }

        return $info;
    }


    private function listCountries()
    {

        $this->load->model('localisation/country');

        $results = $this->model_localisation_country->getCountries();

        if (!empty($results)) {
            foreach ($results as $country) {
                $this->json['data'][] = $this->getCountryInfo($country, false);
            }
        }

    }

    public function session()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get session details
            session_regenerate_id();
            $this->json['data'] = array('session' => session_id());
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    public function featured()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get featured products
            $limit = 0;

            if (isset($this->request->get['limit']) && ctype_digit($this->request->get['limit']) && $this->request->get['limit'] > 0) {
                $limit = $this->request->get['limit'];
            }

            $this->getFeaturedProducts($limit);
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    public function getFeaturedProducts($limit)
    {


        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $featureds = $this->model_catalog_product->getModulesByCode('featured');
        $data = array();
        $index = 0;

        if (count($featureds)) {
            foreach ($featureds as $featured) {
                $data[$index]['module_id'] = $featured['module_id'];
                $data[$index]['name'] = $featured['name'];
                $data[$index]['code'] = $featured['code'];

                if ($this->opencartVersion < 2100) {
                    $settings = unserialize($featured['setting']);
                } else {
                    $settings = json_decode($featured['setting'], true);
                }

                $products = $settings['product'];

                if ($limit) {
                    $products = array_slice($products, 0, (int)$limit);
                }

                $all = $this->model_catalog_product->getProductsByIds($products, $this->customer);

                foreach ($all as $product_info) {

                    if ($product_info) {
                        if ($product_info['image']) {
                            $image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
                        } else {
                            $image = false;
                        }

                        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                            $price_excluding_tax = $this->currency->format($product_info['price'], $this->currency->getRestCurrencyCode(), '', false);
                            $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode(), '', false);
                            $price_formated = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode());
                        } else {
                            $price = false;
                            $price_excluding_tax = false;
                            $price_formated = false;
                        }

                        if (!empty($product_info['special']) && (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price'))) {
                            $special = $this->currency->format(($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'))), $this->currency->getRestCurrencyCode(), '', false);
                            $special_excluding_tax = $this->currency->format($product_info['special'], $this->currency->getRestCurrencyCode(), '', false);
                            $special_formated = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode());
                        } else {
                            $special = false;
                            $special_formated = false;
                            $special_excluding_tax = false;
                        }

                        if ($this->config->get('config_review_status')) {
                            $rating = $product_info['rating'];
                        } else {
                            $rating = false;
                        }

                        $item = array(
                            'product_id' => $product_info['product_id'],
                            'thumb' => $image,
                            'name' => $product_info['name'],
                            'quantity'    => $product_info['quantity'],
                            'status'      => $product_info['status'],
                            'stock_status'      => $product_info['stock_status'],
                            'price_excluding_tax' => $price_excluding_tax,
                            'price' => $price,
                            'price_formated' => $price_formated,
                            'special' => $special,
                            'special_excluding_tax' => $special_excluding_tax,
                            'special_formated' => $special_formated,
                            'rating' => $rating,
                            'special_start_date' => (!empty($product_info['special_start_date']) ? $product_info['special_start_date'] : ""),
                            'special_end_date' => (!empty($product_info['special_end_date']) ? $product_info['special_end_date'] : ""),

                        );

                        if ($this->opencartVersion < 2200) {
                            $item['description'] = utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..';
                        } else {
                            $item['description'] = utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..';
                        }

                        $data[$index]['products'][] = $item;
                    }
                }
                $index++;
            }
        }
        $this->json['data'] = $data;
    }

    public function utc_offset()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $serverTimeZone = date_default_timezone_get();
            $timezone = new DateTimeZone($serverTimeZone);
            $now = new DateTime("now", $timezone);
            $offset = $timezone->getOffset($now);

            $this->json['data'] = array('offset' => $offset);
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    public function orderhistory()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $post = $this->getPost();

            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->addOrderHistory($this->request->get['id'], $post);
            } else {
                $this->statusCode = 400;
                $this->json['error'][] = "Invalid identifier.";
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("PUT");
        }

        return $this->sendResponse();
    }

    private function addOrderHistory($id, $data)
    {

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($id);

        if ($order_info) {
            $this->model_checkout_order->addOrderHistory($id, $data['order_status_id'], $data['comment'], $data['notify']);
        } else {
            $this->json['error'][] = "Order not found";
            $this->statusCode = 404;
        }

    }

    public function bestsellers()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->getBestsellers($this->request);
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    private function getBestsellers($request)
    {

        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        /*check limit parameter*/
        $limit = 10;

        if (isset($request->get['limit']) && ctype_digit($request->get['limit'])) {
            $limit = $request->get['limit'];
        }

        $results = $this->model_catalog_product->getBestSellerProducts($limit);

        if (!empty($results)) {
            foreach ($results as $result) {
                $this->json['data'][] = $this->getProductBaseInfo($result);
            }
        }

    }

    private function getProductBaseInfo($product)
    {

        if (isset($product['image']) && file_exists(DIR_IMAGE . $product['image'])) {
            $image = $this->model_tool_image->resize($product['image'], $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
        } else {
            $image = $this->model_tool_image->resize('no_image.png', $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
        }

        $special = false;
        $special_excluding_tax = false;
        $special_formated = false;
        $discounts = array();

        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $hidePrices = false;
        } else {
            $hidePrices = true;
        }

        //special
        if ((float)$product['special'] && empty($hidePrices)) {
            $special_excluding_tax = $this->currency->format($product['special'], $this->currency->getRestCurrencyCode(), '', false);
            $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode(), '', false);
            $special_formated = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode());
        }

        //discounts
        $data_discounts = $this->model_catalog_product->getProductDiscounts($product['product_id']);

        foreach ($data_discounts as $discount) {
            $discounts[] = array(
                'quantity' => $discount['quantity'],
                'price_excluding_tax' => empty($hidePrices) ? $this->currency->format($discount['price'], $this->currency->getRestCurrencyCode(), '', false) : false,
                'price' => empty($hidePrices) ? $this->currency->format($this->tax->calculate($discount['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode(), '', false) : false,
                'price_formated' => empty($hidePrices) ? $this->currency->format($this->tax->calculate($discount['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode()) : false
            );
        }

        if ($this->config->get('config_review_status')) {
            $rating = (int)$product['rating'];
        } else {
            $rating = "";
        }

        $item = array(
            'product_id' => $product['product_id'],
            'thumb' => $image,
            'name' => $product['name'],
            'quantity'    => $product['quantity'],
            'status'      => $product['status'],
            'stock_status'      => $product['stock_status'],
            'price_excluding_tax' => empty($hidePrices) ? $this->currency->format($product['price'], $this->currency->getRestCurrencyCode(), '', false) : false,
            'price' => empty($hidePrices) ? $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode(), '', false) : false,
            'price_formated' => empty($hidePrices) ? $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode()) : false,
            'special' => $special,
            'special_excluding_tax' => $special_excluding_tax,
            'special_formated' => $special_formated,
            'discounts' => $discounts,
            'rating' => $rating
        );

        if ($this->opencartVersion < 2200) {
            $item['description'] = utf8_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..';
        } else {
            $item['description'] = utf8_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..';
        }

        return $item;
    }

    public function filters()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $categoryId = 0;

            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $categoryId = $this->request->get['id'];
            }
            $this->getFilters($categoryId);
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    private function getFilters($category_id)
    {

        $this->load->model('catalog/category');

        $category_info = $this->model_catalog_category->getCategory($category_id);

        if ($category_info) {
            $this->json["data"] = $this->getCategoryFilters($category_id);
        }

    }

    public function slideshows()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->getSlideshows();
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }


    public function getSlideshows()
    {

        $this->load->model('catalog/product');
        $this->load->model('extension/module');
        $this->load->model('design/banner');
        $this->load->model('tool/image');

        $slideshows = $this->model_catalog_product->getModulesByCode('slideshow');
        $data = array();
        $index = 0;

        if (count($slideshows)) {
            foreach ($slideshows as $slideshow) {
                $module_info = $this->model_extension_module->getModule($slideshow['module_id']);
                $data[$index]['module_id'] = $slideshow['module_id'];
                $data[$index]['name'] = $module_info['name'];
                $data[$index]['banner_id'] = $module_info['banner_id'];
                $data[$index]['width'] = $module_info['width'];
                $data[$index]['height'] = $module_info['height'];
                $data[$index]['status'] = $module_info['status'];

                $data[$index]['banners'] = array();

                $results = $this->model_design_banner->getBanner($module_info['banner_id']);

                foreach ($results as $result) {
                    if (is_file(DIR_IMAGE . $result['image'])) {
                        $data[$index]['banners'][] = array(
                            'title' => $result['title'],
                            'link' => $result['link'],
                            'image' => $this->model_tool_image->resize($result['image'], $module_info['width'], $module_info['height'])
                        );
                    }
                }
                $index++;
            }
        }
        $this->json['data'] = $data;
    }


    public function related()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getRelated($this->request->get['id']);
            } else {
                $this->statusCode = 400;
                $this->json['error'][] = "Invalid identifier.";
            }

        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    private function getRelated($id)
    {

        $this->load->model('tool/image');
        $this->load->model('catalog/product');

        $results = $this->model_catalog_product->getProductRelated($id);

        if (!empty($results)) {
            foreach ($results as $result) {
                $this->json["data"][] = $this->getProductBaseInfo($result);
            }
        }
    }

    public function latest()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $limit = 10;
            if (isset($this->request->get['limit']) && ctype_digit($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            }
            $this->getLatest($limit);
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }

    private function getLatest($limit)
    {

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $filter_data = array(
            'sort' => 'p.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => $limit
        );

        $results = $this->model_catalog_product->getProducts($filter_data);

        if (!empty($results)) {
            foreach ($results as $result) {
                $this->json["data"][] = $this->getProductBaseInfo($result);
            }
        }
    }

    public function information()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get information details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getInformation($this->request->get['id']);
            } else {
                //get information list
                $this->listInformation();
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }


    public function getInformation($id)
    {

        $this->load->language('information/information');
        $this->load->model('catalog/information');

        if (ctype_digit($id)) {
            $information_info = $this->model_catalog_information->getInformation($id);
            if ($information_info) {
                $this->json['data'] = $information_info;
            } else {
                $this->json['error'][] = "Information not found";
                $this->statusCode = 404;
            }
        } else {
            $this->statusCode = 400;
            $this->json['error'][] = "Invalid identifier.";
        }

    }


    public function listInformation()
    {
        $this->load->language('module/information');

        $this->load->model('catalog/information');

        foreach ($this->model_catalog_information->getInformations() as $result) {
            $this->json["data"][] = array(
                'id' => $result['information_id'],
                'title' => $result['title']
            );
        }
    }


    public function banners()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            //get banner details
            if (isset($this->request->get['id']) && ctype_digit($this->request->get['id'])) {
                $this->getBanner($this->request->get['id']);
            } else {
                //get banner list
                $this->listBanners();
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }


    public function getBanner($id)
    {

        $this->load->model('design/banner');
        $this->load->model('tool/image');
        $this->load->model('setting/setting');

        if (ctype_digit($id)) {

            $results = $this->model_design_banner->getBanner($id);

            if (!empty($results)) {
                foreach ($results as $result) {
                    if (is_file(DIR_IMAGE . $result['image'])) {
                        $this->json['data'][] = array(
                            'title' => $result['title'],
                            'link' => $result['link'],
                            'image' => $this->urlPrefix . 'image/' . $result['image']
                        );
                    }
                }
            } else {
                $this->json['error'][] = "Banner not found";
                $this->statusCode = 404;
            }
        } else {
            $this->statusCode = 400;
            $this->json['error'][] = "Invalid identifier.";
        }

    }

    public function listBanners()
    {

        $this->load->model('catalog/product');

        $banners = $this->model_catalog_product->getBanners();

        if (!empty($banners)) {

            $this->load->model('design/banner');

            foreach ($banners as &$banner) {
                $data = $this->model_design_banner->getBanner($banner['banner_id']);
                usort($data, function ($item1, $item2) {
                    if ($item1['title'] == $item2['title']) return 0;
                    return $item1['title'] < $item2['title'] ? -1 : 1;
                });
                $banner['checksum'] = md5(json_encode($data));
            }
            unset($banner);
            $this->json['data'] = $banners;
        }
    }

    public function specials()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $limit = 10;
            if (isset($this->request->get['limit']) && ctype_digit($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            }
            $this->getSpecials($limit);
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();
    }


    private function getSpecials($limit)
    {

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $filter_data = array(
            'sort' => 'pd.name',
            'order' => 'ASC',
            'start' => 0,
            'limit' => $limit
        );


        $results = $this->model_catalog_product->getProductSpecials($filter_data);

        if (!empty($results)) {
            foreach ($results as $result) {
                $this->json['data'][] = $this->getProductBaseInfo($result);
            }
        }
    }

    public function compare()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $compare = $this->request->get['ids'];
            $compare = explode(",", $compare);
            if (!empty($compare)) {
                $this->compareProducts($compare);
            } else {
                $this->statusCode = 400;
                $this->json['error'][] = "Compare parameter is required";
            }

        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET");
        }

        return $this->sendResponse();

    }

    private function compareProducts($compare)
    {

        $data = array();

        $this->load->language('product/compare');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');


        $data['review_status'] = $this->config->get('config_review_status');

        $data['products'] = array();

        $data['attribute_groups'] = array();

        foreach ($compare as $key => $product_id) {
            $product_info = $this->model_catalog_product->getProduct($product_id);

            if ($product_info) {
                if ($product_info['image']) {
                    $image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_rest_api_image_width'), $this->config->get('config_rest_api_image_height'));
                } else {
                    $image = false;
                }

                if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode());
                } else {
                    $price = false;
                }

                if ((float)$product_info['special']) {
                    $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->currency->getRestCurrencyCode());
                } else {
                    $special = false;
                }

                if ($product_info['quantity'] <= 0) {
                    $availability = $product_info['stock_status'];
                } elseif ($this->config->get('config_stock_display')) {
                    $availability = $product_info['quantity'];
                } else {
                    $availability = $this->language->get('text_instock');
                }

                $attribute_data = array();

                $attribute_groups = $this->model_catalog_product->getProductAttributes($product_id);

                foreach ($attribute_groups as $attribute_group) {
                    foreach ($attribute_group['attribute'] as $attribute) {
                        $attribute_data[$attribute['attribute_id']] = $attribute['text'];
                    }
                }

                $data['products'][$product_id] = array(
                    'product_id' => $product_info['product_id'],
                    'name' => $product_info['name'],
                    'thumb' => $image,
                    'price' => $price,
                    'special' => $special,
                    'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '..',
                    'model' => $product_info['model'],
                    'manufacturer' => $product_info['manufacturer'],
                    'availability' => $availability,
                    'minimum' => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
                    'rating' => (int)$product_info['rating'],
                    'reviews' => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
                    'weight' => $this->weight->format($product_info['weight'], $product_info['weight_class_id']),
                    'length' => $this->length->format($product_info['length'], $product_info['length_class_id']),
                    'width' => $this->length->format($product_info['width'], $product_info['length_class_id']),
                    'height' => $this->length->format($product_info['height'], $product_info['length_class_id']),
                    'attribute' => $attribute_data,
                );

                foreach ($attribute_groups as $attribute_group) {
                    $data['attribute_groups'][$attribute_group['attribute_group_id']]['name'] = $attribute_group['name'];

                    foreach ($attribute_group['attribute'] as $attribute) {
                        $data['attribute_groups'][$attribute_group['attribute_group_id']]['attribute'][$attribute['attribute_id']]['name'] = $attribute['name'];
                    }
                }
            }
        }

        if (!empty($data['products'])) {
            $this->json["data"] = array_values($data['products']);
        }
    }


    public function affiliate()
    {

        $this->checkPlugin();

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->json['data']['tracking'] = "";
            if (isset($this->session->data['tracking'])){
                $this->json['data']['tracking'] = $this->session->data['tracking'];
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $post = $this->getPost();

            $this->session->data['tracking'] = $post['tracking'];

        } else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            if (isset($this->session->data['tracking'])){
                unset($this->session->data['tracking']);
            }
        } else {
            $this->statusCode = 405;
            $this->allowedHeaders = array("GET", "POST", "DELETE");
        }

        return $this->sendResponse();
    }
}