
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard - Opiña Law Office</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 0;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
        }

        .sidebar-header img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid rgba(255,255,255,0.2);
        }

        .sidebar-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            margin: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-menu li {
            margin: 5px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: #f1c40f;
            transform: translateX(5px);
        }

        .sidebar-menu i {
            width: 20px;
            margin-right: 15px;
            font-size: 18px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }

        .dashboard-header {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-section h1 {
            font-size: 32px;
            font-weight: 700;
            color: #27ae60;
            margin-bottom: 10px;
        }

        .welcome-section p {
            color: #666;
            font-size: 16px;
        }

        .client-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .client-profile img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid #f1c40f;
        }

        .client-info h3 {
            color: #27ae60;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .client-info p {
            color: #666;
            font-size: 14px;
        }

        /* Statistics Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #27ae60, #2ecc71, #f1c40f, #e67e22);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-icon.cases { background: linear-gradient(135deg, #27ae60, #2ecc71); }
        .stat-icon.documents { background: linear-gradient(135deg, #f1c40f, #f39c12); }
        .stat-icon.hearings { background: linear-gradient(135deg, #e67e22, #d35400); }
        .stat-icon.messages { background: linear-gradient(135deg, #3498db, #2980b9); }

        .stat-content h3 {
            font-size: 36px;
            font-weight: 700;
            color: #27ae60;
            margin-bottom: 5px;
        }

        .stat-content p {
            color: #666;
            font-size: 16px;
            margin: 0;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
        }

        .trend-indicator {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .trend-up {
            background: rgba(39, 174, 96, 0.1);
            color: #27ae60;
        }

        .trend-down {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .chart-header h3 {
            font-size: 20px;
            font-weight: 600;
            color: #27ae60;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        /* Recent Activities */
        .activities-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 15px;
            font-size: 16px;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #27ae60;
            margin-bottom: 5px;
        }

        .activity-details {
            color: #666;
            font-size: 14px;
        }

        .activity-time {
            color: #999;
            font-size: 12px;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .action-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin: 0 auto 15px;
        }

        .action-card h4 {
            color: #27ae60;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .action-card p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .dashboard-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
        }

        /* Additional Stats Row */
        .additional-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .mini-stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .mini-stat-card:hover {
            transform: translateY(-3px);
        }

        .mini-stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            margin: 0 auto 10px;
        }

        .mini-stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #27ae60;
            margin-bottom: 5px;
        }

        .mini-stat-label {
            color: #666;
            font-size: 14px;
        }

        /* Message Styles */
        .message-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .message-sender {
            font-weight: 600;
            color: #27ae60;
        }

        .message-time {
            font-size: 12px;
            color: #999;
        }

        .message-content {
            color: #666;
            font-size: 14px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="images/logo.jpg" alt="Logo">
            <h2>Opiña Law Office</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="client_dashboard.php" class="active"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="client_cases.php"><i class="fas fa-gavel"></i><span>My Cases</span></a></li>
            <li><a href="client_documents.php"><i class="fas fa-file-alt"></i><span>Documents</span></a></li>
            <li><a href="client_schedule.php"><i class="fas fa-calendar-alt"></i><span>Schedule</span></a></li>
            <li><a href="client_messages.php"><i class="fas fa-envelope"></i><span>Messages</span></a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
        </ul>
    </div>
    <div class="main-content">
        
<!-- Enhanced Profile Header with Notifications -->
<div class="header">
    <div class="header-title">
        <h1>Dashboard</h1>
        <p>Overview of your case activities and statistics</p>
    </div>
    <div class="user-info" style="display: flex; align-items: center; gap: 20px;">
        <!-- Notifications Bell -->
        <div class="notifications-container" style="position: relative;">
            <button id="notificationsBtn" style="background: none; border: none; font-size: 20px; color: var(--primary-color); cursor: pointer; padding: 8px; transition: color 0.2s;" onmouseover="this.style.color='var(--accent-color)'" onmouseout="this.style.color='var(--primary-color)'">
                <i class="fas fa-bell"></i>
                <span id="notificationBadge" style="position: absolute; top: 0; right: 0; background: var(--primary-color); color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; display: flex; align-items: center; justify-content: center; display: none;">0</span>
            </button>
            
            <!-- Notifications Dropdown -->
            <div id="notificationsDropdown" style="position: absolute; top: 100%; right: 0; background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); width: 350px; max-height: 400px; overflow-y: auto; z-index: 1000; display: none;">
                <div style="padding: 16px; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="margin: 0; font-size: 16px; color: #374151;">Notifications</h3>
                </div>
                <div id="notificationsList" style="padding: 8px;">
                    <!-- Notifications will be loaded here -->
                </div>
                <div style="padding: 12px; border-top: 1px solid #e5e7eb; text-align: center;">
                    <button onclick="markAllAsRead()" style="background: #1976d2; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-size: 12px;">Mark All as Read</button>
                </div>
            </div>
        </div>
        
        <!-- Profile Image with Dropdown -->
        <div class="profile-dropdown" style="display: flex; align-items: center; gap: 12px;">
            <img src="assets/images/client-avatar.png" alt="Client" style="object-fit: cover; width: 42px; height: 42px; border-radius: 50%; border: 2px solid var(--primary-color); cursor: pointer;" onclick="toggleProfileDropdown()">
            
            <div class="user-details">
                <h3 style="margin: 0; font-size: 16px; color: var(--primary-color);">Yuhan, Nerfy Sheesh</h3>
                <p style="margin: 0; font-size: 14px; color: var(--accent-color);">Client</p>
            </div>
            
            <!-- Profile Dropdown Menu -->
            <div class="profile-dropdown-content" id="profileDropdown">
                <a href="#" onclick="editProfile()">
                    <i class="fas fa-user-edit"></i>
                    Edit Profile
                </a>
                <a href="logout.php" class="logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
        
        <!-- Edit Profile Modal -->
        <div id="editProfileModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Edit Profile</h2>
                    <span class="close" onclick="closeEditProfileModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="profile-edit-container">
                        <form method="POST" enctype="multipart/form-data" class="profile-form" id="editProfileForm">
                            <div class="form-section">
                                <h3>Profile Picture</h3>
                                <div class="profile-image-section">
                                    <img src="assets/images/client-avatar.png" alt="Current Profile" id="currentProfileImage" class="current-profile-image">
                                    <div class="image-upload">
                                        <label for="profile_image" class="upload-btn">
                                            <i class="fas fa-camera"></i>
                                            Change Photo
                                        </label>
                                        <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display: none;" onchange="previewImage(this)">
                                        <p class="upload-hint">JPG, PNG, or GIF. Max 5MB.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h3>Personal Information</h3>
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" id="name" name="name" value="Yuhan, Nerfy Sheesh" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" value="yuhanerfy@gmail.com" readonly style="background-color: #f5f5f5; cursor: not-allowed;">
                                    <small style="color: #666; font-size: 12px;">Email address cannot be changed for security reasons.</small>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" onclick="closeEditProfileModal()">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }
        
        function editProfile() {
            document.getElementById('editProfileModal').style.display = 'block';
            // Close dropdown when opening modal
            document.getElementById('profileDropdown').classList.remove('show');
        }

        function closeEditProfileModal() {
            document.getElementById('editProfileModal').style.display = 'none';
        }
        
        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('img') && !event.target.closest('.profile-dropdown')) {
                const dropdowns = document.getElementsByClassName('profile-dropdown-content');
                for (let dropdown of dropdowns) {
                    if (dropdown.classList.contains('show')) {
                        dropdown.classList.remove('show');
                    }
                }
            }
            
            // Close modal when clicking outside
            if (event.target.classList.contains('modal')) {
                closeEditProfileModal();
            }
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('currentProfileImage').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Handle form submission
        document.getElementById('editProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully!');
                    closeEditProfileModal();
                    // Refresh the page to show updated profile
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the profile.');
            });
        });
        </script>
    </div>
