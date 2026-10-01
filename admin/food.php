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

    padding: 20px;

    border-radius: 12px;

    box-shadow:
        0 5px 15px rgba(0,0,0,0.08);

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

</style>

</head>


<body>


<!-- HEADER -->

<div class="header">

    <h1>
        QuickBite Admin
    </h1>

    <a href="dashboard.php">
        Dashboard
    </a>

</div>


<!-- CONTENT -->

<div class="container">


    <div class="top">

        <h2>
            Manage Foods
        </h2>

        <a href="add_food.php" class="add-btn">
            + Add Food
        </a>

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
                        echo htmlspecialchars($food['name']);
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
                            href="delete_food.php?id=<?php echo $food['id']; ?>"
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