<?php
session_name("doctor_session");
session_start();
include_once "../Config/conn.config.php";

$doc_id = $_SESSION['doc_id'] ?? null;

try {
    $pr = $conn->prepare("SELECT COUNT(*) FROM appointment_schedule WHERE doc_id = ? AND stat = 'Pending' ");
    $pr->execute([$doc_id]);
    $pending_requests = $pr->fetchColumn();
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


try {
    $today = date('Y-m-d');

    $stmt = $conn->prepare("SELECT COUNT(*) FROM appointment_schedule WHERE appointment_date = ?");
    $stmt->execute([$today]);
    $totalAppointments = $stmt->fetchColumn();

    $stmtNew = $conn->prepare("SELECT COUNT(*) FROM appointment_schedule WHERE appointment_date = ? AND stat = 'Pending'");
    $stmtNew->execute([$today]);
    $newAppointments = $stmtNew->fetchColumn();
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
        <?php include_once "../Includes/Personnel_Welcome.php"; ?>
    </main><!-- End #main -->


    <section class="section dashboard">
        <div class="container personnel-date-sched" style="margin-left:240px; overflow-x: hidden;">
            <!-- Card Content -->
            <!-- Quick Stats -->
            <section class="stats-card">
                <div class="stat-card">
                    <div class="k">Today’s Appointment Schedule</div>
                    <div class="v"><?= htmlspecialchars($totalAppointments) ?></div>
                    <span class="pill">+<?= htmlspecialchars($newAppointments) ?> new</span>
                </div>
                <div class="stat-card">
                    <div class="k">Pending Appointment Requests</div>
                    <div class="v"><?= htmlspecialchars($pending_requests) ?></div>
                    <span class="pill" style="background:#ecfeff;color:#0369a1;">Inbox</span>
                </div>
                <div class="stat-card">
                    <div class="k">Total Appointments</div>
                    <div class="v">40</div>
                    <!-- <span class="pill" style="background:#fef3c7;color:#92400e;">Last 30d</span> -->
                </div>
            </section>

            <!-- Two Column Panels -->
            <!-- <section class="grid-card">
                    
                    <div class="consult-card">
                        <h3>Upcoming Consultations</h3>
                        <p>Next few sessions for today.</p>
                        <div class="card-list" style="margin-top:10px;">
                            <div class="item">
                                <div class="slot">09:30 AM</div>
                                <div>
                                    <div class="who">A. Reyes — Video</div>
                                    <div class="meta">General Checkup • 20 mins</div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="slot">11:00 AM</div>
                                <div>
                                    <div class="who">M. Santos — In-person</div>
                                    <div class="meta">Follow-up • 30 mins</div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="slot">02:30 PM</div>
                                <div>
                                    <div class="who">J. Cruz — Video</div>
                                    <div class="meta">Consultation • 25 mins</div>
                                </div>
                            </div>
                        </div>

                        <h3 style="margin-top:16px;">Messages</h3>
                        <div class="message-list">
                            <div class="row">
                                <div class="name-badge">
                                    <strong>Dr. Smith</strong>
                                    <span class="badge">Unread</span>
                                </div>
                                <span class="meta">10:45 AM</span>
                            </div>
                            <div class="preview">Hi Doc, I have a quick question about my medication dosage from last visit...</div>
                        </div>

                        <div class="message-list">
                            <div class="row">
                                <div class="name-badge">
                                    <strong>From: K. Mendoza</strong>
                                    <span class="badge">Replied</span>
                                </div>
                                <span class="meta">10:45 AM</span>
                            </div>
                            <div class="preview">Thank you for the results. Can we move my follow-up to next week?</div>
                        </div>
                    </div>
                </section> -->

            <!-- <section class="grid-card">
                    
                    <div class="schedule-card">
                        <h3>Schedule</h3>
                        <p>Slots for the next two days.</p>
                        <div class="schedule">
                            <div class="day-list">
                                <div class="d">Today</div>
                                <div class="chips">
                                    <span class="chip">09:30 — Booked</span>
                                    <span class="chip">11:00 — Booked</span>
                                    <span class="chip">01:00 — Open</span>
                                    <span class="chip">02:30 — Booked</span>
                                </div>
                            </div>
                            <div class="day-list">
                                <div class="d">Tomorrow</div>
                                <div class="chips">
                                    <span class="chip">09:00 — Open</span>
                                    <span class="chip">10:30 — Open</span>
                                    <span class="chip">01:30 — Booked</span>
                                </div>
                            </div>
                        </div>

                        <h3 style="margin-top:14px;">Week Overview</h3>
                        <table class="table" aria-label="Week overview">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Consults</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Mon</td>
                                    <td>5</td>
                                    <td>2 virtual, 3 in-person</td>
                                </tr>
                                <tr>
                                    <td>Tue</td>
                                    <td>4</td>
                                    <td>Light day</td>
                                </tr>
                                <tr>
                                    <td>Wed</td>
                                    <td>6</td>
                                    <td>Peak</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section> -->
        </div>

        <div class="container">
            <div class="row">
                <div class="col-3 mb-4" style="margin-left: 5px;">
                    <div class="appointment-card blue-card position-relative" data-bs-toggle="modal" data-bs-target="#faqansweredModal">

                        <div class="card-content">
                            <h3 class="card-title text-light">Add FAQ's</h3>
                            <p class="card-subtitle">Add new frequently asked questions</p>
                            <div class="card-icon">
                                <i class="fi fi-tr-add"></i>
                            </div>
                        </div>
                        <div class="card-decoration"></div>
                    </div>
                </div>

                <!-- <div class="col-3 mb-4">
                    <div class="appointment-card green-card position-relative" data-bs-toggle="modal" data-bs-target="#consultNotif">
                        
                        <span class="position-absolute end-0 translate-middle-y badge rounded-pill bg-success me-2"
                            id="totalCallBadge"
                            style="top: 18px; font-size: 0.9rem; padding: 10px 15px; z-index: 10;">
                            
                        </span>

                        <div class="card-content">
                            <h3 class="card-title text-light">Paid Consultations</h3>
                            <p class="card-subtitle">Answer online consultations</p>
                            <div class="card-icon">
                                <i class="fi fi-tr-choose"></i>
                            </div>
                        </div>
                        <div class="card-decoration"></div>
                    </div>
                </div> -->

                <!-- <div class="col-3 mb-4">
                    <a href="add_schedule.php" style="text-decoration: none;">
                        <div class="appointment-card green-card position-relative">
                            <span class="position-absolute end-0 translate-middle-y badge rounded-pill bg-success me-2"
                                id="pendingCountBadge"
                                style="top: 18px; font-size: 0.9rem; padding: 10px 15px; z-index: 10;">
                                <?php echo $pendingCount; ?>
                            </span>

                            <div class="card-content">
                                <h3 class="card-title text-light">Add Schedule</h3>
                                <p class="card-subtitle">Manage your availability</p>
                                <div class="card-icon">
                                    <i class="fi fi-tr-choose"></i>
                                </div>
                            </div>
                            <div class="card-decoration"></div>
                        </div>
                    </a>
                </div> -->
            </div>
        </div>






    </section>


    <!-- FAQ Answered Modal -->
    <div class="modal fade" id="faqansweredModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="../auth/Personnel/Add_Faqs.php" method="POST">

                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="messageModalLabel" style="color: white;">Add new Frequently Asked Questions (FAQ'S)
                            <!-- <span id="senderName">Sender</span> -->
                        </h5>
                        <button type="button" class="custom-close" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-bs-cross"></i></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">

                        <!-- Optional: Add a reply input -->
                        <div class="mt-4">
                            <label for="replyTextareaa" class="form-label">Add Question: </label>
                            <input class="form-control" id="replyTextareaa" name="question" placeholder="Type your question here..." required />
                        </div>
                        <div class="mt-4">
                            <label for="replyTextarea" class="form-label">Your Answer</label>
                            <textarea class="form-control" id="replyTextarea" rows="3" name="answer" placeholder="Type your answer here..." required></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add FAQ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>






    <!-- ======= Footer ======= -->
    <?php include_once '../Includes/Footer.php'; ?>
    <!-- End Footer -->


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


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const badge = document.getElementById("totalCallBadge");

            fetchConsultations();

            setInterval(fetchConsultations, 3000);

            function fetchConsultations() {
                fetch('../auth/Personnel/fetch_video_consultation.php')
                    .then(response => response.json())
                    .then(data => {
                        const tbody = document.getElementById("consultation-body");
                        tbody.innerHTML = "";

                        // Update badge count
                        badge.textContent = data.length;

                        if (data.length > 0) {
                            data.forEach(consultation => {
                                const row = document.createElement("tr");
                                row.innerHTML = `
                            <td>${consultation.patient_name}</td>
                            <td><span class="badge bg-success">${consultation.status}</span></td>
                            <td>
                                <form action="../config/paymongo/start_call.php" method="POST">
                                    <input type="hidden" name="consultation_id" value="${consultation.id}">
                                    <input type="hidden" name="doctor_id" value="${consultation.doctor_id}">
                                    <button class="btn btn-primary">Start Call</button>
                                </form>
                            </td>
                        `;
                                tbody.appendChild(row);
                            });
                        } else {
                            tbody.innerHTML = `<tr><td colspan="3">No paid consultations at the moment.</td></tr>`;
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching consultations:", error);
                        document.getElementById("consultation-body").innerHTML = `<tr><td colspan="3">Error loading data.</td></tr>`;
                        badge.textContent = 0; // reset badge on error
                    });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const questionItems = document.querySelectorAll('[data-bs-target="#faqansweredModal"]');

            questionItems.forEach(item => {
                item.addEventListener('click', () => {
                    const sender = item.getAttribute('data-sender');
                    const question = item.getAttribute('data-question');
                    const id = item.getAttribute('data-question-id');

                    document.getElementById('senderName').textContent = sender;
                    document.getElementById('fullQuestion').textContent = question;
                    document.getElementById('questionId').value = id;
                });
            });
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            let selectedSlots = [];
            let existingSlots = [];
            let highlightedDayIndex = null;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                selectable: true,
                editable: true,
                allDaySlot: false,
                slotMinTime: "08:00:00",
                slotMaxTime: "20:00:00",
                height: 'auto',

                events: {
                    url: '../auth/Personnel/fetch_schedule.php?doctor_id=<?= (int)$doc_id ?>',
                    method: 'GET',
                    failure: function() {
                        alert('Failed to fetch schedules');
                    },
                    eventSourceSuccess: function(events) {
                        existingSlots = events.map(ev => ev.start + '_' + ev.end);
                        return events;
                    }
                },

                select: function(info) {
                    const key = info.startStr + '_' + info.endStr;

                    if (existingSlots.includes(key)) {
                        alert("This slot is already saved.");
                        return;
                    }
                    if (selectedSlots.find(slot => slot.start === info.startStr && slot.end === info.endStr)) {
                        alert("This slot is already selected.");
                        return;
                    }

                    highlightColumn(info.start);

                    const event = calendar.addEvent({
                        title: 'Available',
                        start: info.startStr,
                        end: info.endStr,
                        backgroundColor: '#28a745',
                        borderColor: '#28a745'
                    });
                    event.setExtendedProp('new', true);

                    selectedSlots.push({
                        start: info.startStr,
                        end: info.endStr
                    });
                },

                eventClick: function(info) {
                    const event = info.event;
                    if (event.extendedProps.new) {
                        if (confirm("Remove this unsaved slot?")) {
                            selectedSlots = selectedSlots.filter(
                                slot => !(slot.start === event.startStr && slot.end === event.endStr)
                            );
                            event.remove();
                        }
                    } else {
                        alert("This slot is already saved and cannot be deleted here.");
                    }
                }
            });

            // ✅ Ensure FullCalendar renders correctly when modal opens
            const modalEl = document.getElementById('addSchedule');
            modalEl.addEventListener('shown.bs.modal', function() {
                calendar.render();
            });

            modalEl.addEventListener('hidden.bs.modal', function() {
                // Clear highlights when modal closes
                document.querySelectorAll('.fc-highlighted-column').forEach(el => el.classList.remove('fc-highlighted-column'));
                document.querySelectorAll('.fc-highlighted-header').forEach(el => el.classList.remove('fc-highlighted-header'));
                highlightedDayIndex = null;
            });

            function highlightColumn(dateObj) {
                document.querySelectorAll('.fc-highlighted-column').forEach(el => el.classList.remove('fc-highlighted-column'));
                document.querySelectorAll('.fc-highlighted-header').forEach(el => el.classList.remove('fc-highlighted-header'));

                highlightedDayIndex = dateObj.getDay();

                const headers = document.querySelectorAll('.fc-col-header-cell');
                if (headers[highlightedDayIndex]) {
                    headers[highlightedDayIndex].classList.add('fc-highlighted-header');
                }

                const columns = document.querySelectorAll(`.fc-timegrid-col:nth-child(${highlightedDayIndex + 2})`);
                columns.forEach(col => col.classList.add('fc-highlighted-column'));
            }

            document.getElementById('saveBtn').addEventListener('click', function() {
                if (selectedSlots.length === 0) {
                    alert("No new time slots selected.");
                    return;
                }

                fetch('../auth/Personnel/save_availability.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            doctor_id: <?= (int)$doc_id ?>,
                            slots: selectedSlots
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Availability saved!');
                            selectedSlots = [];
                            calendar.getEvents().forEach(event => {
                                if (event.extendedProps.new) {
                                    event.remove();
                                }
                            });
                            calendar.refetchEvents();
                        } else {
                            alert('Error saving availability.');
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('An error occurred while saving.');
                    });
            });
        });
    </script>


    <?php include_once "../Includes/SweetAlert.php" ?>

</body>

</html>