<?php

require "config/db.php";

$username = "admin";
$password = "admin123";

$hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt = $conn->prepare(
    "UPDATE admins
     SET password = ?
     WHERE username = ?"
);

$stmt->bind_param(
    "ss",
    $hash,
    $username
);

if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {

        echo "Admin password updated successfully.<br>";
        echo "Username: admin<br>";
        echo "Password: admin123";

    } else {

        echo "Admin username was not found.";
    }

} else {

    echo "Error: " . $stmt->error;
}

?>