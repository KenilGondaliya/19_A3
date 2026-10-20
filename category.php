<?php
session_start();
require "config/db.php";
$category_id = isset($_GET["id"])
    ? intval($_GET["id"])
    : 0;
if ($category_id <= 0) {
    die("Invalid category ID.");
}
$stmt = $conn->prepare(
    "SELECT id, name
     FROM categories
     WHERE id = ?"
);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("Category not found.");
}
$category = $result->fetch_assoc();
$category_name = $category["name"];
?>
<div class="container">
    <h2>
        <?= htmlspecialchars($category_name) ?>
    </h2>
    <div id="products">
        Loading products...
    </div>
</div>
<script>
function loadProducts(page = 1) {
    fetch(
        "ajax/products.php?category_id=<?= $category_id ?>&page=" + page
    )
    .then(response => {
        if (!response.ok) {
            throw new Error("HTTP error: " + response.status);
        }
        return response.text();
    })
    .then(data => {
        document.getElementById("products").innerHTML = data;
    })
    .catch(error => {
        console.error(error);
        document.getElementById("products").innerHTML =
            "<p>Error loading products.</p>";
    });
}
function changePage(page) {
    loadProducts(page);
}
loadProducts(1);
</script>
<?php
?>
