<?php

function openCrawlList() {
    // Open the crawl list
    $file = fopen("crawlList.txt", "r");
    $crawlList = fread($file, filesize("crawlList.txt"));

    // Split up the crawl list into separate lines - one object is on each line
    $crawlList = explode("\n", $crawlList);

    $websiteList = [];

    // Create objects for each of the lines
    foreach ($crawlList as $line) {
		// Make the current line a list where each element is separated by where thers a : in the string
        $lineList = explode(": ", $line);
        
        // Assign each attribute of the website to a variable
		$title = $lineList[0];
		
		if (isset($lineList[1])) {
			$keywords = explode(", ", $lineList[1]);
		} else {
			continue;
		}
		
		if (isset($lineList[2])) {
			$url = $lineList[2];
		} else {
			echo "Error for url with: " . $title;
		}
		
		if (isset($lineList[3])) {
			$summary = $lineList[3];
		} else {
			echo "Error for summary with: " . $title;
		}
        
        // Create the website object from the extracted info
        $newWebsite = new Website($title, $keywords, $url, $summary);
        $websiteList[] = $newWebsite;
    }

    fclose($file);
    
    return $websiteList;
}

?>
