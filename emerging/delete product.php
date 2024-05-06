<?php
include('Php/functions.php');
$conn = db_connect();
// Check if the product ID is provided in the URL
if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Prepare a SQL statement to delete the product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Product deleted successfully, redirect to products page
        header("Location: products.php");
        exit;
    } else {
        // Error occurred while deleting the product
        echo "<script>alert('Error deleting product: " . $conn->error . "');</script>";
        header("Location: products.php");
        exit;
    }
} else {
    // Redirect if no product ID is provided or it's not a valid number
    header("Location: products.php");
    exit;
}
?>