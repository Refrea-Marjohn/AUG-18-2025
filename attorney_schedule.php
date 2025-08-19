<?php
require_once 'session_manager.php';
validateUserAccess('attorney');
require_once 'config.php';
$attorney_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT profile_image FROM user_form WHERE id=?");
$stmt->bind_param("i", $attorney_id);
$stmt->execute();
$res = $stmt->get_result();
$profile_image = '';
if ($res && $row = $res->fetch_assoc()) {
    $profile_image = $row['profile_image'];
}
if (!$profile_image || !file_exists($profile_image)) {
    // Fallback to an existing bundled image to avoid 404
    $profile_image = 'images/default-avatar.jpg';
}

// Fetch all cases for dropdown
$cases = [];
$stmt = $conn->prepare("SELECT ac.id, ac.title, uf.name as client_name FROM attorney_cases ac LEFT JOIN user_form uf ON ac.client_id = uf.id WHERE ac.attorney_id=?");
$stmt->bind_param("i", $attorney_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $cases[] = $row;

// Handle add event
if (isset($_POST['action']) && $_POST['action'] === 'add_event') {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $case_id = !empty($_POST['case_id']) ? intval($_POST['case_id']) : null;
    $description = $_POST['description'];
    $client_id = null;
    if ($case_id) {
        $stmt = $conn->prepare("SELECT client_id FROM attorney_cases WHERE id=?");
        $stmt->bind_param("i", $case_id);
        $stmt->execute();
        $q = $stmt->get_result();
        if ($r = $q->fetch_assoc()) $client_id = $r['client_id'];
    }
    $stmt = $conn->prepare("INSERT INTO case_schedules (case_id, attorney_id, client_id, type, title, description, date, time, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('iiissssss', $case_id, $attorney_id, $client_id, $type, $title, $description, $date, $time, $location);
    $stmt->execute();
    echo $stmt->affected_rows > 0 ? 'success' : 'error';
    exit();
}
// Fetch all events for this attorney
$events = [];
$stmt = $conn->prepare("SELECT cs.*, ac.title as case_title, uf.name as client_name FROM case_schedules cs LEFT JOIN attorney_cases ac ON cs.case_id = ac.id LEFT JOIN user_form uf ON cs.client_id = uf.id WHERE cs.attorney_id=? ORDER BY cs.date, cs.time");
$stmt->bind_param("i", $attorney_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $events[] = $row;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Management - Opiña Law Office</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
</head>
<body>
     <!-- Sidebar -->
     <div class="sidebar">
                <div class="sidebar-header">
            <img src="images/logo.jpg" alt="Logo">
            <h2>Opiña Law Office</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="attorney_dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="attorney_cases.php" class="active"><i class="fas fa-gavel"></i><span>Manage Cases</span></a></li>
            <li><a href="attorney_documents.php"><i class="fas fa-file-alt"></i><span>Document Storage</span></a></li>
            <li><a href="attorney_document_generation.php"><i class="fas fa-file-alt"></i><span>Document Generation</span></a></li>
            <li><a href="attorney_schedule.php"><i class="fas fa-calendar-alt"></i><span>My Schedule</span></a></li>
            <li><a href="attorney_clients.php"><i class="fas fa-users"></i><span>My Clients</span></a></li>
            <li><a href="attorney_messages.php"><i class="fas fa-envelope"></i><span>Messages</span></a></li>
            <li><a href="attorney_efiling.php"><i class="fas fa-paper-plane"></i><span>E-Filing</span></a></li>
            <li><a href="attorney_audit.php"><i class="fas fa-history"></i><span>Audit Trail</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-title">
                <h1>Schedule Management</h1>
                <p>Manage your court hearings and appointments</p>
            </div>
            <div class="user-info">
                <img src="<?= htmlspecialchars($profile_image) ?>" alt="Attorney" style="object-fit:cover;width:60px;height:60px;border-radius:50%;border:2px solid #1976d2;">
                <div class="user-details">
                    <h3><?php echo $_SESSION['attorney_name']; ?></h3>
                    <p>Attorney at Law</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="btn btn-primary" id="addEventBtn">
                <i class="fas fa-plus"></i> Add Event
            </button>
            <button class="btn btn-secondary" id="viewDayBtn">
                <i class="fas fa-calendar-day"></i> Day View
            </button>
            <button class="btn btn-secondary" id="viewWeekBtn">
                <i class="fas fa-calendar-week"></i> Week View
            </button>
            <button class="btn btn-secondary" id="viewMonthBtn">
                <i class="fas fa-calendar"></i> Month View
            </button>

        </div>

        <!-- Calendar Container -->
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>

        <!-- Upcoming Events -->
        <div class="upcoming-events-section">
            <div class="section-header">
                <h2><i class="fas fa-calendar-check"></i> Upcoming Events</h2>
            </div>
            
            <?php if (empty($events)): ?>
            <div class="no-events">
                <i class="fas fa-calendar-times"></i>
                <h3>No Upcoming Events</h3>
                <p>You have no scheduled events at the moment.</p>
            </div>
            <?php else: ?>
            <div class="events-grid">
                <?php foreach ($events as $ev): ?>
                <div class="event-card status-<?= strtolower($ev['status']) ?>" data-event-id="<?= $ev['id'] ?>">
                    <div class="event-card-header">
                        <div class="event-avatar">
                            <i class="fas fa-<?= $ev['type'] == 'Hearing' ? 'gavel' : 'calendar-check' ?>"></i>
                        </div>
                        <div class="event-info">
                            <h3><?= htmlspecialchars($ev['type']) ?></h3>
                            <p class="case-detail">
                                <i class="fas fa-folder"></i>
                                <?= htmlspecialchars($ev['case_title'] ?? 'No Case') ?>
                            </p>
                            <p class="client-detail">
                                <i class="fas fa-user"></i>
                                <?= htmlspecialchars($ev['client_name'] ?? 'N/A') ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="event-stats">
                        <div class="stat-item date">
                            <div class="stat-number"><?= date('M j', strtotime($ev['date'])) ?></div>
                            <div class="stat-label">Date</div>
                        </div>
                        <div class="stat-item time">
                            <div class="stat-number"><?= date('h:i A', strtotime($ev['time'])) ?></div>
                            <div class="stat-label">Time</div>
                        </div>
                        <div class="stat-item location">
                            <div class="stat-number">Cabuyao</div>
                            <div class="stat-label">Location</div>
                        </div>
                    </div>
                    
                    <div class="event-actions">
                        <div class="status-management">
                            <select class="status-select" onchange="updateEventStatus(this)" data-previous-status="<?= htmlspecialchars(strtolower($ev['status'])) ?>">
                                <option value="scheduled" <?= strtolower($ev['status']) == 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                                <option value="completed" <?= strtolower($ev['status']) == 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="no-show" <?= strtolower($ev['status']) == 'no-show' ? 'selected' : '' ?>>No Show</option>
                                <option value="rescheduled" <?= strtolower($ev['status']) == 'rescheduled' ? 'selected' : '' ?>>Rescheduled</option>
                                <option value="cancelled" <?= strtolower($ev['status']) == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                        <button class="btn btn-sm btn-primary view-info-btn" 
                            data-type="<?= htmlspecialchars($ev['type']) ?>"
                            data-date="<?= htmlspecialchars($ev['date']) ?>"
                            data-time="<?= htmlspecialchars($ev['time']) ?>"
                            data-location="<?= htmlspecialchars($ev['location']) ?>"
                            data-case="<?= htmlspecialchars($ev['case_title'] ?? '-') ?>"
                            data-client="<?= htmlspecialchars($ev['client_name'] ?? '-') ?>"
                            data-description="<?= htmlspecialchars($ev['description'] ?? '-') ?>">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add Event Modal -->
    <div class="modal" id="addEventModal">
        <div class="modal-content add-event-modal">
            <div class="modal-header">
                <h2>Add New Event</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="eventForm" class="event-form-grid">
                    <div class="form-group">
                        <label for="eventTitle">Event Title</label>
                        <input type="text" id="eventTitle" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="eventDate">Date</label>
                        <input type="date" id="eventDate" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="eventTime">Time</label>
                        <input type="time" id="eventTime" name="time" required>
                    </div>
                    <div class="form-group">
                        <label for="eventLocation">Location</label>
                        <input type="text" id="eventLocation" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="eventCase">Related Case</label>
                        <select id="eventCase" name="case_id">
                            <option value="">Select Case</option>
                            <?php foreach ($cases as $c): ?>
                            <option value="<?= $c['id'] ?>">#<?= $c['id'] ?> - <?= htmlspecialchars($c['title']) ?> (<?= htmlspecialchars($c['client_name']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="eventType">Event Type</label>
                        <select id="eventType" name="type" required>
                            <option value="Hearing">Hearing</option>
                            <option value="Appointment">Appointment</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label for="eventDescription">Description</label>
                        <textarea id="eventDescription" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelEvent">Cancel</button>
                <button class="btn btn-primary" id="saveEvent">Save Event</button>
            </div>
        </div>
    </div>

    <!-- Event Details Modal -->
    <div class="modal" id="eventInfoModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="header-content">
                    <div class="header-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="header-text">
                        <h2>Event Details</h2>
                        <p>Complete event information and case details</p>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="event-overview">
                    <div class="event-type-display">
                        <span class="type-badge" id="modalEventType">Event</span>
                    </div>
                    <div class="event-datetime">
                        <div class="date-display" id="modalEventDate">Date</div>
                        <div class="time-display" id="modalEventTime">Time</div>
                    </div>
                </div>
                <div class="event-details-grid">
                    <div class="detail-section">
                        <h3><i class="fas fa-info-circle"></i> Event Information</h3>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-tag"></i> Type:</span>
                            <span class="detail-value" id="modalType">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-calendar"></i> Date:</span>
                            <span class="detail-value" id="modalDate">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-clock"></i> Time:</span>
                            <span class="detail-value" id="modalTime">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-map-marker-alt"></i> Location:</span>
                            <span class="detail-value" id="modalLocation">-</span>
                        </div>
                    </div>
                    <div class="detail-section">
                        <h3><i class="fas fa-folder-open"></i> Case Details</h3>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-gavel"></i> Case:</span>
                            <span class="detail-value" id="modalCase">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-user-tie"></i> Attorney:</span>
                            <span class="detail-value" id="modalAttorney">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-user"></i> Client:</span>
                            <span class="detail-value" id="modalClient">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-file-alt"></i> Description:</span>
                            <span class="detail-value" id="modalDescription">-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-close-modal" id="closeEventInfoModal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>

    <style>
        .calendar-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Enhanced Upcoming Events Styles */
        .upcoming-events-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-header h2 {
            color: #1976d2;
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-header h2 i {
            color: #9c27b0;
        }

        .no-events {
            text-align: center;
            padding: 3rem 2rem;
            color: #666;
        }

        .no-events i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        .no-events h3 {
            margin: 0 0 0.5rem 0;
            color: #999;
        }

        .no-events p {
            margin: 0;
            color: #bbb;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .event-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .event-card-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .event-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #1976d2, #1565c0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .event-info h3 {
            margin: 0 0 0.5rem 0;
            color: #333;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .event-info p {
            margin: 0 0 0.25rem 0;
            color: #666;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .case-detail {
            color: #1976d2 !important;
            font-weight: 500;
        }

        .client-detail {
            color: #43a047 !important;
            font-weight: 500;
        }

        .event-info i {
            font-size: 0.8rem;
            width: 16px;
            text-align: center;
        }

        .event-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
            height: 100px; /* Fixed height for consistency */
        }

        .stat-item {
            text-align: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .stat-item.date {
            background: rgba(76, 175, 80, 0.1);
            border-color: rgba(76, 175, 80, 0.2);
        }

        .stat-item.time {
            background: rgba(255, 193, 7, 0.1);
            border-color: rgba(255, 193, 7, 0.2);
        }

        .stat-item.location {
            background: rgba(156, 39, 176, 0.1);
            border-color: rgba(156, 39, 176, 0.2);
        }

        .stat-number {
            font-size: 1rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.2rem;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .event-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .status-management {
            flex: 1;
        }

        .status-select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            background: white;
            color: #333;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .status-select:focus {
            outline: none;
            border-color: #1976d2;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }

        .status-select option[value="scheduled"] {
            color: #1976d2;
            font-weight: 600;
        }

        .status-select option[value="completed"] {
            color: #2e7d32;
            font-weight: 600;
        }

        .status-select option[value="no-show"] {
            color: #d32f2f;
            font-weight: 600;
        }

        .status-select option[value="rescheduled"] {
            color: #f57c00;
            font-weight: 600;
        }

        .status-select option[value="cancelled"] {
            color: #6c757d;
            font-weight: 600;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        /* Status-based Card Styling */
        .event-card.status-completed {
            border-left: 4px solid #2e7d32;
        }

        .event-card.status-no-show {
            border-left: 4px solid #d32f2f;
        }

        .event-card.status-rescheduled {
            border-left: 4px solid #f57c00;
        }

        .event-card.status-cancelled {
            border-left: 4px solid #6c757d;
        }

        /* Professional Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
        }

        .modal-content {
            background: #fff;
            border-radius: 8px;
            padding: 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            width: 90%;
            margin: 2% auto;
            overflow: hidden;
            max-height: 90vh;
        }

        .modal-header {
            background: #1976d2;
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px 8px 0 0;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-icon {
            width: 35px;
            height: 35px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .header-text h2 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .header-text p {
            margin: 0.25rem 0 0 0;
            opacity: 0.9;
            font-size: 0.85rem;
        }

        .close-modal {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .close-modal:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .modal-body {
            padding: 1rem;
        }

        .event-overview {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #e0e0e0;
        }

        .event-type-display .type-badge {
            background: #9c27b0;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .event-datetime {
            display: flex;
            gap: 0.75rem;
        }

        .date-display, .time-display {
            background: white;
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
            text-align: center;
            min-width: 70px;
            border: 1px solid #e0e0e0;
            font-size: 0.9rem;
        }

        .date-display {
            color: #1976d2;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .time-display {
            color: #43a047;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .event-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .detail-section {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 1.2rem;
            border: 1px solid #e9ecef;
        }

        .detail-section h3 {
            color: #1976d2;
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 0.75rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e3f2fd;
        }

        .detail-section h3 i {
            color: #9c27b0;
            font-size: 1.2rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #555;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 100px;
            font-size: 0.85rem;
        }

        .detail-label i {
            color: #9c27b0;
            width: 18px;
            text-align: center;
            font-size: 1rem;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
            text-align: right;
            max-width: 250px;
            word-wrap: break-word;
            font-size: 0.85rem;
            padding-left: 0.5rem;
            line-height: 1.3;
        }

        .modal-footer {
            padding: 1rem;
            background: #f8f9fa;
            text-align: right;
            border-top: 1px solid #e0e0e0;
            border-radius: 0 0 8px 8px;
        }

        .btn-close-modal {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-close-modal:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

                    /* Add Event Modal Specific Styles */
            .add-event-modal {
                max-width: 700px !important;
                max-height: 90vh !important;
                margin: 1.5% auto !important;
                width: 95% !important;
            }

                    .add-event-modal .modal-body {
                padding: 0.8rem;
                overflow: visible;
            }

                    .add-event-modal .event-form-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.8rem;
            }

                    .add-event-modal .form-group {
                margin-bottom: 0.6rem;
            }

                    .add-event-modal .form-group.full-width {
                grid-column: 1 / -1;
            }
            
        .add-event-modal .form-group:nth-child(1) {
            grid-column: 1 / -1;
        }

        

        .add-event-modal .form-group:nth-child(1) label {
            color: #9c27b0;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .add-event-modal .form-group:nth-child(1) input {
            border-color: #9c27b0;
            background: linear-gradient(135deg, #fafbfc, #f3f4f6);
        }

        .add-event-modal .form-group:nth-child(1) input:focus {
            border-color: #9c27b0;
            box-shadow: 0 0 0 3px rgba(156, 39, 176, 0.1);
        }

                    .add-event-modal .form-group label {
                display: block;
                margin-bottom: 0.3rem;
                font-weight: 600;
                color: #1976d2;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.1px;
            }

                    .add-event-modal .form-group input,
            .add-event-modal .form-group select,
            .add-event-modal .form-group textarea {
                width: 100%;
                padding: 0.5rem;
                border: 1px solid #e0e0e0;
                border-radius: 5px;
                font-size: 0.8rem;
                background: #ffffff;
                transition: all 0.3s ease;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
            }

        .add-event-modal .form-group input:focus,
        .add-event-modal .form-group select:focus,
        .add-event-modal .form-group textarea:focus {
            outline: none;
            border-color: #1976d2;
            background: white;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
            transform: translateY(-1px);
        }

        .add-event-modal .form-group input:hover,
        .add-event-modal .form-group select:hover,
        .add-event-modal .form-group textarea:hover {
            border-color: #1976d2;
            background: white;
        }

                    .add-event-modal .modal-footer {
                padding: 0.6rem 0.8rem;
                background: #f8f9fa;
                border-top: 1px solid #e0e0e0;
                display: flex;
                justify-content: flex-end;
                gap: 0.6rem;
                border-radius: 0 0 8px 8px;
            }

                    .add-event-modal .btn {
                padding: 0.5rem 1rem;
                border: none;
                border-radius: 5px;
                font-weight: 600;
                font-size: 0.8rem;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                min-width: 80px;
            }

        .add-event-modal .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }

        .add-event-modal .btn-primary {
            background: linear-gradient(135deg, #1976d2, #1565c0);
            color: white;
        }

        .add-event-modal .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .add-event-modal .btn:active {
            transform: translateY(0);
        }

        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border-left: 4px solid;
            z-index: 10000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 300px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-success {
            border-left-color: #2e7d32;
            color: #2e7d32;
        }

        .notification-error {
            border-left-color: #d32f2f;
            color: #d32f2f;
        }

        .notification i {
            font-size: 1.2rem;
        }

        #calendar {
            height: 600px;
        }

        .fc-event {
            cursor: pointer;
        }

        .fc-event-title {
            font-weight: 500;
        }

        .fc-event-time {
            font-size: 0.8em;
        }
            color: #1976d2;
        }
        .event-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 24px;
        }
        .event-form-grid .form-group {
            margin-bottom: 0;
        }
        .event-form-grid .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-group label {
            font-size: 1rem;
            color: #555;
            margin-bottom: 4px;
            display: block;
            font-weight: 500;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            background: #fafbfc;
            margin-top: 2px;
            transition: border 0.2s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #1976d2;
            outline: none;
        }
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 18px;
        }
        .btn-primary {
            background: #1976d2;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background: #125ea2;
        }
        .btn-secondary {
            background: #f5f5f5;
            color: #333;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        @media (max-width: 700px) {
            .modal-content { max-width: 98vw; padding: 10px 4vw; }
            .event-form-grid { grid-template-columns: 1fr; gap: 12px; }
        }
    </style>

    <script>
        // Use json_encode to safely pass PHP events to JS
        var events = <?php echo json_encode(array_map(function($ev) {
            return [
                "title" => ($ev['type'] ?? '') . ': ' . ($ev['title'] ?? ''),
                "start" => ($ev['date'] ?? '') . 'T' . ($ev['time'] ?? ''),
                "description" => $ev['description'] ?? '',
                "location" => $ev['location'] ?? '',
                "case" => $ev['case_title'] ?? '',
                "client" => $ev['client_name'] ?? '',
                "color" => ($ev['type'] ?? '') === 'Hearing' ? '#4CAF50' : '#2196F3'
            ];
        }, $events), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

        // Global function for updating event status
        function updateEventStatus(selectElement) {
            const newStatus = selectElement.value;
            const previousStatus = selectElement.dataset.previousStatus;
            const eventCard = selectElement.closest('.event-card');
            
            // Don't show confirmation if status didn't change
            if (newStatus === previousStatus) {
                return;
            }
            
            // Show enhanced confirmation with warnings based on status
            let confirmMessage = '';
            let warningIcon = '';
            
            switch(newStatus) {
                case 'completed':
                    confirmMessage = `⚠️ WARNING: Mark this event as COMPLETED?\n\nThis action will:\n• Mark the event as finished\n• Update the event history\n• Cannot be easily undone\n\nAre you sure you want to proceed?`;
                    warningIcon = '✅';
                    break;
                case 'no-show':
                    confirmMessage = `🚨 WARNING: Mark this event as NO-SHOW?\n\nThis action will:\n• Record client/party absence\n• May affect case timeline\n• Requires follow-up action\n\nAre you sure you want to proceed?`;
                    warningIcon = '❌';
                    break;
                case 'rescheduled':
                    confirmMessage = `🔄 WARNING: Mark this event as RESCHEDULED?\n\nThis action will:\n• Indicate event was postponed\n• Requires new date/time setup\n• May affect other schedules\n\nAre you sure you want to proceed?`;
                    warningIcon = '📅';
                    break;
                case 'cancelled':
                    confirmMessage = `🚫 WARNING: CANCEL this event?\n\nThis action will:\n• Permanently cancel the event\n• May affect case progress\n• Requires immediate rescheduling\n\nAre you sure you want to proceed?`;
                    warningIcon = '⏹️';
                    break;
                default:
                    confirmMessage = `ℹ️ Update event status to "${newStatus.toUpperCase()}"?\n\nThis will change the event status in the system.`;
                    warningIcon = 'ℹ️';
            }
            
            // Show enhanced confirmation dialog
            if (!confirm(confirmMessage)) {
                selectElement.value = previousStatus;
                return;
            }
            
            // Get event ID from the card (you might need to add this data attribute)
            const eventId = eventCard.dataset.eventId || '1'; // Default fallback
            
            // Show processing notification
            showNotification(`Updating event status to ${newStatus.toUpperCase()}...`, 'info');
            
            // Send AJAX request to update status
            fetch('update_event_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `event_id=${eventId}&new_status=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`✅ Event status successfully updated to ${newStatus.toUpperCase()}!`, 'success');
                    updateEventCardUI(selectElement, newStatus);
                    selectElement.dataset.previousStatus = newStatus;
                    
                    // Show additional warning for critical statuses
                    if (newStatus === 'cancelled') {
                        setTimeout(() => {
                            showNotification('⚠️ Remember to reschedule this cancelled event!', 'warning');
                        }, 2000);
                    } else if (newStatus === 'no-show') {
                        setTimeout(() => {
                            showNotification('⚠️ Follow up required for no-show event!', 'warning');
                        }, 2000);
                    }
                } else {
                    showNotification(`❌ Failed to update event status: ${data.message || 'Unknown error'}`, 'error');
                    selectElement.value = previousStatus;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('❌ Error updating event status. Please try again.', 'error');
                selectElement.value = previousStatus;
            });
        }

        // Global function for showing notifications
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => notification.classList.add('show'), 100);
            
            // Hide and remove notification
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: events,
                eventClick: function(info) {
                    alert('Event: ' + info.event.title + '\n' +
                          'Start: ' + info.event.start + '\n' +
                          'Location: ' + (info.event.extendedProps.location || '') + '\n' +
                          'Case: ' + (info.event.extendedProps.case || '') + '\n' +
                          'Client: ' + (info.event.extendedProps.client || '') + '\n' +
                          'Description: ' + (info.event.extendedProps.description || ''));
                }
            });
            calendar.render();

            // Modal functionality
            const modal = document.getElementById('addEventModal');
            const addEventBtn = document.getElementById('addEventBtn');
            const closeModal = document.querySelector('.close-modal');
            const cancelEvent = document.getElementById('cancelEvent');

            addEventBtn.onclick = function() {
                modal.style.display = "block";
            }

            closeModal.onclick = function() {
                modal.style.display = "none";
            }

            cancelEvent.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // View buttons functionality
            document.getElementById('viewDayBtn').onclick = function() {
                calendar.changeView('timeGridDay');
            }

            document.getElementById('viewWeekBtn').onclick = function() {
                calendar.changeView('timeGridWeek');
            }

            document.getElementById('viewMonthBtn').onclick = function() {
                calendar.changeView('dayGridMonth');
            }

            // Initialize event handlers after calendar is ready
            initializeEventHandlers();

            // Add AJAX for saving event
            document.getElementById('saveEvent').onclick = function() {
                const fd = new FormData(document.getElementById('eventForm'));
                fd.append('action', 'add_event');
                fetch('attorney_schedule.php', { method: 'POST', body: fd })
                    .then(r => r.text()).then(res => {
                        if (res === 'success') location.reload();
                        else alert('Error saving event.');
                    });
            };

            // Event status management functions
            
            // Expose to global scope so onchange handlers can call it
            window.updateEventCardUI = function(selectElement, newStatus) {
                const eventCard = selectElement.closest('.event-card');
                
                // Remove previous status classes
                eventCard.classList.remove('status-scheduled', 'status-completed', 'status-no-show', 'status-rescheduled', 'status-cancelled');
                
                // Add new status class
                eventCard.classList.add(`status-${newStatus}`);
                
                // Update border color based on status
                const borderColors = {
                    'scheduled': '#1976d2',
                    'completed': '#2e7d32',
                    'no-show': '#d32f2f',
                    'rescheduled': '#f57c00',
                    'cancelled': '#6c757d'
                };
                
                eventCard.style.borderLeftColor = borderColors[newStatus] || '#1976d2';
            }
            
            // Handle View Details button clicks
            function handleViewDetailsClick() {
                document.querySelectorAll('.view-info-btn').forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Populate modal with event data
                        document.getElementById('modalEventType').innerText = this.dataset.type || 'Event';
                        document.getElementById('modalEventDate').innerText = this.dataset.date || 'Date';
                        document.getElementById('modalEventTime').innerText = this.dataset.time ? new Date('1970-01-01T' + this.dataset.time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : 'Time';
                        document.getElementById('modalType').innerText = this.dataset.type || '-';
                        document.getElementById('modalDate').innerText = this.dataset.date || '-';
                        document.getElementById('modalTime').innerText = this.dataset.time ? new Date('1970-01-01T' + this.dataset.time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-';
                        document.getElementById('modalLocation').innerText = this.dataset.location || '-';
                        document.getElementById('modalCase').innerText = this.dataset.case || '-';
                        document.getElementById('modalAttorney').innerText = '<?= htmlspecialchars($_SESSION['attorney_name'] ?? 'N/A') ?>';
                        document.getElementById('modalClient').innerText = this.dataset.client || '-';
                        document.getElementById('modalDescription').innerText = this.dataset.description || '-';
                        
                        // Show modal
                        document.getElementById('eventInfoModal').style.display = "block";
                        
                        // Add event listeners for close buttons after modal is shown
                        addModalCloseListeners();
                    });
                });
            }
            
            // Add event listeners for modal close buttons
            function addModalCloseListeners() {
                // Close button (X) in header for Event Details modal
                const closeModal = document.querySelector('#eventInfoModal .close-modal');
                if (closeModal) {
                    // Remove existing listeners first
                    closeModal.replaceWith(closeModal.cloneNode(true));
                    const newCloseModal = document.querySelector('#eventInfoModal .close-modal');
                    newCloseModal.addEventListener('click', function() {
                        document.getElementById('eventInfoModal').style.display = "none";
                    });
                }
                
                // Close button in footer for Event Details modal
                const closeEventInfoModal = document.getElementById('closeEventInfoModal');
                if (closeEventInfoModal) {
                    // Remove existing listeners first
                    closeEventInfoModal.replaceWith(closeEventInfoModal.cloneNode(true));
                    const newCloseEventInfoModal = document.getElementById('closeEventInfoModal');
                    newCloseEventInfoModal.addEventListener('click', function() {
                        document.getElementById('eventInfoModal').style.display = "none";
                    });
                }
                
                // Close button (X) in header for Add Event modal
                const addEventCloseModal = document.querySelector('#addEventModal .close-modal');
                if (addEventCloseModal) {
                    // Remove existing listeners first
                    addEventCloseModal.replaceWith(addEventCloseModal.cloneNode(true));
                    const newAddEventCloseModal = document.querySelector('#addEventModal .close-modal');
                    newAddEventCloseModal.addEventListener('click', function() {
                        document.getElementById('addEventModal').style.display = "none";
                    });
                }
            }
            
            // Initialize all event handlers
            function initializeEventHandlers() {
                // Initialize status selects
                document.querySelectorAll('.status-select').forEach(select => {
                    select.dataset.previousStatus = select.value;
                });
                
                // Initialize view details buttons
                handleViewDetailsClick();
                
                // Initialize modal close functionality
                initializeModalHandlers();
            }
            
            // Initialize modal handlers
            function initializeModalHandlers() {
                // Close modal when clicking outside
                window.onclick = function(event) {
                    if (event.target == document.getElementById('eventInfoModal')) {
                        document.getElementById('eventInfoModal').style.display = "none";
                    }
                }
            }
            
            // Initialize when DOM is ready
            document.addEventListener('DOMContentLoaded', function() {
                initializeEventHandlers();
            });
            
            // Modal close functionality is now handled in initializeModalHandlers()
            

        });
    </script>
</body>
</html> 