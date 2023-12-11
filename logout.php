<?php // Quick file to log out users.
session_start();
unset($_SESSION['userID']); // We don't destroy the basket else users will lose their basket, which isn't intended behaviour.
unset($_SESSION['isAdmin']);
header("Location: index.php");
exit();