<?php

session_start();

include "db.php";

$message = "";
$message_type = "";


/* =====================================
   RESET PASSWORD
===================================== */

if (isset($_POST['reset_password'])) {

    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);


    /* =================================
       EMPTY CHECK
    ================================= */

    if (
        empty($email) ||
        empty($phone) ||
        empty($new_password) ||
        empty($confirm_password)
    ) {

        $message =
            "Please fill all fields.";

        $message_type = "error";

    }


    /* =================================
       EMAIL CHECK
    ================================= */

    elseif (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $message =
            "Please enter a valid email.";

        $message_type = "error";

    }


    /* =================================
       PHONE CHECK
    ================================= */

    elseif (
        !preg_match(
            '/^[0-9]{10}$/',
            $phone
        )
    ) {

        $message =
            "Phone number must be 10 digits.";

        $message_type = "error";

    }


    /* =================================
       PASSWORD LENGTH
    ================================= */

    elseif (
        strlen($new_password) < 5
    ) {

        $message =
            "Password must be at least 5 characters.";

        $message_type = "error";

    }


    /* =================================
       PASSWORD MATCH
    ================================= */

    elseif (
        $new_password !== $confirm_password
    ) {

        $message =
            "Passwords do not match.";

        $message_type = "error";

    }


    else {


        /* =================================
           FIND USER
        ================================= */

        $stmt = mysqli_prepare(
            $conn,

            "SELECT id
             FROM users
             WHERE email = ?
             AND phone = ?"
        );


        if (!$stmt) {

            die(
                "SQL Error: " .
                mysqli_error($conn)
            );

        }


        mysqli_stmt_bind_param(
            $stmt,
            "ss",
            $email,
            $phone
        );


        mysqli_stmt_execute($stmt);


        $result =
            mysqli_stmt_get_result($stmt);


        /* =================================
           USER FOUND
        ================================= */

        if (
            mysqli_num_rows($result) == 1
        ) {

            $user =
                mysqli_fetch_assoc($result);


            /* =================================
               UPDATE PASSWORD
            ================================= */

            $update = mysqli_prepare(
                $conn,

                "UPDATE users
                 SET password = ?
                 WHERE id = ?"
            );


            mysqli_stmt_bind_param(
                $update,
                "si",
                $new_password,
                $user['id']
            );


            if (
                mysqli_stmt_execute($update)
            ) {

                $message =
                    "Password reset successfully. Please Sign In.";

                $message_type =
                    "success";

            }
            else {

                $message =
                    "Password reset failed.";

                $message_type =
                    "error";

            }


            mysqli_stmt_close($update);

        }

        else {

            $message =
                "Email and phone number do not match.";

            $message_type =
                "error";

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

<title>Forgot Password - QuickBite</title>


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
   CONTAINER
===================================== */

.forgot-container {

    min-height: calc(100vh - 75px);

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 30px 20px;

}


/* =====================================
   BOX
===================================== */

.forgot-box {

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

.forgot-box h1 {

    text-align: center;

    color: #ff5722;

    margin-bottom: 10px;

    font-size: 30px;

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

    font-size: 14px;

}


.success {

    background: #e8f5e9;

    color: #2e7d32;

}


.error {

    background: #ffe5df;

    color: #d84315;

}


/* =====================================
   FORM
===================================== */

.form-group {

    margin-bottom: 18px;

}


.form-group label {

    display: block;

    margin-bottom: 8px;

    font-weight: bold;

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

}


/* =====================================
   BUTTON
===================================== */

.reset-btn {

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


.reset-btn:hover {

    background: #e64a19;

}


/* =====================================
   SIGN IN LINK
===================================== */

.signin-text {

    text-align: center;

    margin-top: 22px;

    color: #666;

}


.signin-text a {

    color: #ff5722;

    text-decoration: none;

    font-weight: bold;

}


.signin-text a:hover {

    text-decoration: underline;

}


/* =====================================
   MOBILE
===================================== */

@media(max-width: 700px) {

    header {

        padding: 0 20px;

    }


    nav {

        gap: 10px;

    }


    nav a {

        font-size: 13px;

    }


    .forgot-box {

        padding: 25px;

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

        <a href="input.php">
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
     FORGOT PASSWORD
===================================== -->

<div class="forgot-container">


    <div class="forgot-box">


        <h1>
            Forgot Password?
        </h1>


        <p class="subtitle">

            Reset your QuickBite password

        </p>



        <?php if (!empty($message)): ?>

            <div class="message
                <?php echo $message_type; ?>">

                <?php

                echo htmlspecialchars(
                    $message
                );

                ?>

            </div>

        <?php endif; ?>



        <form
            method="POST"
            onsubmit="return validateReset();"
        >


            <!-- EMAIL -->

            <div class="form-group">

                <label>
                    Email Address
                </label>


                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Enter your email"
                    required
                >

            </div>



            <!-- PHONE -->

            <div class="form-group">

                <label>
                    Phone Number
                </label>


                <input
                    type="text"
                    name="phone"
                    id="phone"
                    maxlength="10"
                    placeholder="Enter your phone number"
                    required
                >

            </div>



            <!-- NEW PASSWORD -->

            <div class="form-group">

                <label>
                    New Password
                </label>


                <input
                    type="password"
                    name="new_password"
                    id="new_password"
                    placeholder="Enter new password"
                    required
                >

            </div>



            <!-- CONFIRM PASSWORD -->

            <div class="form-group">

                <label>
                    Confirm Password
                </label>


                <input
                    type="password"
                    name="confirm_password"
                    id="confirm_password"
                    placeholder="Confirm new password"
                    required
                >

            </div>



            <!-- BUTTON -->

            <button
                type="submit"
                name="reset_password"
                class="reset-btn"
            >

                Reset Password

            </button>


        </form>



        <p class="signin-text">

            Remember your password?

            <a href="signin.php">
                Sign In
            </a>

        </p>


    </div>

</div>



<script>

function validateReset() {

    let phone =
        document.getElementById("phone")
        .value.trim();


    let password =
        document.getElementById("new_password")
        .value.trim();


    let confirmPassword =
        document.getElementById("confirm_password")
        .value.trim();


    if (!/^[0-9]{10}$/.test(phone)) {

        alert(
            "Phone number must be 10 digits."
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

</script>


</body>

</html>