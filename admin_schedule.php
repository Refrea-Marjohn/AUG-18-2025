<?php
require_once 'session_manager.php';
validateUserAccess('admin');
require_once 'config.php';
require_once 'audit_logger.php';
require_once 'action_logger_helper.php';
$admin_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT profile_image FROM user_form WHERE id=?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$res = $stmt->get_result();
$profile_image = '';
if ($res && $row = $res->fetch_assoc()) {
    $profile_image = $row['profile_image'];
}
if (!$profile_image || !file_exists($profile_image)) {
        $profile_image = 'images/default-avatar.jpg';
    }
// Fetch all events with joins
$events = [];
$stmt = $conn->prepare("SELECT cs.*, ac.title as case_title, ac.attorney_id, uf1.name as attorney_name, uf2.name as client_name FROM case_schedules cs
    LEFT JOIN attorney_cases ac ON cs.case_id = ac.id
    LEFT JOIN user_form uf1 ON ac.attorney_id = uf1.id
    LEFT JOIN user_form uf2 ON cs.client_id = uf2.id
    ORDER BY cs.date, cs.time");
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) $events[] = $row;
$js_events = [];
foreach ($events as $ev) {
    $js_events[] = [
        'title' => $ev['type'] . ': ' . ($ev['case_title'] ?? ''),
        'start' => $ev['date'] . 'T' . $ev['time'],
        'type' => $ev['type'],
        'description' => $ev['description'],
        'location' => $ev['location'],
        'case' => $ev['case_title'],
        'attorney' => $ev['attorney_name'],
        'client' => $ev['client_name'],
        'color' => $ev['type'] === 'Hearing' ? '#1976d2' : '#43a047',
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Management - Opiña Law Office</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
                <div class="sidebar-header">
            <img src="images/logo.jpg" alt="Logo">
            <h2>Opiña Law Office</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="admin_documents.php"><i class="fas fa-file-alt"></i><span>Document Storage</span></a></li>
            <li><a href="admin_document_generation.php"><i class="fas fa-file-alt"></i><span>Document Generations</span></a></li>
            <li><a href="admin_schedule.php" class="active"><i class="fas fa-calendar-alt"></i><span>Schedule</span></a></li>
            <li><a href="admin_usermanagement.php"><i class="fas fa-users-cog"></i><span>User Management</span></a></li>
            <li><a href="admin_managecases.php"><i class="fas fa-gavel"></i><span>Case Management</span></a></li>
            <li><a href="admin_clients.php"><i class="fas fa-users"></i><span>My Clients</span></a></li>
            <li><a href="admin_messages.php"><i class="fas fa-comments"></i><span>Messages</span></a></li>
            <li><a href="admin_audit.php"><i class="fas fa-history"></i><span>Audit Trail</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-title">
                <h1>Schedule Management</h1>
                <p>Manage court hearings, meetings, and appointments</p>
            </div>
            <div class="user-info">
                <img src="<?= htmlspecialchars($profile_image) ?>" alt="Admin" style="object-fit:cover;width:60px;height:60px;border-radius:50%;border:2px solid #1976d2;">
                <div class="user-details">
                    <h3><?php echo $_SESSION['admin_name']; ?></h3>
                    <p>System Administrator</p>
                </div>
            </div>
        </div>


        <!-- Calendar Container -->
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>

        <!-- Enhanced Upcoming Events -->
        <div class="upcoming-events-section">
            <div class="section-header">
                <h2><i class="fas fa-calendar-check"></i> Upcoming Events</h2>
                <p>Manage and monitor all scheduled activities</p>
            </div>
            
            <?php if (empty($events)): ?>
                <div class="no-events">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Upcoming Events</h3>
                    <p>No events are currently scheduled. Start by adding new events to your calendar.</p>
                </div>
            <?php else: ?>
                <div class="events-grid">
                    <?php foreach ($events as $ev): ?>
                    <div class="event-card">
                        <div class="event-card-header">
                            <div class="event-avatar">
                                <div class="avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="event-info">
                                <h3><?= htmlspecialchars($ev['type']) ?></h3>
                                <p class="case-detail"><i class="fas fa-folder"></i> <?= htmlspecialchars($ev['case_title'] ?? 'No Case') ?></p>
                                <p class="attorney-detail"><i class="fas fa-user-tie"></i> <?= htmlspecialchars($ev['attorney_name'] ?? 'No Attorney') ?></p>
                            </div>
                        </div>

                        <div class="event-stats">
                            <div class="stat-item active">
                                <div class="stat-number"><?= htmlspecialchars(date('d', strtotime($ev['date']))) ?></div>
                                <div class="stat-label"><?= htmlspecialchars(date('M', strtotime($ev['date']))) ?></div>
                            </div>
                            <div class="stat-item pending">
                                <div class="stat-number"><?= htmlspecialchars(date('h:i A', strtotime($ev['time']))) ?></div>
                                <div class="stat-label">Time</div>
                            </div>
                            <div class="stat-item client">
                                <div class="stat-number client-name" title="<?= htmlspecialchars($ev['client_name'] ?? 'No Client') ?>">
                                    <?php 
                                    $clientName = $ev['client_name'] ?? 'No Client';
                                    if ($clientName === 'No Client') {
                                        echo 'No Client';
                                    } else {
                                        echo htmlspecialchars(substr($clientName, 0, 10)) . (strlen($clientName) > 10 ? '...' : '');
                                    }
                                    ?>
                                </div>
                                <div class="stat-label">Client</div>
                            </div>
                        </div>

                        <div class="event-actions">
                            <span class="event-status status-active">
                                Scheduled
                            </span>
                            <button class="btn btn-primary btn-sm view-info-btn"
                                data-type="<?= htmlspecialchars($ev['type']) ?>"
                                data-date="<?= htmlspecialchars($ev['date']) ?>"
                                data-time="<?= htmlspecialchars($ev['time']) ?>"
                                data-location="<?= htmlspecialchars($ev['location']) ?>"
                                data-case="<?= htmlspecialchars($ev['case_title'] ?? '-') ?>"
                                data-attorney="<?= htmlspecialchars($ev['attorney_name'] ?? '-') ?>"
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
                    <button class="close-modal">&times;</button>
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
    </div>

    <style>
        .calendar-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.08);
        }
        .fc .fc-toolbar-title {
            font-size: 1.6em;
            font-weight: 600;
            color: #1976d2;
        }
        .fc .fc-daygrid-day.fc-day-today {
            background: #e3f2fd;
        }
        .fc .fc-daygrid-event {
            border-radius: 6px;
            font-size: 1em;
            box-shadow: 0 1px 4px rgba(25, 118, 210, 0.08);
        }
        .fc .fc-button {
            background: #1976d2;
            border: none;
            color: #fff;
            border-radius: 5px;
            padding: 6px 14px;
            font-weight: 500;
            margin: 0 2px;
            transition: background 0.2s;
        }
        .fc .fc-button:hover, .fc .fc-button:focus {
            background: #1565c0;
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active {
            background: #43a047;
        }
        .view-options .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            background: #f4f6f8;
            color: #1976d2;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
            }
        .view-options .btn.active, .view-options .btn:active {
            background: #1976d2;
            color: #fff;
            }
        .legend {
            font-size: 1em;
        }
        @media (max-width: 900px) {
            .calendar-container { padding: 5px; }
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
            text-align: center;
            margin-bottom: 2rem;
        }

        .section-header h2 {
            color: #1976d2;
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }

        .section-header p {
            color: #666;
            font-size: 1.1rem;
            margin: 0;
        }

        .no-events {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .no-events i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .no-events h3 {
            margin: 0 0 1rem 0;
            color: #333;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            align-items: start;
        }

        .event-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
            cursor: pointer;
        }

        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            border-color: #1976d2;
        }

        .event-card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .event-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .event-info h3 {
            margin: 0 0 0.25rem 0;
            color: #1976d2;
            font-weight: 600;
        }

        .event-info p {
            margin: 0 0 0.5rem 0;
            color: #666;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .event-info p:last-child {
            margin-bottom: 0;
        }

        .case-detail {
            color: #1976d2 !important;
            font-weight: 500;
        }

        .attorney-detail {
            color: #43a047 !important;
            font-weight: 500;
        }

        .event-info i {
            font-size: 1rem;
            width: 16px;
            text-align: center;
        }

        .event-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            height: 85px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 85px;
        }

        .stat-item.active {
            background: rgba(39, 174, 96, 0.1);
            border-color: rgba(39, 174, 96, 0.2);
        }

        .stat-item.pending {
            background: rgba(243, 156, 18, 0.1);
            border-color: rgba(243, 156, 18, 0.2);
        }

        .stat-item.client {
            background: rgba(156, 39, 176, 0.1);
            border-color: rgba(156, 39, 176, 0.2);
        }

        .stat-item.client .stat-number {
            font-size: 1.1rem;
            font-weight: 600;
            color: #9c27b0;
            max-width: 100%;
            padding: 0 0.25rem;
            min-height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1976d2;
            margin-bottom: 0.25rem;
            line-height: 1;
            min-height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            width: 100%;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1;
            min-height: 1rem;
        }

        .client-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            cursor: help;
            font-size: 0.9rem;
            line-height: 1.2;
            text-align: center;
            width: 100%;
        }

        .event-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .event-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: rgba(25, 118, 210, 0.1);
            color: #1976d2;
            border: 1px solid rgba(25, 118, 210, 0.3);
        }

        .status-inactive {
            background: rgba(244, 67, 54, 0.1);
            color: #c62828;
            border: 1px solid rgba(244, 67, 54, 0.3);
        }

        /* Button Styles */
        .btn {
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #1976d2;
            color: white;
        }

        .btn-primary:hover {
            background: #1565c0;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        /* Professional Event Details Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: white;
            margin: 3% auto;
            border-radius: 8px;
            width: 90%;
            max-width: 700px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, #1976d2, #1565c0);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .header-text h2 {
            margin: 0;
            font-size: 1.5rem;
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
            transition: all 0.2s;
        }

        .close-modal:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .modal-body {
            padding: 1rem;
        }

        .event-overview {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .event-type-display .type-badge {
            background: linear-gradient(135deg, #9c27b0, #7b1fa2);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .event-datetime {
            display: flex;
            gap: 1rem;
        }

        .date-display, .time-display {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            text-align: center;
            min-width: 80px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .date-display {
            color: #1976d2;
            font-weight: 600;
        }

        .time-display {
            color: #43a047;
            font-weight: 600;
        }

        .event-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .detail-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.75rem;
            border: 1px solid #e9ecef;
        }

        .detail-section h3 {
            color: #1976d2;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0 0 1rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-section h3 i {
            color: #9c27b0;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.4rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 120px;
        }

        .detail-label i {
            color: #9c27b0;
            width: 16px;
            text-align: center;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
            text-align: right;
            max-width: 250px;
            word-wrap: break-word;
            line-height: 1.3;
        }

        .modal-footer {
            padding: 0.75rem 1.5rem;
            background: #f8f9fa;
            border-radius: 0 0 8px 8px;
            text-align: right;
        }

        .btn-close-modal {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-close-modal:hover {
            background: linear-gradient(135deg, #5a6268, #495057);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .events-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .events-grid {
                grid-template-columns: 1fr;
            }
            
            .upcoming-events-section {
                padding: 1rem;
            }
            
            .section-header h2 {
                font-size: 1.5rem;
            }

            /* Mobile Modal Responsiveness */
            .modal-content {
                width: 95%;
                margin: 10% auto;
            }

            .modal-header {
                padding: 1rem 1.5rem;
            }

            .header-text h2 {
                font-size: 1.5rem;
            }

            .header-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .event-overview {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .event-details-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .detail-section {
                padding: 1rem;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var events = <?php echo json_encode($js_events); ?>;
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 600,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: events,
                eventClick: function(info) {
                    showEventModal(info.event.extendedProps, info.event);
                }
            });
            calendar.render();
            // View Info button in table
            document.querySelectorAll('.view-info-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    showEventModal({
                        type: this.dataset.type,
                        date: this.dataset.date,
                        time: this.dataset.time,
                        location: this.dataset.location,
                        case: this.dataset.case,
                        attorney: this.dataset.attorney,
                        client: this.dataset.client,
                        description: this.dataset.description
                    });
                });
            });
            // Modal logic
            function showEventModal(props, eventObj) {
                // Populate main fields
                document.getElementById('modalType').innerText = props.type || (eventObj && eventObj.title ? eventObj.title.split(':')[0] : '') || '';
                document.getElementById('modalDate').innerText = props.date || (eventObj && eventObj.start ? eventObj.start.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' }) : '');
                document.getElementById('modalTime').innerText = props.time || (eventObj && eventObj.start ? eventObj.start.toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit' }) : '');
                document.getElementById('modalLocation').innerText = props.location || '';
                document.getElementById('modalCase').innerText = props.case || '';
                document.getElementById('modalAttorney').innerText = props.attorney || '';
                document.getElementById('modalClient').innerText = props.client || '';
                document.getElementById('modalDescription').innerText = props.description || '';
                
                // Populate overview section
                document.getElementById('modalEventType').innerText = props.type || (eventObj && eventObj.title ? eventObj.title.split(':')[0] : '') || 'Event';
                document.getElementById('modalEventDate').innerText = props.date || (eventObj && eventObj.start ? eventObj.start.toLocaleDateString(undefined, { month: 'short', day: 'numeric' }) : 'Date');
                document.getElementById('modalEventTime').innerText = props.time || (eventObj && eventObj.start ? eventObj.start.toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit' }) : 'Time');
                
                document.getElementById('eventInfoModal').style.display = "block";
            }
            document.querySelectorAll('.close-modal, #closeEventInfoModal').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.getElementById('eventInfoModal').style.display = "none";
                });
            });
            window.onclick = function(event) {
                if (event.target == document.getElementById('eventInfoModal')) {
                    document.getElementById('eventInfoModal').style.display = "none";
                }
            }
        });
    </script>
</body>
</html> 