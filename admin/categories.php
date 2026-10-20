<?php
require "../config/auth.php";
admin_required();
require "../config/db.php";
$message = "";
$editCategory = null;
if (isset($_POST["add_category"])) {
    $name = trim($_POST["name"]);
    if ($name == "") {
        $message = "Category name is required.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO categories(name)
             VALUES(?)"
        );
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            $message = "Category added successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}
if (isset($_POST["update_category"])) {
    $id = intval($_POST["id"]);
    $name = trim($_POST["name"]);
    if ($name == "") {
        $message = "Category name is required.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE categories
             SET name = ?
             WHERE id = ?"
        );
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            $message = "Category updated successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $stmt = $conn->prepare(
        "DELETE FROM categories
         WHERE id = ?"
    );
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Category deleted successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }
}
if (isset($_GET["edit"])) {
    $id = intval($_GET["edit"]);
    $stmt = $conn->prepare(
        "SELECT id, name
         FROM categories
         WHERE id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {

        $editCategory = $result->fetch_assoc();
    }
}
$result = $conn->query(
    "SELECT id, name
     FROM categories
     ORDER BY id DESC"
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
</head>
<body>
    <h1>Manage Categories</h1>
    <a href="dashboard.php">
        ← Dashboard
    </a>
    <br><br>
    <?php if ($message != ""): ?>
        <p style="color:green;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>
    <?php if ($editCategory): ?>
        <h2>Edit Category</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $editCategory["id"] ?>">
            <input type="text" name="name" value="<?= htmlspecialchars($editCategory["name"]) ?>" required>
            <button type="submit" name="update_category">
                Update
            </button>
            <a href="categories.php">
                Cancel
            </a>
        </form>
    <?php else: ?>
        <h2>Add Category</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Category name" required>
            <button type="submit" name="add_category">
                Add Category
            </button>
        </form>
    <?php endif; ?>
    <hr>
    <h2>Category List</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
        <?php while ($category = $result->fetch_assoc()): ?>

            <tr>
                <td>
                    <?= $category["id"] ?>
                </td>

                <td>
                    <?= htmlspecialchars($category["name"]) ?>
                </td>
                <td>
                    <a href="categories.php?edit=<?= $category["id"] ?>">
                        Edit
                    </a>
                    |
                    <a href="categories.php?delete=<?= $category["id"] ?>"
                        onclick="return confirm('Delete this category?')">
                        Delete
                    </a>

                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>