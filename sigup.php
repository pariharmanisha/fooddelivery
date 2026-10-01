<?php

session_start();

include "db.php";

$message = "";
$message_type = "";


/* =====================================
   SIGN UP
===================================== */

if (isset($_POST['signup'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);


    /* =================================
       EMPTY CHECK
    ================================= */

    if (
        empty($name) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password) ||
        empty($phone) ||
        empty($address) ||
        empty($city) ||
        empty($pincode)
    ) {

        $message = "Please fill all fields.";
        $message_type = "error";

    }


    /* =================================
       EMAIL CHECK
    ================================= */

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    }


    /* =================================
       PHONE CHECK
    ================================= */

    elseif (!preg_match('/^[0-9]{10}$/', $phone)) {

        $message = "Please enter a valid 10-digit phone number.";
        $message_type = "error";

    }


    /* =================================
       PINCODE CHECK
    ================================= */

    elseif (!preg_match('/^[0-9]{6}$/', $pincode)) {

        $message = "Please enter a valid 6-digit pincode.";
        $message_type = "error";

    }


    /* =================================
       PASSWORD CHECK
    ================================= */

    elseif (strlen($password) < 5) {

        $message = "Password must be at least 5 characters.";
        $message_type = "error";

    }


    /* =================================
       CONFIRM PASSWORD
    ================================= */

    elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";
        $message_type = "error";

    }


    else {


        /* =================================
           CHECK EMAIL
        ================================= */

        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ?"
        );


        mysqli_stmt_bind_param(
            $check,
            "s",
            $email
        );


        mysqli_stmt_execute($check);


        $result = mysqli_stmt_get_result($check);


        if (mysqli_num_rows($result) > 0) {

            $message =
                "Email already registered. Please Sign In.";

            $message_type = "error";

            mysqli_stmt_close($check);

        }

        else {

            mysqli_stmt_close($check);


            /* =================================
               INSERT USER
            ================================= */

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO users
                (
                    name,
                    email,
                    password,
                    phone,
                    address,
                    city,
                    pincode
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );


            if (!$stmt) {

                die(
                    "SQL Error: " .
                    mysqli_error($conn)
                );

            }


            mysqli_stmt_bind_param(
                $stmt,
                "sssssss",
                $name,
                $email,
                $password,
                $phone,
                $address,
                $city,
                $pincode
            );


            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_close($stmt);


                echo "<script>

                    alert(
                        'Account created successfully!'
                    );

                    window.location.href='signin.php';

                </script>";

                exit();

            }

            else {

                $message =
                    "Registration failed: " .
                    mysqli_stmt_error($stmt);

                $message_type = "error";

                mysqli_stmt_close($stmt);

            }

        }

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

<title>Sign Up - QuickBite</title>


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
   NAV
===================================== */

nav {

    display: flex;

    align-items: center;

    gap: 25px;

}


nav a {

    color: white;

    text-decoration: none;

    font-size: 16px;

    font-weight: bold;

}


nav a:hover {

    color: #ffe0d6;

}


/* =====================================
   SIGNUP CONTAINER
===================================== */

.signup-container {

    min-height: calc(100vh - 75px);

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 40px 20px;

}


/* =====================================
   SIGNUP BOX
===================================== */

.signup-box {

    width: 100%;

    max-width: 550px;

    background: white;

    padding: 35px;

    border-radius: 15px;

    box-shadow:
        0 5px 25px rgba(0,0,0,0.10);

}


/* =====================================
   TITLE
===================================== */

.signup-box h1 {

    text-align: center;

    color: #ff5722;

    font-size: 32px;

    margin-bottom: 8px;

}


.subtitle {

    text-align: center;

    color: #777;

    margin-bottom: 25px;

}


/* =====================================
   MESSAGE
===================================== */

.message {

    padding: 12px;

    border-radius: 7px;

    margin-bottom: 20px;

    text-align: center;

}


.error {

    background: #ffe5df;

    color: #d84315;

}


