<?php
	class Document {
		private $schema;
		private $title;
		private $description;
		private $keywords;
		private $links = array();
		private $styles = array();
		private $scripts = array();
		
		private $extra_tags = array();
		public function addExtraTag($property, $content = '', $name=''){
			$this->extra_tags[md5($property)] = array(
			'property' => $property,
			'content'  => $content,
			'name'     => $name,
			);
		}
		
		public function getSchema(){
			return $this->schema;
		}
		
		public function setSchema($schema) {
			$this->schema = $schema;
		}
		
		public function setTitle($title) {
			$this->title = $title;
		}
		
		public function getTitle() {
			return $this->title;
		}
		
		public function setDescription($description) {
			$this->description = $description;
		}
		
		public function getDescription() {
			return $this->description;
		}
		
		public function setKeywords($keywords) {
			$this->keywords = $keywords;
		}
		
		public function getKeywords() {
			return $this->keywords;
		}
		
		public function addLink($href, $rel) {
			$this->links[$href] = array(
			'href' => $href,
			'rel'  => $rel
			);
		}
		
		public function getLinks() {
			return $this->links;
		}
		
		public function addStyle($href, $rel = 'stylesheet', $media = 'screen') {
			$this->styles[$href] = array(
			'href'  => $href,
			'rel'   => $rel,
			'media' => $media
			);
		}
		
		public function getStyles() {
			return $this->styles;
		}
		
		public function addScript($href, $postion = 'header', $delay = false) {
			$this->scripts[$postion][$href] = $delay;
		}
		
		public function getScripts($postion = 'header') {
			if (isset($this->scripts[$postion])) {
				return $this->scripts[$postion];
			} 
			else {
				return array();
			}
		}
	}		