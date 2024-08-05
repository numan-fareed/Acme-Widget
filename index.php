<?php
session_start(); // Start the session to handle user sessions
require_once './Basket.php'; // Include the Basket class
include('./functions.php'); // Include the functions file

// Define the product catalogue by including the products.php file
$products = include('./products.php');

// Check if the session 'basket' is set and is a string, if not, initialize it
if (!isset($_SESSION['basket']) || !is_string($_SESSION['basket'])) {
    $_SESSION['basket'] = serialize(new Basket($products)); // Serialize a new Basket object into the session
}

// If the session 'basket' is a string, unserialize it to get the Basket object
if (is_string($_SESSION['basket'])) {
    try {
        $basket = unserialize($_SESSION['basket']); // Unserialize the session 'basket' string
    } catch (Exception $e) {
        echo 'Error: ', $e->getMessage(); // Display error message if unserialization fails
        exit; // Stop further execution if there is an error
    }
} else {
    echo 'Session data is not a valid serialized string.'; // Display error if session data is not valid
    exit;
}

// Check if a product code is posted to add to the cart
if (isset($_POST['add_product_code'])) {
    try {
        $basket->add($_POST['add_product_code']); // Add the product to the basket
        $_SESSION['basket'] = serialize($basket); // Serialize the updated basket into the session
    } catch (Exception $e) {
        echo $e->getMessage(); // Display error message if adding fails
    }
}

// Check if a product code is posted to remove from the cart
if (isset($_POST['remove_product_code'])) {
    try {
        $basket->remove($_POST['remove_product_code']); // Remove the product from the basket
        $_SESSION['basket'] = serialize($basket); // Serialize the updated basket into the session
    } catch (Exception $e) {
        echo $e->getMessage(); // Display error message if removing fails
    }
}

// Get the current items in the cart and calculate the total price and duties
$cartItems = $basket->getCart();
$total = $basket->calculatePrice();
$duties = $basket->calculateDuties();

// Initialize the R1 offer value
$r1offer = 0;
if (isset($cartItems) && isset($cartItems['R01'])) {
    $r1offer = redWidgetOffer($cartItems['R01'], $products); // Calculate the R1 offer if R01 is in the cart
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acme Widget Co - Product Selection</title>
    <link rel="stylesheet" href="./css/styles.css"> <!-- Link to external CSS file -->
</head>

<body>
    <div class="container">
        <h1>Acme Widget Co</h1>
        <h2>Products</h2>
        <!-- Form to clear the session -->
        <form action="clear_session.php" method="post">
            <button type="submit" name="clear_session">Clear Session</button>
        </form>

        <!-- Display the product list -->
        <div id="product-list">
            <?php foreach ($products as $code => $product) : ?>
                <div class="product">
                    <h3><?php echo $product['name']; ?></h3> <!-- Display product name -->
                    <p>Code: <?php echo $code; ?></p> <!-- Display product code -->
                    <p>Price: <?php echo formatPrice($product['price']); ?></p> <!-- Display product price -->
                    <!-- Form to add the product to the cart -->
                    <form method="post" action=".">
                        <input type="hidden" name="add_product_code" value="<?php echo $code; ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Shopping Cart</h2>
        <!-- Display the shopping cart items -->
        <div id="cart">
            <?php if (empty($cartItems)) : ?>
                <p class="center">Your cart is empty.</p> <!-- Message if the cart is empty -->
            <?php else : ?>
                <?php foreach ($cartItems as $code => $item) : ?>
                    <div class="cart-item">
                        <h4><?php echo $item['name']; ?></h4> <!-- Display item name -->
                        <p>Quantity: <?php echo $item['quantity']; ?></p> <!-- Display item quantity -->
                        <p>Price: <?php echo formatPrice($item['price'], 2); ?></p> <!-- Display item price -->
                        <p>Total: <?php echo formatPrice($item['price'] * $item['quantity']); ?></p> <!-- Display total price for the item -->
                        <!-- Form to remove the product from the cart -->
                        <form method="post" action=".">
                            <input type="hidden" name="remove_product_code" value="<?php echo htmlspecialchars($code); ?>">
                            <button type="submit">-</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Display the total calculations if the cart is not empty -->
        <?php if (!empty($cartItems)) : ?>
            <div id="total">
                <p>Price: <?php echo formatPrice($total); ?></p> <!-- Display total price -->
                <p>Delivery Charges: <?php echo formatPrice($duties); ?></p> <!-- Display delivery charges -->
                <p>R1 Offer: <?php echo formatPrice($r1offer); ?></p> <!-- Display R1 offer -->
                <hr>
                <h3>Total Amount: <?php echo formatPrice($total + $duties - $r1offer); ?></h3> <!-- Display the final total amount -->
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
