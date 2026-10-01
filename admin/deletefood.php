<?php

include "../db.php";

// Check ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: foods.php");
    exit();
}

$id = intval($_GET['id']);

// Get food information first
$query = "SELECT * FROM foods WHERE id = ?";

$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    die("Prepare Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("Food not found.");
}

$food = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


// Delete food
$query = "DELETE FROM foods WHERE id = ?";

$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    die("Prepare Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $id);


if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    // Go back to foods page
    header("Location: foods.php");

    exit();

} else {

    echo "Delete Error: " . mysqli_error($conn);

    mysqli_stmt_close($stmt);
}

?>