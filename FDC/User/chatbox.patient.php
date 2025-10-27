<?php
session_name('patient_session');
session_start();
include "../Config/conn.config.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$doc_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : null;


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

$full_name = $userProfile['first_name'] . ' ' . $userProfile['last_name'];





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

$full_name = $userAcc['first_name'] . ' ' . $userAcc['last_name'];


try {
    $doctor = $conn->prepare("SELECT dpi.firstname, dpi.lastname, dac.specialty, dac.status FROM doctor_acc_creation dac LEFT JOIN doctor_personal_info dpi ON dac.doc_id = dpi.doc_id WHERE dac.status = 'activated' ");
    $doctor->execute();
    $doctorInfo = $doctor->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

//getDoctorData via parameter

$doctorD = $conn->prepare("
    SELECT 
        dac.specialty, 
        dpi.profile_pic,
        dpi.firstname, 
        dpi.lastname
    FROM doctor_acc_creation AS dac 
    LEFT JOIN doctor_personal_info AS dpi ON dac.doc_id = dpi.doc_id
    WHERE dac.doc_id = ?
");
$doctorD->execute([$doc_id]);
$docData = $doctorD->fetch(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "../Includes/Head.php"; ?>

<style>
    .chat-container {
        max-width: 1100px;
        margin: 0px 0px 20px 250px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        height: 80vh;
        display: flex;
        overflow: hidden;
        width: 1100px;
    }

    .doctor-sidebar {
        width: 300px;
        background-color: white;
        border-right: 1px solid var(--border-color);
        overflow-y: auto;

    }

    .doctor-header {
        padding: 15px;
        background-color: #0084FF;
        color: white;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .doctor-list {
        padding: 0;
    }

    .doctor-item {
        padding: 12px 15px;
        border-bottom: 1px solid var(--border-color);
        cursor: pointer;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        border: 1px solid whitesmoke;
        border-radius: 10px;
    }

    .doctor-item:hover {
        background-color: var(--light-gray);
    }

    .doctor-item.active {
        background-color: #e6f2ff;
        border-left: 3px solid var(--primary-color);
    }

    .doctor-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
    }

    .doctor-info {
        flex: 1;
    }

    .doctor-name {
        font-weight: 600;
        margin-bottom: 3px;
        color: var(--text-dark);
    }

    .doctor-specialty {
        font-size: 0.85rem;
        color: var(--text-light);
    }

    .chat-area {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .chat-header {
        padding: 15px;
        background-color: white;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
    }

    .chat-header .doctor-avatar {
        width: 40px;
        height: 40px;
    }

    .chat-header .doctor-info {
        margin-left: 15px;
    }

    .chat-header .doctor-name {
        margin-bottom: 0;
    }

    .chat-header .doctor-status {
        font-size: 0.8rem;
        color: #28a745;
    }

    .messages-container {
        padding: 20px;
        height: 500px;
        overflow-y: auto;
        background: #f4f6f8;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .message-bubble {
        max-width: 70%;
        padding: 10px 14px;
        border-radius: 18px;
        position: relative;
        font-size: 14px;
        line-height: 1.4;
        display: inline-block;
    }

    .sent {
        align-self: flex-end;
        background-color: #007bff;
        color: white;
        border-bottom-right-radius: 0;
    }

    .received {
        align-self: flex-start;
        background-color: #e5e5ea;
        color: #000;
        border-bottom-left-radius: 0;
    }

    .message-time {
        font-size: 11px;
        color: #666;
        margin-top: 4px;
        text-align: right;
    }


    .message {
        margin-bottom: 15px;
        max-width: 70%;
        clear: both;
    }

    .message-content {
        padding: 10px 15px;
        border-radius: 18px;
        display: inline-block;
        word-break: break-word;
    }

    .message-time {
        font-size: 0.7rem;
        color: var(--text-light);
        margin-top: 5px;
        display: block;
    }

    .message.received {
        float: left;
    }

    .message.received .message-content {
        background-color: white;
        color: var(--text-dark);
        border-top-left-radius: 5px;
    }

    .message.sent {
        float: right;
    }

    .message.sent .message-content {
        background-color: #0084FF;
        color: white;
        border-top-right-radius: 5px;
    }

    .message.sent .message-time {
        text-align: right;
    }

    .chat-input {
        padding: 15px;
        background-color: white;
        border-top: 1px solid var(--border-color);
        display: flex;
        align-items: center;
    }

    .chat-input input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 24px;
        outline: none;
        background-color: #f0f2f5;
        border: 1px solid #f0f2f5;
    }

    .chat-input input:focus {
        border-color: var(--primary-color);
        border: 1px solid #f0f2f5;
    }

    .chat-input button {
        background-color: #0084FF;
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin-left: 10px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .chat-input button:hover {
        background-color: rgb(40, 99, 167);
    }

    .back-button {
        margin-right: 10px;
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 1.2rem;
        display: none;
    }

    @media (max-width: 768px) {
        .chat-container {
            height: 90vh;
            margin: 15px;
        }

        .doctor-sidebar {
            width: 100%;
            display: block;
        }

        .chat-area {
            display: none;
        }

        .show-chat .doctor-sidebar {
            display: none;
        }

        .show-chat .chat-area {
            display: flex;
        }

        .back-button {
            display: block;
        }
    }

    /* Modal styles */
    .modal-content {
        border-radius: 12px;
        overflow: hidden;
    }

    .modal-header {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-close {
        filter: brightness(0) invert(1);
    }
</style>



<body>

    <?php include_once "../Includes/Header.php"; ?>
    <?php include_once "../Includes/Sidebar.php"; ?>

    <main id="main" class="main">

    </main>

    <!-- Chat Interface -->
    <div class="container">
        <div class="chat-container">
            <div class="doctor-sidebar">
                <div class="doctor-header">
                    <span>Messages</span>
                    <i class="fas fa-edit"></i>
                </div>
                <div class="doctor-list" id="doctorList">
                </div>
            </div>

            <div class="chat-area">
                <div class="chat-header">
                    <button class="back-button"><i class="fas fa-arrow-left"></i></button>

                    <?php if ($docData): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($docData['profile_pic']); ?>" alt="Doctor" class="doctor-avatar" id="currentDoctorImg">
                        <div class="doctor-info">
                            <div class="doctor-name" id="currentDoctorName">
                                <?php echo "Dr. " . htmlspecialchars($docData['firstname']) . " " . htmlspecialchars($docData['lastname']); ?>
                            </div>
                            <div class="doctor-status">Online</div>
                        </div>
                    <?php else: ?>
                        <img src="../uploads/user.png" alt="Doctor" class="doctor-avatar" id="currentDoctorImg">
                        <div class="doctor-info">
                            <div class="doctor-name" id="currentDoctorName">Unknown Doctor</div>
                            <div class="doctor-status">Offline</div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Messages will be loaded dynamically via JS -->
                <div class="messages-container" id="messagesContainer" data-doctor-id="<?php echo htmlspecialchars($doc_id); ?>">
                    <!-- JS will fetch and render messages here -->
                </div>

                <div class="chat-input">
                    <input type="text" name="message" placeholder="Type a message..." id="messageInput">
                    <button id="sendButton" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>

        </div>
    </div>

    <!-- Chat -->
    <script>
        const patientId = <?php echo json_encode($_SESSION['user_id'] ?? null); ?>;
        let currentDoctorId = null;
        let messagePollingInterval = null;

        document.addEventListener('DOMContentLoaded', () => {
            const doctorList = document.getElementById('doctorList');
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.getElementById('sendButton');
            const doctorNameElem = document.getElementById('currentDoctorName');
            const doctorImgElem = document.getElementById('currentDoctorImg');
            const messagesContainer = document.getElementById('messagesContainer');

            function fetchDoctorList() {
                const previouslyActiveDoctorId = currentDoctorId;

                fetch('../Auth/User/get_doctors_all.php')
                    .then(response => response.json())
                    .then(doctors => {
                        doctorList.innerHTML = '';
                        doctors.forEach(doctor => {
                            const doctorItem = document.createElement('div');
                            doctorItem.className = 'doctor-item';
                            doctorItem.dataset.doctorId = doctor.doctor_id;

                            doctorItem.innerHTML = `
                    <img src="../uploads/${doctor.img || 'user.png'}" alt="${doctor.name}" class="doctor-avatar">
                    <div class="doctor-info">
                        <div class="doctor-name">${doctor.name}</div>
                        <div class="doctor-last-message">${doctor.lastMessage || ''}</div>
                        <div class="message-time">${doctor.lastMessageTime || ''}</div>
                    </div>
                `;

                            // Set up click handler
                            doctorItem.addEventListener('click', () => {
                                loadDoctorChat(doctor.doctor_id, doctor.name, doctor.img);
                                document.querySelectorAll('.doctor-item').forEach(item => item.classList.remove('active'));
                                doctorItem.classList.add('active');
                            });

                            // Restore active styling
                            if (doctor.doctor_id === previouslyActiveDoctorId) {
                                doctorItem.classList.add('active');
                            }

                            doctorList.appendChild(doctorItem);
                        });

                        // If no doctor is selected, select the first one
                        if (!previouslyActiveDoctorId && doctors.length > 0) {
                            const firstDoctor = doctors[0];
                            doctorList.firstChild.classList.add('active');
                            loadDoctorChat(firstDoctor.doctor_id, firstDoctor.name, firstDoctor.img);
                        }
                    });
            }
            fetchDoctorList();
            setInterval(fetchDoctorList, 1000);

            function fetchMessages() {
                const doctorId = document.getElementById('messagesContainer').dataset.doctorId;

                const userId = <?php echo json_encode($user_id); ?>;
                fetch(`../Auth/User/get_messages.php?doctor_id=${doctorId}&patient_id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        const container = document.getElementById('messagesContainer');
                        container.innerHTML = ''; // clear old messages

                        data.forEach(msg => {
                            const div = document.createElement('div');
                            div.className = `message-bubble ${msg.sender === 'user' ? 'sent' : 'received'}`;
                            div.innerHTML = `
                        <div class="message-text">${msg.message}</div>
                        <div class="message-time">${msg.time}</div>
                    `;

                            container.appendChild(div);
                        });


                        // Auto-scroll to bottom
                        container.scrollTop = container.scrollHeight;
                    })
                    .catch(error => {
                        console.error('Error fetching messages:', error);
                    });
            }


            // Call it once on page load
            fetchMessages();

            // Optional: refresh every 3 seconds
            setInterval(fetchMessages, 2000);

            function sendMessage() {
                const userId = <?php echo json_encode($user_id); ?>;

                const messageInput = document.getElementById('messageInput');
                const message = messageInput.value.trim();
                if (!message) return;

                const doctorId = document.getElementById('messagesContainer').dataset.doctorId;

                fetch('../Auth/User/save_message.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `doctor_id=${encodeURIComponent(doctorId)}&patient_id=${encodeURIComponent(userId)}&sender=user&message=${encodeURIComponent(message)}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            messageInput.value = '';
                            fetchMessages(); // refresh messages
                        } else {
                            alert('Error sending message: ' + (data.error || 'Unknown error'));
                        }
                    })
                    .catch(console.error);
            }


            sendButton.addEventListener('click', sendMessage);
            messageInput.addEventListener('keypress', e => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    sendMessage();
                }
            });
        });
    </script>


    <?php include_once "../Includes/Footer.php"; ?>

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