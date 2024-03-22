<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION["userID"])) {
	header("Location: /");
	exit();
}
require_once "../_components/database.php";
$db = new Database();

// Delete user
$result = $db->deleteUser($_SESSION["userID"]);
if ($result) {
	session_destroy();
	header("Location: /");
} else {
	header("Location: /account/manage.php");
}

exit();