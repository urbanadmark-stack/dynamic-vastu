<?php
require_once '../config.php';
checkAuth();
require_once '../includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => sanitize($_POST['title'] ?? ''),
        'description' => sanitize($_POST['description'] ?? ''),
        'property_type' => sanitize($_POST['property_type'] ?? 'house'),
        'status' => sanitize($_POST['status'] ?? 'for_sale'),
        'price' => floatval($_POST['price'] ?? 0),
        'bedrooms' => !empty($_POST['bedrooms']) ? intval($_POST['bedrooms']) : null,
        'bathrooms' => !empty($_POST['bathrooms']) ? floatval($_POST['bathrooms']) : null,
        'area' => !empty($_POST['area']) ? floatval($_POST['area']) : null,
        'area_unit' => sanitize($_POST['area_unit'] ?? 'sqft'),
        'address' => sanitize($_POST['address'] ?? ''),
        'city' => sanitize($_POST['city'] ?? ''),
        'state' => sanitize($_POST['state'] ?? ''),
        'zip_code' => sanitize($_POST['zip_code'] ?? ''),
        'country' => sanitize($_POST['country'] ?? 'India'),
        'images' => '',
        'features' => '',
        'year_built' => !empty($_POST['year_built']) ? intval($_POST['year_built']) : null,
        'parking' => !empty($_POST['parking']) ? intval($_POST['parking']) : null,
        'agent_name' => sanitize($_POST['agent_name'] ?? ''),
        'agent_phone' => sanitize($_POST['agent_phone'] ?? ''),
        'agent_email' => sanitize($_POST['agent_email'] ?? ''),
        'rera_number' => sanitize($_POST['rera_number'] ?? ''),
    ];
    
    // Handle image uploads
    if (!empty($_FILES['images']['name'][0])) {
        $uploaded_images = uploadImages($_FILES['images']);
        if (!empty($uploaded_images)) {
            $data['images'] = json_encode($uploaded_images);
        }
    }
    
    // Handle features
    if (!empty($_POST['features'])) {
        $features_array = array_filter(array_map('trim', explode("\n", $_POST['features'])));
        $data['features'] = json_encode($features_array);
    }
    
    if (empty($data['title']) || empty($data['address']) || empty($data['city']) || $data['price'] <= 0) {
        $error = 'Please fill in all required fields';
    } else {
        if (addProperty($data)) {
            header('Location: index.php?success=added');
            exit();
        } else {
            $error = 'Failed to add property. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/admin-header.php'; ?>
    
    <main class="admin-main">
        <div class="admin-container">
            <div class="admin-header">
                <h1>Add New Property</h1>
                <a href="index.php" class="btn btn-outline">← Back to Properties</a>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="property-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="property_type">Property Type *</label>
                        <select id="property_type" name="property_type" required>
                            <option value="house" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] == 'house') ? 'selected' : ''; ?>>House</option>
                            <option value="apartment" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] == 'apartment') ? 'selected' : ''; ?>>Apartment</option>
                            <option value="villa" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] == 'villa') ? 'selected' : ''; ?>>Villa</option>
                            <option value="commercial" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] == 'commercial') ? 'selected' : ''; ?>>Commercial</option>
                            <option value="land" <?php echo (isset($_POST['property_type']) && $_POST['property_type'] == 'land') ? 'selected' : ''; ?>>Land</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="for_sale" <?php echo (isset($_POST['status']) && $_POST['status'] == 'for_sale') ? 'selected' : ''; ?>>For Sale</option>
                            <option value="for_rent" <?php echo (isset($_POST['status']) && $_POST['status'] == 'for_rent') ? 'selected' : ''; ?>>For Rent</option>
                            <option value="sold" <?php echo (isset($_POST['status']) && $_POST['status'] == 'sold') ? 'selected' : ''; ?>>Sold</option>
                            <option value="rented" <?php echo (isset($_POST['status']) && $_POST['status'] == 'rented') ? 'selected' : ''; ?>>Rented</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="bedrooms">Bedrooms</label>
                        <input type="number" id="bedrooms" name="bedrooms" min="0" value="<?php echo isset($_POST['bedrooms']) ? htmlspecialchars($_POST['bedrooms']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="bathrooms">Bathrooms</label>
                        <input type="number" id="bathrooms" name="bathrooms" step="0.5" min="0" value="<?php echo isset($_POST['bathrooms']) ? htmlspecialchars($_POST['bathrooms']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="area">Area</label>
                        <input type="number" id="area" name="area" step="0.01" min="0" value="<?php echo isset($_POST['area']) ? htmlspecialchars($_POST['area']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="area_unit">Area Unit</label>
                        <select id="area_unit" name="area_unit">
                            <option value="sqft" <?php echo (isset($_POST['area_unit']) && $_POST['area_unit'] == 'sqft') ? 'selected' : 'selected'; ?>>Square Feet (sq ft)</option>
                            <option value="sqm" <?php echo (isset($_POST['area_unit']) && $_POST['area_unit'] == 'sqm') ? 'selected' : ''; ?>>Square Meters (sq m)</option>
                            <option value="sq_yd" <?php echo (isset($_POST['area_unit']) && $_POST['area_unit'] == 'sq_yd') ? 'selected' : ''; ?>>Square Yards (sq yd)</option>
                            <option value="acres" <?php echo (isset($_POST['area_unit']) && $_POST['area_unit'] == 'acres') ? 'selected' : ''; ?>>Acres</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Address *</label>
                    <input type="text" id="address" name="address" required value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" required value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" value="<?php echo isset($_POST['state']) ? htmlspecialchars($_POST['state']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="zip_code">Zip Code</label>
                        <input type="text" id="zip_code" name="zip_code" value="<?php echo isset($_POST['zip_code']) ? htmlspecialchars($_POST['zip_code']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" value="<?php echo isset($_POST['country']) ? htmlspecialchars($_POST['country']) : 'India'; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="5" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="features">Features (one per line)</label>
                    <textarea id="features" name="features" rows="5" placeholder="Swimming Pool&#10;Garage&#10;Garden"><?php echo isset($_POST['features']) ? htmlspecialchars($_POST['features']) : ''; ?></textarea>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="year_built">Year Built</label>
                        <input type="number" id="year_built" name="year_built" min="1800" max="<?php echo date('Y'); ?>" value="<?php echo isset($_POST['year_built']) ? htmlspecialchars($_POST['year_built']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="parking">Parking Spaces</label>
                        <input type="number" id="parking" name="parking" min="0" value="<?php echo isset($_POST['parking']) ? htmlspecialchars($_POST['parking']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="images">Images (multiple allowed)</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*">
                    <small>Maximum file size: 5MB per image. Formats: JPG, PNG, GIF, WebP</small>
                </div>
                
                <h3>Agent Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="agent_name">Agent Name</label>
                        <input type="text" id="agent_name" name="agent_name" value="<?php echo isset($_POST['agent_name']) ? htmlspecialchars($_POST['agent_name']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="agent_phone">Agent Phone</label>
                        <input type="text" id="agent_phone" name="agent_phone" value="<?php echo isset($_POST['agent_phone']) ? htmlspecialchars($_POST['agent_phone']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="agent_email">Agent Email</label>
                        <input type="email" id="agent_email" name="agent_email" value="<?php echo isset($_POST['agent_email']) ? htmlspecialchars($_POST['agent_email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="rera_number">RERA Number</label>
                        <input type="text" id="rera_number" name="rera_number" placeholder="e.g., PRM/KA/RERA/1251/310/AG/171113/000290" value="<?php echo isset($_POST['rera_number']) ? htmlspecialchars($_POST['rera_number']) : ''; ?>">
                        <small style="display: block; margin-top: 0.25rem; color: #6b7280; font-size: 0.875rem;">Real Estate Regulatory Authority registration number (optional)</small>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Property</button>
                    <a href="index.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

