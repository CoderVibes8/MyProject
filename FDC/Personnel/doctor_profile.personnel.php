<?php
session_name("doctor_session");
session_start();
include_once "../Config/conn.config.php";

$doc_id = $_SESSION['doc_id'] ?? null;

try {
    $stmt = $conn->prepare("SELECT * FROM doctor_personal_info WHERE doc_id = :doc_id");
    $stmt->bindParam(':doc_id', $doc_id);
    $stmt->execute();
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$doctor) {
        $_SESSION['message'] = [
            'title' => 'Error',
            'message' => 'Doctor not found.',
            'type' => 'error'
        ];
        header("Location: ../auth/login.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['message'] = [
        'title' => 'Error',
        'message' => 'Database error: ' . $e->getMessage(),
        'type' => 'error'
    ];
    header("Location: ../auth/login.php");
    exit();
} catch (PDOException $e) {
    $_SESSION['message'] = [
        'title' => 'Error',
        'message' => 'Database connection failed: ' . $e->getMessage(),
        'type' => 'error'
    ];
    header("Location: ../index.php");
    exit();
}


// Get the count of unanswered questions
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

//Get all the pending questions
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

<style>
    .card-img-top {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
</style>



<body>

    <?php include_once "../Includes/Personnel_Header.php"; ?>
    <?php include_once "../Includes/Personnel_Sidebar.php"; ?>

    <main id="main" class="main">

        <section class="section dashboard">

            <section class="h-100 gradient-custom-2">
                <div class="container py-5 h-100">
                    <div class="row d-flex justify-content-center">
                        <div class="col col-lg-9 col-xl-8">
                            <div class="card">
                                <div class="rounded-top text-white d-flex flex-row" style="background-color:#A3D1C6; height:200px;">
                                    <div class="ms-4 mt-5 d-flex flex-column" style="width: 150px;">
                                        <img src="../assets/img/developers/cedric.jpg"
                                            alt="Generic placeholder image" class="img-fluid img-thumbnail mt-4 mb-2"
                                            style="width: 150px; z-index: 1">
                                        <!-- <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-dark text-body" data-mdb-ripple-color="dark" style="z-index: 1;">
                                            Edit profile
                                        </button> -->
                                    </div>
                                    <div class="ms-3" style="margin-top: 130px;">
                                        <h5>Cedric</h5>
                                        <p>Iloilo</p>
                                    </div>
                                </div>
                                <!-- <div class="p-4 text-black bg-body-tertiary">
                                    <div class="d-flex justify-content-end text-center py-1 text-body">
                                        <div>
                                            <p class="mb-1 h5">253</p>
                                            <p class="small text-muted mb-0">Photos</p>
                                        </div>
                                        <div class="px-3">
                                            <p class="mb-1 h5">1026</p>
                                            <p class="small text-muted mb-0">Followers</p>
                                        </div>
                                        <div>
                                            <p class="mb-1 h5">478</p>
                                            <p class="small text-muted mb-0">Following</p>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="card-body p-4 text-black mt-3">
                                    <div class="mb-4  text-body">
                                        <p class="lead fw-normal mb-1">Details</p>
                                        <div class="p-4 bg-body-tertiary">
                                            <p class="font-italic mb-1">Gender: Male</p>
                                            <p class="font-italic mb-1">Contact Number: 123-456-789</p>
                                            <p class="font-italic mb-0">Email: cabillonjohncedric@gmail.com</p>
                                        </div>
                                    </div>

                                    <div class="mb-4  text-body">
                                        <p class="lead fw-normal mb-1">Reason for Appointment</p>
                                        <div class="p-4 bg-body-tertiary">
                                            <p class="font-italic mb-1">............</p>
                                        </div>
                                    </div>

                                    <div class="mb-4  text-body">
                                        <p class="lead fw-normal mb-1">Preffered Time and Date</p>
                                        <div class="p-4 bg-body-tertiary">
                                            <p class="font-italic mb-1">Date: Oct 3, 2025:</p>
                                            <p class="font-italic mb-0">Time: 10:00pm:</p>
                                        </div>
                                    </div>

                                    <div class="mb-4  text-body">
                                        <p class="lead fw-normal mb-1">Preffered Time and Date</p>
                                        <div class="p-4 bg-body-tertiary">
                                            <p class="font-italic mb-1">Date: Oct 3, 2025:</p>
                                            <p class="font-italic mb-0">Time: 10:00pm:</p>
                                        </div>
                                    </div>

                                    <div class="mb-4  text-body">
                                        <p class="lead fw-normal mb-1">Preffered Time and Date</p>
                                        <div class="p-4 bg-body-tertiary">
                                            <p class="font-italic mb-1">Selected Doctor: James</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>


    </main><!-- End #main -->
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include_once "../Includes/Footer.php"; ?>
    <!-- End Footer -->

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