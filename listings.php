<?php
require_once 'includes/functions.php';

$filters = [];
if (isset($_GET['property_type']) && !empty($_GET['property_type'])) {
    $filters['property_type'] = sanitize($_GET['property_type']);
}
if (isset($_GET['status']) && !empty($_GET['status'])) {
    $filters['status'] = sanitize($_GET['status']);
}
if (isset($_GET['city']) && !empty($_GET['city'])) {
    $filters['city'] = sanitize($_GET['city']);
}
if (isset($_GET['min_price']) && !empty($_GET['min_price'])) {
    $filters['min_price'] = floatval($_GET['min_price']);
}
if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
    $filters['max_price'] = floatval($_GET['max_price']);
}
if (isset($_GET['bedrooms']) && !empty($_GET['bedrooms'])) {
    $filters['bedrooms'] = intval($_GET['bedrooms']);
}

$properties = getProperties($filters);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Properties - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="listings-page">
        <div class="container">
            <h1 class="page-title">All Properties</h1>
            
            <!-- Filters -->
            <div class="filters-section">
                <form method="GET" action="listings.php" class="filters-form">
                    <div class="filter-row">
                        <input type="text" name="city" placeholder="City, State, Zip" value="<?php echo isset($_GET['city']) ? htmlspecialchars($_GET['city']) : ''; ?>" class="filter-input">
                        
                        <select name="property_type" class="filter-select">
                            <option value="">All Types</option>
                            <option value="house" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'house') ? 'selected' : ''; ?>>House</option>
                            <option value="apartment" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'apartment') ? 'selected' : ''; ?>>Apartment</option>
                            <option value="villa" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'villa') ? 'selected' : ''; ?>>Villa</option>
                            <option value="commercial" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'commercial') ? 'selected' : ''; ?>>Commercial</option>
                            <option value="land" <?php echo (isset($_GET['property_type']) && $_GET['property_type'] == 'land') ? 'selected' : ''; ?>>Land</option>
                        </select>
                        
                        <select name="status" class="filter-select">
                            <option value="">All Status</option>
                            <option value="for_sale" <?php echo (isset($_GET['status']) && $_GET['status'] == 'for_sale') ? 'selected' : ''; ?>>For Sale</option>
                            <option value="for_rent" <?php echo (isset($_GET['status']) && $_GET['status'] == 'for_rent') ? 'selected' : ''; ?>>For Rent</option>
                        </select>
                        
                        <input type="number" name="min_price" placeholder="Min Price" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>" class="filter-input">
                        
                        <input type="number" name="max_price" placeholder="Max Price" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>" class="filter-input">
                        
                        <select name="bedrooms" class="filter-select">
                            <option value="">Bedrooms</option>
                            <option value="1" <?php echo (isset($_GET['bedrooms']) && $_GET['bedrooms'] == '1') ? 'selected' : ''; ?>>1+</option>
                            <option value="2" <?php echo (isset($_GET['bedrooms']) && $_GET['bedrooms'] == '2') ? 'selected' : ''; ?>>2+</option>
                            <option value="3" <?php echo (isset($_GET['bedrooms']) && $_GET['bedrooms'] == '3') ? 'selected' : ''; ?>>3+</option>
                            <option value="4" <?php echo (isset($_GET['bedrooms']) && $_GET['bedrooms'] == '4') ? 'selected' : ''; ?>>4+</option>
                            <option value="5" <?php echo (isset($_GET['bedrooms']) && $_GET['bedrooms'] == '5') ? 'selected' : ''; ?>>5+</option>
                        </select>
                        
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="listings.php" class="btn btn-outline">Clear</a>
                    </div>
                </form>
            </div>
            
            <!-- Results Count -->
            <div class="results-count">
                Found <?php echo count($properties); ?> property(ies)
            </div>
            
            <!-- Properties Grid -->
            <?php if (empty($properties)): ?>
                <div class="no-results">
                    <p>No properties found matching your criteria.</p>
                    <a href="listings.php" class="btn btn-primary">View All Properties</a>
                </div>
            <?php else: ?>
                <div class="properties-grid">
                    <?php foreach ($properties as $property): 
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
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>

