<?php  
class ControllerExtensionModuleNcategory extends Controller {

	public function index() {
	
		$this->language->load('extension/module/ncategory');
		
    	$data['heading_title'] = $this->language->get('heading_title');
		
		$data['heading_ncat'] = $this->language->get('heading_ncat');
		
		$data['button_headlines'] = $this->language->get('button_headlines');
		
		$data['button_search'] = $this->language->get('button_search');
		
		$data['head_search'] = $this->language->get('head_search');
		
		$data['artkey'] = $this->language->get('artkey');
		
		$data['headlines'] = $this->url->link('news/ncategory');
		
		$data['search_url'] = $this->url->link('news/ncategory');

		if (isset($this->request->get['filter_name'])) {
			$data['filter_name'] = $this->request->get['filter_name'];
		} else {
			$data['filter_name'] = '';
		}
		
		if (isset($this->request->get['ncat'])) {
			$parts = explode('_', (string)$this->request->get['ncat']);
			$data['search_url'] .= '&ncat=' . implode('_', $parts);
		} else {
			$parts = array();
		}
		
		if (isset($parts[0])) {
			$data['ncategory_id'] = $parts[0];
		} else {
			$data['ncategory_id'] = 0;
		}
		
		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}
							
		$this->load->model('catalog/ncategory');
		
		$data['ncategories'] = array();

		$ncategories = $this->model_catalog_ncategory->getncategories(0);
		foreach ($ncategories as $ncategory) {
			$children_data = array();
			
			$children = $this->model_catalog_ncategory->getncategories($ncategory['ncategory_id']);
			
			foreach ($children as $child) {	
				$children_data[] = array(
					'ncategory_id' => $child['ncategory_id'],
					'name'        => $child['name'],
					'href'        => $this->url->link('news/ncategory', 'ncat=' . $ncategory['ncategory_id'] . '_' . $child['ncategory_id'])	
				);					
			}
					
			$data['ncategories'][] = array(
				'ncategory_id' => $ncategory['ncategory_id'],
				'name'        => $ncategory['name'],
				'children'    => $children_data,
				'href'        => $this->url->link('news/ncategory', 'ncat=' . $ncategory['ncategory_id'])	
			);
		}
		
		
		return $this->load->view('extension/module/ncategory', $data);
  	}
}
?>