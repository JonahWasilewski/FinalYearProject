<?php

// Get the username from the URL parameters
$username = isset($_GET['username']) ? $_GET['username'] : '';

// Get all entries from search history database
$db = new PDO('sqlite:websiteHistory.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $db->prepare("SELECT * FROM websiteHistory WHERE username = :username ORDER BY DateVisited DESC");
$stmt->bindParam(':username', $username);
$stmt->execute();

$data = [];

while ($row = $stmt->fetch()) {
    $entry = [
        'URL' => $row['URL'],
        'Title' => $row['Title'],
        'Date' => $row['DateVisited'],
		'Username' => $row['username'],
		'Id' => $row['id'],
    ];
    $data[] = $entry;
}

// Encode the data as JSON
$jsonData = json_encode($data);

// Set the appropriate Content-Type header for JSON
header('Content-Type: application/json');

// Return the JSON data
echo $jsonData;
?>
