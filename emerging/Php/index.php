<?php
include 'functions.php'; // Include only once
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: index.php?page=login');
    exit;
}

$page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'home';
$pagePath = $page . '.php';
if (file_exists($pagePath)) {
    include $pagePath;
} else {
    echo "Error: The requested page does not exist.";
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: index.php?page=login");
    exit;
}
?>