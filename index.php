<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MIRЯOR</title>
    <script src="https://kit.fontawesome.com/fb08371e49.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="_stylesheets/main.css">
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
            <?php // before writing any code, i'm gonna first code a product ?>
            <div class="row">
                <div class="product">
                    <img src="./_images/products/bag-bag/pexels-ge-yonk-1152077.jpg" alt="Product 1" />
                    <h2>Product 1</h2>
                </div><div class="product">
                    <img src="./_images/products/bag-bag/pexels-ge-yonk-1152077.jpg" alt="Product 1" />
                    <h2>Product 1</h2>
                </div><div class="product">
                    <img src="./_images/products/bag-bag/pexels-ge-yonk-1152077.jpg" alt="Product 1" />
                    <h2>Product 1</h2>
                </div><div class="product">
                    <img src="./_images/products/bag-bag/pexels-ge-yonk-1152077.jpg" alt="Product 1" />
                    <h2>Product 1</h2>
                </div><div class="product">
                    <img src="./_images/products/bag-bag/pexels-ge-yonk-1152077.jpg" alt="Product 1" />
                    <h2>Product 1</h2>
                </div>
            </div>
        </div>
        <div id="new" class="home-content">
            <h1>LATEST AND <br /> GREATEST RELEASES</h1>
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



