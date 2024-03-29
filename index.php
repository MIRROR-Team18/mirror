<!doctype html>
<html lang="en">
<head>
    <?php include '_components/default.php'; ?>
    <title>MIRЯOR</title>
    <link rel="stylesheet" href="_stylesheets/home.css">
</head>
<body>
    <?php include '_components/header.php'; ?>
    <main>
        <div id="welcome">
            <img id="logo" src="./_images/logo_large.svg" alt="MIRROR LOGO" />
            <h1 id="slogan">reflect your style</h1>
            <div class="buttons">
                <a class="button" href="./products/">Womens</a>
                <a class="button" href="./products/">Mens</a>
                <a class="button" href="./products/">Kids</a>
            </div>
        </div>
        <div id="featured" class="home-content">
            <h1>MOST WANTED <br /> AND MOST LOVED</h1>
            <div class="row products">
            <?php
			    require_once '_components/database.php';
                $db = new Database();
                try {
                    $products = $db->getProductsByPopularity(5);

                    foreach ($products as $product) {
                        $pathForPhoto = "_images/products/" . $product->productID . "/";
                        $photo = file_exists($pathForPhoto) ? $pathForPhoto . scandir($pathForPhoto)[2] : "https://picsum.photos/512"; // [0] is ".", [1] is ".."
                    ?>
                    <div class="product" onclick="window.location.href='./products/product.php?id=<?= $product->productID ?>'">
                        <img src="<?php echo $photo; ?>" alt="<?php echo $product->name; ?>" />
                        <h2><?php echo $product->name; ?></h2>
                    </div>
                    <?php
                    }
				} catch (Exception $e) {
					echo "Error: " . $e->getMessage();
				}
                ?>
            </div>
        </div>
        <div id="new" class="home-content">
            <h1>LATEST AND <br /> GREATEST RELEASES</h1>
            <div class="row products">
            <?php
                try {
                    $products = $db->getProductsByRecency(5);

                    foreach ($products as $product) {
                        $pathForPhoto = "_images/products/" . $product->productID . "/";
                        $photo = file_exists($pathForPhoto) ? $pathForPhoto . scandir($pathForPhoto)[2] : "https://picsum.photos/512"; // [0] is ".", [1] is ".."
                    ?>
                    <div class="product" onclick="window.location.href='./products/product.php?id=<?= $product->productID ?>'">
                        <img src="<?php echo $photo; ?>" alt="<?php echo $product->name; ?>" />
                        <h2><?php echo $product->name; ?></h2>
                    </div>
                    <?php
                    }
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </div>
        </div>
        <div id="notice" class="home-content">
            <div class="row">
                <i class="fa-solid fa-leaf"></i>
                <p>Check for this icon to make sustainable options!</p>
            </div>
        </div>
    </main>
    <?php include '_components/footer.php'; ?>
</body>
</html>



