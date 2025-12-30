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
    $sql = "INSERT INTO properties (title, description, property_type, status, price, bedrooms, bathrooms, area, area_unit, address, city, state, zip_code, country, images, features, year_built, parking, agent_name, agent_phone, agent_email) 
            VALUES (:title, :description, :property_type, :status, :price, :bedrooms, :bathrooms, :area, :area_unit, :address, :city, :state, :zip_code, :country, :images, :features, :year_built, :parking, :agent_name, :agent_phone, :agent_email)";
    
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
        ':country' => $data['country'] ?? 'USA',
        ':images' => $data['images'] ?? null,
        ':features' => $data['features'] ?? null,
        ':year_built' => $data['year_built'] ?? null,
        ':parking' => $data['parking'] ?? null,
        ':agent_name' => $data['agent_name'] ?? null,
        ':agent_phone' => $data['agent_phone'] ?? null,
        ':agent_email' => $data['agent_email'] ?? null,
    ]);
}

// Update property
function updateProperty($id, $data) {
    $db = getDB();
    $sql = "UPDATE properties SET 
            title = :title, description = :description, property_type = :property_type, 
            status = :status, price = :price, bedrooms = :bedrooms, bathrooms = :bathrooms, 
            area = :area, area_unit = :area_unit, address = :address, city = :city, 
            state = :state, zip_code = :zip_code, country = :country, images = :images, 
            features = :features, year_built = :year_built, parking = :parking, 
            agent_name = :agent_name, agent_phone = :agent_phone, agent_email = :agent_email 
            WHERE id = :id";
    
    $stmt = $db->prepare($sql);
    $data[':id'] = $id;
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
        ':country' => $data['country'] ?? 'USA',
        ':images' => $data['images'] ?? null,
        ':features' => $data['features'] ?? null,
        ':year_built' => $data['year_built'] ?? null,
        ':parking' => $data['parking'] ?? null,
        ':agent_name' => $data['agent_name'] ?? null,
        ':agent_phone' => $data['agent_phone'] ?? null,
        ':agent_email' => $data['agent_email'] ?? null,
    ]);
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
?>

