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
                <div class="searchGroup">
                    <label class="sr-only" for="search">SEARCH</label>
                    <input type="text" id="search" placeholder="Search for a product...">
                </div>
                <div id="forProductType" class="filterGroup">
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
                        <div class="product" id="<?= $product->productID ?>" data-product-type="<?= $product->type ?>" data-product-name="<?= $product->name ?>" onclick="window.location.href='./product.php?id=<?= $product->productID ?>'">
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