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
        // As much as I'd like to separate this into another file, I'd have to create API endpoints again and I kinda don't wanna
        // Overwriting defaults, so they don't have to be re-specified.
        Chart.defaults.borderColor = 'rgba(150, 150, 150, 0.5)';
		Chart.defaults.color = '#eee';
		Chart.defaults.font = {
			size: 16,
            family: "'Zen Kaku Gothic New', sans-serif"
        }

        // Generating the table.
        const report = new Chart(document.getElementById('report').getContext('2d'), {
			type: 'line',
			data: {
				labels: [],
				datasets: [{
					label: 'Incoming Stock',
					borderColor: 'rgba(0, 255, 0, 1)',
					borderDash: [10, 5],
				}, {
					label: 'Outgoing Orders',
					borderColor: 'rgba(255, 0, 0, 1)',
                    borderDash: [10, 5],
                }, {
					label: 'Stock Remaining',
					backgroundColor: 'rgba(0, 0, 255, 0.5)',
					borderColor: 'rgba(0, 0, 255, 1)',
                    fill: true,
                }],
			},
			options: {
				responsive: true,
				scales: {
					y: {
						beginAtZero: true,
                        suggestedMax: 50,
					}
				},
			}
		});

		<?php
            $mode = "month";
            $history = [];
            try {
				$history = $db->getProductStockHistory($_GET['id'], 3, $mode);
			} catch (Exception $e) {
                echo "console.error('".$e->getMessage()."')";
            }
        ?>
        const mode = '<?= $mode ?>';
        const overall = <?= json_encode($history) ?>;

		const overallByDate = {};
		overall.forEach((entry) => {
			let date = entry.timeCreated.split(" ")[0];
			if (mode !== "month") {
				date = date.split("-").slice(0, 2).join("-");
				console.log(date);
            }
			if (!overallByDate[date]) {
				overallByDate[date] = {
                    incoming: 0,
                    outgoing: 0
                };
			}

			switch (entry.direction) {
				case 'in':
					overallByDate[date].incoming += entry.quantity;
					break;

                case 'out':
					overallByDate[date].outgoing += entry.quantity;
					break;

                default:
					console.error(`Invalid direction in entry at ${entry.timeCreated}!`);
					break;
            }
        })

		const dates = [];
		switch (mode) {
			case 'month':
				for (let i = 0; i < 31; i++) {
					const newDate = new Date();
					newDate.setDate(newDate.getDate() - i);
					dates.push(newDate.toISOString().split('T')[0]);
				}
				break;

            case 'year':
				for (let i = 0; i < 12; i++) {
					const newDate = new Date();
                    newDate.setMonth(newDate.getMonth() - i);
					const monthYear = newDate.toISOString().split('T')[0].split('-').slice(0, 2).join('-');
                    dates.push(monthYear);

				}
				break;

            case 'all':
				// First, get the date furthest back
                const furthestBack = new Date(overall[overall.length - 1].timeCreated);
				console.log(furthestBack);

				// How many months to furthestBack?
                let months = (new Date().getFullYear() - furthestBack.getFullYear()) * 12
                    + new Date().getMonth() - furthestBack.getMonth();
				if (months < 1) months = 1; // At least one month, else the graph will be empty

				for (let i = 0; i < months; i++) {
					const newDate = new Date();
                    newDate.setMonth(newDate.getMonth() - i);
                    const monthYear = newDate.toISOString().split('T')[0].split('-').slice(0, 2).join('-');
                    dates.push(monthYear);
				}
				break;

            default:
				console.error("Invalid mode!");
        }
		dates.reverse(); // Mutates array
		report.data.labels = dates;

		let started = false;
		const graphData = {
			in: [],
            out: [],
            stock: [],
        }
        dates.forEach((date) => {
			const yesterdayStock = graphData.stock[graphData.stock.length - 1] || 0;
            if (overallByDate[date]) {
                started = true;
                graphData.in.push(overallByDate[date].incoming);
                graphData.out.push(overallByDate[date].outgoing);
                graphData.stock.push(yesterdayStock + overallByDate[date].incoming - overallByDate[date].outgoing);
            } else if (started) {
                graphData.in.push(0);
                graphData.out.push(0);
                graphData.stock.push(yesterdayStock);
            } else {
                graphData.in.push(null);
                graphData.out.push(null);
                graphData.stock.push(null);
            }
        })
        report.data.datasets[0].data = graphData.in;
        report.data.datasets[1].data = graphData.out;
        report.data.datasets[2].data = graphData.stock;

		report.update();

    </script>
    <div class="noPrint">
		<?php include '../../_components/shortFooter.php'; ?>
    </div>
</body>
</html>