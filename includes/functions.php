<?php
require_once __DIR__ . '/../config.php';

// Get all properties with optional filters
function getProperties($filters = []) {
    $db = getDB();
    $sql = "SELECT * FROM properties WHERE 1=1";
    $params = [];
    
    if (!empty($filters['property_type'])) {
        $sql .= " AND property_type = :property_type";
        $params[':property_type'] = $filters['property_type'];
    }
    
    if (!empty($filters['status'])) {
        $sql .= " AND status = :status";
        $params[':status'] = $filters['status'];
    }
    
    if (!empty($filters['city'])) {
        $sql .= " AND city LIKE :city";
        $params[':city'] = '%' . $filters['city'] . '%';
    }
    
    if (!empty($filters['min_price'])) {
        $sql .= " AND price >= :min_price";
        $params[':min_price'] = $filters['min_price'];
    }
    
    if (!empty($filters['max_price'])) {
        $sql .= " AND price <= :max_price";
        $params[':max_price'] = $filters['max_price'];
    }
    
    if (!empty($filters['bedrooms'])) {
        $sql .= " AND bedrooms >= :bedrooms";
        $params[':bedrooms'] = $filters['bedrooms'];
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $limit = null;
    if (!empty($filters['limit'])) {
        $limit = intval($filters['limit']);
        $sql .= " LIMIT " . $limit;
    }
    
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    
    return $stmt->fetchAll();
}

// Get single property by ID
function getProperty($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM properties WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

// Get featured properties
function getFeaturedProperties($limit = 6) {
    return getProperties(['status' => 'for_sale', 'limit' => $limit]);
}

// Add new property
function addProperty($data) {
    $db = getDB();
    $sql = "INSERT INTO properties (title, description, property_type, status, price, bedrooms, bathrooms, area, area_unit, address, city, state, zip_code, country, images, features, year_built, parking, agent_name, agent_phone, agent_email, rera_number) 
            VALUES (:title, :description, :property_type, :status, :price, :bedrooms, :bathrooms, :area, :area_unit, :address, :city, :state, :zip_code, :country, :images, :features, :year_built, :parking, :agent_name, :agent_phone, :agent_email, :rera_number)";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute([
        ':title' => $data['title'],
        ':description' => $data['description'],
        ':property_type' => $data['property_type'],
        ':status' => $data['status'],
        ':price' => $data['price'],
        ':bedrooms' => $data['bedrooms'] ?? null,
        ':bathrooms' => $data['bathrooms'] ?? null,
        ':area' => $data['area'] ?? null,
        ':area_unit' => $data['area_unit'] ?? 'sqft',
        ':address' => $data['address'],
        ':city' => $data['city'],
        ':state' => $data['state'] ?? null,
        ':zip_code' => $data['zip_code'] ?? null,
        ':country' => $data['country'] ?? 'India',
        ':images' => $data['images'] ?? null,
        ':features' => $data['features'] ?? null,
        ':year_built' => $data['year_built'] ?? null,
        ':parking' => $data['parking'] ?? null,
        ':agent_name' => $data['agent_name'] ?? null,
        ':agent_phone' => $data['agent_phone'] ?? null,
        ':agent_email' => $data['agent_email'] ?? null,
        ':rera_number' => $data['rera_number'] ?? null,
    ]);
}

// Update property
function updateProperty($id, $data) {
    try {
        $db = getDB();
        $sql = "UPDATE properties SET 
                title = :title, description = :description, property_type = :property_type, 
                status = :status, price = :price, bedrooms = :bedrooms, bathrooms = :bathrooms, 
                area = :area, area_unit = :area_unit, address = :address, city = :city, 
                state = :state, zip_code = :zip_code, country = :country, images = :images, 
                features = :features, year_built = :year_built, parking = :parking, 
                agent_name = :agent_name, agent_phone = :agent_phone, agent_email = :agent_email, 
                rera_number = :rera_number 
                WHERE id = :id";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':property_type' => $data['property_type'],
            ':status' => $data['status'],
            ':price' => $data['price'],
            ':bedrooms' => $data['bedrooms'] ?? null,
            ':bathrooms' => $data['bathrooms'] ?? null,
            ':area' => $data['area'] ?? null,
            ':area_unit' => $data['area_unit'] ?? 'sqft',
            ':address' => $data['address'],
            ':city' => $data['city'],
            ':state' => $data['state'] ?? null,
            ':zip_code' => $data['zip_code'] ?? null,
            ':country' => $data['country'] ?? 'India',
            ':images' => $data['images'] ?? null,
            ':features' => $data['features'] ?? null,
            ':year_built' => $data['year_built'] ?? null,
            ':parking' => $data['parking'] ?? null,
            ':agent_name' => $data['agent_name'] ?? null,
            ':agent_phone' => $data['agent_phone'] ?? null,
            ':agent_email' => $data['agent_email'] ?? null,
            ':rera_number' => $data['rera_number'] ?? null,
        ]);
    } catch (PDOException $e) {
        error_log("Update property error: " . $e->getMessage());
        return false;
    }
}

