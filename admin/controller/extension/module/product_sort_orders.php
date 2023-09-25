<?php 
class ControllerExtensionModuleProductSortOrders extends Controller {
	private $error = array(); 
     
  	public function index() {
		$this->load->language('extension/module/product_sort_orders');
    	
		$this->document->setTitle($this->language->get('heading_title')); 
		
		$data = array();
		
		$this->load->model('catalog/category');
		$data['categories'] = $this->model_catalog_category->getCategories(array( 'sort' => 'name'));
		
		if (isset($this->request->get['filter_category'])) {
			$filter_category = $this->request->get['filter_category'];
		} else {
			$filter_category = NULL;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
			$sort = 'p2co.sort_order,p.sort_order';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';
						
		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}
						
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/product_sort_orders', 'token=' . $this->session->data['token'] . $url, 'SSL'),       		
      		'separator' => ' :: '
   		);
		
		$data['save'] = $this->url->link('extension/module/product_sort_orders/save', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		if (isset($this->request->get['filter_category'])) {
			$data['products'] = array();

			$filter_data = array(
				'filter_category' => $filter_category,
				'sort'            => $sort,
				'order'           => $order
			);
			
			$this->load->model('extension/module/product_sort_orders');
			$this->load->model('tool/image');

			$results = $this->model_extension_module_product_sort_orders->getProducts($filter_data);

			foreach ($results as $result) {
				$category =  $this->model_extension_module_product_sort_orders->getProductCategories($result['product_id']);
				
				if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
					$image = $this->model_tool_image->resize($result['image'], 40, 40);
				} else {
					$image = $this->model_tool_image->resize('no_image.png', 40, 40);
				}

				$special = false;
				
				$product_specials = $this->model_extension_module_product_sort_orders->getProductSpecials($result['product_id']);
				
				foreach ($product_specials  as $product_special) {
					if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] > date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] < date('Y-m-d'))) {
						$special = $product_special['price'];
				
						break;
					}					
				}
		
				$data['products'][] = array(
					'product_id' => $result['product_id'],
					'name'       => $result['name'],
					'model'      => $result['model'],
					'price'      => $result['price'],
					'sort_order' => $result['sort_order'],
					'special'    => $special,
					'category'   => $category,
					'image'      => $image,
					'quantity'   => $result['quantity'],
					'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
					'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
					'edit'       => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
				);
			}
		} else {
			$data['products'] = array();
		}
		
		$data['token'] = $this->session->data['token'];
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}
								
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
        $data['sort_category'] = $this->url->link('extension/module/product_sort_orders', 'token=' . $this->session->data['token'] . '&sort=p2c.category' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('extension/module/product_sort_orders', 'token=' . $this->session->data['token'] . '&sort=p2co.sort_order,p.sort_order' . $url, 'SSL');
		
		$data['sort_name'] = $this->url->link('extension/module/product_sort_orders', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
		$data['sort_model'] = $this->url->link('extension/module/product_sort_orders', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$data['sort_price'] = $this->url->link('extension/module/product_sort_orders', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$data['sort_quantity'] = $this->url->link('extension/module/product_sort_orders', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('extension/module/product_sort_orders', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		
		$url = '';

		// Add
        if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}
        // End add
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['column_image'] = $this->language->get('column_image');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_name'] = $this->language->get('column_name');	
		$data['column_category'] = $this->language->get('column_category');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_edit'] = $this->language->get('button_edit');
		
		$data['filter_category'] = $filter_category;
		
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/product_sort_orders.tpl', $data));
  	}
	
	public function save() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->model('extension/module/product_sort_orders');
			$this->model_extension_module_product_sort_orders->saveSortOrders($this->request->post);
			
			$this->load->language('extension/module/product_sort_orders');
			$this->session->data['success'] = $this->language->get('save_success');
		}
		$this->index();
  	}
	
	public function install() {
		$this->load->model('extension/module/product_sort_orders');
		$this->model_extension_module_product_sort_orders->install();
		
		$this->load->language('extension/module/product_sort_orders');
		$this->session->data['success'] = $this->language->get('warning_install');
	}
	
	public function uninstall() {
		$this->load->model('extension/module/product_sort_orders');
		$this->model_extension_module_product_sort_orders->uninstall();
		
		$this->load->language('extension/module/product_sort_orders');
		$this->session->data['success'] = $this->language->get('warning_uninstall');
	}
}
?>