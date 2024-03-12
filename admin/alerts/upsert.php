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
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Check fields are set
        if (!isset($_POST['mode']) || $_POST['mode'] == ""
            || !isset($_POST['product']) || $_POST['product'] == ""
            || ($_POST['mode'] == "update" && !isset($_GET['id'])) ) {
			exit(generateExitStr("Not all fields are set."));
        }

        $thresholdsSubmitted = $_POST['thresholds'];
        $thresholdList = [];
        // Start from 1 as 0th will be the empty row.
        for ($i = 1; $i < count($thresholdsSubmitted['value']); $i++) {
            $value = $thresholdsSubmitted['value'][$i] ?? "";
			$email = $thresholdsSubmitted['email'][$i] ?? "";
			$sms = $thresholdsSubmitted['sms'][$i] ?? "";
			$site = $thresholdsSubmitted['site'][$i] ?? "";

            if ($value == "") {
                exit(generateExitStr("A threshold is missing a value."));
            }

            if ($email == "" && $sms == "" && $site == "") {
                exit(generateExitStr("The threshold for " . $thresholdsSubmitted['value'][$i] . " items is missing a method."));
            }

            $thresholdList[] = [
                "value" => $value,
                "email" => $email != "", // Convert to boolean
                "sms" => $sms != "",
                "site" => $site != ""
            ];
        }

        if (count($thresholdList) == 0) {
            exit(generateExitStr("No thresholds were submitted."));
        }

        $result = false;
        if ($_POST['mode'] == "insert") {
            $result = $db->createAlert($_SESSION['userID'], $_POST['product'], $thresholdList);
        } else if ($_POST['mode'] == "update") {
            $result = $db->updateAlert($_GET['id'], $_SESSION['userID'], $_POST['product'], $thresholdList);
        } else {
            exit(generateExitStr("Mode provided is invalid!"));
        }

        if (!$result) exit(generateExitStr("Failed to upsert!"));
        header("Location: ./");
        exit();
    }

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
	<script src="../_scripts/alertUpsert.js" defer async></script>
</head>
<body>
	<?php include "../_components/header.php"; ?>
	<main>
		<section class="blue-3">
			<h1>ALERT INFORMATION</h1>
		</section>
        <section class="blue-1">
            <form id="upsertForm"
				action="upsert.php<?= isset($alert['id']) ? '?id=' . $alert['id'] : '' ?>"
				method="post"
				onsubmit="return confirm('Are you sure you want to save?');"
            >
                <input type="hidden" name="mode" value="<?= isset($alert['id']) ? "update" : "insert" ?>">
                <div class="row">
                    <div class="col">
                        <label for="product">Product</label>
						<select name="product" id="product">
							<option value="" selected disabled hidden>Select a product...</option>
							<?php
								$products = $db->getAllProducts();
								foreach ($products as $product) {
									echo "<option value='$product->productID'" . (isset($alert['productID']) && $alert['productID'] == $product->productID ? " selected" : "") . ">$product->name</option>";
								}
							?>
						</select>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h2>THRESHOLDS</h2>
                        <table id="alertsTable">
                            <thead>
                            	<tr>
                            	    <th></th>
                            	    <th colspan="3">Methods</th>
                                    <th></th>
                            	</tr>
                            	<tr>
                            	    <th>Threshold</th>
                            	    <th>Email</th>
                            	    <th>SMS</th>
                            	    <th>Site</th>
                                    <th>Delete</th>
                            	</tr>
                            </thead>
                            <tbody>
                            	<?php
								    // This sucked last time, but I'm going to do it again
                                    // However, there is an issue: Checkboxes. My custom implementation of them means IDs and names MUST be unique.
                                    // Hence, the introduction of i.
									$thresholds = $alert['thresholds'] ?? [];
								    $i = 0;

                                    $hide = true;
                                    include "_components/tableRow.php";
                                    $hide = false;
                                    $i++;

                                    foreach ($thresholds as $threshold) {
                                        include "_components/tableRow.php";
                                        $i++;
                                    }
                            	?>
                                <td colspan="5">
                                    <button type="button" class="fullWidth" onclick="createRow()">
                                        <i class="fa-solid fa-plus"></i>
                                        Add Row
                                    </button>
                                </td>
                                <script>
                                    let i = <?= $i ?>;
                                </script>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="buttonGrid">
                        <button class="fullWidth">
                            <i class="fa-solid fa-save"></i>
                            Save
                        </button>
                        <button class="fullWidth" type="reset">
                            <i class="fa-solid fa-undo"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </form>
        </section>
	</main>
</body>
</html>