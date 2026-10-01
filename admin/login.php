<?php
session_start();

$error = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Admin login details
    if ($username === "quickbite" && $password === "123") {

        $_SESSION['admin'] = $username;

        header("Location: dashboard.php");
        exit();

    } else {

        $error = "Invalid username or password";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login - QuickBite</title>

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

        min-height: 100vh;

        display: flex;

        justify-content: center;

        align-items: center;

        background: #ff5722;

        font-family: Arial, sans-serif;

    }


    /* =========================
       LOGIN BOX
    ========================= */

    .login-box {

        width: 390px;

        background: white;

        padding: 40px 35px;

        border-radius: 15px;

        box-shadow:
            0 10px 30px rgba(0,0,0,0.25);

        text-align: center;

    }


    /* =========================
       LOGO
    ========================= */

    .logo {

        text-align: center;

        margin-bottom: 25px;

    }


    .logo img {

        width: 60px;

        height: 60px;

      

        border-radius: 50%;

        margin-bottom: 10px;


    }


    .logo h1 {

        color: #ff5722;

        font-size: 32px;

        margin-bottom: 5px;

    }

    .logo h2 {

        color: #333;

        font-size: 22px;

    }


    /* =========================
       ERROR MESSAGE
    ========================= */

    .error {

        background: #ffe5e5;

        color: #d00000;

        padding: 10px;

        border-radius: 6px;

        margin-bottom: 18px;

        font-size: 14px;

    }


    /* =========================
       INPUT
    ========================= */

    .login-box input {

        width: 100%;

        padding: 13px 15px;

        margin-bottom: 18px;

        border: 1px solid #ddd;

        border-radius: 7px;

        font-size: 15px;

        outline: none;

    }


    .login-box input:focus {

        border-color: #ff5722;

        box-shadow:
            0 0 5px rgba(255,87,34,0.3);

    }


    /* =========================
       LOGIN BUTTON
    ========================= */

    .login-box button {

        width: 100%;

        padding: 13px;

        background: #ff5722;

        color: white;

        border: none;

        border-radius: 7px;

        font-size: 16px;

        font-weight: bold;

        cursor: pointer;

        transition: 0.3s;

    }


    .login-box button:hover {

        background: #e64a19;

        transform: translateY(-2px);

    }


    /* =========================
       MOBILE
    ========================= */

    @media (max-width: 500px) {

        .login-box {

            width: 90%;

            padding: 30px 25px;

        }

        .logo h1 {

            font-size: 28px;

        }

        .logo h2 {

            font-size: 20px;

        }

    }

    </style>

</head>


<body>


<div class="login-box">

    <div class="logo">

        <!-- Your QuickBite logo -->
        <img src="../image/logo11.jpg" alt="QuickBite Logo">

        <h1>QuickBite</h1>

        <h2>Admin Login</h2>

    </div>


    <?php if ($error != "") { ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php } ?>


    <form method="POST">

        <input
            type="text"
            name="username"
            placeholder="Username"
            required
        >


        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >


        <button type="submit" name="login">
            Login
        </button>

    </form>

</div>


</body>

</html>