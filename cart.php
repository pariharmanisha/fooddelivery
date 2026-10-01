<?php

session_start();


/* Create cart if not exists */

if (!isset($_SESSION['cart'])) {

    $_SESSION['cart'] = [];

}


/* REMOVE ITEM */

if (isset($_GET['remove'])) {

    $id = intval($_GET['remove']);

    if (isset($_SESSION['cart'][$id])) {

        unset($_SESSION['cart'][$id]);

    }

    header("Location: cart.php");

    exit;

}


/* INCREASE */

if (isset($_GET['increase'])) {

    $id = intval($_GET['increase']);

    if (isset($_SESSION['cart'][$id])) {

        $_SESSION['cart'][$id]['quantity']++;

    }

    header("Location: cart.php");

    exit;

}


/* DECREASE */

if (isset($_GET['decrease'])) {

    $id = intval($_GET['decrease']);

    if (isset($_SESSION['cart'][$id])) {

        if ($_SESSION['cart'][$id]['quantity'] > 1) {

            $_SESSION['cart'][$id]['quantity']--;

        } else {

            unset($_SESSION['cart'][$id]);

        }

    }

    header("Location: cart.php");

    exit;

}


/* CLEAR CART */

if (isset($_GET['clear'])) {

    $_SESSION['cart'] = [];

    header("Location: cart.php");

    exit;

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>QuickBite - Cart</title>


<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #f8f8f8;
}


/* HEADER */

header {
    display: flex;
    justify-content: space-between;
    align-items: center;

    padding: 15px 60px;

    background: #ff5722;
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.logo h2 {
    color: white;
}

nav a {
    text-decoration: none;
    color: white;

    margin-left: 20px;

    font-size: 18px;
    font-weight: bold;
}

nav a:hover {
    color: yellow;
}


/* CART */

.cart-container {

    width: 90%;
    max-width: 1100px;

    margin: 40px auto;

}

.cart-container h1 {

    text-align: center;

    color: #ff5722;

    margin-bottom: 30px;

}


/* CART ITEM */

.cart-item {

    background: white;

    display: flex;

    align-items: center;

    gap: 25px;

    padding: 20px;

    margin-bottom: 20px;

    border-radius: 10px;

    box-shadow: 0 5px 15px rgba(0,0,0,.15);

}

.cart-item img {

    width: 130px;

    height: 100px;

    object-fit: cover;

    border-radius: 8px;

}

.food-details {

    flex: 1;

}

.food-details h2 {

    margin-bottom: 8px;

}

.food-details p {

    color: gray;

}

.price {

    color: #ff5722;

    font-weight: bold;

    font-size: 18px;

    margin-top: 8px;

}


/* QUANTITY */

.quantity {

    display: flex;

    align-items: center;

    gap: 12px;

}

.quantity a {

    text-decoration: none;

    background: #ff5722;

    color: white;

    padding: 6px 12px;

    border-radius: 5px;

    font-weight: bold;

}

.quantity span {

    font-weight: bold;

    font-size: 18px;

}


/* REMOVE */

.remove {

    text-decoration: none;

    background: #dc3545;

    color: white;

    padding: 9px 15px;

    border-radius: 5px;

}


/* TOTAL */

.cart-total {

    background: white;

    padding: 25px;

    border-radius: 10px;

    text-align: right;

    box-shadow: 0 5px 15px rgba(0,0,0,.15);

}

.cart-total h2 {

    color: #333;

    margin-bottom: 20px;

}

.clear {

    text-decoration: none;

    background: #555;

    color: white;

    padding: 12px 20px;

    border-radius: 5px;

    margin-right: 10px;

}

.checkout {

    text-decoration: none;

    background: #ff5722;

    color: white;

    padding: 12px 25px;

    border-radius: 5px;

}


/* EMPTY CART */

.empty-cart {

    background: white;

    padding: 60px;

    text-align: center;

    border-radius: 10px;

}

.empty-cart h2 {

    margin-bottom: 20px;

}

.empty-cart a {

    text-decoration: none;

    background: #ff5722;

    color: white;

    padding: 12px 25px;

    border-radius: 5px;

}

</style>

</head>


<body>


<!-- HEADER -->

<header>

    <div class="logo">

        <img src="image/logo11.jpg"
             alt="QuickBite">

        <h2>QuickBite</h2>

    </div>


    <nav>

        <a href="input.php">Home</a>

        <a href="menu.php">Menu</a>
<a href="myorder.php">Order</a>

        <a href="contact.php">Contact</a>

        <a href="about.php">About us</a>
<a href="profile.php">Profile</a>
        <a href="cart.php">🛒 Cart</a>

    </nav>

</header>



<!-- CART -->

<div class="cart-container">

<h1>🛒 Your Cart</h1>


<?php if (empty($_SESSION['cart'])) { ?>


    <div class="empty-cart">

        <h2>Your cart is empty</h2>

        <a href="menu.php">
            Go to Menu
        </a>

    </div>


<?php } else { ?>


<?php

$total = 0;


foreach ($_SESSION['cart'] as $item) {


    $subtotal =
        $item['price'] * $item['quantity'];


    $total += $subtotal;

?>


<div class="cart-item">


    <img
        src="image/<?php echo htmlspecialchars($item['image']); ?>"
        alt="<?php echo htmlspecialchars($item['food_name']); ?>"
    >


    <div class="food-details">

        <h2>

            <?php

            echo htmlspecialchars(
                $item['food_name']
            );

            ?>

        </h2>


        <p>

            <?php

            echo htmlspecialchars(
                $item['description']
            );

            ?>

        </p>


        <div class="price">

            ₹<?php echo $item['price']; ?>

        </div>

    </div>



    <!-- QUANTITY -->

    <div class="quantity">

        <a href="cart.php?decrease=<?php echo $item['id']; ?>">
            −
        </a>


        <span>

            <?php echo $item['quantity']; ?>

        </span>


        <a href="cart.php?increase=<?php echo $item['id']; ?>">
            +
        </a>

    </div>



    <!-- SUBTOTAL -->

    <strong>

        ₹<?php echo $subtotal; ?>

    </strong>



    <!-- REMOVE -->

    <a
        href="cart.php?remove=<?php echo $item['id']; ?>"
        class="remove"
    >

        Remove

    </a>


</div>


<?php

}

?>


<!-- TOTAL -->

<div class="cart-total">

    <h2>

        Total: ₹<?php echo $total; ?>

    </h2>


    <a
        href="cart.php?clear=1"
        class="clear"
        onclick="return confirm('Clear your cart?');"
    >

        Clear Cart

    </a>


    <a
        href="checkout.php"
        class="checkout"
    >

        Checkout

    </a>

</div>


<?php } ?>


</div>


</body>

</html>