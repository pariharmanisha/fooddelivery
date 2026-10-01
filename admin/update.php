<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include "../db.php";


/* =========================================
   CHECK REQUEST
========================================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: foods.php");
    exit();

}


if (!isset($_POST['update_food'])) {

    header("Location: foods.php");
    exit();

}


/* =========================================
   CHECK ID
========================================= */

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {

    die("Invalid Food ID.");

}

$id = intval($_POST['id']);


/* =========================================
   GET DATA
========================================= */

$food_name = trim($_POST['food_name'] ?? "");

$description = trim($_POST['description'] ?? "");

$price = trim($_POST['price'] ?? "");

$category = trim($_POST['category'] ?? "");


/* =========================================
   VALIDATION
========================================= */

if ($food_name === "") {
    die("Food name is required.");
}

if ($description === "") {
    die("Description is required.");
}

if ($price === "" || !is_numeric($price)) {
    die("Please enter a valid price.");
}

if ($category === "") {
    die("Category is required.");
}


/* =========================================
   GET OLD IMAGE
========================================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT image FROM foods WHERE id = ?"
);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {

    mysqli_stmt_close($stmt);

    die("Food not found.");

}

$old_data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


$old_image = $old_data['image'];

$new_image = $old_image;


/* =========================================
   IMAGE FOLDER
========================================= */

$image_folder = "../image/";


/* =========================================
   CHECK IMAGE FOLDER
========================================= */

if (!is_dir($image_folder)) {

    die(
        "Image folder not found: " .
        $image_folder
    );

}


/* =========================================
   NEW IMAGE STATUS
========================================= */

$new_image_uploaded = false;

$new_image_path = "";


/* =========================================
   NEW IMAGE
========================================= */

if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
) {


    /* =====================================
       CHECK ERROR
    ===================================== */

    if (
        $_FILES['image']['error'] !==
        UPLOAD_ERR_OK
    ) {

        die(
            "Image upload failed. Error Code: " .
            $_FILES['image']['error']
        );

    }


    /* =====================================
       TEMP FILE
    ===================================== */

    $tmp_name =
        $_FILES['image']['tmp_name'];


    /* =====================================
       CHECK IMAGE
    ===================================== */

    $image_info =
        getimagesize($tmp_name);


    if ($image_info === false) {

        die(
            "Selected file is not a valid image."
        );

    }


    /* =====================================
       IMAGE TYPE
    ===================================== */

    $mime_type =
        $image_info['mime'];


    /* =====================================
       ALLOWED TYPES
    ===================================== */

    $allowed_types = [

        "image/jpeg" => "jpg",

        "image/png" => "png",

        "image/gif" => "gif",

        "image/webp" => "webp",

        "image/bmp" => "bmp"

    ];


    if (!isset($allowed_types[$mime_type])) {

        die(
            "Invalid image format."
        );

    }


    /* =====================================
       EXTENSION
    ===================================== */

    $extension =
        $allowed_types[$mime_type];


    /* =====================================
       NEW FILE NAME
    ===================================== */

    $new_image =
        "food_" .
        time() .
        "_" .
        uniqid() .
        "." .
        $extension;


    /* =====================================
       NEW FILE PATH
    ===================================== */

    $new_image_path =
        $image_folder .
        $new_image;


    /* =====================================
       MOVE NEW IMAGE
    ===================================== */

    if (
        !move_uploaded_file(
            $tmp_name,
            $new_image_path
        )
    ) {

        die(
            "New image could not be uploaded."
        );

    }


    $new_image_uploaded = true;

}


/* =========================================
   UPDATE DATABASE
========================================= */

$stmt = mysqli_prepare(
    $conn,

    "UPDATE foods
     SET food_name = ?,
         description = ?,
         price = ?,
         category = ?,
         image = ?
     WHERE id = ?"
);


if (!$stmt) {


    /* Remove new image */

    if (
        $new_image_uploaded &&
        file_exists($new_image_path)
    ) {

        unlink($new_image_path);

    }


    die(
        "Update Prepare Error: " .
        mysqli_error($conn)
    );

}


/* =========================================
   BIND
========================================= */

mysqli_stmt_bind_param(
    $stmt,
    "ssdssi",
    $food_name,
    $description,
    $price,
    $category,
    $new_image,
    $id
);


/* =========================================
   UPDATE
========================================= */

if (!mysqli_stmt_execute($stmt)) {


    $error =
        mysqli_stmt_error($stmt);


    mysqli_stmt_close($stmt);


    /* Delete new image */

    if (
        $new_image_uploaded &&
        file_exists($new_image_path)
    ) {

        unlink($new_image_path);

    }


    die(
        "Food update failed: " .
        $error
    );

}


mysqli_stmt_close($stmt);


/* =========================================
   DELETE OLD IMAGE
========================================= */

if (
    $new_image_uploaded &&
    !empty($old_image) &&
    $old_image !== $new_image
) {

    $old_image_path =
        $image_folder .
        $old_image;


    if (
        file_exists($old_image_path)
    ) {

        unlink($old_image_path);

    }

}


/* =========================================
   SUCCESS
========================================= */

header(
    "Location: foods.php?updated=success"
);

exit();

?>