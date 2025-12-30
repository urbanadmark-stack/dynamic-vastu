<?php
require_once 'includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$property = getProperty(intval($_GET['id']));
if (!$property) {
    header('Location: index.php');
    exit();
}

$images = getPropertyImages($property['images']);
$features = !empty($property['features']) ? json_decode($property['features'], true) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($property['title']); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="property-page">
        <div class="container">
            <a href="listings.php" class="back-link">← Back to Listings</a>
            
            <div class="property-header">
                <h1><?php echo htmlspecialchars($property['title']); ?></h1>
                <div class="property-meta">
                    <span class="property-status-badge"><?php echo ucfirst(str_replace('_', ' ', $property['status'])); ?></span>
                    <span class="property-type-badge"><?php echo ucfirst($property['property_type']); ?></span>
                </div>
            </div>
            
            <div class="property-price-large">
                <?php echo formatPrice($property['price']); ?>
            </div>
            
            <div class="property-location-large">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                </svg>
                <?php echo htmlspecialchars($property['address'] . ', ' . $property['city'] . ', ' . ($property['state'] ?? '') . ' ' . ($property['zip_code'] ?? '')); ?>
            </div>
            
            <!-- Images Gallery -->
            <?php if (!empty($images)): ?>
                <div class="property-gallery">
                    <div class="gallery-main">
                        <img src="uploads/<?php echo htmlspecialchars($images[0]); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" id="main-image">
                    </div>
                    <?php if (count($images) > 1): ?>
                        <div class="gallery-thumbnails">
                            <?php foreach ($images as $index => $image): ?>
                                <img src="uploads/<?php echo htmlspecialchars($image); ?>" alt="Thumbnail <?php echo $index + 1; ?>" class="gallery-thumb <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeImage(this.src)">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="property-gallery">
                    <img src="assets/images/placeholder.jpg" alt="No image available">
                </div>
            <?php endif; ?>
            
            <div class="property-content-grid">
                <div class="property-main">
                    <section class="property-section">
                        <h2>Description</h2>
                        <p><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
                    </section>
                    
                    <?php if (!empty($features)): ?>
                        <section class="property-section">
                            <h2>Features</h2>
                            <ul class="features-list">
                                <?php foreach ($features as $feature): ?>
                                    <li><?php echo htmlspecialchars($feature); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>
                </div>
                
                <div class="property-sidebar">
                    <div class="property-details-card">
                        <h3>Property Details</h3>
                        <div class="detail-row">
                            <span class="detail-label">Bedrooms:</span>
                            <span class="detail-value"><?php echo $property['bedrooms'] ?? 'N/A'; ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Bathrooms:</span>
                            <span class="detail-value"><?php echo $property['bathrooms'] ?? 'N/A'; ?></span>
                        </div>
                        <?php if ($property['area']): ?>
                        <div class="detail-row">
                            <span class="detail-label">Area:</span>
                            <span class="detail-value"><?php echo formatArea($property['area'], $property['area_unit']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($property['parking']): ?>
                        <div class="detail-row">
                            <span class="detail-label">Parking:</span>
                            <span class="detail-value"><?php echo $property['parking']; ?> spaces</span>
                        </div>
                        <?php endif; ?>
                        <?php if ($property['year_built']): ?>
                        <div class="detail-row">
                            <span class="detail-label">Year Built:</span>
                            <span class="detail-value"><?php echo $property['year_built']; ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($property['agent_name'] || $property['agent_phone'] || $property['agent_email']): ?>
                        <div class="contact-card">
                            <h3>Contact Agent</h3>
                            <?php if ($property['agent_name']): ?>
                                <p class="agent-name"><?php echo htmlspecialchars($property['agent_name']); ?></p>
                            <?php endif; ?>
                            <?php if ($property['agent_phone']): ?>
                                <p><a href="tel:<?php echo htmlspecialchars($property['agent_phone']); ?>"><?php echo htmlspecialchars($property['agent_phone']); ?></a></p>
                            <?php endif; ?>
                            <?php if ($property['agent_email']): ?>
                                <p><a href="mailto:<?php echo htmlspecialchars($property['agent_email']); ?>"><?php echo htmlspecialchars($property['agent_email']); ?></a></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script>
        function changeImage(src) {
            document.getElementById('main-image').src = src;
            document.querySelectorAll('.gallery-thumb').forEach(thumb => thumb.classList.remove('active'));
            event.target.classList.add('active');
        }
    </script>
    <script src="assets/js/main.js"></script>
</body>
</html>

