<?php
class ControllerExtensionBganycombi extends Controller {
	private $error = array();  
	private $modpath = 'extension/bganycombi';
	private $modtpl_list = 'extension/bganycombi_list.tpl';
	private $modtpl_form = 'extension/bganycombi_form.tpl';	
	private $modssl = 'SSL';
	private $token_str = ''; 
	
	public function __construct($registry) {
		parent::__construct($registry);
 		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3') { 
 			$this->modpath = 'extension/bganycombi';
 			$this->modtpl_list = 'extension/bganycombi_list';
			$this->modtpl_form = 'extension/bganycombi_form';	 
			$this->modtpl_mail = 'extension/bganycombi_sendquotemail';
 		} else if(substr(VERSION,0,3)=='2.2') {
 			$this->modtpl_list = 'extension/bganycombi_list';
			$this->modtpl_form = 'extension/bganycombi_form';	 
		} 
		 
		if(substr(VERSION,0,3)>='3.0') { 
 			$this->token_str = 'user_token=' . $this->session->data['user_token'];
		} else {
			$this->token_str = 'token=' . $this->session->data['token'];
		}
		
		if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') { 
			$this->modssl = true;
		} 
 	} 

	public function index() {
		$data = $this->load->language($this->modpath);

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model($this->modpath);
 
		$this->getList();
	}

	public function add() {
		$data = $this->load->language($this->modpath);

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model($this->modpath);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_bganycombi->addbganycombi($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			} 
 			
			if (isset($this->request->get['filter_discount_type'])) {
				$url .= '&filter_discount_type=' . $this->request->get['filter_discount_type'];
			}
			
			if (isset($this->request->get['filter_buyqty'])) {
				$url .= '&filter_buyqty=' . $this->request->get['filter_buyqty'];
			}
			
			if (isset($this->request->get['filter_getqty'])) {
				$url .= '&filter_getqty=' . $this->request->get['filter_getqty'];
			}
			
 			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
 			
			if (isset($this->request->get['filter_buyproduct_name']) && isset($this->request->get['filter_buyproduct_id'])) {
				$url .= '&filter_buyproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_buyproduct_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_buyproduct_id=' . $this->request->get['filter_buyproduct_id'];
			}
			
			if (isset($this->request->get['filter_buycategory_name']) && isset($this->request->get['filter_buycategory_id'])) {
				$url .= '&filter_buycategory_name=' . urlencode(html_entity_decode($this->request->get['filter_buycategory_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_buycategory_id=' . $this->request->get['filter_buycategory_id'];
 			}
			
			if (isset($this->request->get['filter_buymanufacturer_name']) && isset($this->request->get['filter_buymanufacturer_id'])) {
				$url .= '&filter_buymanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_buymanufacturer_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_buymanufacturer_id=' . $this->request->get['filter_buymanufacturer_id'];
			}
			
			if (isset($this->request->get['filter_getproduct_name']) && isset($this->request->get['filter_getproduct_id'])) {
				$url .= '&filter_getproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_getproduct_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_getproduct_id=' . $this->request->get['filter_getproduct_id'];
			}
			
			if (isset($this->request->get['filter_getcategory_name']) && isset($this->request->get['filter_getcategory_id'])) {
				$url .= '&filter_getcategory_name=' . urlencode(html_entity_decode($this->request->get['filter_getcategory_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_getcategory_id=' . $this->request->get['filter_getcategory_id'];
 			}
			
			if (isset($this->request->get['filter_getmanufacturer_name']) && isset($this->request->get['filter_getmanufacturer_id'])) {
				$url .= '&filter_getmanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_getmanufacturer_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_getmanufacturer_id=' . $this->request->get['filter_getmanufacturer_id'];
			} 

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link($this->modpath, $this->token_str . $url, $this->modssl));
		}

		$this->getForm();
	}

	public function edit() {
		$data = $this->load->language($this->modpath);

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model($this->modpath);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_bganycombi->editbganycombi($this->request->get['bganycombi_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}
			
			if (isset($this->request->get['filter_discount_type'])) {
				$url .= '&filter_discount_type=' . $this->request->get['filter_discount_type'];
			}
			
			if (isset($this->request->get['filter_buyqty'])) {
				$url .= '&filter_buyqty=' . $this->request->get['filter_buyqty'];
			}
			
			if (isset($this->request->get['filter_getqty'])) {
				$url .= '&filter_getqty=' . $this->request->get['filter_getqty'];
			}
			
			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
 			
			if (isset($this->request->get['filter_buyproduct_name']) && isset($this->request->get['filter_buyproduct_id'])) {
				$url .= '&filter_buyproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_buyproduct_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_buyproduct_id=' . $this->request->get['filter_buyproduct_id'];
			}
			
			if (isset($this->request->get['filter_buycategory_name']) && isset($this->request->get['filter_buycategory_id'])) {
				$url .= '&filter_buycategory_name=' . urlencode(html_entity_decode($this->request->get['filter_buycategory_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_buycategory_id=' . $this->request->get['filter_buycategory_id'];
 			}
			
			if (isset($this->request->get['filter_buymanufacturer_name']) && isset($this->request->get['filter_buymanufacturer_id'])) {
				$url .= '&filter_buymanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_buymanufacturer_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_buymanufacturer_id=' . $this->request->get['filter_buymanufacturer_id'];
			}
			
			if (isset($this->request->get['filter_getproduct_name']) && isset($this->request->get['filter_getproduct_id'])) {
				$url .= '&filter_getproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_getproduct_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_getproduct_id=' . $this->request->get['filter_getproduct_id'];
			}
			
			if (isset($this->request->get['filter_getcategory_name']) && isset($this->request->get['filter_getcategory_id'])) {
				$url .= '&filter_getcategory_name=' . urlencode(html_entity_decode($this->request->get['filter_getcategory_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_getcategory_id=' . $this->request->get['filter_getcategory_id'];
 			}
			
			if (isset($this->request->get['filter_getmanufacturer_name']) && isset($this->request->get['filter_getmanufacturer_id'])) {
				$url .= '&filter_getmanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_getmanufacturer_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_getmanufacturer_id=' . $this->request->get['filter_getmanufacturer_id'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link($this->modpath, $this->token_str . $url, $this->modssl));
		}

		$this->getForm();
	}

	public function delete() {
		$data = $this->load->language($this->modpath);

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model($this->modpath);

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $bganycombi_id) {
				$this->model_extension_bganycombi->deletebganycombi($bganycombi_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}
			
			if (isset($this->request->get['filter_discount_type'])) {
				$url .= '&filter_discount_type=' . $this->request->get['filter_discount_type'];
			}
			
			if (isset($this->request->get['filter_buyqty'])) {
				$url .= '&filter_buyqty=' . $this->request->get['filter_buyqty'];
			}
			
			if (isset($this->request->get['filter_getqty'])) {
				$url .= '&filter_getqty=' . $this->request->get['filter_getqty'];
			}
			
			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
 			
			if (isset($this->request->get['filter_buyproduct_name']) && isset($this->request->get['filter_buyproduct_id'])) {
				$url .= '&filter_buyproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_buyproduct_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_buyproduct_id=' . $this->request->get['filter_buyproduct_id'];
			}
			
			if (isset($this->request->get['filter_buycategory_name']) && isset($this->request->get['filter_buycategory_id'])) {
				$url .= '&filter_buycategory_name=' . urlencode(html_entity_decode($this->request->get['filter_buycategory_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_buycategory_id=' . $this->request->get['filter_buycategory_id'];
 			}
			
			if (isset($this->request->get['filter_buymanufacturer_name']) && isset($this->request->get['filter_buymanufacturer_id'])) {
				$url .= '&filter_buymanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_buymanufacturer_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_buymanufacturer_id=' . $this->request->get['filter_buymanufacturer_id'];
			}
			
			if (isset($this->request->get['filter_getproduct_name']) && isset($this->request->get['filter_getproduct_id'])) {
				$url .= '&filter_getproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_getproduct_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_getproduct_id=' . $this->request->get['filter_getproduct_id'];
			}
			
			if (isset($this->request->get['filter_getcategory_name']) && isset($this->request->get['filter_getcategory_id'])) {
				$url .= '&filter_getcategory_name=' . urlencode(html_entity_decode($this->request->get['filter_getcategory_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_getcategory_id=' . $this->request->get['filter_getcategory_id'];
 			}
			
			if (isset($this->request->get['filter_getmanufacturer_name']) && isset($this->request->get['filter_getmanufacturer_id'])) {
				$url .= '&filter_getmanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_getmanufacturer_name'], ENT_QUOTES, 'UTF-8'));
				$url .= '&filter_getmanufacturer_id=' . $this->request->get['filter_getmanufacturer_id'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link($this->modpath, $this->token_str . $url, $this->modssl));
		}

		$this->getList();
	}

	protected function getList() {
		$data = $this->load->language($this->modpath);
 		
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
		
		$filter_customer_group_id = null;
		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		}
		
		if (isset($this->request->get['filter_discount_type'])) {
			$filter_discount_type = $this->request->get['filter_discount_type'];
		} else {
			$filter_discount_type = null;
		} 
		
		if (isset($this->request->get['filter_buyqty'])) {
			$filter_buyqty = $this->request->get['filter_buyqty'];
		} else {
			$filter_buyqty = null;
		}
		
		if (isset($this->request->get['filter_getqty'])) {
			$filter_getqty = $this->request->get['filter_getqty'];
		} else {
			$filter_getqty = null;
		} 
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = null;
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = null;
		}
		
		$filter_buyproduct_name = null;
		$filter_buyproduct_id = null;
		if (isset($this->request->get['filter_buyproduct_name']) && isset($this->request->get['filter_buyproduct_id'])) {
			$filter_buyproduct_name = $this->request->get['filter_buyproduct_name'];
			$filter_buyproduct_id = $this->request->get['filter_buyproduct_id'];
		}
		
		$filter_buycategory_name = null;
		$filter_buycategory_id = null;
		if (isset($this->request->get['filter_buycategory_name']) && isset($this->request->get['filter_buycategory_id'])) {
			$filter_buycategory_name = $this->request->get['filter_buycategory_name'];
			$filter_buycategory_id = $this->request->get['filter_buycategory_id'];
		}
		
		$filter_buymanufacturer_name = null;
		$filter_buymanufacturer_id = null;
		if (isset($this->request->get['filter_buymanufacturer_name']) && isset($this->request->get['filter_buymanufacturer_id'])) {
			$filter_buymanufacturer_name = $this->request->get['filter_buymanufacturer_name'];
			$filter_buymanufacturer_id = $this->request->get['filter_buymanufacturer_id'];
		}
		
		$filter_getproduct_name = null;
		$filter_getproduct_id = null;
		if (isset($this->request->get['filter_getproduct_name']) && isset($this->request->get['filter_getproduct_id'])) {
			$filter_getproduct_name = $this->request->get['filter_getproduct_name'];
			$filter_getproduct_id = $this->request->get['filter_getproduct_id'];
		}
		
		$filter_getcategory_name = null;
		$filter_getcategory_id = null;
		if (isset($this->request->get['filter_getcategory_name']) && isset($this->request->get['filter_getcategory_id'])) {
			$filter_getcategory_name = $this->request->get['filter_getcategory_name'];
			$filter_getcategory_id = $this->request->get['filter_getcategory_id'];
		}
		
		$filter_getmanufacturer_name = null;
		$filter_getmanufacturer_id = null;
		if (isset($this->request->get['filter_getmanufacturer_name']) && isset($this->request->get['filter_getmanufacturer_id'])) {
			$filter_getmanufacturer_name = $this->request->get['filter_getmanufacturer_name'];
			$filter_getmanufacturer_id = $this->request->get['filter_getmanufacturer_id'];
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'bganycombi_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		} 
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
		
		if (isset($this->request->get['filter_discount_type'])) {
			$url .= '&filter_discount_type=' . $this->request->get['filter_discount_type'];
		} 
		
		if (isset($this->request->get['filter_buyqty'])) {
			$url .= '&filter_buyqty=' . $this->request->get['filter_buyqty'];
		}
		
		if (isset($this->request->get['filter_getqty'])) {
			$url .= '&filter_getqty=' . $this->request->get['filter_getqty'];
		} 
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_buyproduct_name']) && isset($this->request->get['filter_buyproduct_id'])) {
			$url .= '&filter_buyproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_buyproduct_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_buyproduct_id=' . $this->request->get['filter_buyproduct_id'];
		}
		
		if (isset($this->request->get['filter_buycategory_name']) && isset($this->request->get['filter_buycategory_id'])) {
			$url .= '&filter_buycategory_name=' . urlencode(html_entity_decode($this->request->get['filter_buycategory_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_buycategory_id=' . $this->request->get['filter_buycategory_id'];
		}
		
		if (isset($this->request->get['filter_buymanufacturer_name']) && isset($this->request->get['filter_buymanufacturer_id'])) {
			$url .= '&filter_buymanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_buymanufacturer_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_buymanufacturer_id=' . $this->request->get['filter_buymanufacturer_id'];
		}
		
		if (isset($this->request->get['filter_getproduct_name']) && isset($this->request->get['filter_getproduct_id'])) {
			$url .= '&filter_getproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_getproduct_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_getproduct_id=' . $this->request->get['filter_getproduct_id'];
		}
		
		if (isset($this->request->get['filter_getcategory_name']) && isset($this->request->get['filter_getcategory_id'])) {
			$url .= '&filter_getcategory_name=' . urlencode(html_entity_decode($this->request->get['filter_getcategory_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_getcategory_id=' . $this->request->get['filter_getcategory_id'];
		}
		
		if (isset($this->request->get['filter_getmanufacturer_name']) && isset($this->request->get['filter_getmanufacturer_id'])) {
			$url .= '&filter_getmanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_getmanufacturer_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_getmanufacturer_id=' . $this->request->get['filter_getmanufacturer_id'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if(substr(VERSION,0,3)>='3.0') { 
			$data['user_token'] = $this->session->data['user_token'];
		} else {
			$data['token'] = $this->session->data['token'];
		}
  		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->token_str, $this->modssl)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link($this->modpath, $this->token_str . $url, $this->modssl)
		);

		$data['add'] = $this->url->link($this->modpath.'/add', $this->token_str . $url, $this->modssl);
		$data['delete'] = $this->url->link($this->modpath.'/delete', $this->token_str . $url, $this->modssl);
		
		$data['checkcolumns'] = array('buyqty', 'buyproduct', 'buycategory', 'buymanufacturer', 'getqty', 'getproduct', 'getcategory', 'getmanufacturer', 'discount_type', 'customer_group', 'date_start', 'date_end');
		
		$data['check_head_columns'] = array('buyqty' => $data['entry_buyqty'], 'buyproduct' => $data['entry_buyproduct'], 'buycategory' => $data['entry_buycategory'], 'buymanufacturer' => $data['entry_buymanufacturer'], 'getqty' => $data['entry_getqty'], 'getproduct' => $data['entry_getproduct'], 'getcategory' => $data['entry_getcategory'], 'getmanufacturer' => $data['entry_getmanufacturer'], 'discount_type' => $data['entry_discount_type'], 'customer_group' => $data['entry_customer_group'], 'date_start' => $data['entry_date_start'], 'date_end' => $data['entry_date_end'] );
		
		foreach($data['checkcolumns'] as $chkcol) {
			$this->session->data['check_columns'][$chkcol] = isset($this->session->data['check_columns'][$chkcol]) ? $this->session->data['check_columns'][$chkcol] : 0;
		}
   		$data['check_columns'] = $this->session->data['check_columns'];
 
		$data['bganycombis'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_customer_group_id'	  => $filter_customer_group_id,
			'filter_discount_type'	  => $filter_discount_type,
			'filter_buyqty'	  => $filter_buyqty,
			'filter_getqty'	  => $filter_getqty,
 			'filter_date_start'	  => $filter_date_start,
			'filter_date_end'	  => $filter_date_end,
 			'filter_buyproduct_id'	  => $filter_buyproduct_id,
			'filter_buycategory_id'	  => $filter_buycategory_id,
			'filter_buymanufacturer_id'	  => $filter_buymanufacturer_id,
			'filter_getproduct_id'	  => $filter_getproduct_id,
			'filter_getcategory_id'	  => $filter_getcategory_id,
			'filter_getmanufacturer_id'	  => $filter_getmanufacturer_id,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$bganycombi_total = $this->model_extension_bganycombi->getTotalbganycombis($filter_data);

		$results = $this->model_extension_bganycombi->getbganycombis($filter_data);
		
		$data['customer_groups'] = $this->getCustomerGroups();
		
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');

		foreach ($results as $result) {
			$discount_type = '';
			if($result['discount_type'] == 1) {
				$discount_type = $this->language->get('text_free');
				$discount_value = $this->language->get('text_free');
			} else if($result['discount_type'] == 2) {
				$discount_type = $this->language->get('text_fixed_amount');
				$discount_value = $result['discount_value'];
			} else {
				$discount_type = $this->language->get('text_percentage');
				$discount_value = $result['discount_value'];
			}
			
			
			// buy
 			$buyproduct_data = array();
			$buyproducts = explode(",",$result['buyproduct']);
 			if($buyproducts) {
				foreach ($buyproducts as $product_id) {
					$info = $this->model_catalog_product->getProduct($product_id);
 					if ($info) {
						$buyproduct_data[$info['product_id']] = $info['name'];
					}
				}
			}
			
			$buycategory_data = array();
			$buycategories = explode(",",$result['buycategory']);
 			if($buycategories) {
				foreach ($buycategories as $category_id) {
					$info = $this->model_catalog_category->getCategory($category_id);
 					if ($info) {
						$buycategory_data[$info['category_id']] = ($info['path']) ? $info['path'] . ' &gt; ' . $info['name'] : $info['name'];
					}
				}
			}
			
			$buymanufacturer_data = array();
			$buymanufacturers = explode(",",$result['buymanufacturer']);
			//echo "<pre>"; print_r($manufacturers); exit;
 			if($buymanufacturers) {
				foreach ($buymanufacturers as $manufacturer_id) {
					$info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
 					if ($info) {
						$buymanufacturer_data[$info['manufacturer_id']] = $info['name'];
					}
				}
			}
			
			
			// get
			$getproduct_data = array();
			$getproducts = explode(",",$result['getproduct']);
 			if($getproducts) {
				foreach ($getproducts as $product_id) {
					$info = $this->model_catalog_product->getProduct($product_id);
 					if ($info) {
						$getproduct_data[$info['product_id']] = $info['name'];
					}
				}
			}
			
			$getcategory_data = array();
			$getcategories = explode(",",$result['getcategory']);
 			if($getcategories) {
				foreach ($getcategories as $category_id) {
					$info = $this->model_catalog_category->getCategory($category_id);
 					if ($info) {
						$getcategory_data[$info['category_id']] = ($info['path']) ? $info['path'] . ' &gt; ' . $info['name'] : $info['name'];
					}
				}
			}
			
			$getmanufacturer_data = array();
			$getmanufacturers = explode(",",$result['getmanufacturer']);
			//echo "<pre>"; print_r($manufacturers); exit;
 			if($getmanufacturers) {
				foreach ($getmanufacturers as $manufacturer_id) {
					$info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
 					if ($info) {
						$getmanufacturer_data[$info['manufacturer_id']] = $info['name'];
					}
				}
			}
			
			
			$customer_group_data = array();
			$customer_groups = explode(",",$result['customer_group']);
 			if($customer_groups) {
				foreach ($customer_groups as $cgid) {
					$info = $this->getCustomerGroup_id($cgid);
 					if ($info) {	
 						$customer_group_data[$info['customer_group_id']] = $info['name'];
					}
				}
			}
			
			$data['bganycombis'][] = array(
				'bganycombi_id' => $result['bganycombi_id'],
				'name'        => $result['name'],
				'discount_type' => $discount_type,
				'discount_value' => $discount_value,
				'buyqty' => $result['buyqty'],
				'getqty' => $result['getqty'],
				'buyproduct_data' => implode("<br>",$buyproduct_data),
				'buycategory_data' => implode("<br>",$buycategory_data),
				'buymanufacturer_data' => implode("<br>",$buymanufacturer_data),
				'getproduct_data' => implode("<br>",$getproduct_data),
				'getcategory_data' => implode("<br>",$getcategory_data),
				'getmanufacturer_data' => implode("<br>",$getmanufacturer_data),
				'customer_group_data' => implode("<br>",$customer_group_data),
 				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'status_int' => $result['status'],
				'date_start'    => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'    => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'edit'        => $this->url->link($this->modpath.'/edit', $this->token_str . '&bganycombi_id=' . $result['bganycombi_id'] . $url, $this->modssl),
				'delete'      => $this->url->link($this->modpath.'/delete', $this->token_str . '&bganycombi_id=' . $result['bganycombi_id'] . $url, $this->modssl)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_discount_type'] = $this->language->get('column_discount_type');
		$data['column_discount_value'] = $this->language->get('column_discount_value');
		$data['column_buyqty'] = $this->language->get('column_buyqty');
		$data['column_getqty'] = $this->language->get('column_getqty');
 		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
 		$data['column_action'] = $this->language->get('column_action');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_discount_type'] = $this->language->get('entry_discount_type');
		$data['text_free'] = $this->language->get('text_free');
		$data['text_fixed_amount'] = $this->language->get('text_fixed_amount');
		$data['text_percentage'] = $this->language->get('text_percentage');			
		$data['entry_buyproduct'] = $this->language->get('entry_buyproduct');
		$data['entry_buycategory'] = $this->language->get('entry_buycategory');
		$data['entry_buymanufacturer'] = $this->language->get('entry_buymanufacturer');
		$data['entry_getproduct'] = $this->language->get('entry_getproduct');
		$data['entry_getcategory'] = $this->language->get('entry_getcategory');
		$data['entry_getmanufacturer'] = $this->language->get('entry_getmanufacturer');
		$data['button_filter'] = $this->language->get('button_filter');
		

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_rebuild'] = $this->language->get('button_rebuild');

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
		
		if (isset($this->request->get['filter_discount_type'])) {
			$url .= '&filter_discount_type=' . $this->request->get['filter_discount_type'];
		}
		
		if (isset($this->request->get['filter_buyqty'])) {
			$url .= '&filter_buyqty=' . $this->request->get['filter_buyqty'];
		}
		
		if (isset($this->request->get['filter_getqty'])) {
			$url .= '&filter_getqty=' . $this->request->get['filter_getqty'];
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_buyproduct_name']) && isset($this->request->get['filter_buyproduct_id'])) {
			$url .= '&filter_buyproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_buyproduct_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_buyproduct_id=' . $this->request->get['filter_buyproduct_id'];
		}
		
		if (isset($this->request->get['filter_buycategory_name']) && isset($this->request->get['filter_buycategory_id'])) {
			$url .= '&filter_buycategory_name=' . urlencode(html_entity_decode($this->request->get['filter_buycategory_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_buycategory_id=' . $this->request->get['filter_buycategory_id'];
		}
		
		if (isset($this->request->get['filter_buymanufacturer_name']) && isset($this->request->get['filter_buymanufacturer_id'])) {
			$url .= '&filter_buymanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_buymanufacturer_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_buymanufacturer_id=' . $this->request->get['filter_buymanufacturer_id'];
		}
		
		if (isset($this->request->get['filter_getproduct_name']) && isset($this->request->get['filter_getproduct_id'])) {
			$url .= '&filter_getproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_getproduct_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_getproduct_id=' . $this->request->get['filter_getproduct_id'];
		}
		
		if (isset($this->request->get['filter_getcategory_name']) && isset($this->request->get['filter_getcategory_id'])) {
			$url .= '&filter_getcategory_name=' . urlencode(html_entity_decode($this->request->get['filter_getcategory_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_getcategory_id=' . $this->request->get['filter_getcategory_id'];
		}
		
		if (isset($this->request->get['filter_getmanufacturer_name']) && isset($this->request->get['filter_getmanufacturer_id'])) {
			$url .= '&filter_getmanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_getmanufacturer_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_getmanufacturer_id=' . $this->request->get['filter_getmanufacturer_id'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link($this->modpath, $this->token_str . '&sort=name' . $url, $this->modssl);
 		$data['sort_status'] = $this->url->link($this->modpath, $this->token_str . '&sort=status' . $url, $this->modssl);
 
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
		
		if (isset($this->request->get['filter_discount_type'])) {
			$url .= '&filter_discount_type=' . $this->request->get['filter_discount_type'];
		}
		
		if (isset($this->request->get['filter_buyqty'])) {
			$url .= '&filter_buyqty=' . $this->request->get['filter_buyqty'];
		}
		
		if (isset($this->request->get['filter_getqty'])) {
			$url .= '&filter_getqty=' . $this->request->get['filter_getqty'];
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_buyproduct_name']) && isset($this->request->get['filter_buyproduct_id'])) {
			$url .= '&filter_buyproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_buyproduct_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_buyproduct_id=' . $this->request->get['filter_buyproduct_id'];
		}
		
		if (isset($this->request->get['filter_buycategory_name']) && isset($this->request->get['filter_buycategory_id'])) {
			$url .= '&filter_buycategory_name=' . urlencode(html_entity_decode($this->request->get['filter_buycategory_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_buycategory_id=' . $this->request->get['filter_buycategory_id'];
		}
		
		if (isset($this->request->get['filter_buymanufacturer_name']) && isset($this->request->get['filter_buymanufacturer_id'])) {
			$url .= '&filter_buymanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_buymanufacturer_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_buymanufacturer_id=' . $this->request->get['filter_buymanufacturer_id'];
		}
		
		if (isset($this->request->get['filter_getproduct_name']) && isset($this->request->get['filter_getproduct_id'])) {
			$url .= '&filter_getproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_getproduct_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_getproduct_id=' . $this->request->get['filter_getproduct_id'];
		}
		
		if (isset($this->request->get['filter_getcategory_name']) && isset($this->request->get['filter_getcategory_id'])) {
			$url .= '&filter_getcategory_name=' . urlencode(html_entity_decode($this->request->get['filter_getcategory_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_getcategory_id=' . $this->request->get['filter_getcategory_id'];
		}
		
		if (isset($this->request->get['filter_getmanufacturer_name']) && isset($this->request->get['filter_getmanufacturer_id'])) {
			$url .= '&filter_getmanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_getmanufacturer_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_getmanufacturer_id=' . $this->request->get['filter_getmanufacturer_id'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $bganycombi_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link($this->modpath, $this->token_str . $url . '&page={page}', $this->modssl);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($bganycombi_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($bganycombi_total - $this->config->get('config_limit_admin'))) ? $bganycombi_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $bganycombi_total, ceil($bganycombi_total / $this->config->get('config_limit_admin')));
 		
		$data['filter_name'] = $filter_name;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_discount_type'] = $filter_discount_type;
		$data['filter_buyqty'] = $filter_buyqty;
		$data['filter_getqty'] = $filter_getqty;
 		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
 		$data['filter_buyproduct_id'] = $filter_buyproduct_id;
		$data['filter_buyproduct_name'] = $filter_buyproduct_name;
		$data['filter_buycategory_id'] = $filter_buycategory_id;
		$data['filter_buycategory_name'] = $filter_buycategory_name;
		$data['filter_buymanufacturer_id'] = $filter_buymanufacturer_id;		
		$data['filter_buymanufacturer_name'] = $filter_buymanufacturer_name;
		$data['filter_getproduct_id'] = $filter_getproduct_id;
		$data['filter_getproduct_name'] = $filter_getproduct_name;
		$data['filter_getcategory_id'] = $filter_getcategory_id;
		$data['filter_getcategory_name'] = $filter_getcategory_name;
		$data['filter_getmanufacturer_id'] = $filter_getmanufacturer_id;		
		$data['filter_getmanufacturer_name'] = $filter_getmanufacturer_name;
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($this->modtpl_list, $data));
	}

	protected function getForm() {
		$data = $this->load->language($this->modpath);
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['bganycombi_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['text_free'] = $this->language->get('text_free');
		$data['text_fixed_amount'] = $this->language->get('text_fixed_amount');
		$data['text_percentage'] = $this->language->get('text_percentage');	
		$data['text_above_desc_tab'] = $this->language->get('text_above_desc_tab');	
		$data['text_at_popup'] = $this->language->get('text_at_popup');	
		$data['text_at_desc_tab'] = $this->language->get('text_at_desc_tab');	 
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_buyfrom'] = $this->language->get('tab_buyfrom');
		$data['tab_getfrom'] = $this->language->get('tab_getfrom');
		$data['tab_displayoffer'] = $this->language->get('tab_displayoffer');
		
		$data['button_remove'] = $this->language->get('button_remove');		
		$data['button_add_discount'] = $this->language->get('button_add_discount');		
  
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_discount_type'] = $this->language->get('entry_discount_type');
		$data['entry_discount_value'] = $this->language->get('entry_discount_value'); 
		$data['entry_buyqty'] = $this->language->get('entry_buyqty');
		$data['entry_getqty'] = $this->language->get('entry_getqty');
		
		$data['entry_ribbontext'] = $this->language->get('entry_ribbontext');
		$data['entry_ordertotaltext'] = $this->language->get('entry_ordertotaltext');
		$data['entry_display_offer_at'] = $this->language->get('entry_display_offer_at');
 		$data['entry_offer_heading_text'] = $this->language->get('entry_offer_heading_text');	
		$data['entry_offer_content'] = $this->language->get('entry_offer_content');	
		
		$data['entry_buyproduct'] = $this->language->get('entry_buyproduct');
		$data['entry_buycategory'] = $this->language->get('entry_buycategory');
		$data['entry_buymanufacturer'] = $this->language->get('entry_buymanufacturer');
		$data['entry_getproduct'] = $this->language->get('entry_getproduct');
		$data['entry_getcategory'] = $this->language->get('entry_getcategory');
		$data['entry_getmanufacturer'] = $this->language->get('entry_getmanufacturer');		
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
 		$data['entry_status'] = $this->language->get('entry_status');
 		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
 
  		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		} 
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}
		
		if (isset($this->request->get['filter_discount_type'])) {
			$url .= '&filter_discount_type=' . $this->request->get['filter_discount_type'];
		}
		
		if (isset($this->request->get['filter_buyqty'])) {
			$url .= '&filter_buyqty=' . $this->request->get['filter_buyqty'];
		}
		
		if (isset($this->request->get['filter_getqty'])) {
			$url .= '&filter_getqty=' . $this->request->get['filter_getqty'];
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_buyproduct_name']) && isset($this->request->get['filter_buyproduct_id'])) {
			$url .= '&filter_buyproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_buyproduct_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_buyproduct_id=' . $this->request->get['filter_buyproduct_id'];
		}
		
		if (isset($this->request->get['filter_buycategory_name']) && isset($this->request->get['filter_buycategory_id'])) {
			$url .= '&filter_buycategory_name=' . urlencode(html_entity_decode($this->request->get['filter_buycategory_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_buycategory_id=' . $this->request->get['filter_buycategory_id'];
		}
		
		if (isset($this->request->get['filter_buymanufacturer_name']) && isset($this->request->get['filter_buymanufacturer_id'])) {
			$url .= '&filter_buymanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_buymanufacturer_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_buymanufacturer_id=' . $this->request->get['filter_buymanufacturer_id'];
		}
		
		if (isset($this->request->get['filter_getproduct_name']) && isset($this->request->get['filter_getproduct_id'])) {
			$url .= '&filter_getproduct_name=' . urlencode(html_entity_decode($this->request->get['filter_getproduct_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_getproduct_id=' . $this->request->get['filter_getproduct_id'];
		}
		
		if (isset($this->request->get['filter_getcategory_name']) && isset($this->request->get['filter_getcategory_id'])) {
			$url .= '&filter_getcategory_name=' . urlencode(html_entity_decode($this->request->get['filter_getcategory_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_getcategory_id=' . $this->request->get['filter_getcategory_id'];
		}
		
		if (isset($this->request->get['filter_getmanufacturer_name']) && isset($this->request->get['filter_getmanufacturer_id'])) {
			$url .= '&filter_getmanufacturer_name=' . urlencode(html_entity_decode($this->request->get['filter_getmanufacturer_name'], ENT_QUOTES, 'UTF-8'));
			$url .= '&filter_getmanufacturer_id=' . $this->request->get['filter_getmanufacturer_id'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $this->token_str, $this->modssl)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link($this->modpath, $this->token_str . $url, $this->modssl)
		);

		if (!isset($this->request->get['bganycombi_id'])) {
			$data['action'] = $this->url->link($this->modpath.'/add', $this->token_str . $url, $this->modssl);
		} else {
			$data['action'] = $this->url->link($this->modpath.'/edit', $this->token_str . '&bganycombi_id=' . $this->request->get['bganycombi_id'] . $url, $this->modssl);
		}

		$data['cancel'] = $this->url->link($this->modpath, $this->token_str . $url, $this->modssl);

		if (isset($this->request->get['bganycombi_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$bganycombi_info = $this->model_extension_bganycombi->getbganycombi($this->request->get['bganycombi_id']);
		}

 		if(substr(VERSION,0,3)>='3.0') { 
 			$data['user_token'] = $this->session->data['user_token']; 
		} else {
			$data['token'] = $this->session->data['token'];
		}

		$this->load->model('localisation/language');
  		$languages = $this->model_localisation_language->getLanguages();
		foreach($languages as $language) {
			if(substr(VERSION,0,3)>='3.0' || substr(VERSION,0,3)=='2.3' || substr(VERSION,0,3)=='2.2') {
				$imgsrc = "language/".$language['code']."/".$language['code'].".png";
			} else {
				$imgsrc = "view/image/flags/".$language['image'];
			}
			$data['languages'][] = array("language_id" => $language['language_id'], "name" => $language['name'], "imgsrc" => $imgsrc);
		}
 
  		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($bganycombi_info)) {
			$data['name'] = $bganycombi_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['discount_type'])) {
			$data['discount_type'] = $this->request->post['discount_type'];
		} elseif (!empty($bganycombi_info)) {
			$data['discount_type'] = $bganycombi_info['discount_type'];
		} else {
			$data['discount_type'] = 1;
		}
		
		if (isset($this->request->post['discount_value'])) {
			$data['discount_value'] = $this->request->post['discount_value'];
		} elseif (!empty($bganycombi_info)) {
			$data['discount_value'] = $bganycombi_info['discount_value'];
		} else {
			$data['discount_value'] = 0;
		}
		
		if (isset($this->request->post['buyqty'])) {
			$data['buyqty'] = $this->request->post['buyqty'];
		} elseif (!empty($bganycombi_info)) {
			$data['buyqty'] = $bganycombi_info['buyqty'];
		} else {
			$data['buyqty'] = 0;
		}
		
		if (isset($this->request->post['getqty'])) {
			$data['getqty'] = $this->request->post['getqty'];
		} elseif (!empty($bganycombi_info)) {
			$data['getqty'] = $bganycombi_info['getqty'];
		} else {
			$data['getqty'] = 0;
		}
		
		if (isset($this->request->post['ribbontext'])) {
			$data['ribbontext'] = $this->request->post['ribbontext'];
		} elseif (!empty($bganycombi_info)) {
			$data['ribbontext'] = json_decode($bganycombi_info['ribbontext'], true);
		} else {
			$data['ribbontext'] = array();
		} 
		
		if (isset($this->request->post['ordertotaltext'])) {
			$data['ordertotaltext'] = $this->request->post['ordertotaltext'];
		} elseif (!empty($bganycombi_info)) {
			$data['ordertotaltext'] = json_decode($bganycombi_info['ordertotaltext'], true);
		} else {
			$data['ordertotaltext'] = array();
		} 
		
		if (isset($this->request->post['display_offer_at'])) {
			$data['display_offer_at'] = $this->request->post['display_offer_at'];
		} elseif (!empty($bganycombi_info)) {
			$data['display_offer_at'] = $bganycombi_info['display_offer_at'];
		} else {
			$data['display_offer_at'] = 1;
		}
		
		if (isset($this->request->post['offer_heading_text'])) {
			$data['offer_heading_text'] = $this->request->post['offer_heading_text'];
		} elseif (!empty($bganycombi_info)) {
			$data['offer_heading_text'] = json_decode($bganycombi_info['offer_heading_text'], true);
		} else {
			$data['offer_heading_text'] = array();
		} 
		
		if (isset($this->request->post['offer_content'])) {
			$data['offer_content'] = $this->request->post['offer_content'];
		} elseif (!empty($bganycombi_info)) {
			$data['offer_content'] = json_decode($bganycombi_info['offer_content'], true);
		} else {
			$data['offer_content'] = array();
		} 
		
		// Buy 
		//product
 		if (isset($this->request->post['buyproduct'])) {
			$data['buyproduct'] = $this->request->post['buyproduct'];
		} elseif (!empty($bganycombi_info)) {
			$data['buyproduct'] = $bganycombi_info['buyproduct'];
			$data['buyproduct'] = ($bganycombi_info['buyproduct']) ? explode(",",$bganycombi_info['buyproduct']) : array();
 		} else {
			$data['buyproduct'] = array();
		}
		
		$data['buyproduct_data'] = array();
		$this->load->model('catalog/product');
		
 		if($data['buyproduct']) {
 			foreach ($data['buyproduct'] as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
	
				if ($product_info) {
					$data['buyproduct_data'][] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
					);
				}
			}
		}
		
		// category
 		if (isset($this->request->post['buycategory'])) {
			$data['buycategory'] = $this->request->post['buycategory'];
		} elseif (!empty($bganycombi_info)) {
			$data['buycategory'] = $bganycombi_info['buycategory'];
 			$data['buycategory'] = ($bganycombi_info['buycategory']) ? explode(",",$bganycombi_info['buycategory']) : array();
		} else {
			$data['buycategory'] = array();
		}
		
		$data['buycategory_data'] = array();
		$this->load->model('catalog/category');
		
 		if($data['buycategory']) {
 			foreach ($data['buycategory'] as $category_id) {
				$category_info = $this->model_catalog_category->getCategory($category_id);
	
				if ($category_info) {
					$data['buycategory_data'][] = array(
						'category_id' => $category_info['category_id'],
						'name'       => $category_info['name']
					);
				}
			}
		}
 		
		// manufacturer
 		if (isset($this->request->post['buymanufacturer'])) {
			$data['buymanufacturer'] = $this->request->post['buymanufacturer'];
		} elseif (!empty($bganycombi_info)) {
			$data['buymanufacturer'] = $bganycombi_info['buymanufacturer'];
 			$data['buymanufacturer'] = ($bganycombi_info['buymanufacturer']) ? explode(",",$bganycombi_info['buymanufacturer']) : array();
		} else {
 			$data['buymanufacturer'] = array();
		}
		
		$data['buymanufacturer_data'] = array();
		$this->load->model('catalog/manufacturer');
		
 		if($data['buymanufacturer']) {
 			foreach ($data['buymanufacturer'] as $manufacturer_id) {
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
	
				if ($manufacturer_info) {
					$data['buymanufacturer_data'][] = array(
						'manufacturer_id' => $manufacturer_info['manufacturer_id'],
						'name'       => $manufacturer_info['name']
					);
				}
			}
		}
		
		// Get 
		//product
 		if (isset($this->request->post['getproduct'])) {
			$data['getproduct'] = $this->request->post['getproduct'];
		} elseif (!empty($bganycombi_info)) {
			$data['getproduct'] = $bganycombi_info['getproduct'];
			$data['getproduct'] = ($bganycombi_info['getproduct']) ? explode(",",$bganycombi_info['getproduct']) : array();
 		} else {
			$data['getproduct'] = array();
		}
		
		$data['getproduct_data'] = array();
		$this->load->model('catalog/product');
		
 		if($data['getproduct']) {
 			foreach ($data['getproduct'] as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
	
				if ($product_info) {
					$data['getproduct_data'][] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
					);
				}
			}
		}
		
		// category
 		if (isset($this->request->post['getcategory'])) {
			$data['getcategory'] = $this->request->post['getcategory'];
		} elseif (!empty($bganycombi_info)) {
			$data['getcategory'] = $bganycombi_info['getcategory'];
 			$data['getcategory'] = ($bganycombi_info['getcategory']) ? explode(",",$bganycombi_info['getcategory']) : array();
		} else {
			$data['getcategory'] = array();
		}
		
		$data['getcategory_data'] = array();
		$this->load->model('catalog/category');
		
 		if($data['getcategory']) {
 			foreach ($data['getcategory'] as $category_id) {
				$category_info = $this->model_catalog_category->getCategory($category_id);
	
				if ($category_info) {
					$data['getcategory_data'][] = array(
						'category_id' => $category_info['category_id'],
						'name'       => $category_info['name']
					);
				}
			}
		}
 		
		// manufacturer
 		if (isset($this->request->post['getmanufacturer'])) {
			$data['getmanufacturer'] = $this->request->post['getmanufacturer'];
		} elseif (!empty($bganycombi_info)) {
			$data['getmanufacturer'] = $bganycombi_info['getmanufacturer'];
 			$data['getmanufacturer'] = ($bganycombi_info['getmanufacturer']) ? explode(",",$bganycombi_info['getmanufacturer']) : array();
		} else {
 			$data['getmanufacturer'] = array();
		}
		
		$data['getmanufacturer_data'] = array();
		$this->load->model('catalog/manufacturer');
		
 		if($data['getmanufacturer']) {
 			foreach ($data['getmanufacturer'] as $manufacturer_id) {
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
	
				if ($manufacturer_info) {
					$data['getmanufacturer_data'][] = array(
						'manufacturer_id' => $manufacturer_info['manufacturer_id'],
						'name'       => $manufacturer_info['name']
					);
				}
			}
		}
		

		// store
		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['store'])) {
			$data['store'] = $this->request->post['store'];
		} elseif (!empty($bganycombi_info)) {
			$data['store'] = explode(",",$bganycombi_info['store']);
		} else {
			$data['store'] = array();
		}
		
		// customer_group
		$data['customer_groups'] = $this->getCustomerGroups();
		
		if (isset($this->request->post['customer_group'])) {
			$data['customer_group'] = $this->request->post['customer_group'];
		} elseif (!empty($bganycombi_info)) {
			$data['customer_group'] = explode(",",$bganycombi_info['customer_group']);
		} else {
			$data['customer_group'] = array();
		} 
		
		if (isset($this->request->post['date_start'])) {
			$data['date_start'] = $this->request->post['date_start'];
		} elseif (!empty($bganycombi_info)) {
			$data['date_start'] = ($bganycombi_info['date_start'] != '0000-00-00') ? $bganycombi_info['date_start'] : '';
		} else {
			$data['date_start'] = date('Y-m-d');
		}
		
		if (isset($this->request->post['date_end'])) {
			$data['date_end'] = $this->request->post['date_end'];
		} elseif (!empty($bganycombi_info)) {
			$data['date_end'] = ($bganycombi_info['date_end'] != '0000-00-00') ? $bganycombi_info['date_end'] : '';
		} else {
			$data['date_end'] = date('Y-m-d');
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($bganycombi_info)) {
			$data['status'] = $bganycombi_info['status'];
		} else {
			$data['status'] = true;
		}
 
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view($this->modtpl_form, $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', $this->modpath)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 2) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', $this->modpath)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	} 
	
	public function savestatus() { 
		if(isset($this->request->post['id']) && $this->request->post['id']) { 
			$status = (isset($this->request->post['status']) && $this->request->post['status']) ? 1 : 0;
			$this->db->query("UPDATE " . DB_PREFIX . "bganycombi SET status = '" . $this->db->escape($status) . "' WHERE bganycombi_id = '" . (int)$this->request->post['id'] . "'");
		}
	}
	
	public function savecheckcolumn() { 
		$json = 1;
		//unset($this->session->data['check_columns']);
		if(isset($this->request->post['name']) && $this->request->post['name']) { 
			$this->session->data['check_columns'][$this->request->post['name']] = $this->request->post['val'];
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getCustomerGroups($data = array()) {
 		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cgd.name ASC");
 		return $query->rows;
	} 
	public function getCustomerGroup_id($id) {
 		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group cg LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' and cg.customer_group_id = '".(int)$id."' ORDER BY cgd.name ASC");
 		return $query->row;
	} 
}