</div>

<script>
// Notifications functionality
let notificationsVisible = false;

document.getElementById('notificationsBtn').addEventListener('click', function() {
    const dropdown = document.getElementById('notificationsDropdown');
    notificationsVisible = !notificationsVisible;
    dropdown.style.display = notificationsVisible ? 'block' : 'none';
    
    if (notificationsVisible) {
        loadNotifications();
    }
});

// Close notifications when clicking outside
document.addEventListener('click', function(event) {
    const container = document.querySelector('.notifications-container');
    if (!container.contains(event.target)) {
        document.getElementById('notificationsDropdown').style.display = 'none';
        notificationsVisible = false;
    }
});

function loadNotifications() {
    fetch('get_notifications.php')
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.unread_count);
            displayNotifications(data.notifications);
        })
        .catch(error => console.error('Error loading notifications:', error));
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationBadge');
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
}

function displayNotifications(notifications) {
    const container = document.getElementById('notificationsList');
    
    if (notifications.length === 0) {
        container.innerHTML = '<div style="padding: 20px; text-align: center; color: #6b7280;">No notifications</div>';
        return;
    }
    
    container.innerHTML = notifications.map(notification => `
        <div style="padding: 12px; border-bottom: 1px solid #f3f4f6; ${!notification.is_read ? 'background: #f0f8ff;' : ''}">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="flex: 1;">
                    <div style="font-weight: 600; font-size: 14px; color: #1a202c; margin-bottom: 4px;">${notification.title}</div>
                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">${notification.message}</div>
                    <div style="font-size: 11px; color: #9ca3af;">${formatTime(notification.created_at)}</div>
                </div>
                <div style="width: 8px; height: 8px; border-radius: 50%; background: ${getNotificationColor(notification.type)}; margin-left: 8px; ${notification.is_read ? 'display: none;' : ''}"></div>
            </div>
        </div>
    `).join('');
}

