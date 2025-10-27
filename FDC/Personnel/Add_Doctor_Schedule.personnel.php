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

// try {
//     $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM unanswered_questions WHERE stat = 'pending'");
//     $stmt->execute();
//     $result = $stmt->fetch(PDO::FETCH_ASSOC);
//     $pendingCount = $result['total'];
// } catch (PDOException $e) {
//     $_SESSION['message'] = [
//         'title' => 'Error',
//         'message' => 'Database error: ' . $e->getMessage(),
//         'type' => 'error'
//     ];
//     header("Location: dashboard.doctor.php");
//     exit();
// }

// try {
//     $q = $conn->prepare("SELECT id, question  FROM unanswered_questions WHERE stat = 'pending'");
//     $q->execute();
//     $questions = $q->fetchAll(PDO::FETCH_ASSOC);
// } catch (PDOException $e) {
//     $_SESSION['message'] = [
//         'title' => 'Error',
//         'message' => 'Database error: ' . $e->getMessage(),
//         'type' => 'error'
//     ];
//     header("Location: dashboard.doctor.php");
//     exit();
// }

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "../Includes/Personnel_Head.php"; ?>



<body>

    <?php include_once "../Includes/Personnel_Header.php"; ?>
    <?php include_once "../Includes/Personnel_Sidebar.php"; ?>

    <main id="main" class="main">

        <?php include_once "../Includes/Personnel_Welcome.php"; ?>

        <section class="section dashboard mt-4 add-sched-slot">
            <div class="calendar-container">
                <div class="card">
                <h2 class="text-center mt-3 p-3">Check Here Your Schedule</h2>
                </div>
                

                <!-- Card: Doctor & Consultation Type -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Doctor and Consultation Details</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="doctorSelect" class="font-weight-bold">Select Doctor</label>
                                <select id="doctorSelect" class="form-select" required>
                                    <option value="" selected disabled>Select a doctor</option>
                                    <?php
                                    $stmt = $conn->query("SELECT DISTINCT doctor_id, CONCAT(firstname , ' ' , lastname) AS doctor_name FROM doctor_info");
                                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($rows as $doctor) {
                                        echo "<option value='{$doctor['doctor_id']}'>{$doctor['doctor_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="consultType" class="font-weight-bold">Consultation Type</label>
                                <select id="consultType" class="form-select" required>
                                    <option value="" selected disabled>Select consultation type</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Card: Slot Selection -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Select Date & Time</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="font-weight-bold">Select Month</label>
                                <select id="monthSelect" class="form-select"></select>
                            </div>
                            <div class="col-md-4">
                                <label class="font-weight-bold">Select Day</label>
                                <select id="daySelect" class="form-select"></select>
                            </div>
                            <div class="col-md-4">
                                <label class="font-weight-bold">Select Time Slot</label>
                                <select id="timeSelect" class="form-select"></select>
                            </div>
                        </div>
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <button type="reset" class="btn btn-secondary w-100">Clear</button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" id="addSlotBtn" class="btn btn-success w-100">Add Slot</button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Card: Table of Added Slots -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Selected Slots</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="slotsTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Type of Consultation</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <button id="saveBtn" class="btn btn-primary w-100">Save Schedule</button>
            </div>
        </section>


        <script>
            const doctorSelect = document.getElementById('doctorSelect');
            const consultType = document.getElementById('consultType');
            const monthSelect = document.getElementById('monthSelect');
            const daySelect = document.getElementById('daySelect');
            const timeSelect = document.getElementById('timeSelect');
            const addedSlots = [];

            // Fetch consultation types when doctor changes
            doctorSelect.addEventListener('change', async function() {
                const doctorId = this.value;
                const res = await fetch(`../Auth/Personnel/Fetch_Consult_Types.auth.php?doctor_id=${doctorId}`);
                const data = await res.json();
                consultType.innerHTML = '<option value="" disabled selected>Select consultation type</option>';
                data.forEach(type => {
                    consultType.innerHTML += `<option value="${type.doctor_id}">${type.consultation_type}</option>`;
                });
                generateTimeSlots();
            });

            // Populate months
            monthSelect.innerHTML = '<option value="" selected disabled>Select Month</option>';
            const today = new Date();
            for (let i = 0; i <= 3; i++) {
                const month = new Date(today.getFullYear(), today.getMonth() + i, 1);
                const monthStr = month.toLocaleString('default', {
                    month: 'long',
                    year: 'numeric'
                });
                monthSelect.innerHTML += `<option value="${month.getMonth() + 1}-${month.getFullYear()}">${monthStr}</option>`;
            }

            // Populate days when month changes
            monthSelect.addEventListener('change', function() {
                const [month, year] = this.value.split('-').map(Number);
                const daysInMonth = new Date(year, month, 0).getDate(); // month already 1-based now
                daySelect.innerHTML = '<option value="" disabled selected>Select day</option>';
                const now = new Date();
                for (let d = 1; d <= daysInMonth; d++) {
                    if (month - 1 === now.getMonth() && year === now.getFullYear() && d <= now.getDate()) continue;
                    daySelect.innerHTML += `<option value="${d}-${month}-${year}">${d}</option>`;
                }
            });


            // Refresh time slots when type or day changes
            consultType.addEventListener('change', generateTimeSlots);
            daySelect.addEventListener('change', generateTimeSlots);

            // Fetch booked slots from DB and normalize
            async function fetchBookedSlots() {
                const doctorId = doctorSelect.value;
                const typeOption = consultType.selectedOptions[0];
                const dateValue = daySelect.value;
                if (!doctorId || !typeOption || !dateValue) return [];

                const consultationName = typeOption.text;
                const [day, month, year] = dateValue.split('-').map(Number);
                const formattedDate = `${year}-${String(month).padStart(2,'0')}-${String(day).padStart(2,'0')}`;

                try {
                    const res = await fetch(`../Auth/Personnel/Get_Saved_Slots.auth.php?doctor_id=${doctorId}&consult_type=${consultationName}&date=${formattedDate}`);
                    const data = await res.json();
                    if (data.success && Array.isArray(data.slots)) {
                        // Normalize DB slots to HH:MM-HH:MM
                        return data.slots.map(s => {
                            const start = s.start_time.slice(0, 5);
                            const end = s.end_time.slice(0, 5);
                            return `${start}-${end}`;
                        });
                    }
                } catch (err) {
                    console.error('Error fetching booked slots:', err);
                }
                return [];
            }

            // Generate available time slots
            async function generateTimeSlots() {
                const typeOption = consultType.selectedOptions[0];
                const dateValue = daySelect.value;
                if (!typeOption || !dateValue) {
                    timeSelect.innerHTML = '<option value="" disabled selected>Select time</option>';
                    return;
                }

                const consultationName = typeOption.text;
                const gap = consultationName === 'Ultrasound' ? 15 : 60;

                const [day, month, year] = dateValue.split('-').map(Number);
                const formattedDate = `${year}-${String(month).padStart(2,'0')}-${String(day).padStart(2,'0')}`;

                const bookedSlots = await fetchBookedSlots();

                timeSelect.innerHTML = '<option value="" disabled selected>Select time</option>';
                let current = new Date();
                current.setHours(8, 0, 0, 0);
                const endTime = new Date();
                endTime.setHours(17, 0, 0, 0);

                while (current < endTime) {
                    const slotStart = current;
                    const slotEnd = new Date(current.getTime() + gap * 60000);
                    if (slotEnd > endTime) break;

                    const startStr = `${slotStart.getHours().toString().padStart(2,'0')}:${slotStart.getMinutes().toString().padStart(2,'0')}`;
                    const endStr = `${slotEnd.getHours().toString().padStart(2,'0')}:${slotEnd.getMinutes().toString().padStart(2,'0')}`;
                    const slotValue = `${startStr}-${endStr}`;

                    // Skip if booked in DB or already added in session
                    if (!bookedSlots.includes(slotValue) && !addedSlots.some(s => s.date_slots === formattedDate && s.start_time === startStr && s.end_time === endStr)) {
                        timeSelect.innerHTML += `<option value="${slotValue}">${slotValue}</option>`;
                    }

                    current = slotEnd;
                }
            }

            // Add slot
            document.getElementById('addSlotBtn').addEventListener('click', function() {
                const dateValue = daySelect.value;
                const timeRange = timeSelect.value;
                const type = consultType.selectedOptions[0]?.text;
                if (!dateValue || !timeRange || !type) return alert('Please select all fields!');

                const [day, month, year] = dateValue.split('-').map(Number);
                const formattedDate = `${year}-${String(month).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
                const [start_time, end_time] = timeRange.split('-');

                if (addedSlots.some(s => s.date_slots === formattedDate && s.start_time === start_time && s.end_time === end_time)) {
                    return alert('Slot already added!');
                }

                addedSlots.push({
                    date_slots: formattedDate,
                    start_time,
                    end_time,
                    consult_type: type
                });
                renderSlotsTable();
                generateTimeSlots();
            });

            // Render slots table
            function renderSlotsTable() {
                const tbody = document.querySelector('#slotsTable tbody');
                tbody.innerHTML = '';
                addedSlots.forEach((s, idx) => {
                    tbody.innerHTML += `
            <tr>
                <td>${s.date_slots}</td>
                <td>${s.start_time} - ${s.end_time}</td>
                <td>${s.consult_type}</td>
                <td><button class="delete-sched-btn" onclick="removeSlot(${idx})">Delete</button></td>
            </tr>`;
                });
            }

            // Remove slot
            function removeSlot(idx) {
                addedSlots.splice(idx, 1);
                renderSlotsTable();
                generateTimeSlots();
            }

            // Save slots to DB
            document.getElementById('saveBtn').addEventListener('click', async function() {
                if (!addedSlots.length) return alert('No slots to save!');
                try {
                    const res = await fetch('../Auth/Personnel/Save_Availability.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            doctor_id: doctorSelect.value,
                            slots: addedSlots
                        })
                    });
                    const data = await res.json();
                    if (data.success) {
                        alert(data.message || 'Slots saved!');
                        addedSlots.length = 0;
                        renderSlotsTable();
                        generateTimeSlots();
                    } else {
                        alert('Error saving slots: ' + (data.message || 'Unknown'));
                    }
                } catch (err) {
                    console.error(err);
                    alert('Server error. Check console.');
                }
            });
        </script>




    </main><!-- End #main -->



    <!-- ======= Footer ======= -->
    <?php include_once "../Includes/Footer.php"; ?>
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



    <?php
    include_once "../Includes/SweetAlert.php";
    ?>

</body>

</html>