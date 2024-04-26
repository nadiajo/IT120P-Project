<?php
include 'functions.php';
$conn = db_connect();

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_data = $stmt->get_result()->fetch_assoc();

$sql_activity = "SELECT * FROM order_activity WHERE order_id = ?";
$stmt_activity = $conn->prepare($sql_activity);
$stmt_activity->bind_param("i", $order_id);
$stmt_activity->execute();
$order_activity_data = $stmt_activity->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$stmt_activity->close();
$conn->close();
?>