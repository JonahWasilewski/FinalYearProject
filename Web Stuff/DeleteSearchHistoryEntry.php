<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve entry id from SearchHistory.php
    $id = $_POST["id"];
	
	$isSuccess = deleteEntry($id);

    // Return data to webpage to acknowledge success or failiure with the db query
    if ($isSuccess) {
        echo "Search entry successfully deleted";
    } else {
        echo "Error deleting your search history";
    }
}

function deleteEntry($id) {
    try {
        $db = new PDO('sqlite:/var/www/clients/client2658/web2624/web/BigProject/WebStuff/websiteHistory.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Delete the row containing the given username from the db
        $stmt = $db->prepare("DELETE FROM websiteHistory WHERE id = :id");
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
