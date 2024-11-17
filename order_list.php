<?php
session_start();

include("connect.php");

// Get customer_id from the session (assuming the user is already authenticated)
$customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;

// Fetch orders for the logged-in user
$sqlOrders = "SELECT * FROM orders WHERE customer_id = '$customer_id' ORDER BY order_date DESC";
$resultOrders = $conn->query($sqlOrders);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("header.php"); ?>

<!-- Order List -->
<div class="container mt-4">
    <h2>Your Order List</h2>

    <?php if ($resultOrders->num_rows > 0) { ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($rowOrder = $resultOrders->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $rowOrder['order_id']; ?></td>
                        <td>$<?php echo $rowOrder['total_amount']; ?></td>
                        <td><?php echo $rowOrder['status']; ?></td>
                        <td><?php echo $rowOrder['order_date']; ?></td>
                        <td>
                            <a href="order_details.php?order_id=<?php echo $rowOrder['order_id']; ?>">View Details</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <div class="alert alert-info" role="alert">
            You have no orders yet.
        </div>
    <?php } ?>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
