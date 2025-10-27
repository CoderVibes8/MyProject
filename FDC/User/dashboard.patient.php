<?php
session_name('patient_session');
session_start();
include_once "../Config/conn.config.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];


//Retrieve all activated doctors
try {
    $doctors = $conn->prepare("
        SELECT dpi.* , dac.specialty 
        FROM doctor_acc_creation AS dac 
        LEFT JOIN doctor_personal_info AS dpi ON dac.doc_id = dpi.doc_id  
        WHERE dac.status = 'activated'
    ");
    $doctors->execute();
    $doc_info = $doctors->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


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






try {
    $user = $conn->prepare("SELECT up.*, uc.profile_picture FROM user_patient up 
                               LEFT JOIN user_credentials uc ON up.user_id = uc.user_id 
                               WHERE up.user_id = ?");
    $user->execute([$user_id]);
    $userAcc = $user->fetch(PDO::FETCH_ASSOC);

    $profile_picture = !empty($userAcc['profile_picture'])
        ? "../uploads/" . htmlspecialchars($userAcc['profile_picture'])
        : "../uploads/user.png";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


try {
    $doctor = $conn->prepare("SELECT dpi.firstname, dpi.lastname, dac.specialty, dac.status, dci.onsite_rate, dpi.doc_id  FROM doctor_acc_creation dac JOIN doctor_personal_info dpi ON dac.doc_id = dpi.doc_id JOIN doctor_consultation_info dci ON dac.doc_id = dci.doc_id WHERE dac.status = 'activated' ");
    $doctor->execute();
    $doctorInfo = $doctor->fetchAll(PDO::FETCH_ASSOC);
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
    </main>



    <!-- Chat a Doctor -->
    <section class="section dashboard" style="margin-left: 130px;">
        <div class="row g-3">
            <!-- <div class="col-md-3 mb-3">
                <div class="appointment-card blue-card" data-bs-toggle="modal" data-bs-target="#selectDoctor">
                    <div class="card-content">
                        <h3 class="card-title">Chat a Personnel</h3>
                        <p class="card-subtitle">Choose your favorite personnel</p>
                        <div class="card-icon">
                            <i class="fi fi-rr-comment-alt-dots"></i>
                        </div>
                    </div>
                    <div class="card-decoration"></div>
                </div>
            </div> -->

            <div class="col-md-3 mb-3">
                <div class="appointment-card green-card" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                    <div class="card-content">
                        <h3 class="card-title">Book Appointment</h3>
                        <p class="card-subtitle">Schedule your next visit</p>
                        <div class="card-icon">
                            <i class="fi fi-tr-choose"></i>
                        </div>
                    </div>
                    <div class="card-decoration"></div>
                </div>
            </div>
        </div>


        <div class="container about-content" style="margin-left: 730px;">
            <section class="card card-about" aria-label="About and Contact">
                <div class="art" aria-hidden="true">
                    <!-- Compact doctor SVG -->
                    <svg width="220" height="180" viewBox="0 0 220 180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Doctor">
                        <circle cx="110" cy="90" r="78" fill="#e0f2fe" />
                        <path d="M65 142 Q110 120 155 142 L155 170 L65 170 Z" fill="#93c5fd" />
                        <path d="M75 142 L110 120 L145 142 L145 170 L75 170 Z" fill="#fff" stroke="#cbd5e1" />
                        <circle cx="110" cy="80" r="26" fill="#fde68a" />
                        <path d="M90 76 C98 58, 122 58, 130 76 C118 66, 102 66, 90 76 Z" fill="#0f172a" />
                        <circle cx="101" cy="82" r="3.5" fill="#0f172a" />
                        <circle cx="119" cy="82" r="3.5" fill="#0f172a" />
                        <path d="M100 96 Q110 103 120 96" stroke="#0f172a" stroke-width="3" fill="none" stroke-linecap="round" />
                        <path d="M98 115 C92 124, 92 138, 104 142" stroke="#0ea5e9" stroke-width="3" fill="none" />
                        <circle cx="106" cy="144" r="5.5" fill="#0ea5e9" />
                        <path d="M122 115 C128 124, 128 138, 116 142" stroke="#0ea5e9" stroke-width="3" fill="none" />
                        <circle cx="114" cy="144" r="5.5" fill="#0ea5e9" />
                    </svg>
                </div>

                <div>
                    <h2 class="title-about">About FDC</h2>
                    <p class="text-about">Simple, connected care. Quick appointment booking, reliable diagnostics, and clear communication—all in one place.</p>

                    <div class="rows-about">
                        <div class="row-about">

                            <div class="text-about">Trusted clinicians • Secure access • Quick support</div>
                        </div>
                        <div class="row-about">

                            <div>
                                <div class="text-about"><strong>Email:</strong>diagnosticfamily@gmail.com</div>
                            </div>
                        </div>
                        <div class="row-about">

                            <div class="text-about"><strong>Hours:</strong> Mon–Fri, 6:00 AM – 4:30 PM</div>
                        </div>
                    </div>

                    <div class="actions-about">
                        <button class="btn-about" type="button" id="openContact">Contact</button>
                        <button class="btn-about alt" type="button">Learn more</button>
                    </div>
                </div>
            </section>
        </div>
    </section>


    <!-- Modal for Appointment Booking -->
    <div class="bookappointmentmodal modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content p-4" style="max-height: 110vh; overflow-y: auto; border-radius:10px">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: #333; margin-right:auto;">Book an Appointment</h5>
                    <button type="button" class="custom-close" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-bs-cross"></i></button>

                </div>
                <div class="modal-body">
                    <!-- Centered Logo -->
                    <div class="text-center">
                        <img src="../Assets/img/familylogo.jpg" alt="Logo" class="img-fluid mb-3" style="width: 60px;">
                    </div>

                    <form class="form-contact" action="../Auth/User/book_appointment.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">First Name</label>
                                    <input type="text" class="form-control" name="first_name" placeholder="Enter first name" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Gender</label>
                                    <select name="gender" class="form-control" required style="cursor: pointer;">
                                        <option selected disabled>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="prefer-not-to-say">Prefer not to say</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($userAcc['email']); ?>" placeholder="<?php echo htmlspecialchars($userAcc['email']); ?>" required>
                                </div>

                                <?php
                                try {
                                    $stmt = $conn->prepare("SELECT DISTINCT consultation_type FROM doctor_schedule ORDER BY consultation_type ASC");
                                    $stmt->execute();
                                    $appointmentTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                } catch (PDOException $e) {
                                    echo "Error: " . $e->getMessage();
                                    $appointmentTypes = [];
                                }
                                ?>


                                <!-- Appointment Type (from DB) -->
                                <div class="form-group">
                                    <label class="font-weight-bold">Procedure Type</label>
                                    <select name="appointment_type" id="appointmentType" class="form-select" required style="cursor: pointer;">
                                        <option value="" selected disabled>-- Select Procedure Type --</option>
                                        <?php foreach ($appointmentTypes as $type): ?>
                                            <option value="<?= htmlspecialchars($type['consultation_type']) ?>">
                                                <?= htmlspecialchars($type['consultation_type']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Preferred Date -->
                                <div class="form-group">
                                    <label class="font-weight-bold">Preferred Date</label>
                                    <select name="date" id="preferredDate" class="form-select" required style="cursor: pointer;">
                                        <option value="" selected disabled>Select a date</option>
                                        <option value="other" title="⚠️ Selecting a custom date may cause delays or rejection of your booking, as it requires personnel approval. ⚠️">
                                            Other (Custom Date)
                                        </option>

                                    </select>
                                    <input type="date" id="customDate" name="custom_date" class="form-control mt-2" style="display:none;">
                                    
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" placeholder="Enter last name" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Contact Number</label>
                                    <input type="tel" class="form-control" name="contact_number" placeholder="Enter contact number" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Estimated Expenses</label>
                                    <input type="text" id="estimatedExpense" class="form-control" name="total_expense" value="0" readonly>
                                </div>

                                <!-- Doctor (filtered by appointment type) -->
                                <div class="form-group">
                                    <label for="doctor" class="form-label font-weight-bold">Choose Doctor</label>
                                    <select class="form-select" id="doctor" name="doctor" required style="cursor: pointer;">
                                        <option value="" selected disabled>-- Select a Doctor --</option>
                                    </select>
                                    <input type="hidden" name="doc_id" id="doctor_id" value="">
                                </div>

                                <!-- Preferred Time -->
                                <div class="form-group">
                                    <label class="font-weight-bold">Preferred Time</label>
                                    <select id="preferredTime" name="time" class="form-select" required style="cursor: pointer;">
                                        <option value="" selected disabled>Select time</option>
                                        <option value="other"
                                            title="⚠️ Selecting a custom time may cause delays or rejection of your booking, as it requires personnel approval. ⚠️">Other (Custom Time)</option>
                                    </select>

                                    <!-- Custom Start Time -->
                                    <div id="customTime" class="mt-2" style="display:none;">
                                        <input type="time" name="custom_time_start" class="form-control" id="customTimeStart" placeholder="Start Time">
                                    </div>
                                </div>

                                <input type="hidden" name="isCustomed" id="isCustomed" value="0">

                            </div>
                        </div>

                        <div class="modal-footer border-0 d-flex flex-column">
                            <button type="submit" class="btn btn-primary btn-block" name="book_appointment">
                                Book Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php include_once "../Includes/Footer.php"; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const appointmentSelect = document.getElementById('appointmentType');
            const doctorSelect = document.getElementById('doctor');
            const dateSelect = document.getElementById('preferredDate');
            const timeSelect = document.getElementById('preferredTime');
            const customDateInput = document.getElementById('customDate');
            const customTimeInput = document.getElementById('customTime');
            const customTimeStart = document.getElementById('customTimeStart');
            const expenseInput = document.getElementById('estimatedExpense');
            const doctorIdInput = document.getElementById('doctor_id');

            // Minimum date for custom date = tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            customDateInput.min = tomorrow.toISOString().split('T')[0];

            // Fetch doctors when appointment type changes
            appointmentSelect.addEventListener('change', function() {
                const type = this.value;
                doctorSelect.innerHTML = '<option value="" selected disabled>Loading doctors...</option>';
                dateSelect.innerHTML = '<option value="" selected disabled>Select a date</option><option value="other">Other (Custom Date)</option>';
                timeSelect.innerHTML = '<option value="" selected disabled>Select time</option><option value="other">Other (Custom Time)</option>';
                expenseInput.value = '0';
                customDateInput.style.display = 'none';
                customTimeInput.style.display = 'none';

                if (!type) return;

                fetch(`../Auth/User/Get_Doctors_By_Type.php?type_id=${type}`)
                    .then(res => res.json())
                    .then(doctors => {
                        doctorSelect.innerHTML = '<option value="" selected disabled>-- Select a Doctor --</option>';
                        doctors.forEach(doc => {
                            const option = document.createElement('option');
                            option.value = doc.name;
                            option.setAttribute('data-id', doc.doctor_id);
                            option.setAttribute('data-rate', doc.onsite_rate);
                            option.textContent = `Dr. ${doc.name} (${doc.specialty})`;
                            doctorSelect.appendChild(option);
                        });
                    });
            });

            // Fetch available dates when doctor changes
            doctorSelect.addEventListener('change', function() {
                const selectedOption = this.selectedOptions[0];
                const doctorId = selectedOption.getAttribute('data-id');
                const rate = selectedOption.getAttribute('data-rate');

                doctorIdInput.value = doctorId || '';
                expenseInput.value = rate || '0';
                dateSelect.innerHTML = '<option value="" selected disabled>Select a date</option><option value="other">Other (Custom Date)</option>';
                timeSelect.innerHTML = '<option value="" selected disabled>Select time</option><option value="other">Other (Custom Time)</option>';
                customDateInput.style.display = 'none';
                customTimeInput.style.display = 'none';

                if (!doctorId || !appointmentSelect.value) return;

                fetch(`../Auth/User/Get_Available_Dates.auth.php?doc_id=${doctorId}&type_id=${appointmentSelect.value}`)
                    .then(res => res.json())
                    .then(dates => {
                        dates.forEach(date => {
                            const option = document.createElement('option');
                            option.value = date.raw;
                            option.textContent = date.label;
                            dateSelect.appendChild(option);
                        });
                    });
            });

            // Handle date change
            dateSelect.addEventListener('change', function() {
                if (this.value === 'other') {
                    document.getElementById('isCustomed').value = "1"; // custom date = 1
                    customDateInput.style.display = 'block';
                    timeSelect.innerHTML = '<option value="" selected disabled>Select time</option><option value="other">Other (Custom Time)</option>';
                } else {
                    customDateInput.style.display = 'none';
                    customTimeInput.style.display = 'none';
                    document.getElementById('isCustomed').value = "0"; // reset back to 0

                    const doctorId = doctorSelect.selectedOptions[0]?.getAttribute('data-id');
                    if (!doctorId || !appointmentSelect.value) return;

                    fetch(`../Auth/User/Get_Available_Times.auth.php?doc_id=${doctorId}&date=${this.value}&type_id=${appointmentSelect.value}`)
                        .then(res => res.json())
                        .then(times => {
                            timeSelect.innerHTML = '<option value="" selected disabled>Select time</option><option value="other">Other (Custom Time)</option>';
                            times.forEach(slot => {
                                const option = document.createElement('option');
                                option.value = slot.value;
                                option.textContent = slot.label;
                                timeSelect.appendChild(option);
                            });
                        });
                }
            });

            // Handle time change
            timeSelect.addEventListener('change', function() {
                if (this.value === 'other') {
                    customTimeInput.style.display = 'block';
                    document.getElementById('isCustomed').value = "1"; // custom time = 1

                    // Auto set end time based on consultation type
                    customTimeStart.addEventListener('change', () => {
                        const startTime = customTimeStart.value;
                        const type = appointmentSelect.value.toLowerCase();
                        let endTime = startTime;

                        if (type === 'ultra sound') {
                            // 15 minutes
                            endTime = addMinutesToTime(startTime, 15);
                        } else if (['2d echo', 'vascular studies', 'dna'].includes(type)) {
                            // 1 hour
                            endTime = addMinutesToTime(startTime, 60);
                        }

                        // Save as "start|end" for PHP
                        customTimeStart.dataset.timeValue = `${startTime}|${endTime}`;
                    });
                } else {
                    customTimeInput.style.display = 'none';
                    document.getElementById('isCustomed').value = "0"; // reset back to 0
                }
            });

            function addMinutesToTime(time, minsToAdd) {
                const [h, m] = time.split(':').map(Number);
                let date = new Date();
                date.setHours(h);
                date.setMinutes(m + minsToAdd);
                const hh = date.getHours().toString().padStart(2, '0');
                const mm = date.getMinutes().toString().padStart(2, '0');
                return `${hh}:${mm}`;
            }
        });
    </script>



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
    <script src="Assets/js/main.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php include_once "../Includes/SweetAlert.php"; ?>
</body>

</html>