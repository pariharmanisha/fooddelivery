<?php

session_start();

include "db.php";


/* =====================================
   CHECK LOGIN
===================================== */

if (!isset($_SESSION['user_id'])) {

    echo "<script>
        alert('Please login first.');
        window.location.href='signin.php';
    </script>";

    exit();
}


$user_id = $_SESSION['user_id'];

$message = "";
$message_type = "";


/* =====================================
   GET USER DETAILS
===================================== */

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, name, email, password, phone, address, city, pincode
     FROM users
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$user) {

    session_destroy();

    header("Location: signin.php");

    exit();
}


/* =====================================
   UPDATE PROFILE
===================================== */

if (isset($_POST['update_profile'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);


    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($address) ||
        empty($city) ||
        empty($pincode)
    ) {

        $message = "Please fill all fields.";

        $message_type = "error";

    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email.";

        $message_type = "error";

    }

    elseif (!preg_match('/^[0-9]{10}$/', $phone)) {

        $message = "Phone number must be 10 digits.";

        $message_type = "error";

    }

    elseif (!preg_match('/^[0-9]{6}$/', $pincode)) {

        $message = "Pincode must be 6 digits.";

        $message_type = "error";

    }

    else {

        /* Check email */

        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM users
             WHERE email = ?
             AND id != ?"
        );

        mysqli_stmt_bind_param(
            $check,
            "si",
            $email,
            $user_id
        );

        mysqli_stmt_execute($check);

        $check_result =
            mysqli_stmt_get_result($check);


        if (mysqli_num_rows($check_result) > 0) {

            $message =
                "This email is already registered.";

            $message_type = "error";

        }

        else {

            /* =====================================
               UPDATE ALL PROFILE DETAILS
            ===================================== */

            $update = mysqli_prepare(
                $conn,
                "UPDATE users
                 SET name = ?,
                     email = ?,
                     phone = ?,
                     address = ?,
                     city = ?,
                     pincode = ?
                 WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $update,
                "ssssssi",
                $name,
                $email,
                $phone,
                $address,
                $city,
                $pincode,
                $user_id
            );


            if (mysqli_stmt_execute($update)) {

                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;

                $user['name'] = $name;
                $user['email'] = $email;
                $user['phone'] = $phone;
                $user['address'] = $address;
                $user['city'] = $city;
                $user['pincode'] = $pincode;

                $message =
                    "Profile updated successfully.";

                $message_type = "success";

            }
            else {

                $message =
                    "Profile update failed.";

                $message_type = "error";

            }

            mysqli_stmt_close($update);

        }

        mysqli_stmt_close($check);

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

<title>My Profile - QuickBite</title>


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

    font-family: Arial, sans-serif;

    background: #f7f7f7;

    color: #333;

}


/* =====================================
   HEADER
===================================== */

header {

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

    gap: 22px;

}


nav a {

    color: white;

    text-decoration: none;

    font-weight: bold;

}


nav a:hover {

    color: #ffe0d6;

}


/* =====================================
   PROFILE CONTAINER
===================================== */

.profile-container {

    width: 90%;

    max-width: 1000px;

    margin: 40px auto;

}


/* =====================================
   TITLE
===================================== */

.profile-title {

    text-align: center;

    margin-bottom: 30px;

}


.profile-title h1 {

    color: #ff5722;

    font-size: 36px;

}


.profile-title p {

    color: #777;

    margin-top: 8px;

}


/* =====================================
   PROFILE GRID
===================================== */

.profile-grid {

    display: grid;

    grid-template-columns: 300px 1fr;

    gap: 30px;

}


/* =====================================
   PROFILE CARD
===================================== */

.profile-card {

    background: white;

    padding: 30px;

    border-radius: 15px;

    box-shadow:
        0 5px 20px
        rgba(0,0,0,0.08);

    text-align: center;

    height: fit-content;

}


.profile-icon {

    width: 100px;

    height: 100px;

    border-radius: 50%;

    background: #ff5722;

    color: white;

    display: flex;

    justify-content: center;

    align-items: center;

    margin: 0 auto 20px;

    font-size: 45px;

    font-weight: bold;

}


.profile-card h2 {

    margin-bottom: 8px;

}


.profile-card p {

    color: #777;

    word-break: break-word;

    margin-bottom: 7px;

}


/* =====================================
   LOGOUT BUTTON
===================================== */

.logout-btn {

    display: block;

    margin-top: 25px;

    padding: 13px;

    background: #e53935;

    color: white;

    text-decoration: none;

    border-radius: 7px;

    font-weight: bold;

}


.logout-btn:hover {

    background: #c62828;

}


/* =====================================
   FORM CARDS
===================================== */

.form-card {

    background: white;

    padding: 30px;

    border-radius: 15px;

    box-shadow:
        0 5px 20px
        rgba(0,0,0,0.08);

    margin-bottom: 25px;

}


