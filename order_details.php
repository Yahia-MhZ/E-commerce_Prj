<?php
session_start();

include("connect.php");

// Get customer_id from the session (assuming the user is already authenticated)
$customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;

// Check if the order_id is provided in the query string
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order details for the specified order_id
    $sqlOrderDetails = "SELECT * FROM orders WHERE order_id = '$order_id' AND customer_id = '$customer_id'";
    $resultOrderDetails = $conn->query($sqlOrderDetails);

    // Fetch order items for the specified order_id
    $sqlOrderItems = "SELECT oi.*, p.product_name, p.price FROM order_items oi JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = '$order_id'";
    $resultOrderItems = $conn->query($sqlOrderItems);

    if ($resultOrderDetails->num_rows == 1) {
        $rowOrderDetails = $resultOrderDetails->fetch_assoc();
    } else {
        // Order not found or doesn't belong to the logged-in user
        header("Location: order_list.php");
        exit();
    }
} else {
    // Invalid or missing order_id parameter
    header("Location: order_list.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("header.php"); ?>

<!-- Order Details -->
<div class="container mt-4">
    <h2>Order Details - Order ID: <?php echo $order_id; ?></h2>

    <p>Total Amount: $<?php echo $rowOrderDetails['total_amount']; ?></p>
    <p>Order Date: <?php echo $rowOrderDetails['order_date']; ?></p>

    <h3>Order Items</h3>
    <?php if ($resultOrderItems->num_rows > 0) { ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($rowOrderItem = $resultOrderItems->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $rowOrderItem['product_name']; ?></td>
                        <td>$<?php echo $rowOrderItem['price']; ?></td>
                  
                        <td><?php echo $rowOrderItem['quantity']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <div class="alert alert-info" role="alert">
            No items found in this order.
        </div>
    <?php } ?>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
