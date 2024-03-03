<?php
// Connect to SearchEngineFrontEnd so that the user is given recommendations before they search
// ie - Would you like to continue with a previous search? Or would you like to find new things simialr to what you're interested in?
$username = $_POST['username'];

try {
    // Connect to the database
    $db = new PDO('sqlite:/var/www/clients/client2658/web2624/web/BigProject/WebStuff/websiteHistory.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve user's search history
    $stmt = $db->prepare("SELECT * FROM websiteHistory WHERE username = :username ORDER BY DateVisited DESC LIMIT 10");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $searchHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate recommendations based on search history
    $recommendations = generateRecommendations($searchHistory);

    // Send recommendations to the client-side for display
    echo json_encode($recommendations);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Function to generate recommendations based on search history
function generateRecommendations($searchHistory) {
    // Example logic: You can use a simple rule-based system or more advanced recommendation algorithms
    $recommendations = array();

    // Example: Suggest URLs that are similar to the ones the user has visited
    foreach ($searchHistory as $history) {
        $similarUrls = findSimilarUrls($history['URL']);
        $recommendations = array_merge($recommendations, $similarUrls);
    }

    // Remove duplicates and return top recommendations
    $recommendations = array_unique($recommendations);
    return array_slice($recommendations, 0, 5); // Return top 5 recommendations
}

// Function to find similar URLs (example implementation)
function findSimilarUrls($url) {
	
    // Extract domain name from the given URL
    $parsedUrl = parse_url($url);
    $domain = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';

    // Query the database for URLs with similar domain names
    try {
        // Connect to the database - checking against all saved websites
        $db = new PDO('sqlite:/var/www/clients/client2658/web2624/web/BigProject/WebStuff/websites.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query for URLs with similar domain names
        $stmt = $db->prepare("SELECT URL FROM websiteHistory WHERE URL LIKE :domain");
        $stmt->bindValue(':domain', "%$domain%");
        $stmt->execute();
        $similarUrls = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $similarUrls;
    } catch (PDOException $e) {
        // Handle database connection error
        die("Database Error: " . $e->getMessage());
    }
    return similarUrls();
}
?>
