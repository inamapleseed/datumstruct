<?php
class GkdUrl {
	private $url;
	private $ssl;
	private $rewrite = array();

	public function __construct($registry, $url, $ssl = '') {
		$this->url = $url;
		$this->ssl = $ssl;
    
    if ($registry->get('config')->get('config_seo_url')) {
      if (version_compare(VERSION, '2.2', '>=')) {
        $seourl_file = DIR_SYSTEM.'../catalog/controller/startup/seo_url.php';
      } else {
        $seourl_file = DIR_SYSTEM.'../catalog/controller/common/seo_url.php';
      }
      
      if (isset($vqmod)) {
        if (function_exists('modification')) {
          require_once($vqmod->modCheck(modification($seourl_file)));
        } else {
          require_once($vqmod->modCheck($seourl_file));
        }
      } else if (class_exists('VQMod')) {
        if (function_exists('modification')) {
          require_once(VQMod::modCheck(modification($seourl_file)));
        } else {
          require_once(VQMod::modCheck($seourl_file));
        }
      } else {
        if (function_exists('modification')) {
          require_once(modification($seourl_file));
        } else {
          require_once($seourl_file);
        }
      }
      
      if (version_compare(VERSION, '2.2', '>=')) {
        $this->rewrite[] = new ControllerStartupSeoUrl($registry);
      } else {
        $this->rewrite[] = new ControllerCommonSeoUrl($registry);
      }
    }
	}

	public function link($route, $args = '', $secure = false) {
		if ($this->ssl && $secure) {
      $url = $this->ssl . 'index.php?route=' . $route;
		} else {
      $url = $this->url . 'index.php?route=' . $route;
		}
		
		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url;
	}
}