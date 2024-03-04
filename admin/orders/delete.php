<?php
	require_once "../_components/adminCheck.php";
	require_once "../../_components/database.php";

	if (!isset($_GET['id'])) {
		exit("ID not provided for deletion!");
	}

	// Only have to delete from DB this time.
	$db = new Database();
	$result = $db->deleteOrder($_GET['id']);

	if (!$result) exit ("Failed to delete order.");
	else header("Location: ./");