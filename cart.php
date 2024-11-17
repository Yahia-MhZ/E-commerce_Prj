<?php
session_start();

include("connect.php");

// Remove product from the cart if the "Remove" button is clicked
if (isset($_GET['remove']) && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Check if the product is in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If the quantity is greater than 1, decrement it; otherwise, remove the item from the cart
        if ($_SESSION['cart'][$product_id] > 1) {
            $_SESSION['cart'][$product_id]--;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include("header.php"); ?>


<!-- Cart Listing -->
<div class="container mt-4">
    <h2>Your Shopping Cart</h2>

    <?php if (count($cartProducts) > 0) { ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalPrice = 0;
                foreach ($cartProducts as $cartProduct) {
                    $subtotal = $cartProduct['price'] * $cartProduct['quantity'];
                    $totalPrice += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo $cartProduct['product_name']; ?></td>
                        <td><?php echo $cartProduct['description']; ?></td>
                        <td>$<?php echo $cartProduct['price']; ?></td>
                        <td><?php echo $cartProduct['quantity']; ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <a href="?remove=true&product_id=<?php echo $cartProduct['product_id']; ?>" class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                    <td>$<?php echo number_format($totalPrice, 2); ?></td>
                    <td>
                        <form method="post" action="confirm_order.php">
                            <button type="submit" class="btn btn-success" name="confirm_order">Confirm Order</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php } else { ?>
        <div class="alert alert-info" role="alert">
            Your cart is empty. <a href="index.php" class="alert-link">Continue shopping</a>.
        </div>
    <?php } ?>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
