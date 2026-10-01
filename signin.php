<?php

session_start();

include "db.php";

$message = "";


/* =====================================
   ALREADY LOGIN CHECK
===================================== */

if (isset($_SESSION['user_id'])) {

    header("Location: input.php");
    exit();

}


/* =====================================
   LOGIN
===================================== */

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);


    /* CHECK EMPTY */

    if (empty($email) || empty($password)) {

        $message = "Please enter email and password.";

    } else {


        /* FIND USER */

        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, name, email, password
             FROM users
             WHERE email = ?"
        );


        if (!$stmt) {

            die("SQL Error: " . mysqli_error($conn));

        }


        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $email
        );


        mysqli_stmt_execute($stmt);


        $result = mysqli_stmt_get_result($stmt);


        /* CHECK USER */

        if (mysqli_num_rows($result) == 1) {

            $user = mysqli_fetch_assoc($result);


            /* SIMPLE PASSWORD CHECK */

            if ($password === $user['password']) {


                /* CREATE SESSION */

                session_regenerate_id(true);

                $_SESSION['user_id'] =
                    $user['id'];

                $_SESSION['user_name'] =
                    $user['name'];

                $_SESSION['user_email'] =
                    $user['email'];


                /* =========================
                   CHECKOUT REDIRECT
                ========================= */

                if (
                    isset($_SESSION['checkout_redirect']) &&
                    $_SESSION['checkout_redirect'] == true
                ) {

                    unset(
                        $_SESSION['checkout_redirect']
                    );


                    header(
                        "Location: checkout.php"
                    );

                    exit();

                }


                /* =========================
                   NORMAL LOGIN
                ========================= */

                header("Location: input.php");

                exit();


            } else {

                $message =
                    "Invalid email or password.";

            }


        } else {

            $message =
                "Invalid email or password.";

        }


        mysqli_stmt_close($stmt);

    }

}

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Sign In - QuickBite</title>


<style>

/* =====================================
   RESET
===================================== */

* {

    margin: 0;

    padding: 0;

    box-sizing: border-box;

}


/* =====================================
   BODY
===================================== */

body {

    min-height: 100vh;

    font-family: Arial, sans-serif;

    background: #f7f7f7;

}


/* =====================================
   HEADER
===================================== */

header {

    width: 100%;

    height: 75px;

    background: #ff5722;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 50px;

}


/* =====================================
   LOGO
===================================== */

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

    font-size: 25px;

}


/* =====================================
   NAVIGATION
===================================== */

nav {

    display: flex;

    align-items: center;

    gap: 25px;

}


nav a {

    text-decoration: none;

    color: white;

    font-size: 16px;

    font-weight: bold;

}


nav a:hover {

    color: #ffe0d6;

}


/* =====================================
   LOGIN CONTAINER
===================================== */

.login-container {

    min-height: calc(100vh - 75px);

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 30px 20px;

}


/* =====================================
   LOGIN BOX
===================================== */

.login-box {

    width: 100%;

    max-width: 430px;

    background: white;

    padding: 35px;

    border-radius: 15px;

    box-shadow:
        0 5px 25px
        rgba(0,0,0,0.10);

}


/* =====================================
   TITLE
===================================== */

.login-box h1 {

    text-align: center;

    color: #ff5722;

    margin-bottom: 10px;

    font-size: 32px;

}


.login-box .subtitle {

    text-align: center;

    color: #777;

    margin-bottom: 25px;

}


/* =====================================
   MESSAGE
===================================== */

.message {

    background: #ffe5df;

    color: #d84315;

    padding: 12px;

    border-radius: 7px;

    margin-bottom: 20px;

    text-align: center;

    font-size: 14px;

}


/* =====================================
   FORM
===================================== */

.form-group {

    margin-bottom: 20px;

}


.form-group label {

    display: block;

    margin-bottom: 8px;

    font-weight: bold;

    color: #333;

}


.form-group input {

    width: 100%;

    padding: 13px;

    border: 1px solid #ddd;

    border-radius: 7px;

    outline: none;

    font-size: 15px;

}


.form-group input:focus {

    border-color: #ff5722;

    box-shadow:
        0 0 0 2px
        rgba(255,87,34,0.10);

}


/* =====================================
   PASSWORD AREA
===================================== */

.password-box {

    position: relative;

}


