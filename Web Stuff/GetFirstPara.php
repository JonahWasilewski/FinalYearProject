<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Search Engine</title>
</head>

<body>

<?php
function getWebsiteSummary($url) {
    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

    // Execute cURL session and get the webpage content
    $html = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        return "Failed to retrieve website data: " . curl_error($ch);
    }

    // Close cURL session
    curl_close($ch);

    // Create a DOMDocument to parse the HTML
    $dom = new DOMDocument();
    libxml_use_internal_errors(true); // Disable error reporting for HTML parsing

    // Load the HTML content into the DOMDocument
    $dom->loadHTML($html);

    // Extract the title from the webpage
    $title = $dom->getElementsByTagName('title')->item(0)->nodeValue;

    // Extract the description (you may need to adjust this based on the website's HTML structure)
    $description = "";
    $metaTags = $dom->getElementsByTagName('meta');
    foreach ($metaTags as $metaTag) {
        if ($metaTag->getAttribute('name') == 'description') {
            $description = $metaTag->getAttribute('content');
            break;
        }
    }

    // Clean up HTML entities in the description
    $description = htmlspecialchars_decode($description, ENT_QUOTES);

    return array(
        'title' => $title,
        'description' => $description
    );
}

// Example usage
$url = "https://www.w3schools.com/python/";
$summary = getWebsiteSummary($url);

if (is_array($summary)) {
    echo "Title: " . $summary['title'] . "<br>";
    echo "Description: " . $summary['description'] . "<br>";
} else {
    echo $summary;
}
?>


</body>
</html>