<?php
	require_once "../_components/adminCheck.php";
	require_once "../../_components/database.php";
	$db = new Database();

	// POST STUFF WILL GO HERE

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
						<input type="text" id="addressName" name="addressName" placeholder="Company/Customer Name" value="<?= $order != null ? $order['addressName'] : '' ?>">
						<label for="addressLine1">Line 1</label>
						<input type="text" id="addressLine1" name="addressLine1" placeholder="Address Line 1" value="<?= $order != null ? $order['addressLine1'] : '' ?>">
						<label for="addressLine2">Line 2</label>
						<input type="text" id="addressLine2" name="addressLine2" placeholder="Address Line 2" value="<?= $order != null ? $order['addressLine2'] : '' ?>">
						<label for="addressLine3">Line 3</label>
						<input type="text" id="addressLine3" name="addressLine3" placeholder="Address Line 3" value="<?= $order != null ? $order['addressLine3'] : '' ?>">
						<label for="addressCity">City</label>
						<input type="text" id="addressCity" name="addressCity" placeholder="City" value="<?= $order != null ? $order['addressCity'] : '' ?>">
						<label for="addressPostcode">Postcode</label>
						<input type="text" id="addressPostcode" name="addressPostcode" placeholder="Postcode or equivalent" value="<?= $order != null ? $order['addressPostcode'] : '' ?>">
						<label for="addressCountry">Country</label>
						<input type="text" id="addressCountry" name="addressCountry" placeholder="Country" value="<?= $order != null ? $order['addressCountry'] : '' ?>">
					</div>
					<div class="col">
						<h2>PRODUCTS IN ORDER</h2>
						<table id="productsTable">
							<thead>
								<tr>
									<th>Product</th>
									<th>Quantity</th>
									<th>Size</th>
									<th>Price</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<tr style="display: none;">
									<td>
										<input type="text" name="products[id][]" value="" aria-label="Product ID" placeholder="Product ID...">
									</td>
									<td>
                                        <input type="text" name="products[quantity][]" value="" aria-label="Quantity" placeholder="Quantity of Product...">
                                    </td>
									<td>
                                        <input type="text" name="products[size][]" value="" aria-label="Size" placeholder="Size of Product...">
                                    </td>
									<td>£0.00</td>
									<td><i class="fa-solid fa-trash"></i></td>
								</tr>
								<?php
									// If we're updating, we need to get the products in the order.
									if ($order != null) {
										$products = array(); // $db->getProductsInOrder($order['id']);
										foreach ($products as $product) {
											// It's in cases like this I really wish we had an ORM, but I still refuse to use Laravel.
											?>
                                            <tr>
                                                <td>
                                                    <input type="text" name="products[id][]" value="<?= $product['id'] ?>" aria-label="Product ID" placeholder="Product ID...">
                                                </td>
                                                <td>
                                                    <input type="text" name="products[quantity][]" value="<?= $product['quantity'] ?>" aria-label="Quantity" placeholder="Quantity of Product...">
                                                </td>
                                                <td>
                                                    <input type="text" name="products[size][]" value="<?= $product['size'] ?>" aria-label="Size" placeholder="Size of Product...">
                                                </td>
                                                <td>£0.00</td>
                                                <td><i class="fa-solid fa-trash"></i></td>
                                            </tr>
											<?php
										}
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
					</div>
				</div>
			</form>
		</section>
	</main>
	<?php include '../../_components/shortFooter.php' ?>
</body>
</html>
