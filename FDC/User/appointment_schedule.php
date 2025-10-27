<?php
session_name('patient_session');
session_start();
include_once "../Config/conn.config.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve appointment schedules for the user
$sql = "SELECT * FROM appointment_schedule
        WHERE user_id = ?
          AND appointment_date = CURDATE()
          AND stat = 'Approved'
        ORDER BY appointment_time_start ASC, created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Find the newest created_at
$newestCreatedAt = null;
if ($appointments) {
    $newestCreatedAt = max(array_column($appointments, 'created_at'));
}

// All upcoming appointments (including today)
$sql1 = "SELECT * FROM appointment_schedule
        WHERE user_id = ?
          AND (
              appointment_date > CURDATE()
              OR (appointment_date = CURDATE() AND appointment_time_end > CURTIME())
          )
        ORDER BY 
            CASE 
                WHEN stat = 'Approved' THEN 1
                WHEN stat = 'Pending' THEN 2
                ELSE 3
            END,
            appointment_date ASC,
            appointment_time_start ASC,
            created_at ASC";
$stmt1 = $conn->prepare($sql1);
$stmt1->execute([$user_id]);
$appointments1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);


// Find the newest created_at
$newestCreatedAt1 = null;
if ($appointments1) {
    $newestCreatedAt1 = max(array_column($appointments1, 'created_at'));
}

//done appointments
$sql2 = "SELECT * FROM appointment_schedule
        WHERE user_id = ?
          AND stat = 'Done'
        ORDER BY appointment_date DESC, appointment_time_start DESC, created_at DESC";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute([$user_id]);
$doneAppointments = $stmt2->fetchAll(PDO::FETCH_ASSOC);


// Retrieve user profile information
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

<?php include_once "../Includes/Head.php" ?>

