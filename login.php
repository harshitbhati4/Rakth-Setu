
<?php
$page_title = "Login";
require_once 'includes/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);
    
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid form submission, please try again.');
        redirect('login.php');
    }
    
    $result = loginUser($email, $password, $remember);
    
    if ($result === true) {
        // Redirect based on user type
        if ($_SESSION['is_donor']) {
            redirect('sender.php');
        } else {
            redirect('receiver.php');
        }
    } else {
        setFlashMessage('error', $result);
    }
}

include 'includes/header.php';
?>

<main class="pt-28 pb-16 px-6">
    <div class="max-w-md mx-auto">
        <div class="mb-8">
            <a href="index.php" class="inline-flex items-center text-muted-foreground hover:text-foreground transition-colors">
                <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"></path>
                </svg>
                Back to home
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 fade-in">
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center">
                        <svg class="h-7 w-7 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-6 h-6 bg-primary text-white text-xs font-bold flex items-center justify-center rounded-full">
                        RS
                    </span>
                </div>
            </div>
            
            <h1 class="text-2xl font-bold text-center mb-2">
                Welcome back
            </h1>
            <p class="text-muted text-center mb-8">
                Sign in to your Rakth Setu account
            </p>

            <?php
            $error = getFlashMessage('error');
            if ($error):
            ?>
            <div class="alert alert-danger mb-4">
                <?php echo $error['message']; ?>
            </div>
            <?php endif; ?>

            <form action="login.php" method="post" class="space-y-6">
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

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password" class="form-label">
                            Password
                        </label>
                        <a href="forgot_password.php" class="text-xs text-primary hover:underline">
                            Forgot password?
                        </a>
                    </div>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-control"
                        required
                    />
                </div>

                <div class="form-check">
                    <input
                        id="remember"
                        name="remember"
                        type="checkbox"
                        class="form-check-input"
                    />
                    <label for="remember" class="form-check-label text-muted">
                        Remember me for 30 days
                    </label>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary btn-block"
                >
                    Sign in
                </button>
                
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            </form>

            <div class="mt-8 text-center">
                <p class="text-muted">
                    Don't have an account?
                    <a href="register.php" class="text-primary hover:underline">
                        Register now
                    </a>
                </p>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
