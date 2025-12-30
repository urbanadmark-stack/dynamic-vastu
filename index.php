<?php
require_once 'includes/functions.php';
$featured_properties = getFeaturedProperties(6);
$featured_project = null;
$featured_projects = [];
// Get first featured project for hero section and featured projects for homepage section
try {
    $all_projects = getProjects(['limit' => 100]);
    foreach ($all_projects as $proj) {
        if (!empty($proj['featured_project'])) {
            if (!$featured_project) {
                $featured_project = $proj; // Use first one for hero
            }
            $featured_projects[] = $proj; // Collect all featured projects
        }
    }
    // Limit featured projects to 6 for the section
    $featured_projects = array_slice($featured_projects, 0, 6);
} catch (Exception $e) {
    // Projects table might not exist yet, continue without featured project
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Find Your Dream Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Hero Section -->
        <section class="hero">
            <?php if ($featured_project): 
                $project_images = getProjectImages($featured_project['project_images'] ?? null);
                $hero_image = !empty($project_images) ? 'uploads/' . $project_images[0] : 'assets/images/placeholder.svg';
            ?>
                <div class="hero-background" style="background-image: url('<?php echo htmlspecialchars($hero_image); ?>');"></div>
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <div class="hero-project-info">
                        <div class="hero-project-badge">
                            <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #fff;"><?php echo htmlspecialchars($featured_project['project_name']); ?></h2>
                            <?php if (!empty($featured_project['developer_name'])): ?>
                                <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: rgba(255,255,255,0.9);"><?php echo htmlspecialchars($featured_project['developer_name']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($featured_project['locality']) || !empty($featured_project['city'])): ?>
                                <p style="margin: 0.25rem 0 0 0; font-size: 0.75rem; color: rgba(255,255,255,0.8);">
                                    <?php echo htmlspecialchars(($featured_project['locality'] ?? '') . ($featured_project['locality'] && $featured_project['city'] ? ', ' : '') . ($featured_project['city'] ?? '')); ?>
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($featured_project['rera_number'])): ?>
                                <p style="margin: 0.5rem 0 0 0; font-size: 0.7rem; color: rgba(255,255,255,0.75);">
                                    RERA: <?php echo htmlspecialchars($featured_project['rera_number']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="hero-project-highlight">
                            <?php 
                            $payment_plans = !empty($featured_project['payment_plans']) ? json_decode($featured_project['payment_plans'], true) : [];
                            if (!empty($payment_plans)): ?>
                                <div class="hero-payment-plan"><?php echo htmlspecialchars($payment_plans[0]); ?></div>
                            <?php endif; ?>
                            <h3 style="margin: 1rem 0; font-size: 2.5rem; font-weight: 700; color: #fff;">LIVE THE VACATION LIFE</h3>
                            <?php if ($featured_project['id']): ?>
                                <a href="project.php?id=<?php echo $featured_project['id']; ?>" class="hero-cta-btn">Explore Now →</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="hero-background-gradient"></div>
                <div class="hero-content">
                    <div class="hero-text">
                        <h1>Find Your Dream Property in India</h1>
                        <p>Discover premium homes, luxury apartments, and prime commercial spaces across India</p>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Search Bar -->
            <div class="hero-search-container">
                <form class="search-form" action="listings.php" method="GET">
                    <div class="search-tabs">
                        <button type="button" class="search-tab active" data-action="listings.php">Buy</button>
                        <button type="button" class="search-tab" data-action="listings.php?status=for_rent">Rent</button>
                        <button type="button" class="search-tab" data-action="projects.php">
                            New Launch
                            <span class="search-tab-badge"></span>
                        </button>
                        <button type="button" class="search-tab" data-action="listings.php?property_type=commercial">Commercial</button>
                        <button type="button" class="search-tab" data-action="listings.php?property_type=land">Plots/Land</button>
                        <button type="button" class="search-tab" data-action="projects.php">Projects</button>
                    </div>
                    <div class="search-grid">
                        <select name="property_type" class="search-select-category">
                            <option value="">All Residential</option>
                            <option value="house">House</option>
                            <option value="apartment">Apartment</option>
                            <option value="villa">Villa</option>
                        </select>
                        <div class="search-input-wrapper">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6b7280; pointer-events: none;">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                            <input type="text" name="city" placeholder="Search 'Hyderabad'" class="search-input" style="padding-left: 3rem; padding-right: 3rem;">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="position: absolute; right: 2.5rem; top: 50%; transform: translateY(-50%); color: #6b7280; pointer-events: none;">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                            </svg>
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; cursor: pointer;">
                                <path d="M15.854 8.854a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 8.5l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                <path d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm7-6a6 6 0 1 1 0 12A6 6 0 0 1 8 2z"/>
                            </svg>
                        </div>
                        <button type="submit" class="btn btn-primary search-btn">Search</button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Featured Projects -->
        <?php if (!empty($featured_projects)): ?>
        <section class="featured-section" style="background: var(--bg-light); padding: 4rem 0;">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Featured New Launches</h2>
                    <p class="section-subtitle">Discover premium real estate projects with RERA registration across India</p>
                </div>
                <div class="properties-grid">
                    <?php foreach ($featured_projects as $proj): 
                        $project_images = getProjectImages($proj['project_images'] ?? null);
                        $main_image = !empty($project_images) ? 'uploads/' . $project_images[0] : 'assets/images/placeholder.svg';
                        $price_range = '';
                        if ($proj['price_range_min'] && $proj['price_range_max']) {
                            $price_range = formatPrice($proj['price_range_min']) . ' - ' . formatPrice($proj['price_range_max']);
                        } elseif ($proj['price_range_min']) {
                            $price_range = 'Starting from ' . formatPrice($proj['price_range_min']);
                        }
                    ?>
                        <div class="property-card">
                            <div class="property-image">
                                <img src="<?php echo htmlspecialchars($main_image); ?>" alt="<?php echo htmlspecialchars($proj['project_name']); ?>">
                                <span class="property-status"><?php echo ucfirst(str_replace('_', ' ', $proj['project_status'] ?? 'new_launch')); ?></span>
                                <span class="property-type"><?php echo ucfirst(str_replace('_', ' ', $proj['project_type'] ?? 'residential')); ?></span>
                                <?php if ($proj['hot_deal'] ?? false): ?>
                                    <span class="property-type" style="background: #ef4444;">Hot Deal</span>
                                <?php endif; ?>
                            </div>
                            <div class="property-content">
                                <h3><a href="project.php?id=<?php echo $proj['id']; ?>"><?php echo htmlspecialchars($proj['project_name']); ?></a></h3>
                                <?php if (!empty($proj['developer_name'])): ?>
                                    <p style="color: var(--text-light); font-size: 0.875rem; margin-bottom: 0.5rem;">
                                        by <strong><?php echo htmlspecialchars($proj['developer_name']); ?></strong>
                                    </p>
                                <?php endif; ?>
                                <p class="property-location">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                    </svg>
                                    <?php echo htmlspecialchars(($proj['locality'] ?? $proj['city'] ?? '') . ($proj['locality'] && $proj['city'] ? ', ' : '') . ($proj['city'] ?? '') . ($proj['city'] && $proj['state'] ? ', ' : '') . ($proj['state'] ?? '')); ?>
                                </p>
                                <?php if (!empty($proj['short_description'])): ?>
                                    <p style="color: var(--text-light); font-size: 0.9rem; margin: 1rem 0;">
                                        <?php echo htmlspecialchars(substr($proj['short_description'], 0, 100)) . (strlen($proj['short_description']) > 100 ? '...' : ''); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($proj['rera_number'])): ?>
                                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0.5rem 0;">
                                        RERA: <?php echo htmlspecialchars($proj['rera_number']); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($price_range): ?>
                                    <div class="property-price">
                                        <?php echo $price_range; ?>
                                    </div>
                                <?php endif; ?>
                                <a href="project.php?id=<?php echo $proj['id']; ?>" class="btn btn-outline">View Project Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center" style="margin-top: 2rem;">
                    <a href="projects.php" class="btn btn-primary">View All Projects</a>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Featured Properties -->
        <section class="featured-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Featured Properties</h2>
                    <p class="section-subtitle">Explore our handpicked selection of premium properties across India</p>
                </div>
                <?php if (empty($featured_properties)): ?>
                    <p class="no-results">No properties available at the moment. Check back soon!</p>
                <?php else: ?>
                    <div class="properties-grid">
                        <?php foreach ($featured_properties as $property): 
                            $images = getPropertyImages($property['images']);
                            $main_image = !empty($images) ? 'uploads/' . $images[0] : 'assets/images/placeholder.svg';
                        ?>
                            <div class="property-card">
                                <div class="property-image">
                                    <img src="<?php echo htmlspecialchars($main_image); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
                                    <span class="property-status"><?php echo ucfirst(str_replace('_', ' ', $property['status'])); ?></span>
                                    <span class="property-type"><?php echo ucfirst($property['property_type']); ?></span>
                                </div>
                                <div class="property-content">
                                    <h3><a href="property.php?id=<?php echo $property['id']; ?>"><?php echo htmlspecialchars($property['title']); ?></a></h3>
                                    <p class="property-location">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                        </svg>
                                        <?php echo htmlspecialchars($property['city'] . ', ' . ($property['state'] ?? '')); ?>
                                    </p>
                                    <div class="property-details">
                                        <?php if ($property['bedrooms']): ?><span><strong><?php echo $property['bedrooms']; ?></strong> Beds</span><?php endif; ?>
                                        <?php if ($property['bathrooms']): ?><span><strong><?php echo $property['bathrooms']; ?></strong> Baths</span><?php endif; ?>
                                        <?php if ($property['area']): ?><span><strong><?php echo formatArea($property['area'], $property['area_unit']); ?></strong></span><?php endif; ?>
                                    </div>
                                    <div class="property-price">
                                        <?php echo formatPrice($property['price']); ?>
                                    </div>
                                    <a href="property.php?id=<?php echo $property['id']; ?>" class="btn btn-outline">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center" style="margin-top: 2rem;">
                        <a href="listings.php" class="btn btn-primary">View All Properties</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>
