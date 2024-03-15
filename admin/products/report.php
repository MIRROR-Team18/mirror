<?php
	require_once "../_components/adminCheck.php";
	require_once "../../_components/database.php";
	$db = new Database();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include '../../_components/default.php'; ?>
	<title>Product Report - MIRЯOR</title>
	<link rel="stylesheet" href="../admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
</head>
<body>
	<?php include "../_components/header.php"; ?>
	<main>
		<section class="blue-3">
			<h1>PRODUCT REPORT</h1>
		</section>
		<section class="blue-1">
            <form action="" method="GET" class="noPrint">
                <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
                <label for="mode">Mode:</label>
                <select name="mode" id="mode" onchange="this.form.submit();">
                    <!-- The purpose of this first element is to allow users to switch to month. Else, clicking on it does nothing. Doing it this way largely hides this problem. -->
                    <option value="" hidden disabled selected><?= ucfirst($_GET['mode'] ?? ""); ?></option>
                    <option value="month">Month</option>
                    <option value="year">Year</option>
                    <option value="all">All</option>
                </select>
            </form>
			<canvas id="report"></canvas>
            <div class="buttonGrid noPrint">
                <button class="fullWidth" onclick="window.location.href = window.location.href.replace('report', 'upsert')">
                    <i class="fa-solid fa-arrow-left"></i>
                    Return
                </button>
                <button class="fullWidth" onclick="window.print()">
                    <i class="fa-solid fa-print"></i>
                    Print
                </button>
            </div>
		</section>
	</main>
    <script>
        // Some JS has to stay here, because it needs to be executed after the PHP has run.
		<?php
            $mode = $_GET['mode'] ?? "month";
            $history = [];
            try {
				$history = $db->getProductStockHistory($_GET['id'], $mode);
			} catch (Exception $e) {
                echo "console.error('".$e->getMessage()."')";
            }
        ?>
        const mode = '<?= $mode ?>';
        const overall = <?= json_encode($history) ?>;
    </script>
    <script src="../_scripts/generateChart.js"></script>
    <div class="noPrint">
		<?php include '../../_components/shortFooter.php'; ?>
    </div>
</body>
</html>