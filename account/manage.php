<?php
if (session_status() === PHP_SESSION_NONE) session_start();
//Send the user back to the index page if they try to access the account manage page without being signed in 
//if (!isset($_SESSION["userID"])) echo '<script>window.location.replace("../index.php");</script>';
require '../_components/database.php';
if(isset($_GET['option']) && in_array($_GET['option'], array('details', 'details-change', 'security', 'pastOrders', 'statistics', 'dangerZone', 'link-gooogle', '2fa', 'change-password', 'request-data', 'delete-account'))) {
    $currentView = $_GET['option'];
} else $currentView = "details";
?>
<!doctype html>
<html lang = "en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Account Management</title>
    <link rel="stylesheet" href="../_stylesheets/main.css">
    <link rel="stylesheet" href="../_stylesheets/accountManage.css">
</head>
<body>
    <?php include '../_components/header.php'; ?>
    <div class = "sidenav">
        <div>
            <a href = "manage.php?option=details">Your Details</a><br>
            <a href = "manage.php?option=security">Security</a><br>
            <a href = "manage.php?option=pastOrders">Past Orders</a><br>
            <a href = "manage.php?option=statistics">Statistics</a><br>
            <a href = "manage.php?option=dangerZone">Danger Zone</a><br><br>
        </div>
    </div>
    <main class = "main">
        <?php
        switch($currentView) {
            case "details":
                ?>
                <h1>YOUR DETAILS</h1>
                <div class = "grid">
                    <div style = "text-align: right;">
                        <p>FIRST NAME:</p>
                        <p>LAST NAME:</p>
                        <p>EMAIL:</p>
                    </div>
                    <div style = "text-align: left;">
                        <?php 
                        $userID = $_SESSION["userID"];
                        $db = new Database();
                        $user = $db->getUser($userID);
                        echo "<p>".$user->firstName."</p><p>".$user->lastName."</p><p>".$user->email."</p>";
                        ?>
                    </div>
                </div>
                <a href = "manage.php?option=details-change"><input class = "button" type = "submit" value = "Make Changes"></a>
                <?php
                break;
            case "details-change":
                break;
            case "security":
                ?>
                <h1>SECURITY</h1>
                <div class = "grid">
                    <div style = "text-align: right;">
                        <p>Google account not linked</p><br><br>
                        <p>2FA not enabled</p><br><br>
                        <p>Need to change your password?</p>
                    </div>
                    <div style = "text-align: left;">
                        <a href = "manage.php?option=link-google"><input style = "width: 100%" class = "button" type = "submit" value = "Link Google Account"></a><br><br>
                        <a href = "manage.php?option=2fa"><input style = "width: 100%" class = "button" type = "submit" value = "Enable 2FA"></a><br><br>
                        <a href = "manage.php?option=change-password"><input style = "width: 100%" class = "button" type = "submit" value = "Change Password"></a>
                    </div>
                </div>
                <?php
                break;
            case "pastOrders":
                ?>
                <h1>ORDERS</h1>

                <nav class = ordersNav>
                    <form action="#" method="GET" style = "margin: 0 auto;">
                        <input type="number" name="search" placeholder="Search by order ID">
                        <select name="filter">
                            <option value="order-id-highest">Order ID (Highest)</option>
                            <option value="order-id-lowest">Order ID (Lowest)</option>
                            <option value="price-highest">Filter by Price (Highest)</option>
                            <option value="price-lowest">Filter by Price (Lowest)</option>
                        </select>
                        <input type="submit" value="Apply">
                    </form>
                </nav>
                <table>
                    <tr>
                        <th>Order ID</th>
                        <th>No. of Items</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>More</th>
                    </tr>
                    <!-- Sample data rows, replace with actual data from the database -->
                    <tr>
                        <td>1</td>
                        <td>5</td>
                        <td>Shipped</td>
                        <td>£50.00</td>
                        <td><a href="#">View Details</a></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>3</td>
                        <td>Delivered</td>
                        <td>£30.00</td>
                        <td><a href="#">View Details</a></td>
                    </tr>
                    <!-- More rows here -->
                </table>

                <?php
                break;
            case "statistics":
                ?>
                <h1>STATS</h1>
                <p>Some random facts about your account!</p><br><br>
                <p>Total spent: £[amt]</p>
                <p>This means you have brought the equivalent of [random fact]</p><br><br>
                <p style = "color: #2DF695;">CO2 saved: [amt]</p>
                <p style = "color: #2DF695;">Or, done the same amount of work as [amt] trees!</p><br><br>
                <p>Articles Brought: [amt]</p>
                <p>That's enough to fill [amt] wardrobes!</p>
                <?php
                break;
            case "dangerZone":
                ?>
                <h1>DANGER ZONE</h1>
                <div class = "grid">
                    <div style = "text-align: right;">
                        <p>We haven't got much but if you want it:</p><br><br>
                        <p>Got enough? We'd rather you didn't but...</p>
                    </div>
                    <div style = "text-align: left;">
                        <a href = "manage.php?option=request-data"><input style = "width: 100%" class = "button" type = "submit" value = "Request Data"></a><br><br>
                        <a href = "manage.php?option=delete-account"><input style = "width: 100%" class = "button" type = "submit" value = "Delete Account"></a>
                    </div>
                </div>
                <?php
                break;
            }
        ?>
    </main>
    <?php include '../_components/footer.php'; ?>
</body>
</html>