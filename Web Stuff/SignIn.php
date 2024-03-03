<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the data from the form
    $userName = $_POST["username"];
    $password = $_POST["password"];

    $correctUsernameAndPassword = signIn($userName, $password);

    // If username and password are both correct
    if ($correctUsernameAndPassword[0] && $correctUsernameAndPassword[1]) {
        echo "Login successful. Welcome " . $userName;
    } else if ($correctUsernameAndPassword[0]) {
        // Correct username but incorrect password
        echo "Incorrect password for account called: " . $userName;
    } else {
        echo "Incorrect username and password.";
    }
}

function signIn($username, $password) {
    try {
        // Check credentials against database
        $db = new PDO('sqlite:users.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT username, password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $storedPassword = $row['password'];

            // Check if the given password matches the stored password
            if ($password === $storedPassword) {
                return [true, true];
            } else {
                // Correct username but incorrect password
                return [true, false];
            }
        }

        // If no matching user is found, you might want to return an appropriate message or value
        return [false, false];
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

?>
