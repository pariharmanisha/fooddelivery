
<html lang="en">
<head>
<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Food Delivery</title>

<link rel="stylesheet" href="style.css">
<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}


body{
    margin:0;
    background:#f8f8f8;
}
 


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
</style>
</head>

<header>

    <div class="logo">
        <img src="image/logo11.jpg" alt="Logo">
        <h2>QuickBite</h2>
    </div>

    <nav>
        <a href="input.php" class="active">Home</a>
        <a href="menu.php">Menu</a>
        <a href="restaurant.php">Restaurants</a>
        <a href="contact.php">Contact us</a>
         <a href="about.php">About us</a>
        <a href="cart.php">Cart</a>
    </nav>

</header>
