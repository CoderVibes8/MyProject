<?php
session_name('patient_session');
session_start();
include_once "../Config/conn.config.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
$user_id = $_SESSION['user_id'];


try {
    $user = $conn->prepare("SELECT up.*, uc.profile_picture FROM user_patient up 
                               LEFT JOIN user_credentials uc ON up.user_id = uc.user_id 
                               WHERE up.user_id = ?");
    $user->execute([$user_id]);
    $userProfile = $user->fetch(PDO::FETCH_ASSOC);

    $profile_picture = !empty($userProfile['profile_picture'])
        ? "../uploads/" . htmlspecialchars($userProfile['profile_picture'])
        : "../uploads/user.png";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$full_name = $userProfile['first_name'] . ' ' . $userProfile['last_name'];


?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "../Includes/Head.php"; ?>



<body>

    <?php include_once "../Includes/Header.php"; ?>
    <?php include_once "../Includes/Sidebar.php"; ?>


    <main id="main" class="main">

        <section class="section dashboard">
            <form action="../Auth/User/update_profile.php" method="POST" enctype="multipart/form-data">
                <div class="container profile-container" style="margin-left: -120px;">
                    <div class="main-body">
                        <div class="row gutters-sm">
                            <!-- Profile Picture Card -->
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body pt-4">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="rounded-circle" width="150" height="150">
                                            <div class="mt-3">
                                                <h4><?php echo htmlspecialchars($userProfile['first_name'] . ' ' . $userProfile['last_name']); ?></h4>
                                                <p class="text-secondary mb-1">User/Patient</p>
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
                                                <?php echo htmlspecialchars($userProfile['first_name'] . ' ' . $userProfile['last_name']); ?>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Address</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary"><?php echo htmlspecialchars($userProfile['home_address']); ?></div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Email</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary"><?php echo htmlspecialchars($userProfile['email']); ?></div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Contact Number</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary"><?php echo htmlspecialchars($userProfile['contact_number']); ?></div>
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
                                                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($userProfile['first_name']); ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- Last Name -->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Last Name</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($userProfile['last_name']); ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- Address -->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Address</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="text" name="home_address" class="form-control" value="<?php echo htmlspecialchars($userProfile['home_address']); ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- Email -->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Email</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($userProfile['email']); ?>">
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- Contact Number -->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Contact Number</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <input type="text" name="contact_number" class="form-control" value="<?php echo htmlspecialchars($userProfile['contact_number']); ?>">
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

    <?php include_once "../Includes/Footer.php"; ?>

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

    <?php include_once "../Includes/SweetAlert.php"; ?>

</body>
</html>