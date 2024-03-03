<?php
	require_once "../_components/adminCheck.php";
	require_once "../../_components/database.php";
	$db = new Database();

	// POST STUFF WILL GO HERE
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        var_dump($_POST);
        exit();
	}

	/**
	 * It looked messy doing it repeatedly, so I made a function to generate the exit string.
	 * @param string $message The message to display
	 * @return string The HTML string to display
	 */
	function generateExitStr(string $message): string {
		return "<p style='text-align: center;'>$message</p>";
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include '../../_components/default.php' ?>
	<title>Upsert Order - MIRЯOR</title>
	<link rel="stylesheet" href="../admin.css">
	<script src="../_scripts/orderUpsert.js"></script>
</head>
<body>
	<?php include '../_components/header.php' ?>
	<main>
		<section class="blue-3">
			<h1>ORDER INFORMATION</h1>
		</section>
		<?php
			// As with products/upsert.php, are we modifying an order here?
			$order = null;
			if (isset($_GET['id'])) {
				$order = $db->getOrderByID($_GET['id']);
				if ($order == null) {
					exit(generateExitStr("ID provided in the URL doesn't return an order. Halting to prevent accidental damage."));
				}
			}
		?>
		<section class="blue-1">
			<form id="upsert"
				  action="./upsert.php<?= $order != null ? '?id=' . $order['id'] : '' ?>"
				  method="post" enctype="multipart/form-data"
				  onsubmit="return confirm('Are you sure you want to save?');"
			>
				<input type="hidden" name="mode" value="<?= $order != null ? "update" : "insert" ?>">
				<div class="row">
					<div class="col">
						<label for="id">ID</label>
						<input type="text" id="id" name="id" value="<?= $order != null ? $order['id'] : 'Created on insert...' ?>" readonly>
						<p>This cannot be modified.</p>
					</div>
					<div class="col">
						<label for="status">Status</label>
						<select name="status" id="status">
							<!-- php needed to select which. -->
							<option value="processing">Processing</option>
							<option value="dispatched">Dispatched</option>
						</select>
					</div>
					<div class="col">
						<label for="direction">Direction</label>
						<select name="direction" id="direction">
							<!-- php needed to select which. -->
							<option value="in">In</option>
							<option value="out">Out</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<h2>ADDRESS</h2>
						<p>Whether this is the "from" or "to" address depends on direction above.</p>
						<label for="addressName">Name</label>
						<input type="text" id="addressName" name="addressName" placeholder="Company/Customer Name" value="<?= $order != null ? $order['addressName'] : '' ?>" required >
						<label for="addressLine1">Line 1</label>
						<input type="text" id="addressLine1" name="addressLine1" placeholder="Address Line 1" value="<?= $order != null ? $order['addressLine1'] : '' ?>" required >
						<label for="addressLine2">Line 2</label>
						<input type="text" id="addressLine2" name="addressLine2" placeholder="Address Line 2" value="<?= $order != null ? $order['addressLine2'] : '' ?>">
						<label for="addressLine3">Line 3</label>
						<input type="text" id="addressLine3" name="addressLine3" placeholder="Address Line 3" value="<?= $order != null ? $order['addressLine3'] : '' ?>">
						<label for="addressCity">City</label>
						<input type="text" id="addressCity" name="addressCity" placeholder="City" value="<?= $order != null ? $order['addressCity'] : '' ?>" required >
						<label for="addressPostcode">Postcode</label>
						<input type="text" id="addressPostcode" name="addressPostcode" placeholder="Postcode or equivalent" value="<?= $order != null ? $order['addressPostcode'] : '' ?>" required >
						<label for="addressCountry">Country</label>
						<input type="text" id="addressCountry" name="addressCountry" placeholder="Country" value="<?= $order != null ? $order['addressCountry'] : '' ?>" required >
					</div>
					<div class="col">
						<h2>PRODUCTS IN ORDER</h2>
						<table id="productsTable" class="fixed">
							<thead>
								<tr>
									<th>Product</th>
									<th>Size</th>
                                    <th>Quantity</th>
									<th>Price</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
									// If we're updating, we need to get the products in the order.
                                    $allProducts = $db->getAllProducts();
									if ($order != null) {
										$productsInOrder = $db->getProductsInOrder($order['id']);

                                        // I'm screaming I really wrote this.
                                        $hide = true;
                                        include './_components/tableRow.php';
                                        $hide = false;

                                        foreach ($productsInOrder as $thisProduct) {
                                            include './_components/tableRow.php';
                                        }
									} else {
                                        // If we're inserting, we need to have at least one row else duplicating can't occur.
                                        $hide = true;
                                        include './_components/tableRow.php';
                                    }
								?>
								<tr>
									<td colspan="5">
										<button type="button" class="fullWidth" onclick="createRow()">
											<i class="fa-solid fa-plus"></i>
											Add Row
										</button>
									</td>
								</tr>
							</tbody>
						</table>
                        <div class="buttonGrid">
							<?php
							if (isset($order)): ?>
                                <button class="fullWidth" type="button">
                                    <i class="fa-solid fa-chart-line"></i> Stock
                                </button>
                                <button class="fullWidth" type="button">
                                    <i class="fa-solid fa-bell"></i> Alerts
                                </button>
                                <button class="fullWidth" type="button" onclick="deleteProduct()">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
							<?php else: ?>
                                <button class="fullWidth" type="button" onclick="window.location.href = './'">
                                    <i class="fa-solid fa-trash"></i> Cancel
                                </button>
							<?php endif ?>
                            <button class="fullWidth">
                                <i class="fa-solid fa-save"></i> Save
                            </button>
                        </div>
					</div>
				</div>
			</form>
		</section>
	</main>
	<?php include '../../_components/shortFooter.php' ?>
</body>
</html>
