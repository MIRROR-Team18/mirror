<?php require_once("../_components/adminCheck.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include '../../_components/default.php'; ?>
	<title>Upsert Product - MIRЯOR</title>
	<link rel="stylesheet" href="../admin.css">
</head>
<body>
	<?php include '../_components/header.php'; ?>
	<main>
		<section class="blue-3">
			<h1>PRODUCT INFORMATION</h1>
		</section>
		<?php
			require_once '../../_components/database.php';
			$db = new Database();
		?>
		<section class="blue-1">
			<form id="upsertForm" action="./upsert.php" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col">
						<label for="id">ID</label>
						<input type="text" id="id" name="id" placeholder="A shortened name usually works well" required>
						<p>This shouldn't be changed unless there's a good reason to.</p>
					</div>
					<div class="col">
						<label for="name">Name</label>
						<input type="text" id="name" name="name" placeholder="What did the supplier tell you it was called?" required>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<label for="description">Description</label>
						<textarea id="description" rows="4" name="description" placeholder="What's so special about this product?"></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<h2>GENDER</h2>
						<?php
							$productGenders = $db->getGenders();
							foreach ($productGenders as $gender) {
								$genderName = $gender['name'];
								// Check if selected!
								echo <<<HTML
								<div class="row">
									<input type="radio" name="gender" id="$genderName" value="$genderName">
									<label for="$genderName">$genderName</label>
								</div>
								HTML;
							}
						?>
					</div>
					<div class="col">
						<h2>TYPE</h2>
						<?php
							$productTypes = $db->getTypes();
							foreach ($productTypes as $type) {
								$typeName = $type['name'];
								// Check if selected!
								echo <<<HTML
								<div class="row">
									<input type="radio" name="type" id="$typeName" value="$typeName">
									<label for="$typeName">$typeName</label>
								</div>
								HTML;
							}
						?>
					</div>
					<div class="col" style="flex:2;">
						<h2>SIZE</h2>

					</div>
				</div>
			</form>
		</section>
	</main>
	<?php include '../../_components/shortFooter.php'; ?>
</body>
</html>