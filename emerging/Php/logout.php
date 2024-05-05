<?php
// logout.php
include 'functions.php';
logout();
header('Location: ../home.php'); // Redirect to the homepage in the parent directory
exit;
?>