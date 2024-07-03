<?php
session_start();
unset($_SESSION['subject']);
header("Location: index3.php");
exit();
?>
