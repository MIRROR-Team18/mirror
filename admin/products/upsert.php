<?php
    require_once("../_components/adminCheck.php");
    require_once '../../_components/database.php';
    $db = new Database();

    if (isset($_POST['id'])) {
        // We're updating a product
        // TODO: SANITISE INPUTS!!!
        var_dump($_POST);
        if ($_POST['mode'] == "update") {
			$product = $db->getProduct($_GET['id']);
			if ($product == null) {
				exit("<p style='text-align: center;'>The ID provided in the URL doesn't return a product.</p>");
			}
            if ($_GET['id'] != $_POST['id']) {
                // Check if the ID exists already
                $existingProduct = $db->getProduct($_POST['id']);
                if ($existingProduct != null) {
                    exit("<p style='text-align: center;'>The ID provided already exists.</p>");
                }
                $db->changeProductID($_GET['id'], $_POST['id']);
				$product->productID = $_POST['id'];
            }
			$product->name = $_POST['name'];
			$product->description = $_POST['description'];
            $product->isSustainable = isset($_POST['sustainable']) && $_POST['sustainable'] == "on";
            $product->gender = $_POST['gender'];
            $product->type = $_POST['type'];

            // Update the sizes
            /** @var Size[] $productSizes */
            $productSizes = array();
            $sizesInRequest = array_filter($_POST, function($key) { return str_starts_with($key, 'size_'); }, ARRAY_FILTER_USE_KEY);
            foreach ($sizesInRequest as $sizeEntry => $value) {
                $sizeID = explode('_', $sizeEntry)[1];
                $price = $_POST['price_' . $sizeID];
                // I don't like this usage of the Size class.
                $productSizes[$sizeID] = new Size($sizeID, "", 0, str_replace('£', '', $price));
            }
            $product->sizes = $productSizes;

            echo "<br>";
            var_dump($product);

            try {
                $success = $db->updateProduct($product);
                if ($success) header("Location: ./");
                else exit("<p style='text-align: center;'>An unspecified error occurred while updating the product. Please try again.</p>");
            } catch (Exception $e) {
                echo $e->getMessage();
                exit("<p style='text-align: center;'>An error occurred while updating the product. Please try again.</p>");
            }

		} else if ($_POST['mode'] == "insert") {

		} else {
            exit("<p style='text-align: center;'>The mode provided in the form is invalid.</p>");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include '../../_components/default.php'; ?>
	<title>Upsert Product - MIRЯOR</title>
	<link rel="stylesheet" href="../admin.css">
    <script src="../_scripts/upsert.js" defer async></script>
</head>
<body>
	<?php include '../_components/header.php'; ?>
	<main>
		<section class="blue-3">
			<h1>PRODUCT INFORMATION</h1>
		</section>
		<?php
            // Are we modifying a product?
            $product = null;
            if (isset($_GET['id'])) {
                $product = $db->getProduct($_GET['id']);
                if ($product == null) {
					exit("<p style='text-align: center;'>The ID provided in the URL doesn't return a product. Halting to prevent accidental damage.</p>");
				}
            }
		?>
		<section class="blue-1">
			<form id="upsertForm" action="./upsert.php<?= isset($product->productID) ? '?id=' . $product->productID : '' ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="mode" value="<?= isset($product->productID) ? "update" : "insert" ?>">
				<div class="row">
					<div class="col">
						<label for="id">ID</label>
						<input type="text" id="id" name="id" placeholder="A shortened name usually works well" value="<?= $product->productID ?? '' ?>" required>
                        <p>
                            <?= isset($product->productID)
                                ? "This shouldn't be changed unless there's a good reason to."
                                : "Choose wisely, as this will be used in the URL. It can be changed later but it shouldn't."
                            ?>
                        </p>
					</div>
					<div class="col">
						<label for="name">Name</label>
						<input type="text" id="name" name="name" placeholder="What did the supplier tell you it was called?" value="<?= $product->name ?? '' ?>" required>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<label for="description">Description</label>
						<textarea id="description" rows="4" name="description" placeholder="What's so special about this product?"><?= $product->description ?? '' ?></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<h2>GENDER</h2>
						<?php
							$productGenders = $db->getGenders();
							foreach ($productGenders as $gender) {
								$genderName = $gender['name'];
								$productIsThisGender = isset($product->gender) && $gender['id'] == $product->gender ? 'checked' : '';
								echo <<<HTML
								<div class="row">
									<input type="radio" name="gender" id="$genderName" value="$genderName" $productIsThisGender >
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
								$productIsThisType = isset($product->type) && $type['id'] == $product->type ? 'checked' : '';
								echo <<<HTML
								<div class="row">
									<input type="radio" name="type" id="$typeName" value="$typeName" $productIsThisType >
									<label for="$typeName">$typeName</label>
								</div>
								HTML;
							}
						?>
					</div>
					<div class="col" style="flex:2;">
						<h2>SIZE</h2>
                        <div class="row">
							<?php
                                $allSizes = $db->getSizes(); // Get all sizes possible from the database
                                $currencyFormatter = new NumberFormatter('en_GB', NumberFormatter::CURRENCY); // Create a currency formatter for GBP
                            ?>
                            <div class="col">
                                <h3>Adult</h3>
                                <?php
                                    $adultSizes = array_filter($allSizes, function($size) { return !$size->isKids; });
                                    // Note that we're using Size classes here.
                                    foreach ($adultSizes as $size) {
                                        $sizeName = $size->name;
                                        $sizeID = $size->sizeID;
                                        // Size[] uses keys of sizeID, so we can check if the product has this size by checking if the key exists.
                                        $productHasThisSize = isset($product->sizes[$size->sizeID]) ? 'checked' : '';
                                        $priceOfSize = $productHasThisSize
                                            ? $currencyFormatter->formatCurrency($product->sizes[$size->sizeID]->price, "GBP")
                                            : '';
                                        echo <<<HTML
                                        <div class="row">
                                            <input type="checkbox" class="priceBox" name="size_$sizeID" id="$sizeName" $productHasThisSize >
                                            <label for="$sizeName">$sizeName</label>
                                            <label for="price_$sizeName"></label>
                                            <input class="priceInput" name="price_$sizeID" id="price_$sizeName" type="text" value="$priceOfSize">
                                        </div>
                                        HTML;
                                    }
                                ?>
                            </div>
                            <div class="col">
                                <h3>Child</h3>
                                <?php
                                    $childSizes = array_filter($allSizes, function($size) { return $size->isKids; });
                                    // Note that we're using Size classes here.
                                    foreach ($childSizes as $size) {
                                        $sizeName = $size->name;
                                        $sizeID = $size->sizeID;
                                        // Size[] uses keys of sizeID, so we can check if the product has this size by checking if the key exists.
										$productHasThisSize = isset($product->sizes[$size->sizeID]) ? 'checked' : '';
										$priceOfSize = $productHasThisSize
                                            ? $currencyFormatter->formatCurrency($product->sizes[$size->sizeID]->price, "GBP")
                                            : '';
                                        echo <<<HTML
                                        <div class="row">
                                            <input type="checkbox" class="priceBox" name="size_$sizeID" id="$sizeName" $productHasThisSize >
                                            <label for="$sizeName">$sizeName</label>
                                            <label for="price_$sizeName" class="sr-only">Price for $sizeName</label>
                                            <input class="priceInput" name="price_$sizeID" id="price_$sizeName" type="text" value="$priceOfSize">
                                        </div>
                                        HTML;
                                    }
                                ?>
                            </div>
                        </div>
					</div>
                    <div class="col">
                        <h2>FINAL BITS</h2>
                        <div class="row">
                            <input type="checkbox" name="sustainable" id="sustainable" <?= isset($product->isSustainable) && $product->isSustainable ? 'checked' : '' ?>>
                            <label for="sustainable">Sustainable?<i class="fa-solid fa-leaf"></i></label>
                        </div>
                        <div class="buttonGrid">
                            <button class="fullWidth">
                                <i class="fa-solid fa-chart-line"></i> Stock
                            </button>
                            <button class="fullWidth">
                                <i class="fa-solid fa-bell"></i> Alerts
                            </button>
                            <button class="fullWidth">
                                <i class="fa-solid fa-save"></i> Save
                            </button>
                            <button class="fullWidth">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
				</div>
			</form>
		</section>
	</main>
	<?php include '../../_components/shortFooter.php'; ?>
</body>
</html>