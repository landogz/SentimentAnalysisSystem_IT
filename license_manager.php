<?php
session_start();

// Configuration
$jsonFile = 'licenses.json';
$adminUsername = '';
$adminPassword = '';

// Initialize JSON file if it doesn't exist
if (!file_exists($jsonFile)) {
    $defaultLicenses = [
        [
            "license_id" => "LWS-001",
            "system_name" => "Board Members' Portal",
            "license_type" => "Lifetime",
            "issued_to" => "Dangerous Drugs Board",
            "issue_date" => "2025-08-01",
            "expiry_date" => "2026-08-02",
            "status" => "Active"
        ],
        [
            "license_id" => "LWS-002",
            "system_name" => "Lead Source Management System",
            "license_type" => "Annual",
            "issued_to" => "RIARD Enterprise Solutions, Inc",
            "issue_date" => "2025-07-10",
            "expiry_date" => "2026-07-10",
            "status" => "Active"
        ],
        [
            "license_id" => "LWS-003",
            "system_name" => "Inventory Settings Module",
            "license_type" => "Annual",
            "issued_to" => "ABC Manufacturing Corp.",
            "issue_date" => "2024-12-15",
            "expiry_date" => "2025-12-15",
            "status" => "Expired"
        ],
        [
            "license_id" => "LWS-004",
            "system_name" => "Sentiment Analysis System",
            "license_type" => "Annual",
            "issued_to" => "PRMSU CCIT Sentiment Analysis System",
            "issue_date" => "2025-08-01",
            "expiry_date" => "2026-08-10",
            "status" => "Active"
        ]
    ];
    file_put_contents($jsonFile, json_encode($defaultLicenses, JSON_PRETTY_PRINT));
}

// Functions
function loadLicenses() {
    global $jsonFile;
    if (file_exists($jsonFile)) {
        $data = file_get_contents($jsonFile);
        return json_decode($data, true) ?: [];
    }
    return [];
}

function saveLicenses($licenses) {
    global $jsonFile;
    return file_put_contents($jsonFile, json_encode($licenses, JSON_PRETTY_PRINT));
}

function findLicenseById($licenseId) {
    $licenses = loadLicenses();
    foreach ($licenses as $license) {
        if ($license['license_id'] === $licenseId) {
            return $license;
        }
    }
    return null;
}

function updateLicenseStatus() {
    $licenses = loadLicenses();
    $updated = false;
    
    foreach ($licenses as &$license) {
        if ($license['license_type'] !== 'Lifetime' && isset($license['expiry_date'])) {
            $expiryDate = new DateTime($license['expiry_date']);
            $currentDate = new DateTime();
            
            if ($currentDate > $expiryDate && $license['status'] !== 'Expired') {
                $license['status'] = 'Expired';
                $updated = true;
            } elseif ($currentDate <= $expiryDate && $license['status'] === 'Expired') {
                $license['status'] = 'Active';
                $updated = true;
            }
        }
    }
    
    if ($updated) {
        saveLicenses($licenses);
    }
    
    return $licenses;
}

// Handle login
if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === $adminUsername && $password === $adminPassword) {
        $_SESSION['logged_in'] = true;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $loginError = 'Invalid username or password';
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle CRUD operations
if (isset($_SESSION['logged_in'])) {
    $message = '';
    $messageType = '';
    
    // Create/Update License
    if (isset($_POST['save_license'])) {
        $licenseId = $_POST['license_id'];
        $systemName = $_POST['system_name'];
        $licenseType = $_POST['license_type'];
        $issuedTo = $_POST['issued_to'];
        $issueDate = $_POST['issue_date'];
        $expiryDate = $_POST['expiry_date'];
        $status = $_POST['status'];
        
        $licenses = loadLicenses();
        $existingIndex = -1;
        $isEditing = isset($_GET['edit']);
        
        if ($isEditing) {
            // We're editing an existing license
            $originalLicenseId = $_GET['edit'];
            foreach ($licenses as $index => $license) {
                if ($license['license_id'] === $originalLicenseId) {
                    $existingIndex = $index;
                    break;
                }
            }
        } else {
            // We're creating a new license - check if license ID already exists
            foreach ($licenses as $index => $license) {
                if ($license['license_id'] === $licenseId) {
                    $existingIndex = $index;
                    break;
                }
            }
        }
        
        $newLicense = [
            'license_id' => $licenseId,
            'system_name' => $systemName,
            'license_type' => $licenseType,
            'issued_to' => $issuedTo,
            'issue_date' => $issueDate,
            'expiry_date' => $expiryDate,
            'status' => $status
        ];
        
        if ($isEditing && $existingIndex >= 0) {
            // Update existing license
            $licenses[$existingIndex] = $newLicense;
            $message = 'License updated successfully!';
            $messageType = 'success';
        } elseif (!$isEditing && $existingIndex >= 0) {
            // Trying to create with existing license ID
            $message = 'License ID already exists! Please use a different license ID.';
            $messageType = 'danger';
        } elseif (!$isEditing && $existingIndex === -1) {
            // Create new license
            $licenses[] = $newLicense;
            $message = 'License created successfully!';
            $messageType = 'success';
        } else {
            // Error case
            $message = 'Error: License not found for editing!';
            $messageType = 'danger';
        }
        saveLicenses($licenses);
    }
    
    // Delete License
    if (isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];
        $licenses = loadLicenses();
        $licenses = array_filter($licenses, function($license) use ($deleteId) {
            return $license['license_id'] !== $deleteId;
        });
        saveLicenses($licenses);
        $message = 'License deleted successfully!';
        $messageType = 'success';
    }
    
    // Edit License
    $editLicense = null;
    if (isset($_GET['edit'])) {
        $editLicense = findLicenseById($_GET['edit']);
    }
}

