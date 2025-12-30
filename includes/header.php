<header class="main-header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="index.php">
                    <h1><?php echo SITE_NAME; ?></h1>
                </a>
            </div>
            <nav class="main-nav">
                <a href="index.php">Home</a>
                <a href="listings.php">Properties</a>
                <?php if (isAdmin()): ?>
                    <a href="admin/index.php">Admin</a>
                    <a href="admin/logout.php">Logout</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>

