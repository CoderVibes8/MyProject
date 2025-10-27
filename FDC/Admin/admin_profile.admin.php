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

    /* 💻 Small desktops / large tablets (with sidebar offset) */
    @media (min-width: 769px) and (max-width: 1020px) {
        section.section.dashboard {
            margin-left: 300px;
            /* ✅ Push main content to the right of sidebar */
            width: calc(100% - 300px);
            /* Prevent horizontal scroll */
        }
    }
</style>

<body>


    <?php include_once "admin_includes/Header.php"; ?>
    <?php include_once "admin_includes/Sidebar.php"; ?>

    <main id="main" class="main">



        <section class="section dashboard">
            <form action="../auth/Personnel/update_profile.auth.php" method="POST" enctype="multipart/form-data">
                <div class="container">
                    <div class="main-body">
                        <div class="row gutters-sm">
                            <!-- Profile Picture Card -->
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body pt-4">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <img src="<?php echo '../uploads/' . htmlspecialchars($adminAcc['profile']);; ?>" alt="Profile Picture" class="rounded-circle" width="150">
                                            <div class="mt-3">
                                                <h4><?php echo htmlspecialchars($adminAcc['first_name'] . ' ' . $adminAcc['last_name']); ?></h4>
                                                <p class="text-secondary mb-1"><?php echo htmlspecialchars($adminAcc['role']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Profile and Edit Panel -->
                            <div class="col-md-8 card p-2">
                                <!-- Navigation Tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="profileTab">Profile</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="editProfileTab">Edit Profile</a>
                                    </li>
                                </ul>

                                <!-- Non-Editable Profile View -->
                                <div class="card mb-3" id="viewProfile">
                                    <div class="card-body pt-4">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Full Name</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <?php echo htmlspecialchars($adminAcc['first_name'] . ' ' . $adminAcc['last_name']); ?>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Email</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary"><?php echo htmlspecialchars($adminAcc['email']); ?></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Editable Profile Form -->
                                <div class="card mb-3 d-none" id="editProfile">
                                    <div class="card-body pt-4">
                                        <!-- Profile Picture Upload -->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Profile Picture</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="file" class="form-control" name="profile_picture" accept="image/*">
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- First Name -->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">First Name</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($adminAcc['first_name']); ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- Last Name -->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Last Name</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($adminAcc['last_name']); ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- Email -->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Email</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($adminAcc['email']); ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- Submit Button -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- JavaScript for toggling views -->
                            <script>
                                document.getElementById("editProfileTab").addEventListener("click", function(event) {
                                    event.preventDefault();
                                    document.getElementById("viewProfile").classList.add("d-none");
                                    document.getElementById("editProfile").classList.remove("d-none");

                                    // Update active tab styles
                                    document.getElementById("profileTab").classList.remove("active");
                                    document.getElementById("editProfileTab").classList.add("active");
                                });

                                document.getElementById("profileTab").addEventListener("click", function(event) {
                                    event.preventDefault();
                                    document.getElementById("editProfile").classList.add("d-none");
                                    document.getElementById("viewProfile").classList.remove("d-none");

                                    // Update active tab styles
                                    document.getElementById("editProfileTab").classList.remove("active");
                                    document.getElementById("profileTab").classList.add("active");
                                });
                            </script>

                            <!-- Bootstrap Styling -->
                            <style>
                                .nav-tabs .nav-link {
                                    cursor: pointer;
                                    font-weight: bold;
                                }

                                .nav-tabs .nav-link:hover {
                                    color: #007bff;
                                }
                            </style>
                        </div>
                    </div>
                </div>
            </form>
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