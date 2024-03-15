<?php
	require_once "../_components/adminCheck.php";
	require_once "../../_components/database.php";

	if (!isset($_GET['id'])) {
		exit("ID not provided for deletion!");
	}

	$db = new Database();
	$result = $db->deleteAlert($_GET['id']);

	if (!$result) exit ("Failed to delete alert.");
	else header("Location: ./");