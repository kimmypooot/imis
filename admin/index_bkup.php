<?php
// Secure session start
session_start([
    'cookie_httponly' => true,     // Mitigate XSS
    'cookie_secure' => isset($_SERVER['HTTPS']), // Use secure cookies if HTTPS
    'cookie_samesite' => 'Strict', // Prevent CSRF via cross-site requests
]);

require_once 'connect.php'; // Use require_once for critical dependencies

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
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>CSC RO VIII - IMIS</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>  
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <!-- <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet"> -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
  <style>
    input[type="text"],
    select.form-select {
      font-size: 14px;
    }
  </style>
</head>

<body>

  <?php include 'inc/header.php' ?>
  <?php include 'inc/sidebar.php' ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Backup Database</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active">Backup Database</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
      <div class="card">
            <div class="card-body">
              <p></p>
              <!-- <h5 class="card-title">Backup Database</h5> -->
              <button class="btn btn-success btn-sm" id="backupButton"><i class="bi bi-database"></i> Backup Database</button>
              <p></p>
              <table id="fileTable" class="table table-bordered table-hover table-striped table-select table-responsive" style="font-size: 14px; width: 100%">
                <thead class="table-success">
                    <tr>
                        <th class="text-center align-middle">File Name of Database</th>
                        <th class="text-center align-middle">Last Modified</th>
                        <th class="text-center align-middle">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            </div>
          </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  
  <?php include 'inc/footer.php' ?>

  <!-- End Footer -->


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
  $(document).ready(function() {
    // Define the password required for deletion
    var password = "cscro8";

    $('#fileTable').DataTable({
  "ajax": {
    "url": "file_listing.php",
    "dataSrc": "data"
  },
  "columns": [
    { "data": "file" },
    { "data": "modified" },
    {
      "data": null,
      "width": "30%",
      "className": "text-center",
      "render": function(data, type, row) {
        var downloadLink = '<a href="' + row.download + '" class="btn btn-primary btn-sm"><i class="bi bi-download"></i> Download</a>';
        var deleteButton = '<button class="deleteButton btn btn-danger btn-sm" data-file="' + row.file + '"><i class="bi bi-trash"></i> Delete</button>';
        var emailButton = '<button class="emailButton btn btn-success btn-sm" data-file="' + row.file + '"><i class="bi bi-envelope"></i> Send Email</button>';
        return downloadLink + ' ' + deleteButton + ' ' + emailButton;
      }
    }
  ],
  "order": [[1, "desc"]] // Sort by the second column in descending order
});

// Handle click event for the email button
$(document).on("click", ".emailButton", function() {
  var file = $(this).data("file");

  Swal.fire({
    title: "Enter email address",
    input: "email",
    inputAttributes: {
      autocapitalize: "off"
    },
    showCancelButton: true,
    confirmButtonText: "Send",
    showLoaderOnConfirm: true,
    preConfirm: function(email) {
      return new Promise(function(resolve) {
        if (email) {
          sendEmail(file, email)
            .then(function(response) {
              if (response === "success") {
                Swal.fire(
                  "Success",
                  "Email sent successfully!",
                  "success"
                );
                resolve();
              } else {
                Swal.fire(
                  "Error",
                  "Failed to send email, please check your internet connectivity and try again.",
                  "error"
                );
                resolve(false);
              }
            })
            .catch(function() {
              Swal.fire(
                "Error",
                "Failed to send email, please check your internet connectivity and try again.",
                "error"
              );
              resolve(false);
            });
        } else {
          Swal.showValidationMessage("Please enter an email address");
          resolve(false);
        }
      });
    },
    allowOutsideClick: false
  });
});


// Function to send email via AJAX
function sendEmail(file, email) {
  return new Promise(function(resolve, reject) {
    $.ajax({
      url: "send_email.php",
      method: "POST",
      data: { file: file, email: email },
      success: function(response) {
        resolve(response);
      },
      error: function() {
        reject();
      }
    });
  });
}

    // Handle delete button click event
    $('#fileTable').on('click', '.deleteButton', function() {
      var file = $(this).data('file');

      // Use SweetAlert2 for the delete confirmation with password prompt
      Swal.fire({
        title: 'Delete File',
        html: 'Are you sure you want to delete the file: <b>' + file + '</b>?',
        input: 'password',
        inputPlaceholder: 'Enter password',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it'
      }).then((result) => {
        if (result.isConfirmed) {
          var enteredPassword = result.value;

          // Check if the entered password matches the required password
          if (enteredPassword === password) {
            // Perform the delete operation for the selected file
            $.ajax({
              url: 'delete_file.php', // Replace with your server-side delete script
              type: 'POST',
              data: { file: file },
              success: function(response) {
                Swal.fire({
                  title: 'File Deleted',
                  text: response,
                  icon: 'success'
                });
                // Refresh the DataTable after successful deletion
                $('#fileTable').DataTable().ajax.reload();
              },
              error: function() {
                Swal.fire({
                  title: 'Error',
                  text: 'Error deleting the file',
                  icon: 'error'
                });
              }
            });
          } else {
            Swal.fire({
              title: 'Invalid Password',
              text: 'The entered password is incorrect. Please try again.',
              icon: 'error'
            });
          }
        }
      });
    });
  });
</script>
<script>
$(document).ready(function() {
  // Trigger backup process when the button is clicked
  $('#backupButton').click(function() {
    Swal.fire({
      title: 'Backup Database',
      text: 'Do you want to backup the database?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    }).then((result) => {
      if (result.isConfirmed) {
        // Backup the database
        backupDatabase();
      }
    });
  });

  // Function to backup the database
  function backupDatabase() {
    Swal.fire({
      title: 'Backing up.. please wait',
      html: 'Backing up and preparing to send the file to the System Administrator<br><b>kmbanoyo@csc.gov.ph</b>.',
      icon: 'info',
      allowOutsideClick: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    // Send an AJAX request to the server to perform the backup
    $.ajax({
      url: 'backup.php', // Replace with your server-side backup script
      type: 'POST',
      success: function(response) {
        // Check if the backup was successful
        if (response === 'success') {
          Swal.fire({
            title: 'Backup Sent',
            html: 'The database backup was sent successfully.',
            icon: 'success'
          }).then(function() {
            // Refresh the page
            location.reload();
          });
        } else {
          Swal.fire({
            title: 'Error',
            text: 'There was an error sending the database backup, it seems that we are able to save the backup file but we can`t email the file due to intermittent connection, please check your internet connectivity and try again.',
            icon: 'error'
          }).then(function() {
            // Refresh the page
            location.reload();
          });
        }
      },
      error: function() {
        Swal.fire({
          title: 'Error',
          text: 'There was an error performing the database backup, it seems that we are able to save the backup file but we can`t email the file due to intermittent connection, please check your internet connectivity and try again.',
          icon: 'error'
        }).then(function() {
            // Refresh the page
            location.reload();
          });
      }
    });
  }
});
</script>
</body>
</html>