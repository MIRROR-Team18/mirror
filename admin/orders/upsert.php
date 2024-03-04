<?php
	require_once "../_components/adminCheck.php";
	require_once "../../_components/database.php";
	$db = new Database();

	// POST STUFF WILL GO HERE
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check fields are set
        if (!isset($_POST['mode']) || $_POST['mode'] == ""
            || !isset($_POST['status']) || $_POST['status'] == ""
            || !isset($_POST['direction']) || $_POST['direction'] == ""
            || !isset($_POST['addressName']) || $_POST['addressName'] == ""
            || !isset($_POST['addressLine1']) || $_POST['addressLine1'] == ""
            || !isset($_POST['addressCity']) || $_POST['addressCity'] == ""
            || !isset($_POST['addressPostcode']) || $_POST['addressPostcode'] == ""
            || !isset($_POST['addressCountry']) || $_POST['addressCountry'] == "") {
            exit(generateExitStr("Not all fields are set."));
        }

        if ($_POST['mode'] == "update") {
        	// Check if the order exists
        	$order = $db->getOrderByID($_POST['id']);
        	if ($order == null) {
        		exit(generateExitStr("Order ID provided doesn't exist."));
        	}
        }

        $productsSubmitted = $_POST['products'];
        // Quantities have to be stored separately as they're not part of the product object, and this is how we handled the basket last term.
        $products = [];
        $quantityMap = [];
        for ($i = 0; $i < count($productsSubmitted['id']); $i++) {
            $id = $productsSubmitted['id'][$i];

            if (!$productsSubmitted['size'][$i] || !$productsSubmitted['quantity'][$i]) {
                exit(generateExitStr("Not all fields are set for product ID $id."));
            }

            $size = $productsSubmitted['size'][$i];
            $quantity = $productsSubmitted['quantity'][$i];

            // Validate product exists
            $product = $db->getProduct($id);
            if ($product == null) {
                exit(generateExitStr("Product ID $id doesn't exist."));
            }

            // Check product size is valid
            $sizeExists = null;
            foreach ($product->sizes as $thisSize) {
                if ($thisSize->sizeID == $size) {
                    $sizeExists = $thisSize;
                    break;
                }
            }
            if (is_null($sizeExists)) {
                exit(generateExitStr("Size ID $size doesn't exist for product ID $id."));
            }

            $sizes = array();
            $sizes[] = $sizeExists;

            $products[] = new Product($id, $product->name, $product->type, $product->gender, $product->description, $product->isSustainable, $sizes);
            $quantityMap[$id] = $quantity;
        }

		$addressID = $db->createOrGetAddress($_POST['addressName'], $_POST['addressLine1'], $_POST['addressLine2'], $_POST['addressLine3'], $_POST['addressCity'], $_POST['addressPostcode'], $_POST['addressCountry']);

        // updating or inserting
        if ($_POST['mode'] == "update") {
            // Update the order
            $result = $db->updateOrder($_POST['id'], $products, $quantityMap, $addressID, $_POST['direction'], $_POST['status']);
            if (!$result) exit(generateExitStr("Failed to update order."));
            else header("Location: ./");
        } else {
            // Insert the order. The userID will be ours.
            $result = $db->createOrder($_SESSION['userID'], $products, $quantityMap, $addressID, $_POST['direction'], $_POST['status']);
            if (!$result) exit(generateExitStr("Failed to insert order."));
            else header("Location: ./");
        }

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
			$order = null; $address = null;
			if (isset($_GET['id'])) {
				$order = $db->getOrderByID($_GET['id']);
				if ($order == null) {
					exit(generateExitStr("ID provided in the URL doesn't return an order. Halting to prevent accidental damage."));
				}
                $address = $db->getAddressDetails($order['addressID']);
			}
		?>
		<section class="blue-1">
			<form id="upsert"
				  action="./upsert.php<?= !is_null($order) ? '?id=' . $order['id'] : '' ?>"
				  method="post" enctype="multipart/form-data"
				  onsubmit="return confirm('Are you sure you want to save?');"
			>
				<input type="hidden" name="mode" value="<?= !is_null($order) ? "update" : "insert" ?>">
				<div class="row">
					<div class="col">
						<label for="id">ID</label>
						<input type="text" id="id" name="id" value="<?= !is_null($order) ? $order['id'] : 'Created on insert...' ?>" readonly>
						<p>This cannot be modified.</p>
					</div>
					<div class="col">
						<label for="status">Status</label>
						<select name="status" id="status">
							<option value="processing" <?= isset($order['status']) && $order['status'] == 'processing' ? "selected" : "" ?>>Processing</option>
							<option value="dispatched" <?= isset($order['status']) && $order['status'] == 'dispatched' ? "selected" : "" ?>>Dispatched</option>
						</select>
					</div>
					<div class="col">
						<label for="direction">Direction</label>
						<select name="direction" id="direction">
							<option value="in" <?= isset($order['direction']) && $order['direction'] == 'in' ? "selected" : "" ?>>In</option>
							<option value="out" <?= isset($order['direction']) && $order['direction'] == 'out' ? "selected" : "" ?>>Out</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<h2>ADDRESS</h2>
						<p>Whether this is the "from" or "to" address depends on direction above.</p>
						<label for="addressName">Name</label>
						<input type="text" id="addressName" name="addressName" placeholder="Company/Customer Name" value="<?= !is_null($order) ? $address['name'] : '' ?>" required >
						<label for="addressLine1">Line 1</label>
						<input type="text" id="addressLine1" name="addressLine1" placeholder="Address Line 1" value="<?= !is_null($order) ? $address['line1'] : '' ?>" required >
						<label for="addressLine2">Line 2</label>
						<input type="text" id="addressLine2" name="addressLine2" placeholder="Address Line 2" value="<?= !is_null($order) ? $address['line2'] : '' ?>">
						<label for="addressLine3">Line 3</label>
						<input type="text" id="addressLine3" name="addressLine3" placeholder="Address Line 3" value="<?= !is_null($order) ? $address['line3'] : '' ?>">
						<label for="addressCity">City</label>
						<input type="text" id="addressCity" name="addressCity" placeholder="City" value="<?= !is_null($order) ? $address['city'] : '' ?>" required >
						<label for="addressPostcode">Postcode</label>
						<input type="text" id="addressPostcode" name="addressPostcode" placeholder="Postcode or equivalent" value="<?= !is_null($order) ? $address['postcode'] : '' ?>" required >
						<label for="addressCountry">Country</label>
						<input type="text" id="addressCountry" name="addressCountry" placeholder="Country" value="<?= !is_null($order) ? $address['country'] : '' ?>" required >
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
									if (!is_null($order)) {
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
                                <button class="fullWidth" type="button" onclick="deleteOrder()">
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
