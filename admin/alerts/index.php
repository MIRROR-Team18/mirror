<?php
	require_once "../_components/adminCheck.php";
	require_once "../../_components/database.php";
	$db = new Database();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include '../../_components/default.php'; ?>
	<title>Admin - Alerts</title>
	<link rel="stylesheet" href="../../_stylesheets/products.css">
	<link rel="stylesheet" href="../admin.css">
	<script src="../../_scripts/listFiltering.js" defer async></script>
</head>
<body>
	<?php include '../_components/header.php'; ?>
	<section class="blue-3">
		<h1>ALERTS</h1>
	</section>
	<main>
		<aside>
			<div class="asideContent">
				<div class="topAside">
					<div class="searchGroup">
						<label class="sr-only" for="search">SEARCH</label>
						<input type="text" id="search" placeholder="Search by Product...">
					</div>
					<div class="filterGroup" data-for="method">
						<div class="title">
							<h2>METHOD</h2>
							<span>
								<a href="#" id="orderType_only" class="selected sr-only">Only</a>
								<a href="#" onclick="reset()">Deselect</a>
							</span>
						</div>
						<div class="inputLabelGroup">
							<input type="radio" name="method" id="email" value="email">
							<label for="email">Email</label>
						</div>
						<div class="inputLabelGroup">
							<input type="radio" name="method" id="sms" value="sms">
							<label for="sms">SMS</label>
						</div>
						<div class="inputLabelGroup">
							<input type="radio" name="method" id="site" value="site">
							<label for="site">Site</label>
						</div>
					</div>
				</div>
				<button class="fullWidth" onclick="window.location.href='./upsert.php'">
					<i class="fa-solid fa-plus"></i>
					Add Alert
				</button>
			</div>
		</aside>
		<section id="alerts" class="blue-1">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Threshold</th>
                        <th>Method</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $alerts = $db->getAlertsByAuthor($_SESSION['userID']);
                        foreach ($alerts as $alert) {
							$id = $alert['id'];
                            $thresholds = implode(", ",
                                array_map(function ($threshold) {
                                    return $threshold['threshold'];
                                },  $alert['thresholds'])
                            );
                            $method = implode(", ",
                                array_map(function ($threshold) {
                                    $sms = $threshold['bySMS'] ? "SMS" : "";
                                    $email = $threshold['byEmail'] ? "Email" : "";
                                    $site = $threshold['bySite'] ? "Site" : "";
                                    return "(" . implode(", ", array_filter([$sms, $email, $site])) . ")";
                                },  $alert['thresholds'])
                            );
                            echo <<<HTML
                            <tr>
                                <td>{$alert['productID']}</td>
                                <td>{$thresholds}</td>
                                <td>{$method}</td>
                                <td><a href='./upsert.php?id=$id'><i class='fa-solid fa-pencil'></i></a></td>
                            </tr>
                            HTML;
                        }
                    ?>
                </tbody>
            </table>
		</section>
	</main>
	<?php include '../../_components/shortFooter.php'; ?>
</body>
</html>
