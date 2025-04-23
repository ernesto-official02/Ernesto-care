<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Get all tables from the database
try {
    $tables = [];
    $stmt = $pdo->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }
} catch(PDOException $e) {
    $error = "Error fetching tables: " . $e->getMessage();
}

// Get data from selected table
$selectedTable = isset($_GET['table']) ? $_GET['table'] : (isset($tables[0]) ? $tables[0] : '');
$tableData = [];
$columns = [];

if (!empty($selectedTable)) {
    try {
        // Get column names
        $stmt = $pdo->query("SHOW COLUMNS FROM `$selectedTable`");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Get table data
        $stmt = $pdo->query("SELECT * FROM `$selectedTable`");
        $tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Error fetching data: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Ernesto Health</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px 0;
        }
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #444;
        }
        .sidebar-title {
            margin: 0;
            font-size: 32px;
            font-weight: 600;
        }
        .sidebar-subtitle {
            margin: 5px 0 0;
            font-size: 16px;
            color: #aaa;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .sidebar-menu li {
            padding: 0;
        }
        .sidebar-menu a {
            display: block;
            padding: 14px 20px;
            color: #ddd;
            text-decoration: none;
            transition: background-color 0.3s;
            font-size: 16px;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: #444;
        }
        .sidebar-menu i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            overflow-x: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .page-title {
            margin: 0;
            font-size: 32px;
            font-weight: 600;
            color: #333;
        }
        .user-info {
            display: flex;
            align-items: center;
            font-size: 16px;
            gap: 15px;
            background: #fff;
            padding: 8px 15px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .user-name {
            font-size: 18px;
            color: #333;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        .user-name i {
            margin-right: 8px;
            color: #4CAF50;
        }
        .home-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
        }
        .home-btn:hover {
            background-color: #45a049;
        }
        .home-btn i {
            font-size: 20px;
        }
        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
        }
        .logout-btn:hover {
            background-color: #45a049;
        }
        .logout-btn i {
            font-size: 20px;
        }
        .table-selector {
            margin-bottom: 20px;
            font-size: 16px;
        }
        .table-select {
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 200px;
            font-size: 16px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            overflow: hidden;
            font-size: 16px;
        }
        .data-table th {
            background-color: #4CAF50;
            color: white;
            text-align: left;
            padding: 14px 16px;
            font-size: 18px;
            font-weight: 600;
        }
        .data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }
        .data-table tr:last-child td {
            border-bottom: none;
        }
        .data-table tr:hover {
            background-color: #f5f5f5;
        }
        .action-btn {
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
            color: white;
            border: none;
        }
        .edit-btn {
            background-color: #2196F3;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .add-btn {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 500;
        }
        .add-btn:hover {
            background-color: #45a049;
        }
        .error-message {
            color: #f44336;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #ffebee;
            border-radius: 4px;
        }
        .empty-message {
            text-align: center;
            padding: 30px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-title">Admin Panel</h1>
                <p class="sidebar-subtitle">Ernesto Health</p>
            </div>
            <ul class="sidebar-menu">
                <li><a href="admin_panel.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <?php foreach ($tables as $table): ?>
                <li><a href="admin_panel.php?table=<?php echo $table; ?>" <?php echo ($selectedTable === $table) ? 'class="active"' : ''; ?>>
                    <i class="fas fa-table"></i> <?php echo ucfirst($table); ?>
                </a></li>
                <?php endforeach; ?>
                <li><a href="admin_settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h2 class="page-title"><?php echo ucfirst($selectedTable); ?> Data</h2>
                <div class="user-info">
                    <span class="user-name">
                        <i class="fas fa-user-circle"></i>
                        <?php echo $_SESSION['admin_username']; ?>
                    </span>
                    <a href="index.php" class="home-btn">
                        <i class="fas fa-home"></i>
                        Home Page
                    </a>
                    <a href="admin_logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="table-selector">
                <label for="table-select">Select Table: </label>
                <select id="table-select" class="table-select" onchange="window.location.href='admin_panel.php?table=' + this.value">
                    <?php foreach ($tables as $table): ?>
                        <option value="<?php echo $table; ?>" <?php echo ($selectedTable === $table) ? 'selected' : ''; ?>>
                            <?php echo ucfirst($table); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button class="add-btn"><i class="fas fa-plus"></i> Add New Record</button>
            
            <?php if (empty($tableData)): ?>
                <div class="empty-message">No data found in this table.</div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <?php foreach ($columns as $column): ?>
                                <th><?php echo ucfirst($column); ?></th>
                            <?php endforeach; ?>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tableData as $row): ?>
                            <tr>
                                <?php foreach ($columns as $column): ?>
                                    <td><?php echo htmlspecialchars($row[$column]); ?></td>
                                <?php endforeach; ?>
                                <td>
                                    <button class="action-btn edit-btn"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete-btn"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Add confirmation for delete action
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this record?')) {
                    // Add delete functionality here
                    alert('Delete functionality will be implemented here');
                }
            });
        });
        
        // Add edit functionality
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Add edit functionality here
                alert('Edit functionality will be implemented here');
            });
        });
        
        // Add new record functionality
        document.querySelector('.add-btn').addEventListener('click', function() {
            // Add new record functionality here
            alert('Add new record functionality will be implemented here');
        });
    </script>
</body>
</html> 