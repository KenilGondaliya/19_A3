<?php

require "../config/db.php";

$category_id = intval($_GET["category_id"] ?? 0);
$page = intval($_GET["page"] ?? 1);

if ($page < 1) {
    $page = 1;
}

$limit = 4;
$offset = ($page - 1) * $limit;


/* ==========================================
   COUNT PRODUCTS
========================================== */

$count_stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM products
     WHERE category_id = ?"
);

$count_stmt->bind_param("i", $category_id);
$count_stmt->execute();

$count_result = $count_stmt->get_result();
$count_data = $count_result->fetch_assoc();

$total_products = $count_data["total"];

$total_pages = ceil($total_products / $limit);


/* ==========================================
   GET PRODUCTS
========================================== */

$stmt = $conn->prepare(
    "SELECT
        products.id,
        products.pname,
        products.price,
        products.picture,
        categories.name AS category_name

     FROM products

     INNER JOIN categories
        ON products.category_id = categories.id

     WHERE products.category_id = ?

     ORDER BY products.id DESC

     LIMIT ? OFFSET ?"
);

$stmt->bind_param(
    "iii",
    $category_id,
    $limit,
    $offset
);

$stmt->execute();

$result = $stmt->get_result();


/* ==========================================
   NO PRODUCTS
========================================== */

if ($result->num_rows == 0) {

    echo "<p>No products available in this category.</p>";

    exit;
}

?>


<div class="product-container">

    <?php while ($product = $result->fetch_assoc()): ?>

        <div class="product-card">

            <?php if (!empty($product["picture"])): ?>
                <img src="/shopping_cart/uploads/products/<?= htmlspecialchars($product["picture"]) ?>" width="180" height="180"
                    style="object-fit: cover;" alt="<?= htmlspecialchars($product["pname"]) ?>">

            <?php else: ?>

                <div style="
                    width:180px;
                    height:180px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    background:#eee;
                ">
                    No Image
                </div>

            <?php endif; ?>


            <h3>
                <?= htmlspecialchars($product["pname"]) ?>
            </h3>


            <p>
                ₹<?= number_format($product["price"], 2) ?>
            </p>


            <p>
                Category:
                <?= htmlspecialchars($product["category_name"]) ?>
            </p>

        </div>

    <?php endwhile; ?>

</div>


<!-- ==========================================
     PAGINATION
========================================== -->

<?php if ($total_pages > 1): ?>

    <div class="pagination">

        <?php if ($page > 1): ?>

            <button onclick="changePage(<?= $page - 1 ?>)">
                Previous
            </button>

        <?php endif; ?>


        <?php for ($i = 1; $i <= $total_pages; $i++): ?>

            <button onclick="changePage(<?= $i ?>)" <?= ($i == $page) ? 'style="font-weight:bold;"' : '' ?>>
                <?= $i ?>
            </button>

        <?php endfor; ?>


        <?php if ($page < $total_pages): ?>

            <button onclick="changePage(<?= $page + 1 ?>)">
                Next
            </button>

        <?php endif; ?>

    </div>

<?php endif; ?>