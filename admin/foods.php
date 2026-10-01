<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include "../db.php";

$query = "SELECT * FROM foods ORDER BY id DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Manage Foods - QuickBite</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
}


/* HEADER */

.header {
    height: 70px;

    background: #ff5722;

    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 0 30px;

    color: white;
}

.header h1 {
    font-size: 25px;
}

.header a {
    color: white;
    text-decoration: none;

    background: #e64a19;

    padding: 10px 18px;

    border-radius: 6px;
}


/* CONTENT */

.container {
    padding: 30px;
}


/* TOP */

.top {
    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 25px;
}

.top h2 {
    color: #333;
}

.add-btn {
    background: #ff5722;

    color: white;

    text-decoration: none;

    padding: 12px 20px;

    border-radius: 7px;

    font-weight: bold;
}

.add-btn:hover {
    background: #e64a19;
}


/* TABLE */

.table-box {
    background: white;

    padding: 10px;

    border-radius: 8px;

    box-shadow:
        0 2px 150px rgba(0,0,0,0.08);

    overflow-x: auto;
}

table {
    width: 100%;

    border-collapse: collapse;
}

th {
    background: #ff5722;

    color: white;

    padding: 14px;

    text-align: left;
}

td {
    padding: 12px;

    border-bottom: 1px solid #eee;
}

tr:hover {
    background: #fafafa;
}


/* FOOD IMAGE */

.food-img {
    width: 70px;
    height: 60px;

    object-fit: cover;

    border-radius: 8px;
}


/* BUTTONS */

.edit-btn,
.delete-btn {
    text-decoration: none;

    padding: 8px 12px;

    border-radius: 5px;

    color: white;

    font-size: 14px;
}

.edit-btn {
    background: #2196f3;
}

.delete-btn {
    background: #f44336;
}

.edit-btn:hover {
    background: #1976d2;
}

.delete-btn:hover {
    background: #d32f2f;
}


/* MOBILE */

@media(max-width: 700px) {

    .container {
        padding: 15px;
    }

    .top {
        flex-direction: column;

        align-items: flex-start;

        gap: 15px;
    }

}
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



</style>

</head>


<body>

<div class="sidebar">

    <div class="logo">

        <img
            src="../image/logo11.jpg"
            alt="QuickBite Logo"
        >

        <h2>QuickBite</h2>

    </div>


    <ul>

        <li>
            <a href="dashboard.php">
                🏠 Dashboard
            </a>
        </li>

        <li>
            <a href="foods.php" class="active">
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

<div class="main">


    <!-- HEADER -->

    <div class="header">

        <h1>
            Admin Dashboard
        </h1>

        <div class="admin-name">

            Welcome,
            <?php echo htmlspecialchars($_SESSION['admin']); ?>

        </div>

    </div>


<!-- HEADER -->

<div class="header">

    <h1>
        QuickBite Admin
    </h1>

   
</div>


<!-- CONTENT -->

<div class="container">


    <div class="top">

        <h2>
            Manage Foods
        </h2>

      

    </div>


    <div class="table-box">

        <table>

            <thead>

                <tr>

                    <th>
                        ID
                    </th>

                    <th>
                        Image
                    </th>

                    <th>
                        Food Name
                    </th>

                    <th>
                        Description
                    </th>

                    <th>
                        Price
                    </th>

                    <th>
                        Category
                    </th>

                    <th>
                        Action
                    </th>

                </tr>

            </thead>


            <tbody>

            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($food = mysqli_fetch_assoc($result)) {

            ?>

                <tr>

                    <td>
                        <?php
                        echo $food['id'];
                        ?>
                    </td>


                    <td>

                        <img
                            src="../image/<?php
                            echo htmlspecialchars($food['image']);
                            ?>"
                            class="food-img"
                            alt="Food"
                        >

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars($food['food_name']);
                        ?>

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars(
                            $food['description']
                        );
                        ?>

                    </td>


                    <td>

                        ₹<?php
                        echo htmlspecialchars($food['price']);
                        ?>

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars(
                            $food['category']
                        );
                        ?>

                    </td>


                    <td>

                        <a
                            href="edit_food.php?id=<?php echo $food['id']; ?>"
                            class="edit-btn"
                        >
                            Edit
                        </a>


                        <a
                            href="deletefood.php?id=<?php echo $food['id']; ?>"
                            class="delete-btn"
                            onclick="return confirm('Are you sure you want to delete this food?');"
                        >
                            Delete
                        </a>

                    </td>

                </tr>

            <?php

                }

            } else {

            ?>

                <tr>

                    <td colspan="7"
                        style="text-align:center;">

                        No food found.

                    </td>

                </tr>

            <?php

            }

            ?>

            </tbody>

        </table>

    </div>

</div>


</body>

</html>