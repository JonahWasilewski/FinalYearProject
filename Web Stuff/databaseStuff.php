<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include 'Website.php';

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

echo sizeOf($websiteList);

for ($i=0; $i<sizeOf($websiteList); $i++) {
  echo $websiteList[$i]->getTitle();
	echo  "<br>";
  for ($j=0; $j<sizeOf($websiteList[$i]->getKeywords()); $j++) {
	  echo $websiteList[$i]->getKeywords()[$j];
	  echo "<br>";
  }
  echo "<br>";
}

$db->close();
?>
