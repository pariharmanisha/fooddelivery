<?php

session_start();

/* Delete all session data */

$_SESSION = array();


/* Destroy session */

session_destroy();


/* Redirect to login */

header("Location: signin.php");

exit();

?>