// Update license statuses
$licenses = updateLicenseStatus();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Manager - Landogz Web Solutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #059669;
            --danger-color: #dc2626;
            --warning-color: #d97706;
            --info-color: #0891b2;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
            font-weight: 400;
        }

        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            margin: 30px auto;
            max-width: 1400px;
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            transform: skewX(-15deg);
        }

        .header h4 {
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .header p {
            font-weight: 400;
            opacity: 0.9;
            margin: 0;
        }

        .content {
            padding: 2rem;
        }

        .login-container {
            max-width: 450px;
            margin: 100px auto;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-xl);
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
        }

        .login-container h3 {
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .btn-custom {
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

        .btn-outline-light {
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            background: transparent;
        }

        .btn-outline-light:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.5);
            color: white;
        }

        .table-responsive {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #1e293b, #334155);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: var(--light-bg);
            transition: background-color 0.2s ease;
        }

        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: var(--success-color);
        }

        .status-expired {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: var(--danger-color);
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid var(--border-color);
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--light-bg), #f1f5f9);
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem;
        }

        .card-header h5 {
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            font-size: 1.1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: var(--success-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: var(--danger-color);
        }

        .badge {
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
        }

        .btn-group-sm .btn {
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-danger {
            border-color: var(--danger-color);
            color: var(--danger-color);
        }

        .btn-outline-danger:hover {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        pre {
            background: #1e293b;
            color: #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            font-size: 0.85rem;
            line-height: 1.6;
            border: 1px solid #334155;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .main-container {
                margin: 15px;
                border-radius: 16px;
            }
            
            .content {
                padding: 1rem;
            }
            
            .header {
                padding: 1.5rem;
            }
            
            .login-container {
                margin: 50px 15px;
                padding: 2rem;
            }
        }

        /* Animation for page load */
        .main-container {
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['logged_in'])): ?>
    <!-- Login Form -->
    <div class="login-container">
        <div class="text-center mb-4">
            <div class="mb-3">
                <i class="fas fa-shield-alt" style="font-size: 3rem; color: var(--primary-color);"></i>
            </div>
            <h3>License Manager</h3>
            <p class="text-muted">Landogz Web Solutions</p>
        </div>
        
        <?php if (isset($loginError) && !empty($loginError)): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed!',
                        text: '<?php echo addslashes($loginError); ?>',
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-user text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" id="username" name="username" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                    <input type="password" class="form-control border-start-0" id="password" name="password" required>
                </div>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100 btn-custom">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </button>
        </form>
    </div>

<?php else: ?>
    <!-- Main Application -->
    <div class="main-container">
        <!-- Header -->
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4><i class="fas fa-shield-alt me-3"></i>License Manager</h4>
                    <p>Landogz Web Solutions</p>
                </div>
                <div>
                    <a href="?logout=1" class="btn btn-outline-light btn-custom">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <?php if (isset($message) && !empty($message)): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: '<?php echo $messageType === 'success' ? 'success' : 'error'; ?>',
                            title: '<?php echo $messageType === 'success' ? 'Success!' : 'Error!'; ?>',
                            text: '<?php echo addslashes($message); ?>',
                            confirmButtonColor: '#2563eb',
                            confirmButtonText: 'OK',
                            timer: <?php echo $messageType === 'success' ? '3000' : '5000'; ?>,
                            timerProgressBar: true
                        });
                    });
                </script>
            <?php endif; ?>
            
            <!-- Add/Edit License Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-<?php echo $editLicense ? 'edit' : 'plus-circle'; ?> me-2"></i>
                        <?php echo $editLicense ? 'Edit License' : 'Add New License'; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="license_id" class="form-label">License ID</label>
                                <input type="text" class="form-control" id="license_id" name="license_id" 
                                       value="<?php echo $editLicense ? $editLicense['license_id'] : ''; ?>" 
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="system_name" class="form-label">System Name</label>
                                <input type="text" class="form-control" id="system_name" name="system_name" 
                                       value="<?php echo $editLicense ? $editLicense['system_name'] : ''; ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="license_type" class="form-label">License Type</label>
                                <select class="form-select" id="license_type" name="license_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Lifetime" <?php echo ($editLicense && $editLicense['license_type'] === 'Lifetime') ? 'selected' : ''; ?>>Lifetime</option>
                                    <option value="Annual" <?php echo ($editLicense && $editLicense['license_type'] === 'Annual') ? 'selected' : ''; ?>>Annual</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="issued_to" class="form-label">Issued To</label>
                                <input type="text" class="form-control" id="issued_to" name="issued_to" 
                                       value="<?php echo $editLicense ? $editLicense['issued_to'] : ''; ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="issue_date" class="form-label">Issue Date</label>
                                <input type="date" class="form-control" id="issue_date" name="issue_date" 
                                       value="<?php echo $editLicense ? $editLicense['issue_date'] : ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date" 
                                       value="<?php echo $editLicense ? $editLicense['expiry_date'] : ''; ?>">
                                <small class="text-muted">Leave empty for Lifetime licenses</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="Active" <?php echo ($editLicense && $editLicense['status'] === 'Active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="Expired" <?php echo ($editLicense && $editLicense['status'] === 'Expired') ? 'selected' : ''; ?>>Expired</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <button type="submit" name="save_license" class="btn btn-primary btn-custom">
                                <i class="fas fa-save me-2"></i><?php echo $editLicense ? 'Update License' : 'Add License'; ?>
                            </button>
                            <?php if ($editLicense): ?>
                                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary btn-custom">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Licenses Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>All Licenses
                        <span class="badge bg-primary ms-2"><?php echo count($licenses); ?></span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>License ID</th>
                                    <th>System Name</th>
                                    <th>Type</th>
                                    <th>Issued To</th>
                                    <th>Issue Date</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($licenses as $license): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($license['license_id']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($license['system_name']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $license['license_type'] === 'Lifetime' ? 'success' : 'info'; ?>">
                                                <?php echo $license['license_type']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($license['issued_to']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($license['issue_date'])); ?></td>
                                        <td>
                                            <?php 
                                            if ($license['expiry_date']) {
                                                echo date('M d, Y', strtotime($license['expiry_date']));
                                            } else {
                                                echo '<span class="text-muted">N/A</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower($license['status']); ?>">
                                                <?php echo $license['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="?edit=<?php echo $license['license_id']; ?>" 
                                                   class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?delete=<?php echo $license['license_id']; ?>" 
                                                   class="btn btn-outline-danger" 
                                                   onclick="return confirmDelete('<?php echo addslashes($license['license_id']); ?>')" 
                                                   title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- JSON Data Display -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-code me-2"></i>JSON Data
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard()">
                        <i class="fas fa-copy me-1"></i>Copy JSON
                    </button>
                </div>
                <div class="card-body">
                    <pre id="jsonData" style="max-height: 300px; overflow-y: auto;"><?php echo json_encode($licenses, JSON_PRETTY_PRINT); ?></pre>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function copyToClipboard() {
    const jsonData = document.getElementById('jsonData').textContent;
    navigator.clipboard.writeText(jsonData).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'JSON data copied to clipboard!',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'OK',
            timer: 2000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
        });
    }, function(err) {
        console.error('Could not copy text: ', err);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Failed to copy JSON data to clipboard.',
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'OK'
        });
    });
}

function confirmDelete(licenseId) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete license "${licenseId}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `?delete=${licenseId}`;
        }
    });
    return false;
}

// Auto-update status based on expiry date
document.getElementById('license_type').addEventListener('change', function() {
    const expiryDateField = document.getElementById('expiry_date');
    if (this.value === 'Lifetime') {
        expiryDateField.value = '';
        expiryDateField.disabled = true;
    } else {
        expiryDateField.disabled = false;
    }
});

// Add smooth animations
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.style.animation = 'slideInUp 0.6s ease-out forwards';
    });
});
</script>

</body>
</html> 