<body>

    <?php include_once "../Includes/Header.php"; ?>
    <?php include_once "../Includes/Sidebar.php"; ?>


    <main id="main" class="main">

        <?php include_once "../Includes/Welcome.php"; ?>

        <div class="schedule-container">

            <!-- Header -->
            <section class="header-schedule">
                <div class="title">Your Appointment Schedules</div>
                <!-- <hr>

                <h4 class="appointment_date-title">By Date</h4>
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
                </div>

                <div class="appointment_status">
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




            <!-- Row list of schedule cards -->
            <h3 class="appointed-schedule">Today</h3>
            <section class="schedule-list" aria-label="Today schedule list">
                <?php
                $hasToday = false;
                if ($appointments):
                    foreach ($appointments as $appointment):
                        if ($appointment['appointment_date'] === date("Y-m-d")):
                            $hasToday = true;
                            // Combine date + time into one string
                            $appointmentDateTime = $appointment['appointment_date'] . ' ' . $appointment['appointment_time_start'];
                            $isNewest = ($appointment['created_at'] === $newestCreatedAt);
                            $stat = strtolower($appointment['stat']);
                            $badgeClass = "bg-secondary";
                            if ($stat === "done") $badgeClass = "bg-success";
                            elseif ($stat === "cancelled") $badgeClass = "bg-danger";
                            elseif ($stat === "upcoming") $badgeClass = "bg-primary";
                            elseif ($stat === "approved") $badgeClass = "bg-info";
                            elseif ($stat === "pending") $badgeClass = "bg-warning";
                ?>
                            <article class="card" role="article">
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
                                            <span class="badge bg-primary">⭐ Newest Appointment</span>
                                        <?php else: ?>
                                            <span class="type clinic"><span class="dot d-clinic"></span> In-Clinic</span>
                                        <?php endif; ?>
                                        <span class="muted countdown" data-appointment="<?= $appointmentDateTime ?>">Calculating...</span>
                                    </div>
                                </div>
                                <div class="actions">
                                    <?php if ($stat === 'done'): ?>
                                        <button class="schedule-btn btn btn-secondary" disabled>Completed</button>
                                        <button class="schedule-btn btn btn-success result-btn"
                                            data-id="<?= $appointment['appointment_id'] ?>">Result</button>
                                    <?php elseif ($stat === 'cancelled'): ?>
                                        <button class="schedule-btn btn btn-secondary" disabled>Cancelled</button>
                                    <?php else: ?>
                                        <button class="schedule-btn btn btn-danger cancel-btn"
                                            data-id="<?= $appointment['appointment_id'] ?>"
                                            data-user-id="<?= $user_id ?>"
                                            data-doc-id="<?= $appointment['doc_id'] ?>">Cancel</button>
                                    <?php endif; ?>
                                    <button class="schedule-btn btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#appointmentModal"
                                        data-id="<?= $appointment['appointment_id'] ?>"
                                        data-user-id="<?= $user_id ?>">Details</button>
                                </div>
                            </article>
                    <?php endif;
                    endforeach;
                endif;

                if (!$hasToday): ?>
                    <!-- No schedule card -->
                    <article class="card" role="article">
                        <div class="schedule-time">
                            <div class="t-main">--:--</div>
                            <div class="t-sub"><?= date("D, M j") ?></div>
                        </div>
                        <div class="info">
                            <div class="name">
                                No available schedule today
                            </div>
                            <div class="meta">
                                <span class="type clinic"><span class="dot d-clinic"></span> Please check upcoming</span>
                            </div>
                        </div>
                        <div class="actions">
                            <button class="schedule-btn btn btn-secondary" disabled>Unavailable</button>
                        </div>
                    </article>
                <?php endif; ?>
            </section>

            <h3 class="appointed-schedule">Upcoming</h3>
            <section class="schedule-list" aria-label="Upcoming schedule list">
                <?php if ($appointments1): ?>
                    <?php foreach ($appointments1 as $appointment1): ?>
                        <?php if ($appointment1['appointment_date'] !== date("Y-m-d") && strtolower($appointment1['stat']) !== "done"): ?>
                            <?php
                            $appointmentDateTime = $appointment1['appointment_date'] . ' ' . $appointment1['appointment_time_start'];
                            $isNewest = ($appointment1['created_at'] === $newestCreatedAt1);
                            $stat = strtolower($appointment1['stat']);
                            $badgeClass = "bg-secondary";
                            if ($stat === "done") $badgeClass = "bg-success";
                            elseif ($stat === "cancelled") $badgeClass = "bg-danger";
                            elseif ($stat === "upcoming") $badgeClass = "bg-primary";
                            elseif ($stat === "approved") $badgeClass = "bg-info";
                            elseif ($stat === "pending") $badgeClass = "bg-warning";
                            ?>
                            <article class="card" role="article">
                                <div class="schedule-time">
                                    <div class="t-main"><?= date("g:i A", strtotime($appointment1['appointment_time_start'])) ?></div>
                                    <div class="t-sub"><?= date("D, M j", strtotime($appointment1['appointment_date'])) ?></div>
                                </div>
                                <div class="info">
                                    <div class="name">
                                        Dr. <?= htmlspecialchars($appointment1['doc_fn']) ?> <?= htmlspecialchars($appointment1['doc_ln']) ?>
                                        (<?= htmlspecialchars($appointment1['specialty']) ?>)
                                        <span class="badge <?= $badgeClass ?> status-badge"><?= ucfirst($stat) ?></span>
                                    </div>
                                    <div class="meta">
                                        <?php if ($isNewest): ?>
                                            <span class="badge bg-primary">⭐ Newest Appointment</span>
                                        <?php else: ?>
                                            <span class="type clinic"><span class="dot d-clinic"></span> In-Clinic</span>
                                        <?php endif; ?>
                                        <span class="muted countdown" data-appointment="<?= $appointmentDateTime ?>">Calculating...</span>
                                    </div>
                                </div>
                                <div class="actions">
                                    <?php if ($stat === 'cancelled'): ?>
                                        <button class="schedule-btn btn btn-secondary" disabled>Cancelled</button>
                                    <?php else: ?>
                                        <button class="schedule-btn btn btn-danger cancel-btn"
                                            data-id="<?= $appointment1['appointment_id'] ?>"
                                            data-user-id="<?= $user_id ?>"
                                            data-doc-id="<?= $appointment1['doc_id'] ?>">Cancel</button>
                                    <?php endif; ?>
                                    <button class="schedule-btn btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#appointmentdetailsModal"
                                        data-id="<?= $appointment1['appointment_id'] ?>"
                                        data-user-id="<?= $user_id ?>">Details</button>
                                </div>
                            </article>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No appointments found.</p>
                <?php endif; ?>
            </section>

            <h3 class="appointed-schedule">Done</h3>
            <section class="schedule-list" aria-label="Done schedule list">
                <?php
                $hasDone = false;
                if ($doneAppointments):
                    foreach ($doneAppointments as $doneAppointment):
                        if (strtolower($doneAppointment['stat']) === "done"):
                            $hasDone = true;
                            $appointmentDateTime = $doneAppointment['appointment_date'] . ' ' . $doneAppointment['appointment_time_start'];
                ?>
                            <article class="card" role="article">
                                <div class="schedule-time">
                                    <div class="t-main"><?= date("g:i A", strtotime($doneAppointment['appointment_time_start'])) ?></div>
                                    <div class="t-sub"><?= date("D, M j", strtotime($doneAppointment['appointment_date'])) ?></div>
                                </div>
                                <div class="info">
                                    <div class="name">
                                        Dr. <?= htmlspecialchars($doneAppointment['doc_fn']) ?> <?= htmlspecialchars($doneAppointment['doc_ln']) ?>
                                        (<?= htmlspecialchars($doneAppointment['specialty']) ?>)
                                        <span class="badge bg-success status-badge">Done</span>
                                    </div>
                                    <div class="meta">
                                        <span class="type clinic"><span class="dot d-clinic"></span> In-Clinic</span>
                                    </div>
                                </div>
                                <div class="actions">
                                    <button class="schedule-btn btn btn-success result-btn"
                                        data-id="<?= $doneAppointment['appointment_id'] ?>">Result</button>
                                </div>
                            </article>
                    <?php
                        endif;
                    endforeach;
                endif;

                if (!$hasDone): ?>
                    <article class="card" role="article">
                        <div class="schedule-time">
                            <div class="t-main">--:--</div>
                            <div class="t-sub">---</div>
                        </div>
                        <div class="info">
                            <div class="name">No completed appointments yet</div>
                        </div>
                        <div class="actions">
                            <button class="schedule-btn btn btn-secondary" disabled>Unavailable</button>
                        </div>
                    </article>
                <?php endif; ?>
            </section>





            <!-- Result Modal -->
            <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
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



            <!-- Appointment Details Modal -->
            <div class="modal fade" id="appointmentdetailsModal" tabindex="-1" aria-hidden="true">
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
                                    <span id="appointment_id">0</span>
                                    <span class="pdf-status">Reference number for tracking</span>
                                </div>

                                <!-- Appointment Date -->
                                <div class="record-item">
                                    <strong><i class="fi fi-rr-calendar"></i> Appointment Date</strong>
                                    <span id="appointment_date">2025-08-22</span>
                                    <span class="pdf-status" id="date_remaining">Calculating...</span>
                                </div>

                                <!-- Time Slot -->
                                <div class="record-item">
                                    <strong><i class="fi fi-rr-clock"></i> Time Slot</strong>
                                    <span id="appointment_time">10:00 AM</span>
                                    <span class="pdf-status" id="duration_time">1 hour</span>
                                </div>

                                <!-- Reason for Visit -->
                                <div class="record-item">
                                    <strong><i class="fi fi-rr-notepad"></i> Appointment Type</strong>
                                    <span id="specialty">Checkup</span>
                                    <span class="pdf-status" id="doctor_name">Consultation</span>
                                </div>

                                <!-- Total Expenses -->
                                <div class="record-item">
                                    <strong><i class="fi fi-rr-wallet"></i> Total Expenses</strong>
                                    <span class="expense-amount" id="total_expenses">$285.00</span>
                                    <span class="pdf-status">Consultation fee</span>
                                </div>

                                <!-- Status -->
                                <div class="record-item">
                                    <strong><i class="fi fi-ss-check-circle"></i> Status</strong>
                                    <span>
                                        <span class="status-badge status-confirmed" id="status">
                                            <span class="badge status-dot"></span>
                                            Confirmed
                                        </span>
                                    </span>
                                    <span class="pdf-status" id="status_message">Appointment confirmed by clinic</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




    </main><!-- End #main -->

    <?php include_once "../Includes/Footer.php"; ?>


    <!-- Appointment Details Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const appointmentModal = document.getElementById("appointmentdetailsModal");

            appointmentModal.addEventListener("show.bs.modal", function(event) {
                const button = event.relatedTarget;
                const appointmentId = button.getAttribute("data-id");
                const userId = button.getAttribute("data-user-id");

                if (!appointmentId) return;

                // Fetch appointment details via AJAX
                fetch(`../Auth/User/get_appointment_details.php?id=${appointmentId}&user_id=${userId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }

                        // Populate modal fields dynamically
                        document.getElementById("appointment_id").textContent = `Appt_id_${data.appointment_id}`;
                        const date = new Date(data.appointment_date);
                        const options = {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
                        document.getElementById("appointment_date").textContent = date.toLocaleDateString('en-US', options);
                        document.getElementById("date_remaining").textContent = `${Math.ceil((date - new Date()) / (1000 * 60 * 60 * 24))} days to go`;
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
                        document.getElementById("duration_time").textContent = `${data.duration_time.split(':')[0]} minutes diagnostic test duration`;
                        document.getElementById("specialty").textContent = data.specialty;
                        document.getElementById("total_expenses").textContent = `₱${parseFloat(data.total_expense).toFixed(2)}`;
                        document.getElementById("doctor_name").textContent = `Diagnostic Test with Dr. ${data.doctor_name}`;

                        const statusEl = document.getElementById("status");
                        const statusMessageEl = document.getElementById("status_message");
                        statusEl.textContent = data.status;

                        let badgeClass = "bg-secondary";

                        if (data.status === "Approved") {
                            badgeClass = "bg-primary";
                            statusMessageEl.textContent = "Appointment approved by clinic";
                        } else if (data.status === "Cancelled") {
                            badgeClass = "bg-danger";
                            statusMessageEl.textContent = "Appointment cancelled by user";
                        } else if (data.status === "Done") {
                            badgeClass = "bg-success";
                        } else if (data.status === "Upcoming") {
                            badgeClass = "bg-info"; // changed color for Upcoming
                        } else if (data.status === "Pending") {
                            badgeClass = "bg-warning";
                            statusMessageEl.textContent = "Appointment doesn't have confirmation yet";
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
                        text: "Do you really want to cancel this appointment?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#6c757d",
                        confirmButtonText: "Yes, cancel it"
                    }).then((result) => {
                        if (!result.isConfirmed) return;

                        fetch("../Auth/User/cancel_appointment.php", {
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
                                        statusBadge.textContent = "Cancelled";
                                        statusBadge.className = "badge bg-danger status-badge";
                                    }

                                    button.textContent = "Cancelled";
                                    button.disabled = true;
                                    button.classList.remove("btn-danger");
                                    button.classList.add("btn-secondary");

                                    Swal.fire({
                                        icon: "success",
                                        title: "Cancelled",
                                        text: "Your appointment has been cancelled successfully.",
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



            // Handle Result button click
            document.querySelectorAll('.result-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const appointmentId = this.getAttribute('data-id');
                    const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                    const downloadBtn = document.getElementById('downloadPdf');

                    // Clear old link
                    downloadBtn.href = "#";
                    downloadBtn.classList.add("disabled");
                    downloadBtn.textContent = "Waiting for result...";

                    resultModal.show();

                    // Start polling
                    const interval = setInterval(() => {
                        fetch(`../Auth/User/Get_Result_Pdf.php?appointment_id=${appointmentId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Enable the download link once file is found
                                    downloadBtn.href = data.file;
                                    downloadBtn.classList.remove("disabled");
                                    downloadBtn.textContent = "Download PDF";

                                    clearInterval(interval); // stop polling
                                }
                            })
                            .catch(err => console.error(err));
                    }, 3000); // poll every 3 seconds
                });
            });






            // === POLLING LOGIC FOR REAL-TIME UPDATES ===
            function checkStatuses() {
                fetch("../Auth/User/Realtime_Status.php")
                    .then(res => res.json())
                    .then(data => {
                        if (!Array.isArray(data)) return;

                        data.forEach(app => {
                            const card = document.querySelector(
                                `.card .actions [data-id="${app.appointment_id}"]`
                            )?.closest(".card");

                            if (card) {
                                const badge = card.querySelector(".status-badge");
                                if (badge) {
                                    let badgeClass = "bg-secondary";
                                    let text = app.stat;

                                    switch (app.stat.toLowerCase()) {
                                        case "done":
                                            badgeClass = "bg-success";
                                            break;
                                        case "cancelled":
                                            badgeClass = "bg-danger";
                                            break;
                                        case "upcoming":
                                            badgeClass = "bg-primary";
                                            break;
                                        case "approved":
                                            badgeClass = "bg-info";
                                            break;
                                        case "pending":
                                            badgeClass = "bg-warning";
                                            break;
                                    }

                                    badge.textContent = text.charAt(0).toUpperCase() + text.slice(1);
                                    badge.className = "badge " + badgeClass + " status-badge";

                                    const actionsDiv = card.querySelector(".actions");

                                    if (app.stat.toLowerCase() === "done") {
                                        // Disable cancel button
                                        const cancelBtn = actionsDiv.querySelector(".cancel-btn");
                                        if (cancelBtn) {
                                            cancelBtn.textContent = "Cancel";
                                            cancelBtn.disabled = true;
                                            cancelBtn.classList.remove("btn-danger");
                                            cancelBtn.classList.add("btn-secondary");
                                        }

                                        // Add Result button if not already added
                                        if (!actionsDiv.querySelector(".result-btn")) {
                                            const resultBtn = document.createElement("button");
                                            resultBtn.className = "schedule-btn btn btn-success result-btn";
                                            resultBtn.textContent = "Result";
                                            resultBtn.setAttribute("data-id", app.appointment_id);
                                            actionsDiv.insertBefore(resultBtn, actionsDiv.querySelector(".btn-primary")); // before Details
                                        }
                                    }
                                }
                            }
                        });
                    })
                    .catch(err => console.error("Polling error:", err));
            }

            // Check immediately and then every 5s
            checkStatuses();
            setInterval(checkStatuses, 5000);

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