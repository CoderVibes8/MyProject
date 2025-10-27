<?php
session_name("doctor_session");
session_start();
include_once "../Config/conn.config.php";

$doc_id = $_SESSION['doc_id'] ?? null;

// Fetch doctor information
try {
    $doc = $conn->prepare("SELECT dac.email, dac.specialty, dpi.firstname, dpi.lastname, dpi.phone, dpi.profile_pic, dac.availability 
                            FROM doctor_acc_creation dac JOIN doctor_personal_info dpi ON dac.doc_id = dpi.doc_id 
                            WHERE dac.doc_id = :doc_id");

    $doc->bindParam(':doc_id', $doc_id);
    $doc->execute();
    $doctor = $doc->fetch(PDO::FETCH_ASSOC);

    if (!$doctor) {
        $_SESSION['message'] = [
            'title' => 'Error',
            'message' => 'Doctor not found. \n Please log in again.',
            'type' => 'error'
        ];
        header("Location: ../index.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['message'] = [
        'title' => 'Error',
        'message' => 'Database connection failed: ' . $e->getMessage(),
        'type' => 'error'
    ];
    header("Location: ../index.php");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM unanswered_questions WHERE stat = 'pending'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $pendingCount = $result['total'];
} catch (PDOException $e) {
    $_SESSION['message'] = [
        'title' => 'Error',
        'message' => 'Database error: ' . $e->getMessage(),
        'type' => 'error'
    ];
    header("Location: dashboard.doctor.php");
    exit();
}

try {
    $q = $conn->prepare("SELECT id, question  FROM unanswered_questions WHERE stat = 'pending'");
    $q->execute();
    $questions = $q->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['message'] = [
        'title' => 'Error',
        'message' => 'Database error: ' . $e->getMessage(),
        'type' => 'error'
    ];
    header("Location: dashboard.doctor.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "../Includes/Personnel_Head.php"; ?>

<body>

    <?php include_once "../Includes/Personnel_Header.php"; ?>
    <?php include_once "../Includes/Personnel_Sidebar.php"; ?>

    <main id="main" class="main">


        <section class="section dashboard personnel-profile">
            <div class="container">
                <div class="main-body">
                    <div class="row gutters-sm">

                        <!-- Profile Picture Card -->
                        <div class="col-md-4 mb-3">
                            <div class="card card-picture">
                                <div class="card-body pt-4">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        <img src="../uploads/<?php echo htmlspecialchars($doctor['profile_pic']); ?>" alt="Doctor Profile" class="rounded-circle" width="150" height="150">
                                        <div class="mt-3">
                                            <h4><?php echo htmlspecialchars($doctor['firstname']) . ' ' . htmlspecialchars($doctor['lastname']); ?></h4>

                                            <!-- Separate Availability Form -->
                                            <form action="../auth/Personnel/update_availability.php" method="POST">
                                                <div class="dropdown">
                                                    <?php
                                                    $availability = htmlspecialchars($doctor['availability'] ?? 'Not Available');
                                                    $btnClass = $availability === 'Available' ? 'btn-success' : 'btn-danger';
                                                    ?>
                                                    <button class="btn <?php echo $btnClass; ?> btn-sm dropdown-toggle"
                                                        type="button"
                                                        id="statusDropdown"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <?php echo $availability; ?>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                                        <li><button type="submit" name="availability" value="Available" class="dropdown-item">Available</button></li>
                                                        <li><button type="submit" name="availability" value="Not Available" class="dropdown-item">Not Available</button></li>
                                                    </ul>
                                                </div>
                                            </form>

                                            <p class="text-secondary mb-1">Doctor</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile and Edit Panel -->
                        <div class="col-md-8 card card-text p-3">
                            <!-- Profile form starts here -->
                            <form action="../auth/Personnel/update_profile.php" method="POST" enctype="multipart/form-data">

                                <!-- Navigation Tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="profileTab" style="cursor: pointer;">Profile</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="editProfileTab" style="cursor: pointer;">Edit Profile</a>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" id="consultationInfoTab" style="cursor: pointer;">Edit Consultation</a>
                                    </li> -->
                                </ul>

                                <!-- Non-Editable Profile View -->
                                <div class="card col-md-12 mb-3" id="viewProfile">
                                    <div class="card-body pt-4">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Full Name</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary"><?php echo htmlspecialchars($doctor['firstname'] . ' ' . $doctor['lastname']); ?></div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Email</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary"><?php echo htmlspecialchars($doctor['email']); ?></div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Phone</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary"><?php echo htmlspecialchars($doctor['phone']); ?></div>
                                        </div>
                                        <hr>
                                    </div>

                                </div>

                                <!-- Non-Editable Consultation View -->
                            

                                <!-- Editable Profile Form -->
                                <div class="card col-md-12 mb-3 d-none" id="editProfile">
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
                                                <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($doctor['firstname']); ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- Last Name -->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Last Name</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($doctor['lastname']); ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- Email -->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Email</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($doctor['email']); ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- Phone -->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Phone Number</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="number" name="phone" class="form-control" value="<?php echo htmlspecialchars($doctor['phone']); ?>">
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

                                <!-- Editable Consulation Info -->
                        
                            </form> <!-- end of profile form -->
                        </div>

                        <!-- JavaScript for Tabs -->
                        <script>
                            document.getElementById("editProfileTab").addEventListener("click", function(event) {
                                event.preventDefault();
                                document.getElementById("viewProfile").classList.add("d-none");
                                document.getElementById("editProfile").classList.remove("d-none");

                                document.getElementById("profileTab").classList.remove("active");
                                document.getElementById("editProfileTab").classList.add("active");
                            });

                            document.getElementById("profileTab").addEventListener("click", function(event) {
                                event.preventDefault();
                                document.getElementById("editProfile").classList.add("d-none");
                                document.getElementById("viewProfile").classList.remove("d-none");

                                document.getElementById("editProfileTab").classList.remove("active");
                                document.getElementById("profileTab").classList.add("active");

                            });
                        </script>

                    </div>
                </div>
            </div>
        </section>

        <!-- Modal for View -->
        <div class="modal fade" id="viewID" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">License ID</h5>
                        <button type="button" class="close ms-auto" data-dismiss="modal" aria-label="Close" style="border: none; background: none; font-size: 1.5rem;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="<?php echo "../uploads" . htmlspecialchars($doctorAcc['professional_license_id']); ?>" alt="License Image" class="img-fluid" width="200px">
                    </div>
                </div>
            </div>
        </div>

    </main><!-- End #main -->
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include_once "../Includes/Footer.php"; ?>
    <!-- End Footer -->

    <!-- Status -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const statusButton = document.getElementById("statusDropdown");
            const statusOptions = document.querySelectorAll(".status-option");

            statusOptions.forEach(option => {
                option.addEventListener("click", function(e) {
                    e.preventDefault();
                    let selectedStatus = this.getAttribute("data-status");

                    // Update button text
                    statusButton.textContent = selectedStatus;

                    // Change button color based on status
                    if (selectedStatus === "Available") {
                        statusButton.classList.remove("btn-secondary");
                        statusButton.classList.add("btn-success");
                    } else {
                        statusButton.classList.remove("btn-success");
                        statusButton.classList.add("btn-secondary");
                    }
                });
            });
        });
    </script>


    <!-- Modal JS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = new bootstrap.Modal(document.getElementById("exampleModalCenter"));

            document.querySelectorAll(".open-modal").forEach(button => {
                button.addEventListener("click", function() {
                    modal.show();
                });
            });
        });
    </script>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Vendor JS Files -->
    <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../assets/vendor/echarts/echarts.min.js"></script>
    <script src="../assets/vendor/quill/quill.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <?php
    include_once "../Includes/SweetAlert.php";
    ?>

</body>
</html>