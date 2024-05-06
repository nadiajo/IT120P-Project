<?php
include 'functions.php';
$conn = db_connect(); // Use the centralized connection function

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM products LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product-item'>{$row['product_name']}</div>";
    }
} else {
    echo "No products found.";
}

$total_products_sql = "SELECT COUNT(*) FROM products";
$total_result = $conn->query($total_products_sql);
$total_products = $total_result->fetch_row()[0];

if ($total_products > $page * $limit) {
    echo "<a href='?page=" . ($page + 1) . "' class='view-more'>View More</a>";
}

$conn->close();
?>
