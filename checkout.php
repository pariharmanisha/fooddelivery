<?php

session_start();

include "db.php";


/* =====================================
   CHECK USER LOGIN
===================================== */

if (!isset($_SESSION['user_id'])) {

    // Remember that user came from checkout
    $_SESSION['checkout_redirect'] = true;

    echo "<script>
        alert('Please login first to checkout.');
        window.location.href='signin.php';
    </script>";

    exit();
}


$user_id = $_SESSION['user_id'];


/* =====================================
   CHECK CART
===================================== */

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {

    echo "<script>
        alert('Your cart is empty.');
        window.location.href='cart.php';
    </script>";

    exit();
}


/* =====================================
   GET USER DETAILS
===================================== */

$user_name = "";
$user_email = "";

$stmt = mysqli_prepare(
    $conn,
    "SELECT name, email FROM users WHERE id = ?"
);

if (!$stmt) {
    die("User Prepare Failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {

    $user_name = $user['name'];
    $user_email = $user['email'];

}

mysqli_stmt_close($stmt);


/* =====================================
   CALCULATE TOTAL
===================================== */

$total = 0;

foreach ($_SESSION['cart'] as $item) {

    $price = isset($item['price'])
        ? (float)$item['price']
        : 0;

    $quantity = isset($item['quantity'])
        ? (int)$item['quantity']
        : 1;

    $total += $price * $quantity;
}


/* =====================================
   PLACE ORDER
===================================== */

if (isset($_POST['place_order'])) {

    /* ---------------------------------
       VALIDATE ADDRESS
    --------------------------------- */

    $address = trim($_POST['address']);

    if (empty($address)) {

        echo "<script>
            alert('Please enter your delivery address.');
        </script>";

    } else {

        /* ---------------------------------
           INSERT EACH CART ITEM
        --------------------------------- */

        $success = true;

        foreach ($_SESSION['cart'] as $item) {

            $food_id = isset($item['id'])
                ? (int)$item['id']
                : 0;

            $quantity = isset($item['quantity'])
                ? (int)$item['quantity']
                : 1;

            $price = isset($item['price'])
                ? (float)$item['price']
                : 0;

            $item_total = $price * $quantity;


            /* INSERT INTO ORDERS */

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO orders
                (user_id, food_id, quantity, total_price, status)
                VALUES (?, ?, ?, ?, 'Pending')"
            );


            if (!$stmt) {

                $success = false;

                break;
            }


            mysqli_stmt_bind_param(
                $stmt,
                "iiid",
                $user_id,
                $food_id,
                $quantity,
                $item_total
            );


            if (!mysqli_stmt_execute($stmt)) {

                $success = false;

                mysqli_stmt_close($stmt);

                break;
            }


            mysqli_stmt_close($stmt);
        }


        /* ---------------------------------
           ORDER SUCCESS
        --------------------------------- */

        if ($success) {

            /* Clear cart */

            unset($_SESSION['cart']);


            echo "<script>

                alert('Order placed successfully!');

                window.location.href='menu.php';

            </script>";

            exit();

        } else {

            echo "<script>

                alert('Something went wrong while placing your order.');

            </script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Checkout - QuickBite</title>


<style>

/* =========================
   RESET
========================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


/* =========================
   BODY
========================= */

body {

    font-family: Arial, sans-serif;

    background: #f7f7f7;

    color: #333;

}


/* =========================
   HEADER
========================= */

header {

    height: 75px;

    background: #ff5722;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 50px;

}


/* =========================
   LOGO
========================= */

.logo {

    display: flex;

    align-items: center;

    gap: 10px;

}

.logo img {

    width: 55px;

    height: 55px;

    border-radius: 50%;

    object-fit: cover;

}

.logo h2 {

    color: white;

    font-size: 25px;

}


/* =========================
   NAVIGATION
========================= */

nav {

    display: flex;

    gap: 25px;

}

nav a {

    color: white;

    text-decoration: none;

    font-weight: bold;

}

nav a:hover {

    color: #ffe0d6;

}


/* =========================
   MAIN CONTAINER
========================= */

.checkout-container {

    width: 90%;

    max-width: 1100px;

    margin: 40px auto;

}


/* =========================
   TITLE
========================= */

.checkout-title {

    text-align: center;

    margin-bottom: 30px;

}

.checkout-title h1 {

    color: #ff5722;

    font-size: 38px;

}

.checkout-title p {

    margin-top: 8px;

    color: #777;

}


/* =========================
   GRID
========================= */

.checkout-grid {

    display: grid;

    grid-template-columns: 1fr 400px;

    gap: 30px;

}


/* =========================
   BOX
========================= */

.checkout-box,
.summary-box {

    background: white;

    padding: 30px;

    border-radius: 12px;

    box-shadow: 0 5px 20px rgba(0,0,0,0.08);

}


/* =========================
   HEADINGS
========================= */

.checkout-box h2,
.summary-box h2 {

    margin-bottom: 25px;

}


/* =========================
   FORM
========================= */

.form-group {

    margin-bottom: 20px;

}


.form-group label {

    display: block;

    margin-bottom: 8px;

    font-weight: bold;

}


.form-group input,
.form-group textarea {

    width: 100%;

    padding: 13px;

    border: 1px solid #ddd;

    border-radius: 7px;

    font-size: 15px;

    outline: none;

}


.form-group input:focus,
.form-group textarea:focus {

    border-color: #ff5722;

}


.form-group textarea {

    height: 110px;

    resize: none;

}


/* =========================
   PAYMENT
========================= */

.payment-method {

    margin-top: 20px;

}

.payment-option {

    background: #f7f7f7;

    padding: 13px;

    margin-top: 10px;

    border-radius: 7px;

}


.payment-option input {

    margin-right: 8px;

}


/* =========================
   ORDER ITEM
========================= */

.order-item {

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 15px 0;

    border-bottom: 1px solid #eee;

}


.order-item-name {

    font-weight: bold;

}


.order-item-qty {

    font-size: 14px;

    color: #777;

    margin-top: 5px;

}


.order-item-price {

    color: #ff5722;

    font-weight: bold;

}


/* =========================
   TOTAL
========================= */

.total {

    display: flex;

    justify-content: space-between;

    margin-top: 25px;

    padding-top: 20px;

    border-top: 2px solid #eee;

}


.total h3 {

    font-size: 22px;

}


.total-price {

    color: #ff5722;

}


/* =========================
   BUTTON
========================= */

.place-order {

    width: 100%;

    padding: 15px;

    margin-top: 25px;

    background: #ff5722;

    color: white;

    border: none;

    border-radius: 8px;

    font-size: 18px;

    font-weight: bold;

    cursor: pointer;

}


.place-order:hover {

    background: #e64a19;

}


/* =========================
   MOBILE
========================= */

@media(max-width: 800px) {

    header {

        padding: 0 20px;

    }

    nav {

        gap: 10px;

    }

    nav a {

        font-size: 13px;

    }

    .checkout-grid {

        grid-template-columns: 1fr;

    }

}

</style>

</head>


<body>


<!-- =========================
     HEADER
========================= -->

<header>

    <div class="logo">

        <img
            src="image/logo11.jpg"
            alt="QuickBite Logo"
        >

        <h2>QuickBite</h2>

    </div>


    <nav>

        <a href="input.php">Home</a>

        <a href="menu.php">Menu</a>

        <a href="myorder.php">Order</a>

        <a href="about.php">About us</a>

        <a href="contact.php">Contact us</a>

        <a href="profile.php">Profile</a>

        <a href="cart.php">🛒 Cart</a>

    </nav>

</header>



<!-- =========================
     CHECKOUT
========================= -->

<div class="checkout-container">


    <div class="checkout-title">

        <h1>Checkout</h1>

        <p>Complete your order</p>

    </div>



    <div class="checkout-grid">


        <!-- =========================
             CUSTOMER DETAILS
        ========================= -->

        <div class="checkout-box">

            <h2>Delivery Details</h2>


            <form
                method="POST"
                onsubmit="return validateCheckout();"
            >


                <!-- NAME -->

                <div class="form-group">

                    <label>Full Name</label>

                    <input
                        type="text"
                        value="<?php
                        echo htmlspecialchars($user_name);
                        ?>"
                        readonly
                    >

                </div>


                <!-- EMAIL -->

                <div class="form-group">

                    <label>Email</label>

                    <input
                        type="email"
                        value="<?php
                        echo htmlspecialchars($user_email);
                        ?>"
                        readonly
                    >

                </div>


                <!-- ADDRESS -->

                <div class="form-group">

                    <label>Delivery Address</label>

                    <textarea
                        name="address"
                        id="address"
                        placeholder="Enter your complete address"
                    ></textarea>

                </div>


                <!-- PAYMENT -->

                <div class="payment-method">

                    <h3>Payment Method</h3>


                    <div class="payment-option">

                        <label>

                            <input
                                type="radio"
                                name="payment"
                                value="cod"
                                checked
                            >

                            Cash on Delivery

                        </label>

                    </div>


                    <div class="payment-option">

                        <label>

                            <input
                                type="radio"
                                name="payment"
                                value="upi"
                            >

                            UPI

                        </label>

                    </div>


                    <div class="payment-option">

                        <label>

                            <input
                                type="radio"
                                name="payment"
                                value="card"
                            >

                            Debit / Credit Card

                        </label>

                    </div>

                </div>


                <button
                    type="submit"
                    name="place_order"
                    class="place-order"
                >

                    Place Order

                </button>


            </form>

        </div>



        <!-- =========================
             ORDER SUMMARY
        ========================= -->

        <div class="summary-box">

            <h2>Order Summary</h2>


            <?php

            foreach ($_SESSION['cart'] as $item):


                /*
                 * GET FOOD NAME
                 *
                 * First check food_name.
                 * If not available, check name.
                 */

                if (isset($item['food_name']) && !empty($item['food_name'])) {

                    $item_name = $item['food_name'];

                } elseif (isset($item['name']) && !empty($item['name'])) {

                    $item_name = $item['name'];

                } else {

                    $item_name = "Unknown Food";

                }


                $item_price =
                    isset($item['price'])
                    ? (float)$item['price']
                    : 0;


                $item_quantity =
                    isset($item['quantity'])
                    ? (int)$item['quantity']
                    : 1;


                $item_total =
                    $item_price * $item_quantity;

            ?>


                <div class="order-item">


                    <div>

                        <div class="order-item-name">

                            <?php

                            echo htmlspecialchars($item_name);

                            ?>

                        </div>


                        <div class="order-item-qty">

                            Quantity:

                            <?php

                            echo $item_quantity;

                            ?>

                        </div>

                    </div>


                    <div class="order-item-price">

                        ₹<?php

                        echo number_format(
                            $item_total,
                            2
                        );

                        ?>

                    </div>


                </div>


            <?php endforeach; ?>



            <!-- TOTAL -->

            <div class="total">

                <h3>Total</h3>

                <h3 class="total-price">

                    ₹<?php

                    echo number_format(
                        $total,
                        2
                    );

                    ?>

                </h3>

            </div>


        </div>


    </div>

</div>



<script>

/* =====================================
   CHECKOUT VALIDATION
===================================== */

function validateCheckout() {

    let address =
        document.getElementById("address").value.trim();


    if (address === "") {

        alert("Please enter your delivery address.");

        return false;

    }


    return true;

}

</script>


</body>

</html>