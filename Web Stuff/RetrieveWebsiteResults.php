<?php
try {
    // Get the username from the URL parameters
    $username = isset($_GET['username']) ? $_GET['username'] : '';

    // Save it as a JavaScript variable
    echo "<script>";
    echo "let username = '" . $username . "';";
    echo "</script>";
} catch (Exception $e) {
    // Store username as an empty string to let the rest of the program know the user isnt signed in 
    echo "<script>";
    echo "let username = '';";
    echo "</script>";
}
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Search Engine</title>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <script>
  
	// Function to log the clicked website
    function logClickedWebsite(linkElement) {
	    let title = linkElement.textContent;
		let url = linkElement.getAttribute("href");
				
		if (username != "") {
			// Make an AJAX request to a server-side script (LogWebsiteVisits.php) to log the clicked website		
			$.ajax({
			  type: "POST",
			  url: "LogWebsiteVisits.php",
			  data: { title: title, url: url, username: username },
			  success: function(response) {
				// Handle the server's response
				//getAllEntries();
				}
			});
		}
    }
	
	// Function to get all entries from the database
	function getAllEntries() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				// Parse the JSON response
				var entries = this.responseText;
				entriesList = entries.split(',');
				console.log("All entries in the database:");
				for (let i = 0; i < entriesList.length; i++) {
					console.log(entriesList[i]);
				}
			}
		};
		xhttp.open("GET", "GetWebsiteVisits.php", true);
		xhttp.send();
	}

    // Add a click event listener to all search result links
    document.addEventListener("DOMContentLoaded", function() {
        var resultLinks = document.querySelectorAll('.search-result-box a');
        resultLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                // Log the clicked website when the link is clicked
                logClickedWebsite(link);
            });
        });
    });
  
  </script>
  
  <style>
	.grid-container {
		display: grid;
		grid-template-columns: auto auto auto;
		padding: 10px;
	}

	.grid-item {
		padding: 20px;
		
	}
	
	.logo-container {
	  display: flex;
	  justify-content: center;
	  align-items: center;
	}
  
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
	  background-color: #f5f5f5;
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
	
	.main-content {
		width: 70%;
	}
	@media screen and (max-width: 1000px) {
		.main-content {
			width: 100%; /* New width for screens smaller than 1000px */
		}
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
	<div class="grid-item main-content">								<!-- Section for the results to go -->

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

	// Set the number of results to display per page
	$resultsPerPage = 15;

	// Calculate the total number of pages based on the results count
	$totalPages = ceil(count($results) / $resultsPerPage);

	// Get the current page number from the query parameters
	$currentpage = isset($_GET['page']) ? $_GET['page'] : 1;

	// Calculate the starting index for the current page
	$startIndex = ($currentpage - 1) * $resultsPerPage;

	// Display the results for the current page
	if (count($results) > 0) {
		echo '<div class="search-results">';
		for ($i = $startIndex; $i < min($startIndex + $resultsPerPage, count($results)); $i++) {
			$result = $results[$i];
			echo '<div class="search-result-box">';
			echo '<a id="title' . $i . '" href="' . $result->getURL() . '" target="blank">' . $result->getTitle() . '</a>';
			echo '<p>' . $result->getUrl() . '</p>';
			echo '<p>' . $result->getSummary() . '</p>';
			echo '</div>';
		}
		echo '</div>';

		// Display pagination links
		echo '<div class="pagination">';
		
		// If we arent on the first set of websites then give the user the option to go to the previous set - ie currentPage - 1
		if ($currentpage > 1) {
			// Define the link that will take the user to the previous webpage when clicked - same url but specifying the page of returned webpages that we are showing the user in the search results area
			echo '<a href="?query=' . urlencode($query) . '&page=' . ($currentpage - 1) . '">Previous</a>';
			echo " ";							// Extra echo is used to space out each of the links by one space so they dont look too crowded
		}

		// In all states we want to show the user the number of pages they can look at and define the links for them to do this
		for ($i = 1; $i <= $totalPages; $i++) {
			echo '<a href="?query=' . urlencode($query) . '&page=' . $i . '" ';
			if ($i == $currentpage) {
				echo 'class="active"';
			}
			echo '>' . $i . '</a>';
			echo " ";
		}

		// Show the 'next' link to the user if they arent on the last page - will take them to currentPage + 1 when pressed
		if ($currentpage < $totalPages) {
			echo '<a href="?query=' . urlencode($query) . '&page=' . ($currentpage + 1) . '">Next</a>';
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
        if (in_array(strtolower($query), explode(" ", $title)) || in_array(strtolower($title), explode(" ", $query))) {
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
	
	// Rank the websites based on the user's query and the relevance score found from it
	usort($websites, function ($a, $b) use ($query) {
		return $b->calculateRelevance($query) - $a->calculateRelevance($query);
	});
	
	// Sort the websites by relevance score in descending order
	usort($matchedWebsiteList, array('Website', 'compareByRelevanceScore'));
	
    return $matchedWebsiteList;
}

// Things to fix
// Query is shortened to one word after enter is pressed - no clue why
// When checking if the title matches the query - i explode the title and check each element to see if it matches the whole query
// WOuld be a lot better if i checked to see if there was an resemblance with query and title - maybe see if title is "in" query or query is "in" title
// Or use the explode functionality and then compare each of the individual words of title and query to see how many matches i get and give a score

?>

	</div>	
	<div class="grid-item"></div>
	
	<div class="grid-item"></div>
    <div class="grid-item"></div>
	<div class="grid-item"></div>
  </div>

  <script>
        // Add a click event listener to the image
        document.getElementById("logo").addEventListener("click", function() {
            // Redirect the user back to the home page
            window.location.href = "SearchEngineFrontEnd.php";
        });
    </script>

  </body>
</html>


