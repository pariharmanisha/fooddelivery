<?php

session_start();

include "db.php";


/* =========================================
   CHECK USER LOGIN
========================================= */

if (!isset($_SESSION['user_id'])) {

    header("Location: signin.php");
    exit();

}

$user_id = $_SESSION['user_id'];


/* =========================================
   GET LOGGED-IN USER NAME
========================================= */

$user_name = "";

$user_query = "SELECT name FROM users WHERE id = ?";

$user_stmt = mysqli_prepare($conn, $user_query);

if (!$user_stmt) {
    die("User Prepare Failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $user_stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($user_stmt);

$user_result = mysqli_stmt_get_result($user_stmt);

if ($user_row = mysqli_fetch_assoc($user_result)) {

    $user_name = $user_row['name'];

}

mysqli_stmt_close($user_stmt);


/* =========================================
   GET ONLY LOGGED-IN USER ORDERS
========================================= */

$order_query = "
    SELECT 
        orders.id,
        orders.user_id,
        orders.food_id,
        orders.quantity,
        orders.total_price,
        orders.status,
        orders.order_date,
        foods.food_name,
        foods.image

    FROM orders

    LEFT JOIN foods
        ON orders.food_id = foods.id

    WHERE orders.user_id = ?

    ORDER BY orders.id ASC
";


$order_stmt = mysqli_prepare($conn, $order_query);

if (!$order_stmt) {

    die("Order Prepare Failed: " . mysqli_error($conn));

}


mysqli_stmt_bind_param(
    $order_stmt,
    "i",
    $user_id
);


/* Execute order query */

mysqli_stmt_execute($order_stmt);

$result = mysqli_stmt_get_result($order_stmt);


/* =========================================
   USER ORDER NUMBER STARTS FROM 1
========================================= */

$order_number = 1;

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Orders - QuickBite</title>


<style>

/* =========================================
   RESET
========================================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}


/* =========================================
   BODY
========================================= */

body {
    background: #f8f8f8;
}


/* =========================================
   HEADER
========================================= */

header {
    width: 100%;
    height: 76px;

    display: flex;
    justify-content: space-between;
    align-items: center;

    padding: 8px 55px;

    background: #ff5722;
}


/* =========================================
   LOGO
========================================= */

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
    font-size: 22px;
    font-weight: bold;
}


/* =========================================
   NAVIGATION
========================================= */

nav {
    display: flex;
    align-items: center;
}

nav a {
    text-decoration: none;

    color: white;

    margin-left: 22px;

    font-size: 17px;

    font-weight: bold;

    white-space: nowrap;
}


/* =========================================
   HOVER / ACTIVE
========================================= */

nav a:hover,
nav .active {
    color: yellow;
}


/* =========================================
   CART
========================================= */

nav a.cart {
    font-size: 21px;
    color: #111;
}

nav a.cart:hover {
    color: yellow;
}


/* =========================================
   CONTAINER
========================================= */

.container {
    width: 92%;
    max-width: 1100px;

    margin: 40px auto;
}


/* =========================================
   TITLE
========================================= */

.page-title {
    margin-bottom: 25px;
}

.page-title h1 {
    font-size: 32px;
    margin-bottom: 8px;
}

.page-title .welcome {
    font-size: 30px;
    margin-bottom: 5px;
}

.page-title .welcome span {
    color: #111;
}

.page-title p {
    color: #777;
    font-size: 15px;
}


/* =========================================
   ORDER CARD
========================================= */

.order-card {
    background: white;

    border-radius: 12px;

    padding: 20px;

    margin-bottom: 20px;

    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}


/* =========================================
   ORDER TOP
========================================= */

.order-top {
    display: flex;

    justify-content: space-between;

    align-items: center;

    padding-bottom: 15px;

    margin-bottom: 18px;

    border-bottom: 1px solid #eee;
}


.order-id {
    font-size: 18px;

    font-weight: bold;
}


.order-date {
    color: #777;

    font-size: 14px;
}


/* =========================================
   ORDER CONTENT
========================================= */

.order-content {
    display: flex;

    align-items: center;

    gap: 20px;
}


/* =========================================
   FOOD IMAGE
========================================= */

.food-image {
    width: 90px;

    height: 90px;

    border-radius: 10px;

    object-fit: cover;
}


.no-image {
    width: 90px;

    height: 90px;

    border-radius: 10px;

    background: #eee;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #777;

    font-size: 13px;

    text-align: center;
}


/* =========================================
   FOOD DETAILS
========================================= */

.food-details {
    flex: 1;
}


.food-name {
    font-size: 19px;

    font-weight: bold;

    margin-bottom: 8px;
}


.quantity {
    color: #666;

    font-size: 15px;
}


/* =========================================
   PRICE
========================================= */

.price {
    font-size: 19px;

    font-weight: bold;

    margin-right: 25px;
}


/* =========================================
   STATUS
========================================= */

.status {
    padding: 8px 16px;

    border-radius: 20px;

    font-size: 13px;

    font-weight: bold;

    display: inline-block;
}


/* Pending */

.pending {
    background: #fff0c7;

    color: #9a6700;
}


/* Completed */

.completed {
    background: #d7f0df;

    color: #08752d;
}


/* Cancelled */

.cancelled {
    background: #f8d7da;

    color: #a00000;
}


/* =========================================
   NO ORDERS
========================================= */

.no-orders {
    background: white;

    padding: 60px 20px;

    text-align: center;

    border-radius: 12px;

    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}


.no-orders h2 {
    margin-bottom: 10px;
}


.no-orders p {
    color: #777;

    margin-bottom: 20px;
}


.menu-btn {
    display: inline-block;

    background: #ff5722;

    color: white;

    padding: 11px 22px;

    border-radius: 7px;

    text-decoration: none;

    font-weight: bold;
}


.menu-btn:hover {
    background: #e64a19;
}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 900px) {

    header {
        padding: 10px 20px;
    }

    nav a {
        margin-left: 12px;
        font-size: 15px;
    }

}


