<?php

require "config/auth.php";

admin_required();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Dashboard</title>

</head>

<body>

    <h1>Admin Dashboard</h1>

    <p>
        Welcome,
        <strong>
            <?= htmlspecialchars($_SESSION["admin_username"]) ?>
        </strong>
    </p>

    <hr>

    <h2>Admin Menu</h2>

    <ul>

        <li>
            <a href="admin/categories.php">
                Manage Categories
            </a>
        </li>

        <li>
            <a href="admin/products.php">
                Manage Products
            </a>
        </li>

        <li>
            <a href="index.php">
                View Shopping Website
            </a>
        </li>

        <li>
            <a href="logout.php">
                Logout
            </a>
        </li>

    </ul>

</body>

</html>