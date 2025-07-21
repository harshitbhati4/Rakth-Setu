
<?php
$page_title = "Forgot Password";
require_once 'includes/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid form submission, please try again.');
        redirect('forgot_password.php');
    }
    
    $email = sanitize($_POST['email']);
    
    $result = resetPassword($email);
    
    if ($result === true) {
        setFlashMessage('success', 'Password reset instructions have been sent to your email.');
        redirect('login.php');
    } else {
        setFlashMessage('error', $result);
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
                            <path d="M22 2h-8v4h8V2zM2 6h8v4H2V6zM22 10h-8v4h8v-4zM2 14h8v4H2v-4zM22 18h-8v4h8v-4zM2 22h8v4H2v-4z"></path>
                        </svg>
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-6 h-6 bg-primary text-white text-xs font-bold flex items-center justify-center rounded-full">
                        RS
                    </span>
                </div>
            </div>
            
            <h1 class="text-2xl font-bold text-center mb-2">
                Forgot Password
            </h1>
            <p class="text-muted text-center mb-8">
                Enter your email address and we'll send you a link to reset your password.
            </p>

            <?php
            $error = getFlashMessage('error');
            if ($error):
            ?>
            <div class="alert alert-danger mb-4">
                <?php echo $error['message']; ?>
            </div>
            <?php endif; ?>

            <form action="forgot_password.php" method="post" class="space-y-6">
                <div class="space-y-2">
                    <label for="email" class="form-label">
                        Email
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        class="form-control"
                        required
                    />
                </div>

                <button
                    type="submit"
                    class="btn btn-primary btn-block"
                >
                    Send Reset Link
                </button>
                
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            </form>

            <div class="mt-8 text-center">
                <p class="text-muted">
                    Remember your password?
                    <a href="login.php" class="text-primary hover:underline">
                        Back to login
                    </a>
                </p>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
