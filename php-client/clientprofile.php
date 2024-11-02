<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>User Profile</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <link rel="icon" href="../assets/img/ClinicaLog.ico" type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: {
        families: ["Public Sans:300,400,500,600,700"]
      },
      custom: {
        families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
        urls: ["../css/fonts.min.css"],
      },
      active: function () {
        sessionStorage.fonts = true;
      },
    });
  </script>

  <!-- CSS Files -->
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/plugins.min.css" />
  <link rel="stylesheet" href="../css/kaiadmin.min.css" />
  <link rel="stylesheet" href="../css/client.css">

  <!-- ICONS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pQnI6Z1ypA1QPTDdTnYkkpN0sE+0ZK3SAs+69IXS7SgSR/RG6upgjB8cSBaHh0FYv3cwUqq3Kv1BrV3iwGsnZw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    .profile-image {
      text-align: center;
    }

    .profile-image img {
      border-radius: 50%;
      width: 150px;
      height: 150px;
      margin-bottom: 10px;
    }

    @media (max-width: 576px) {
      .profile-image img {
        width: 120px;
        height: 120px;
      }
    }

    .invalid {
      border-color: red !important;
    }

    .card-body {
      display: flex;
      flex-direction: column;
      justify-content: space-between; /* Ensure content is spaced evenly */
      max-height: 400px; /* Set a max height */
      overflow-y: auto; /* Enable vertical scroll */
    }

    .table-responsive {
      max-height: 300px; /* Set a max height for the table */
      overflow-y: auto; /* Enable vertical scroll */
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <div class="main-panel" id="clientpanel">
      <!-- Header -->
      <div class="main-header" id="client_header"></div>
      <!-- Main Content -->
      <div class="container" id="content">
        <div class="page-inner">
          <!-- Modal Structure -->
          <div class="row">
            <div class="col-12">
              <a href="javascript:history.back()" class="back-nav">
                <i class="fas fa-arrow-left"></i> Back to Appointment
              </a>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Account Settings</h4>
                </div>
                <div class="card-body">
                  <form action="change_password.php" method="POST" id="changePasswordForm" enctype="multipart/form-data">
                    <div class="profile-image">
                      <img id="profilePic" src="../assets/img/profile.jpg" alt="Profile Image" />
                      <input type="file" class="form-control" id="profilePicture" name="profilePicture" accept="image/*" />
                      <small class="form-text text-muted">Upload a new profile picture (optional)</small>
                    </div>
                    <div class="row mt-3">
                      <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="Jackilyn M. Furog" disabled />
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="jmfurog@usep.edu.ph" disabled />
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" required />
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="showCurrentPassword">
                          <label class="form-check-label" for="showCurrentPassword">Show Password</label>
                        </div>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required />
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="showNewPassword">
                          <label class="form-check-label" for="showNewPassword">Show Password</label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required />
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="showConfirmPassword">
                          <label class="form-check-label" for="showConfirmPassword">Show Password</label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<!-- Core JS Files -->
<script src="../assets/js/core/jquery-3.7.1.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>

  <!-- Core JS Files -->
  <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>

  <!-- jQuery Scrollbar -->
  <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

  <!-- Chart JS -->
  <script src="../assets/js/plugin/chart.js/chart.min.js"></script>

  <!-- jQuery Sparkline -->
  <script src="../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

  <!-- Chart Circle -->
  <script src="../assets/js/plugin/chart-circle/circles.min.js"></script>

  <!-- Datatables -->
  <script src="../assets/js/plugin/datatables/datatables.min.js"></script>

  <!-- Bootstrap Notify -->
  <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

  <!-- jQuery Vector Maps -->
  <script src="../assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
  <script src="../assets/js/plugin/jsvectormap/world.js"></script>

  <!-- Sweet Alert -->
  <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>

  <!-- Kaiadmin JS -->
  <script src="../assets/js/kaiadmin.min.js"></script>

      <script>
        $(document).ready(function () {
          // Load Header
          $("#client_header").load("clientheader.php", function (response, status, xhr) {
            if (status == "error") {
              console.log("Error loading header: " + xhr.status + " " + xhr.statusText);
            }
          });

          $("#changePasswordForm").on("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            // Get input values
            const currentPassword = $("#currentPassword").val();
            const newPassword = $("#newPassword").val();
            const confirmPassword = $("#confirmPassword").val();

            // Validate the current password (this should normally be done on the server-side)
            const correctPassword = "your_actual_password"; // Replace with actual logic

            if (currentPassword !== correctPassword) {
              $("#currentPassword").addClass("invalid");
              return; // Exit the function
            } else {
              $("#currentPassword").removeClass("invalid");
            }

            if (newPassword !== confirmPassword) {
              alert("New password and confirmation do not match.");
              return; // Exit the function
            }

            this.submit(); // Regular form submission
          });

          // Show password functionality
          $("#showCurrentPassword").on("change", function () {
            const type = $(this).is(":checked") ? "text" : "password";
            $("#currentPassword").attr("type", type);
          });

          $("#showNewPassword").on("change", function () {
            const type = $(this).is(":checked") ? "text" : "password";
            $("#newPassword").attr("type", type);
          });

          $("#showConfirmPassword").on("change", function () {
            const type = $(this).is(":checked") ? "text" : "password";
            $("#confirmPassword").attr("type", type);
          });
        });
      </script>
</body>

</html>