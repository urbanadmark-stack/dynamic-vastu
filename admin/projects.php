<?php
require_once '../config.php';
checkAuth();
require_once '../includes/functions.php';

$projects = getProjects();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects Management - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include 'includes/admin-header.php'; ?>
    
    <main class="admin-main">
        <div class="admin-container">
            <div class="admin-header">
                <h1>Projects Management</h1>
                <a href="add-project.php" class="btn btn-primary">Add New Project</a>
            </div>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Project <?php echo htmlspecialchars($_GET['success']); ?> successfully!</div>
            <?php endif; ?>
            
            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Project deleted successfully!</div>
            <?php endif; ?>
            
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Project Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>RERA Number</th>
                            <th>Location</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($projects)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 2rem;">
                                    No projects found. <a href="add-project.php">Add your first project</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($projects as $project): 
                                $images = getProjectImages($project['project_images']);
                                $main_image = !empty($images) ? '../uploads/' . $images[0] : '../assets/images/placeholder.svg';
                            ?>
                                <tr>
                                    <td><?php echo $project['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($project['project_name']); ?></strong>
                                        <?php if ($project['hot_deal']): ?>
                                            <span style="background: #ef4444; color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.75rem; margin-left: 0.5rem;">Hot Deal</span>
                                        <?php endif; ?>
                                        <?php if ($project['limited_units']): ?>
                                            <span style="background: #f59e0b; color: white; padding: 2px 6px; border-radius: 3px; font-size: 0.75rem; margin-left: 0.5rem;">Limited Units</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $project['project_type'])); ?></td>
                                    <td><span class="status-badge status-<?php echo $project['project_status']; ?>"><?php echo ucfirst(str_replace('_', ' ', $project['project_status'])); ?></span></td>
                                    <td style="font-size: 0.875rem;"><?php echo htmlspecialchars($project['rera_number']); ?></td>
                                    <td><?php echo htmlspecialchars($project['city'] . ', ' . $project['state']); ?></td>
                                    <td>
                                        <?php if ($project['featured_project']): ?>
                                            <span style="color: #10b981; font-weight: 600;">★ Featured</span>
                                        <?php else: ?>
                                            <span style="color: #9ca3af;">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions">
                                        <a href="../project.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-outline" target="_blank">View</a>
                                        <a href="edit-project.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="delete-project.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this project?')">Delete</a>
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

