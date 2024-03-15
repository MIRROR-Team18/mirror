<?php
    require_once("./_components/adminCheck.php");
    require_once("../_components/database.php");
    $db = new Database();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include '../_components/default.php'; ?>
	<title>Admin - MIRЯOR</title>
	<link rel="stylesheet" href="./admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
</head>
<body>
	<?php include './_components/header.php'; ?>
	<main>
		<section class="blue-3">
			<h1>WELCOME BACK</h1>
		</section>
		<section id="attention" class="blue-2">
			<h2>these might need your attention:</h2>
			<div id="items">
                <?php
                    // We want to find the products with the lowest stock.
                    $products = $db->getLowestStock(6);
                    foreach ($products as $product) {
						$id = $product['id'];
                        $image = Database::findPrimaryProductImageUrl($id);
                        $name = $product['name'];
                        $reason = ($product['stock'] < 1) ? "Out of Stock!" : "Low on Stock.";
                        echo <<<HTML
                            <div class="item">
                                <div class="image">
                                    <img class="homeImage" src="$image" alt="$id image"></image>
                                </div>
                                <div class="text">
                                    <h2><a href="./products/upsert.php?id=$id">$name</a></h2>
                                    <p>$reason</p>
                                </div>
                            </div>
                        HTML;
                    }
                ?>
			</div>
		</section>
		<section id="orders" class="blue-1">
			<h2>otherwise, this is how the site is going:</h2>
			<div id="graphRow">
				<div id="graph">
                    <canvas id="report"></canvas>
				</div>
				<div id="stats">
					<p>total purchases: <span id="totalPurchases">0</span></p>
					<p>peak: <span id="peakDate">1st January 1970</span></p>
				</div>
			</div>
		</section>
	</main>
    <script>
		// Some JS has to stay here, because it needs to be executed after the PHP has run.
		<?php
		$mode = $_GET['mode'] ?? "month";
		$history = [];
		try {
			$history = $db->getSiteStockHistory();
		} catch (Exception $e) {
			echo "console.error('".$e->getMessage()."')";
		}
		?>
        // Bringing PHP variables into JS
		const mode = '<?= $mode ?>';
		const overall = <?= json_encode($history) ?>;
		const totalPurchases = overall.reduce((acc, cur) => acc + cur.quantity, 0);
        let mostPurchases = {};

		overall.forEach((item) => {
			if (!mostPurchases.quantity || item.quantity > mostPurchases.quantity) {
                mostPurchases = item;
            }
        });

		// Waiting for page to load before trying to change the DOM
		window.addEventListener('load', () => {
			document.querySelector('#totalPurchases').innerText = totalPurchases;
			document.querySelector('#peakDate').innerText = new Date(mostPurchases.timeCreated).toLocaleDateString('en-GB', { year: 'numeric', month: 'long', day: 'numeric' });
		});
    </script>
    <script src="./_scripts/generateChart.js"></script>
	<?php include '../_components/shortFooter.php'; ?>
</body>
</html>
