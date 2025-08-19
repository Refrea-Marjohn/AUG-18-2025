<?php
// Test database connection and table structure
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

echo "<h2>Database Connection Test</h2>";

// Test connection
if ($conn) {
    echo "✅ Database connection successful<br>";
} else {
    echo "❌ Database connection failed<br>";
    exit;
}

// Test case_schedules table
echo "<h3>Testing case_schedules table:</h3>";
$result = $conn->query("DESCRIBE case_schedules");
if ($result) {
    echo "✅ case_schedules table exists<br>";
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']}: {$row['Type']}<br>";
    }
} else {
    echo "❌ case_schedules table not found<br>";
}

// Test audit_trail table
echo "<h3>Testing audit_trail table:</h3>";
$result = $conn->query("DESCRIBE audit_trail");
if ($result) {
    echo "✅ audit_trail table exists<br>";
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']}: {$row['Type']}<br>";
    }
} else {
    echo "❌ audit_trail table not found<br>";
}

// Test sample data
echo "<h3>Sample case_schedules data:</h3>";
$result = $conn->query("SELECT id, status, attorney_id FROM case_schedules LIMIT 5");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "- ID: {$row['id']}, Status: {$row['status']}, Attorney ID: {$row['attorney_id']}<br>";
    }
} else {
    echo "No data found in case_schedules table<br>";
}

$conn->close();
?>
