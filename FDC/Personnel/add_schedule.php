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

?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "../Includes/Personnel_Head.php"; ?>

<style>
    .side-close {
        background: transparent;
        border: none;
        font-size: 20px;
        line-height: 1;
        color: #38BDF8;
        cursor: pointer;
        transition: color 0.3s ease;
        margin: 0 0 0 12px;
    }



    .side-close:hover {
        color: #007bff;
        background: none;
    }

    .btn-save {
        display: block;
        margin: 10px auto;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 12px;
    }

    .card-img-top {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .card-header {
        border-bottom: none !important;
    }

    .schedules {
        max-width: 500px;
        width: 100%;
        padding: 20px;
    }

    .schedules h1 {
        font-size: 35px;
        font-weight: bold;
        color: #007bff;
        text-align: center;
        margin-bottom: 10px;
    }

    .schedules span {
        color: gray;
        font-weight: 600;
        display: block;
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group,
    .form-group-time,
    .form-group-desc {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .form-group label,
    .form-group-time label,
    .form-group-desc label {
        font-size: 15px;
        color: rgb(54, 54, 54);
        font-weight: 600;
        margin-bottom: 5px;
        text-align: left;
    }

    .form-group input,
    .form-group-time input {
        padding: 10px;
        font-size: 14px;
        height: 45px;
    }

    .form-group input {
        width: 450px;
    }

    .form-group-time input {
        width: 215px;
    }

    .form-group-desc textarea {
        padding: 10px;
        font-size: 14px;
        height: 100px;
        resize: vertical;
        width: 100%;
    }

    .time-row {
        display: flex;
        gap: 20px;
    }

    .time-row .form-group-time {
        flex: 1;
    }

    @media (max-width: 600px) {
        .schedules {
            width: 100%;
            padding: 15px;
        }

        .form-group input,
        .form-group-time input,
        .form-group-desc textarea {
            width: 100%;
        }

        .time-row {
            flex-direction: column;
            gap: 10px;
        }

        .form-group-time {
            width: 100%;
        }

        .form-group button,
        .form-group .btn {
            width: 100%;
            text-align: center;
        }
    }

    .card-table {
        max-width: 1200px;
        width: 100%;
        padding: 20px;
        border-radius: 10px;
    }

    .card-table table thead {
        background-color: red;
    }

    .card-table h1 {
        margin: -10px 0px -5px -15px;
        color: #007bff;
        font-weight: bold;
        font-size: 30px;
    }

    .card-table .table thead th {
        background-color: whitesmoke;
        color: #007bff;
    }


    .table-button {
        background-color: transparent;
        border: none;
        cursor: pointer;
        margin-right: 8px;
        padding: 5px;

    }

    .table-button:hover {
        background-color: transparent;
        transform: translateY(-2px);
    }

    /* Container styling */
    #calendar {
        max-width: 900px;
        margin: 20px auto;
        background-color: white;
        padding: 20px;
        border-radius: 15px;
    }

    .fc-scrollgrid {
        border: 2px solid #e9ecef;
    }

    /* Calendar Container */
    .calendar-container {
        max-width: 1000px;
        margin: 30px auto;
        background: #ffffff;
        padding: 20px 25px;
        border-radius: 12px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.08);
        font-family: "Segoe UI", Tahoma, sans-serif;
    }

    /* Title */
    .calendar-container h2 {
        font-size: 22px;
        font-weight: 600;
        color: #2C3E50;
        margin-bottom: 20px;
    }

    /* Legend */
    .calendar-container ul {
        display: flex;
        justify-content: center;
        gap: 25px;
        padding: 0;
        margin: 0 0 20px 0;
        list-style: none;
    }

    .calendar-container ul li {
        font-size: 14px;
        font-weight: 500;
        color: #444;
        position: relative;
        padding-left: 28px;
    }

    /* Legend color indicators */
    .calendar-container ul li::before {
        content: "";
        width: 16px;
        height: 16px;
        border-radius: 4px;
        position: absolute;
        left: 0;
        top: 2px;
    }

    .calendar-container ul li:nth-child(1)::before {
        background-color: #28a745;
        /* Available = green */
    }

    .calendar-container ul li:nth-child(2)::before {
        background-color: #dc3545;
        /* Booked = red */
    }

    .calendar-container ul li:nth-child(3)::before {
        background-color: #6c757d;
        /* Expired = gray */
    }

    /* FullCalendar tweaks */
    .fc .fc-toolbar-title {
        font-size: 18px;
        font-weight: 600;
        color: #2C3E50;
    }

    .fc .fc-button {
        background: #2C3E50 !important;
        color: #fff !important;
        border: none !important;
        border-radius: 6px !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
        transition: 0.2s ease-in-out;
    }

    .fc .fc-button:hover {
        background: #1A252F !important;
    }

    /* Calendar table */
    .fc .fc-timegrid-slot {
        height: 45px;
        /* taller slots for clarity */
    }


    /* Title */
    .calendar-title {
        text-align: center;
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 20px;

    }

    /* FullCalendar body */
    #calendar {
        background: #fafafa;
        border-radius: 12px;
        padding: 10px;
        border: 2px solid #e9ecef;
    }

    /* Header day names - text at the top */
    .fc-col-header-cell {
        background: #2c5aa0;
        color: white !important;
        /* Keep text white */
        font-weight: bold;
        vertical-align: top;
        padding-top: 4px;

    }

    /* Force ALL header text to be white */
    .fc .fc-col-header-cell .fc-scrollgrid-sync-inner,
    .fc .fc-col-header-cell .fc-scrollgrid-sync-inner a {
        color: white !important;
        text-decoration: none;
        padding: 6px;
    }

    /* Ensure the 'today' header text is also white */
    .fc .fc-col-header-cell.fc-day-today .fc-scrollgrid-sync-inner,
    .fc .fc-col-header-cell.fc-day-today .fc-scrollgrid-sync-inner a {
        color: white !important;
        text-decoration: none;
        padding: 6px;
    }

    .fc-timegrid-slot-label-frame.fc-scrollgrid-shrink-frame {
        background: #2c5aa0;
        color: white;
        font-weight: bold;
        padding-top: 10px;
        padding-bottom: 10px;
        padding-left: 8px;
        line-height: 1.6;
        margin-right: 35px;
        margin-left: 20px;
    }

    /* Ensure hover looks exactly the same */
    .fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink:hover {
        background: #2c5aa0 !important;
        color: white !important;
    }

    .fc-timegrid-slot.fc-timegrid-slot-label.fc-timegrid-slot-minor {
        transition: none !important;
        /* remove animations */
        pointer-events: none;
        /* disable hover/click effects */
    }

    /* Lock hover state so nothing changes */
    .fc-timegrid-slot.fc-timegrid-slot-label.fc-timegrid-slot-minor:hover {
        background: inherit !important;
        color: inherit !important;
    }


    .fc-timegrid td,
    .fc-timegrid th {
        text-align: center;
        vertical-align: middle;
    }


    .fc-timegrid-slot-label {
        background: #2c5aa0;
        color: white;
        font-weight: bold;
        vertical-align: top;
    }


    /* Event style */
    .fc-event {
        font-size: 0.85rem;
        font-weight: bold;
        border-radius: 8px !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .fc-event:hover {
        transform: scale(1.05);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    }

    /* Time slots (hover) */
    .fc-timegrid-slot:hover {
        background-color: rgba(0, 123, 255, 0.05);
        cursor: pointer;
    }


    /* Style for FullCalendar navigation buttons */
    .fc .fc-button {
        background-color: #E0F2FE !important;
        /* dark background */
        color: black !important;
        /* white text */
        border: none !important;
        border-radius: 6px !important;
        padding: 10px 16px !important;
        font-size: 14px !important;
        font-weight: bold !important;
        cursor: pointer !important;
        transition: all 0.3s ease-in-out;
    }

    /* Hover effect */
    .fc .fc-button:hover {
        background-color: #38BDF8 !important;
        transform: scale(1.05);
    }

    /* Disabled state */
    .fc .fc-button:disabled {
        background-color: #999 !important;
        cursor: not-allowed !important;
    }

    /* Specific for prev/next icons */
    .fc-prev-button,
    .fc-next-button {
        font-size: 18px !important;
        width: 40px !important;
        height: 40px !important;
        display: flex !important;
        align-items: center;
        justify-content: center;
    }

    /* Make prev/today/title/next sit closer */
    .fc .fc-toolbar.fc-header-toolbar {
        justify-content: center;
    }

    .fc .fc-toolbar-chunk {
        display: flex;
        align-items: center;
        gap: 6px;
        /* adjust spacing between buttons & date */
    }

    .fc-toolbar-title {
        margin: 0 6px;
        /* small space around the date */
        font-weight: 600;
    }

    /* Header strip for the patient */
    .welcome {
        background: rgba(255, 255, 255, .94);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 4px 14px rgba(2, 6, 23, .06);
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .welcome h2 {
        margin: 0;
        font-size: 20px;
        letter-spacing: .2px;
    }

    .welcome p {
        margin: 4px 0 0;
        color: #475569;
        font-size: 14px;
    }

    .welcome .grow {
        flex: 1;
    }

    .actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .welcome-btn {
        border: 0;
        border-radius: 12px;
        padding: 10px 14px;
        font-weight: 700;
        cursor: pointer;
        background: #0ea5e9;
        color: #fff;
        transition: transform .05s ease, background .2s ease;
    }

    .welcome-btn span {
        font-size: 12px;
        margin: 0;
        color: gray;
    }

    .welcome-btn:hover {
        background: #0369a1;
    }

    .welcome-btn:active {
        transform: translateY(1px);
    }

    .welcome-btn.alt {
        background: #f8fafc;
        color: #0f172a;
        border: 1px solid #e2e8f0;
        font-weight: 600;
    }

















    /* 💻 Tablets / small laptops (min 769px to max 1020px) */
    @media (min-width: 769px) and (max-width: 1020px) {
        .calendar-container {
            max-width: 850px;
            padding: 18px;
            margin-left: 20px;
        }

        .calendar-container h2 {
            font-size: 20px;
        }

        .calendar-container ul {
            gap: 18px;
        }

        .calendar-container ul li {
            font-size: 13px;
        }

        #calendar {
            max-width: 820px;
            padding: 15px;
        }

        .fc .fc-toolbar-title {
            font-size: 16px;
        }

        .fc .fc-button {
            padding: 6px 10px !important;
            font-size: 12px !important;
        }

        .btn-save {
            font-size: 14px;
            padding: 8px 16px;
        }
    }

    /* 📲 Small tablets / large phones (min 481px to max 768px) */
    @media (min-width: 481px) and (max-width: 768px) {
        .calendar-container {
            max-width: 100%;
            padding: 15px;
            margin-left: 20px;
        }

        .calendar-container h2 {
            font-size: 18px;
        }

        .calendar-container ul {
            flex-direction: row;
            flex-wrap: wrap;
            gap: 15px;
        }

        .calendar-container ul li {
            font-size: 12px;
        }

        #calendar {
            max-width: 100%;
            padding: 10px;
        }

        .fc .fc-toolbar-title {
            font-size: 15px;
        }

        .fc .fc-button {
            padding: 5px 9px !important;
            font-size: 11px !important;
        }

        .btn-save {
            font-size: 13px;
            padding: 8px 15px;
        }
    }

    /* 📱 Mobile (≤480px) */
    @media (max-width: 480px) {
        .calendar-container {
            width: 100%;
            padding: 8px;
            margin: 10px auto;
            border-radius: 8px;
        }

        .calendar-container h2 {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .calendar-container ul {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
            margin-bottom: 10px;
        }

        .calendar-container ul li {
            font-size: 10px;
            padding-left: 18px;
        }

        #calendar {
            max-width: 100%;
            padding: 5px;
            border-radius: 8px;
        }

        /* 🕒 Make time column even narrower */
        .fc-timegrid-axis {
            width: 28px !important;
            min-width: 28px !important;
            font-size: 9px !important;
            line-height: 1.1;
        }

        /* 🗓️ Make time labels tighter */
        .fc-timegrid-slot-label {
            padding: 0 1px !important;
            text-align: right !important;
        }

        /* 📅 Title + Buttons */
        .fc .fc-toolbar-title {
            font-size: 11px !important;
        }

        .fc .fc-button {
            padding: 3px 6px !important;
            font-size: 9px !important;
            border-radius: 4px !important;
        }

        /* 🧭 Shrink slot height and event text */
        .fc-timegrid-slot {
            height: 10px !important;
            min-height: 10px !important;
        }

        .fc-event {
            font-size: 0.6rem !important;
            padding: 1px 3px !important;
        }

        /* 💾 Save button compact */
        .btn-save {
            width: 100%;
            font-size: 11px;
            padding: 6px 0;
            border-radius: 8px;
        }

        /* 🗓️ Make table cells thinner */
        td.fc-timegrid-slot.fc-timegrid-slot-label.fc-scrollgrid-shrink {
            height: 20px !important;
            padding: 0 !important;
            font-size: 10px;
        }

        /* 🔠 Make top day headers smaller */
        .fc-col-header-cell-cushion {
            font-size: 6px !important;
            padding: 2px 0 !important;
        }

        /* 🔠 Optional: shrink day number separately if visible */
        .fc-col-header-cell-cushion span {
            font-size: 9px !important;
        }
    }




    /* 🖥️ Large desktops (≥1021px) */
    @media (min-width: 1021px) {
        .calendar-container {
            max-width: 1000px;
            padding: 25px;
            margin-left: 20px;
        }

        .calendar-container h2 {
            font-size: 22px;
        }

        #calendar {
            max-width: 950px;
            padding: 20px;

        }

        .btn-save {
            font-size: 16px;
            padding: 10px 20px;
        }
    }
