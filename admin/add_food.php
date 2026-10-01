<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include "../db.php";

$message = "";


/* =========================
   ADD FOOD
========================= */

if (isset($_POST['add_food'])) {

    $name = trim($_POST['food_name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);


    /* =========================
       IMAGE
    ========================= */

    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];

    $upload_folder = "../image/";


    if (!empty($image)) {

        $image_name = basename($image);

        if (!move_uploaded_file(
            $tmp_name,
            $upload_folder . $image_name
        )) {

            $message = "Image upload failed.";

        } else {

            $image = $image_name;
        }

    }


    /* =========================
       INSERT DATA
    ========================= */

    $stmt = mysqli_prepare(
    $conn,
    "INSERT INTO foods
    (food_name, description, price, category, image)
    VALUES (?, ?, ?, ?, ?)"
);

if (!$stmt) {
    $message = "SQL Prepare Error: " . mysqli_error($conn);
} else {

    mysqli_stmt_bind_param(
        $stmt,
        "ssdss",
        $name,
        $description,
        $price,
        $category,
        $image
    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: foods.php");
        exit();

    } else {

        $message = "Food could not be added: "
                 . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}       }
   

?>


<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Add Food - QuickBite Admin</title>


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
    background: #f5f5f5;
}


/* =========================
   SIDEBAR
========================= */

.sidebar {

    width: 240px;

    height: 100vh;

    position: fixed;

    left: 0;

    top: 0;

    background: #ff5722;

    padding: 25px 15px;
}


/* =========================
   LOGO
========================= */

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


/* =========================
   SIDEBAR MENU
========================= */

.sidebar ul {

    list-style: none;
}


.sidebar ul li {

    margin: 10px 0;
}


.sidebar ul li a {

    display: block;

    color: white;

    text-decoration: none;

    padding: 14px 15px;

    border-radius: 8px;

    font-size: 16px;
}


.sidebar ul li a:hover,
.sidebar ul li a.active {

    background: white;

    color: #ff5722;
}


/* =========================
   LOGOUT
========================= */

.logout {

    margin-top: 30px;
}


.logout a {

    background: #d84315;
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

    box-shadow:
        0 2px 10px rgba(0,0,0,0.08);
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

    display: flex;

    justify-content: center;

    align-items: flex-start;
}


/* =========================
   FORM BOX
========================= */

.form-box {

    width: 600px;

    max-width: 100%;

    background: white;

    padding: 30px;

    border-radius: 12px;

    box-shadow:
        0 5px 20px rgba(0,0,0,0.08);
}


.form-box h2 {

    color: #ff5722;

    margin-bottom: 25px;
}


/* =========================
   FORM GROUP
========================= */

.form-group {

    margin-bottom: 18px;
}


.form-group label {

    display: block;

    margin-bottom: 7px;

    font-weight: bold;

    color: #333;
}


/* =========================
   INPUT
========================= */

.form-group input,
.form-group textarea,
.form-group select {

    width: 100%;

    padding: 12px;

    border: 1px solid #ddd;

    border-radius: 7px;

    font-size: 15px;

    outline: none;
}


.form-group textarea {

    height: 100px;

    resize: none;
}


/* =========================
   FOCUS
========================= */

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {

    border-color: #ff5722;

    box-shadow:
        0 0 5px rgba(255,87,34,0.2);
}


/* =========================
   BUTTON
========================= */

.submit-btn {

    width: 100%;

    padding: 13px;

    border: none;

    border-radius: 7px;

    background: #ff5722;

    color: white;

    font-size: 16px;

    font-weight: bold;

    cursor: pointer;
}


.submit-btn:hover {

    background: #e64a19;
}


/* =========================
   ERROR
========================= */

.error {

    background: #ffe5e5;

    color: #d00000;

    padding: 10px;

    border-radius: 6px;

    margin-bottom: 18px;

    text-align: center;
}


/* =========================
   MOBILE
========================= */

@media(max-width: 700px) {

    .sidebar {

        width: 200px;
    }


    .main {

        margin-left: 200px;
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


    .main {

        margin-left: 70px;
    }


    .header h1 {

        font-size: 18px;
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

            <a href="add_food.php"
               class="active">

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

            Add Food

        </h1>


        <div class="admin-name">

            Welcome,

            <?php

            echo htmlspecialchars(
                $_SESSION['admin']
            );

            ?>

        </div>


    </div>



    <!-- CONTENT -->

    <div class="content">


        <div class="form-box">


            <h2>

                Add New Food

            </h2>



            <?php if ($message != "") { ?>

                <div class="error">

                    <?php

                    echo htmlspecialchars($message);

                    ?>

                </div>

            <?php } ?>



            <form
                method="POST"
                enctype="multipart/form-data"
            >


                <!-- FOOD NAME -->

                <div class="form-group">

                    <label>

                        Food Name

                    </label>


                    <input
                        type="text"
                        name="food_name"
                        placeholder="Chicken Biryani"
                        required
                    >

                </div>



                <!-- DESCRIPTION -->

                <div class="form-group">

                    <label>

                        Description

                    </label>


                    <textarea
                        name="description"
                        placeholder="Delicious Hyderabadi Chicken Biryani"
                        required
                    ></textarea>

                </div>



                <!-- PRICE -->

                <div class="form-group">

                    <label>

                        Price

                    </label>


                    <input
                        type="number"
                        name="price"
                        placeholder="299"
                        min="1"
                        required
                    >

                </div>



                <!-- CATEGORY -->

                <div class="form-group">

                    <label>

                        Category

                    </label>


                    <select
                        name="category"
                        required
                    >

                        <option value="">

                            Select Category

                        </option>

                        <option value="Biryani">

                            Biryani

                        </option>

                        <option value="Pizza">

                            Pizza

                        </option>

                        <option value="Burger">

                            Burger

                        </option>

                        <option value="Chinese">

                            Chinese

                        </option>

                        <option value="Fast Food">

                            Fast Food

                        </option>

                        <option value="Dessert">

                            Dessert

                        </option>

                        <option value="Drinks">

                            Drinks

                        </option>

                    </select>

                </div>



                <!-- IMAGE -->

                <div class="form-group">

                    <label>

                        Food Image

                    </label>


                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        required
                    >

                </div>



                <!-- SUBMIT -->

                <button
                    type="submit"
                    name="add_food"
                    class="submit-btn"
                >

                    Add Food

                </button>


            </form>


        </div>

    </div>


</div>


</body>

</html>