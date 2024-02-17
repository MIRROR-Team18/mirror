<?php require_once("./_components/adminCheck.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include '../_components/default.php'; ?>
	<title>Admin - MIRЯOR</title>
	<link rel="stylesheet" href="./admin.css">
</head>
<body>
	<?php include './_components/header.php'; ?>
	<main>
		<section>
			<h1>WELCOME BACK</h1>
		</section>
		<section id="attention">
			<h2>these might need your attention:</h2>
			<div id="items">
				<!-- Code needed to load low stock items -->
			</div>
		</section>
		<section id="orders">
			<h2>otherwise, this is how the site is going:</h2>
			<div id="graphRow">
				<div id="graph">
					<!-- Chart.js graph goes here -->
				</div>
				<div id="stats">
					<p>total purchases: <span id="totalPurchases">0</span></p>
					<p>peak: <span id="peakDate">1st January 1970</span></p>
				</div>
			</div>
		</section>
	</main>
	<?php include '../_components/shortFooter.php'; ?>
</body>
</html>
