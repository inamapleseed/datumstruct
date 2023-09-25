<?php
class ControllerEventSbNews extends Controller {

	public function menu(&$view, &$data) {
		$data['extra_tags'] = $this->document->getExtraTags();
		if ($this->config->get('ncategory_bnews_top_link') && isset($data['categories']) && count($data['categories'])) {
			$this->language->load('extension/module/news_latest');
			$data['categories'][] = array(
				'name'     => $this->language->get('text_blogpage'),
				'children' => array(),
				'column'   => 1,
				'href'     => $this->url->link('news/ncategory')
			);
		}			
	}

}
