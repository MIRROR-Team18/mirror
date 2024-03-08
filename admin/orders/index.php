<?php
	require_once "../_components/adminCheck.php";
	require_once "../../_components/database.php";
    $db = new Database();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include '../../_components/default.php'; ?>
	<title>Admin - Orders</title>
	<link rel="stylesheet" href="../../_stylesheets/products.css">
	<link rel="stylesheet" href="../admin.css">
    <script src="../../_scripts/listFiltering.js" defer async></script>
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
                            <span>
                                <a href="#" id="orderType_only" class="selected sr-only">Only</a>
                                <a href="#" onclick="reset()">Deselect</a>
                            </span>
                        </div>
                        <div class="inputLabelGroup">
                            <input type="radio" name="direction" id="in" value="in">
                            <label for="in">In</label>
                        </div>
                        <div class="inputLabelGroup">
                            <input type="radio" name="direction" id="out" value="out">
                            <label for="out">Out</label>
                        </div>
                    </div>
                </div>
                <button class="fullWidth" onclick="window.location.href='./upsert.php'">
                    <i class="fa-solid fa-plus"></i>
                    Add Order
                </button>
            </div>
		</aside>
        <section id="orders" class="blue-1">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Items</th>
                        <th>Addressee</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $orders = $db->getAllOrders();

                    foreach ($orders as $order) {
                        $quantity = $db->getQuantityProductsInOrder($order['id']);
                        $address = $db->getAddressDetails($order['addressID'])
                        ?>
                        <tr class="listObject" data-direction="<?= $order['direction']; ?>" data-name="<?= $order['id']; ?>">
                            <td><?= $order['id']; ?></td>
                            <td><?= $quantity ?></td>
                            <td><?= $address['name'] ?? 'Unknown'; ?></td>
                            <td>£<?= $order['paidAmount']; ?></td>
                            <td>
                                <a href="./upsert.php?id=<?php echo $order['id']; ?>">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                        </tr>
                    <?php }
                ?>
                </tbody>
            </table>
        </section>
	</main>
    <?php include '../../_components/shortFooter.php'; ?>
</body>
</html>
