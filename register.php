
<?php
$page_title = "Register";
require_once 'includes/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

// Determine registration type (donor or recipient)
$is_donor = isset($_GET['type']) && $_GET['type'] === 'donor' ? 1 : 0;

// Check for form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid form submission, please try again.');
        redirect('register.php');
    }
    
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = sanitize($_POST['phone']);
    $blood_type = sanitize($_POST['blood_type']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    $state = sanitize($_POST['state']);
    $gender = sanitize($_POST['gender']);
    $dob = sanitize($_POST['date_of_birth']);
    $weight = sanitize($_POST['weight']);
    $is_donor = isset($_POST['is_donor']) ? 1 : 0;
    
    // Validate password
    if ($password !== $confirm_password) {
        setFlashMessage('error', 'Passwords do not match');
        redirect('register.php');
    }
    
    if (strlen($password) < 8) {
        setFlashMessage('error', 'Password must be at least 8 characters long');
        redirect('register.php');
    }
    
    // Create user data array
    $user_data = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'phone' => $phone,
        'blood_type' => $blood_type,
        'address' => $address,
        'city' => $city,
        'state' => $state,
        'is_donor' => $is_donor,
        'date_of_birth' => $dob,
        'gender' => $gender,
        'weight' => $weight
    ];
    
    // Register user
    $result = registerUser($user_data);
    
    if ($result === true) {
        setFlashMessage('success', 'Registration successful! Please login.');
        redirect('login.php');
    } else {
        setFlashMessage('error', $result);
    }
}

include 'includes/header.php';
?>

<main class="pt-28 pb-16">
    <div class="container">
        <div class="max-w-xl mx-auto">
            <div class="mb-8">
                <a href="index.php" class="inline-flex items-center text-muted hover:text-gray-800 transition-colors">
                    <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7"></path>
                    </svg>
                    Back to home
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 fade-in">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold mb-2">Create Account</h1>
                    <p class="text-muted">
                        Join Rakth Setu as a <?php echo $is_donor ? 'blood donor' : 'recipient'; ?>
                    </p>
                </div>
                
                <?php
                $error = getFlashMessage('error');
                if ($error):
                ?>
                <div class="alert alert-danger mb-4">
                    <?php echo $error['message']; ?>
                </div>
                <?php endif; ?>

                <form action="register.php" method="post" class="space-y-6">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                                <small class="form-text">Must be at least 8 characters long</small>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Blood Type</label>
                                <div class="blood-type-container mt-2">
                                    <?php 
                                    $blood_types = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                    foreach ($blood_types as $type):
                                    ?>
                                    <div class="blood-type-card" data-blood-type="<?php echo $type; ?>">
                                        <div class="blood-type"><?php echo $type; ?></div>
                                        <div class="blood-type-label">Blood Type</div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" name="blood_type" required>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="gender" class="form-label">Gender</label>
                                <select id="gender" name="gender" class="form-control" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="weight" class="form-label">Weight (kg)</label>
                                <input type="number" id="weight" name="weight" min="0" step="0.1" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" id="address" name="address" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="city" class="form-label">City</label>
                                <input type="text" id="city" name="city" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="state" class="form-label">State</label>
                                <input type="text" id="state" name="state" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_donor" name="is_donor" <?php echo $is_donor ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_donor">
                                    Register as a blood donor
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Create Account</button>
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                </form>

                <div class="mt-6 text-center">
                    <p class="text-muted">
                        Already have an account?
                        <a href="login.php" class="text-primary hover:underline">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
