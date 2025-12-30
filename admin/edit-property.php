<?php
require_once '../config.php';
checkAuth();
require_once '../includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$property = getProperty(intval($_GET['id']));
if (!$property) {
    header('Location: index.php');
    exit();
}

$error = '';

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
        'images' => $property['images'],
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
            $existing_images = getPropertyImages($property['images']);
            $all_images = array_merge($existing_images, $uploaded_images);
            $data['images'] = json_encode($all_images);
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
        try {
            if (updateProperty($property['id'], $data)) {
                header('Location: index.php?success=updated');
                exit();
            } else {
                $error = 'Failed to update property. Please try again.';
            }
        } catch (PDOException $e) {
            // Check if error is due to missing column
            if (strpos($e->getMessage(), 'rera_number') !== false || strpos($e->getMessage(), 'Unknown column') !== false) {
                $error = 'Database error: The rera_number column is missing. Please run the migration: database/add_rera_column.sql';
            } else {
                error_log("Update property error: " . $e->getMessage());
                $error = 'An error occurred while updating the property: ' . htmlspecialchars($e->getMessage());
            }
        } catch (Exception $e) {
            error_log("Update property error: " . $e->getMessage());
            $error = 'An error occurred while updating the property. Please check the error logs for details.';
        }
    }
    
    // Reload property data
    $property = getProperty($property['id']);
}

$existing_features = !empty($property['features']) ? json_decode($property['features'], true) : [];
$features_text = is_array($existing_features) ? implode("\n", $existing_features) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/admin-header.php'; ?>
    
    <main class="admin-main">
        <div class="admin-container">
            <div class="admin-header">
                <h1>Edit Property</h1>
                <a href="index.php" class="btn btn-outline">← Back to Properties</a>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="property-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($property['title']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="property_type">Property Type *</label>
                        <select id="property_type" name="property_type" required>
                            <option value="house" <?php echo $property['property_type'] == 'house' ? 'selected' : ''; ?>>House</option>
                            <option value="apartment" <?php echo $property['property_type'] == 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                            <option value="villa" <?php echo $property['property_type'] == 'villa' ? 'selected' : ''; ?>>Villa</option>
                            <option value="commercial" <?php echo $property['property_type'] == 'commercial' ? 'selected' : ''; ?>>Commercial</option>
                            <option value="land" <?php echo $property['property_type'] == 'land' ? 'selected' : ''; ?>>Land</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select id="status" name="status" required>
                            <option value="for_sale" <?php echo $property['status'] == 'for_sale' ? 'selected' : ''; ?>>For Sale</option>
                            <option value="for_rent" <?php echo $property['status'] == 'for_rent' ? 'selected' : ''; ?>>For Rent</option>
                            <option value="sold" <?php echo $property['status'] == 'sold' ? 'selected' : ''; ?>>Sold</option>
                            <option value="rented" <?php echo $property['status'] == 'rented' ? 'selected' : ''; ?>>Rented</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required value="<?php echo $property['price']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="bedrooms">Bedrooms</label>
                        <input type="number" id="bedrooms" name="bedrooms" min="0" value="<?php echo $property['bedrooms'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="bathrooms">Bathrooms</label>
                        <input type="number" id="bathrooms" name="bathrooms" step="0.5" min="0" value="<?php echo $property['bathrooms'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="area">Area</label>
                        <input type="number" id="area" name="area" step="0.01" min="0" value="<?php echo $property['area'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="area_unit">Area Unit</label>
                        <select id="area_unit" name="area_unit">
                            <option value="sqft" <?php echo ($property['area_unit'] ?? 'sqft') == 'sqft' ? 'selected' : ''; ?>>Square Feet (sq ft)</option>
                            <option value="sqm" <?php echo ($property['area_unit'] ?? '') == 'sqm' ? 'selected' : ''; ?>>Square Meters (sq m)</option>
                            <option value="sq_yd" <?php echo ($property['area_unit'] ?? '') == 'sq_yd' ? 'selected' : ''; ?>>Square Yards (sq yd)</option>
                            <option value="acres" <?php echo ($property['area_unit'] ?? '') == 'acres' ? 'selected' : ''; ?>>Acres</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Address *</label>
                    <input type="text" id="address" name="address" required value="<?php echo htmlspecialchars($property['address']); ?>">
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="city">City *</label>
                        <input type="text" id="city" name="city" required value="<?php echo htmlspecialchars($property['city']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($property['state'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="zip_code">Zip Code</label>
                        <input type="text" id="zip_code" name="zip_code" value="<?php echo htmlspecialchars($property['zip_code'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($property['country'] ?? 'India'); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($property['description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="features">Features (one per line)</label>
                    <textarea id="features" name="features" rows="5" placeholder="Swimming Pool&#10;Garage&#10;Garden"><?php echo htmlspecialchars($features_text); ?></textarea>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="year_built">Year Built</label>
                        <input type="number" id="year_built" name="year_built" min="1800" max="<?php echo date('Y'); ?>" value="<?php echo $property['year_built'] ?? ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="parking">Parking Spaces</label>
                        <input type="number" id="parking" name="parking" min="0" value="<?php echo $property['parking'] ?? ''; ?>">
                    </div>
                </div>
                
                <?php 
                $existing_images = getPropertyImages($property['images']);
                if (!empty($existing_images)): 
                ?>
                    <div class="form-group">
                        <label>Current Images</label>
                        <div class="existing-images">
                            <?php foreach ($existing_images as $img): ?>
                                <div class="image-preview">
                                    <img src="../uploads/<?php echo htmlspecialchars($img); ?>" alt="Property image">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="images">Add More Images (optional)</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*">
                    <small>Maximum file size: 5MB per image. Formats: JPG, PNG, GIF, WebP</small>
                </div>
                
                <h3>Agent Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="agent_name">Agent Name</label>
                        <input type="text" id="agent_name" name="agent_name" value="<?php echo htmlspecialchars($property['agent_name'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="agent_phone">Agent Phone</label>
                        <input type="text" id="agent_phone" name="agent_phone" value="<?php echo htmlspecialchars($property['agent_phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="agent_email">Agent Email</label>
                        <input type="email" id="agent_email" name="agent_email" value="<?php echo htmlspecialchars($property['agent_email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="rera_number">RERA Number</label>
                        <input type="text" id="rera_number" name="rera_number" placeholder="e.g., PRM/KA/RERA/1251/310/AG/171113/000290" value="<?php echo htmlspecialchars($property['rera_number'] ?? ''); ?>">
                        <small style="display: block; margin-top: 0.25rem; color: #6b7280; font-size: 0.875rem;">Real Estate Regulatory Authority registration number (optional)</small>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Property</button>
                    <a href="index.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

