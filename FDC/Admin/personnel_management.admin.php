<?php
include "admin_function_DB/links.php";
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "admin_includes/Head.php"; ?>

<style>
  .custom-close {
    background: none;
    border: none;
    outline: none;
    box-shadow: none;
    color: #333;
    font-size: 15px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
  }

  .custom-close:hover,
  .custom-close:focus {
    background: none !important;
    color: #333 !important;
    outline: none !important;
    box-shadow: none !important;
  }




  /* 🎨 Default (Desktop ≥1021px) */
  .section.dashboard .row {
    display: flex;
    flex-wrap: nowrap;
    gap: 1rem;
  }

  .section.dashboard .card {
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .section.dashboard .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
  }

  .section.dashboard .card-title {
    font-size: 18px;
    font-weight: 600;
  }

  /* 📱 ≤480px — Mobile */
  @media (max-width: 480px) {
    .section.dashboard .row {
      margin-left: 0;
      flex-direction: column;
      align-items: center;
      gap: 10px;
    }

    .section.dashboard .col-6,
    .section.dashboard .col-md-3 {
      width: 90% !important;
    }

    .section.dashboard .card {
      width: 100%;
      height: auto;
      padding: 10px;
    }

    .section.dashboard .card-title {
      font-size: 14px;
    }

    .section.dashboard .card-icon i {
      font-size: 2em !important;
    }

    /* 📦 Modal Adjustments */
    .modal-dialog {
      max-width: 95%;
      margin: 10px auto;
    }

    .modal-content {
      padding: 15px;
    }

    .modal-title {
      font-size: 16px;
    }

    .form-group label {
      font-size: 13px;
    }

    .btn {
      font-size: 13px;
    }
  }

  /* 📲 481px - 768px — Tablets / Small Screens */
  @media (min-width: 481px) and (max-width: 768px) {
    .section.dashboard .row {
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
    }

    .section.dashboard .col-6,
    .section.dashboard .col-md-3 {
      flex: 0 0 45%;
      max-width: 45%;
    }

    .section.dashboard .card {
      width: 100%;
      height: 180px;
    }

    .section.dashboard .card-title {
      font-size: 16px;
    }

    .section.dashboard .card-icon i {
      font-size: 2.5em !important;
    }

    /* 🧩 Modal */
    .modal-dialog {
      max-width: 85%;
    }

    .modal-title {
      font-size: 17px;
    }

    .form-group label {
      font-size: 14px;
    }

    .btn {
      font-size: 14px;
    }
  }

  /* 💻 769px - 1020px — Small Desktops / Large Tablets */
  @media (min-width: 769px) and (max-width: 1020px) {
    .section.dashboard .row {
      flex-wrap: wrap;
      justify-content: flex-start;
      gap: 20px;
    }

    .section.dashboard .col-md-3 {
      flex: 0 0 30%;
      max-width: 30%;
    }

    .section.dashboard .card {
      width: 100%;
      height: 200px;
    }

    .section.dashboard .card-title {
      font-size: 17px;
    }

    .section.dashboard .card-icon i {
      font-size: 2.8em !important;
    }

    /* Modal Styling */
    .modal-dialog {
      max-width: 75%;
    }

    .modal-content {
      padding: 25px;
    }

    .modal-title {
      font-size: 18px;
    }

    .btn {
      font-size: 15px;
    }
  }
</style>