// Delete property
function deleteProperty($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM properties WHERE id = :id");
    return $stmt->execute([':id' => $id]);
}

// Handle image upload
function uploadImages($files) {
    $uploaded = [];
    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }
    
    foreach ($files['tmp_name'] as $key => $tmp_name) {
        if ($files['error'][$key] === UPLOAD_ERR_OK) {
            $file_size = $files['size'][$key];
            if ($file_size > MAX_IMAGE_SIZE) {
                continue;
            }
            
            $file_ext = strtolower(pathinfo($files['name'][$key], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($file_ext, $allowed)) {
                continue;
            }
            
            $new_name = uniqid('prop_', true) . '.' . $file_ext;
            $dest = UPLOAD_DIR . $new_name;
            
            if (move_uploaded_file($tmp_name, $dest)) {
                $uploaded[] = $new_name;
            }
        }
    }
    
    return $uploaded;
}

// Get property images array
function getPropertyImages($images_json) {
    if (empty($images_json)) {
        return [];
    }
    $images = json_decode($images_json, true);
    return is_array($images) ? $images : [];
}

// ============================================
// PROJECTS FUNCTIONS
// ============================================

// Get all projects with optional filters
function getProjects($filters = []) {
    $db = getDB();
    $sql = "SELECT * FROM projects WHERE 1=1";
    $params = [];
    
    if (!empty($filters['project_type'])) {
        $sql .= " AND project_type = :project_type";
        $params[':project_type'] = $filters['project_type'];
    }
    
    if (!empty($filters['project_status'])) {
        $sql .= " AND project_status = :project_status";
        $params[':project_status'] = $filters['project_status'];
    }
    
    if (!empty($filters['city'])) {
        $sql .= " AND city LIKE :city";
        $params[':city'] = '%' . $filters['city'] . '%';
    }
    
    if (!empty($filters['state'])) {
        $sql .= " AND state LIKE :state";
        $params[':state'] = '%' . $filters['state'] . '%';
    }
    
    if (!empty($filters['featured'])) {
        $sql .= " AND featured_project = 1";
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    if (!empty($filters['limit'])) {
        $limit = intval($filters['limit']);
        $sql .= " LIMIT " . $limit;
    }
    
    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    
    return $stmt->fetchAll();
}

// Get single project by ID
function getProject($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

// Get featured projects
function getFeaturedProjects($limit = 6) {
    return getProjects(['featured' => true, 'limit' => $limit]);
}

// Add new project
function addProject($data) {
    $db = getDB();
    $sql = "INSERT INTO projects (
        project_name, project_type, project_status, rera_number, rera_authority_state, rera_valid_till,
        developer_name, developer_rera_number, state, city, locality, address, landmark, latitude, longitude, pincode,
        short_description, long_description, project_highlights, unit_configurations,
        price_range_min, price_range_max, all_inclusive_price, price_breakup, payment_plans,
        amenities, structure_type, flooring, kitchen, bathroom_fittings, doors_windows, electrical,
        rera_certificate, approved_plans, commencement_certificate, occupation_certificate, land_title_clear,
        launch_date, possession_date, construction_status,
        project_images, renders_3d, walkthrough_video, brochure_pdf, nearby_locations,
        sales_contact_name, sales_mobile, sales_email, whatsapp_enabled, assigned_agent_id,
        seo_title, meta_description, target_keywords, schema_markup_enabled,
        featured_project, hot_deal, limited_units,
        rera_info_verified, prices_subject_to_change, images_representational
    ) VALUES (
        :project_name, :project_type, :project_status, :rera_number, :rera_authority_state, :rera_valid_till,
        :developer_name, :developer_rera_number, :state, :city, :locality, :address, :landmark, :latitude, :longitude, :pincode,
        :short_description, :long_description, :project_highlights, :unit_configurations,
        :price_range_min, :price_range_max, :all_inclusive_price, :price_breakup, :payment_plans,
        :amenities, :structure_type, :flooring, :kitchen, :bathroom_fittings, :doors_windows, :electrical,
        :rera_certificate, :approved_plans, :commencement_certificate, :occupation_certificate, :land_title_clear,
        :launch_date, :possession_date, :construction_status,
        :project_images, :renders_3d, :walkthrough_video, :brochure_pdf, :nearby_locations,
        :sales_contact_name, :sales_mobile, :sales_email, :whatsapp_enabled, :assigned_agent_id,
        :seo_title, :meta_description, :target_keywords, :schema_markup_enabled,
        :featured_project, :hot_deal, :limited_units,
        :rera_info_verified, :prices_subject_to_change, :images_representational
    )";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute([
        ':project_name' => $data['project_name'] ?? '',
        ':project_type' => $data['project_type'] ?? 'residential',
        ':project_status' => $data['project_status'] ?? 'new_launch',
        ':rera_number' => $data['rera_number'] ?? '',
        ':rera_authority_state' => $data['rera_authority_state'] ?? null,
        ':rera_valid_till' => !empty($data['rera_valid_till']) ? $data['rera_valid_till'] : null,
        ':developer_name' => $data['developer_name'] ?? null,
        ':developer_rera_number' => $data['developer_rera_number'] ?? null,
        ':state' => $data['state'] ?? '',
        ':city' => $data['city'] ?? '',
        ':locality' => $data['locality'] ?? null,
        ':address' => $data['address'] ?? '',
        ':landmark' => $data['landmark'] ?? null,
        ':latitude' => !empty($data['latitude']) ? $data['latitude'] : null,
        ':longitude' => !empty($data['longitude']) ? $data['longitude'] : null,
        ':pincode' => $data['pincode'] ?? null,
        ':short_description' => $data['short_description'] ?? null,
        ':long_description' => $data['long_description'] ?? null,
        ':project_highlights' => !empty($data['project_highlights']) ? json_encode($data['project_highlights']) : null,
        ':unit_configurations' => !empty($data['unit_configurations']) ? json_encode($data['unit_configurations']) : null,
        ':price_range_min' => !empty($data['price_range_min']) ? $data['price_range_min'] : null,
        ':price_range_max' => !empty($data['price_range_max']) ? $data['price_range_max'] : null,
        ':all_inclusive_price' => isset($data['all_inclusive_price']) ? (int)$data['all_inclusive_price'] : 0,
        ':price_breakup' => !empty($data['price_breakup']) ? json_encode($data['price_breakup']) : null,
        ':payment_plans' => !empty($data['payment_plans']) ? json_encode($data['payment_plans']) : null,
        ':amenities' => !empty($data['amenities']) ? json_encode($data['amenities']) : null,
        ':structure_type' => $data['structure_type'] ?? null,
        ':flooring' => $data['flooring'] ?? null,
        ':kitchen' => $data['kitchen'] ?? null,
        ':bathroom_fittings' => $data['bathroom_fittings'] ?? null,
        ':doors_windows' => $data['doors_windows'] ?? null,
        ':electrical' => $data['electrical'] ?? null,
        ':rera_certificate' => $data['rera_certificate'] ?? null,
        ':approved_plans' => $data['approved_plans'] ?? null,
        ':commencement_certificate' => $data['commencement_certificate'] ?? null,
        ':occupation_certificate' => $data['occupation_certificate'] ?? null,
        ':land_title_clear' => isset($data['land_title_clear']) ? (int)$data['land_title_clear'] : 0,
        ':launch_date' => !empty($data['launch_date']) ? $data['launch_date'] : null,
        ':possession_date' => !empty($data['possession_date']) ? $data['possession_date'] : null,
        ':construction_status' => !empty($data['construction_status']) ? (int)$data['construction_status'] : 0,
        ':project_images' => !empty($data['project_images']) ? json_encode($data['project_images']) : null,
        ':renders_3d' => !empty($data['renders_3d']) ? json_encode($data['renders_3d']) : null,
        ':walkthrough_video' => $data['walkthrough_video'] ?? null,
        ':brochure_pdf' => $data['brochure_pdf'] ?? null,
        ':nearby_locations' => !empty($data['nearby_locations']) ? json_encode($data['nearby_locations']) : null,
        ':sales_contact_name' => $data['sales_contact_name'] ?? null,
        ':sales_mobile' => $data['sales_mobile'] ?? null,
        ':sales_email' => $data['sales_email'] ?? null,
        ':whatsapp_enabled' => isset($data['whatsapp_enabled']) ? (int)$data['whatsapp_enabled'] : 0,
        ':assigned_agent_id' => !empty($data['assigned_agent_id']) ? (int)$data['assigned_agent_id'] : null,
        ':seo_title' => $data['seo_title'] ?? null,
        ':meta_description' => $data['meta_description'] ?? null,
        ':target_keywords' => $data['target_keywords'] ?? null,
        ':schema_markup_enabled' => isset($data['schema_markup_enabled']) ? (int)$data['schema_markup_enabled'] : 0,
        ':featured_project' => isset($data['featured_project']) ? (int)$data['featured_project'] : 0,
        ':hot_deal' => isset($data['hot_deal']) ? (int)$data['hot_deal'] : 0,
        ':limited_units' => isset($data['limited_units']) ? (int)$data['limited_units'] : 0,
        ':rera_info_verified' => isset($data['rera_info_verified']) ? (int)$data['rera_info_verified'] : 0,
        ':prices_subject_to_change' => isset($data['prices_subject_to_change']) ? (int)$data['prices_subject_to_change'] : 1,
        ':images_representational' => isset($data['images_representational']) ? (int)$data['images_representational'] : 1,
    ]);
}