.form-card h2 {

    margin-bottom: 22px;

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


.success {

    background: #e8f5e9;

    color: #2e7d32;

}


.error {

    background: #ffebee;

    color: #c62828;

}


/* =====================================
   FORM
===================================== */

.form-group {

    margin-bottom: 18px;

}


.form-group label {

    display: block;

    font-weight: bold;

    margin-bottom: 7px;

}


.form-group input,
.form-group textarea {

    width: 100%;

    padding: 13px;

    border: 1px solid #ddd;

    border-radius: 7px;

    font-size: 15px;

    outline: none;

}


.form-group textarea {

    min-height: 80px;

    resize: vertical;

}


.form-group input:focus,
.form-group textarea:focus {

    border-color: #ff5722;

}


/* =====================================
   TWO COLUMN
===================================== */

.form-row {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 15px;

}


/* =====================================
   BUTTON
===================================== */

.update-btn {

    width: 100%;

    padding: 13px;

    background: #ff5722;

    color: white;

    border: none;

    border-radius: 7px;

    font-size: 16px;

    font-weight: bold;

    cursor: pointer;

}


.update-btn:hover {

    background: #e64a19;

}


/* =====================================
   MOBILE
===================================== */

@media(max-width: 800px) {

    header {

        padding: 0 20px;

    }


    nav {

        gap: 10px;

    }


    nav a {

        font-size: 13px;

    }


    .profile-grid {

        grid-template-columns: 1fr;

    }


    .form-row {

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
            alt="QuickBite"
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
<a href="myorder.php">Order</a>
       
            <a href="about.php">About us</a>
            <a href="contact.php">Contact us</a>
            <a href="profile.php"  class="active">Profile</a>
            <a href="cart.php">🛒 Cart</a>

    </nav>

</header>



<!-- =====================================
     PROFILE
===================================== -->

<div class="profile-container">


    <div class="profile-title">

        <h1>My Profile</h1>

        <p>
            Manage your QuickBite account
        </p>

    </div>



    <?php if (!empty($message)): ?>

        <div class="message <?php
            echo $message_type;
        ?>">

            <?php
            echo htmlspecialchars($message);
            ?>

        </div>

    <?php endif; ?>



    <div class="profile-grid">


        <!-- =================================
             PROFILE INFORMATION
        ================================== -->

        <div class="profile-card">


            <div class="profile-icon">

                <?php

                echo strtoupper(
                    substr($user['name'], 0, 1)
                );

                ?>

            </div>


            <h2>

                <?php

                echo htmlspecialchars(
                    $user['name']
                );

                ?>

            </h2>


            <p>

                <?php

                echo htmlspecialchars(
                    $user['email']
                );

                ?>

            </p>


            <!-- PHONE -->

            <p>

                📞

                <?php

                echo htmlspecialchars(
                    $user['phone']
                );

                ?>

            </p>


            <!-- ADDRESS -->

            <p>

                📍

                <?php

                echo htmlspecialchars(
                    $user['address']
                );

                ?>

            </p>


            <!-- CITY -->

            <p>

                🏙️

                <?php

                echo htmlspecialchars(
                    $user['city']
                );

                ?>

            </p>


            <!-- PINCODE -->

            <p>

                📮

                <?php

                echo htmlspecialchars(
                    $user['pincode']
                );

                ?>

            </p>


            <a
                href="logout.php"
                class="logout-btn"
                onclick="return confirmLogout();"
            >

                Logout

            </a>


        </div>



        <!-- =================================
             RIGHT SIDE
        ================================= -->

        <div>


            <!-- EDIT PROFILE -->

            <div class="form-card">

                <h2>
                    Edit Profile
                </h2>


                <form method="POST">


                    <!-- NAME -->

                    <div class="form-group">

                        <label>
                            Full Name
                        </label>


                        <input
                            type="text"
                            name="name"
                            value="<?php

                            echo htmlspecialchars(
                                $user['name']
                            );

                            ?>"
                            required
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
                            value="<?php

                            echo htmlspecialchars(
                                $user['email']
                            );

                            ?>"
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
                            maxlength="10"
                            value="<?php

                            echo htmlspecialchars(
                                $user['phone']
                            );

                            ?>"
                            required
                        >

                    </div>



                    <!-- ADDRESS -->

                    <div class="form-group">

                        <label>
                            Address
                        </label>


                        <textarea
                            name="address"
                            required
                        ><?php

                        echo htmlspecialchars(
                            $user['address']
                        );

                        ?></textarea>

                    </div>



                    <!-- CITY + PINCODE -->

                    <div class="form-row">


                        <div class="form-group">

                            <label>
                                City
                            </label>


                            <input
                                type="text"
                                name="city"
                                value="<?php

                                echo htmlspecialchars(
                                    $user['city']
                                );

                                ?>"
                                required
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
                                value="<?php

                                echo htmlspecialchars(
                                    $user['pincode']
                                );

                                ?>"
                                required
                            >

                        </div>


                    </div>



                    <!-- UPDATE -->

                    <button
                        type="submit"
                        name="update_profile"
                        class="update-btn"
                    >

                        Update Profile

                    </button>


                </form>

    </div>


<script>

function confirmLogout() {

    return confirm(
        "Are you sure you want to logout?"
    );

}

</script>


</body>

</html>