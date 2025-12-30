<?php
require_once '../config.php';
checkAuth();
require_once '../includes/functions.php';

$properties = getProperties();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/admin-header.php'; ?>
    
    <main class="admin-main">
        <div class="admin-container">
            <div class="admin-header">
                <h1>Properties Management</h1>
                <a href="add-property.php" class="btn btn-primary">Add New Property</a>
            </div>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Property <?php echo htmlspecialchars($_GET['success']); ?> successfully!</div>
            <?php endif; ?>
            
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Property deleted successfully!</div>
            <?php endif; ?>
            
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($properties)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No properties found. <a href="add-property.php">Add your first property</a></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($properties as $property): 
                                $images = getPropertyImages($property['images']);
                                $main_image = !empty($images) ? '../uploads/' . $images[0] : '../assets/images/placeholder.jpg';
                            ?>
                                <tr>
                                    <td><?php echo $property['id']; ?></td>
                                    <td><img src="<?php echo htmlspecialchars($main_image); ?>" alt="Thumbnail" class="table-thumbnail"></td>
                                    <td><?php echo htmlspecialchars($property['title']); ?></td>
                                    <td><?php echo ucfirst($property['property_type']); ?></td>
                                    <td><span class="status-badge status-<?php echo $property['status']; ?>"><?php echo ucfirst(str_replace('_', ' ', $property['status'])); ?></span></td>
                                    <td><?php echo formatPrice($property['price']); ?></td>
                                    <td><?php echo htmlspecialchars($property['city']); ?></td>
                                    <td class="actions">
                                        <a href="../property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm btn-outline" target="_blank">View</a>
                                        <a href="edit-property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="delete-property.php?id=<?php echo $property['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this property?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>

