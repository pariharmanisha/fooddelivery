<?php

session_start();

/* =========================
   ADMIN LOGIN CHECK
========================= */

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}


/* =========================
   DATABASE CONNECTION
========================= */

include "../db.php";


/* =========================
   TOTAL FOODS
========================= */

$food_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM foods"
);

$food_data = mysqli_fetch_assoc($food_query);

$total_foods = $food_data['total'];


/* =========================
   TOTAL ORDERS
========================= */

$order_count_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM orders"
);

$order_count_data = mysqli_fetch_assoc($order_count_query);

$total_orders = $order_count_data['total'];


/* =========================
   TOTAL CUSTOMERS
========================= */

$customer_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM users"
);

$customer_data = mysqli_fetch_assoc($customer_query);

$total_customers = $customer_data['total'];


/* =========================
   RECENT ORDERS
========================= */

$recent_orders_query = mysqli_query(
    $conn,

    "SELECT
        o.id,
        o.user_id,
        o.food_id,
        o.quantity,
        o.total_price,
        o.status,
        o.order_date,

        u.name AS customer_name,

        f.food_name AS food_name

     FROM orders o

     LEFT JOIN users u
        ON o.user_id = u.id

     LEFT JOIN foods f
        ON o.food_id = f.id

     ORDER BY o.id DESC

     LIMIT 10"
);


/* =========================
   CHECK QUERY ERROR
========================= */

if (!$recent_orders_query) {

    die(
        "Recent Orders Query Failed: "
        . mysqli_error($conn)
    );

}

?>


<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>QuickBite Admin Dashboard</title>


<style>

/* =========================
   RESET
========================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
}


/* =========================
   SIDEBAR
========================= */

.sidebar {

    width: 240px;

    height: 100vh;

    background: #ff5722;

    position: fixed;

    left: 0;

    top: 0;

    padding: 25px 15px;
}


/* LOGO */

.logo {

    text-align: center;

    margin-bottom: 35px;
}

.logo img {

    width: 70px;

    height: 70px;

    border-radius: 50%;

    object-fit: cover;
}

.logo h2 {

    color: white;

    margin-top: 10px;
}


/* MENU */

.sidebar ul {

    list-style: none;
}

.sidebar ul li {

    margin: 10px 0;
}

.sidebar ul li a {

    display: block;

    text-decoration: none;

    color: white;

    padding: 14px 15px;

    border-radius: 8px;

    font-size: 16px;
}

.sidebar ul li a:hover,
.sidebar ul li a.active {

    background: white;

    color: #ff5722;
}


/* LOGOUT */

.logout {

    margin-top: 30px;
}

.logout a {

    background: #d84315 !important;
}


/* =========================
   MAIN
========================= */

.main {

    margin-left: 240px;

    min-height: 100vh;
}


/* =========================
   HEADER
========================= */

