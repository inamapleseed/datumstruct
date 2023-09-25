<?php
class ControllerExtensionModuleAddLinksToAdminMenu extends Controller {
	private $error = array(); 

	public function index() {   
		$this->load->language('extension/module/addLinksToAdminMenu');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['menu_title'] = $this->language->get('menu_title');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$this->check_the_tables();
		$this->document->setTitle($data['heading_title']);
        $this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$arr['module_addLinksToAdminMenu_status'] = $this->request->post['module_addLinksToAdminMenu_status'];
			$this->model_setting_setting->editSetting('module_addLinksToAdminMenu', $arr);
			$this->update_link_information($this->request->post);
			$this->session->data['success'] = 'Success: You have modified module Add Links To Admin Menu!';
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$addlinksmenurename = $this->config->get('addlinksmenurename');
		if ( (isset($addlinksmenurename)) && (!empty($addlinksmenurename)) ) {
			$data['addlinksmenurename'] = $addlinksmenurename;
		} else {
			$data['addlinksmenurename'] = $data['menu_title'];
		}

		$data['heading_title'] = $data['heading_title'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => 'Home',
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => 'Extension',
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text'      => 'Add Links To Admin Menu',
			'href'      => $this->url->link('extension/module/addLinksToAdminMenu', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('extension/module/addLinksToAdminMenu', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');

		$data['modules'] = array();

		$data['existing_links'] = $this->get_links();
		$data['existing_categories'] = $this->get_categories();

		if (isset($this->request->post['addLinksToAdminMenu_module'])) {
			$data['modules'] = $this->request->post['addLinksToAdminMenu_module'];
		}

		if (isset($this->request->post['module_addLinksToAdminMenu_status'])) {
			$data['module_addLinksToAdminMenu_status'] = $this->request->post['module_addLinksToAdminMenu_status'];
		} else {
			$data['module_addLinksToAdminMenu_status'] = $this->config->get('module_addLinksToAdminMenu_status');
		}

		$this->load->model('design/layout');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/addLinksToAdminMenu.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/addLinksToAdminMenu')) {
			$this->error['warning'] = 'Warning: You do not have permission to modify module Add Links To Admin Menu!';
		}

		return !$this->error;
	}

	protected function check_the_tables() {
		$query1 = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "imiw_links' ");
		$imiw_links_exist = count($query1->rows);

		if ($imiw_links_exist==0) {
			$this->db->query(" CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "imiw_links (id int(11) AUTO_INCREMENT, link_title varchar(255), link_href varchar(255), new_window int(3), category_id int(11), PRIMARY KEY (id) ) ");	
		}

			$linksHaveSortOrder = FALSE;
			  $result = $this->db->query( "DESCRIBE `".DB_PREFIX."imiw_links`;" );
			  foreach ($result->rows as $row) {
			     if ($row['Field'] == 'sort_order') {
			        $linksHaveSortOrder = TRUE;
			        break;
			     }
			  }

			  if ($linksHaveSortOrder==FALSE) {
			     $sql = "ALTER TABLE `".DB_PREFIX."imiw_links` ADD `sort_order` INT( 11 ) ";
			     $this->db->query( $sql );
			  }

		$query2 = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "imiw_categories' ");
		$imiw_categories_exist = count($query2->rows);

		if ($imiw_categories_exist==0) {
			$this->db->query(" CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "imiw_categories (id int(11) AUTO_INCREMENT, category_title varchar(255), PRIMARY KEY (id) ) ");	
		}

			$categoriesHaveSortOrder = FALSE;
			  $result = $this->db->query( "DESCRIBE `".DB_PREFIX."imiw_categories`;" );
			  foreach ($result->rows as $row) {
			     if ($row['Field'] == 'sort_order') {
			        $categoriesHaveSortOrder = TRUE;
			        break;
			     }
			  }

			  if ($categoriesHaveSortOrder==FALSE) {
			     $sql = "ALTER TABLE `".DB_PREFIX."imiw_categories` ADD `sort_order` INT( 11 ) ";
			     $this->db->query( $sql );
			  }

		return 1;
	}

	protected function get_categories() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "imiw_categories order by sort_order asc");
		return $query->rows;
	}

	protected function get_links() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "imiw_links order by category_id asc, sort_order asc");
		return $query->rows;
	}


	public function update_link_information($array) {

		$this->load->model('setting/setting');

		if (isset($array['addlinksmenurename'])) {
			$link_settings['addlinksmenurename'] = $array['addlinksmenurename'];
			$this->model_setting_setting->editSetting('addlinksmenu', $link_settings);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "imiw_categories ");

		if ( (isset($array['existing_category'])) && (is_array($array['existing_category'])) ) {

			foreach ($array['existing_category'] as $category) {

				if (strlen($category['category_title'])==0) { $cat_title = '-'; } else { $cat_title = $category['category_title']; }
				if ($category['sort_order']>0) { $category_sort_order = $category['sort_order']; } else { $category_sort_order = 0; }

				if (isset($category['category_id'])) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "imiw_categories (id, category_title, sort_order) VALUES (".$category['category_id'].", '".$cat_title."', ".$category_sort_order.") ");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "imiw_categories (category_title, sort_order) VALUES ('".$cat_title."', ".$category_sort_order.") ");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "imiw_links ");

			if ( (isset($array['existing_link'])) && (is_array($array['existing_link'])) ) {

				foreach ($array['existing_link'] as $link) {

					if ( (isset($link['new_window'])) && ($link['new_window']==1) ) {
						$new_window = 1;
					} else $new_window = 0;

					if ($link['sort_order']>0) {
						$sort_order = $link['sort_order'];
					} else $sort_order = 0;

					$this->db->query("INSERT INTO " . DB_PREFIX . "imiw_links (link_title, link_href, category_id, new_window, sort_order) VALUES ('".$link['link_title']."', '".$link['link_href']."', ".$link['category_id'].", ".$new_window.", ".$sort_order.") ");
				}

			}

		return 1;
	}

}
?>