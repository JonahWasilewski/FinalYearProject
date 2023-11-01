<?php
	
	class Website {
		private $title;
		private $keywords;
		private $url;
		private $summary;

		public function __construct($title, $keywords, $url, $summary) {
			$this->title = $title;
			$this->keywords = $keywords;
			$this->url = $url;
			$this->summary = $summary;
		}

		public function getTitle() {
			return $this->title;
		}

		public function getKeywords() {
			return $this->keywords;
		}

		public function getUrl() {
			return $this->url;
		}
		
		public function getSummary() {
			return $this->summary;
		}
	}
	
?>
