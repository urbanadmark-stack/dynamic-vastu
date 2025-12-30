<?php
require_once 'includes/functions.php';
$featured_properties = getFeaturedProperties(6);
$featured_project = null;
// Get first featured project for hero section
$featured_projects = getProjects(['featured_project' => 1, 'limit' => 1]);
if (!empty($featured_projects)) {
    $featured_project = $featured_projects[0];
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
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Find Your Dream Property in India</h1>
                    <p>Discover premium homes, luxury apartments, and prime commercial spaces across India</p>
                </div>
                <form class="search-form" action="listings.php" method="GET">
                    <div class="search-grid">
                        <div class="search-input-wrapper">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6b7280;">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                            <input type="text" name="city" placeholder="Search by City, Location..." class="search-input" style="padding-left: 3rem;">
                        </div>
                        <select name="property_type" class="search-select">
                            <option value="">All Property Types</option>
                            <option value="house">House</option>
                            <option value="apartment">Apartment</option>
                            <option value="villa">Villa</option>
                            <option value="commercial">Commercial</option>
                            <option value="land">Land</option>
                        </select>
                        <select name="status" class="search-select">
                            <option value="for_sale">For Sale</option>
                            <option value="for_rent">For Rent</option>
                        </select>
                        <button type="submit" class="btn btn-primary search-btn">
                            <span>Search Properties</span>
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 0.5rem;">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg>
                        </button>
                    </div>
                </form>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Properties</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Cities</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">1000+</span>
                        <span class="stat-label">Happy Clients</span>
                    </div>
                </div>
            </div>
        </section>

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
                            $main_image = !empty($images) ? 'uploads/' . $images[0] : 'assets/images/placeholder.jpg';
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

