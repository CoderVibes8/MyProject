<style>
    .notif-note {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
    }

    .notif-note.unread {
        background-color: #f0f8ff;

    }

    .notif-note .status {
        color: #0369a1;
        font-size: 0.7em;
    }

    .notif_badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: red;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 0.75rem;
        font-weight: bold;
        display: none;
    }
</style>
<section class="welcome">
    <div>
        <h2>Welcome back, <?php echo htmlspecialchars($userProfile['first_name']) . ' ' . htmlspecialchars($userProfile['last_name']); ?></h2>
        <p>Here’s what’s next with your care at <strong>Family Diagnostic Center</strong>.</p>
    </div>
    <div class="grow"></div>
    <div class="actions">
        <!-- <button class="welcome-btn alt" type="button" id="openSchedule">
            Today Schedule
            <br>
            <span>Tap to view.</span>
        </button> -->
        <button class="welcome-btn " type="button" id="reqAppt">
            Notifications &nbsp;<i class="fi fi-rs-bell-notification-social-media"></i><span id="notifCount" class="badge">0</span>
            <br>
            <span style="color: #f0f8ff;">Tap to view.</span>
        </button>
    </div>
</section>

<!-- Notification Modal -->
<div class="notification-modal" id="notification-modal" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="mTitle">
        <div class="notif-head">
            <div class="title" id="mTitle">Notifications</div>
            <button class="close" id="close">Close</button>
        </div>

        <div class="notif-content" aria-live="polite"></div>

        <div class="notif-actions">
            <!-- <button class="secondary" id="dismiss">Dismiss all</button> -->
            <button class="primary" id="viewAll">Mark All As Read</button>
        </div>
    </div>
</div>



<!-- Notif Script -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const openBtn = document.getElementById("reqAppt");
        const modal = document.getElementById("notification-modal");
        const closeBtn = document.getElementById("close");
        const viewAllBtn = document.getElementById("viewAll");

        const notifContainer = document.querySelector(".notif-content");
        const notifCount = document.getElementById("notifCount");

        let unreadCount = 0;
        let notifications = [];
        let lastDataHash = ""; // prevent unnecessary UI reload

        // ===== Modal Events =====
        openBtn.addEventListener("click", () => modal.setAttribute("aria-hidden", "false"));
        closeBtn.addEventListener("click", () => modal.setAttribute("aria-hidden", "true"));
        modal.addEventListener("click", (e) => {
            if (e.target === modal) modal.setAttribute("aria-hidden", "true");
        });
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") modal.setAttribute("aria-hidden", "true");
        });

        // ===== Fetch Notifications =====
        function fetchNotifications() {
            fetch("../Auth/User/fetch_notifications.php")
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;

                    // Generate hash to detect data changes
                    const dataHash = JSON.stringify(data.data);
                    if (dataHash === lastDataHash) return; // prevent visual flicker
                    lastDataHash = dataHash;

                    notifications = data.data || [];
                    unreadCount = data.unread || 0;
                    notifContainer.innerHTML = "";

                    if (notifications.length > 0) {
                        notifications.forEach(notif => {
                            const notifItem = document.createElement("div");
                            notifItem.classList.add("notif-note");
                            if (notif.isRead == 0) notifItem.classList.add("unread");

                            notifItem.innerHTML = `
                            <div class="msg">
                                ${notif.description}
                                <div class="time">${new Date(notif.created_at).toLocaleString()}</div>
                            </div>
                            <span class="status">${notif.isRead == 0 ? "New" : ""}</span>
                        `;

                            notifItem.addEventListener("click", () => {
                                if (notif.isRead == 0) {
                                    notif.isRead = 1;
                                    notifItem.classList.remove("unread");
                                    notifItem.querySelector(".status").textContent = "";
                                    unreadCount = Math.max(0, unreadCount - 1);
                                    updateBadge();

                                    fetch("../Auth/User/mark_read.php", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json"
                                        },
                                        body: JSON.stringify({
                                            id: notif.id
                                        })
                                    }).catch(err => console.error(err));
                                }

                                if (notif.link) window.location.href = notif.link;
                            });

                            notifContainer.appendChild(notifItem);
                        });
                    } else {
                        notifContainer.innerHTML = "<p>No notifications found!</p>";
                    }

                    updateBadge();
                })
                .catch(err => console.error(err));
        }

        // ===== Update Badge =====
        function updateBadge() {
            notifCount.style.display = "inline-block"; // always show badge
            notifCount.textContent = unreadCount > 9 ? "9+" : unreadCount;
        }

        // ===== Mark All As Read =====
        viewAllBtn.addEventListener("click", () => {
            const unreadItems = notifContainer.querySelectorAll(".notif-note.unread");
            if (unreadItems.length === 0) return;

            unreadItems.forEach(item => {
                item.classList.remove("unread");
                const status = item.querySelector(".status");
                if (status) status.textContent = "";
            });

            unreadCount = 0;
            updateBadge();

            // Call backend
            fetch("../Auth/User/Mark_All_Read.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Update local data
                        notifications.forEach(n => (n.isRead = 1));
                        lastDataHash = ""; // force refresh next poll to show updated data
                    } else {
                        console.error("Failed to mark all as read");
                    }
                })
                .catch(err => console.error(err));
        });

        // ===== Initial + Polling =====
        fetchNotifications();
        setInterval(fetchNotifications, 2000);
    });
</script>