<body>


  <?php include_once "admin_includes/Header.php"; ?>
  <?php include_once "admin_includes/Sidebar.php"; ?>

  <main id="main" class="main">
    <?php include_once "admin_includes/Welcome.php"; ?>
    <section class="section dashboard">

      <div class="row w-100 justify-content-start flex-nowrap gap-3 mt-4">

        <!-- Add Personnel Card -->
        <a data-toggle="modal" data-target="#add_personnel" class="col-md-3 col-6">
          <div class="card info-card patient-card h-100 w-100 d-flex flex-column align-items-center">
            <div class="card-body text-center">
              <h5 class="card-title mb-3">Add Personnel</h5>
              <div class="d-flex align-items-center justify-content-center mb-3">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fi fi-ts-plus-hexagon" style="font-size: 3em;"></i>
                </div>
              </div>
            </div>
          </div>
        </a>

        <!-- Add Doctor Card -->
        <a data-toggle="modal" data-target="#add_doctor" class="col-md-3 col-6">
          <div class="card info-card patient-card h-100 w-100 d-flex flex-column align-items-center">
            <div class="card-body text-center">
              <h5 class="card-title mb-3">Add Doctor</h5>
              <div class="d-flex align-items-center justify-content-center mb-3">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fi fi-ts-plus-hexagon" style="font-size: 3em;"></i>
                </div>
              </div>
            </div>
          </div>
        </a>

        <!-- Personnel List Card -->
        <a href="PersonnelList.admin.php" class="col-md-3 col-6">
          <div class="card info-card patient-card h-100 w-100 d-flex flex-column align-items-center">
            <div class="card-body text-center">
              <h5 class="card-title mb-3">List of Personnels</h5>
              <div class="d-flex align-items-center justify-content-center mb-3">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fi fi-ss-employees" style="font-size: 3em;"></i>
                </div>
              </div>
            </div>
          </div>
        </a>

        <!-- Doctor List Card -->
        <a href="DoctorList.admin.php" class="col-md-3 col-6">
          <div class="card info-card patient-card h-100 w-100 d-flex flex-column align-items-center">
            <div class="card-body text-center">
              <h5 class="card-title mb-3">List of Doctors</h5>
              <div class="d-flex align-items-center justify-content-center mb-3">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fi fi-ss-employees" style="font-size: 3em;"></i>
                </div>
              </div>
            </div>
          </div>
        </a>

      </div>


      <!-- Add Personnel Modal -->
      <div class="modal fade" id="add_personnel" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content p-4">
            <div class="modal-header">
              <h5 class="modal-title">Add New Personnel</h5>
              <button type="close" class="custom-close" data-dismiss="modal" aria-label="Close"><i class="fi fi-bs-cross"></i></button>
            </div>

            <div class="modal-body">
              <form action="../Auth/Admin/AddPersonnel.auth.php" method="POST">

                <div class="form-group w-100">
                  <label class="font-weight-bold">Email</label>
                  <input type="email" class="form-control" name="email" placeholder="Enter doctor's email" required>
                </div>

                <div class="form-group w-100">
                  <label class="font-weight-bold">Password</label>
                  <div class="input-group w-100">
                    <input type="password" class="form-control" name="password" placeholder="Enter a temporary password" required>
                    <div class="input-group-append">
                      <button class="btn btn-outline-secondary toggle-password" type="button">
                        <i class="fa fa-eye"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="form-group w-100">
                  <button type="submit" class="btn btn-primary btn-block" name="add_doctor">Add Personnel</button>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>


      <!-- Add Doctor Modal -->
      <div class="modal fade" id="add_doctor" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content p-4">
            <div class="modal-header">
              <h5 class="modal-title">Add New Doctor</h5>
              <button type="close" class="custom-close" data-dismiss="modal" aria-label="Close"><i class="fi fi-bs-cross"></i></button>
            </div>

            <div class="modal-body">
              <form action="../Auth/Admin/AddDoctor.auth.php" method="POST">

                <div class="form-group w-100">
                  <label class="font-weight-bold">Doctor First Name</label>
                  <input type="text" class="form-control" name="firstname" placeholder="Enter doctor's first name" required>
                </div>

                <div class="form-group w-100">
                  <label class="font-weight-bold">Doctor Last Name</label>
                  <input type="text" class="form-control" name="lastname" placeholder="Enter doctor's last name" required>
                </div>

                <div class="form-group w-100">
                  <label class="font-weight-bold">Doctor Specialty</label>
                  <select class="form-select form-control" name="specialty" required>
                    <option value="" selected disabled>Select doctor's specialty</option>
                    <option value="2D Echo">2D Echo</option>
                    <option value="Ultrasound">Ultrasound</option>
                    <option value="Vascular Studies">Vascular Studies</option>
                    <option value="DNA">DNA</option>
                    <option value="ECG">ECG</option>
                    <option value="Clinical Laboratory">Clinical Laboratory</option>
                    <option value="XRAY">XRAY</option>
                  </select>
                </div>

                <div class="form-group w-100">
                  <label class="font-weight-bold">Doctor Price</label>
                  <input type="text" class="form-control" name="price" placeholder="Enter doctor's price" required>
                </div>

                <div class="form-group w-100">
                  <button type="submit" class="btn btn-primary btn-block" name="add_doctor">Add Doctor</button>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>


    </section>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include "admin_includes/footer.php"; ?>


  <!-- jQuery and Bootstrap JS -->
  <!-- Include jQuery (Required for Bootstrap Modals to Work) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Include jQuery (Required for Bootstrap Modals to Work) -->


  <!-- Bootstrap JS (Ensure it's included) -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- Js for Modal -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Handle switching between modals
      document.querySelectorAll(".switch-modal").forEach(link => {
        link.addEventListener("click", function(event) {
          event.preventDefault();
          let targetModal = this.getAttribute("data-target");

          $(".modal").modal("hide"); // Hide any open modal

          $(".modal").on("hidden.bs.modal", function() {
            $(targetModal).modal("show"); // Show the target modal
            $(".modal").off("hidden.bs.modal"); // Prevent multiple event bindings
          });
        });
      });

      // Ensure modals remove backdrops properly when closed
      $(".modal").on("hidden.bs.modal", function() {
        $("body").removeClass("modal-open"); // Remove class preventing scroll
        $(".modal-backdrop").remove(); // Remove any lingering modal-backdrop
      });

      // Fix password toggle visibility
      document.querySelectorAll(".toggle-password").forEach(button => {
        button.addEventListener("click", function() {
          let input = this.closest(".input-group").querySelector("input");
          input.type = input.type === "password" ? "text" : "password";
          this.firstElementChild.classList.toggle("fa-eye-slash");
        });
      });
    });
  </script>

  <script>
    $(document).ready(function() {
      $('#clinicTable').DataTable({
        "paging": true, // Enables pagination
        "searching": true, // Enables search
        "ordering": true, // Enables column sorting
        "lengthMenu": [5, 10, 25, 50], // Dropdown for number of rows per page
        "language": {
          "search": "Search Clinic:", // Customizing search box placeholder
          "lengthMenu": "Show _MENU_ entries per page"
        }
      });
    });
  </script>




  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js">
  </script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js">
  </script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- jQuery (required for DataTables) -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>


  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <?php
  include_once "admin_includes/Alert.php";
  ?>

</body>

</html>