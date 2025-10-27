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


// Retrieve appointment schedules for the personnel
$sql = "SELECT aps.*  FROM appointment_schedule aps
        LEFT JOIN appointments ap ON aps.appointment_id = ap.appointment_id
        WHERE aps.stat = 'Pending' AND ap.isCustomed = 1
        ORDER BY aps.appointment_date ASC, aps.appointment_time_start ASC, aps.created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
$today = date("Y-m-d"); // today's date

// Find the newest created_at
$newestCreatedAt = null;
if ($appointments) {
    $newestCreatedAt = max(array_column($appointments, 'created_at'));
}

?>


<!DOCTYPE html>
<html lang="en">

<?php include_once "../Includes/Personnel_Head.php" ?>



<body>

    <?php include_once "../Includes/Personnel_Header.php"; ?>
    <?php include_once "../Includes/Personnel_Sidebar.php"; ?>


    <main id="main" class="main">

        <?php include_once "../Includes/Personnel_Welcome.php"; ?>

        <div class="schedule-container">
            <!-- Header -->
            <section class="header-schedule">
                <div class="title">Your Appointment Requests Schedules</div>

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


            <!-- ================= UPCOMING ================= -->
            <div class="appointment-group">
                <h3 class="appointed-schedule">Pending Requests</h3>
                <section class="schedule-list" aria-label="Schedule list">
                    <?php
                    $hasUpcoming = false; // flag to check if upcoming approvals exist
                    ?>

                    <?php if ($appointments): ?>
                        <?php foreach ($appointments as $appointment): ?>
                            <?php
                            $appointmentDateTime = $appointment['appointment_date'] . ' ' . $appointment['appointment_time_start'];
                            $stat = strtolower($appointment['stat']);
                            $badgeClass = "bg-secondary";

                            if ($stat === "done") {
                                $badgeClass = "bg-success";
                            } elseif ($stat === "cancelled") {
                                $badgeClass = "bg-danger";
                            } elseif ($stat === "upcoming") {
                                $badgeClass = "bg-primary";
                            }

                            // ✅ show only upcoming (future date, not done)
                            if ($appointment['appointment_date'] > $today && $stat !== "done"):
                                $hasUpcoming = true;
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
                                            <span class="type clinic"><span class="dot d-clinic"></span> In-Clinic</span>
                                            <span class="muted countdown" data-appointment="<?= $appointmentDateTime ?>">Calculating...</span>
                                        </div>
                                    </div>
                                    <div class="actions">
                                        <button class="schedule-btn btn btn-success approve-btn"
                                            data-id="<?= $appointment['appointment_id'] ?>"
                                            data-user-id="<?= $appointment['user_id'] ?>"
                                            data-personnel-id="<?= $doc_id ?>"
                                            data-doc-id="<?= $appointment['doc_id'] ?>">
                                            Approve
                                        </button>

                                        <?php if ($stat !== 'cancelled'): ?>
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
                                            data-bs-target="#personnel_requestModal"
                                            data-id="<?= $appointment['appointment_id'] ?>">
                                            Details
                                        </button>
                                    </div>
                                </article>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (!$hasUpcoming): ?>
                        <!-- ✅ Blank fallback card -->
                        <article class="card blank" role="article">
                            <div class="schedule-time">
                                <div class="t-main">--:--</div>
                                <div class="t-sub">--/--/----</div>
                            </div>
                            <div class="info">
                                <div class="name">
                                    No pending request yet
                                </div>
                                <div class="meta">
                                    <span class="type clinic"><span class="dot d-clinic"></span> Please check later</span>
                                </div>
                            </div>
                            <div class="actions">
                                <button class="schedule-btn btn btn-secondary" disabled>Unavailable</button>
                            </div>
                        </article>
                    <?php endif; ?>
                </section>
            </div>


            <!-- ================= DONE ================= -->
            <!-- <div class="appointment-group">
                <h3 class="appointed-schedule">Done</h3>
                <section class="schedule-list" aria-label="Schedule list">
                    <?php if ($appointments): ?>
                        <?php foreach ($appointments as $appointment): ?>
                            <?php
                            $appointmentDateTime = $appointment['appointment_date'] . ' ' . $appointment['appointment_time_start'];
                            $stat = strtolower($appointment['stat']);
                            $badgeClass = ($stat === "done") ? "bg-success" : "bg-secondary";

                            // ✅ show only done
                            if ($stat === "done"):
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
                                            <span class="badge <?= $badgeClass ?> status-badge">Done</span>
                                        </div>
                                        <div class="meta"><span class="muted">Completed</span></div>
                                    </div>
                                    <div class="actions">
                                        <button class="schedule-btn btn btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#appointmentModal"
                                            data-id="<?= $appointment['appointment_id'] ?>">
                                            Details
                                        </button>
                                    </div>
                                </article>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No completed approvals.</p>
                    <?php endif; ?>
                </section>
            </div> -->


            <!-- Appointment Details Modal -->
            <div class="modal fade" id="personnel_requestModal" tabindex="-1" aria-hidden="true">
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

        </div>

    </main><!-- End #main -->

    <?php include_once "../Includes/Footer.php"; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".approve-btn").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    const appointmentId = this.getAttribute("data-id");
                    const userId = this.getAttribute("data-user-id");
                    const docId = this.getAttribute("data-doc-id");
                    const personnelId = this.getAttribute("data-personnel-id");

                    fetch("../Auth/Personnel/Approve_Appointment.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: "appointment_id=" + appointmentId + "&user_id=" + userId + "&doc_id=" + docId + "&personnel_id=" + personnelId
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("Appointment approved!");
                                location.reload(); // refresh to see updated status
                            } else {
                                alert("Error: " + data.message);
                            }
                        })
                        .catch(err => console.error("Request failed", err));
                });
            });
        });
    </script>


    <!-- Appointment Details Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const appointmentModal = document.getElementById("personnel_requestModal");

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
                            badgeClass = "bg-secondary";
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
                        text: "Do you want to decline this appointment?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, decline it!"
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
                                        text: "The appointment has been declined.",
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
                                Swal.fire({
                                    icon: "error",
                                    title: "Network Error",
                                    text: "Something went wrong. Please try again."
                                });
                                console.error(err);
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