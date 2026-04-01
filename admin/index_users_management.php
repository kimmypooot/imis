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
imis_include ('header_js');
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
        .edit-overlay {
            background: rgba(0, 0, 0, 0.4);
            /* semi-transparent dark */
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .profile-container:hover .edit-overlay {
            opacity: 1;
        }
    </style>

</head>

<body>
    
    <?php include 'inc/sidebar.php' ?>

    <!-- Main Elements Here -->
    <main id="main" class="main">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title fw-bold text-uppercase text-primary mb-0">
                        <i class="bi bi-person-fill me-2"></i> Manage Overall Users
                    </h5>
                    <button class="btn btn-sm btn-success rounded-pill" id="addUserBtn">
                        <i class="bi bi-person-fill-add me-1"></i> Add User
                    </button>
                </div>
        
                <div class="table-responsive">
                    <table id="usersTable" class="table table-bordered table-striped table-hover align-middle w-100" style="font-size: 14px">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center align-middle" style="width: 5%;">#</th>
                                <th class="text-center align-middle">Name</th>
                                <th class="text-center align-middle">Username</th>
                                <th class="text-center align-middle">Office / Division</th>
                                <th class="text-center align-middle">Position</th>
                                <th class="text-center align-middle">Status</th>
                                <th class="text-center align-middle" style="width: 12%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populated via DataTables AJAX or backend -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!---------------------------------------------------- Modals Here  ---------------------------------------------------->

    <!-- Insert New User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <!-- Default width is fine for 2x4 -->
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold text-uppercase" id="addUserModalLabel"><i
                            class="bi bi-person-fill-add"></i> Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="initial" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="mname" name="mname">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Division/FO</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="" selected hidden>-- Select Type --</option>
                                <optgroup label="CSC Regional Support Units">
                                    <option value="ord">Office of the Regional Director</option>
                                    <option value="esd">Examination Services Division</option>
                                    <option value="msd">Management Services Division</option>
                                    <option value="hrd">Human Resource Division</option>
                                    <option value="pald">Public Assistance and Liaison Division</option>
                                    <option value="psed">Policies and Systems Evaluation Division</option>
                                    <option value="lsd">Legal Services Division</option>
                                </optgroup>
                                <optgroup label="Field Offices and Sattelite Office">
                                    <option value="lfoi">Field Office Leyte I</option>
                                    <option value="lfoii">Field Office Leyte II</option>
                                    <option value="esfo">Field Office Eastern Samar</option>
                                    <option value="sfo">Field Office Samar</option>
                                    <option value="bfo">Field Office Biliran</option>
                                    <option value="slfo">Field Office Southern Leyte</option>
                                    <option value="nsfo">Field Office Northern Samar</option>
                                    <option value="wlso">Satellite Office Western Office</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" class="form-control" id="position" name="position" required>
                            </div>
                            <div class="col-md-6">
                                <label for="role" class="form-label">User Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="" selected hidden>-- Select Role --</option>
                                    <option value="superadmin">superadmin</option>
                                    <option value="admin">admin</option>
                                    <option value="user">user</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="birthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="profilePic" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="profilePic" name="profilePic"
                                    accept="image/*">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitAddUserBtn">Add User</button>
                </div>
            </div>
        </div>
    </div>

    <!---------- Menu Modal ---------->
    <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title w-100" id="menuModalLabel">User Actions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-grid gap-3">
                    <button class="btn btn-outline-primary" id="changePwBtn" type="button" data-id="">Change
                        Password</button>
                    <button class="btn btn-outline-warning" id="updateRoleBtn" type="button" data-id="">Update
                        Role</button>
                    <button class="btn btn-outline-info" id="viewDetailsBtn" type="button" data-id="">View
                        Details</button>
                </div>
            </div>
        </div>
    </div>

    <!---------- View Details Modal ---------->
    <div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl ">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold text-uppercase" id="viewDetailsLabel"><i
                            class="bi bi-person-fill"></i> User Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Profile Picture & Basic Info -->
                            <div class="col-md-4 text-center">
                                <div class="profile-container position-relative d-inline-block rounded-circle"
                                    style="width: 200px; height: 200px; overflow: hidden;" data-id="">
                                    <img id="profile" src="https://via.placeholder.com/200"
                                        class="img-fluid rounded-circle border shadow-sm mb-3"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                    <div
                                        class="edit-overlay position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center rounded-circle">
                                        <i class="fa fa-pencil-alt text-white" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <input type="file" id="profileInput" accept="image/*" style="display: none;">
                                <p class="fw-semibold text-primary text-capitalize" id="user-role-status">Admin - Active
                                </p>
                                <!-- Toggle Switch -->
                                <div class="d-flex justify-content-center mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="editToggle">
                                        <label class="form-check-label" for="userToggle">Edit</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Details Section -->
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" id="id" hidden>
                                            <input type="text" class="form-control to-edit " id="fname" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" class="form-control to-edit " id="mname" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control to-edit " id="username" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Position</label>
                                            <input type="text" class="form-control to-edit " id="position" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control to-edit " id="lname" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Birthday</label>
                                            <input type="text" class="form-control " id="birthday" readonly>
                                            <input type="date" class="form-control to-edit " id="birthday2">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control to-edit " id="email" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">FO/RSU / Office</label>
                                            <input type="text" class="form-control to-edit " id="fo_rsu" readonly>
                                            <select class="form-select to-edit" id="type" name="type" required>
                                                <optgroup label="CSC Regional Support Units">
                                                    <option value="ord">Office of the Regional Director</option>
                                                    <option value="esd">Examination Services Division</option>
                                                    <option value="msd">Management Services Division</option>
                                                    <option value="hrd">Human Resource Division</option>
                                                    <option value="pald">Public Assistance and Liaison Division</option>
                                                    <option value="psed">Policies and Systems Evaluation Division</option>
                                                    <option value="lsd">Legal Services Division</option>
                                                </optgroup>
                                                <optgroup label="Field Offices and Sattelite Office">
                                                    <option value="lfoi">CSC Field Office - Leyte I</option>
                                                    <option value="lfoii">CSC Field Office - Leyte II</option>
                                                    <option value="esfo">CSC Field Office - Eastern Samar</option>
                                                    <option value="sfo">CSC Field Office - Samar</option>
                                                    <option value="bfo">CSC Field Office - Biliran</option>
                                                    <option value="slfo">CSC Field Office - Southern Leyte</option>
                                                    <option value="nsfo">CSC Field Office - Northern Samar</option>
                                                    <option value="wlso">Satellite Office Western Office</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div> <!-- end row -->
                            </div>
                        </div> <!-- end main row -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!----------- Update Role Modal ---------->
    <div class="modal fade" id="updateRoleModal" tabindex="-1" aria-labelledby="updateRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="updateRoleLabel">Update User Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="updateRoleForm">
                        <div class="mb-3 form-check form-switch d-flex align-items-center">
                            <input class="form-check-input me-2" type="checkbox" id="userStatusToggle" name="status">
                            <label class="form-check-label" for="userStatusToggle" id="statusLabel">Active</label>
                        </div>

                        <div class="mb-3" id="roleContainer">
                            <label for="userRoleSelect" class="form-label">User Role</label>
                            <select class="form-select" id="userRoleSelect" name="role">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                                <option value="none" hidden>None</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveRoleChangesBtn" data-id="">Save
                        Changes</button>
                </div>
            </div>
        </div>
    </div>





    <!---------------------------------------------------- Scripts Here ---------------------------------------------------->
    <?php include 'vendor_js.html' ?>
    <script>
        <?php include 'js/users_management.js' ?>
    </script>
<?php imis_include ('footer') ?>
    <!---------------------------------------------------- Footer ---------------------------------------------------->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>
</body>

</html>