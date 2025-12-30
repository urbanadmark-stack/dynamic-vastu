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
                <a href="index.php" class="nav-link">Home</a>
                <a href="listings.php" class="nav-link">Properties</a>
                <a href="projects.php" class="nav-link">New Launches</a>
                <a href="listings.php?status=for_sale" class="nav-link">For Sale</a>
                <a href="listings.php?status=for_rent" class="nav-link">For Rent</a>
                <?php if (isAdmin()): ?>
                    <a href="admin/index.php" class="nav-link admin-link">Admin Panel</a>
                    <a href="admin/logout.php" class="nav-link">Logout</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>

