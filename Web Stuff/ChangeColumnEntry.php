<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the clicked URL and title from the AJAX request
	$username = $_POST['username'];
    $columnName = $_POST['columnName'];
	$newValue = $_POST['newValue'];

    // Get the entry relating to the username and column given
    try {
        $db = new PDO('sqlite:/var/www/clients/client2658/web2624/web/BigProject/WebStuff/users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// Retrieve the entry for the given username and column
		$stmt = $db->prepare("UPDATE users SET $columnName = :newValue WHERE username = :username");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':newValue', $newValue);
		$stmt->execute();

		// Return the entry 
		echo "success";

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // Respond with an error message if the URL parameter is not provided
    echo 'Error: URL parameter not provided';
}
?>
