<?php
require "../config/auth.php";
admin_required();
require "../config/db.php";
$message = "";
if (isset($_POST["add_product"])) {
    $category_id = intval($_POST["category_id"]);
    $pname = trim($_POST["pname"]);
    $price = floatval($_POST["price"]);
    $picture = null;
    if (
        isset($_FILES["picture"]) &&
        $_FILES["picture"]["error"] === UPLOAD_ERR_OK
    ) {
        $tmpName = $_FILES["picture"]["tmp_name"];
        $imageInfo = getimagesize($tmpName);
        if ($imageInfo === false) {
            $message = "Uploaded file is not a valid image.";
        } else {
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $type = $imageInfo[2];
            if (
                $type != IMAGETYPE_JPEG &&
                $type != IMAGETYPE_PNG &&
                $type != IMAGETYPE_WEBP
            ) {
                $message = "Only JPG, PNG and WEBP images are allowed.";
            } else {
                if ($type == IMAGETYPE_JPEG) {
                    $source = imagecreatefromjpeg($tmpName);
                } elseif ($type == IMAGETYPE_PNG) {
                    $source = imagecreatefrompng($tmpName);
                } else {
                    $source = imagecreatefromwebp($tmpName);

                }
                $maxSize = 500;
                $scale = min(
                    $maxSize / $width,
                    $maxSize / $height,
                    1
                );
                $newWidth = intval($width * $scale);
                $newHeight = intval($height * $scale);
                $destination = imagecreatetruecolor(
                    $newWidth,
                    $newHeight
                );
                $white = imagecolorallocate(
                    $destination,
                    255,
                    255,
                    255
                );
                imagefill(
                    $destination,
                    0,
                    0,
                    $white
                );
                imagecopyresampled(
                    $destination,
                    $source,
                    0,
                    0,
                    0,
                    0,
                    $newWidth,
                    $newHeight,
                    $width,
                    $height
                );
                $uploadDir = __DIR__ . "/../uploads/products/";
                if (!is_dir($uploadDir)) {
                    mkdir(
                        $uploadDir,
                        0777,
                        true
                    );
                }
                $picture = uniqid("product_") . ".jpg";
                imagejpeg(
                    $destination,
                    $uploadDir . $picture,
                    85
                );
                imagedestroy($source);
                imagedestroy($destination);
            }
        }
    }
    if ($message == "") {
        $stmt = $conn->prepare(
            "INSERT INTO products
            (category_id, pname, price, picture)
            VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "isds",
            $category_id,
            $pname,
            $price,
            $picture
        );
        if ($stmt->execute()) {
            $message = "Product added successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $stmt = $conn->prepare(
        "SELECT picture
         FROM products
         WHERE id = ?"
    );
    $stmt->bind_param(
        "i",
        $id
    );
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
     if (!empty($product["picture"])) {
            $file =
                __DIR__ .
                "/../uploads/products/" .
                $product["picture"];
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
    $stmt->close();
    $stmt = $conn->prepare(
        "DELETE FROM products
         WHERE id = ?"
    );
    $stmt->bind_param(
        "i",
        $id
    );
    if ($stmt->execute()) {
        $message = "Product deleted successfully.";
    } else {
        $message = "Error deleting product.";
    }
    $stmt->close();
}
$categories = $conn->query(
    "SELECT id, name
     FROM categories
     ORDER BY name"
);
$products = $conn->query(
    "SELECT
        products.id,
        products.pname,
        products.price,
        products.picture,
        categories.name AS category_name
     FROM products
     INNER JOIN categories
        ON products.category_id = categories.id
     ORDER BY products.id DESC"
);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
</head>
<body>
<h1>Manage Products</h1>
<a href="../dashboard.php">
    ← Dashboard
</a>
<br><br>
<?php if ($message != ""): ?>
    <p style="color: green;">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>
<hr>
<h2>Add Product</h2>
<form
    method="POST"
    enctype="multipart/form-data"
>
    <label>
        Category
    </label>
    <br>
    <select
        name="category_id"
        required
    >
        <option value="">
            Select Category
        </option>
        <?php while ($category = $categories->fetch_assoc()): ?>
            <option
                value="<?= $category["id"] ?>"
            >
                <?= htmlspecialchars($category["name"]) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>
    <label>
        Product Name
    </label>
    <br>
    <input
        type="text"
        name="pname"
        required
    >
    <br><br>

    <label>
        Price
    </label>
    <br>
    <input
        type="number"
        name="price"
        step="0.01"
        min="0"
        required
    >
    <br><br>
    <label>
        Product Picture
    </label>
    <br>
    <input
        type="file"
        name="picture"
        accept="image/jpeg,image/png,image/webp"
    >
    <br><br>
    <button
        type="submit"
        name="add_product"
    >

        Add Product

    </button>


</form>
<hr>
<h2>Product List</h2>
<table
    border="1"
    cellpadding="10"
    cellspacing="0"
>
    <tr>
        <th>
            ID
        </th>
        <th>
            Picture
        </th>
        <th>
            Product Name
        </th>
        <th>
            Category
        </th>
        <th>
            Price
        </th>
        <th>
            Action
        </th>
    </tr>
    <?php while ($product = $products->fetch_assoc()): ?>
        <tr>
            <td>
                <?= $product["id"] ?>
            </td>
            <td>
                <?php if (!empty($product["picture"])): ?>
                    <img
                        src="../uploads/products/<?= htmlspecialchars($product["picture"]) ?>"
                        width="100"
                        height="100"
                        style="object-fit: contain;"
                    >
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>
            <td>
                <?= htmlspecialchars($product["pname"]) ?>
            </td>
            <td>
                <?= htmlspecialchars($product["category_name"]) ?>
            </td>
            <td>
                ₹<?= number_format(
                    $product["price"],
                    2
                ) ?>
            </td>
            <td>
                <a
                    href="products.php?delete=<?= $product["id"] ?>"
                    onclick="return confirm('Are you sure you want to delete this product?')"
                >
                    Delete
                </a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>