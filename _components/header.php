<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav>
    <div class="left">
        <a href="/"><img src="/_images/logo_square.svg" alt=""></a>
    </div>
    <div class="right">
        <a href="/about.php">About Us</a>
        <a href="/basket.php">Basket</a>
        <?php if (isset($_SESSION['userID'])): ?>
            <a href="/logout.php">Logout</a>
            <?php if (isset($_SESSION["isAdmin"]) && $_SESSION['isAdmin'] === true): ?>
                <a href="/admin.php">Admin</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="/login.php">Login</a>
        <?php endif; ?>
    </div>
</nav>