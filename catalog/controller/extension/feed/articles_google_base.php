<?php 
class ControllerExtensionFeedArticlesGoogleBase extends Controller {
	public function index() {
		if ($this->config->get('articles_google_base_status')) { 
			$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
			$output .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
            $output .= '<channel>';
			$output .= '<title>' . $this->config->get('config_name') . '</title>'; 
			$output .= '<description>' . $this->config->get('config_meta_description') . '</description>';
			$output .= '<link>' . HTTP_SERVER . '</link>';
			
			$this->load->model('catalog/news');
			
			$this->load->model('tool/image');
			
			$articles = $this->model_catalog_news->getNews();
			
			foreach ($articles as $article) {
				if ($article['description']) {
					$output .= '<item>';
					$output .= '<title>' . $article['title'] . '</title>';
					$output .= '<link>' . $this->url->link('news/article', 'news_id=' . $article['news_id']) . '</link>';
					$output .= '<description>' . $article['description'] . '</description>';
					$output .= '<g:id>' . $article['news_id'] . '</g:id>';
					
					if ($article['image']) {
						$output .= '<g:image_link>' . $this->model_tool_image->resize($article['image'], 500, 500) . '</g:image_link>';
					} else {
						$output .= '<g:image_link>' . $this->model_tool_image->resize('no_image.jpg', 500, 500) . '</g:image_link>';
					}
					
					$output .= '</item>';
				}
			}
			
			$output .= '</channel>'; 
			$output .= '</rss>';	
			
			$this->response->addHeader('Content-Type: application/rss+xml');
			$this->response->setOutput($output);
		}
	}
			
}
?>