<?php

function openCrawlList() {
    // Open the crawl list
	
	$db = new SQLite3('webpages.db');
	if (!$db) {
		die("Failed to connect to the database.");
	}

	$result = $db->query('SELECT * FROM webpages');
	if (!$result) {
		die("Query failed: " . $db->lastErrorMsg());
	}

	$websiteList = [];
	$isFirstWebsite = true;
	$currentKeywordList = [];
	$previousWebsite = null;
	$counter = 0;

	while ($row = $result->fetchArray()) {
		
		$currentWebsite = $row['url'];
		
		if (!$isFirstWebsite) {
			if ($currentWebsite == $previousWebsite) {
				$currentKeywordList[] = $row['keyword'];
			} else {
				$newWebsite = new Website($row['title'], $currentKeywordList, $row['url'], $row['summary'], 0, 0.0);
				$websiteList[] = $newWebsite;
				$currentKeywordList = [];
				$currentKeywordList[] = $row['keyword'];
			}
		} else {
			$currentKeywordList[] = $row['keyword'];
			$isFirstWebsite = false;
		}
		
		$previousWebsite = $currentWebsite;

	}
    
    return $websiteList;
}

?>