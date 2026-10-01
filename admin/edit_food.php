<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include "../db.php";


// =========================
// CHECK ID
// =========================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: foods.php");
    exit();
}

$id = intval($_GET['id']);


// =========================
// GET FOOD
// =========================

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, food_name, description, price, category, image
     FROM foods
     WHERE id = ?"
);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Food not found.");
}

$food = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Food - QuickBite</title>

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


/* =========================
   SIDEBAR
========================= */

.sidebar {
    position: fixed;
    left: 0;
    top: 0;

    width: 240px;
    height: 100vh;

    background: #ff5722;

    padding: 25px 15px;
}

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

.sidebar ul {
    list-style: none;
}

.sidebar li {
    margin-bottom: 8px;
}

.sidebar a {
    display: block;

    color: white;

    text-decoration: none;

    padding: 14px 18px;

    border-radius: 8px;

    font-size: 16px;
}

.sidebar a:hover {
    background: white;
    color: #ff5722;
}


/* =========================
   MAIN
========================= */

.main {
    margin-left: 240px;

    min-height: 100vh;

    padding: 35px;
}


/* =========================
   HEADER
========================= */

.header {
    background: white;

    padding: 20px 25px;

    border-radius: 10px;

    margin-bottom: 25px;

    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

.header h1 {
    color: #333;
}


/* =========================
   FORM
========================= */

.form-container {
    width: 650px;

    max-width: 100%;

    margin: auto;

    background: white;

    padding: 30px;

    border-radius: 12px;

    box-shadow: 0 5px 20px rgba(0,0,0,0.10);
}

.form-container h2 {
    text-align: center;

    color: #ff5722;

    margin-bottom: 25px;
}


/* =========================
   LABEL
========================= */

label {
    display: block;

    font-weight: bold;

    margin-bottom: 7px;

    color: #333;
}


/* =========================
   INPUT
========================= */

input,
textarea,
select {

    width: 100%;

    padding: 12px;

    margin-bottom: 18px;

    border: 1px solid #ddd;

    border-radius: 7px;

    font-size: 15px;

    outline: none;
}

input:focus,
textarea:focus,
select:focus {
    border-color: #ff5722;
}

textarea {
    height: 120px;

    resize: vertical;
}


/* =========================
   CURRENT IMAGE
========================= */

.current-image {
    text-align: center;

    margin: 10px 0 20px;
}

.current-image img {

    width: 180px;

    height: 140px;

    object-fit: cover;

    border-radius: 10px;

    border: 3px solid #ff5722;
}

.current-image p {

    margin-top: 8px;

    color: #777;
}


/* =========================
   BUTTON
========================= */

.update-btn {

    width: 100%;

    padding: 14px;

    border: none;

    border-radius: 7px;

    background: #ff5722;

    color: white;

    font-size: 17px;

    font-weight: bold;

    cursor: pointer;
}

.update-btn:hover {
    background: #e64a19;
}


/* =========================
   BACK
========================= */

.back-btn {

    display: block;

    text-align: center;

    margin-top: 15px;

    padding: 12px;

    background: #333;

    color: white;

    text-decoration: none;

    border-radius: 7px;
}

.back-btn:hover {
    background: #222;
}


/* =========================
   MOBILE
========================= */

@media(max-width: 768px) {

    .sidebar {

        position: relative;

        width: 100%;

        height: auto;
    }

    .main {

        margin-left: 0;

        padding: 20px;
    }

    .form-container {

        width: 100%;
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
            alt="QuickBite"
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

        <li>
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


    <div class="header">

        <h1>Edit Food</h1>

    </div>


    <div class="form-container">

        <h2>Edit Food Details</h2>


        <form
            action="update.php"
            method="POST"
            enctype="multipart/form-data"
        >


            <!-- FOOD ID -->

            <input
                type="hidden"
                name="id"
                value="<?php echo $food['id']; ?>"
            >


            <!-- FOOD NAME -->

            <label>Food Name</label>

            <input
                type="text"
                name="food_name"
                value="<?php echo htmlspecialchars($food['food_name']); ?>"
                required
            >


            <!-- DESCRIPTION -->

            <label>Description</label>

            <textarea
                name="description"
                required
            ><?php echo htmlspecialchars($food['description']); ?></textarea>


            <!-- PRICE -->

            <label>Price</label>

            <input
                type="number"
                name="price"
                step="0.01"
                value="<?php echo htmlspecialchars($food['price']); ?>"
                required
            >


            <!-- CATEGORY -->

            <label>Category</label>

            <select
                name="category"
                required
            >

                <option value="">
                    Select Category
                </option>

                <option value="Pizza"
                    <?php
                    if ($food['category'] == "Pizza") {
                        echo "selected";
                    }
                    ?>
                >
                    Pizza
                </option>

                <option value="Burger"
                    <?php
                    if ($food['category'] == "Burger") {
                        echo "selected";
                    }
                    ?>
                >
                    Burger
                </option>

                <option value="Biryani"
                    <?php
                    if ($food['category'] == "Biryani") {
                        echo "selected";
                    }
                    ?>
                >
                    Biryani
                </option>

                <option value="Chinese"
                    <?php
                    if ($food['category'] == "Chinese") {
                        echo "selected";
                    }
                    ?>
                >
                    Chinese
                </option>

                <option value="South Indian"
                    <?php
                    if ($food['category'] == "South Indian") {
                        echo "selected";
                    }
                    ?>
                >
                    South Indian
                </option>

                <option value="Fast Food"
                    <?php
                    if ($food['category'] == "Fast Food") {
                        echo "selected";
                    }
                    ?>
                >
                    Fast Food
                </option>

                <option value="Dessert"
                    <?php
                    if ($food['category'] == "Dessert") {
                        echo "selected";
                    }
                    ?>
                >
                    Dessert
                </option>

                <option value="Drinks"
                    <?php
                    if ($food['category'] == "Drinks") {
                        echo "selected";
                    }
                    ?>
                >
                    Drinks
                </option>

            </select>


            <!-- =========================
                 CURRENT IMAGE
            ========================= -->

            <?php if (!empty($food['image'])): ?>

                <div class="current-image">

                    <img
                        src="../image/<?php echo htmlspecialchars($food['image']); ?>"
                        alt="Food Image"
                    >

                    <p>Current Image</p>

                </div>

            <?php endif; ?>


            <!-- =========================
                 CHANGE IMAGE
            ========================= -->

            <label>
                Change Image
            </label>

            <input
                type="file"
                name="image"
                accept="image/*"
            >


            <!-- =========================
                 UPDATE
            ========================= -->

            <button
                type="submit"
                name="update_food"
                class="update-btn"
            >
                Update Food
            </button>


            <a
                href="foods.php"
                class="back-btn"
            >
                ← Back to Foods
            </a>

        </form>

    </div>

</div>

</body>

</html>