<?php
class ControllerExtensionFeedArticlesGoogleSitemap extends Controller {
   public function index() {
	  if ($this->config->get('articles_google_sitemap_status')) {
		 $output  = '<?xml version="1.0" encoding="UTF-8"?>';
		 $output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		 
		 $this->load->model('catalog/news');
		 
		 $articles = $this->model_catalog_news->getNews();
		 
		 foreach ($articles as $article) {
			$output .= '<url>';
			$output .= '<loc>' . $this->url->link('news/article', 'news_id=' . $article['news_id']) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';   
		 }
		 
		 $this->load->model('catalog/ncategory');
		 
		 $output .= $this->getNcategories(0);
		 
		 $output .= '</urlset>';
		 
		 $this->response->addHeader('Content-Type: application/xml');
		 $this->response->setOutput($output);
	  }
   }
   
   protected function getNcategories($parent_id, $current_path = '') {
	  $output = '';
	  
	  $results = $this->model_catalog_ncategory->getncategories($parent_id);
	  
	  foreach ($results as $result) {
		 if (!$current_path) {
			$new_path = $result['ncategory_id'];
		 } else {
			$new_path = $current_path . '_' . $result['ncategory_id'];
		 }

		 $output .= '<url>';
		 $output .= '<loc>' . $this->url->link('news/ncategory', 'ncat=' . $new_path) . '</loc>';
		 $output .= '<changefreq>weekly</changefreq>';
		 $output .= '<priority>0.7</priority>';
		 $output .= '</url>';         

		 $articles = $this->model_catalog_news->getNews(array('filter_ncategory_id' => $result['ncategory_id']));
		 
		 foreach ($articles as $article) {
			$output .= '<url>';
			$output .= '<loc>' . $this->url->link('news/article', 'ncat=' . $new_path . '&news_id=' . $article['news_id']) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';   
		 }   
		 
		   $output .= $this->getNcategories($result['ncategory_id'], $new_path);
	  }

	  return $output;
   }      
}
?>