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
			<canvas id="report"></canvas>
		</section>
	</main>
    <script>
        // As much as I'd like to separate this into another file, I'd have to create API endpoints again and I kinda don't wanna
        const report = new Chart(document.getElementById('report').getContext('2d'), {
			type: 'line',
			data: {
				labels: [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
				datasets: [{
					label: 'Incoming Stock',
					data: [ 0, 10, 3, 0, 3, 6, 7, 8, 9, 10, 11, 12 ],
					backgroundColor: 'rgba(0, 255, 0, 0.5)',
					borderColor: 'rgba(0, 255, 0, 1)',
					borderWidth: 1
				}, {
					label: 'Outgoing Orders',
					data: [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 2, 3 ],
					backgroundColor: 'rgba(255, 0, 0, 0.5)',
					borderColor: 'rgba(255, 0, 0, 1)',
					borderWidth: 1
                }, {
					label: 'Stock Remaining',
					data: [ 0, 5, 10, 13, 13, 16, 22, 29, 37, 46, 55, 64, 73 ],
					backgroundColor: 'rgba(0, 0, 255, 0.5)',
					borderColor: 'rgba(0, 0, 255, 1)',
                    borderWidth: 1
                }]
			},
			options: {
				scales: {
					y: {
						beginAtZero: true
					}
				}
			}
		});
    </script>
	<?php include '../../_components/shortFooter.php'; ?>
</body>
</html>