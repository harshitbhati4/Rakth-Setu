<?php
require_once __DIR__ . '/includes/config.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    setFlashMessage('error', 'Please login to update your donor status');
    redirect('login.php');
}

// Validate CSRF token
if (!verifyCSRFToken($_POST['csrf_token'])) {
    setFlashMessage('error', 'Invalid form submission, please try again.');
    redirect('receiver.php');
}

// Get user data
$user = getUserById($_SESSION['user_id']);

// Update user to be a donor
$user_data = [
    'name' => $user['name'],
    'phone' => $user['phone'],
    'blood_type' => $user['blood_type'],
    'address' => $user['address'],
    'city' => $user['city'],
    'state' => $user['state'],
    'is_donor' => 1,
    'date_of_birth' => $user['date_of_birth'],
    'gender' => $user['gender'],
    'weight' => $user['weight']
];

$result = updateUserProfile($_SESSION['user_id'], $user_data);

if ($result === true) {
    // Update session variable
    $_SESSION['is_donor'] = 1;
    
    setFlashMessage('success', 'You are now registered as a blood donor! Thank you for your willingness to save lives.');
    redirect('sender.php');
} else {
    setFlashMessage('error', $result);
    redirect('receiver.php');
}
