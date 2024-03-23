<?php
if (session_status() === PHP_SESSION_NONE) session_start();
//Send the user back to the index page if they try to access the account manage page without being signed in 
if (!isset($_SESSION["userID"])) echo '<script>window.location.replace("/login.php");</script>';
require '../_components/database.php';
$db = new Database();

if (isset($_GET['option']) && in_array($_GET['option'], array('details', 'details-change', 'security', 'pastOrders', 'statistics', 'dangerZone', 'link-gooogle', '2fa', 'change-password', 'request-data', 'delete-account'))) {
    $currentView = $_GET['option'];
} else $currentView = "details";

// If POST, update database
$inputError = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (!isset($_POST['for'])) {
        echo "Invalid request";
        die;
    }

    switch ($_POST['for']) {
        case "details-change":
            $firstName = $_POST['firstName'] ?? null;
            $surName = $_POST['surName'] ?? null;
            $email = $_POST['email'] ?? null;
            $userID = $_SESSION["userID"];

            if ($firstName == null && $surName == null && $email == null) {
                $inputError = "You must fill in at least one field to change";
				$currentView = "details-change";
                break;
            }

            if (!is_null($firstName) && $firstName != "") {
                if (strlen($firstName) < 2 || strlen($firstName) > 50) {
                    $inputError = "First name must be between 2 and 50 characters";
					$currentView = "details-change";
                    break;
                }
				if (!preg_match("/^[a-zA-Z-' ]*$/", $firstName)) {
					$inputError .= "First name can only contain letters and spaces.\n";
					$currentView = "details-change";
                    break;
				}

                $sql = "UPDATE users SET firstName = ? WHERE id = ?";
                $conn = Connection::getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute([$firstName, $userID]);
            }

            if (!is_null($surName) && $surName != "") {
                if (strlen($surName) < 2 || strlen($surName) > 50) {
					$inputError = "Last name must be between 2 and 50 characters";
					$currentView = "details-change";
					break;
				}
                if (!preg_match("/^[a-zA-Z-' ]*$/", $surName)) {
                    $inputError .= "Last name can only contain letters and spaces.\n";
                    $currentView = "details-change";
                    break;
				}

                $sql = "UPDATE users SET lastName = ? WHERE id = ?";
                $conn = Connection::getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute([$surName, $userID]);
            }

            if (!is_null($email) && $email != "") {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$inputError = "Invalid email format";
					$currentView = "details-change";
					break;
				}
                if (strlen($email) > 320) {
					$inputError = "Email must be less than 320 characters";
					$currentView = "details-change";
					break;
				}

                $sql = "UPDATE users SET email = ? WHERE id = ?";
                $conn = Connection::getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute([$email, $userID]);
            }
            break;

        case "change-password":
            $oldPass = $_POST['currentPassword'] ?? "";
            $newPass = $_POST['newPassword'] ?? "";
            $confirmNewPass = $_POST['confirmNewPassword'] ?? "";

            if ($oldPass == "" || $newPass == "" || $confirmNewPass == "") {
                $inputError = "All fields must be filled in";
                $currentView = "change-password";
                break;
            }

            if ($newPass != $confirmNewPass) {
                $inputError = "New password and confirm new password do not match";
                $currentView = "change-password";
                break;
            }

            if (strlen($newPass) < 8) {
                $inputError = "Password must be at least 8 characters long";
                $currentView = "change-password";
                break;
            }

			if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d])[\s\S]{7,256}$/', $newPass)) {
                $inputError = "Password must contain at least one uppercase letter, one lowercase letter, one number and one special character";
                $currentView = "change-password";
                break;
			}

            // Get old password, ensure it matches
            $userID = $_SESSION["userID"];
            $conn = Connection::getConnection();
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$userID]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!password_verify($oldPass, $result['password'])) {
                $inputError = "Old password is incorrect";
                $currentView = "change-password";
                break;
            }

            // Update password
            $newPass = password_hash($newPass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$newPass, $userID]);

            $currentView = "security";
            break;
    }
}
?>
<!doctype html>
<html lang = "en">
<head>
    <?php include '../_components/default.php'; ?>
    <title>Account Management</title>
    <link rel="stylesheet" href="../_stylesheets/accountManage.css">
