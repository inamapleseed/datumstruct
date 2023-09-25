<?php
class Response {
	private $headers = array();
	private $level = 0;
	private $output;

	public function addHeader($header) {
		$this->headers[] = $header;
	}

	public function redirect($url, $status = 302) {
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url), true, $status);
		exit();
	}

	public function setCompression($level) {
		$this->level = $level;
	}

	public function getOutput() {
		return $this->output;
	}
	
	public function setOutput($output) {
		$this->output = $output;
	}

	private function compress($data, $level = 0) {
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)) {
			$encoding = 'gzip';
		}

		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false)) {
			$encoding = 'x-gzip';
		}

		if (!isset($encoding) || ($level < -1 || $level > 9)) {
			return $data;
		}

		if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
			return $data;
		}

		if (headers_sent()) {
			return $data;
		}

		if (connection_status()) {
			return $data;
		}

		$this->addHeader('Content-Encoding: ' . $encoding);

		return gzencode($data, (int)$level);
	}

	public function output() {
		if ($this->output) {
			header_remove("X-Powered-By");
			
			$output = $this->level ? $this->compress($this->output, $this->level) : $this->output;
			if (!headers_sent()) {
				foreach ($this->headers as $header) {
					header($header, true);
				}
			}

			if(defined('URL') && defined('ADMIN_FOLDER')){
				$current_domain = str_replace('/' . ADMIN_FOLDER, '', URL); // If first visit is from Admin then remove Admin
				$current_domain = str_replace('www.', '', URL); // Handle www issue
				$current_domain = trim($current_domain, "/");
				
				//unset($_SESSION['old_domain']);
				if( !isset($_SESSION['old_domain']) || !isset($_SESSION['current_domain']) || $_SESSION['current_domain'] != $current_domain ){
					// First Load || Visited another opencart project
					$_SESSION['current_domain'] = $current_domain;
	
					$cached_domain = DIR_SYSTEM . '/domain_hop.json';
					$old_domain_json = file_get_contents($cached_domain);
					$_SESSION['old_domain'] = $old_domain_json = json_decode($old_domain_json, true);
				}
	
				$old_domain = $_SESSION['old_domain'];
	
				$output = str_replace($old_domain, $current_domain, $output);
	
				if($_SERVER['SERVER_PORT'] == 443){
					$output = str_replace('http:', 'https:', $output);
				}else{
					$output = str_replace('https:', 'http:', $output);
				}
				
			}

			$output = $this->minify($output);

			echo $output;
		}
	}

	public function minify($html){
    	$html = preg_replace("`>\s+<`", "> <", $html);
    	$replace = array(
    		'&nbsp;' => '&#160;',
    		'&copy;' => '&#169;',
    		'&acirc;' => '&#226;',
    		'&cent;' => '&#162;',
    		'&raquo;' => '&#187;',
    		'&laquo;' => '&#171;'
    	);
    	return $html;
    }	
}
