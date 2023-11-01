<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Search Engine</title>
  
  <style>
	.grid-container {
		display: grid;
		grid-template-columns: auto auto auto;
		padding: 10px;
	}

	.grid-item {
		padding: 20px;
		background-color: #f5f5f5;
	}
	
	.logo-container {
	  display: flex;
	  justify-content: center;
	  align-items: center;
	}
  
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    h2 {
      color: #333;
    }

    ul {
      list-style-type: none;
      padding: 0;
    }

    li {
      margin-bottom: 10px;
    }

    a {
      text-decoration: none;
      color: #0070c9;
    }

    a:hover {
      text-decoration: underline;
    }
	
	input[type="text"] {
      padding: 10px;
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-right: 10px;
    }

    #search-button {
      padding: 10px 20px;
      background-color: #3498db;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
	
    .search-results {
        border: 1px solid #ccc;
        padding: 10px;
    }

    .search-result-box {
        border: 1px solid #ddd;
        padding: 10px;
        margin: 10px 0;
    }
	
	#logo:hover {
		cursor: pointer;
	}

  </style>
  
</head>

<body>

  <div class="grid-container">
    <div class="grid-item"></div>		
	<div class="grid-item">
	  <div class="logo-container">
		<img src="Logo.png" alt="My Logo" id="logo">
	  </div>
	</div>	
	<div class="grid-item"></div>
	
	<div class="grid-item"></div>
	<div class="grid-item">								<!-- Section for the results to go -->

<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

include 'Website.php';
include 'OpenCrawlList.php';

if (isset($_GET['query'])) {
    // Get the search query from the HTML form
    $query = $_GET['query'];

    // Read the list of websites from the text file
    $websites = openCrawlList();

    // Filter the websites that contain the search query
    $results = searchWebsites($websites, $query);

	// Display the search bar
	echo '
	<div id="search-container">
		<form action="RetrieveWebsiteResults.php" method="GET">
		  <input type="text" name="query" value=' . $query . '>
		  <br><br>
		  <input type="submit" value="Search" id="search-button">
		</form>
	  </div>';

    // Display the search results
	echo '<h2>Search Results for: ' . "$query" . '</h2>';
	if (count($results) > 0) {
		echo '<div class="search-results">';
		foreach ($results as $result) {
			echo '<div class="search-result-box">';
			echo '<a href="' . $result->getURL() . '" target="blank">' . $result->getTitle() . '</a>';
			echo '<p>' . $result->getUrl() . '</p>';
			echo '<p>' . $result->getSummary() . '</p>';
			echo '</div>';
		}
		echo '</div>';
	} else {
		echo 'No results found.';
	}


}

function searchWebsites($websites, $query) {
    $matchedWebsiteList = [];

    // Split the query into individual words
    $queryElements = explode(" ", $query);

    foreach ($websites as $currentWebsite) {
        $keywords = $currentWebsite->getKeywords();
        $title = strtolower($currentWebsite->getTitle());

        // Check if the title of the webpage matches the query (case-insensitive)
        if (in_array(strtolower($query), explode(" ", $title))) {
            $matchedWebsiteList[] = $currentWebsite;
            continue;
        }

        foreach ($queryElements as $element) {
            // Define a threshold for Levenshtein distance (you can adjust this)
            $threshold = 2;

            foreach ($keywords as $keyword) {
                // Calculate the Levenshtein distance between the query element and the keyword
                $distance = levenshtein(strtolower($element), strtolower($keyword));

                if ($distance <= $threshold) {
                    $matchedWebsiteList[] = $currentWebsite;
                    break; // If a match is found, no need to check other keywords for this website
                }
            }
        }
    }

    // Deduplicate the results by using an associative array
    $matchedWebsiteList = array_values(array_unique($matchedWebsiteList, SORT_REGULAR));

    return $matchedWebsiteList;
}


?>

	</div>	
	<div class="grid-item"></div>
	
	<div class="grid-item"></div>
    <div class="grid-item"></div>
	<div class="grid-item"></div>
  </div>

  <script>
        // Add a click event listener to the image element
        document.getElementById("logo").addEventListener("click", function() {
            // Redirect the user to the new webpage when the image is clicked
            window.location.href = "SearchEngineFrontEnd.php";
        });
    </script>

  </body>
</html>
