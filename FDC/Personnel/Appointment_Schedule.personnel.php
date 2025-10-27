<?php
session_name("doctor_session");
session_start();
include_once "../Config/conn.config.php";

$doc_id = $_SESSION['doc_id'] ?? null;

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
    // Retrieve appointment schedules for the personnel
    $sql = "
        SELECT aps.*
        FROM appointment_schedule aps
        LEFT JOIN appointments ap ON aps.appointment_id = ap.appointment_id
        WHERE aps.stat IN ('Approved', 'Done')
        ORDER BY aps.appointment_date ASC, aps.appointment_time_start ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Find the newest created_at
    $newestCreatedAt = null;
    if ($appointments) {
        $newestCreatedAt = max(array_column($appointments, 'created_at'));
    }

    // === Categorize Appointments First ===
    $todayAppointments = [];
    $upcomingAppointments = [];
    $doneAppointments = [];

    $todayDate = date("Y-m-d");

    if ($appointments) {
        foreach ($appointments as $appointment) {
            $stat = strtolower($appointment['stat']);
            $apptDate = $appointment['appointment_date'];

            if ($stat === "done") {
                $doneAppointments[] = $appointment;
            } elseif ($apptDate === $todayDate && $stat !== "cancelled" && $stat !== "done") {
                $todayAppointments[] = $appointment;
            } elseif ($apptDate > $todayDate && $stat !== "cancelled" && $stat !== "done") {
                $upcomingAppointments[] = $appointment;
            }
        }
    }
} catch (PDOException $e) {
    // Handle query or connection errors
    echo "Error fetching appointment schedules: " . $e->getMessage();
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

        <div class="schedule-container">
            <!-- Header -->
             
            <section class="header-schedule">
                <div class="title ">Your Appointment Schedules</div>
                <!-- Filter by Date -->
                <!-- <h4 class="appointment_date-title">By Date</h4>
                <div class="appointment_date">
                    <button class="appointment_date-btn active" data-filter="all" data-type="date">
                        <span class="appointment_date-dot" style="background: #6b7280;"></span>
                        All <span class="filter-count">8</span>
                    </button>
                    <button class="appointment_date-btn" data-filter="today" data-type="date">
                        <span class="appointment_date-dot dot-today"></span>
                        Today <span class="filter-count">3</span>
                    </button>
                    <button class="appointment_date-btn" data-filter="tomorrow" data-type="date">
                        <span class="appointment_date-dot dot-upcomming"></span>
                        Upcomming <span class="filter-count">2</span>
                    </button>
                </div> -->

                <!-- Filter by Status -->
                <!-- <div class="appointment_status">
                    <h4 class="appointment_status-title">By Status</h4>
                    <div class="filter-buttons">
                        <button class="appointment_status-btn active" data-filter="all" data-type="status">
                            <span class="appointment_status-dot" style="background: #6b7280;"></span>
                            All <span class="filter-count">8</span>
                        </button>
                        <button class="appointment_status-btn" data-filter="upcoming" data-type="status">
                            <span class="appointment_status-dot dot-upcoming"></span>
                            Done <span class="filter-count">3</span>
                        </button>
                        <button class="appointment_status-btn" data-filter="approved" data-type="status">
                            <span class="appointment_status-dot dot-approved"></span>
                            Approved <span class="filter-count">3</span>
                        </button>
                        <button class="appointment_status-btn" data-filter="pending" data-type="status">
                            <span class="appointment_status-dot dot-pending"></span>
                            Pending <span class="filter-count">2</span>
                        </button>
                    </div>
                </div> -->
            </section>


            <!-- ================= TODAY ================= -->
            <div class="appointment-group">
                <h3 class="appointed-schedule">Today</h3>
                <section class="schedule-list" aria-label="Schedule list">
                    <?php if (!empty($todayAppointments)): ?>
                        <?php foreach ($todayAppointments as $appointment): ?>
                            <?php
                            $appointmentDateTime = $appointment['appointment_date'] . ' ' . $appointment['appointment_time_start'];
                            $isNewest = ($appointment['created_at'] === $newestCreatedAt);
                            $stat = strtolower($appointment['stat']);
                            $badgeClass = "bg-secondary";

                            if ($stat === "done") {
                                $badgeClass = "bg-success";
                            } elseif ($stat === "cancelled") {
                                $badgeClass = "bg-danger";
                            } elseif ($stat === "upcoming") {
                                $badgeClass = "bg-primary";
                            }
                            ?>
                            <article class="card">
                                <div class="schedule-time">
                                    <div class="t-main"><?= date("g:i A", strtotime($appointment['appointment_time_start'])) ?></div>
                                    <div class="t-sub"><?= date("D, M j", strtotime($appointment['appointment_date'])) ?></div>
                                </div>
                                <div class="info">
                                    <div class="name">
                                        Dr. <?= htmlspecialchars($appointment['doc_fn']) ?> <?= htmlspecialchars($appointment['doc_ln']) ?>
                                        (<?= htmlspecialchars($appointment['specialty']) ?>)
                                        <span class="badge <?= $badgeClass ?> status-badge"><?= ucfirst($stat) ?></span>
                                    </div>
                                    <div class="meta">
                                        <?php if ($isNewest): ?>
                                            <span class="badge bg-primary">Next Appointment</span>
                                        <?php else: ?>
                                            <span class="type clinic"><span class="dot d-clinic"></span> In-Clinic</span>
                                        <?php endif; ?>
                                        <span class="muted countdown" data-appointment="<?= $appointmentDateTime ?>">Calculating...</span>
                                    </div>
                                </div>
                                <div class="actions">
                                    <?php if ($stat === 'approved'): ?>
                                        <button class="schedule-btn btn btn-success approve-btn"
                                            data-id="<?= $appointment['appointment_id'] ?>"
                                            data-user-id="<?= $appointment['user_id'] ?>"
                                            data-personnel-id="<?= $doc_id ?>"
                                            data-doc-id="<?= $appointment['doc_id'] ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#uploadModal">
                                            Mark as Done
                                        </button>
                                    <?php else: ?>
                                        <button class="schedule-btn btn btn-secondary" disabled>Done</button>
                                    <?php endif; ?>

                                    <?php if ($stat !== 'cancelled' && $stat !== 'done'): ?>
                                        <button class="schedule-btn btn btn-danger cancel-btn"
                                            data-id="<?= $appointment['appointment_id'] ?>"
                                            data-user-id="<?= $appointment['user_id'] ?>"
                                            data-doc-id="<?= $appointment['doc_id'] ?>">
                                            Decline
                                        </button>
                                    <?php else: ?>
                                        <button class="schedule-btn btn btn-secondary" disabled>Declined</button>
                                    <?php endif; ?>

                                    <button class="schedule-btn btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#personnel_appointment_detailsModal"
                                        data-id="<?= $appointment['appointment_id'] ?>">
                                        Details
                                    </button>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <article class="card no-schedule">
                            <div class="schedule-time">
                                <div class="t-main">--:--</div>
                                <div class="t-sub"><?= date("D, M j") ?></div>
                            </div>
                            <div class="info">
                                <div class="name">No available schedule today</div>
                                <div class="meta">
                                    <span class="type clinic"><span class="dot d-clinic"></span> Please check upcoming</span>
                                </div>
                            </div>

                            <div class="actions"><button class="schedule-btn btn btn-secondary" disabled>Unavailable</button></div>
                        </article>
                    <?php endif; ?>
                </section>
            </div>

            <!-- ================= UPCOMING ================= -->
            <div class="appointment-group">
                <h3 class="appointed-schedule">Upcoming</h3>
                <section class="schedule-list" aria-label="Schedule list">
                    <?php if (!empty($upcomingAppointments)): ?>
                        <?php foreach ($upcomingAppointments as $appointment): ?>
                            <?php
                            $appointmentDateTime = $appointment['appointment_date'] . ' ' . $appointment['appointment_time_start'];
                            $stat = strtolower($appointment['stat']);
                            $badgeClass = ($stat === "cancelled") ? "bg-danger" : "bg-primary";
                            ?>
                            <article class="card">
                                <div class="schedule-time">
                                    <div class="t-main"><?= date("g:i A", strtotime($appointment['appointment_time_start'])) ?></div>
                                    <div class="t-sub"><?= date("D, M j", strtotime($appointment['appointment_date'])) ?></div>
                                </div>
                                <div class="info">
                                    <div class="name">
                                        Dr. <?= htmlspecialchars($appointment['doc_fn']) ?> <?= htmlspecialchars($appointment['doc_ln']) ?>
                                        (<?= htmlspecialchars($appointment['specialty']) ?>)
                                        <span class="badge <?= $badgeClass ?> status-badge"><?= ucfirst($stat) ?></span>
                                    </div>
                                    <div class="meta">
                                        <span class="type clinic"><span class="dot d-clinic"></span> In-Clinic</span>
                                        <span class="muted countdown" data-appointment="<?= $appointmentDateTime ?>">Calculating...</span>
                                    </div>
                                </div>
                                <div class="actions">
                                    <!-- ✅ all your buttons kept -->
                                    <?php if ($stat === 'approved'): ?>
                                        <button class="schedule-btn btn btn-success approve-btn"
                                            data-id="<?= $appointment['appointment_id'] ?>"
                                            data-user-id="<?= $appointment['user_id'] ?>"
                                            data-personnel-id="<?= $doc_id ?>"
                                            data-doc-id="<?= $appointment['doc_id'] ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#uploadModal">
                                            Mark as Done
                                        </button>
                                    <?php else: ?>
                                        <button class="schedule-btn btn btn-secondary" disabled>Done</button>
                                    <?php endif; ?>

                                    <?php if ($stat !== 'cancelled' && $stat !== 'done'): ?>
                                        <button class="schedule-btn btn btn-danger cancel-btn"
                                            data-id="<?= $appointment['appointment_id'] ?>"
                                            data-user-id="<?= $appointment['user_id'] ?>"
                                            data-doc-id="<?= $appointment['doc_id'] ?>">
                                            Decline
                                        </button>
                                    <?php else: ?>
                                        <button class="schedule-btn btn btn-secondary" disabled>Declined</button>
                                    <?php endif; ?>

                                    <button class="schedule-btn btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#personnel_appointment_detailsModal"
                                        data-id="<?= $appointment['appointment_id'] ?>">
                                        Details
                                    </button>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <article class="card no-schedule">
                            <div class="schedule-time">
                                <div class="t-main">--:--</div>
                                <div class="t-sub">---</div>
                            </div>
                            <div class="info">
                                <div class="name">No upcoming schedules.</div>
                            </div>
                            <div class="actions"><button class="schedule-btn btn btn-secondary" disabled>---</button></div>
                        </article>
                    <?php endif; ?>
                </section>
            </div>

            <!-- ================= DONE ================= -->
            <div class="appointment-group">
                <h3 class="appointed-schedule">Done</h3>
                <section class="schedule-list" aria-label="Schedule list">
                    <?php if (!empty($doneAppointments)): ?>
                        <?php foreach ($doneAppointments as $appointment): ?>
                            <?php
                            $appointmentDateTime = $appointment['appointment_date'] . ' ' . $appointment['appointment_time_start'];
                            ?>
                            <article class="card">
                                <div class="schedule-time">
                                    <div class="t-main"><?= date("g:i A", strtotime($appointment['appointment_time_start'])) ?></div>
                                    <div class="t-sub"><?= date("D, M j", strtotime($appointment['appointment_date'])) ?></div>
                                </div>
                                <div class="info">
                                    <div class="name">
                                        Dr. <?= htmlspecialchars($appointment['doc_fn']) ?> <?= htmlspecialchars($appointment['doc_ln']) ?>
                                        (<?= htmlspecialchars($appointment['specialty']) ?>)
                                        <span class="badge bg-success status-badge">Done</span>
                                    </div>
                                    <div class="meta"><span class="muted">Completed</span></div>
                                </div>
                                <div class="actions">
                                    <button class="schedule-btn btn btn-secondary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#personnel_resultsModal"
                                        data-id="<?= $appointment['appointment_id'] ?>" disabled>
                                        Done
                                    </button>

                                    <button class="schedule-btn btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#personnel_appointment_detailsModal"
                                        data-id="<?= $appointment['appointment_id'] ?>">
                                        Details
                                    </button>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <article class="card no-schedule">
                            <div class="schedule-time">
                                <div class="t-main">--:--</div>
                                <div class="t-sub">---</div>
                            </div>
                            <div class="info">
                                <div class="name">No completed schedules.</div>
                            </div>
                            <div class="actions"><button class="schedule-btn btn btn-secondary" disabled>---</button></div>
                        </article>
                    <?php endif; ?>
                </section>
            </div>


        </div>



        <!-- Appointment Details Modal -->
        <div class="modal fade" id="personnel_appointment_detailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h3 class="modal-title">Appointment Details</h3>
                        <button type="button" class="custom-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fi fi-rr-cross"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="record-grid">

                            <!-- Appointment ID -->
                            <div class="record-item">
                                <strong><i class="fi fi-rr-id-badge"></i> Appointment ID</strong>
                                <span id="appointment_id">12345</span>
                                <span class="pdf-status">Reference number for tracking</span>
                            </div>

                            <!-- Fullname -->
                            <div class="record-item">
                                <strong><i class="fi fi-rr-user"></i> Fullname</strong>
                                <span id="fullname">John Doe</span>
                                <span class="pdf-status">Registered patient</span>
                            </div>

                            <!-- Gender -->
                            <div class="record-item">
                                <strong><i class="fi fi-rr-venus-mars"></i> Gender</strong>
                                <span id="gender">Male</span>
                                <span class="pdf-status">As declared on profile</span>
                            </div>

                            <!-- Contact -->
                            <div class="record-item">
                                <strong><i class="fi fi-rr-phone-call"></i> Contact</strong>
                                <span id="contact">09123456789</span>
                                <span class="pdf-status">Patient’s contact number</span>
                            </div>

                            <!-- Email -->
                            <div class="record-item">
                                <strong><i class="fi fi-rr-envelope"></i> Email</strong>
                                <span id="email">john@example.com</span>
                                <span class="pdf-status">Patient’s registered email</span>
                            </div>

                            <!-- Appointment Date -->
                            <div class="record-item">
                                <strong><i class="fi fi-rr-calendar"></i> Appointment Date</strong>
                                <span id="appointment_date">2025-08-22</span>
                                <span class="pdf-status">Scheduled date</span>
                            </div>

                            <!-- Appointment Time -->
                            <div class="record-item">
                                <strong><i class="fi fi-rr-clock"></i> Appointment Time</strong>
                                <span id="appointment_time">10:00 AM</span>
                                <span class="pdf-status">Expected time slot</span>
                            </div>

                            <!-- Total Expenses -->
                            <div class="record-item">
                                <strong><i class="fi fi-rr-wallet"></i> Total Expenses</strong>
                                <span class="expense-amount">₱<span id="total_expenses">800.00</span></span>
                                <span class="pdf-status">Consultation + other charges</span>
                            </div>

                            <!-- Status -->
                            <div class="record-item">
                                <strong><i class="fi fi-ss-check-circle"></i> Status</strong>
                                <span>
                                    <span id="status" class="status-badge status-confirmed" style="color: #fff;">
                                        <span class="status-dot"></span>
                                        Confirmed
                                    </span>
                                </span>
                                <span class="pdf-status">Appointment status</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Personnel Result Modal -->
        <div class="modal fade" id="personnel_resultsModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resultModalLabel">Appointment Result</h5>
                        <button type="button" class="custom-close" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-rr-cross"></i></button>
                    </div>

                    <div class="modal-body text-center">
                        <div class="pdf-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13Vs9H18V20Z" />
                            </svg>
                        </div>
                        <h5>Your appointment result is ready. Click below to download:</h5>
                        <p>Your analysis has been completed successfully. Download the PDF report to view detailed results and insights.</p>
                        <a id="downloadPdf" href="#" download class="btn btn-primary">
                            <i class="bi bi-file-earmark-pdf"></i> Download Your Result
                        </a>
                    </div>

                </div>
            </div>
        </div>


        <!--Upload PDF Modal-->
        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Upload PDF for Appointment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="appointment_id" id="appointment_id">
                            <input type="hidden" name="user_id" id="user_id">
                            <input type="hidden" name="doc_id" id="doc_id">
                            <input type="hidden" name="personnel_id" id="personnel_id">

                            <div class="mb-3">
                                <label for="pdfFile" class="form-label">Upload PDF</label>
                                <input type="file" class="form-control" id="pdfFile" name="pdfFile" accept="application/pdf" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        </div>

    </main><!-- End #main -->

    <?php include_once "../Includes/Footer.php"; ?>

    <!-- Upload PDF Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let currentButton = null;
            let currentStatusBadge = null;

            const uploadModalEl = document.getElementById("uploadModal");
            const uploadModal = new bootstrap.Modal(uploadModalEl);

            // Approve button -> open modal + fill hidden fields
            document.querySelectorAll(".approve-btn").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    // Store the clicked button + status badge
                    currentButton = this;
                    currentStatusBadge = this.closest("tr")?.querySelector(".status-badge");

                    // Fill hidden inputs
                    document.getElementById("appointment_id").value = this.getAttribute("data-id");
                    document.getElementById("user_id").value = this.getAttribute("data-user-id");
                    document.getElementById("doc_id").value = this.getAttribute("data-doc-id");
                    document.getElementById("personnel_id").value = this.getAttribute("data-personnel-id");

                    // Show modal
                    uploadModal.show();
                });
            });

            // Handle PDF upload
            const uploadForm = document.getElementById("uploadForm");
            if (uploadForm) {
                uploadForm.addEventListener("submit", function(e) {
                    e.preventDefault();

                    // Collect values
                    const appointmentId = document.getElementById("appointment_id").value;
                    const userId = document.getElementById("user_id").value;
                    const docId = document.getElementById("doc_id").value;
                    const personnelId = document.getElementById("personnel_id").value;
                    const pdfFile = document.getElementById("pdfFile").files[0];

                    if (!pdfFile) {
                        alert("Please select a PDF file.");
                        return;
                    }

                    const formData = new FormData();
                    formData.append("appointment_id", appointmentId);
                    formData.append("user_id", userId);
                    formData.append("doc_id", docId);
                    formData.append("personnel_id", personnelId);
                    formData.append("pdfFile", pdfFile);

                    fetch("../Auth/Personnel/Upload_Appointment_File.personnel.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Update badge if exists
                                if (currentStatusBadge) {
                                    currentStatusBadge.textContent = "Done";
                                    currentStatusBadge.className = "badge bg-success status-badge";
                                }

                                // Update "Mark as Done" button
                                if (currentButton) {
                                    currentButton.textContent = "Done";
                                    currentButton.disabled = true;
                                    currentButton.classList.remove("btn-success");
                                    currentButton.classList.add("btn-secondary");
                                }


                                // Hide modal
                                const modalEl = document.getElementById("uploadModal");
                                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                                modalInstance.hide();

                                // Success popup
                                const msg = data.sessionMessage || {
                                    title: "Success!",
                                    message: "File uploaded successfully.",
                                    type: "success"
                                };

                                Swal.fire({
                                    title: msg.title,
                                    text: msg.message,
                                    icon: msg.type
                                }).then(() => {
                                    window.location.reload();
                                });

                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: data.message,
                                    icon: "error"
                                });
                            }
                        })
                        .catch(err => console.error("Upload failed", err));
                });
            }
        });
    </script>

    <!-- Appointment Details Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const appointmentModal = document.getElementById("personnel_appointment_detailsModal");

            appointmentModal.addEventListener("show.bs.modal", function(event) {
                const button = event.relatedTarget;
                const appointmentId = button.getAttribute("data-id");

                if (!appointmentId) return;

                // Fetch appointment details via AJAX
                fetch(`../Auth/Personnel/Get_Appointment_Details.php?id=${appointmentId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }

                        // Populate modal fields dynamically
                        document.getElementById("appointment_id").textContent = data.appointment_id;
                        document.getElementById("fullname").textContent = data.fullname;
                        document.getElementById("gender").textContent = data.gender;
                        document.getElementById("contact").textContent = data.contact;
                        document.getElementById("email").textContent = data.email;
                        document.getElementById("appointment_date").textContent = data.appointment_date;
                        document.getElementById("appointment_time").textContent =
                            new Date(`1970-01-01T${data.appointment_time_start}`).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            }) + " - " +
                            new Date(`1970-01-01T${data.appointment_time_end}`).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            });
                        document.getElementById("total_expenses").textContent = parseFloat(data.total_expense).toFixed(2);

                        const statusEl = document.getElementById("status");
                        statusEl.textContent = data.status;

                        let badgeClass = "bg-secondary";

                        if (data.status === "Approved") {
                            badgeClass = "bg-primary";
                        } else if (data.status === "Cancelled") {
                            badgeClass = "bg-danger";
                        } else if (data.status === "Done") {
                            badgeClass = "bg-success";
                        } else if (data.status === "Upcoming") {
                            badgeClass = "bg-secondary"; // changed color for Upcoming
                        } else if (data.status === "Pending") {
                            badgeClass = "bg-warning";
                        }

                        statusEl.className = "badge " + badgeClass;

                    })
                    .catch(err => console.error(err));
            });


            // this is for cancel button
            document.querySelectorAll(".cancel-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    const appointmentId = this.getAttribute("data-id");
                    const userId = this.getAttribute("data-user-id");
                    const docId = this.getAttribute("data-doc-id");
                    const button = this;
                    const statusBadge = button.closest(".card").querySelector(".status-badge");

                    Swal.fire({
                        title: "Are you sure?",
                        text: "Do you really want to decline this appointment?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#6c757d",
                        confirmButtonText: "Yes, decline it"
                    }).then((result) => {
                        if (!result.isConfirmed) return;

                        fetch("../Auth/Personnel/Cancel_Appointment.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/x-www-form-urlencoded"
                                },
                                body: "appointment_id=" + encodeURIComponent(appointmentId) +
                                    "&user_id=" + encodeURIComponent(userId) +
                                    "&doc_id=" + encodeURIComponent(docId)
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    if (statusBadge) {
                                        statusBadge.textContent = "Declined";
                                        statusBadge.className = "badge bg-danger status-badge";
                                    }

                                    button.textContent = "Declined";
                                    button.disabled = true;
                                    button.classList.remove("btn-danger");
                                    button.classList.add("btn-secondary");

                                    Swal.fire({
                                        icon: "success",
                                        title: "Declined",
                                        text: "The appointment has been successfully declined.",
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: data.message
                                    });
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Something went wrong!"
                                });
                            });
                    });
                });
            });
        });
    </script>

    <!-- Countdown Timer Script -->
    <script>
        function updateCountdowns() {
            const countdowns = document.querySelectorAll('.countdown');
            const now = new Date();

            countdowns.forEach(el => {
                const appointmentTime = new Date(el.dataset.appointment);
                const diffMs = appointmentTime - now;

                if (diffMs <= 0) {
                    el.innerHTML = "<strong>Ongoing or Passed</strong>";
                    return;
                }

                const diffMins = Math.floor(diffMs / (1000 * 60));
                const diffHours = Math.floor(diffMins / 60);
                const diffDays = Math.floor(diffHours / 24);
                const diffWeeks = Math.floor(diffDays / 7);

                if (diffWeeks >= 1) {
                    el.innerHTML = "More than 1 week";
                } else if (diffDays > 0) {
                    el.innerHTML = `<strong>${diffDays} day${diffDays > 1 ? 's' : ''}</strong> to go`;
                } else if (diffHours > 0) {
                    const mins = diffMins % 60;
                    el.innerHTML = `<strong>${diffHours}h ${mins}m</strong> left`;
                } else {
                    el.innerHTML = `<strong>${diffMins}m</strong> left`;
                }
            });
        }

        updateCountdowns();
        setInterval(updateCountdowns, 30000);
    </script>

    <!-- Bootstrap JS -->
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Vendor JS Files -->
    <script src="../Assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../Assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../Assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../Assets/vendor/echarts/echarts.min.js"></script>
    <script src="../Assets/vendor/quill/quill.js"></script>
    <script src="../Assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../Assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../Assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="../Assets/js/main.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php include_once "../Includes/SweetAlert.php"; ?>

</body>

</html>