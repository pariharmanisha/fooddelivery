<?php

session_start();

include "db.php";

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

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>QuickBite - Menu</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="menu.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#f8f8f8;
}


/* HEADER */

header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 60px;
    background:#ff5722;
}

.logo{
    display:flex;
    align-items:center;
    gap:10px;
}

.logo img{
width:60px;
height:60px;
border-radius:50%;
}

.logo h2{
    color:white;
}

nav a{
    text-decoration:none;
    color:white;
    margin-left:20px;
    font-size:18px;
    font-weight:bold;
}

nav a:hover,
nav .active{
    color:yellow;
}


/* MENU BANNER */

.menu-banner{
    text-align:center;
    padding:50px;
    background:white;
}

.menu-banner h1{
    font-size:40px;
    color:#ff5722;
    margin-bottom:10px;
}

.menu-banner p{
    font-size:20px;
    color:gray;
}


/* FOOD MENU */

.menu-container{
    width:90%;
    margin:40px auto;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:30px;
}

.food-card{
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,.2);
    transition:.3s;
    text-align:center;
    padding-bottom:20px;
}

.food-card:hover{
    transform:translateY(-8px);
}

.food-card img{
    width:100%;
    height:220px;
    object-fit:cover;
}

.food-card h3{
    margin-top:15px;
    font-size:24px;
}

.food-card p{
    padding:10px;
    color:gray;
}

.food-card h2{
    color:#ff5722;
    margin:10px;
}


/* ADD TO CART */

.food-card button{
    background:#ff5722;
    color:white;
    border:none;
    padding:12px 25px;
    border-radius:5px;
    cursor:pointer;
    font-size:16px;
}

.food-card button:hover{
    background:#e64a19;
}


/* NO FOOD */

.no-food{
    text-align:center;
    font-size:22px;
    color:gray;
    grid-column:1/-1;
    padding:40px;
}

</style>

</head>

<body>


<!-- HEADER -->

<header>

<div class="logo">

    <img src="image/logo11.jpg" alt="Logo">

    <h2>QuickBite</h2>

</div>


<nav>

    <a href="input.php">Home</a>

    <a href="menu.php" class="active">Menu</a>
<a href="myorder.php">Order</a>

    <a href="contact.php">Contact</a>

    <a href="about.php">About us</a>
<a href="profile.php">Profile</a>
    <a href="cart.php">🛒 Cart</a>
    


</nav>

</header>



<!-- BANNER -->

<section class="menu-banner">

    <h1>Our Delicious Menu</h1>

    <p>
        Choose your favourite food and order instantly.
    </p>

</section>



<!-- FOOD MENU -->

<section class="menu-container">

<?php

if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

?>

    <div class="food-card">

        <img
            src="image/<?php echo htmlspecialchars($row['image']); ?>"
            alt="<?php echo htmlspecialchars($row['food_name']); ?>"
        >


        <h3>

            <?php
            echo htmlspecialchars($row['food_name']);
            ?>

        </h3>


        <p>

            <?php
            echo htmlspecialchars($row['description']);
            ?>

        </p>


        <h2>

            ₹<?php
            echo htmlspecialchars($row['price']);
            ?>

        </h2>


        <!-- ADD TO CART -->

        <form action="addtocart.php" method="POST">

            <input
                type="hidden"
                name="id"
                value="<?php echo $row['id']; ?>"
            >

            <button type="submit">
                Add to Cart
            </button>

        </form>

    </div>


<?php

    }

} else {

?>

    <div class="no-food">
        No food available.
    </div>

<?php

}

?>

</section>


<script src="app.js"></script>

</body>

</html>