</head>
<body>
    <?php include '../_components/header.php';
          include '../_components/accountSidebar.php'; ?>
    <main class="main">
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
				$userID = $_SESSION["userID"];
				$db = new Database();
				$user = $db->getUser($userID);
                ?>
                <form class="accDetails" method="POST" action="">
                    <input type="hidden" name="for" value="details-change">
                    <p>Leave fields you wish not to change blank.</p>
                    <p><?= nl2br($inputError); ?></p><br>
                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" id="firstName" placeholder="<?= $user->firstName ?>">
                    <label for="surName">Last Name</label>
                    <input type="text" name="surName" id="surName" placeholder="<?= $user->lastName ?>">
                    <label for="email">Email</label>
                    <input type="text" name = "email" id="email" placeholder="<?= $user->email ?>">
                    <input class="button" type="submit" value="Submit Changes">
                </form>
                <?php
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
            case "change-password":
                ?>
                <form class="accDetails" method = "post" action="">
                    <input type="hidden" name="for" value="change-password">
					<?php echo "<p>". nl2br($inputError) ."</p><br>"?>
                    <label for="currentPassword">Current Password</label>
                    <input type="password" name="currentPassword" id="currentPassword">

                    <label for="newPassword">New Password</label>
                    <input type="password" name="newPassword" id="newPassword">

                    <label for="confirmNewPassword">Confirm New Password</label>
                    <input type="password" name="confirmNewPassword" id="confirmNewPassword">

                    <input class="button" type="submit" value="Submit Changes">
                </form>
            <?php
                break;

            case "pastOrders":
                ?>
                <h1>ORDERS</h1>
                <nav class = ordersNav>
                    <form action="#" method="GET" style = "margin: 0 auto;">
                        <label class="sr-only" for="search">Search by Order ID</label>
                        <input type="number" id="search" name="search" placeholder="Search by order ID">
                        <label for="filter" class="sr-only">Filter by</label>
                        <select id="filter" name="filter">
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
                    <?php
                        $userID = $_SESSION["userID"];
                        $orders = $db->getOrdersByUser($userID);

                        $orders = array_filter($orders, function($order) {
                            if (isset($_GET['search']) && $_GET['search'] != "") { // If searching
                                return $order['id'] == $_GET['search'];
                            }
                            return $order['direction'] === "out"; // Only show orders going out, we don't want to display the other ones here.
                        });

                        foreach ($orders as $order) {
                            $products = $db->getProductsInOrder($order['id']);
                            $quantity = array_reduce($products, function($carry, $item) {
                                return $carry + $item['quantity'];
                            }, 0);
							$ucStatus = ucfirst($order['status']);

                            echo <<<HTML
                            <tr>
                                <td>{$order['id']}</td>
                                <td>{$quantity}</td>
                                <td>{$ucStatus}</td>
                                <td>£{$order['paidAmount']}</td>
                                <td><a href="./orderDetails.php?orderID={$order['id']}"><i class="fa-solid fa-angles-right"></i></a></td>
                            </tr>
                            HTML;
                        }
                    ?>
                </table>

                <?php
                break;
            case "statistics":
                $orders = $db->getOrdersByUser($_SESSION["userID"]);
                $totalSpent = array_reduce($orders, function($carry, $item) {
                    return $carry + $item['paidAmount'];
                }, 0);
                $totalBought = array_reduce($orders, function($carry, $item) {
					global $db;
					return $carry + count($db->getProductsInOrder($item['id']));
                }, 0);

                include "../_components/accountFacts.php";
                $fact = getRandomFact();
                $comparison = round($totalSpent / $fact['cost'], 8);

                ?>
                <h1>STATS</h1>
                <p>Some random facts about your account!</p>
                <br><br>
                <p>Total spent: £<?= $totalSpent ?></p>
                <p>This means you have brought the equivalent of <?= $comparison ?> <?= $fact['object'] . ($comparison != 1 ? "s" : "") ?> <?= $fact['historical'] ? "(in today's money)" : "" ?></p>
                <br><br>
                <!-- We aren't tracking carbon emissions yet, so this is just a placeholder -->
                <p class="green">CO2 saved: 0g</p>
                <p class="green">Or, done the same amount of work as 0 trees!</p>
                <br><br>
                <p>Articles Brought: <?= $totalBought ?></p>
                <p>That's enough to fill <?= round($totalBought / 74, 2) ?> wardrobes!</p>
                <!-- Source for 74 clothes: 4th paragraph of https://www.vogue.com/article/how-many-clothes-should-we-own -->
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

            case "request-data":
                ?>
                <h1>REQUEST DATA</h1>
                <p>It's on its way! <i>When we figure out emails...</i><br>
                    You should expect it within 30 days.
                </p>
                <a href="manage.php?option=dangerZone"><input class="button" type="submit" value="Back"></a>
                <?php
                break;

            case "delete-account":
                ?>
                <!-- Formatting here could absolutely be better if I wasn't in a rush. -->
                <h1>DELETE ACCOUNT</h1>
                <p>Are you sure you want to delete your account? This action is irreversible!</p>
                <p>By deleting your account, you will lose all your data and will not be able to recover it.</p>
                <p>Deleting your account will NOT:<br>
                    - Cancel your orders<br>
                    - Remove your reviews<br>
                    - Un-send enquiries and refund requests<br> (but will make it impossible to give you a refund if not processed yet)
                </p>
                <a href="manage.php?option=dangerZone"><input class="button" type="submit" value="No, go back"></a>
                <a href="deleteAccount.php"><input class="button" type="submit" value="Yes, delete my account"></a>
                <?php
                break;
            }
        ?>
    </main>
    <?php include '../_components/footer.php'; ?>
</body>
</html>