/* =====================================
   FORM
===================================== */

.form-group {

    margin-bottom: 17px;

}


.form-group label {

    display: block;

    margin-bottom: 7px;

    font-weight: bold;

    color: #333;

}


.form-group input,
.form-group textarea {

    width: 100%;

    padding: 12px;

    border: 1px solid #ddd;

    border-radius: 7px;

    outline: none;

    font-size: 15px;

}


.form-group input:focus,
.form-group textarea:focus {

    border-color: #ff5722;

}


.form-group textarea {

    height: 80px;

    resize: vertical;

}


/* =====================================
   TWO COLUMNS
===================================== */

.row {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 15px;

}


/* =====================================
   PASSWORD
===================================== */

.password-box {

    position: relative;

}


.password-box input {

    padding-right: 65px;

}


.show-btn {

    position: absolute;

    right: 10px;

    top: 50%;

    transform: translateY(-50%);

    background: none;

    border: none;

    color: #ff5722;

    font-weight: bold;

    cursor: pointer;

}


/* =====================================
   SIGNUP BUTTON
===================================== */

.signup-btn {

    width: 100%;

    padding: 14px;

    background: #ff5722;

    color: white;

    border: none;

    border-radius: 8px;

    font-size: 17px;

    font-weight: bold;

    cursor: pointer;

    margin-top: 5px;

}


.signup-btn:hover {

    background: #e64a19;

}


/* =====================================
   LOGIN
===================================== */

.login-text {

    text-align: center;

    margin-top: 22px;

    color: #666;

}


.login-text a {

    color: #ff5722;

    text-decoration: none;

    font-weight: bold;

}


/* =====================================
   MOBILE
===================================== */

@media(max-width: 600px) {

    header {

        padding: 0 20px;

    }

    nav {

        gap: 10px;

    }

    nav a {

        font-size: 12px;

    }

    .signup-box {

        padding: 25px 20px;

    }

    .row {

        grid-template-columns: 1fr;

        gap: 0;

    }

}

</style>

</head>


<body>


<!-- =====================================
     HEADER
===================================== -->

<header>


    <div class="logo">

        <img
            src="image/logo11.jpg"
            alt="QuickBite Logo"
        >

        <h2>QuickBite</h2>

    </div>


    <nav>

        <a href="index.php">
            Home
        </a>

        <a href="menu.php">
            Menu
        </a>

        <a href="cart.php">
            Cart
        </a>

        <a href="signin.php">
            Sign In
        </a>

    </nav>


</header>



<!-- =====================================
     SIGNUP
===================================== -->

