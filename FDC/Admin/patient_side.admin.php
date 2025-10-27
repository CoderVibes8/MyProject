<?php
include "admin_function_DB/links.php";
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "admin_includes/Head.php"; ?>

<style>
    .side-close {
        background: transparent;
        border: none;
        font-size: 20px;
        line-height: 1;
        color: #38BDF8;
        cursor: pointer;
        transition: color 0.3s ease;
        margin: 0 0 0 12px;
    }



    .side-close:hover {
        color: #007bff;
        background: none;
    }

    /* Header strip for the patient */
    .welcome {
        background: rgba(255, 255, 255, .94);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 4px 14px rgba(2, 6, 23, .06);
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .welcome h2 {
        margin: 0;
        font-size: 20px;
        letter-spacing: .2px;
    }

    .welcome p {
        margin: 4px 0 0;
        color: #475569;
        font-size: 14px;
    }

    .welcome .grow {
        flex: 1;
    }

    .actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .welcome-btn {
        border: 0;
        border-radius: 12px;
        padding: 10px 14px;
        font-weight: 700;
        cursor: pointer;
        background: #0ea5e9;
        color: #fff;
        transition: transform .05s ease, background .2s ease;
    }

    .welcome-btn span {
        font-size: 12px;
        margin: 0;
        color: gray;
    }

    .welcome-btn:hover {
        background: #0369a1;
    }

    .welcome-btn:active {
        transform: translateY(1px);
    }

    .welcome-btn.alt {
        background: #f8fafc;
        color: #0f172a;
        border: 1px solid #e2e8f0;
        font-weight: 600;
    }
</style>

<body>


    <?php include "admin_includes/Header.php"; ?>
    <?php include "admin_includes/Sidebar.php"; ?>

    <main id="main" class="main">
        <?php include_once "admin_includes/Welcome.php"; ?>

        <section class="section dashboard mt-4">

            <div class="row">
                <div class="card h-100 w-100">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Overall Patient Lists</h4>
                    </div>
                    <div class="card-body">
                        <div class="pt-5 pb-2">
                            <div class="d-flex justify-content-center align-items-center">
                                <table class="table table-bordered">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Full Name</th>
                                            <th scope="col">Date of Birth</th>
                                            <th scope="col">Home Address</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Contact Number</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Credentials</th>
                                            <th scope="col">View Profile</th>
                                        </tr>
                                    </thead>

                                    <?php if ($approved): ?>
                                        <?php foreach ($approved as $index => $row): ?>
                                            <tr>
                                                <th><?php echo $index + 1; ?></th>
                                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['dob']); ?></td>
                                                <td><?php echo htmlspecialchars($row['home_address']); ?></td>
                                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                                <td><?php echo htmlspecialchars($row['role']); ?></td>
                                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                        <button class="btn btn-primary btn-sm w-auto" data-bs-toggle="modal" data-bs-target="#credentialsModal-<?php echo $index; ?>">
                                                            View Credentials
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center flex-wrap">
                                                        <a href="patient_profile.admin.php?id=<?php echo htmlspecialchars($row['user_id']); ?>" class="btn btn-success btn-sm w-auto">View Profile</a>
                                                    </div>
                                                </td>


                                                <!-- Modal for Viewing Credentials -->
                                                <div class="modal fade" id="credentialsModal-<?php echo $index; ?>" tabindex="-1" aria-labelledby="credentialsModalLabel-<?php echo $index; ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="credentialsModalLabel-<?php echo $index; ?>">Uploaded Credentials</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-6">
                                                                        <h6>Business Permit</h6>
                                                                        <img src="<?php echo '../uploads/' . htmlspecialchars($row['national_id']); ?>" class="img-fluid rounded mb-2" alt="Business Permit">
                                                                    </div>
                                                                    <div class="col-12 col-md-6">
                                                                        <h6>Accreditation Certificate</h6>
                                                                        <img src="<?php echo '../uploads/' . htmlspecialchars($row['philhealth_id']); ?>" class="img-fluid rounded mb-2" alt="Accreditation Certificate">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="10" class="text-center">No data available</td>
                                            </tr>
                                        <?php endif; ?>
                                </table>
                            </div>
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

    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
    include_once "admin_includes/Alert.php";
    ?>

</body>
</html>