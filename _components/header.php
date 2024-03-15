<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav>
    <div class="left">
        <a href="/"><img src="/_images/logo_square.svg" alt=""></a>
    </div>
    <div class="right">
        <a href="/about.php"><i class="fa-solid fa-house"></i>About Us</a>
        <a href="/basket.php"><i class="fa-solid fa-basket-shopping"></i>Basket</a>
        <?php if (isset($_SESSION['userID'])): ?>
            <a href="/logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
            <?php if (isset($_SESSION["isAdmin"]) && $_SESSION['isAdmin'] === true): ?>
                <a href="/admin/index.php"><i class="fa-solid fa-wrench"></i>Admin</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="/login.php"><i class="fa-solid fa-right-to-bracket"></i>Login</a>
        <?php endif; ?>
    </div>
</nav>