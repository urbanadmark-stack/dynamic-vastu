<?php
/**
 * Database Migration Runner
 * This script will add the rera_number column to your properties table
 * 
 * IMPORTANT: Delete this file after running the migration for security!
 */

require_once 'config.php';

// Check if column already exists
function columnExists($db, $table, $column) {
    try {
        $stmt = $db->prepare("SHOW COLUMNS FROM `{$table}` LIKE ?");
        $stmt->execute([$column]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

$db = getDB();
$success = false;
$error = '';
$message = '';

// Check if rera_number column exists
if (columnExists($db, 'properties', 'rera_number')) {
    $message = '✓ The rera_number column already exists in your database. No migration needed!';
} else {
    // Run migration
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['run_migration'])) {
        try {
            $sql = "ALTER TABLE `properties` ADD COLUMN `rera_number` varchar(100) DEFAULT NULL AFTER `agent_email`";
            $db->exec($sql);
            $success = true;
            $message = '✓ Migration successful! The rera_number column has been added to your properties table.';
        } catch (PDOException $e) {
            $error = 'Migration failed: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Migration - Add RERA Column</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 3rem;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        .info {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 4px;
        }
        .success {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 4px;
            color: #2e7d32;
        }
        .error {
            background: #ffebee;
            border-left: 4px solid #f44336;
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 4px;
            color: #c62828;
        }
        .warning {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 4px;
            color: #e65100;
        }
        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }
        button:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        .sql-preview {
            background: #f5f5f5;
            padding: 1rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            margin: 1rem 0;
            overflow-x: auto;
        }
        .footer {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
            font-size: 0.875rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Migration</h1>
        
        <?php if ($message): ?>
            <div class="<?php echo $success ? 'success' : 'info'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!columnExists($db, 'properties', 'rera_number') && !$success): ?>
            <div class="info">
                <strong>What this migration does:</strong><br>
                Adds the <code>rera_number</code> column to your <code>properties</code> table to support RERA registration numbers for Indian real estate properties.
            </div>
            
            <div class="sql-preview">
                <strong>SQL Query to be executed:</strong><br>
                <code>ALTER TABLE `properties` ADD COLUMN `rera_number` varchar(100) DEFAULT NULL AFTER `agent_email`;</code>
            </div>
            
            <form method="POST">
                <button type="submit" name="run_migration">Run Migration</button>
            </form>
        <?php endif; ?>
        
        <div class="footer">
            <strong>⚠️ Security Note:</strong> Please delete this file (<code>run-migration.php</code>) after running the migration to prevent unauthorized access.
        </div>
    </div>
</body>
</html>

