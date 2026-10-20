<?php

session_start();

require "../config/db.php";

$message = "";


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $username = $_POST['username'];

    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT *
         FROM admins
         WHERE username = ?"
    );

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $admin = $stmt->get_result()->fetch_assoc();

    if (
        $admin &&
        password_verify(
            $password,
            $admin['password']
        )
    ) {

        $_SESSION['admin_id'] =
            $admin['id'];

        header("Location: ../dashboard.php");

        exit;

    } else {

        $message = "Invalid login.";
    }
}

?>

<h2>Admin Login</h2>

<p><?= htmlspecialchars($message) ?></p>

<form method="post">

    <input name="username" type="text" placeholder="Username" required>

    <br><br>

    <input name="password" type="password" placeholder="Password" required>
    <br><br>

    <button>
        Login
    </button>
</form>