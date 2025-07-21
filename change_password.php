
<?php
$page_title = "Change Password";
require_once 'includes/config.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    setFlashMessage('error', 'Please login to change your password');
    redirect('login.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid form submission, please try again.');
        redirect('change_password.php');
    }
    
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate passwords match
    if ($new_password !== $confirm_password) {
        setFlashMessage('error', 'New passwords do not match');
        redirect('change_password.php');
    }
    
    // Validate new password length
    if (strlen($new_password) < 8) {
        setFlashMessage('error', 'New password must be at least 8 characters long');
        redirect('change_password.php');
    }
    
    // Get user data to verify current password
    $user = getUserById($_SESSION['user_id']);
    
    if (!password_verify($current_password, $user['password'])) {
        setFlashMessage('error', 'Current password is incorrect');
        redirect('change_password.php');
    }
    
    // Update password
    if (updatePassword($_SESSION['user_id'], $new_password)) {
        setFlashMessage('success', 'Your password has been changed successfully');
        redirect('profile.php');
    } else {
        setFlashMessage('error', 'Failed to change password. Please try again.');
        redirect('change_password.php');
    }
}

include 'includes/header.php';
?>

<main class="pt-28 pb-16">
    <div class="container">
        <div class="max-w-md mx-auto">
            <div class="mb-8">
                <a href="profile.php" class="inline-flex items-center text-muted hover:text-gray-800 transition-colors">
                    <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7"></path>
                    </svg>
                    Back to profile
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 fade-in">
                <h1 class="text-2xl font-bold text-center mb-6">
                    Change Password
                </h1>
                
                <?php
                $error = getFlashMessage('error');
                if ($error):
                ?>
                <div class="alert alert-danger mb-4">
                    <?php echo $error['message']; ?>
                </div>
                <?php endif; ?>
                
                <?php
                $success = getFlashMessage('success');
                if ($success):
                ?>
                <div class="alert alert-success mb-4">
                    <?php echo $success['message']; ?>
                </div>
                <?php endif; ?>

                <form action="change_password.php" method="post" class="space-y-6">
                    <div class="space-y-2">
                        <label for="current_password" class="form-label">
                            Current Password
                        </label>
                        <input
                            id="current_password"
                            name="current_password"
                            type="password"
                            class="form-control"
                            required
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="new_password" class="form-label">
                            New Password
                        </label>
                        <input
                            id="new_password"
                            name="new_password"
                            type="password"
                            class="form-control"
                            required
                        />
                        <small class="form-text">Must be at least 8 characters long</small>
                    </div>

                    <div class="space-y-2">
                        <label for="confirm_password" class="form-label">
                            Confirm New Password
                        </label>
                        <input
                            id="confirm_password"
                            name="confirm_password"
                            type="password"
                            class="form-control"
                            required
                        />
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            Change Password
                        </button>
                        <a href="profile.php" class="btn btn-outline btn-block mt-2">
                            Cancel
                        </a>
                    </div>
                    
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
