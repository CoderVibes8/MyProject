<?php
session_name('patient_session');
session_start();
include_once "../Config/conn.config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

//Retreives Appointment History
try {
    $history = $conn->prepare("SELECT aps.*, a.total_expense, af.file_path FROM appointments AS a 
                               LEFT JOIN appointment_schedule AS aps ON a.appointment_id = aps.appointment_id
                               LEFT JOIN appointment_files AS af ON a.appointment_id = af.appointment_id
                               WHERE aps.user_id = ? ORDER BY aps.appointment_date DESC, aps.appointment_time_start DESC");
    $history->execute([$user_id]);
    $historyRecords = $history->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


//Retreives Patient Records
try {
    $precords = $conn->prepare("SELECT * FROM patient_records WHERE user_id = ? ");
    $precords->execute([$user_id]);
    $patientRecords = $precords->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

//Retreives user info
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
?>


<!DOCTYPE html>
<html lang="en">

<?php include_once "../Includes/Head.php"; ?>

<body>

    <?php include_once "../Includes/Header.php"; ?>

    <?php include_once "../Includes/Sidebar.php"; ?>


    <main id="main" class="main">

        <?php include_once "../Includes/Welcome.php"; ?>

        <!-- Payment Receipt -->
        <section class="section dashboard mt-4">
            <div class="main-content">
                <div class="table-container">
                    <div class="table-header d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h2>Patient Records</h2>
                            <p class="mb-0">Click on any appointment to view details</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="patientTable" class="table table-hover table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Appointment ID</th>
                                    <th>Doctor Name</th>
                                    <th>Appointment Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($historyRecords)): ?>
                                    <?php foreach ($historyRecords as $patient): ?>
                                        <?php
                                        $apptId = "Appointment" . (int)$patient['appointment_id'];
                                        $doctorName = "Dr. " . htmlspecialchars($patient['doc_fn'] . ' ' . $patient['doc_ln']);
                                        $specialty = htmlspecialchars($patient['specialty'] ?? '');
                                        $rate = "₱" . number_format((float)($patient['total_expense'] ?? 0), 2);
                                        $date = date("F d, Y", strtotime($patient['appointment_date']));
                                        $timeStart = date("g:i a", strtotime($patient['appointment_time_start']));
                                        $timeEnd = date("g:i a", strtotime($patient['appointment_time_end']));
                                        $time = $timeStart . " to " . $timeEnd;
                                        $status = htmlspecialchars($patient['stat'] ?? '');
                                        $fileName = htmlspecialchars($patient['file_path'] ?? '');
                                        $filePath = !empty($fileName) ? "../uploads/Pdf_Files/" . $fileName : '';

                                        $modalId = "appointmentModal" . (int)$patient['appointment_id'];
                                        ?>
                                        <tr data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>">
                                            <td><?php echo $apptId; ?></td>
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
                                            <div class="history-records">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Appointment Details</h5>
                                                            <button type="button" class="custom-close" data-bs-dismiss="modal"
                                                                style="background: transparent; border: none; line-height: 1; cursor: pointer; transition: color 0.3s ease; margin: 0; color:#333;"><i class="fi fi-rr-cross"></i></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="record-grid">
                                                                <div class="record-item">
                                                                    <strong><i class="fi fi-rr-id-badge"></i> Appointment ID</strong>
                                                                    <span><?php echo $apptId; ?></span>
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
                                                                        <a href="<?php echo $filePath; ?>" target="_blank" class="btn btn-sm btn-outline-primary" download>
                                                                            <i class="bi bi-file-earmark-pdf"></i> Download PDF
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

    <?php include_once "../Includes/Footer.php"; ?>



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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flaticon@8.0.0/css/flaticon.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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