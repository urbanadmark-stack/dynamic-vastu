<?php
require_once '../config.php';
checkAuth();
require_once '../includes/functions.php';

$error = '';
$success = '';

// Indian States list for RERA
$indian_states = [
    'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
    'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand',
    'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur',
    'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab',
    'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
    'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Delhi', 'Jammu and Kashmir',
    'Ladakh', 'Puducherry', 'Andaman and Nicobar', 'Chandigarh', 'Dadra and Nagar Haveli and Daman and Diu',
    'Lakshadweep'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect all form data
    $data = [
        'project_name' => sanitize($_POST['project_name'] ?? ''),
        'project_type' => sanitize($_POST['project_type'] ?? 'residential'),
        'project_status' => sanitize($_POST['project_status'] ?? 'new_launch'),
        'rera_number' => sanitize($_POST['rera_number'] ?? ''),
        'rera_authority_state' => sanitize($_POST['rera_authority_state'] ?? ''),
        'rera_valid_till' => !empty($_POST['rera_valid_till']) ? $_POST['rera_valid_till'] : null,
        'developer_name' => sanitize($_POST['developer_name'] ?? ''),
        'developer_rera_number' => sanitize($_POST['developer_rera_number'] ?? ''),
        'state' => sanitize($_POST['state'] ?? ''),
        'city' => sanitize($_POST['city'] ?? ''),
        'locality' => sanitize($_POST['locality'] ?? ''),
        'address' => sanitize($_POST['address'] ?? ''),
        'landmark' => sanitize($_POST['landmark'] ?? ''),
        'latitude' => !empty($_POST['latitude']) ? floatval($_POST['latitude']) : null,
        'longitude' => !empty($_POST['longitude']) ? floatval($_POST['longitude']) : null,
        'pincode' => sanitize($_POST['pincode'] ?? ''),
        'short_description' => sanitize($_POST['short_description'] ?? ''),
        'long_description' => sanitize($_POST['long_description'] ?? ''),
        'project_highlights' => isset($_POST['project_highlights']) ? $_POST['project_highlights'] : [],
        'unit_configurations' => isset($_POST['unit_types']) ? [] : null,
        'price_range_min' => !empty($_POST['price_range_min']) ? floatval($_POST['price_range_min']) : null,
        'price_range_max' => !empty($_POST['price_range_max']) ? floatval($_POST['price_range_max']) : null,
        'all_inclusive_price' => isset($_POST['all_inclusive_price']) ? 1 : 0,
        'amenities' => isset($_POST['amenities']) ? $_POST['amenities'] : [],
        'structure_type' => sanitize($_POST['structure_type'] ?? ''),
        'flooring' => sanitize($_POST['flooring'] ?? ''),
        'kitchen' => sanitize($_POST['kitchen'] ?? ''),
        'bathroom_fittings' => sanitize($_POST['bathroom_fittings'] ?? ''),
        'doors_windows' => sanitize($_POST['doors_windows'] ?? ''),
        'electrical' => sanitize($_POST['electrical'] ?? ''),
        'land_title_clear' => isset($_POST['land_title_clear']) ? 1 : 0,
        'launch_date' => !empty($_POST['launch_date']) ? $_POST['launch_date'] : null,
        'possession_date' => !empty($_POST['possession_date']) ? $_POST['possession_date'] : null,
        'construction_status' => !empty($_POST['construction_status']) ? intval($_POST['construction_status']) : 0,
        'walkthrough_video' => sanitize($_POST['walkthrough_video'] ?? ''),
        'nearby_locations' => isset($_POST['nearby_location']) ? [] : null,
        'sales_contact_name' => sanitize($_POST['sales_contact_name'] ?? ''),
        'sales_mobile' => sanitize($_POST['sales_mobile'] ?? ''),
        'sales_email' => sanitize($_POST['sales_email'] ?? ''),
        'whatsapp_enabled' => isset($_POST['whatsapp_enabled']) ? 1 : 0,
        'seo_title' => sanitize($_POST['seo_title'] ?? ''),
        'meta_description' => sanitize($_POST['meta_description'] ?? ''),
        'target_keywords' => sanitize($_POST['target_keywords'] ?? ''),
        'schema_markup_enabled' => isset($_POST['schema_markup_enabled']) ? 1 : 0,
        'featured_project' => isset($_POST['featured_project']) ? 1 : 0,
        'hot_deal' => isset($_POST['hot_deal']) ? 1 : 0,
        'limited_units' => isset($_POST['limited_units']) ? 1 : 0,
        'rera_info_verified' => isset($_POST['rera_info_verified']) ? 1 : 0,
        'prices_subject_to_change' => isset($_POST['prices_subject_to_change']) ? 1 : 1,
        'images_representational' => isset($_POST['images_representational']) ? 1 : 1,
    ];
    
    // Handle unit configurations
    if (isset($_POST['unit_types']) && is_array($_POST['unit_types'])) {
        $unit_configs = [];
        foreach ($_POST['unit_types'] as $index => $unit_type) {
            if (!empty($unit_type)) {
                $unit_configs[] = [
                    'unit_type' => $unit_type,
                    'carpet_area' => !empty($_POST['carpet_area'][$index]) ? floatval($_POST['carpet_area'][$index]) : null,
                    'builtup_area' => !empty($_POST['builtup_area'][$index]) ? floatval($_POST['builtup_area'][$index]) : null,
                    'price_starting_from' => !empty($_POST['price_starting_from'][$index]) ? floatval($_POST['price_starting_from'][$index]) : null,
                ];
            }
        }
        $data['unit_configurations'] = $unit_configs;
    }
    
    // Handle nearby locations
    if (isset($_POST['nearby_location']) && is_array($_POST['nearby_location'])) {
        $nearby = [];
        foreach ($_POST['nearby_location'] as $index => $location) {
            if (!empty($location) && !empty($_POST['nearby_distance'][$index])) {
                $nearby[] = [
                    'location' => $location,
                    'distance' => sanitize($_POST['nearby_distance'][$index]),
                ];
            }
        }
        $data['nearby_locations'] = $nearby;
    }
    
    // Handle file uploads
    if (!empty($_FILES['project_images']['name'][0])) {
        $uploaded_images = uploadImages($_FILES['project_images']);
        if (!empty($uploaded_images)) {
            $data['project_images'] = $uploaded_images;
        }
    }
    
    if (!empty($_FILES['rera_certificate']['name'])) {
        $rera_cert = uploadPDF($_FILES['rera_certificate'], 'rera_');
        if ($rera_cert) {
            $data['rera_certificate'] = $rera_cert;
        }
    }
    
    if (!empty($_FILES['brochure_pdf']['name'])) {
        $brochure = uploadPDF($_FILES['brochure_pdf'], 'brochure_');
        if ($brochure) {
            $data['brochure_pdf'] = $brochure;
        }
    }
    
    // Validate required fields
    if (empty($data['project_name']) || empty($data['rera_number']) || empty($data['address']) || empty($data['city']) || empty($data['state'])) {
        $error = 'Please fill all mandatory fields: Project Name, RERA Number, Address, City, and State.';
    } else {
        if (addProject($data)) {
            header('Location: projects.php?success=added');
            exit();
        } else {
            $error = 'Failed to add project. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Project - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .form-section {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .form-section h3 {
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--primary-color);
            color: var(--primary-color);
        }
        .required-field {
            color: #ef4444;
        }
        .form-help {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        .repeatable-item {
            border: 1px solid #e5e7eb;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
            background: #f9fafb;
        }
        .add-item-btn {
            background: #10b981;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            margin-bottom: 1rem;
        }
        .remove-item-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            cursor: pointer;
            float: right;
        }
    </style>
</head>
<body>
    <?php include 'includes/admin-header.php'; ?>
    
    <main class="admin-main">
        <div class="admin-container">
            <div class="admin-header">
                <h1>Add New Project</h1>
                <a href="projects.php" class="btn btn-outline">← Back to Projects</a>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="admin-form">
                
                <!-- Section 1: Basic Project Information -->
                <div class="form-section">
                    <h3>1. Basic Project Information <span class="required-field">*</span></h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="project_name">Project Name <span class="required-field">*</span></label>
                            <input type="text" id="project_name" name="project_name" required
                                   value="<?php echo isset($_POST['project_name']) ? htmlspecialchars($_POST['project_name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="project_type">Project Type <span class="required-field">*</span></label>
                            <select id="project_type" name="project_type" required>
                                <option value="residential" <?php echo (isset($_POST['project_type']) && $_POST['project_type'] == 'residential') ? 'selected' : 'selected'; ?>>Residential</option>
                                <option value="commercial" <?php echo (isset($_POST['project_type']) && $_POST['project_type'] == 'commercial') ? 'selected' : ''; ?>>Commercial</option>
                                <option value="mixed_use" <?php echo (isset($_POST['project_type']) && $_POST['project_type'] == 'mixed_use') ? 'selected' : ''; ?>>Mixed-Use</option>
                                <option value="plotted_development" <?php echo (isset($_POST['project_type']) && $_POST['project_type'] == 'plotted_development') ? 'selected' : ''; ?>>Plotted Development</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="project_status">Project Status <span class="required-field">*</span></label>
                            <select id="project_status" name="project_status" required>
                                <option value="new_launch" <?php echo (isset($_POST['project_status']) && $_POST['project_status'] == 'new_launch') ? 'selected' : 'selected'; ?>>New Launch</option>
                                <option value="under_construction" <?php echo (isset($_POST['project_status']) && $_POST['project_status'] == 'under_construction') ? 'selected' : ''; ?>>Under Construction</option>
                                <option value="ready_to_move" <?php echo (isset($_POST['project_status']) && $_POST['project_status'] == 'ready_to_move') ? 'selected' : ''; ?>>Ready to Move</option>
                                <option value="oc_received" <?php echo (isset($_POST['project_status']) && $_POST['project_status'] == 'oc_received') ? 'selected' : ''; ?>>OC Received</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="rera_number">RERA Registration Number <span class="required-field">*</span></label>
                            <input type="text" id="rera_number" name="rera_number" required
                                   placeholder="e.g., PRM/KA/RERA/1251/310/AG/171113/000290"
                                   value="<?php echo isset($_POST['rera_number']) ? htmlspecialchars($_POST['rera_number']) : ''; ?>">
                            <div class="form-help">Mandatory for all real estate projects in India</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="rera_authority_state">RERA Authority State</label>
                            <select id="rera_authority_state" name="rera_authority_state">
                                <option value="">Select State</option>
                                <?php foreach ($indian_states as $state): ?>
                                    <option value="<?php echo htmlspecialchars($state); ?>" 
                                        <?php echo (isset($_POST['rera_authority_state']) && $_POST['rera_authority_state'] == $state) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($state); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="rera_valid_till">RERA Registration Valid Till</label>
                            <input type="date" id="rera_valid_till" name="rera_valid_till"
                                   value="<?php echo isset($_POST['rera_valid_till']) ? htmlspecialchars($_POST['rera_valid_till']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="developer_name">Developer / Builder Name</label>
                            <input type="text" id="developer_name" name="developer_name"
                                   value="<?php echo isset($_POST['developer_name']) ? htmlspecialchars($_POST['developer_name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="developer_rera_number">Developer RERA Registration Number</label>
                            <input type="text" id="developer_rera_number" name="developer_rera_number"
                                   value="<?php echo isset($_POST['developer_rera_number']) ? htmlspecialchars($_POST['developer_rera_number']) : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Section 2: Location Details -->
                <div class="form-section">
                    <h3>2. Location Details <span class="required-field">*</span></h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="state">State <span class="required-field">*</span></label>
                            <select id="state" name="state" required>
                                <option value="">Select State</option>
                                <?php foreach ($indian_states as $state): ?>
                                    <option value="<?php echo htmlspecialchars($state); ?>" 
                                        <?php echo (isset($_POST['state']) && $_POST['state'] == $state) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($state); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="city">City <span class="required-field">*</span></label>
                            <input type="text" id="city" name="city" required
                                   value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="locality">Locality / Area</label>
                            <input type="text" id="locality" name="locality"
                                   value="<?php echo isset($_POST['locality']) ? htmlspecialchars($_POST['locality']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Exact Address <span class="required-field">*</span></label>
                        <textarea id="address" name="address" rows="3" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="landmark">Landmark</label>
                            <input type="text" id="landmark" name="landmark"
                                   value="<?php echo isset($_POST['landmark']) ? htmlspecialchars($_POST['landmark']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="pincode">Pincode</label>
                            <input type="text" id="pincode" name="pincode" maxlength="6" pattern="[0-9]{6}"
                                   value="<?php echo isset($_POST['pincode']) ? htmlspecialchars($_POST['pincode']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="latitude">Latitude (for Google Maps)</label>
                            <input type="text" id="latitude" name="latitude" step="any"
                                   value="<?php echo isset($_POST['latitude']) ? htmlspecialchars($_POST['latitude']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="longitude">Longitude (for Google Maps)</label>
                            <input type="text" id="longitude" name="longitude" step="any"
                                   value="<?php echo isset($_POST['longitude']) ? htmlspecialchars($_POST['longitude']) : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Section 3: Project Overview -->
                <div class="form-section">
                    <h3>3. Project Overview</h3>
                    
                    <div class="form-group">
                        <label for="short_description">Short Description (150-200 words)</label>
                        <textarea id="short_description" name="short_description" rows="4" 
                                  placeholder="SEO-friendly short description of the project"><?php echo isset($_POST['short_description']) ? htmlspecialchars($_POST['short_description']) : ''; ?></textarea>
                        <div class="form-help">Keep it concise, highlight key features, and include important keywords for SEO</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="long_description">Long Description</label>
                        <textarea id="long_description" name="long_description" rows="8"
                                  placeholder="Detailed description including highlights, lifestyle, and investment angle"><?php echo isset($_POST['long_description']) ? htmlspecialchars($_POST['long_description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Project Highlights</label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.5rem;">
                            <?php 
                            $highlights = ['Gated Community', 'High-rise Tower', 'Podium Amenities', 'Temple / Clubhouse', 'Smart Homes', 'Green Building'];
                            foreach ($highlights as $highlight): 
                            ?>
                                <label style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="checkbox" name="project_highlights[]" value="<?php echo htmlspecialchars($highlight); ?>"
                                        <?php echo (isset($_POST['project_highlights']) && in_array($highlight, $_POST['project_highlights'])) ? 'checked' : ''; ?>>
                                    <?php echo htmlspecialchars($highlight); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Section 4: Unit Configurations -->
                <div class="form-section">
                    <h3>4. Unit Configurations</h3>
                    <div id="unit-configs">
                        <div class="repeatable-item">
                            <button type="button" class="remove-item-btn" onclick="this.parentElement.remove()">Remove</button>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Unit Type</label>
                                    <select name="unit_types[]">
                                        <option value="">Select</option>
                                        <option value="1 BHK">1 BHK</option>
                                        <option value="2 BHK">2 BHK</option>
                                        <option value="3 BHK">3 BHK</option>
                                        <option value="4 BHK">4 BHK</option>
                                        <option value="5 BHK">5 BHK</option>
                                        <option value="Jodi">Jodi</option>
                                        <option value="Shops">Shops</option>
                                        <option value="Offices">Offices</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Carpet Area (sq ft)</label>
                                    <input type="number" name="carpet_area[]" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label>Built-up Area (sq ft) - Optional</label>
                                    <input type="number" name="builtup_area[]" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label>Price Starting From (₹)</label>
                                    <input type="number" name="price_starting_from[]" step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="add-item-btn" onclick="addUnitConfig()">+ Add Unit Type</button>
                </div>
                
                <!-- Section 5: Pricing -->
                <div class="form-section">
                    <h3>5. Pricing & Payment</h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="price_range_min">Price Range - Minimum (₹)</label>
                            <input type="number" id="price_range_min" name="price_range_min" step="0.01"
                                   value="<?php echo isset($_POST['price_range_min']) ? htmlspecialchars($_POST['price_range_min']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="price_range_max">Price Range - Maximum (₹)</label>
                            <input type="number" id="price_range_max" name="price_range_max" step="0.01"
                                   value="<?php echo isset($_POST['price_range_max']) ? htmlspecialchars($_POST['price_range_max']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="all_inclusive_price" value="1"
                                <?php echo (isset($_POST['all_inclusive_price'])) ? 'checked' : ''; ?>>
                            All Inclusive Price
                        </label>
                    </div>
                </div>
                
                <!-- Section 6: Amenities -->
                <div class="form-section">
                    <h3>6. Amenities</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.5rem;">
                        <?php 
                        $amenities_list = ['Lift', 'Power Backup', 'Parking', 'Gym', 'Garden', 'Children Play Area', 
                                          'Swimming Pool', 'Security', 'CCTV', 'Clubhouse', 'Sports Facilities', 'Medical Facility'];
                        foreach ($amenities_list as $amenity): 
                        ?>
                            <label style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" name="amenities[]" value="<?php echo htmlspecialchars($amenity); ?>"
                                    <?php echo (isset($_POST['amenities']) && in_array($amenity, $_POST['amenities'])) ? 'checked' : ''; ?>>
                                <?php echo htmlspecialchars($amenity); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Section 7: Specifications -->
                <div class="form-section">
                    <h3>7. Project Specifications</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="structure_type">Structure Type</label>
                            <input type="text" id="structure_type" name="structure_type"
                                   value="<?php echo isset($_POST['structure_type']) ? htmlspecialchars($_POST['structure_type']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="flooring">Flooring</label>
                            <input type="text" id="flooring" name="flooring"
                                   value="<?php echo isset($_POST['flooring']) ? htmlspecialchars($_POST['flooring']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="kitchen">Kitchen</label>
                            <input type="text" id="kitchen" name="kitchen"
                                   value="<?php echo isset($_POST['kitchen']) ? htmlspecialchars($_POST['kitchen']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="bathroom_fittings">Bathroom Fittings</label>
                            <input type="text" id="bathroom_fittings" name="bathroom_fittings"
                                   value="<?php echo isset($_POST['bathroom_fittings']) ? htmlspecialchars($_POST['bathroom_fittings']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="doors_windows">Doors & Windows</label>
                            <input type="text" id="doors_windows" name="doors_windows"
                                   value="<?php echo isset($_POST['doors_windows']) ? htmlspecialchars($_POST['doors_windows']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="electrical">Electrical</label>
                            <input type="text" id="electrical" name="electrical"
                                   value="<?php echo isset($_POST['electrical']) ? htmlspecialchars($_POST['electrical']) : ''; ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Section 8: Legal & Compliance -->
                <div class="form-section">
                    <h3>8. Legal & Compliance</h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="rera_certificate">RERA Certificate (PDF)</label>
                            <input type="file" id="rera_certificate" name="rera_certificate" accept=".pdf">
                        </div>
                        
                        <div class="form-group">
                            <label for="brochure_pdf">Brochure PDF</label>
                            <input type="file" id="brochure_pdf" name="brochure_pdf" accept=".pdf">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="land_title_clear" value="1"
                                <?php echo (isset($_POST['land_title_clear'])) ? 'checked' : ''; ?>>
                            Land Title Clear
                        </label>
                    </div>
                </div>
                
                <!-- Section 9: Construction Timeline -->
                <div class="form-section">
                    <h3>9. Construction Timeline</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="launch_date">Project Launch Date</label>
                            <input type="date" id="launch_date" name="launch_date"
                                   value="<?php echo isset($_POST['launch_date']) ? htmlspecialchars($_POST['launch_date']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="possession_date">Possession Date (As per RERA)</label>
                            <input type="date" id="possession_date" name="possession_date"
                                   value="<?php echo isset($_POST['possession_date']) ? htmlspecialchars($_POST['possession_date']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="construction_status">Construction Status (%)</label>
                            <input type="number" id="construction_status" name="construction_status" min="0" max="100"
                                   value="<?php echo isset($_POST['construction_status']) ? htmlspecialchars($_POST['construction_status']) : '0'; ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Section 10: Media -->
                <div class="form-section">
                    <h3>10. Media Uploads</h3>
                    
                    <div class="form-group">
                        <label for="project_images">Project Images (Multiple)</label>
                        <input type="file" id="project_images" name="project_images[]" multiple accept="image/*">
                        <div class="form-help">You can select multiple images</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="walkthrough_video">Walkthrough Video (YouTube Link)</label>
                        <input type="url" id="walkthrough_video" name="walkthrough_video"
                               placeholder="https://www.youtube.com/watch?v=..."
                               value="<?php echo isset($_POST['walkthrough_video']) ? htmlspecialchars($_POST['walkthrough_video']) : ''; ?>">
                    </div>
                </div>
                
                <!-- Section 11: Nearby Locations -->
                <div class="form-section">
                    <h3>11. Connectivity & Nearby</h3>
                    <div id="nearby-locations">
                        <div class="repeatable-item">
                            <button type="button" class="remove-item-btn" onclick="this.parentElement.remove()">Remove</button>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Location Type</label>
                                    <input type="text" name="nearby_location[]" placeholder="e.g., Railway Station, Metro, School">
                                </div>
                                <div class="form-group">
                                    <label>Distance</label>
                                    <input type="text" name="nearby_distance[]" placeholder="e.g., 2 km or 10 mins">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="add-item-btn" onclick="addNearbyLocation()">+ Add Nearby Location</button>
                </div>
                
                <!-- Section 12: Contact -->
                <div class="form-section">
                    <h3>12. Contact & Lead Routing</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="sales_contact_name">Sales Contact Name</label>
                            <input type="text" id="sales_contact_name" name="sales_contact_name"
                                   value="<?php echo isset($_POST['sales_contact_name']) ? htmlspecialchars($_POST['sales_contact_name']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="sales_mobile">Mobile Number</label>
                            <input type="tel" id="sales_mobile" name="sales_mobile"
                                   value="<?php echo isset($_POST['sales_mobile']) ? htmlspecialchars($_POST['sales_mobile']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="sales_email">Email ID</label>
                            <input type="email" id="sales_email" name="sales_email"
                                   value="<?php echo isset($_POST['sales_email']) ? htmlspecialchars($_POST['sales_email']) : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="whatsapp_enabled" value="1"
                                <?php echo (isset($_POST['whatsapp_enabled'])) ? 'checked' : ''; ?>>
                            Enable WhatsApp Click-to-Chat
                        </label>
                    </div>
                </div>
                
                <!-- Section 13: SEO -->
                <div class="form-section">
                    <h3>13. SEO & Marketing (Admin Only)</h3>
                    <div class="form-group">
                        <label for="seo_title">SEO Title</label>
                        <input type="text" id="seo_title" name="seo_title"
                               value="<?php echo isset($_POST['seo_title']) ? htmlspecialchars($_POST['seo_title']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" rows="3"><?php echo isset($_POST['meta_description']) ? htmlspecialchars($_POST['meta_description']) : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="target_keywords">Target Keywords (comma separated)</label>
                        <input type="text" id="target_keywords" name="target_keywords"
                               value="<?php echo isset($_POST['target_keywords']) ? htmlspecialchars($_POST['target_keywords']) : ''; ?>">
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="featured_project" value="1"
                                <?php echo (isset($_POST['featured_project'])) ? 'checked' : ''; ?>>
                            Featured Project
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="hot_deal" value="1"
                                <?php echo (isset($_POST['hot_deal'])) ? 'checked' : ''; ?>>
                            Hot Deal
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="limited_units" value="1"
                                <?php echo (isset($_POST['limited_units'])) ? 'checked' : ''; ?>>
                            Limited Units Badge
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="schema_markup_enabled" value="1"
                                <?php echo (isset($_POST['schema_markup_enabled'])) ? 'checked' : ''; ?>>
                            Enable Schema Markup
                        </label>
                    </div>
                </div>
                
                <!-- Section 14: Compliance Declarations -->
                <div class="form-section">
                    <h3>14. Compliance Declarations</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="rera_info_verified" value="1"
                                <?php echo (isset($_POST['rera_info_verified'])) ? 'checked' : ''; ?>>
                            Information is true as per RERA
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="prices_subject_to_change" value="1"
                                <?php echo (!isset($_POST['prices_subject_to_change']) || $_POST['prices_subject_to_change']) ? 'checked' : ''; ?>>
                            Prices subject to change
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" name="images_representational" value="1"
                                <?php echo (!isset($_POST['images_representational']) || $_POST['images_representational']) ? 'checked' : ''; ?>>
                            Images for representation purpose only
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Project</button>
                    <a href="projects.php" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </main>
    
    <script>
        function addUnitConfig() {
            const container = document.getElementById('unit-configs');
            const item = document.createElement('div');
            item.className = 'repeatable-item';
            item.innerHTML = `
                <button type="button" class="remove-item-btn" onclick="this.parentElement.remove()">Remove</button>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Unit Type</label>
                        <select name="unit_types[]">
                            <option value="">Select</option>
                            <option value="1 BHK">1 BHK</option>
                            <option value="2 BHK">2 BHK</option>
                            <option value="3 BHK">3 BHK</option>
                            <option value="4 BHK">4 BHK</option>
                            <option value="5 BHK">5 BHK</option>
                            <option value="Jodi">Jodi</option>
                            <option value="Shops">Shops</option>
                            <option value="Offices">Offices</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Carpet Area (sq ft)</label>
                        <input type="number" name="carpet_area[]" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Built-up Area (sq ft) - Optional</label>
                        <input type="number" name="builtup_area[]" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Price Starting From (₹)</label>
                        <input type="number" name="price_starting_from[]" step="0.01">
                    </div>
                </div>
            `;
            container.appendChild(item);
        }
        
        function addNearbyLocation() {
            const container = document.getElementById('nearby-locations');
            const item = document.createElement('div');
            item.className = 'repeatable-item';
            item.innerHTML = `
                <button type="button" class="remove-item-btn" onclick="this.parentElement.remove()">Remove</button>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Location Type</label>
                        <input type="text" name="nearby_location[]" placeholder="e.g., Railway Station, Metro, School">
                    </div>
                    <div class="form-group">
                        <label>Distance</label>
                        <input type="text" name="nearby_distance[]" placeholder="e.g., 2 km or 10 mins">
                    </div>
                </div>
            `;
            container.appendChild(item);
        }
    </script>
</body>
</html>

