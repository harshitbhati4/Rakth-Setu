
<?php
$page_title = "Reset Password";
require_once 'includes/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

// Check for token
if (!isset($_GET['token']) || empty($_GET['token'])) {
    setFlashMessage('error', 'Invalid or missing reset token.');
    redirect('forgot_password.php');
}

$token = $_GET['token'];
$user = verifyResetToken($token);

if (!$user) {
    setFlashMessage('error', 'Invalid or expired reset token.');
    redirect('forgot_password.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid form submission, please try again.');
        redirect("reset_password.php?token=$token");
    }
    
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate password
    if ($password !== $confirm_password) {
        setFlashMessage('error', 'Passwords do not match');
        redirect("reset_password.php?token=$token");
    }
    
    if (strlen($password) < 8) {
        setFlashMessage('error', 'Password must be at least 8 characters long');
        redirect("reset_password.php?token=$token");
    }
    
    // Update password
    if (updatePassword($user['id'], $password)) {
        setFlashMessage('success', 'Your password has been reset successfully. Please login with your new password.');
        redirect('login.php');
    } else {
        setFlashMessage('error', 'Failed to reset password. Please try again.');
        redirect("reset_password.php?token=$token");
    }
}

include 'includes/header.php';
?>

<main class="pt-28 pb-16 px-6">
    <div class="max-w-md mx-auto">
        <div class="mb-8">
            <a href="login.php" class="inline-flex items-center text-muted-foreground hover:text-foreground transition-colors">
                <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"></path>
                </svg>
                Back to login
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 fade-in">
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center">
                        <svg class="h-7 w-7 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-6 h-6 bg-primary text-white text-xs font-bold flex items-center justify-center rounded-full">
                        RS
                    </span>
                </div>
            </div>
            
            <h1 class="text-2xl font-bold text-center mb-2">
                Reset Password
            </h1>
            <p class="text-muted text-center mb-8">
                Create a new password for your account
            </p>

            <?php
            $error = getFlashMessage('error');
            if ($error):
            ?>
            <div class="alert alert-danger mb-4">
                <?php echo $error['message']; ?>
            </div>
            <?php endif; ?>

            <form action="reset_password.php?token=<?php echo $token; ?>" method="post" class="space-y-6">
                <div class="space-y-2">
                    <label for="password" class="form-label">
                        New Password
                    </label>
                    <input
                        id="password"
                        name="password"
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

                <button
                    type="submit"
                    class="btn btn-primary btn-block"
                >
                    Reset Password
                </button>
                
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
