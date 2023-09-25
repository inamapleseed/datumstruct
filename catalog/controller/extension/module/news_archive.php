<?php  
class ControllerExtensionModuleNewsArchive extends Controller {
	public function index() {
		
		$this->language->load('extension/module/news_archive');
		
    	$data['heading_title'] = $this->language->get('heading_title');
		
		$months = $this->config->get('news_archive_months');
		
		$data['archives'] = array();
		
		$lid = $this->config->get('config_language_id');
		
		$m_name = array();
		
		$m_name[1] = (isset($months['jan'][$lid]) && $months['jan'][$lid]) ? $months['jan'][$lid] : 'January';
		$m_name[2] = (isset($months['feb'][$lid]) && $months['feb'][$lid]) ? $months['feb'][$lid] : 'February';
		$m_name[3] = (isset($months['march'][$lid]) && $months['march'][$lid]) ? $months['march'][$lid] : 'March';
		$m_name[4] = (isset($months['april'][$lid]) && $months['april'][$lid]) ? $months['april'][$lid] : 'April';
		$m_name[5] = (isset($months['may'][$lid]) && $months['may'][$lid]) ? $months['may'][$lid] : 'May';
		$m_name[6] = (isset($months['june'][$lid]) && $months['june'][$lid]) ? $months['june'][$lid] : 'June';
		$m_name[7] = (isset($months['july'][$lid]) && $months['july'][$lid]) ? $months['july'][$lid] : 'July';
		$m_name[8] = (isset($months['aug'][$lid]) && $months['aug'][$lid]) ? $months['aug'][$lid] : 'August';
		$m_name[9] = (isset($months['sep'][$lid]) && $months['sep'][$lid]) ? $months['sep'][$lid] : 'September';
		$m_name[10] = (isset($months['oct'][$lid]) && $months['oct'][$lid]) ? $months['oct'][$lid] : 'October';
		$m_name[11] = (isset($months['nov'][$lid]) && $months['nov'][$lid]) ? $months['nov'][$lid] : 'November';
		$m_name[12] = (isset($months['dec'][$lid]) && $months['dec'][$lid]) ? $months['dec'][$lid] : 'December';
		
		$this->load->model('catalog/news');
		
		$years = $this->model_catalog_news->getArchive();
		
		foreach ($years as $year) {
			$data_month = array();
			$total = 0;
			$months = unserialize($year['months']);
			foreach ($months as $mo => $articles) {
				$total += $articles;
				$data_month[] = array(
					'name' => $m_name[$mo] . ' '. $year['year'] . ' (' . $articles . ')',
					'href' => $this->url->link('news/ncategory', 'archive=' . $year['year'] . '-' . $mo)
				);	
			}
			$data['archives'][] = array(
				'year' => $year['year'] . ' (' .$total. ')',
				'month' => $data_month
			);
		}
		
		return $this->load->view('extension/module/news_archive', $data);
		
  	}
}