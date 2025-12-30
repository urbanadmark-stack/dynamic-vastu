<header class="main-header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="index.php">
                    <?php if (file_exists('assets/images/logo.png')): ?>
                        <img src="assets/images/logo.png" alt="<?php echo SITE_NAME; ?>" class="logo-img">
                    <?php endif; ?>
                    <span class="logo-text"><?php echo SITE_NAME; ?></span>
                </a>
            </div>
            <button class="mobile-menu-toggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <nav class="main-nav" id="mainNav">
                <a href="admin/add-property.php" class="nav-link">List Property</a>
                <a href="admin/add-property.php" class="nav-link nav-btn nav-btn-free">Free</a>
                <a href="listings.php?status=for_sale" class="nav-link">Buy</a>
                <a href="listings.php?status=for_rent" class="nav-link">Rent</a>
                <a href="projects.php" class="nav-link">New Launches</a>
                <a href="#" class="nav-link">Blogs</a>
                <?php if (isAdmin()): ?>
                    <a href="admin/logout.php" class="nav-link nav-btn nav-btn-login">Logout</a>
                <?php else: ?>
                    <a href="admin/login.php" class="nav-link nav-btn nav-btn-login">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>

