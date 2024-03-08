<?php
	require_once "../_components/adminCheck.php";
	require_once "../../_components/database.php";
	$db = new Database();

	/**
	 * It looked messy doing it repeatedly, so I made a function to generate the exit string.
	 * @param string $message The message to display
	 * @return string The HTML string to display
	 */
	function generateExitStr(string $message): string {
		return "<p style='text-align: center;'>$message</p>";
	}

	// POST things will go here.

	// Then GET
	// If there is an ID, then it's an update
	$alert = null;
	if (isset($_GET['id'])) {
		$alert = $db->getAlert($_GET['id']);
        if ($alert == null) {
			exit(generateExitStr("ID provided in the URL doesn't return an alert. Halting to prevent accidental damage."));
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include '../../_components/default.php'; ?>
	<title>Upsert Alert - MIRЯOR</title>
	<link rel="stylesheet" href="../admin.css">
	<script src="../_scripts/orderUpsert.js" defer async></script>
</head>
<body>
	<?php include "../_components/header.php"; ?>
	<main>
		<section class="blue-3">
			<h1>ALERT INFORMATION</h1>
		</section>
        <section class="blue-1">
            <form id="upsertForm"
				action="upsert.php<?= isset($alert['id']) ? "?id=" . $alert['id'] : "" ?>"
				method="post"
				onsubmit="return confirm('Are you sure you want to save?');"
            >
                <input type="hidden" name="mode" value="<?= isset($alert['id']) ? "update" : "insert" ?>">
                <div class="row">
                    <div class="col">
                        <label for="product">Product</label>
						<select name="product" id="product">
							<option value="">Select a product...</option>
							<?php
								$products = $db->getAllProducts();
								foreach ($products as $product) {
									echo "<option value='{$product->productID}'" . (isset($alert['productID']) && $alert['productID'] == $product->productID ? " selected" : "") . ">{$product->name}</option>";
								}
							?>
						</select>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h2>THRESHOLDS</h2>
                        <table>
                            <thead>
                            	<tr>
                            	    <th></th>
                            	    <th colspan="3">Methods</th>
                            	</tr>
                            	<tr>
                            	    <th>Threshold</th>
                            	    <th>Email</th>
                            	    <th>SMS</th>
                            	    <th>Site</th>
                            	</tr>
                            </thead>
                            <tbody>
                            	<?php
									$thresholds = $alert['thresholds'] ?? [];

                            	?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </section>
	</main>
</body>
</html>