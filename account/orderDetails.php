<?php
    session_start();
    require '../_components/database.php';
    $db = new Database();
    if (!isset($_SESSION["userID"])) echo '<script>window.location.replace("../index.php");</script>';
?>
<!doctype html>
<html lang="en">
<head>
    <?php require_once '../_components/default.php'; ?>
    <title>Order Details</title>
    <link rel="stylesheet" href="../_stylesheets/accountManage.css">
</head>
<body>
<?php include '../_components/header.php';
      include '../_components/accountSidebar.php'; ?>
    <main>
        <?php
            //get price and status of order
            $orderID = $_GET["orderID"];
            $order = $db->getOrderByID($orderID);

            if (is_null($order) || $order["userID"] != $_SESSION["userID"]) {
                header("Location: manage.php?option=pastOrders");
                die;
            }

            //get items in the order
            $productsInOrder = $db->getProductsInOrder($orderID);

            ?>
            <section class="main orderDetails">
                <div class="top">
                    <h1>ORDER #<?= $orderID ?></h1>
                </div>
                <!-- This differs from the design because I just got a better idea, after submitting the report. -->
                <div class="left">
                    <p>Status: <?= ucfirst($order['status']) ?></p>
                    <p>Last Updated: <?= date_format(date_create($order['timeModified']), "jS F Y") ?></p>
                    <!-- Again, CO2 isn't tracked... -->
                    <p class="green"><i class="fa-solid fa-leaf"></i>C02 saved: 4.12kg</p>
                </div>
                <div class="right">
                    <h2>PRODUCTS</h2>
                    <table>
                        <tr>
                            <th>Product</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                        <?php
                            $totalPrice = 0;
                            foreach ($productsInOrder as $po) {
                                $product = $db->getProduct  ($po["productID"]);
                                $productSize = $product->sizes[$po["sizeID"]];
                                $paid = $productSize->price * $po['quantity'];
                                $totalPrice += $paid;
                                ?>
                                <tr>
                                    <td><?= $product->name ?></td>
                                    <td><?= $productSize->name ?></td>
                                    <td><?= $po["quantity"] ?></td>
                                    <td>$<?= number_format($paid, 2) ?></td>
                                </tr>
                                <?php
                            }
                        ?>
                        <tr>
                            <td colspan="3" class="total">Total</td>
                            <td>$<?= number_format($totalPrice, 2) ?></td>
                        </tr>
                    </table>
                </div>
                <div class="bottom">
                    <a class="button" href="manage.php?option=pastOrders">Back to Past Orders</a>
                    <a class="button" href="refund.php?order_number=<?= $orderID ?>">Request Refund</a>
                </div>
            </section>
            <?php
        ?>
    </main>
<?php include '../_components/footer.php'; ?>
</body>
</html>