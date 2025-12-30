<?php
require_once 'includes/functions.php';

$filters = [];
if (isset($_GET['project_type']) && !empty($_GET['project_type'])) {
    $filters['project_type'] = sanitize($_GET['project_type']);
}
if (isset($_GET['project_status']) && !empty($_GET['project_status'])) {
    $filters['project_status'] = sanitize($_GET['project_status']);
}
if (isset($_GET['city']) && !empty($_GET['city'])) {
    $filters['city'] = sanitize($_GET['city']);
}
if (isset($_GET['state']) && !empty($_GET['state'])) {
    $filters['state'] = sanitize($_GET['state']);
}

$projects = getProjects($filters);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="listings-page">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Real Estate Projects</h1>
                <p class="page-subtitle">Explore premium residential and commercial projects across India</p>
            </div>
            
            <!-- Filters -->
            <div class="filters-section">
                <form method="GET" action="projects.php" class="filters-form">
                    <div class="filter-row">
                        <input type="text" name="city" placeholder="City" value="<?php echo isset($_GET['city']) ? htmlspecialchars($_GET['city']) : ''; ?>" class="filter-input">
                        
                        <select name="project_type" class="filter-select">
                            <option value="">All Types</option>
                            <option value="residential" <?php echo (isset($_GET['project_type']) && $_GET['project_type'] == 'residential') ? 'selected' : ''; ?>>Residential</option>
                            <option value="commercial" <?php echo (isset($_GET['project_type']) && $_GET['project_type'] == 'commercial') ? 'selected' : ''; ?>>Commercial</option>
                            <option value="mixed_use" <?php echo (isset($_GET['project_type']) && $_GET['project_type'] == 'mixed_use') ? 'selected' : ''; ?>>Mixed-Use</option>
                            <option value="plotted_development" <?php echo (isset($_GET['project_type']) && $_GET['project_type'] == 'plotted_development') ? 'selected' : ''; ?>>Plotted Development</option>
                        </select>
                        
                        <select name="project_status" class="filter-select">
                            <option value="">All Status</option>
                            <option value="new_launch" <?php echo (isset($_GET['project_status']) && $_GET['project_status'] == 'new_launch') ? 'selected' : ''; ?>>New Launch</option>
                            <option value="under_construction" <?php echo (isset($_GET['project_status']) && $_GET['project_status'] == 'under_construction') ? 'selected' : ''; ?>>Under Construction</option>
                            <option value="ready_to_move" <?php echo (isset($_GET['project_status']) && $_GET['project_status'] == 'ready_to_move') ? 'selected' : ''; ?>>Ready to Move</option>
                            <option value="oc_received" <?php echo (isset($_GET['project_status']) && $_GET['project_status'] == 'oc_received') ? 'selected' : ''; ?>>OC Received</option>
                        </select>
                        
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="projects.php" class="btn btn-outline">Clear</a>
                    </div>
                </form>
            </div>
            
            <div class="results-count">
                <?php echo count($projects); ?> Project(s) Found
            </div>
            
            <?php if (empty($projects)): ?>
                <p class="no-results">No projects found. Please adjust your filters.</p>
            <?php else: ?>
                <div class="properties-grid">
                    <?php foreach ($projects as $project): 
                        $images = getProjectImages($project['project_images']);
                        $main_image = !empty($images) ? 'uploads/' . $images[0] : 'assets/images/placeholder.jpg';
                        $price_range = '';
                        if ($project['price_range_min'] && $project['price_range_max']) {
                            $price_range = formatPrice($project['price_range_min']) . ' - ' . formatPrice($project['price_range_max']);
                        } elseif ($project['price_range_min']) {
                            $price_range = 'Starting from ' . formatPrice($project['price_range_min']);
                        }
                    ?>
                        <div class="property-card">
                            <div class="property-image">
                                <img src="<?php echo htmlspecialchars($main_image); ?>" alt="<?php echo htmlspecialchars($project['project_name']); ?>">
                                <span class="property-status"><?php echo ucfirst(str_replace('_', ' ', $project['project_status'])); ?></span>
                                <span class="property-type"><?php echo ucfirst(str_replace('_', ' ', $project['project_type'])); ?></span>
                                <?php if ($project['hot_deal']): ?>
                                    <span style="position: absolute; top: 1rem; left: 1rem; background: #ef4444; color: white; padding: 0.375rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">Hot Deal</span>
                                <?php endif; ?>
                                <?php if ($project['limited_units']): ?>
                                    <span style="position: absolute; top: 3.5rem; left: 1rem; background: #f59e0b; color: white; padding: 0.375rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">Limited Units</span>
                                <?php endif; ?>
                                <?php if ($project['featured_project']): ?>
                                    <span style="position: absolute; top: 1rem; right: 1rem; background: #10b981; color: white; padding: 0.375rem 0.75rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">★ Featured</span>
                                <?php endif; ?>
                            </div>
                            <div class="property-content">
                                <h3><a href="project.php?id=<?php echo $project['id']; ?>"><?php echo htmlspecialchars($project['project_name']); ?></a></h3>
                                <?php if (!empty($project['developer_name'])): ?>
                                    <p style="color: var(--text-light); font-size: 0.875rem; margin-bottom: 0.5rem;">
                                        by <strong><?php echo htmlspecialchars($project['developer_name']); ?></strong>
                                    </p>
                                <?php endif; ?>
                                <p class="property-location">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                    </svg>
                                    <?php echo htmlspecialchars($project['locality'] ?? $project['city'] . ', ' . $project['state']); ?>
                                </p>
                                <?php if (!empty($project['short_description'])): ?>
                                    <p style="color: var(--text-light); font-size: 0.9rem; margin: 1rem 0;">
                                        <?php echo htmlspecialchars(substr($project['short_description'], 0, 100)) . (strlen($project['short_description']) > 100 ? '...' : ''); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($project['rera_number'])): ?>
                                    <p style="font-size: 0.75rem; color: #6b7280; margin: 0.5rem 0;">
                                        RERA: <?php echo htmlspecialchars($project['rera_number']); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($price_range): ?>
                                    <div class="property-price">
                                        <?php echo $price_range; ?>
                                    </div>
                                <?php endif; ?>
                                <a href="project.php?id=<?php echo $project['id']; ?>" class="btn btn-outline">View Project Details</a>
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