.password-box input {

    padding-right: 80px;

}


.show-password {

    position: absolute;

    right: 12px;

    top: 50%;

    transform: translateY(-50%);

    border: none;

    background: transparent;

    color: #ff5722;

    cursor: pointer;

    font-weight: bold;

}


/* =====================================
   LOGIN BUTTON
===================================== */

.login-btn {

    width: 100%;

    padding: 14px;

    background: #ff5722;

    color: white;

    border: none;

    border-radius: 8px;

    font-size: 17px;

    font-weight: bold;

    cursor: pointer;

}


.login-btn:hover {

    background: #e64a19;

}


/* =====================================
   REGISTER LINK
===================================== */

.register-text {

    text-align: center;

    margin-top: 22px;

    color: #666;

}


.register-text a {

    color: #ff5722;

    text-decoration: none;

    font-weight: bold;

}


.register-text a:hover {

    text-decoration: underline;

}


/* =====================================
   MOBILE
===================================== */

@media (max-width: 700px) {

    header {

        padding: 0 20px;

    }


    nav {

        gap: 10px;

    }


    nav a {

        font-size: 13px;

    }


    .logo h2 {

        font-size: 21px;

    }


    .login-box {

        padding: 25px;

    }

}
/* =====================================
   FORGOT PASSWORD
===================================== */

.forgot-password {

    text-align: right;

    margin-top: 15px;

}


.forgot-password a {

    color: #ff5722;

    text-decoration: none;

    font-size: 14px;

    font-weight: bold;

}


.forgot-password a:hover {

    text-decoration: underline;

}

</style>

</head>


<body>


<!-- ===================================
     HEADER
=================================== -->

<header>


    <div class="logo">

        <img
            src="image/logo11.jpg"
            alt="QuickBite Logo"
        >

        <h2>QuickBite</h2>

    </div>


    <nav>

        <a href="input.php">
            Home
        </a>

        <a href="menu.php">
            Menu
        </a>

        <a href="cart.php">
            Cart
        </a>

        <a href="contact.php">
            Contact
        </a>

    </nav>


</header>



<!-- ===================================
     LOGIN
=================================== -->

<div class="login-container">


    <div class="login-box">


        <h1>Sign In</h1>


        <p class="subtitle">
            Login to your QuickBite account
        </p>


        <?php if (!empty($message)): ?>

            <div class="message">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php endif; ?>


        <form
            method="POST"
            action=""
            onsubmit="return validateLogin();"
        >


            <!-- EMAIL -->

            <div class="form-group">

                <label for="email">
                    Email Address
                </label>


                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Enter your email"
                    autocomplete="email"
                >

            </div>



            <!-- PASSWORD -->

            <div class="form-group">

                <label for="password">
                    Password
                </label>


                <div class="password-box">

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                    >


                    <button
                        type="button"
                        class="show-password"
                        onclick="togglePassword()"
                    >

                        Show

                    </button>
<p class="forgot-password">

    <a href="forgot_password.php">
        Forgot Password?
    </a>

</p>
                </div>

            </div>



            <!-- LOGIN -->

            <button
                type="submit"
                name="login"
                class="login-btn"
            >

                Sign In

            </button>


        </form>



        <!-- REGISTER -->

        <p class="register-text">

            Don't have an account?

            <a href="sigup.php">
                Create Account
            </a>

        </p>


    </div>

</div>



<!-- ===================================
     JAVASCRIPT
=================================== -->

<script>


/* =====================================
   LOGIN VALIDATION
===================================== */

function validateLogin() {


    let email =
        document.getElementById("email")
        .value
        .trim();


    let password =
        document.getElementById("password")
        .value
        .trim();


    if (email === "") {

        alert("Please enter your email.");

        return false;

    }


    if (password === "") {

        alert("Please enter your password.");

        return false;

    }


    let emailPattern =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


    if (!emailPattern.test(email)) {

        alert("Please enter a valid email.");

        return false;

    }


    return true;

}


/* =====================================
   SHOW / HIDE PASSWORD
===================================== */

function togglePassword() {


    let password =
        document.getElementById("password");


    let button =
        document.querySelector(
            ".show-password"
        );


    if (password.type === "password") {

        password.type = "text";

        button.innerText = "Hide";

    } else {

        password.type = "password";

        button.innerText = "Show";

    }

}

</script>


</body>

</html>