<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the username from the webpage
    $userName = $_POST["username"];
	
	$isSuccess = deleteAccount($userName);

    // Return data to webpage to acknowledge success or failiure with the db query
    if ($isSuccess) {
        echo "Account successfully deleted";
    } else {
        echo "Error deleting your account";
    }
}

function deleteAccount($userName) {
    try {
        $db = new PDO('sqlite:/var/www/clients/client2658/web2624/web/BigProject/WebStuff/users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Delete the row containing the given username from the db
        $stmt = $db->prepare("DELETE FROM users WHERE username = :username");
        $stmt->bindParam(':username', $userName);

        $stmt->execute();
		
		// NOTE also need to delete all search history entries for the deleted account to maintain database consistency
		$db = new PDO('sqlite:/var/www/clients/client2658/web2624/web/BigProject/WebStuff/websiteHistory.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Delete all rows where the deleted user occurs
        $stmt = $db->prepare("DELETE FROM websiteHistory WHERE username = :username");
        $stmt->bindParam(':username', $userName);

        $stmt->execute();
		
        return true;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
	
	
}

?>
