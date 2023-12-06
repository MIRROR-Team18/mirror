<?php
    session_start();
    require '../_components/database.php';
    if (isset($_SESSION["userID"]) == false) echo '<script>window.location.replace("../index.php");</script>';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Details</title>
    <link rel="stylesheet" href="../_stylesheets/main.css">
    <link rel="stylesheet" href="../_stylesheets/accountManage.css">
</head>
<body>
<?php include '../_components/header.php'; ?>
    <main>
        <?php
            //get price and status of order
            $orderID = $_GET["orderID"];
            $sql = "SELECT * FROM orders WHERE orderID = '$orderID'";
            $conn = Connection:: getConnection();
            $order = $conn->query($sql)->fetch(PDO::FETCH_ASSOC);
            $conn = null;
            $price = $order["paidAmount"];
            $status = $order["status"];

            //get items in the order
            $products = "";
            $sql = "SELECT productID FROM products_in_orders WHERE orderID = '$orderID'";
            $conn = Connection:: getConnection();
            $productIDs = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            $conn = null;
            foreach($productIDs as $productID) {
                $sql = "SELECT name FROM products WHERE productID = '{$productID['productID']}'";
                $conn = Connection::getConnection();
                $productName = $conn->query($sql)->fetch(PDO::FETCH_ASSOC);
                $conn = null;
                $products .= "ProductID: " . $productID['productID'] . " | Product name: " . $productName['name'] . "\n";
            }
            ?>
            <section id = "accountPanel">
                <div id = "options">
                    <a class = "button" href = "manage.php?option=pastOrders" type = "submit">Return to previous orders</a>
                </div>
                <div id = "view"><?php
                    echo '<p>OrderID: '.$orderID. '</p><br><p>Order status: '.$status.'</p><br><p>Order price: £'.$price.'</p><br><p>'.nl2br($products).'</p>';
                ?></div>
            </section>
            <?php
        ?>
    </main>
<?php include '../_components/footer.php'; ?>
</body>
</html>