<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION["isAdmin"]) || !$_SESSION["isAdmin"]) {
	header("Location: /index.php");
	exit();
}