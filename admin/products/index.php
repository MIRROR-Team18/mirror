<?php require_once("../_components/adminCheck.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include '../../_components/default.php'; ?>
	<title>Products Admin - MIRЯOR</title>
    <link rel="stylesheet" href="../../_stylesheets/products.css">
	<link rel="stylesheet" href="../admin.css">
    <script src="../../_scripts/productsListing.js" defer async></script>
</head>
<body>
	<?php include '../_components/header.php'; ?>
    <section class="blue-3">
        <h1>PRODUCTS</h1>
    </section>
	<main>
        <aside>
            <div class="asideContent">
                <div class="topAside">
                    <div class="searchGroup">
                        <label class="sr-only" for="search">SEARCH</label>
                        <input type="text" id="search" placeholder="Search for a product...">
                    </div>
                    <div class="filterGroup" data-for="type">
                        <div class="title">
                            <h2>TYPE</h2>
                            <span>
                            <a href="#" id="productType_any" class="selected">Any of...</a>
                            &nbsp;|&nbsp;
                            <a href="#" id="productType_only">Only...</a>
                        </span>
                        </div>
						<?php
						require_once '../../_components/database.php';
						$db = new Database();
						$productTypes = $db->getTypes();

						foreach ($productTypes as $type) {
							$typeName = $type['name'];
							echo <<<HTML
                            <div class="inputLabelGroup" >
                                <input type="checkbox" name="$typeName" id="$typeName">
                                <label for="$typeName">$typeName</label>
                            </div>
                            HTML;
						}
						?>
                    </div>
                    <div class="filterGroup" data-for="gender">
                        <div class="title">
                            <h2>GENDER</h2>
                            <span>
                            <a href="#" id="productGender_any" class="selected">Any of...</a>
                            &nbsp;|&nbsp;
                            <a href="#" id="productGender_only">Only...</a>
                        </span>
                        </div>
						<?php
						$productGenders = $db->getGenders();

						foreach ($productGenders as $gender) {
							$genderName = $gender['name'];
							echo <<<HTML
                            <div class="inputLabelGroup">
                                <input type="checkbox" name="$genderName" id="$genderName">
                                <label for="$genderName">$genderName</label>
                            </div>
                            HTML;
						}
						?>
                    </div>
                </div>
                <button class="fullWidth" onclick="window.location.href='./upsert.php'">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Product</span>
                </button>
            </div>
        </aside>
        <section id="products" class="blue-1">
            <div id="productsGrid">
				<?php
				try {
					$products = $db->getAllProducts();

					foreach ($products as $product) {
						$photo = Database::findProductImageUrl($product->productID);
						?>
                        <div class="product" id="<?= $product->productID ?>"
                             data-gender="<?= $product->gender ?>"
                             data-type="<?= $product->type ?>"
                             data-name="<?= $product->name ?>"
                             onclick="window.location.href='./upsert.php?id=<?= $product->productID ?>'"
                        >
                            <img src="<?= $photo ?>" alt="<?= $product->productID . "_image" ?>">
                            <h1><?= $product->name ?></h1>
                        </div>
						<?php
					}
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				?>
            </div>
        </section>
	</main>
	<?php include '../../_components/shortFooter.php'; ?>
</body>
</html>