<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>About Us | QuickBite</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="about.css">
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
header {
    height: 90px;
    background: #ff512b;
    display: flex;
    align-items: center;
    padding: 0 48px;
    box-sizing: border-box;
}

.navbar {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.logo {
    display: flex;
    align-items: center;
    gap: 10px;
}

.logo img {
    width: 55px;
    height: 55px;
    border-radius: 50%;
}

.logo span {
    color: #fff;
    font-size: 24px;
    font-weight: bold;
}

/* NAVIGATION RIGHT */
.nav-links {
    display: flex;
    align-items: center;
    gap: 28px;
}

.nav-links a {
    color: white;
    text-decoration: none;
    font-size: 17px;
    font-weight: bold;
}

.nav-links a:hover {
    color: yellow;
}

/* ABOUT SECTION */

.about{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:70px 80px;
    gap:50px;
}

.about-image{
    width:45%;
}

.about-image img{
    width:100%;
    border-radius:20px;
}

.about-content{
    width:50%;
}

.about-content h1{
    font-size:50px;
    margin-bottom:20px;
}

.about-content span{
    color:#ff5722;
}

.about-content p{
    font-size:18px;
    line-height:1.8;
    color:#555;
    margin-bottom:20px;
}

.features{
    display:flex;
    gap:20px;
    margin:30px 0;
}

.feature-box{
    background:white;
    width:160px;
    padding:20px;
    text-align:center;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}

.feature-box h2{
    color:#ff5722;
    margin-bottom:10px;
}

.btn{
    display:inline-block;
    background:#ff5722;
    color:white;
    padding:14px 30px;
    text-decoration:none;
    border-radius:8px;
    transition:.3s;
}

.btn:hover{
    background:#e64a19;
}

/* Responsive */

@media(max-width:768px){

.about{
    flex-direction:column;
    padding:40px 20px;
}

.about-image,
.about-content{
    width:100%;
}

.features{
    flex-wrap:wrap;
}

}
</style>
</head>
<body>

<header>
    <nav class="navbar">

        <div class="logo">
                <img src="image/logo11.jpg" alt="Logo">
        
            <span><h2>QuickBite</h2></span>
        </div>

                <div class="nav-links">       
            <a href="input.php">Home</a>
           <a href="menu.php">Menu</a>
           <a href="myorder.php">Order</a>
            <a href="about.php" class="active">About us</a>
            <a href="contact.php">Contact us</a>
            <a href="profile.php">Profile</a>
        <a href="cart.php">🛒 Cart</a>
</div>   

    
</header>

<section class="about">

    <div class="about-image">
        <img src="image/about.png" alt="About">
    </div>

    <div class="about-content">

        <h1>About <span>QuickBite</span></h1>

        <p>
            Welcome to <b>QuickBite</b>, your trusted online food delivery
            platform. We connect customers with the best restaurants and
            deliver fresh, delicious meals right to your doorstep.
        </p>

        <p>
            Whether you're craving pizza, burgers, biryani, Chinese,
            desserts, or healthy meals, QuickBite offers a wide variety
            of food choices with fast delivery and secure online ordering.
        </p>

       
        <a href="menu.php" class="btn">Explore Menu</a>

    </div>

</section>

</body>
</html>