<style>
    /* This is for modified chatbot*/

    .chatbot-modal-body {
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
        background: #f5f5f5;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Bot bubble */
    .bot-message {
        max-width: 100%;
        background: #ffffff;
        border: 1px solid #ddd;
        padding: 10px 14px;
        border-radius: 18px;
        align-self: flex-start;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        line-height: 1.4;
        font-size: 14px;
    }

    /* Reply container (bot text + FAQ buttons wrapper) */
    .bot-message div {
        display: flex;
        flex-direction: column;
        gap: 8px;
        /* spacing between text and buttons */
        font-size: 14px;
        color: #333;
        line-height: 1.4;
    }


    /* Bot avatar */
    .bot-message img.bot-image {
        width: 32px;
        height: 32px;
        border-radius: 50%;
    }

    /* User bubble */
    .user-message {
        max-width: 75%;
        background: #007bff;
        color: #fff;
        padding: 10px 14px;
        border-radius: 18px;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
        font-size: 14px;
    }

    /* FAQ option buttons inside chat */
    .faq-options {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 6px;
    }

    .faq-option {
        background: #007bff;
        color: white;
        border: none;
        border-radius: 18px;
        padding: 6px 12px;
        cursor: pointer;
        font-size: 13px;
        transition: background 0.3s;
    }

    .faq-option:hover {
        background: #0056b3;
    }

    /* Default Sidebar (Desktop) */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 300px;
        background: #fff;
        transition: all 0.3s ease;
        overflow-y: auto;
        z-index: 1050;
        border-right: 1px solid #ddd;
    }

    /* Style for each nav-item */
    .sidebar .nav-item {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-radius: 10px;
    }

    /* Hover effect */
    .sidebar .nav-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
        /* subtle lift */
    }

    /* Nav links inside */
    .sidebar .nav-item .nav-link {
        color: #ddd;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }




    /* Collapsed sidebar */
    .sidebar.collapsed {
        width: 90px;
    }

    /* Hide text inside when collapsed */
    .sidebar.collapsed .brand-text,
    .sidebar.collapsed .nav-link span {
        display: none;
    }

    /* Brand image default */
    .sidebar .brand-marks img {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    /* Brand image shrink on collapse */
    .sidebar.collapsed .brand-marks img {
        width: 30px;
        height: 30px;
        border-radius: 8px;
    }



    /* ========================= */
    /* BOTCONTAINER              */
    /* ========================= */
    .sidebar .bot-container {
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .sidebar .bot-container .bot-title {
        font-size: 14px;
        font-weight: bold;
    }

    .sidebar .bot-container .bot-subtitle {
        font-size: 12px;
        color: #666;
    }

    /* Collapsed botcontainer */
    .sidebar.collapsed .bot-container {
        padding: 6px;
        text-align: center;
        font-size: 12px;
        width: 50px;
    }

    /* Hide bot text when collapsed */
    .sidebar.collapsed .bot-container .bot-title,
    .sidebar.collapsed .bot-container .bot-subtitle,
    .sidebar.collapsed .bot-container .chatbot-text {
        display: none;
    }

    /* Keep only icons */
    .sidebar.collapsed .bot-container .chatbot-icons {
        display: flex;
        justify-content: center;
    }


    /* Overlay (dark background behind sidebar) */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1500;
    }

    /* Show overlay only when sidebar is open */
    .sidebar.open~.sidebar-overlay {
        display: block;
    }

    /* Close button inside sidebar */
    .sidebar .close-btn {
        background: none;
        border: none;
        font-size: 2rem;
        color: #333;
        position: absolute;
        top: 15px;
        right: 15px;
        cursor: pointer;
        z-index: 2001;
    }

    /* Hide close button by default */
    .sidebar .close-btn {
        display: none;
    }

    /* Show close button only on tablets & phones (≤768px) */
    @media (max-width: 768px) {
        .sidebar .close-btn {
            display: block;
        }
    }

    /* Extra small phones (≤480px) */
    @media (max-width: 480px) {
        .sidebar .close-btn {
            display: block;
            font-size: 1.8rem;
            /* slightly smaller for tiny screens */
            top: 25px;
            right: 5px;
        }
    }



    /* ====== MOBILE VIEW ====== */
    @media (max-width: 768px) {
        .sidebar {
            width: 220px;
            /* 📌 smaller width on phones */
            transform: translateX(-100%);
        }

        .sidebar.open {
            transform: translateX(0);
        }

        /* Page content full width */
        .page-content {
            margin-left: 0 !important;
        }


    }




    /* ======================== */
    /* Responsive Media Queries */
    /* ======================== */

    /* Tablets (≤ 992px) */
    @media (max-width: 992px) {
        .chatbot-modal {
            width: 300px;
            height: 450px;
            bottom: 60px;
            right: 20px;
            top: 250px;
        }
    }

    /* Phones (≤ 576px) */
    @media (max-width: 576px) {
        .chatbot-modal {
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            border-radius: 0;
            /* full screen */
        }

        .chatbot-modal-header {
            padding: 14px;
            font-size: 1.1rem;
        }

        .chatbot-modal-body {
            padding: 12px;
            font-size: 0.95rem;
        }
    }


    /* ============================= */
    /* Extra Small Phones (≤ 480px)  */
    /* ============================= */
    @media (max-width: 480px) {

        /* Sidebar smaller and full-height */
        .sidebar {
            width: 200px;
            /* smaller width */
            transform: translateX(-100%);
        }

        .sidebar.open {
            transform: translateX(0);
        }

        /* Reduce brand text size */
        .sidebar .brand-text h1 {
            font-size: 13px;
            line-height: 1.3;
        }

        .sidebar .brand-marks img {
            width: 32px;
            height: 32px;
            border-radius: 8px;
        }

        /* Nav links smaller */
        .sidebar .nav-item .nav-link {
            font-size: 13px;
            padding: 8px 10px;
        }

        /* Bot container compact */
        .sidebar .bot-container {
            padding: 8px;
            font-size: 12px;
        }

        .sidebar .bot-container .bot-title {
            font-size: 12px;
        }

        .sidebar .bot-container .bot-subtitle {
            font-size: 11px;
        }

        .sidebar .bot-container .chatbot-icons {
            gap: 4px;
            font-size: 14px;
        }

        /* Chatbot modal full screen on very small phones */
        .chatbot-modal {
            width: 100% !important;
            height: 100% !important;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 0;
        }

        .chatbot-modal-header {
            font-size: 1rem;
            padding: 12px;
        }

        .chatbot-modal-body {
            font-size: 0.9rem;
            padding: 10px;
        }
    }



    /* Chatbot mobile view */
    /* 📱 Make chatbot same size as nav links */
    @media (max-width: 768px) {
        .sidebar .bot-container {
            padding: 8px 10px;
            /* same as nav link */
            font-size: 13px;
            /* same text size */
            width: 100%;
            /* flexible full width inside sidebar */
            box-sizing: border-box;
        }

        .sidebar .bot-container .bot-title,
        .sidebar .bot-container .bot-subtitle,
        .sidebar .bot-container .chatbot-icons,
        .sidebar .bot-container .chatbot-text {
            font-size: 13px;
            /* match nav link */
        }
    }

    @media (max-width: 480px) {
        .sidebar .bot-container {
            padding: 8px 10px;
            /* keep same as nav link */
            font-size: 13px;
            width: 100%;
            /* flexible full width */
            box-sizing: border-box;
        }

        .sidebar .bot-container .bot-title,
        .sidebar .bot-container .bot-subtitle,
        .sidebar .bot-container .chatbot-icons,
        .sidebar .bot-container .chatbot-text {
            font-size: 13px;
        }
    }











    /* Media query for chabot modal */
    /* ✅ Large screens (≥900px) */
    @media (min-width: 900px) {
        #chatbotModal {
            width: 320px;
            /* Bigger modal */
            right: 30px;
            /* Position with more spacing */
            bottom: 70px;
        }

        .chatbot-modal-body {
            max-height: 500px;
            /* Allow more messages */
            font-size: 15px;
            padding: 15px;
        }

        .bot-message,
        .user-message {
            font-size: 15px;
        }
    }

    /* ✅ Tablets and small laptops (≤720px) */
    @media (max-width: 720px) {
        #chatbotModal {
            width: 320px;
            /* Slightly smaller modal */
            right: 15px;
            bottom: 20px;
        }

        .chatbot-modal-body {
            max-height: 420px;
            font-size: 14px;
            padding: 12px;
        }

        .bot-message,
        .user-message {
            font-size: 14px;
        }
    }

    /* ✅ Small phones (≤480px) */
    @media (max-width: 480px) {
        #chatbotModal {
            width: 80% !important;
            height: 60% !important;
            right: 10% !important;
            bottom: 150px !important;
            border-radius: 12px !important;
            left: auto !important;
            top: auto !important;
        }

        .chatbot-modal-body {
            max-height: calc(100% - 60px);
            font-size: 13px;
            padding: 10px;
        }

        .bot-message,
        .user-message {
            font-size: 13px;
        }

        .faq-option {
            font-size: 12px;
            padding: 5px 10px;
        }
    }
