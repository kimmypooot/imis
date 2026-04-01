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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>CSC RO VIII - IMIS</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php include 'vendor_css.html' ?>
    <style>
        .hover-shadow:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        #usersTable th,
        #usersTable td {
            white-space: nowrap;
            /* Prevent line breaks */
            vertical-align: middle;
        }

        #usersTable td:nth-child(1) {
            white-space: normal;
            /* Allow Division/Office to wrap if it's too long */
        }

        .modal-body .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <?php imis_include('header_js') ?>
    <?php include 'inc/sidebar.php' ?>

    <?php
    $systems = [
        ["id" => "OTRS", "name" => "Online Training Registration System"],
        ["id" => "ERIS", "name" => "Examination Related Information System"],
        ["id" => "ORS",  "name" => "Online Recruitment System"],
        ["id" => "CDL",  "name" => "Client Daily Logsheet"],
        ["id" => "IIS",  "name" => "Intern Information System"],
        ["id" => "RFCS", "name" => "Report Fuel Consumption System"],
        ["id" => "DVS",  "name" => "Disbursement Voucher System"],
        ["id" => "CTS",  "name" => "Case Tracking System"],
        ["id" => "LMS",  "name" => "Leave Management System"],
        ["id" => "PSED", "name" => "Primary System of Electronic Based Documents"],
        ["id" => "ROOMS", "name" => "Regional Office Orders and Memoranda Management System"],
        ["id" => "MSDESERVE", "name" => "MsDeServe"],
        ["id" => "ITSRTS", "name" => "ICT Service Request Ticketing System"],
        ["id" => "JPortal", "name" => "CSC RO VIII Job Portal"],
        ["id" => "LCMMS", "name" => "Leave Card Management and Monitoring System"],
        ["id" => "GAD-CORNER", "name" => "Gender and Development Corner"],
        ["id" => "PMS", "name" => "IPCRF"]
    ];
    ?>

    <main id="main" class="main">
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold text-uppercase text-secondary mb-0">
                    <i class="bi bi-shield-lock-fill me-2"></i> Manage System Access
                </h5>
                <table id="systemTable" class="table table-striped table-hover table-bordered w-100" style="font-size: 14px">
                    <thead class="table-light">
                        <tr>
                            <th style="text-align: center; vertical-align: middle">#</th>
                            <th style="text-align: center; vertical-align: middle">System Name</th>
                            <th style="text-align: center; vertical-align: middle">System Code</th>
                            <th style="text-align: center; vertical-align: middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($systems as $i => $sys): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($sys["name"]) ?></td>
                                <td><?= htmlspecialchars($sys["id"]) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary manage-btn"
                                        data-id="<?= htmlspecialchars($sys["id"]) ?>"
                                        data-sysname="<?= htmlspecialchars($sys["name"]) ?>">
                                        <i class="bi bi-gear-fill me-1"></i> Manage Access
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>



    <!---------------------------------------------------- Modals Here  ---------------------------------------------------->

    <!-- Modal -->
    <div class="modal fade" id="accessModal" tabindex="-1" aria-labelledby="accessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold text-uppercase" id="accessModalLabel">
                        <i class="bi bi-person-gear me-2"></i> Manage System Access
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="fw-bold text-primary text-uppercase mb-3"><i class="bi bi-motherboard"></i> <span id="systemName"></span></h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle w-100" id="usersTable" style="font-size: 14px">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center align-middle">Division / Office</th>
                                    <th class="text-center align-middle">Position</th>
                                    <th class="text-center align-middle">Super Admin</th>
                                    <th class="text-center align-middle">Admin</th>
                                    <th class="text-center align-middle">User</th>
                                    <th class="text-center align-middle">None</th>
                                    <th class="text-center align-middle">Office</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success" id="updateBtn" data-id="">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!---------------------------------------------------- Scripts Here ---------------------------------------------------->
    <?php include 'vendor_js.html' ?>
    <script src="LumaFramework/LumaFramework.js"></script>
    <script src="js/access_management.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize systemTable
            $('#systemTable').DataTable({
                responsive: true,
                autoWidth: false,
                columnDefs: [{
                        targets: 0,
                        className: 'text-center align-middle',
                        width: '5%'
                    }, // #
                    {
                        targets: 1,
                        className: 'align-middle'
                    }, // System Name
                    {
                        targets: 2,
                        className: 'text-center align-middle'
                    }, // System Code
                    {
                        targets: 3,
                        className: 'text-center align-middle',
                        orderable: false
                    } // Action
                ],
                order: [
                    [0, 'asc']
                ],
                pageLength: 15,
                lengthMenu: [5, 10, 15, 25, 50, 100]
            });
        });
    </script>
    <!---------------------------------------------------- Footer ---------------------------------------------------->
    <?php imis_include('footer') ?>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>
</body>

</html>