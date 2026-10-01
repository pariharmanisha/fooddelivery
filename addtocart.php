<?php

session_start();

include "db.php";

if (!isset($_POST['id'])) {
    header("Location: menu.php");
    exit;
}

$id = intval($_POST['id']);


// Food database se find karo
$stmt = mysqli_prepare(
    $conn,
    "SELECT id, food_name, description, price, category, image
     FROM foods
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$food = mysqli_fetch_assoc($result);


if (!$food) {
    header("Location: menu.php");
    exit;
}


// Cart create karo
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


// Agar item already cart me hai
if (isset($_SESSION['cart'][$id])) {

    $_SESSION['cart'][$id]['quantity']++;

} else {

    $_SESSION['cart'][$id] = [
        'id' => $food['id'],
        'food_name' => $food['food_name'],
        'description' => $food['description'],
        'price' => $food['price'],
        'category' => $food['category'],
        'image' => $food['image'],
        'quantity' => 1
    ];
}


// Cart page par bhejo
header("Location: cart.php");
exit;

?>