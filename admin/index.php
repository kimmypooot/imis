<?php
require_once __DIR__ . '/../includes/session.php'; 
require_once __DIR__ . '/../includes/connect.php';
include_once __DIR__ . '/../imis_include.php';

// Check if user is logged in and has a valid role
if (
    empty($_SESSION['username']) ||
    empty($_SESSION['role'])
) {
    session_unset();
    session_destroy();
    header('Location: ../login');
    exit();
}

// Redirect regular users away from superadmin pages
switch ($_SESSION['role']) {
    case 'admin':
    case 'user':
        header('Location: ../index_dashboard');
        exit();
    case 'superadmin':
        // Allowed: continue
        break;
    default:
        // Unknown role: force logout
        session_unset();
        session_destroy();
        header('Location: ../login');
        exit();
}

// Sample data queries (replace with actual database queries)
$total_users = 157; // Query: SELECT COUNT(*) FROM users
$active_sessions = 23; // Query: SELECT COUNT(*) FROM active_sessions
$pending_requests = 12; // Query: SELECT COUNT(*) FROM requests WHERE status = 'pending'
$system_alerts = 3; // Query: SELECT COUNT(*) FROM system_alerts WHERE status = 'active'
$monthly_transactions = 2847; // Query: SELECT COUNT(*) FROM transactions WHERE MONTH(created_at) = MONTH(NOW())
$server_uptime = "99.8%"; // From system monitoring
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Super Administrator Dashboard - CSC RO VIII IMIS</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php include 'vendor_css.html' ?>

    <style>
        .edit-overlay {
            background: rgba(0, 0, 0, 0.4);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .profile-container:hover .edit-overlay {
            opacity: 1;
        }

        .stat-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .accent-header {
            background: linear-gradient(135deg, #0077b6 0%, #005f8a 100%);
            color: white;
        }

        .accent-border {
            border-left: 4px solid #0077b6;
        }

        .text-accent {
            color: #0077b6;
        }

        .btn-accent {
            background-color: #0077b6;
            border-color: #0077b6;
            color: white;
        }

        .btn-accent:hover {
            background-color: #005f8a;
            border-color: #005f8a;
            color: white;
        }

        .activity-item {
            transition: background-color 0.2s ease;
        }

        .activity-item:hover {
            background-color: #f8f9fa;
        }

        .progress-custom {
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
        }

        .progress-bar-custom {
            background: linear-gradient(90deg, #0077b6 0%, #00b4d8 100%);
            border-radius: 4px;
        }

        .alert-custom {
            border-left: 4px solid #dc3545;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .quick-action-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .quick-action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 119, 182, 0.2);
        }
    </style>

</head>

<body>
    <?php imis_include ('header_js') ?>
    <?php include 'inc/sidebar.php' ?>

<main id="main" class="main">
    <!-- Page Header -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body accent-header">
            <div class="row align-items-center mt-3">
                <div class="col">
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-shield-lock-fill me-2"></i> SUPER ADMINISTRATOR DASHBOARD
                    </h4>
                    <p class="mb-0 mt-1 opacity-75">Complete system oversight and management</p>
                </div>
                <div class="col-auto">
                    <small class="opacity-75">
                        <i class="bi bi-clock me-1"></i> Last updated: <?php echo date('M d, Y - h:i A'); ?>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview Statistics -->
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body mt-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted mb-1 fw-bold">Total Users</h6>
                            <h3 class="text-accent mb-0 fw-bold"><?php echo number_format($total_users); ?></h3>
                        </div>
                        <div class="col-auto">
                            <div class="p-3 rounded-circle" style="background-color: rgba(0, 119, 182, 0.1);">
                                <i class="bi bi-people-fill text-accent fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <small class="text-success">
                                <i class="bi bi-arrow-up me-1"></i> 12% from last month
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body mt-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted mb-1 fw-bold">Active Sessions</h6>
                            <h3 class="text-accent mb-0 fw-bold"><?php echo number_format($active_sessions); ?></h3>
                        </div>
                        <div class="col-auto">
                            <div class="p-3 rounded-circle" style="background-color: rgba(40, 167, 69, 0.1);">
                                <i class="bi bi-activity text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <small class="text-info">
                                <i class="bi bi-clock me-1"></i> Real-time data
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body mt-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted mb-1 fw-bold">Pending Requests</h6>
                            <h3 class="text-warning mb-0 fw-bold"><?php echo number_format($pending_requests); ?></h3>
                        </div>
                        <div class="col-auto">
                            <div class="p-3 rounded-circle" style="background-color: rgba(255, 193, 7, 0.1);">
                                <i class="bi bi-hourglass-split text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <small class="text-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i> Requires attention
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body mt-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="text-uppercase text-muted mb-1 fw-bold">System Alerts</h6>
                            <h3 class="text-danger mb-0 fw-bold"><?php echo number_format($system_alerts); ?></h3>
                        </div>
                        <div class="col-auto">
                            <div class="p-3 rounded-circle" style="background-color: rgba(220, 53, 69, 0.1);">
                                <i class="bi bi-shield-exclamation text-danger fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <small class="text-danger">
                                <i class="bi bi-bell me-1"></i> Critical alerts
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header accent-header border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-lightning-fill me-2"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card quick-action-card border-0 text-center h-100" style="background-color: #f8f9fa;">
                                <div class="card-body py-3">
                                    <i class="bi bi-person-plus-fill text-accent fs-2 mb-2"></i>
                                    <h6 class="mb-0 fw-bold">Add User</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card quick-action-card border-0 text-center h-100" style="background-color: #f8f9fa;">
                                <div class="card-body py-3">
                                    <i class="bi bi-gear-fill text-accent fs-2 mb-2"></i>
                                    <h6 class="mb-0 fw-bold">System Config</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card quick-action-card border-0 text-center h-100" style="background-color: #f8f9fa;">
                                <div class="card-body py-3">
                                    <i class="bi bi-database-fill text-accent fs-2 mb-2"></i>
                                    <h6 class="mb-0 fw-bold">Database</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card quick-action-card border-0 text-center h-100" style="background-color: #f8f9fa;">
                                <div class="card-body py-3">
                                    <i class="bi bi-file-earmark-text-fill text-accent fs-2 mb-2"></i>
                                    <h6 class="mb-0 fw-bold">Reports</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card quick-action-card border-0 text-center h-100" style="background-color: #f8f9fa;">
                                <div class="card-body py-3">
                                    <i class="bi bi-shield-check-fill text-accent fs-2 mb-2"></i>
                                    <h6 class="mb-0 fw-bold">Security</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card quick-action-card border-0 text-center h-100" style="background-color: #f8f9fa;">
                                <div class="card-body py-3">
                                    <i class="bi bi-cloud-upload-fill text-accent fs-2 mb-2"></i>
                                    <h6 class="mb-0 fw-bold">Backup</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Performance & Recent Activity -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header accent-header border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-graph-up-arrow me-2"></i> System Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Server Uptime</span>
                                <span class="badge bg-success"><?php echo $server_uptime; ?></span>
                            </div>
                            <div class="progress progress-custom mb-3">
                                <div class="progress-bar progress-bar-custom" style="width: 99.8%"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Memory Usage</span>
                                <span class="badge bg-warning">67%</span>
                            </div>
                            <div class="progress progress-custom mb-3">
                                <div class="progress-bar progress-bar-custom" style="width: 67%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">CPU Usage</span>
                                <span class="badge bg-info">42%</span>
                            </div>
                            <div class="progress progress-custom mb-3">
                                <div class="progress-bar progress-bar-custom" style="width: 42%"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Storage Usage</span>
                                <span class="badge bg-primary">23%</span>
                            </div>
                            <div class="progress progress-custom mb-3">
                                <div class="progress-bar progress-bar-custom" style="width: 23%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4 text-center">
                            <div class="p-3 rounded" style="background-color: rgba(0, 119, 182, 0.1);">
                                <i class="bi bi-arrow-repeat text-accent fs-4"></i>
                                <h6 class="mt-2 mb-0 fw-bold">Monthly Transactions</h6>
                                <h4 class="text-accent mb-0"><?php echo number_format($monthly_transactions); ?></h4>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-3 rounded" style="background-color: rgba(40, 167, 69, 0.1);">
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                <h6 class="mt-2 mb-0 fw-bold">Success Rate</h6>
                                <h4 class="text-success mb-0">98.7%</h4>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-3 rounded" style="background-color: rgba(255, 193, 7, 0.1);">
                                <i class="bi bi-clock-history text-warning fs-4"></i>
                                <h6 class="mt-2 mb-0 fw-bold">Avg Response</h6>
                                <h4 class="text-warning mb-0">1.2s</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header accent-header border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clock-history me-2"></i> Recent Activity
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item activity-item border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="p-2 rounded-circle me-3" style="background-color: rgba(0, 119, 182, 0.1);">
                                    <i class="bi bi-person-plus text-accent"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fw-bold">New user registered</h6>
                                    <small class="text-muted">John Doe - 2 minutes ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item activity-item border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="p-2 rounded-circle me-3" style="background-color: rgba(40, 167, 69, 0.1);">
                                    <i class="bi bi-check-circle text-success"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fw-bold">System backup completed</h6>
                                    <small class="text-muted">Automated process - 15 minutes ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item activity-item border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="p-2 rounded-circle me-3" style="background-color: rgba(255, 193, 7, 0.1);">
                                    <i class="bi bi-shield-exclamation text-warning"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fw-bold">Security alert triggered</h6>
                                    <small class="text-muted">Multiple login attempts - 1 hour ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item activity-item border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="p-2 rounded-circle me-3" style="background-color: rgba(220, 53, 69, 0.1);">
                                    <i class="bi bi-exclamation-triangle text-danger"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fw-bold">Critical system error</h6>
                                    <small class="text-muted">Database connection - 2 hours ago</small>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item activity-item border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="p-2 rounded-circle me-3" style="background-color: rgba(0, 119, 182, 0.1);">
                                    <i class="bi bi-gear text-accent"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="mb-0 fw-bold">System maintenance</h6>
                                    <small class="text-muted">Configuration update - 3 hours ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Alerts & Management Tools -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header accent-header border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-bell-fill me-2"></i> System Alerts
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-custom border-0 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill text-danger me-3 fs-5"></i>
                            <div class="flex-fill">
                                <h6 class="mb-1 fw-bold">Database Connection Warning</h6>
                                <p class="mb-0 small">Connection pool reaching capacity limit</p>
                                <small class="text-muted">Priority: High • 30 minutes ago</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning border-0 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-shield-exclamation text-warning me-3 fs-5"></i>
                            <div class="flex-fill">
                                <h6 class="mb-1 fw-bold">Security Scan Alert</h6>
                                <p class="mb-0 small">Unusual login patterns detected</p>
                                <small class="text-muted">Priority: Medium • 1 hour ago</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info border-0 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill text-info me-3 fs-5"></i>
                            <div class="flex-fill">
                                <h6 class="mb-1 fw-bold">System Update Available</h6>
                                <p class="mb-0 small">New security patches ready for deployment</p>
                                <small class="text-muted">Priority: Low • 2 hours ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header accent-header border-0">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-tools me-2"></i> Management Tools
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-people-fill me-2"></i> User Management
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-outline-success">
                                    <i class="bi bi-shield-lock-fill me-2"></i> Role Management
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-outline-warning">
                                    <i class="bi bi-database-fill me-2"></i> Database Admin
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-outline-info">
                                    <i class="bi bi-graph-up-arrow me-2"></i> Analytics
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-outline-secondary">
                                    <i class="bi bi-gear-fill me-2"></i> System Settings
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-outline-danger">
                                    <i class="bi bi-file-earmark-text-fill me-2"></i> Log Viewer
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-accent">
                                    <i class="bi bi-cloud-upload-fill me-2"></i> System Backup
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-accent">
                                    <i class="bi bi-arrow-clockwise me-2"></i> System Restart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

    <!---------------------------------------------------- Scripts Here ---------------------------------------------------->
    <?php include 'vendor_js.html' ?>
    
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add click handlers for quick actions
        document.querySelectorAll('.quick-action-card').forEach(card => {
            card.addEventListener('click', function() {
                // Add your navigation logic here
                console.log('Quick action clicked:', this.querySelector('h6').textContent);
            });
        });

        // Add click handlers for management tools
        document.querySelectorAll('.btn-outline-primary, .btn-outline-success, .btn-outline-warning, .btn-outline-info, .btn-outline-secondary, .btn-outline-danger').forEach(btn => {
            btn.addEventListener('click', function() {
                console.log('Management tool clicked:', this.textContent.trim());
            });
        });

        // Auto-refresh dashboard data every 30 seconds
        setInterval(function() {
            // Add AJAX calls to refresh dashboard data
            console.log('Refreshing dashboard data...');
        }, 30000);
    </script>
    
    <!---------------------------------------------------- Footer ---------------------------------------------------->
    <?php include 'inc/footer.php' ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>
</body>

</html>