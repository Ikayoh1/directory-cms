<?php
session_start();

// Destroy all session data
$_SESSION = array();
session_destroy();

// Optional: Redirect to login page or homepage
header("Location: login.php");
exit;
?>