<div class="signup-container">


    <div class="signup-box">


        <h1>Create Account</h1>

        <p class="subtitle">
            Join QuickBite today
        </p>


        <?php if (!empty($message)): ?>

            <div class="message error">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php endif; ?>



        <form
            method="POST"
            action=""
            onsubmit="return validateSignup();"
        >


            <!-- NAME -->

            <div class="form-group">

                <label>
                    Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    placeholder="Enter your full name"
                    value="<?php
                    echo isset($_POST['name'])
                        ? htmlspecialchars($_POST['name'])
                        : '';
                    ?>"
                >

            </div>



            <!-- EMAIL -->

            <div class="form-group">

                <label>
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?php
                    echo isset($_POST['email'])
                        ? htmlspecialchars($_POST['email'])
                        : '';
                    ?>"
                >

            </div>



            <!-- PHONE -->

            <div class="form-group">

                <label>
                    Phone Number
                </label>

                <input
                    type="tel"
                    name="phone"
                    maxlength="10"
                    placeholder="Enter 10-digit phone number"
                    value="<?php
                    echo isset($_POST['phone'])
                        ? htmlspecialchars($_POST['phone'])
                        : '';
                    ?>"
                >

            </div>



            <!-- ADDRESS -->

            <div class="form-group">

                <label>
                    Address
                </label>

                <textarea
                    name="address"
                    placeholder="Enter your full address"
                ><?php
                echo isset($_POST['address'])
                    ? htmlspecialchars($_POST['address'])
                    : '';
                ?></textarea>

            </div>



            <!-- CITY + PINCODE -->

            <div class="row">


                <div class="form-group">

                    <label>
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        placeholder="Enter city"
                        value="<?php
                        echo isset($_POST['city'])
                            ? htmlspecialchars($_POST['city'])
                            : '';
                        ?>"
                    >

                </div>



                <div class="form-group">

                    <label>
                        Pincode
                    </label>

                    <input
                        type="text"
                        name="pincode"
                        maxlength="6"
                        placeholder="Enter pincode"
                        value="<?php
                        echo isset($_POST['pincode'])
                            ? htmlspecialchars($_POST['pincode'])
                            : '';
                        ?>"
                    >

                </div>


            </div>



            <!-- PASSWORD -->

            <div class="form-group">

                <label>
                    Password
                </label>


                <div class="password-box">

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Create password"
                    >

                    <button
                        type="button"
                        class="show-btn"
                        onclick="togglePassword('password', this)"
                    >
                        Show
                    </button>

                </div>

            </div>



            <!-- CONFIRM PASSWORD -->

            <div class="form-group">

                <label>
                    Confirm Password
                </label>


                <div class="password-box">

                    <input
                        type="password"
                        name="confirm_password"
                        id="confirm_password"
                        placeholder="Confirm password"
                    >

                    <button
                        type="button"
                        class="show-btn"
                        onclick="togglePassword('confirm_password', this)"
                    >
                        Show
                    </button>

                </div>

            </div>



            <!-- BUTTON -->

            <button
                type="submit"
                name="signup"
                class="signup-btn"
            >

                Create Account

            </button>


        </form>



        <p class="login-text">

            Already have an account?

            <a href="signin.php">
                Sign In
            </a>

        </p>


    </div>

</div>



<script>

/* =====================================
   VALIDATION
===================================== */

function validateSignup() {

    let name =
        document.querySelector(
            '[name="name"]'
        ).value.trim();

    let email =
        document.querySelector(
            '[name="email"]'
        ).value.trim();

    let phone =
        document.querySelector(
            '[name="phone"]'
        ).value.trim();

    let address =
        document.querySelector(
            '[name="address"]'
        ).value.trim();

    let city =
        document.querySelector(
            '[name="city"]'
        ).value.trim();

    let pincode =
        document.querySelector(
            '[name="pincode"]'
        ).value.trim();

    let password =
        document.getElementById(
            "password"
        ).value.trim();

    let confirmPassword =
        document.getElementById(
            "confirm_password"
        ).value.trim();


    if (name === "") {

        alert("Please enter your name.");

        return false;

    }


    if (email === "") {

        alert("Please enter your email.");

        return false;

    }


    if (phone === "") {

        alert("Please enter your phone number.");

        return false;

    }


    if (!/^[0-9]{10}$/.test(phone)) {

        alert(
            "Phone number must be 10 digits."
        );

        return false;

    }


    if (address === "") {

        alert("Please enter your address.");

        return false;

    }


    if (city === "") {

        alert("Please enter your city.");

        return false;

    }


    if (!/^[0-9]{6}$/.test(pincode)) {

        alert(
            "Pincode must be 6 digits."
        );

        return false;

    }


    if (password.length < 5) {

        alert(
            "Password must be at least 5 characters."
        );

        return false;

    }


    if (password !== confirmPassword) {

        alert(
            "Passwords do not match."
        );

        return false;

    }


    return true;

}


/* =====================================
   SHOW PASSWORD
===================================== */

function togglePassword(
    id,
    button
) {

    let input =
        document.getElementById(id);


    if (input.type === "password") {

        input.type = "text";

        button.innerText = "Hide";

    }

    else {

        input.type = "password";

        button.innerText = "Show";

    }

}

</script>


</body>

</html>