<?php
  function checkout($user_id,$order_date,$payment,$address_id,$delivery_date,$status,$cartItems,$totalPrice)
  {
  $conn = db_connect();



  $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, payment_method_id, shipping_address_id, order_total, delivery_date, order_status_id) VALUES (?,?,?,?,?,?,?)");
  $stmt->bind_param("ississs", $user_id, $order_date , $payment, $address_id, $totalPrice, $delivery_date, $status);
  $stmt->execute();

  foreach ($cartItems as $item) {
      $product_id = $item['product_id'];
      $order_id = $stmt->insert_id;
      $quantity = $item['qty'];

      $stmt = $conn->prepare("INSERT INTO order_items (product_id, order_id, qty, subtotal) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("iiss", $product_id, $order_id, $quantity, $totalPrice);
      $stmt->execute();
  }
}

function clearCart($user_id, $conn) {
  $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  }
?>