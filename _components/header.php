<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav>
    <div class="left">
        <a href="/"><img src="./_images/logo_square.svg" alt=""></a>
    </div>
    <div class="right">
        <a>About Us</a>
        <a>Basket</a>
        <?php if (isset($_SESSION['userID'])): ?>
            <a href="/logout">Logout</a>
            <?php if (isset($_SESSION["isAdmin"]) && $_SESSION['isAdmin'] === true): ?>
                <a href="/admin">Admin</a>
            <?php endif; ?>
        <?php else: ?>
            <a>Login</a>
        <?php endif; ?>
    </div>
</nav>