<?php
session_name("doctor_session");
session_start();
include_once "../Config/conn.config.php";

$doc_id = $_SESSION['doc_id'] ?? null;

//fetch doctor details
try {
    $stmt = $conn->prepare("SELECT dac.email, dac.specialty, dpi.firstname, dpi.lastname, dpi.phone, dpi.profile_pic FROM doctor_acc_creation dac JOIN doctor_personal_info dpi ON dac.doc_id = dpi.doc_id WHERE dac.doc_id = :doc_id");
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

//retrieve all appointment history
try {
    $history = $conn->prepare("SELECT aps.*, a.total_expense, af.file_path, up.first_name, up.last_name FROM appointments AS a 
                               LEFT JOIN appointment_schedule AS aps ON a.appointment_id = aps.appointment_id
                               LEFT JOIN appointment_files AS af ON a.appointment_id = af.appointment_id
                               LEFT JOIN user_patient AS up ON a.user_id = up.user_id
                               ORDER BY aps.appointment_date DESC, aps.appointment_time_start DESC");
    $history->execute();
    $historyRecords = $history->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "../Includes/Personnel_Head.php"; ?>

<body>

    <?php include_once "../Includes/Personnel_Header.php"; ?>
    <?php include_once "../Includes/Personnel_Sidebar.php"; ?>

    <main id="main" class="main">

        <?php include_once "../Includes/Personnel_Welcome.php"; ?>

        <!-- Payment Receipt -->
        <section class="section dashboard mt-4">
            <div class="main-content">
                <div class="table-container">
                    <div class="table-header d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h2>My Records</h2>
                            <p class="mb-0">All appointment records are listed below.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="patientTable" class="table table-hover table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Appointment ID</th>
                                    <th>Patient Name</th>
                                    <th>Doctor Name</th>
                                    <th>Appointment Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($historyRecords)): ?>
                                    <?php foreach ($historyRecords as $records): ?>
                                        <?php
                                        $apptId = "Appointment" . (int)$records['appointment_id'];
                                        $patientName = htmlspecialchars($records['first_name'] . ' ' . $records['last_name']);
                                        $doctorName = "Dr. " . htmlspecialchars($records['doc_fn'] . ' ' . $records['doc_ln']);
                                        $specialty = htmlspecialchars($records['specialty'] ?? '');
                                        $rate = "₱" . number_format((float)($records['total_expense'] ?? 0), 2);
                                        $date = date("F d, Y", strtotime($records['appointment_date']));
                                        $timeStart = date("g:i a", strtotime($records['appointment_time_start']));
                                        $timeEnd = date("g:i a", strtotime($records['appointment_time_end']));
                                        $time = $timeStart . " to " . $timeEnd;
                                        $status = htmlspecialchars($records['stat'] ?? '');
                                        $fileName = htmlspecialchars($records['file_path'] ?? '');
                                        $filePath = !empty($fileName) ? "../uploads/Pdf_Files/" . $fileName : '';

                                        $modalId = "recordsdetailsModal" . (int)$records['appointment_id'];
                                        ?>
                                        <tr data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>">
                                            <td><?php echo $apptId; ?></td>
                                            <td><?php echo $patientName; ?></td>
                                            <td><?php echo $doctorName; ?></td>
                                            <td><?php echo $specialty; ?></td>
                                            <td><?php echo $date; ?></td>
                                            <td>
                                                <span class="badge 
                                            <?php echo ($status === 'Approved') ? 'bg-primary' : (($status === 'Cancelled') ? 'bg-danger' : (($status === 'Done') ? 'bg-success' : (($status === 'Pending') ? 'bg-warning' : 'bg-secondary'))); ?>">
                                                    <?php echo $status; ?>
                                                </span>
                                            </td>
                                        </tr>

                                        <!-- Appointment Details Modal -->
                                        <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Appointment Details</h5>
                                                        <button type="button" class="custom-close" data-bs-dismiss="modal"
                                                            style="background: transparent; border: none; line-height: 1; cursor: pointer; transition: color 0.3s ease; margin: 0; color:#333;">
                                                            <i class="fi fi-rr-cross"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="record-grid">
                                                            <div class="record-item">
                                                                <strong><i class="fi fi-rr-id-badge"></i> Appointment ID</strong>
                                                                <span><?php echo $apptId; ?></span>
                                                            </div>
                                                            <div class="record-item">
                                                                <strong><i class="fi fi-rr-user"></i> Patient</strong>
                                                                <span><?php echo $patientName; ?></span>
                                                            </div>
                                                            <div class="record-item">
                                                                <strong><i class="fi fi-ts-user-md"></i> Doctor</strong>
                                                                <span><?php echo $doctorName; ?> (<?php echo $specialty; ?>)</span>
                                                            </div>
                                                            <div class="record-item">
                                                                <strong><i class="fi fi-rr-star"></i> Rate</strong>
                                                                <span><?php echo $rate; ?></span>
                                                            </div>
                                                            <div class="record-item">
                                                                <strong><i class="fi fi-rr-calendar"></i> Date</strong>
                                                                <span><?php echo $date; ?></span>
                                                            </div>
                                                            <div class="record-item">
                                                                <strong><i class="fi fi-rr-clock"></i> Time</strong>
                                                                <span><?php echo $time; ?></span>
                                                            </div>
                                                            <div class="record-item">
                                                                <strong><i class="fi fi-ss-check-circle"></i> Status</strong>
                                                                <span><?php echo $status; ?></span>
                                                            </div>
                                                            <div class="record-item">
                                                                <strong><i class="fi fi-rr-document"></i> PDF File</strong>
                                                                <?php if (!empty($filePath)): ?>
                                                                    <a class="btn btn-sm btn-outline-primary">
                                                                        <i class="bi bi-file-earmark-pdf"></i> <?php echo $filePath; ?>
                                                                    </a>
                                                                <?php else: ?>
                                                                    <span class="pdf-status">No file uploaded</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <div id="pagination" class="mt-3 d-flex justify-content-center">
                            <button id="prevBtn" class="btn btn-outline-primary btn-sm me-2">Previous</button>
                            <span id="pageInfo" class="align-self-center"></span>
                            <button id="nextBtn" class="btn btn-outline-primary btn-sm ms-2">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->



    <!-- ======= Footer ======= -->
    <?php include_once "../Includes/Footer.php"; ?>
    <!-- End Footer -->



    <!-- Pagination -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const rows = document.querySelectorAll("#patientTable tbody tr");
            const rowsPerPage = 5;
            let currentPage = 1;

            function showPage(page) {
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                rows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? "" : "none";
                });

                // Update page info
                document.getElementById("pageInfo").textContent =
                    `Page ${currentPage} of ${Math.ceil(rows.length / rowsPerPage)}`;

                // Disable buttons if needed
                document.getElementById("prevBtn").disabled = (page === 1);
                document.getElementById("nextBtn").disabled = (page === Math.ceil(rows.length / rowsPerPage));
            }

            // Event listeners
            document.getElementById("prevBtn").addEventListener("click", function() {
                if (currentPage > 1) {
                    currentPage--;
                    showPage(currentPage);
                }
            });

            document.getElementById("nextBtn").addEventListener("click", function() {
                if (currentPage < Math.ceil(rows.length / rowsPerPage)) {
                    currentPage++;
                    showPage(currentPage);
                }
            });

            // Initialize first page
            showPage(currentPage);
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap 5 JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-dF5QocP7V9bP4k9LkS0PjTI+lNKBu3V2Oy5VyoVovvN52EvP7zFZR3qEl0nYtN4D" crossorigin="anonymous"></script>






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