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
        <div class="chatbox-container">
            <!-- Sidebar -->
            <div class="chatbox-sidebar">
                <div class="chatbox-sidebar-header">


                    <h2 class="chatbox-title">Recent Conversations</h2>
                    <p class="chatbox-subtitle">Your patient communications</p>
                    <div class="chatbox-stats">
                        <div class="chatbox-stat">
                            <div class="chatbox-dot online"></div>
                            <span id="online-count">0 Online</span>
                        </div>
                        <div class="chatbox-stat">
                            <div class="chatbox-dot offline"></div>
                            <span id="offline-count">0 Offline</span>
                        </div>
                    </div>
                </div>

                <div class="chatbox-search">
                    <input type="text" class="chatbox-search-input" placeholder="Search patients...">
                </div>

                <div class="chatbox-contacts" id="chatbox-contacts"></div>
            </div>

            <!-- Chat Area -->
            <div class="chatbox-main">
                <div class="chatbox-header">
                    <!-- Back / Previous Button (Mobile Only) -->
                    <button class="chatbox-back-btn" id="chatbox-back-btn" style="display:none; ">
                        <i class="bi bi-arrow-left"></i>
                    </button>

                    <div class="chatbox-header-info">
                        <div class="chatbox-breadcrumb">
                            <span>Messages</span>
                            <span class="chatbox-separator">›</span>
                            <span id="active-contact-name">Select a patient</span>
                        </div>
                        <div class="chatbox-user" id="chatbox-user-info">
                            <!-- Filled dynamically -->
                        </div>
                    </div>
                </div>

                <div class="chatbox-messages" id="chatbox-messages"></div>

                <div class="chatbox-input-area">
                    <textarea class="chatbox-input" placeholder="Type your message..."></textarea>
                    <button class="chatbox-send mt-2"><i class="fi fi-tr-paper-plane-top" style="margin-left: -2px;"></i></button>
                </div>
            </div>
        </div>

    </main><!-- End #main -->

    <script>
        let activeContactIndex = 0;
        let contacts = [];
        let lastMessageId = 0;
        const doctor_id = <?php echo json_encode($doc_id); ?>;

        // ===== Fetch patients =====
        async function fetchContacts() {
            try {
                const res = await fetch(`../Auth/Personnel/Fetch_Contacts.php?doctor_id=${doctor_id}`);
                contacts = await res.json();

                // 🔧 Sort contacts: newest chat (latest message) first
                contacts.sort((a, b) => {
                    const timeA = new Date(a.lastMsgTime || a.time || 0);
                    const timeB = new Date(b.lastMsgTime || b.time || 0);
                    return timeB - timeA;
                });

                renderContacts(contacts);
            } catch (err) {
                console.error('Error fetching contacts:', err);
            }
        }

        // ===== Render contacts =====
        function renderContacts(data) {
            const list = document.getElementById('chatbox-contacts');
            const onlineCount = document.getElementById('online-count');
            const offlineCount = document.getElementById('offline-count');
            list.innerHTML = '';
            let online = 0,
                offline = 0;

            if (!data.length) {
                list.innerHTML = `<div class="text-center p-3 text-muted">No active patients</div>`;
                document.getElementById('chatbox-messages').innerHTML = '';
                onlineCount.textContent = '0 Online';
                offlineCount.textContent = '0 Offline';
                return;
            }

            data.forEach((p, i) => {
                if (p.isOnline === 'Online') online++;
                else offline++;

                const avatar = p.profile ?
                    `<img src="../uploads/${p.profile}" style="width:45px;height:45px;border-radius:50%;object-fit:cover;">` :
                    `<div class="chatbox-avatar-text">${p.initials}</div>`;

                const statusHTML = p.isOnline === 'Online' ? `<div class="chatbox-status online"></div>` : '';
                const preview = p.lastMsgFrom === 'personnel' ? `You: ${p.lastMsg}` : p.lastMsg;
                const msgClass = p.isRead === '0' ? 'fw-bold' : '';

                const div = document.createElement('div');
                div.classList.add('chatbox-contact');
                if (i === activeContactIndex) div.classList.add('active');
                div.innerHTML = `
                <div class="chatbox-avatar">${avatar}${statusHTML}</div>
                <div class="chatbox-contact-info">
                    <div class="chatbox-name">${p.name}</div>
                    <div class="chatbox-role">Patient</div>
                    <div class="chatbox-lastmsg ${msgClass}">${preview || 'No messages yet'}</div>
                </div>
                <div class="chatbox-time">${p.time || ''}</div>
            `;
                div.addEventListener('click', () => {
                    activeContactIndex = i;
                    markAsRead(p.patient_id);
                    loadChat(p, true);
                });

                list.appendChild(div);
            });

            onlineCount.textContent = `${online} Online`;
            offlineCount.textContent = `${offline} Offline`;

            loadChat(data[activeContactIndex], true);
        }

        // ===== Load chat messages =====
        async function loadChat(p, forceReload = false) {
            document.querySelectorAll('.chatbox-contact').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.chatbox-contact')[activeContactIndex]?.classList.add('active');

            document.getElementById('active-contact-name').textContent = p.name;
            const userInfo = document.getElementById('chatbox-user-info');
            const messagesDiv = document.getElementById('chatbox-messages');

            const avatar = p.profile ?
                `<img src="../uploads/${p.profile}" style="width:50px;height:50px;border-radius:50%;object-fit:cover;">` :
                `<div class="chatbox-avatar-text">${p.initials}</div>`;
            const statusDot = p.isOnline === 'Online' ? `<div class="chatbox-dot online"></div>` : `<div class="chatbox-dot offline"></div>`;
            const statusText = `${p.isOnline} • Patient`;

            userInfo.innerHTML = `
            ${avatar}
            <div>
                <div class="chatbox-user-name">${p.name}</div>
                <div class="chatbox-user-status">${statusDot} ${statusText}</div>
            </div>
        `;

            if (p.messages && !forceReload) {
                renderCachedMessages(p);
            }

            messagesDiv.innerHTML = `<div class="text-center text-muted p-3">Loading messages...</div>`;
            try {
                const res = await fetch(`../Auth/Personnel/get_messages.php?patient_id=${p.patient_id}`);
                const messages = await res.json();
                p.messages = messages || [];
                messagesDiv.innerHTML = '';
                if (!p.messages.length) {
                    messagesDiv.innerHTML = `<div class="text-center text-muted p-3">No messages yet</div>`;
                } else {
                    p.messages.forEach(msg => appendMessage(msg, p, messagesDiv));
                    lastMessageId = Math.max(...p.messages.map(m => m.id || 0), lastMessageId);
                }
            } catch (err) {
                console.error(err);
                messagesDiv.innerHTML = `<div class="text-center text-danger p-3">Failed to load messages.</div>`;
            }
        }

        // ===== Show cached messages instantly =====
        function renderCachedMessages(p) {
            const messagesDiv = document.getElementById('chatbox-messages');
            messagesDiv.innerHTML = '';
            if (!p.messages.length) {
                messagesDiv.innerHTML = `<div class="text-center text-muted p-3">No messages yet</div>`;
                return;
            }
            p.messages.forEach(msg => appendMessage(msg, p, messagesDiv));
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        // ===== Append message =====
        function appendMessage(msg, contact, container) {
            if (msg.id && msg.id <= lastMessageId && container.querySelector(`[data-id="${msg.id}"]`)) return;

            const div = document.createElement('div');
            div.classList.add('chatbox-message');
            div.dataset.id = msg.id || '';
            if (msg.from === 'personnel') div.classList.add('sent');

            div.innerHTML = msg.from === 'user' ?
                `<div class="chatbox-msg-avatar">${contact.profile ? `<img src="../uploads/${contact.profile}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">` : contact.initials}</div>
                <div class="chatbox-msg-content">
                    <div class="chatbox-msg-text">${msg.text}</div>
                    <div class="chatbox-msg-time">${msg.time}</div>
                </div>` :
                `<div class="chatbox-msg-content">
                    <div class="chatbox-msg-text">${msg.text}</div>
                    <div class="chatbox-msg-time">${msg.time}</div>
                </div>
                <div class="chatbox-msg-avatar">You</div>`;

            container.appendChild(div);
            container.scrollTop = container.scrollHeight;

            if (msg.id) lastMessageId = Math.max(lastMessageId, msg.id);
        }

        // ===== Update last message in contact list =====
        function updateLastMessagePreview(contact, lastMsg) {
            const contactElements = document.querySelectorAll('.chatbox-contact');
            const contactElement = Array.from(contactElements).find(el => {
                const nameEl = el.querySelector('.chatbox-name');
                return nameEl && nameEl.textContent === contact.name;
            });
            if (contactElement) {
                const preview = contactElement.querySelector('.chatbox-lastmsg');
                if (preview) preview.innerHTML = lastMsg;
            }

            // 🔧 Move this contact to top of list (newest first)
            const list = document.getElementById('chatbox-contacts');
            if (contactElement) {
                list.prepend(contactElement);
            }
        }

        // ===== Fetch only new messages =====
        async function fetchNewMessages() {
            if (!contacts.length) return;
            const current = contacts[activeContactIndex];
            try {
                const res = await fetch(`../Auth/Personnel/get_messages.php?patient_id=${current.patient_id}&after_id=${lastMessageId}`);
                const newMsgs = await res.json();
                if (!newMsgs.length) return;

                const container = document.getElementById('chatbox-messages');
                newMsgs.forEach(msg => {
                    current.messages.push(msg);
                    appendMessage(msg, current, container);

                    const previewText = msg.from === 'personnel' ? `You: ${msg.text}` : msg.text;
                    updateLastMessagePreview(current, previewText);
                });
            } catch (err) {
                console.error(err);
            }
        }

        // ===== Send message =====
        document.querySelector('.chatbox-send').addEventListener('click', sendMessage);
        document.querySelector('.chatbox-input').addEventListener('keypress', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        async function sendMessage() {
            const textarea = document.querySelector('.chatbox-input');
            const text = textarea.value.trim();
            if (!text || !contacts.length) return;

            const current = contacts[activeContactIndex];
            textarea.value = '';

            try {
                const res = await fetch('../Auth/Personnel/send_message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: current.patient_id,
                        text
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    appendMessage(data.message, current, document.getElementById('chatbox-messages'));
                    if (!current.messages) current.messages = [];
                    current.messages.push(data.message);
                    updateLastMessagePreview(current, `You: ${text}`);
                } else {
                    alert(`Failed to send: ${data.message}`);
                }
            } catch (err) {
                console.error(err);
            }
        }

        // ===== Mark as read =====
        async function markAsRead(user_id) {
            try {
                await fetch(`../Doctor/mark_read.php?doctor_id=${doctor_id}&user_id=${user_id}`);
            } catch (err) {
                console.error(err);
            }
        }

        // ===== Initialize =====
        fetchContacts();
        setInterval(fetchNewMessages, 2000);
    </script>



    <script>
        const chatboxSidebar = document.querySelector('.chatbox-sidebar');
        const chatboxMain = document.querySelector('.chatbox-main');
        const backBtn = document.getElementById('chatbox-back-btn');

        // Only apply behavior for small screens (≤480px)
        function isMobileView() {
            return window.innerWidth <= 480;
        }

        // When a contact is clicked
        document.addEventListener('click', (e) => {
            if (e.target.closest('.chatbox-contact') && isMobileView()) {
                chatboxSidebar.style.display = 'none';
                chatboxMain.style.display = 'flex';
                backBtn.style.display = 'inline-block';
            }
        });

        // When Previous button is clicked
        backBtn.addEventListener('click', () => {
            if (isMobileView()) {
                chatboxMain.style.display = 'none';
                chatboxSidebar.style.display = 'block';
                backBtn.style.display = 'none';
            }
        });

        // Reset visibility if screen resizes
        window.addEventListener('resize', () => {
            if (!isMobileView()) {
                // Desktop/tablet view — show both sections
                chatboxSidebar.style.display = 'flex';
                chatboxMain.style.display = 'flex';
                backBtn.style.display = 'none';
            } else {
                // Mobile view — only show contacts by default
                chatboxSidebar.style.display = 'block';
                chatboxMain.style.display = 'none';
                backBtn.style.display = 'none';
            }
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