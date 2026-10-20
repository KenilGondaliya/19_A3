<?php

$directory = "uploads/products";

if (!is_dir($directory)) {

    die("Directory does not exist.");

}

$files =
    scandir($directory);

echo "<h2>Files</h2>";

echo "<ul>";

foreach ($files as $file) {

    if (
        $file == "." ||
        $file == ".."
    ) {
        continue;
    }

    $path =
        $directory .
        "/" .
        $file;

    if (is_file($path)) {

        echo "<li>";
        echo "<a href='$path'>";
        echo htmlspecialchars($file);
        echo "</a>";
        echo "</li>";
    }
}

echo "</ul>";

?>