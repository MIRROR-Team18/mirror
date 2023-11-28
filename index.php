<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MIЯЯOR</title>
    <link rel="stylesheet" href="_stylesheets/main.css">
</head>
<body>
    <?php include '_components/header.php'; ?>
    <header>
        <h1>MIЯЯOR</h1>
        <p>we'll have a slogan right?</p>
    </header>
    <main>
        <section>
            <h1>NEW RELEASES</h1>
            <div class="grid" id="newReleases">
                <?php
                // Example code for fetching products from the database
                // $products = $db->query('SELECT * FROM products ORDER BY id DESC LIMIT 4');
                // foreach ($products as $product) {
                //     include '_components/product.php';
                // }
                ?>
            </div>
            <button>See the range...</button>
        </section>
    </main>
    <?php include '_components/footer.php'; ?>
</body>
</html>



