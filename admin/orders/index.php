<?php
	require_once "../_components/adminCheck.php";
	require_once "../../_components/database.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include '../../_components/default.php'; ?>
	<title>Admin - Orders</title>
	<link rel="stylesheet" href="../../_stylesheets/products.css">
	<link rel="stylesheet" href="../admin.css">
</head>
<body>
	<?php include '../_components/header.php'; ?>
	<section class="blue-3">
		<h1>ORDERS</h1>
	</section>
	<main>
		<aside>
            <div class="asideContent">
                <div class="topAside">
                    <div class="searchGroup">
                        <label class="sr-only" for="search">SEARCH</label>
                        <input type="text" id="search" placeholder="Search by ID...">
                    </div>
                    <div class="filterGroup" data-for="direction">
                        <div class="title">
                            <h2>DIRECTION</h2>
                            <span><a href="#">Deselect</a></span>
                        </div>
                        <div class="inputLabelGroup" >
                            <input type="radio" name="direction" id="in" value="in">
                            <label for="in">In</label>
                        </div>
                        <div class="inputLabelGroup" >
                            <input type="radio" name="direction" id="out" value="out">
                            <label for="out">Out</label>
                        </div>
                    </div>
                </div>
                <button class="fullWidth" onclick="window.location.href='./upsert.php'">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Order</span>
                </button>
            </div>
		</aside>
	</main>
</body>
</html>