@media (max-width: 700px) {

    header {
        height: auto;

        flex-direction: column;

        gap: 15px;

        padding: 15px 20px;
    }


    nav {
        gap: 12px;

        flex-wrap: wrap;

        justify-content: center;
    }


    nav a {
        margin-left: 8px;

        font-size: 14px;
    }


    .container {
        width: 94%;
    }


    .page-title .welcome {
        font-size: 24px;
    }


    .order-content {
        flex-wrap: wrap;
    }


    .price {
        margin-right: 0;
    }

}

</style>

</head>


<body>


<!-- =========================================
     HEADER
========================================= -->

<header>

    <div class="logo">

        <img src="image/logo11.jpg" alt="Logo">

        <h2>QuickBite</h2>

    </div>


    <nav>

        <a href="input.php">
            Home
        </a>

        <a href="menu.php">
            Menu
        </a>

        <a href="myorder.php" class="active">
            Orders
        </a>

        <a href="contact.php">
            Contact us
        </a>

        <a href="about.php">
            About us
        </a>

        <a href="profile.php">
            Profile
        </a>
  <a href="cart.php">🛒 Cart</a>
    </nav>

</header>



<!-- =========================================
     MAIN
========================================= -->

<div class="container">


    <!-- =====================================
         PAGE TITLE
    ====================================== -->

    <div class="page-title">

        <h1>
            My Orders
        </h1>


        <h1 class="welcome">

            Hello,

            <span>
                <?php
                echo htmlspecialchars($user_name);
                ?>
            </span>

            👋

        </h1>


        <p>
            Here you can see your order history.
        </p>

    </div>



<?php

/* =========================================
   CHECK ORDERS
========================================= */

if (mysqli_num_rows($result) > 0) {


    while ($row = mysqli_fetch_assoc($result)) {


        /* =====================================
           GET ORDER VALUES
        ===================================== */

        $food_name = $row['food_name'] ?? "Food Deleted";

        $quantity = $row['quantity'] ?? 0;

        $total_price = $row['total_price'] ?? 0;

        $status = $row['status'] ?? "Pending";

        $order_date = $row['order_date'] ?? "";

        $image = $row['image'] ?? "";


?>


<!-- =================================
     ORDER CARD
================================= -->

<div class="order-card">


    <!-- ORDER TOP -->

    <div class="order-top">


        <div class="order-id">

            Order #

            <?php

            /*
             * THIS IS THE USER-WISE ORDER NUMBER
             *
             * First order  = 1
             * Second order = 2
             * Third order  = 3
             * etc.
             */

            echo $order_number;

            ?>

        </div>


        <div class="order-date">

            <?php

            if (!empty($order_date)) {

                echo date(
                    "d M Y, h:i A",
                    strtotime($order_date)
                );

            } else {

                echo "Date not available";

            }

            ?>

        </div>


    </div>



    <!-- ORDER CONTENT -->

    <div class="order-content">


        <!-- FOOD IMAGE -->

        <?php

        if (!empty($image)) {

        ?>

            <img
                src="image/<?php echo htmlspecialchars($image); ?>"
                class="food-image"
                alt="Food"
                onerror="this.style.display='none';"
            >

        <?php

        } else {

        ?>

            <div class="no-image">
                No Image
            </div>

        <?php

        }

        ?>



        <!-- FOOD DETAILS -->

        <div class="food-details">


            <div class="food-name">

                <?php

                echo htmlspecialchars($food_name);

                ?>

            </div>


            <div class="quantity">

                Quantity:

                <?php

                echo htmlspecialchars($quantity);

                ?>

            </div>


        </div>



        <!-- PRICE -->

        <div class="price">

            ₹<?php

            echo number_format(
                (float)$total_price,
                2
            );

            ?>

        </div>



        <!-- STATUS -->

        <div>

            <?php

            $status_lower = strtolower(
                trim($status)
            );


            if ($status_lower == "completed") {

            ?>

                <span class="status completed">

                    Completed

                </span>

            <?php

            } elseif ($status_lower == "cancelled") {

            ?>

                <span class="status cancelled">

                    Cancelled

                </span>

            <?php

            } else {

            ?>

                <span class="status pending">

                    <?php

                    echo htmlspecialchars($status);

                    ?>

                </span>

            <?php

            }

            ?>

        </div>


    </div>


</div>


<?php


        /* =====================================
           INCREASE USER ORDER NUMBER
        ===================================== */

        $order_number++;


    }


} else {

?>


<!-- =================================
     NO ORDERS
================================= -->

<div class="no-orders">

    <h2>
        No Orders Yet
    </h2>


    <p>
        You have not placed any orders yet.
    </p>


    <a
        href="menu.php"
        class="menu-btn"
    >
        Order Food
    </a>

</div>


<?php

}


/* =========================================
   CLOSE ORDER STATEMENT
========================================= */

mysqli_stmt_close($order_stmt);

?>


</div>


</body>

</html>