<?php
include('Php/functions.php');
include('Php/checkout.php');
$conn = db_connect();
$user_id = $_SESSION['user_id'] ?? 0;
?>

<html>
<!--Nadia-->
<head>
  <meta charset="utf-8">
  <title>Success</title>
  <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Style/success style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>




<body>
  <div class="card">
    <div class="header">
      <div class="content">
        <img src="Image/check.png" class = "img">
        <br></br>
        <span class="title">Thank you for ordering!</span>
        <p class="message">Your order will be delivered within 3-5 business days.</p>
      </div>
      <div class="actions">
        <button class="history" type="button"><a href="OrderHistoryPage.php">Go to Order History</button></a>
        <button class="home" type="button"><a href="home.php">Go to Home</button></a>
      </div>
    </div>
  </div>
</body>

</html>
<!--Nadia-->