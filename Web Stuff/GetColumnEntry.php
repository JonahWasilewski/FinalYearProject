<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the clicked URL and title from the AJAX request
    $username = $_POST['username'];
	$columnName = $_POST['columnName'];

    // Get the entry relating to the username and column given
    try {
        $db = new PDO('sqlite:/var/www/clients/client2658/web2624/web/BigProject/WebStuff/users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		// Retrieve the entry for the given username and column
		$stmtMaxId = $db->prepare("SELECT * FROM users WHERE username = :username");
		$stmtMaxId->bindParam(':username', $username);
		$stmtMaxId->execute();

		while ($row = $stmtMaxId->fetch()) {
			$entry = $row[$columnName];
		}

		// Return the entry 
		echo $entry;

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // Respond with an error message if the URL parameter is not provided
    echo 'Error: URL parameter not provided';
}
?>
