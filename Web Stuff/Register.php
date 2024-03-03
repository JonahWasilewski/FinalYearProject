<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the data from the form
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $userName = $_POST["username"];
    $password = $_POST["password"];
    $dob = $_POST["dob"];

    // Call function to see if the user already exists and make the new user if the user doesn't exist
    $registerComplete = register($fname, $lname, $userName, $password, $dob);

    // Return data to log the user in using their username or say that the username already exists
    if ($registerComplete) {
        echo "You have been successfully registered. Welcome " . $userName;
    } else {
        echo "This username already exists. If you have an account, then log in using the sign-in section.";
    }
}

function register($fname, $lname, $userName, $password, $dob) {
    try {
        // Check username against database
        $db = new PDO('sqlite:/var/www/clients/client2658/web2624/web/BigProject/WebStuff/users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Look to see if the username already exists in the database - username is the primary key so can't have duplicates
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $userName);
        $stmt->execute();
		
        if ($stmt->fetch()) {
            return false;   // Username already exists
        } else {
            // Username doesn't exist in the database, so we can create a new user entry with all the data passed to Register.php
            $stmt = $db->prepare("INSERT INTO users (fname, lname, username, password, dob) VALUES (:fname, :lname, :username, :password, :dob)");
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':username', $userName);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':dob', $dob);

            $stmt->execute();

            return true;
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