.header {

    height: 75px;

    background: white;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 30px;

    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.header h1 {

    color: #333;

    font-size: 25px;
}

.admin-name {

    color: #ff5722;

    font-weight: bold;
}


/* =========================
   CONTENT
========================= */

.content {

    padding: 30px;
}

.content h2 {

    color: #333;

    margin-bottom: 25px;
}


/* =========================
   CARDS
========================= */

.cards {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 20px;

    margin-bottom: 30px;
}

.card {

    background: white;

    padding: 25px;

    border-radius: 12px;

    box-shadow:
        0 5px 15px rgba(0,0,0,0.08);

    transition: 0.3s;
}

.card:hover {

    transform: translateY(-5px);
}

.card h3 {

    color: #777;

    font-size: 16px;

    margin-bottom: 12px;
}

.card .number {

    color: #ff5722;

    font-size: 30px;

    font-weight: bold;
}


/* =========================
   RECENT ORDERS
========================= */

.orders {

    background: white;

    padding: 25px;

    border-radius: 12px;

    box-shadow:
        0 5px 15px rgba(0,0,0,0.08);

    overflow-x: auto;
}

.orders h2 {

    margin-bottom: 20px;
}


/* =========================
   TABLE
========================= */

table {

    width: 100%;

    border-collapse: collapse;

    min-width: 700px;
}

table th {

    background: #ff5722;

    color: white;

    padding: 14px;

    text-align: left;
}

table td {

    padding: 14px;

    border-bottom: 1px solid #eee;
}

table tr:hover {

    background: #fafafa;
}


/* =========================
   STATUS
========================= */

.status {

    padding: 6px 12px;

    border-radius: 20px;

    font-size: 13px;

    font-weight: bold;

    display: inline-block;
}

.completed {

    background: #d4edda;

    color: #155724;
}

.pending {

    background: #fff3cd;

    color: #856404;
}

.cancelled {

    background: #f8d7da;

    color: #721c24;
}


/* =========================
   NO ORDERS
========================= */

.no-orders {

    text-align: center;

    padding: 30px;

    color: #777;

    font-size: 16px;
}


/* =========================
   RESPONSIVE
========================= */

@media(max-width: 1000px) {

    .cards {

        grid-template-columns:
            repeat(2, 1fr);
    }

}


@media(max-width: 700px) {

    .sidebar {

        width: 200px;
    }

    .main {

        margin-left: 200px;
    }

    .cards {

        grid-template-columns: 1fr;
    }

    .header h1 {

        font-size: 20px;
    }

    .content {

        padding: 20px;
    }

}


@media(max-width: 550px) {

    .sidebar {

        width: 70px;

        padding: 15px 8px;
    }

    .logo h2 {

        display: none;
    }

    .logo img {

        width: 50px;

        height: 50px;
    }

    .sidebar ul li a {

        font-size: 0;

        text-align: center;
    }

    .sidebar ul li a::first-letter {

        font-size: 20px;
    }

    .main {

        margin-left: 70px;
    }

}

</style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">


    <div class="logo">

        <img
            src="../image/logo11.jpg"
            alt="QuickBite Logo"
        >

        <h2>
            QuickBite
        </h2>

    </div>


    <ul>

        <li>

            <a
                href="dashboard.php"
                class="active"
            >
                🏠 Dashboard
            </a>

        </li>


        <li>

            <a href="foods.php">
                🍔 Foods
            </a>

        </li>


        <li>

            <a href="add_food.php">
                ➕ Add Food
            </a>

        </li>


        <li>

            <a href="../input.php">
                🌐 View Website
            </a>

        </li>


        <li class="logout">

            <a href="logout.php">
                🚪 Logout
            </a>

        </li>

    </ul>


</div>



<!-- =========================
     MAIN
========================= -->

<div class="main">


    <!-- HEADER -->

    <div class="header">

        <h1>
            Admin Dashboard
        </h1>


        <div class="admin-name">

            Welcome,
            <?php
            echo htmlspecialchars($_SESSION['admin']);
            ?>

        </div>

    </div>



    <!-- CONTENT -->

    <div class="content">


        <h2>
            Dashboard Overview
        </h2>



        <!-- =========================
             CARDS
        ========================= -->

        <div class="cards">


            <!-- TOTAL FOODS -->

            <div class="card">

                <h3>
                    Total Foods
                </h3>

                <div class="number">

                    <?php
                    echo $total_foods;
                    ?>

                </div>

            </div>



            <!-- TOTAL ORDERS -->

            <div class="card">

                <h3>
                    Total Orders
                </h3>

                <div class="number">

                    <?php
                    echo $total_orders;
                    ?>

                </div>

            </div>



            <!-- CUSTOMERS -->

            <div class="card">

                <h3>
                    Customers
                </h3>

                <div class="number">

                    <?php
                    echo $total_customers;
                    ?>

                </div>

            </div>



            <!-- RESTAURANTS -->

            <div class="card">

                <h3>
                    Restaurants
                </h3>

                <div class="number">
                    50+
                </div>

            </div>


        </div>



        <!-- =========================
             RECENT ORDERS
        ========================= -->

        <div class="orders">


            <h2>
                Recent Orders
            </h2>



            <table>


                <thead>

                    <tr>

                        <th>
                            Order ID
                        </th>

                        <th>
                            Customer
                        </th>

                        <th>
                            Food
                        </th>

                        <th>
                            Amount
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>



                <tbody>


                <?php

                if (
                    mysqli_num_rows($recent_orders_query)
                    > 0
                ) {

                    while (
                        $order =
                        mysqli_fetch_assoc(
                            $recent_orders_query
                        )
                    ) {

                ?>


                    <tr>


                        <!-- ORDER ID -->

                        <td>

                            #<?php
                            echo $order['id'];
                            ?>

                        </td>



                        <!-- CUSTOMER -->

                        <td>

                            <?php

                            if (
                                !empty(
                                    $order['customer_name']
                                )
                            ) {

                                echo htmlspecialchars(
                                    $order['customer_name']
                                );

                            } else {

                                echo "User #"
                                    . $order['user_id'];

                            }

                            ?>

                        </td>



                        <!-- FOOD -->

                        <td>

                            <?php

                            if (
                                !empty(
                                    $order['food_name']
                                )
                            ) {

                                echo htmlspecialchars(
                                    $order['food_name']
                                );

                            } else {

                                echo "Food #"
                                    . $order['food_id'];

                            }

                            ?>

                            ×

                            <?php
                            echo $order['quantity'];
                            ?>

                        </td>



                        <!-- AMOUNT -->

                        <td>

                            ₹<?php

                            echo number_format(
                                $order['total_price'],
                                2
                            );

                            ?>

                        </td>



                        <!-- STATUS -->

                        <td>


                            <?php

                            $status =
                                strtolower(
                                    trim(
                                        $order['status']
                                    )
                                );


                            if (
                                $status == "completed"
                            ) {

                                $status_class =
                                    "completed";

                            }

                            elseif (
                                $status == "cancelled"
                            ) {

                                $status_class =
                                    "cancelled";

                            }

                            else {

                                $status_class =
                                    "pending";

                            }

                            ?>


                            <span
                                class="status
                                <?php
                                echo $status_class;
                                ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $order['status']
                                );

                                ?>

                            </span>


                        </td>


                    </tr>


                <?php

                    }

                } else {

                ?>


                    <tr>

                        <td
                            colspan="5"
                            class="no-orders"
                        >

                            No orders found

                        </td>

                    </tr>


                <?php

                }

                ?>


                </tbody>


            </table>


        </div>


    </div>


</div>


</body>

</html>