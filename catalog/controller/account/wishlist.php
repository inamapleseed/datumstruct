<?php
class ControllerAccountWishList extends Controller {
	public function index() {
		$products = array();

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/wishlist', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/wishlist');

		$this->load->model('account/wishlist');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['remove'])) {
			// Remove Wishlist
			$this->model_account_wishlist->deleteWishlist($this->request->get['remove']);

			$this->load->model('catalog/product');

			$product_info = $this->model_catalog_product->getProduct($this->request->get['remove']);

			if($product_info){
				$href = $this->url->link('product/product', 'product_id=' . $product_info['product_id']);
				$name = $product_info['name'];
				$wishlist_url = $this->url->link('account/wishlist');
				$msg = sprintf($this->language->get('text_remove_alt'), $href, $name, $wishlist_url);
			}else{
				$this->session->data['success'] = $this->language->get('text_remove');
			}

			$this->response->redirect($this->url->link('account/wishlist'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/wishlist')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_stock'] = $this->language->get('column_stock');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}


		$data['products'] = array();

		$results = $this->model_account_wishlist->getWishlist();

		foreach ($results as $result) {
			$product_info = $this->model_catalog_product->getProduct($result['product_id']);

			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_wishlist_width'), $this->config->get($this->config->get('config_theme') . '_image_wishlist_height'));
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}


				$products[] = $product_info;

				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'stock'      => $stock,
					'price'      => $price,
					'special'    => $special,
					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
					'remove'     => $this->url->link('account/wishlist', 'remove=' . $product_info['product_id'])
				);
			} else {
				$this->model_account_wishlist->deleteWishlist($result['product_id']);
			}
		}


		$this->facebookcommonutils = new FacebookCommonUtils();
        if (sizeof($products)) {
          $params = new DAPixelConfigParams(array(
            'eventName' => 'AddToWishlist',
            'products' => $products,
            'currency' => $this->currency,
            'currencyCode' => $this->session->data['currency'],
            'hasQuantity' => false));
          $facebook_pixel_event_params_FAE =
            $this->facebookcommonutils->getDAPixelParamsForProducts($params);
          // stores the pixel params in the session
          $this->request->post['facebook_pixel_event_params_FAE'] =
            addslashes(json_encode($facebook_pixel_event_params_FAE));
		}
		

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/wishlist', $data));
	}

	public function add() {
		$this->load->language('account/wishlist');

		$json = array();

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {

			$name = $product_info['name'];
			$url = $this->url->link('product/product', 'product_id=' . (int)$product_info['product_id']);
			$wishlist_url = $this->url->link('account/wishlist');

			if ($this->customer->isLogged()) {
				// Edit customers cart
				$this->load->model('account/wishlist');

				$wishlist = $this->model_account_wishlist->getWishlist();

				$item_removed = false;

				foreach($wishlist as $product){
					if($product['product_id'] == $product_info['product_id']){

						$this->model_account_wishlist->deleteWishlist($product_info['product_id']);

						$item_removed = true;

						break;

					}
				}

				$this->model_account_wishlist->addWishlist($this->request->post['product_id']);

				if($item_removed){
					$json['success_title'] = $this->language->get('success_remove_title');
					$json['success'] = sprintf($this->language->get('text_remove_alt'), $url, $name, $wishlist_url);
				}
				else{
					$json['success_title'] = $this->language->get('success_title');
					$json['success'] = sprintf($this->language->get('text_success'), $url, $name, $wishlist_url);
				}
				

				$json['total'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
			} else {
				if (!isset($this->session->data['wishlist'])) {
					$this->session->data['wishlist'] = array();
				}

				if (($key = array_search($this->request->post['product_id'], $this->session->data['wishlist'])) !== false) {
					unset($this->session->data['wishlist'][$key]);
					$json['success_title'] = $this->language->get('success_remove_title');
					$json['success'] = sprintf($this->language->get('text_remove_alt'), $url, $name, $wishlist_url);
				}
				else{
					$this->session->data['wishlist'][] = $this->request->post['product_id'];

					$json['success_title'] = $this->language->get('success_title');
					$json['success'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));
				}

				$this->session->data['wishlist'] = array_unique($this->session->data['wishlist']);

				$json['total'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