// Update project
function updateProject($id, $data) {
    $db = getDB();
    $sql = "UPDATE projects SET 
        project_name = :project_name, project_type = :project_type, project_status = :project_status,
        rera_number = :rera_number, rera_authority_state = :rera_authority_state, rera_valid_till = :rera_valid_till,
        developer_name = :developer_name, developer_rera_number = :developer_rera_number,
        state = :state, city = :city, locality = :locality, address = :address, landmark = :landmark,
        latitude = :latitude, longitude = :longitude, pincode = :pincode,
        short_description = :short_description, long_description = :long_description,
        project_highlights = :project_highlights, unit_configurations = :unit_configurations,
        price_range_min = :price_range_min, price_range_max = :price_range_max,
        all_inclusive_price = :all_inclusive_price, price_breakup = :price_breakup, payment_plans = :payment_plans,
        amenities = :amenities, structure_type = :structure_type, flooring = :flooring,
        kitchen = :kitchen, bathroom_fittings = :bathroom_fittings, doors_windows = :doors_windows, electrical = :electrical,
        rera_certificate = :rera_certificate, approved_plans = :approved_plans,
        commencement_certificate = :commencement_certificate, occupation_certificate = :occupation_certificate,
        land_title_clear = :land_title_clear, launch_date = :launch_date, possession_date = :possession_date,
        construction_status = :construction_status, project_images = :project_images, renders_3d = :renders_3d,
        walkthrough_video = :walkthrough_video, brochure_pdf = :brochure_pdf, nearby_locations = :nearby_locations,
        sales_contact_name = :sales_contact_name, sales_mobile = :sales_mobile, sales_email = :sales_email,
        whatsapp_enabled = :whatsapp_enabled, assigned_agent_id = :assigned_agent_id,
        seo_title = :seo_title, meta_description = :meta_description, target_keywords = :target_keywords,
        schema_markup_enabled = :schema_markup_enabled, featured_project = :featured_project,
        hot_deal = :hot_deal, limited_units = :limited_units,
        rera_info_verified = :rera_info_verified, prices_subject_to_change = :prices_subject_to_change,
        images_representational = :images_representational
        WHERE id = :id";
    
    $stmt = $db->prepare($sql);
    $params = [
        ':id' => $id,
        ':project_name' => $data['project_name'] ?? '',
        ':project_type' => $data['project_type'] ?? 'residential',
        ':project_status' => $data['project_status'] ?? 'new_launch',
        ':rera_number' => $data['rera_number'] ?? '',
        ':rera_authority_state' => $data['rera_authority_state'] ?? null,
        ':rera_valid_till' => !empty($data['rera_valid_till']) ? $data['rera_valid_till'] : null,
        ':developer_name' => $data['developer_name'] ?? null,
        ':developer_rera_number' => $data['developer_rera_number'] ?? null,
        ':state' => $data['state'] ?? '',
        ':city' => $data['city'] ?? '',
        ':locality' => $data['locality'] ?? null,
        ':address' => $data['address'] ?? '',
        ':landmark' => $data['landmark'] ?? null,
        ':latitude' => !empty($data['latitude']) ? $data['latitude'] : null,
        ':longitude' => !empty($data['longitude']) ? $data['longitude'] : null,
        ':pincode' => $data['pincode'] ?? null,
        ':short_description' => $data['short_description'] ?? null,
        ':long_description' => $data['long_description'] ?? null,
        ':project_highlights' => !empty($data['project_highlights']) ? json_encode($data['project_highlights']) : null,
        ':unit_configurations' => !empty($data['unit_configurations']) ? json_encode($data['unit_configurations']) : null,
        ':price_range_min' => !empty($data['price_range_min']) ? $data['price_range_min'] : null,
        ':price_range_max' => !empty($data['price_range_max']) ? $data['price_range_max'] : null,
        ':all_inclusive_price' => isset($data['all_inclusive_price']) ? (int)$data['all_inclusive_price'] : 0,
        ':price_breakup' => !empty($data['price_breakup']) ? json_encode($data['price_breakup']) : null,
        ':payment_plans' => !empty($data['payment_plans']) ? json_encode($data['payment_plans']) : null,
        ':amenities' => !empty($data['amenities']) ? json_encode($data['amenities']) : null,
        ':structure_type' => $data['structure_type'] ?? null,
        ':flooring' => $data['flooring'] ?? null,
        ':kitchen' => $data['kitchen'] ?? null,
        ':bathroom_fittings' => $data['bathroom_fittings'] ?? null,
        ':doors_windows' => $data['doors_windows'] ?? null,
        ':electrical' => $data['electrical'] ?? null,
        ':rera_certificate' => $data['rera_certificate'] ?? null,
        ':approved_plans' => $data['approved_plans'] ?? null,
        ':commencement_certificate' => $data['commencement_certificate'] ?? null,
        ':occupation_certificate' => $data['occupation_certificate'] ?? null,
        ':land_title_clear' => isset($data['land_title_clear']) ? (int)$data['land_title_clear'] : 0,
        ':launch_date' => !empty($data['launch_date']) ? $data['launch_date'] : null,
        ':possession_date' => !empty($data['possession_date']) ? $data['possession_date'] : null,
        ':construction_status' => !empty($data['construction_status']) ? (int)$data['construction_status'] : 0,
        ':project_images' => !empty($data['project_images']) ? json_encode($data['project_images']) : null,
        ':renders_3d' => !empty($data['renders_3d']) ? json_encode($data['renders_3d']) : null,
        ':walkthrough_video' => $data['walkthrough_video'] ?? null,
        ':brochure_pdf' => $data['brochure_pdf'] ?? null,
        ':nearby_locations' => !empty($data['nearby_locations']) ? json_encode($data['nearby_locations']) : null,
        ':sales_contact_name' => $data['sales_contact_name'] ?? null,
        ':sales_mobile' => $data['sales_mobile'] ?? null,
        ':sales_email' => $data['sales_email'] ?? null,
        ':whatsapp_enabled' => isset($data['whatsapp_enabled']) ? (int)$data['whatsapp_enabled'] : 0,
        ':assigned_agent_id' => !empty($data['assigned_agent_id']) ? (int)$data['assigned_agent_id'] : null,
        ':seo_title' => $data['seo_title'] ?? null,
        ':meta_description' => $data['meta_description'] ?? null,
        ':target_keywords' => $data['target_keywords'] ?? null,
        ':schema_markup_enabled' => isset($data['schema_markup_enabled']) ? (int)$data['schema_markup_enabled'] : 0,
        ':featured_project' => isset($data['featured_project']) ? (int)$data['featured_project'] : 0,
        ':hot_deal' => isset($data['hot_deal']) ? (int)$data['hot_deal'] : 0,
        ':limited_units' => isset($data['limited_units']) ? (int)$data['limited_units'] : 0,
        ':rera_info_verified' => isset($data['rera_info_verified']) ? (int)$data['rera_info_verified'] : 0,
        ':prices_subject_to_change' => isset($data['prices_subject_to_change']) ? (int)$data['prices_subject_to_change'] : 1,
        ':images_representational' => isset($data['images_representational']) ? (int)$data['images_representational'] : 1,
    ];
    
    return $stmt->execute($params);
}

// Delete project
function deleteProject($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM projects WHERE id = :id");
    return $stmt->execute([':id' => $id]);
}

// Upload PDF file
function uploadPDF($file, $prefix = 'project_') {
    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_size = $file['size'];
        if ($file_size > (10 * 1024 * 1024)) { // 10MB max
            return false;
        }
        
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf'];
        if (!in_array($file_ext, $allowed)) {
            return false;
        }
        
        $new_name = $prefix . uniqid('', true) . '.' . $file_ext;
        $dest = UPLOAD_DIR . $new_name;
        
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $new_name;
        }
    }
    
    return false;
}

// Get project images array
function getProjectImages($images_json) {
    if (empty($images_json)) {
        return [];
    }
    $images = json_decode($images_json, true);
    return is_array($images) ? $images : [];
}
?>

