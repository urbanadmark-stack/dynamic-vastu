<?php
require_once 'config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: projects.php');
    exit();
}

$project = null;
try {
    $project = getProject(intval($_GET['id']));
} catch (Exception $e) {
    // Handle case where projects table doesn't exist
    error_log("Error fetching project: " . $e->getMessage());
    header('Location: projects.php');
    exit();
}

if (!$project) {
    header('Location: projects.php');
    exit();
}

// Decode JSON fields
$project_highlights = !empty($project['project_highlights']) ? json_decode($project['project_highlights'], true) : [];
$unit_configurations = !empty($project['unit_configurations']) ? json_decode($project['unit_configurations'], true) : [];
$amenities = !empty($project['amenities']) ? json_decode($project['amenities'], true) : [];
$nearby_locations = !empty($project['nearby_locations']) ? json_decode($project['nearby_locations'], true) : [];
$project_images = getProjectImages($project['project_images'] ?? null);
$renders_3d = !empty($project['renders_3d']) ? json_decode($project['renders_3d'], true) : [];
$payment_plans = !empty($project['payment_plans']) ? json_decode($project['payment_plans'], true) : [];

$page_title = !empty($project['seo_title']) ? $project['seo_title'] : htmlspecialchars($project['project_name']) . ' - ' . SITE_NAME;
$meta_description = !empty($project['meta_description']) ? $project['meta_description'] : htmlspecialchars($project['short_description'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <?php if (!empty($project['target_keywords'])): ?>
        <meta name="keywords" content="<?php echo htmlspecialchars($project['target_keywords']); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="property-page">
        <div class="container">
            <a href="projects.php" class="back-link">← Back to Projects</a>
            
            <div class="property-header">
                <h1><?php echo htmlspecialchars($project['project_name']); ?></h1>
                <div class="property-meta">
                    <span class="property-status-badge"><?php echo ucfirst(str_replace('_', ' ', $project['project_status'] ?? 'new_launch')); ?></span>
                    <span class="property-type-badge"><?php echo ucfirst(str_replace('_', ' ', $project['project_type'] ?? 'residential')); ?></span>
                    <?php if ($project['featured_project'] ?? false): ?>
                        <span class="property-status-badge" style="background: #f59e0b;">Featured</span>
                    <?php endif; ?>
                    <?php if ($project['hot_deal'] ?? false): ?>
                        <span class="property-status-badge" style="background: #ef4444;">Hot Deal</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (!empty($project['developer_name'])): ?>
                <div style="margin: 1rem 0; font-size: 1.1rem; color: var(--text-light);">
                    <strong>Developer:</strong> <?php echo htmlspecialchars($project['developer_name']); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($project['price_range_min'] || $project['price_range_max']): ?>
                <div class="property-price-large">
                    <?php 
                    if ($project['price_range_min'] && $project['price_range_max']) {
                        echo formatPrice($project['price_range_min']) . ' - ' . formatPrice($project['price_range_max']);
                    } elseif ($project['price_range_min']) {
                        echo 'Starting from ' . formatPrice($project['price_range_min']);
                    } else {
                        echo 'Up to ' . formatPrice($project['price_range_max']);
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="property-location-large">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                </svg>
                <?php 
                $location_parts = array_filter([
                    $project['locality'] ?? null,
                    $project['city'] ?? null,
                    $project['state'] ?? null,
                    $project['pincode'] ?? null
                ]);
                echo htmlspecialchars(implode(', ', $location_parts));
                ?>
            </div>
            
            <?php if (!empty($project['rera_number'])): ?>
                <div style="margin: 0.5rem 0; padding: 0.75rem; background: #f0f9ff; border-left: 3px solid #0ea5e9; border-radius: 0.25rem;">
                    <strong>RERA Number:</strong> <?php echo htmlspecialchars($project['rera_number']); ?>
                    <?php if (!empty($project['rera_authority_state'])): ?>
                        <span style="color: #6b7280;">(<?php echo htmlspecialchars($project['rera_authority_state']); ?>)</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Images Gallery -->
            <?php if (!empty($project_images)): ?>
                <div class="property-gallery">
                    <div class="gallery-main">
                        <img src="uploads/<?php echo htmlspecialchars($project_images[0]); ?>" alt="<?php echo htmlspecialchars($project['project_name']); ?>" id="main-image">
                    </div>
                    <?php if (count($project_images) > 1): ?>
                        <div class="gallery-thumbnails">
                            <?php foreach ($project_images as $index => $image): ?>
                                <img src="uploads/<?php echo htmlspecialchars($image); ?>" alt="Thumbnail <?php echo $index + 1; ?>" class="gallery-thumb <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeImage(this.src)">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="property-gallery">
                    <img src="assets/images/placeholder.svg" alt="No image available">
                </div>
            <?php endif; ?>
            
            <div class="property-content-grid">
                <div class="property-main">
                    <?php if (!empty($project['short_description'])): ?>
                        <section class="property-section">
                            <h2>Overview</h2>
                            <p><?php echo nl2br(htmlspecialchars($project['short_description'])); ?></p>
                        </section>
                    <?php endif; ?>
                    
                    <?php if (!empty($project['long_description'])): ?>
                        <section class="property-section">
                            <h2>Description</h2>
                            <p><?php echo nl2br(htmlspecialchars($project['long_description'])); ?></p>
                        </section>
                    <?php endif; ?>
                    
                    <?php if (!empty($project_highlights)): ?>
                        <section class="property-section">
                            <h2>Project Highlights</h2>
                            <ul class="features-list">
                                <?php foreach ($project_highlights as $highlight): ?>
                                    <li><?php echo htmlspecialchars($highlight); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>
                    
                    <?php if (!empty($unit_configurations)): ?>
                        <section class="property-section">
                            <h2>Available Unit Types</h2>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 1rem;">
                                <?php foreach ($unit_configurations as $unit): ?>
                                    <div style="padding: 1.5rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: #f9fafb;">
                                        <h3 style="margin: 0 0 0.75rem 0; color: var(--primary-color);"><?php echo htmlspecialchars($unit['unit_type'] ?? 'N/A'); ?></h3>
                                        <?php if (!empty($unit['carpet_area'])): ?>
                                            <p style="margin: 0.5rem 0;"><strong>Carpet Area:</strong> <?php echo number_format($unit['carpet_area']); ?> sq ft</p>
                                        <?php endif; ?>
                                        <?php if (!empty($unit['builtup_area'])): ?>
                                            <p style="margin: 0.5rem 0;"><strong>Built-up Area:</strong> <?php echo number_format($unit['builtup_area']); ?> sq ft</p>
                                        <?php endif; ?>
                                        <?php if (!empty($unit['price_starting_from'])): ?>
                                            <p style="margin: 0.5rem 0; font-size: 1.1rem; color: var(--primary-color); font-weight: 600;">
                                                <strong>Starting from:</strong> <?php echo formatPrice($unit['price_starting_from']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <?php if (!empty($amenities)): ?>
                        <section class="property-section">
                            <h2>Amenities</h2>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem; margin-top: 1rem;">
                                <?php foreach ($amenities as $amenity): ?>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: #f9fafb; border-radius: 0.25rem;">
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="color: var(--primary-color);">
                                            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                        </svg>
                                        <span><?php echo htmlspecialchars($amenity); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                    
                    <?php if (!empty($nearby_locations)): ?>
                        <section class="property-section">
                            <h2>Nearby Locations</h2>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem; margin-top: 1rem;">
                                <?php foreach ($nearby_locations as $location): ?>
                                    <div style="padding: 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
                                        <strong><?php echo htmlspecialchars($location['location'] ?? ''); ?></strong>
                                        <p style="margin: 0.25rem 0 0 0; color: var(--text-light);"><?php echo htmlspecialchars($location['distance'] ?? ''); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>
                </div>
                
                <div class="property-sidebar">
                    <div class="property-details-card">
                        <h3>Project Details</h3>
                        <?php if ($project['project_type']): ?>
                        <div class="detail-row">
                            <span class="detail-label">Type:</span>
                            <span class="detail-value"><?php echo ucfirst(str_replace('_', ' ', $project['project_type'])); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($project['project_status']): ?>
                        <div class="detail-row">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value"><?php echo ucfirst(str_replace('_', ' ', $project['project_status'])); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($project['project_launch_date']): ?>
                        <div class="detail-row">
                            <span class="detail-label">Launch Date:</span>
                            <span class="detail-value"><?php echo date('M Y', strtotime($project['project_launch_date'])); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($project['possession_date_rera']): ?>
                        <div class="detail-row">
                            <span class="detail-label">Possession:</span>
                            <span class="detail-value"><?php echo date('M Y', strtotime($project['possession_date_rera'])); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($project['construction_status_percentage'] !== null): ?>
                        <div class="detail-row">
                            <span class="detail-label">Construction:</span>
                            <span class="detail-value"><?php echo $project['construction_status_percentage']; ?>% Complete</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($project['sales_contact_name'] || $project['sales_mobile'] || $project['sales_email']): ?>
                        <div class="contact-card">
                            <h3>Contact Sales</h3>
                            <?php if ($project['sales_contact_name']): ?>
                                <p class="agent-name"><?php echo htmlspecialchars($project['sales_contact_name']); ?></p>
                            <?php endif; ?>
                            <?php if ($project['sales_mobile']): ?>
                                <p>
                                    <a href="tel:<?php echo htmlspecialchars($project['sales_mobile']); ?>">
                                        <?php echo htmlspecialchars($project['sales_mobile']); ?>
                                    </a>
                                    <?php if ($project['whatsapp_enabled'] ?? false): ?>
                                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $project['sales_mobile']); ?>" target="_blank" style="margin-left: 0.5rem; color: #25D366;">
                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($project['sales_email']): ?>
                                <p><a href="mailto:<?php echo htmlspecialchars($project['sales_email']); ?>"><?php echo htmlspecialchars($project['sales_email']); ?></a></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
    <script>
        function changeImage(src) {
            document.getElementById('main-image').src = src;
            document.querySelectorAll('.gallery-thumb').forEach(thumb => {
                thumb.classList.remove('active');
            });
            event.target.classList.add('active');
        }
    </script>
</body>
</html>

