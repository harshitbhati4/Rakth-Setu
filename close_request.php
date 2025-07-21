
<?php
require_once 'includes/config.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    setFlashMessage('error', 'Please login to manage blood requests');
    redirect('login.php');
}

// Check if request ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setFlashMessage('error', 'Invalid blood request');
    redirect('receiver.php');
}

$request_id = (int)$_GET['id'];
$request = getDonationRequestById($request_id);

// Check if request exists and belongs to user
if (!$request || $request['user_id'] != $_SESSION['user_id']) {
    setFlashMessage('error', 'You do not have permission to modify this request');
    redirect('receiver.php');
}

// Check if request is already closed or fulfilled
if ($request['status'] !== 'open') {
    setFlashMessage('error', 'This request is already ' . $request['status']);
    redirect('receiver.php');
}

// Close the request
$stmt = $conn->prepare("UPDATE donation_requests SET status = 'closed' WHERE id = ?");
$stmt->bind_param("i", $request_id);

if ($stmt->execute()) {
    setFlashMessage('success', 'Blood request has been closed successfully');
} else {
    setFlashMessage('error', 'Failed to close blood request');
}

redirect('receiver.php');