</style>



<body>

    <?php include_once "../Includes/Personnel_Header.php"; ?>
    <?php include_once "../Includes/Personnel_Sidebar.php"; ?>

    <main id="main" class="main">

        <?php include_once "../Includes/Personnel_Welcome.php"; ?>


        <section class="section dashboard mt-4">
            <div class="calendar-container">
                <h2 style="text-align:center">Set your Schedule</h2>
                <ul>
                    <li>Available</li>
                    <li>Already Booked</li>
                    <li>Expired</li>
                </ul>
                <div id="calendar"></div>
                <button id="saveBtn" class="btn-save">Save Availability</button>
            </div>
        </section>
    </main><!-- End #main -->



    <!-- ======= Footer ======= -->
    <?php include_once "../Includes/Footer.php"; ?>
    <!-- End Footer -->


    <!-- Calendar Script -->
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
                nowIndicator: true,

                // ✅ Add header toolbar without deleting anything
                headerToolbar: {
                    left: '',
                    center: 'prev title next today',
                    right: ''
                },

                selectAllow: (selectInfo) => {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    return selectInfo.start >= today;
                },

                events: {
                    url: '../auth/Personnel/fetch_schedule.php?doctor_id=<?= (int)$doc_id ?>',
                    method: 'GET',
                    failure: () => alert('Failed to fetch schedules'),
                    eventSourceSuccess: (events) => {
                        existingSlots = events.map(ev => ev.start + '_' + ev.end);
                        return events;
                    }
                },

                eventDidMount: function(info) {
                    const eventStart = info.event.start;
                    if (!eventStart) return;

                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    // Determine availability label
                    let availability = info.event.extendedProps.availability || info.event.title || 'Available';

                    // Override to Expired if in the past
                    if (eventStart < today) {
                        availability = 'Expired';
                        info.el.classList.add('fc-past-event');
                        info.el.setAttribute('title', 'Expired (past event)');
                    }

                    // Style by availability
                    switch (availability) {
                        case 'Available':
                            info.el.style.backgroundColor = '#28a745'; // green
                            info.el.style.borderColor = '#28a745';
                            info.el.style.color = 'white';
                            break;
                        case 'Booked':
                            info.el.style.backgroundColor = '#dc3545'; // red
                            info.el.style.borderColor = '#dc3545';
                            info.el.style.color = 'white';
                            break;
                        case 'Expired':
                            info.el.style.backgroundColor = '#6c757d'; // gray
                            info.el.style.borderColor = '#6c757d';
                            info.el.style.color = 'white';
                            break;
                    }

                    // New unsaved slots are blue (override colors)
                    if (info.event.extendedProps.new) {
                        info.el.style.backgroundColor = '#007bff';
                        info.el.style.borderColor = '#007bff';
                        info.el.style.color = 'white';
                    }
                },

                select: function(info) {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    const startDate = new Date(info.start);
                    startDate.setHours(0, 0, 0, 0);

                    if (startDate <= today) {
                        alert("⚠️ You cannot select slots for today or past dates.");
                        calendar.unselect();
                        return;
                    }

                    const key = info.startStr + '_' + info.endStr;

                    if (existingSlots.includes(key)) {
                        alert("This slot is already saved.");
                        return;
                    }
                    if (selectedSlots.some(slot => slot.start === info.startStr && slot.end === info.endStr)) {
                        alert("This slot is already selected.");
                        return;
                    }

                    highlightColumn(info.start);

                    const event = calendar.addEvent({
                        title: 'Available',
                        start: info.startStr,
                        end: info.endStr,
                        extendedProps: {
                            availability: 'Available',
                            new: true
                        }
                    });

                    // Push to selectedSlots for saving later
                    selectedSlots.push({
                        start: info.startStr,
                        end: info.endStr
                    });
                },

                eventClick: function(info) {
                    const eventStart = new Date(info.event.start);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    if (eventStart <= today) {
                        alert("⚠️ You cannot modify past or current date slots.");
                        return;
                    }

                    if (info.event.extendedProps.new) {
                        // Unsaved slot - remove locally
                        if (confirm("Remove this unsaved slot?")) {
                            selectedSlots = selectedSlots.filter(
                                slot => !(slot.start === info.event.startStr && slot.end === info.event.endStr)
                            );
                            info.event.remove();
                        }
                    } else {
                        // Saved slot - confirm deletion and delete from DB
                        if (confirm("Delete this saved availability slot? This action cannot be undone.")) {
                            const payload = {
                                doctor_id: <?= (int)$doc_id ?>,
                                date_slots: info.event.startStr.split('T')[0],
                                start_time: new Date(info.event.start).toTimeString().split(' ')[0],
                                end_time: new Date(info.event.end).toTimeString().split(' ')[0]
                            };

                            fetch('../auth/Personnel/delete_availability.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(payload)
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        alert('Availability slot deleted.');
                                        info.event.remove();
                                        calendar.refetchEvents();
                                    } else {
                                        alert('Failed to delete availability slot.');
                                    }
                                })
                                .catch(err => {
                                    console.error('Error deleting availability:', err);
                                    alert('An error occurred while deleting the slot.');
                                });
                        }
                    }
                },

                viewDidMount: markPastDays,
                datesSet: markPastDays
            });

            calendar.render();

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

            function markPastDays() {
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                document.querySelectorAll('.fc-timegrid-col.fc-past-day').forEach(c => c.classList.remove('fc-past-day'));
                document.querySelectorAll('.fc-col-header-cell .past-label').forEach(l => l.remove());

                document.querySelectorAll('.fc-timegrid-col').forEach(col => {
                    const dateStr = col.getAttribute('data-date');
                    if (dateStr) {
                        const colDate = new Date(dateStr);
                        if (colDate <= today) {
                            col.classList.add('fc-past-day');
                        }
                    }
                });

                document.querySelectorAll('.fc-col-header-cell').forEach(header => {
                    const dateStr = header.getAttribute('data-date');
                    if (dateStr) {
                        const headerDate = new Date(dateStr);
                        if (headerDate <= today) {
                            const label = document.createElement('span');
                            label.className = 'past-label';
                            label.textContent = " (Past)";
                            header.appendChild(label);
                        }
                    }
                });
            }

            document.getElementById('saveBtn').addEventListener('click', function() {
                if (selectedSlots.length === 0) {
                    alert("No new time slots selected.");
                    return;
                }

                const formattedSlots = selectedSlots.map(slot => {
                    const startObj = new Date(slot.start);
                    const endObj = new Date(slot.end);

                    const date_slots = startObj.toISOString().split('T')[0];
                    const start_time = startObj.toTimeString().split(' ')[0];
                    const end_time = endObj.toTimeString().split(' ')[0];

                    return {
                        date_slots: date_slots,
                        start_time: start_time,
                        end_time: end_time
                    };
                });

                fetch('../auth/Personnel/save_availability.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            doctor_id: <?= (int)$doc_id ?>,
                            slots: formattedSlots
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Availability saved!');
                            selectedSlots = [];
                            calendar.getEvents().forEach(event => {
                                if (event.extendedProps.new) event.remove();
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


    <style>
        .fc-highlighted-column {
            background-color: rgba(0, 123, 255, 0.12) !important;
            /* blue tint for new */
        }

        .fc-highlighted-header {
            background-color: rgba(0, 123, 255, 0.22) !important;
        }

        .fc-past-day {
            background-color: #f1f3f5 !important;
            opacity: 0.95;
        }

        .fc-past-event {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
            color: #fff !important;
            opacity: 0.9;
        }

        .fc-past-event .fc-event-title {
            font-style: italic;
            color: #fff !important;
        }

        .fc-col-header-cell .past-label {
            color: #6c757d;
            font-style: italic;
            margin-left: 4px;
            font-size: 0.9em;
        }
    </style>






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