function getNotificationColor(type) {
    switch (type) {
        case 'success': return '#10b981';
        case 'warning': return '#f59e0b';
        case 'error': return '#ef4444';
        default: return '#3b82f6';
    }
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) return 'Just now';
    if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
    if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
    return date.toLocaleDateString();
}

function markAllAsRead() {
    fetch('get_notifications.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'mark_read=true'
    })
    .then(() => {
        loadNotifications();
    })
    .catch(error => console.error('Error marking notifications as read:', error));
}

// Load notifications on page load
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    // Refresh notifications every 30 seconds
    setInterval(loadNotifications, 30000);
});
</script> 
            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="action-card" onclick="window.location.href='client_cases.php'">
                    <div class="action-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h4>View Cases</h4>
                    <p>Check your case status and progress</p>
                </div>
                <div class="action-card" onclick="window.location.href='client_documents.php'">
                    <div class="action-icon">
                        <i class="fas fa-file-upload"></i>
                    </div>
                    <h4>Upload Documents</h4>
                    <p>Upload important documents and files</p>
                </div>
                <div class="action-card" onclick="window.location.href='client_schedule.php'">
                    <div class="action-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h4>View Schedule</h4>
                    <p>Check upcoming hearings and meetings</p>
                </div>
                <div class="action-card" onclick="window.location.href='client_messages.php'">
                    <div class="action-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4>Send Message</h4>
                    <p>Communicate with your attorney</p>
                </div>
            </div>

            <!-- Statistics Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon cases">
                            <i class="fas fa-gavel"></i>
                        </div>
                        <div class="stat-content">
                            <h3>1</h3>
                            <p>Total Cases</p>
                        </div>
                    </div>
                    <div class="stat-trend">
                        <span class="trend-indicator trend-up">Active</span>
                        <span>cases in progress</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon documents">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-content">
                            <h3>0</h3>
                            <p>Total Documents</p>
                        </div>
                    </div>
                    <div class="stat-trend">
                        <span class="trend-indicator trend-up">+0</span>
                        <span>uploaded today</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon hearings">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-content">
                            <h3>0</h3>
                            <p>Upcoming Hearings</p>
                        </div>
                    </div>
                    <div class="stat-trend">
                        <span class="trend-indicator trend-up">Next 7 days</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon messages">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stat-content">
                            <h3>3</h3>
                            <p>Recent Messages</p>
                        </div>
                    </div>
                    <div class="stat-trend">
                        <span class="trend-indicator trend-up">Latest</span>
                        <span>communication</span>
                    </div>
                </div>
            </div>

            <!-- Charts and Activities Section -->
            <div class="charts-section">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Case Status Distribution</h3>
                    </div>
                    <div class="chart-container">
                        <canvas id="caseChart"></canvas>
                    </div>
                </div>

                <div class="activities-card">
                    <div class="chart-header">
                        <h3>Recent Activities</h3>
                    </div>
                                                                        <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-folder"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">dasdasd</div>
                                    <div class="activity-details">Attorney: Laica Castillo Refrea</div>
                                </div>
                                <div class="activity-time">
                                    Aug 13                                </div>
                            </div>
                                                
                                                    <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-comment"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Recent Message</div>
                                    <div class="activity-details">dwa...</div>
                                </div>
                                <div class="activity-time">
                                    Aug 14                                </div>
                            </div>
                                                            </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="additional-stats">
                <div class="mini-stat-card">
                    <div class="mini-stat-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="mini-stat-value">1</div>
                    <div class="mini-stat-label">Active Cases</div>
                </div>

                <div class="mini-stat-card">
                    <div class="mini-stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="mini-stat-value">0</div>
                    <div class="mini-stat-label">Pending Cases</div>
                </div>

                <div class="mini-stat-card">
                    <div class="mini-stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="mini-stat-value">0</div>
                    <div class="mini-stat-label">Completed Cases</div>
                </div>

                <div class="mini-stat-card">
                    <div class="mini-stat-icon">
                        <i class="fas fa-upload"></i>
                    </div>
                    <div class="mini-stat-value">0</div>
                    <div class="mini-stat-label">Today's Uploads</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Case Status Chart
        const caseCtx = document.getElementById('caseChart').getContext('2d');
        new Chart(caseCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active Cases', 'Pending Cases', 'Completed Cases'],
        datasets: [{
                    data: [1, 0, 0],
            backgroundColor: [
                        '#27ae60',
                        '#f1c40f',
                        '#3498db'
            ],
                    borderWidth: 0,
                    hoverOffset: 4
        }]
            },
        options: {
            responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });

        // Add smooth scrolling and animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stats on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all stat cards
            document.querySelectorAll('.stat-card, .mini-stat-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
    });
    </script>
</body>
</html> 