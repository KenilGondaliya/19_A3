<?php

session_start();

require "config/db.php";

$result = $conn->query(
    "SELECT id, name FROM categories ORDER BY id ASC"
);

?>

<div class="container">

    <h2>Shop By Category</h2>

    <?php if ($result && $result->num_rows > 0): ?>

        <div class="categories">

            <?php while ($category = $result->fetch_assoc()): ?>

                <a
                    href="category.php?id=<?= (int)$category['id'] ?>"
                    class="category-box"
                >
                    <?= htmlspecialchars($category['name']) ?>
                </a>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <p>No categories found.</p>

    <?php endif; ?>

</div>

<?php
?>