</style>


<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside id="sidebar" class="sidebar">
    <!-- Close button -->
    <button class="close-btn" id="closeSidebar">&times;</button>

    <div class="sidebar-header d-flex align-items-center mb-4 mt-2" style="position: relative;">
        <!-- Logo -->
        <div class="brand-marks">
            <img src="../Assets/img/2.png" alt="Logo">
        </div>
        <div class="brand-text">
            <h1 style="font-size: 15px;">FAMILY DIAGNOSTIC
                <br>
                <span>CENTER</span>
            </h1>
        </div>


    </div>
    <hr>
    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'dashboard.patient.php') ? 'active' : 'collapsed'; ?>" href="dashboard.patient.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'view_messages.php') ? 'active' : 'collapsed'; ?>" href="view_messages.php">
                <i class="bi bi-chat-left-dots"></i><span>Chat Messages</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'appointment_schedule.php') ? 'active' : 'collapsed'; ?>" href="appointment_schedule.php">
                <i class="fi fi-rr-calendar"></i><span>Appointment Schedule</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage == 'recent_transaction.php') ? 'active' : 'collapsed'; ?>" href="recent_transaction.php">
                <i class="bi bi-file-medical"></i><span>My History Records</span>
            </a>
        </li>

        <li class="nav-item2">
            <div class="page-content" id="pageContent">
                <div class="d-flex justify-content-end chatbot-wrapper" id="chatbotWrapper">
                    <div class="chatcontainer position-relative">
                        <a href="#" id="dropdownchatbot" class="chatbot-link">
                            <div class="bot-container">
                                <p class="bot-title">Need Help?</p>
                                <span class="bot-subtitle">Health Bot can help you.</span>
                                <div class="chatbot-icons">
                                    <i class="fi fi-tr-chatbot-speech-bubble"></i>
                                    <span class="chatbot-text">Click Me!</span>
                                    <i class="fi fi-tr-chatbot-speech-bubble"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</aside>


