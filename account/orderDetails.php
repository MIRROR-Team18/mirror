<?php
    session_start();
    require '../_components/database.php';
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
            $order = $conn->query($sql);
            $conn->close();
            $price = $order["paidAmount"];
            $starus = $order["status"];

            //get items in the order
            $products = "";
            $sql = "SELECT productID FROM products_in_orders WHERE orderID = '$orderID'";
            $conn = Connection:: getConnection();
            $productIDs = $conn->query($sql);
            $conn->close();
            foreach($productIDs as $productID) {
                $sql = "SELECT name FROM products WHERE productID = '$productID'";
                $conn = Connection:: getConnection();
                $productName = $conn->query($sql);
                $conn->close();
                $products .= "ProductID: " . $productID . " | Product name: " . $productName . "\n";
            }
            ?>
            <div style = "display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                <div><?php 
                    echo '<p>'.$orderID.'</p>'; 
                ?></div>
                <div><?php
                    echo '<p> Order status: '.$status.'</p><br>' .'<p>Order price: £'.$price.'</p><br>'.'<p>'.nl2br($products).'</p>';
                ?></div>
            </div>
            <?php
        ?>
    </main>
<?php include '../_components/footer.php'; ?>
</body>
</html>