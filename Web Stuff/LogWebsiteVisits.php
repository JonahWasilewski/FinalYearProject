<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the clicked URL and title from the AJAX request
    $clickedUrl = $_POST['url'];
    $clickedTitle = $_POST['title'];
	$username = $_POST['username'];

    $currentDate = date("Y-m-d H:i:s");

    // Log the clicked URL	
    // Store in website history database
    try {
        // Check username against database
        $db = new PDO('sqlite:/var/www/clients/client2658/web2624/web/BigProject/WebStuff/websiteHistory.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// Retrieve the current maximum ID
		$stmtMaxId = $db->prepare("SELECT MAX(id) AS max_id FROM websiteHistory");
		$stmtMaxId->execute();
		$resultMaxId = $stmtMaxId->fetch(PDO::FETCH_ASSOC);
		$nextId = $resultMaxId['max_id'] + 1;

        // Username doesn't exist in the database, so we can create a new user entry with all the data passed to Register.php
        $stmt = $db->prepare("INSERT INTO websiteHistory (URL, DateVisited, title, username, id) VALUES (:URL, :DateVisited, :title, :username, :id)");
        $stmt->bindParam(':URL', $clickedUrl);
        $stmt->bindParam(':DateVisited', $currentDate);
        $stmt->bindParam(':title', $clickedTitle);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':id', $nextId);

        $stmt->execute();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    // Respond with a success message
    echo 'Success';
} else {
    // Respond with an error message if the URL parameter is not provided
    echo 'Error: URL parameter not provided';
}
?>
