<?php
session_start();

include("connect.php");

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

// Get customer_id from the session
$customer_id = $_SESSION['customer_id'];

// Fetch products in the cart
$cartProducts = [];
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $cartProductIds = array_keys($_SESSION['cart']);
    $cartProductIdsString = implode(",", $cartProductIds);

    // Check if $cartProductIdsString is not empty before executing the SQL query
    if (!empty($cartProductIdsString)) {
        $sqlCart = "SELECT * FROM products WHERE product_id IN ($cartProductIdsString)";
        $resultCart = $conn->query($sqlCart);

        while ($rowCart = $resultCart->fetch_assoc()) {
            $rowCart['quantity'] = $_SESSION['cart'][$rowCart['product_id']];
            $cartProducts[] = $rowCart;
        }
    }
}

// Insert order into the orders table
$orderTotal = 0;
foreach ($cartProducts as $cartProduct) {
    $orderTotal += $cartProduct['price'] * $cartProduct['quantity'];
}

// Insert the order details into the orders table
$sqlInsertOrder = "INSERT INTO orders (customer_id, total_amount, order_date) VALUES ('$customer_id', '$orderTotal', NOW())";
if ($conn->query($sqlInsertOrder) === TRUE) {
    $orderId = $conn->insert_id;

    // Insert order items into the order_items table
    foreach ($cartProducts as $cartProduct) {
        $productId = $cartProduct['product_id'];
        $quantity = $cartProduct['quantity'];

        $sqlInsertOrderItem = "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('$orderId', '$productId', '$quantity')";
        $conn->query($sqlInsertOrderItem);
    }

    // Clear the cart after the order is confirmed
    unset($_SESSION['cart']);

    header("Location: index.php"); // Redirect to the home page or order confirmation page
    exit();
} else {
    // Handle the case where the order insertion fails
    echo "Error: " . $conn->error;
}

$conn->close();
?>
