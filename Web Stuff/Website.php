<?php
	
	class Website {
		private $title;
		private $keywords;
		private $url;
		private $summary;
		private $relevanceScore;
		private $pageRank;

		public function __construct($title, $keywords, $url, $summary, $relevanceScore, $pageRank) {
			$this->title = $title;
			$this->keywords = $keywords;
			$this->url = $url;
			$this->summary = $summary;
			$this->relevanceScore = $relevanceScore;											
			$this->pageRank = $pageRank;
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
		
		public function getRelevanceScore() {
			return $this->relevanceScore;
		}
		
		public function setRelevanceScore($newScore) {
				$this->relevanceScore = $newScore;
		}
		
		// Add a method to calculate the relevance score for a given query
		public function calculateRelevance($query) {
			// If relevanceScore has already been calculated, return it
			if ($this->relevanceScore != 0) {
				return $this->relevanceScore;
			}

			$score = 0;

			// Consider the title, keywords, URL, and summary for scoring
			$score += $this->calculateScoreForField($this->title, $query);
			$score += $this->calculateScoreForKeywords($this->keywords, $query);
			$score += $this->calculateScoreForURL($this->url, $query);
			$score += $this->calculateScoreForField($this->summary, $query);

			// Set the relevanceScore for the website
			$this->relevanceScore = $score;

			return $score;
		}

		// Helper method to calculate the score for a specific field
		private function calculateScoreForField($field, $query) {
			// You can implement your scoring logic here.
			// For a simple example, let's count the number of query terms in the field.
			$field = strtolower($field);
			$query = strtolower($query);
			return substr_count($field, $query);
		}

		// Helper method to calculate the score for the URL field
		private function calculateScoreForURL($url, $query) {
			// For the URL field, we'll consider both the occurrence of the query terms
			// and the length of the URL. Shorter URLs are considered more relevant.

			// Count the number of occurrences of the query terms in the URL
			$score = $this->calculateScoreForField($url, $query);

			// Penalize longer URLs
			$urlLengthPenalty = max(strlen($url) - strlen($query), 0);

			// Adjust the score based on the URL length penalty
			$score -= $urlLengthPenalty;

			// Ensure the score is non-negative
			$score = max($score, 0);

			return $score;
		}

		// Helper method to calculate the score for the keywords field
		private function calculateScoreForKeywords($keywords, $query) {
			// For the keywords field, we'll sum the scores for each keyword in the list.
			$score = 0;
			$query = strtolower($query);

			foreach ($keywords as $keyword) {
				$keyword = strtolower($keyword);
				$score += substr_count($keyword, $query);
			}

			return $score;
		}

		
		// Comparison function for sorting by relevance score in descending order
		public static function compareByRelevanceScore($a, $b) {
			return $b->relevanceScore - $a->relevanceScore;
		}
	}
	
?>