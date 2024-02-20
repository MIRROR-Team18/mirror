<!doctype html>
<html lang="en">
<head>
    <?php include '../_components/default.php'; ?>
    <title>Products - MIRЯOR</title>
    <link rel="stylesheet" href="../_stylesheets/products.css">
    <script src="../_scripts/productsListing.js" defer async></script>
</head>
<body>
    <?php include '../_components/header.php'; ?>
    <main>
        <aside>
            <div class="asideContent">
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
					require_once '../_components/database.php';
					$db = new Database();
					$productTypes = $db->getTypes();

					foreach ($productTypes as $type) {
						$typeName = $type['name'];
						echo <<<HTML
                            <div class="inputLabelGroup">
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
        </aside>
        <section id="products">
            <h1 id="productsDescriptor">PRODUCTS</h1>
            <div id="productsGrid">
                <?php
                try {
					$products = $db->getAllProducts();

					foreach ($products as $product) {
                        $photo = Database::findProductImageUrl($product->productID);
                        $price = "Unknown...";

                        if (sizeof($product->sizes) > 0) {
                            // Add all available prices into an array
                            $prices = array();
                            foreach ($product->sizes as $size) {
                                if (isset($size->price)) $prices[] = $size->price;
                            }

							if (sizeof($prices) > 2) { $price = "From £" . min($prices); } // If there's more than one price, provide the lowest price.
                            else if (sizeof($prices) == 1) { $price = "£" . $prices[0]; } // Only one price, just provide that price
                            // No final else, because price is already set
                        }

                    ?>
                        <div class="product" id="<?= $product->productID ?>"
                             data-gender="<?= $product->gender ?>"
                             data-type="<?= $product->type ?>"
                             data-name="<?= $product->name ?>"
                             onclick="window.location.href='./product.php?id=<?= $product->productID ?>'"
                        >
                            <img src="<?= $photo ?>" alt="<?= $product->productID . "_image" ?>">
                            <h1><?= $product->name ?></h1>
                            <h2><?= $price ?></h2>
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
    <?php include '../_components/footer.php'; ?>
</body>
</html>