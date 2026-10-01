<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QuickBite - Menu</title>
<link rel="stylesheet" href="contact.css">
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
.navbar {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-left: auto;
}
nav a:hover,
nav .active{
color:yellow;
}

/* Contact Section */

.contact{
    width:90%;
    max-width:1200px;
    margin:120px auto 50px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:60px;
}

.contact-left{
    width:45%;
}

.contact-left h1{
    font-size:50px;
    margin-bottom:20px;
}

.contact-left span{
    color:#ff5722;
}

.contact-left p{
    font-size:20px;
    line-height:1.8;
    color:#555;
}

.info{
    margin-top:30px;
}

.info p{
    margin:15px 0;
    font-size:18px;
}

.contact-right{
    width:50%;
    background:#fff;
    padding:35px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,.15);
}

.contact-right form{
    display:flex;
    flex-direction:column;
}

.contact-right input,
.contact-right textarea{
    padding:15px;
    margin-bottom:18px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:17px;
    outline:none;
}

.contact-right input:focus,
.contact-right textarea:focus{
    border:2px solid #ff5722;
}

.contact-right button{
    background:#ff5722;
    color:white;
    padding:15px;
    border:none;
    border-radius:8px;
    font-size:18px;
    cursor:pointer;
    transition:.3s;
}

.contact-right button:hover{
    background:#e64a19;
}

@media(max-width:768px){

.contact{
    flex-direction:column;
}

.contact-left,
.contact-right{
    width:100%;
}

.contact-left{
    text-align:center;
}

}

</style>
</head>
<body>

<header>
    <nav class="navbar">

        <div class="logo">
                <img src="image/logo11.jpg" alt="Logo">
        
           <h2><span>QuickBite<span></h2>
        </div>

        <div class="nav-links">
            <a href="input.php">Home</a>
           <a href="menu.php">Menu</a>
            <a href="myorder.php">Order</a>
            <a href="about.php">About us</a>
            <a href="contact.php"class="active">Contact us</a>
            <a href="profile.php">Profile</a>
            <a href="cart.php">🛒 Cart</a>
</div>

   
</header>

<section class="contact">

    <div class="contact-left">

        <h1>Contact <span>Us</span></h1>

        <p>Have questions or need help with your food order? We'd love to hear from you.</p>

        <div class="info">
            <p><strong>📍 Address:</strong> Ahmedabad, Gujarat</p>
            <p><strong>📞 Phone:</strong> +91 9876543210</p>
            <p><strong>✉ Email:</strong> quickbite@gmail.com</p>
            <p><strong>🕒 Working Hours:</strong> 9:00 AM - 11:00 PM</p>
        </div>

    </div>

    <div class="contact-right">

        <form action="#" method="POST">

            <input type="text" name="name" placeholder="Your Name" required>

            <input type="email" name="email" placeholder="Your Email" required>

            <input type="text" name="subject" placeholder="Subject" required>

            <textarea name="message" rows="6" placeholder="Write Your Message" required></textarea>

            <button type="submit">Send Message</button>

        </form>

    </div>

</section>

</body>
</html>
