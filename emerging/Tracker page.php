<?php
include('Php/functions.php');

$conn = db_connect();

function getOrderDetails($orderId)
{
    global $conn;

    // Extended query to fetch related information
    $query = $conn->prepare("
        SELECT 
            o.id AS order_id, 
            o.order_date, 
            o.delivery_date,
            os.status AS order_status, 
            pm.provider AS payment_method, 
            CONCAT(a.street_address, ', ', a.city, ', ', a.region) AS shipping_address,
            oi.product_id, 
            oi.qty, 
            p.name AS product_name, 
            (p.price * oi.qty) AS subtotal
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        JOIN order_status os ON o.order_status_id = os.id
        JOIN user_payment_method pm ON o.payment_method_id = pm.id
        JOIN address a ON o.shipping_address_id = a.id
        WHERE o.id = ?
    ");
    $query->bind_param("i", $orderId);
    $query->execute();
    $result = $query->get_result();

    $orderDetails = [
        'items' => [],
        'order_status' => '',
        'order_date' => '',
        'order_id' => '',
        'delivery_date' => '',
        'payment_method' => '',
        'shipping_address' => ''
    ];

    while ($row = $result->fetch_assoc()) {
        $orderDetails['items'][] = $row;
        $orderDetails['order_status'] = $row['order_status'];
        $orderDetails['order_date'] = $row['order_date'];
        $orderDetails['order_id'] = $row['order_id'];
        $orderDetails['delivery_date'] = $row['delivery_date'];
        $orderDetails['payment_method'] = $row['payment_method'];
        $orderDetails['shipping_address'] = $row['shipping_address'];
    }

    return $orderDetails;
}
$orderDetails = getOrderDetails($_GET['order_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Order Tracker</title>
    <link rel="icon" type="image/x-icon" href="Image/web_logo.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Style/bootstrap.min.css">
    <!--Nadia-->
    <link rel="stylesheet" href="Style/global.css">
    <link rel="stylesheet" href="Style/trackerstyle.css">
    <link rel="stylesheet" href="Style/styles1.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="navbar">
    <h1>Ivatan Store</h1>
        <div class="search-container">
        <form action="search.php" method="get">
        <img src="Image/furco_search.png" alt="Search" class="search-icon">
        <input type="search" name="query" placeholder="Search" required>
            </form>
        </div>
         <!--Nadia-->
        <?php require_once("Php/navigation.php"); ?>
    </div>
    <div class="container">
        <div class="card">
            <div class="title">Purchase Reciept</div>
            <div class="info">
                <div class="row">
                    <div class="col-9">
                        <span id="heading">Date</span><br>
                        <span id="details"><?= $orderDetails['order_date']; ?></span>
                    </div>
                    <div class="col-3">
                        <span id="heading">Order No.</span><br>
                        <span id="details"><?= $orderDetails['order_id']; ?></span>
                    </div>
                </div>
            </div>
            <div class="pricing">
                <?php
                $total = 0;
                foreach ($orderDetails['items'] as $item) :
                    $total += $item['subtotal'];
                    $base_price = $item['subtotal'] / $item['qty']; // Calculate base price per unit
                ?>
                    <div class="row">
                        <div class="col-9">
                            <!-- Display product name and quantity -->
                            <span id="name"><?= htmlspecialchars($item['product_name']) ?> (<?= $item['qty'] ?> x ₱<?= number_format($base_price, 2) ?>)</span>
                        </div>
                        <div class="col-3">
                            <!-- Display subtotal for this item -->
                            <span id="price">₱<?= number_format($item['subtotal'], 2) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="row">
                    <div class="col-9">
                        <span id="name">Shipping (Flat-Rate)</span>
                    </div>
                    <div class="col-3">
                        <span id="price">₱58.00</span>
                    </div>
                </div>
            </div>
            <div class="total">
                <div class="row">
                    <div class="col-9"></div>
                    <div class="col-3"><big>₱<?= number_format($total + 58, 2) ?></big></div>
                </div>
            </div>
            <div class="tracking">
                <div class="title">Order Tracking</div>
            </div>
            <div class="progress-track">
                <ul id="progressbar">
                    <li class="step0 <?= ($orderDetails['order_status'] == 'Ordered' || $orderDetails['order_status'] == 'Shipped' || $orderDetails['order_status'] == 'On the way' || $orderDetails['order_status'] == 'Delivered') ? 'active' : '' ?>" id="step1" style="font-size: 16px;">Ordered</li>
                    <li class="step0 <?= ($orderDetails['order_status'] == 'Shipped' || $orderDetails['order_status'] == 'On the way' || $orderDetails['order_status'] == 'Delivered') ? 'active' : '' ?>" id="step2" style="font-size: 16px; padding-left:0px;">Shipped</li>
                    <li class="step0 <?= ($orderDetails['order_status'] == 'On the way' || $orderDetails['order_status'] == 'Delivered') ? 'active' : '' ?>" id="step3" style="font-size: 16px">On the way</li>
                    <li class="step0 <?= ($orderDetails['order_status'] == 'Delivered') ? 'active' : '' ?>" id="step4" style="font-size: 16px">Delivered</li>
                </ul>
            </div>
        </div>
    </div>
    <!--Nadia-->
    <?= template_footer() ?>
    <!--Nadia-->
</body>
</html>