<!-- Modal-style popup -->
<div class="chatbot-modal" id="chatbotModal">
    <div class="chatbot-modal-header">
        <a class="logo" style="text-decoration: none;"><span class="fs-5" style="color: white;">Chatbot</span></a>
        <button id="closeModal">&times;</button>
    </div>
    <div class="chatbot-modal-body" id="chatMessages"></div>
</div>


<!--Chatbot Script-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatbotBtn = document.getElementById('dropdownchatbot');
        const modal = document.getElementById('chatbotModal');
        const closeModalBtn = document.getElementById('closeModal');
        const chatMessages = document.getElementById('chatMessages');
        let faqs = []; // will be filled dynamically

        // Utility: create message bubble
        function createMessage(type, content) {
            const msg = document.createElement('div');
            msg.classList.add(type === 'bot' ? 'bot-message' : 'user-message');

            if (type === 'bot') {
                const botImage = document.createElement('img');
                botImage.src = '../Assets/img/chatbot/chatbot.png';
                botImage.classList.add('bot-image');
                msg.appendChild(botImage);
            }

            const text = document.createElement('span');
            text.innerHTML = content;
            msg.appendChild(text);

            chatMessages.appendChild(msg);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Load FAQs from database
        function loadFAQs(callback) {
            fetch("../Auth/User/fetch_faqs.php")
                .then(res => res.json())
                .then(data => {
                    faqs = data;
                    if (callback) callback();
                })
                .catch(() => {
                    createMessage('bot', '⚠️ Failed to load FAQs.');
                });
        }

        // Show initial welcome with FAQ buttons
        function showWelcomeMessage() {
            const botReply = document.createElement('div');
            botReply.classList.add('bot-message');

            const botImage = document.createElement('img');
            botImage.src = '../Assets/img/chatbot/chatbot.png';
            botImage.classList.add('bot-image');

            const replyContainer = document.createElement('div');
            replyContainer.innerHTML = "Hello! How can I help you? Please choose an option below:";

            const optionsDiv = document.createElement('div');
            optionsDiv.classList.add('faq-options');

            faqs.forEach(faq => {
                const btn = document.createElement('button');
                btn.classList.add('faq-option');
                btn.textContent = faq.question;
                btn.addEventListener('click', () => handleFAQClick(faq));
                optionsDiv.appendChild(btn);
            });

            replyContainer.appendChild(optionsDiv);
            botReply.appendChild(botImage);
            botReply.appendChild(replyContainer);

            chatMessages.appendChild(botReply);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Handle FAQ click
        function handleFAQClick(faq) {
            // Show user bubble
            createMessage('user', faq.question);

            // Show bot answer (no need to fetch again, we already have answer in faqs)
            createMessage('bot', faq.answer);

            // Show FAQs again
            showFAQOptions();
        }

        // Show FAQ options again
        function showFAQOptions() {
            const botReply = document.createElement('div');
            botReply.classList.add('bot-message');

            const botImage = document.createElement('img');
            botImage.src = '../Assets/img/chatbot/chatbot.png';
            botImage.classList.add('bot-image');

            const replyContainer = document.createElement('div');
            replyContainer.innerHTML = "Would you like to know something else?";

            const optionsDiv = document.createElement('div');
            optionsDiv.classList.add('faq-options');

            faqs.forEach(faq => {
                const btn = document.createElement('button');
                btn.classList.add('faq-option');
                btn.textContent = faq.question;
                btn.addEventListener('click', () => handleFAQClick(faq));
                optionsDiv.appendChild(btn);
            });

            replyContainer.appendChild(optionsDiv);
            botReply.appendChild(botImage);
            botReply.appendChild(replyContainer);

            chatMessages.appendChild(botReply);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Show/Hide Chatbot Modal
        chatbotBtn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.toggle('show');

            if (modal.classList.contains('show') && chatMessages.children.length === 0) {
                loadFAQs(showWelcomeMessage);
            }
        });

        closeModalBtn.addEventListener('click', function() {
            modal.classList.remove('show');
        });

        document.addEventListener('click', function(e) {
            if (!modal.contains(e.target) && !chatbotBtn.contains(e.target)) {
                modal.classList.remove('show');
            }
        });
    });
</script>