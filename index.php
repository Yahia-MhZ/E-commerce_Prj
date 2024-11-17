<?php
session_start();

include("connect.php");

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    // Check if the product is already in the cart
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1; // Add the product to the cart with quantity 1
    } else {
        $_SESSION['cart'][$product_id]++; // Increment the quantity if the product is already in the cart
    }
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("header.php"); ?>


<!-- Product Listing -->
<div class="container mt-4">
    <div class="row">

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo $row["image"]; ?>" class="card-img-top" height="150px" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row["product_name"]; ?></h5>
                            <p class="card-text"><?php echo $row["description"]; ?></p>
                            <p class="card-text">Price: $<?php echo $row["price"]; ?></p>
                            <p class="card-text">Stock Quantity: <?php echo $row["stock_quantity"]; ?></p>
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                <button type="submit" class="btn btn-primary" name="add_to_cart">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No products found in the database.</p>";
        }
        $conn->close();
        ?>

    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Custom JavaScript for Add to Cart functionality -->
<script>
    function addToCart(productId) {
        // You can implement the logic to add the product to the cart here
        alert('Product added to cart! Product ID: ' + productId);
    }
</script>

</body>
</html>
