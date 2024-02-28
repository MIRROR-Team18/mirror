<?php 
if(isset($_GET['option']) && in_array($_GET['option'], array('details', 'details-change', 'security', 'security-change', 'pastOrders', 'statistics', 'dangerZone'))) {
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
                break;
            case "details-change":
                break;
            case "security":
                break;
            case "security-change":
                break;
            case "pastOrders":
                break;
            case "statistics":
                break;
            case "dangerZone":
                break;
        }
        ?>
    </main>
    <?php include '../_components/footer.php'; ?>
</body>
</html>