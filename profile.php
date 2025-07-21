
<?php
$page_title = "My Profile";
require_once 'includes/config.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    setFlashMessage('error', 'Please login to access your profile');
    redirect('login.php');
}

// Get user data
$user = getUserById($_SESSION['user_id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid form submission, please try again.');
        redirect('profile.php');
    }
    
    $name = sanitize($_POST['name']);
    $phone = sanitize($_POST['phone']);
    $blood_type = sanitize($_POST['blood_type']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    $state = sanitize($_POST['state']);
    $gender = sanitize($_POST['gender']);
    $dob = sanitize($_POST['date_of_birth']);
    $weight = sanitize($_POST['weight']);
    $is_donor = isset($_POST['is_donor']) ? 1 : 0;
    
    $user_data = [
        'name' => $name,
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
    
    $result = updateUserProfile($_SESSION['user_id'], $user_data);
    
    if ($result === true) {
        // Update session variable
        $_SESSION['user_name'] = $name;
        $_SESSION['is_donor'] = $is_donor;
        
        setFlashMessage('success', 'Profile updated successfully');
        redirect('profile.php');
    } else {
        setFlashMessage('error', $result);
    }
}

include 'includes/header.php';
?>

<main>
    <section class="hero bg-light">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">My <span>Profile</span></h1>
                <p class="hero-subtitle">
                    Update your personal information and preferences
                </p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Profile Overview</h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="mx-auto mb-3" style="width: 100px; height: 100px; background-color: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 2.5rem; font-weight: bold; color: var(--primary);"><?php echo $user['blood_type']; ?></span>
                                </div>
                                <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                                <p class="text-muted">Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                            </div>
                            
                            <div class="user-details">
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Email:</span>
                                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                                </div>
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">User Type:</span>
                                    <span><?php echo $user['is_donor'] ? 'Donor' : 'Recipient'; ?></span>
                                </div>
                                <?php if ($user['is_donor'] && $user['last_donation_date']): ?>
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Last Donation:</span>
                                    <span><?php echo date('F j, Y', strtotime($user['last_donation_date'])); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-4">
                                <?php if ($user['is_donor']): ?>
                                <a href="sender.php" class="btn btn-primary btn-block">Donor Dashboard</a>
                                <?php else: ?>
                                <a href="receiver.php" class="btn btn-primary btn-block">Recipient Dashboard</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Security</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h4 class="mb-2">Change Password</h4>
                                <p class="text-muted mb-3">
                                    If you want to change your password, click the button below.
                                </p>
                                <a href="change_password.php" class="btn btn-outline btn-block">Change Password</a>
                            </div>
                            
                            <div>
                                <h4 class="mb-2">Account Privacy</h4>
                                <p class="text-muted mb-3">
                                    Your contact information is only shared with users who have active blood requests that match your donor profile.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3>Edit Profile</h3>
                        </div>
                        <div class="card-body">
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
                            
                            <form action="profile.php" method="post">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Full Name</label>
                                            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                            <small class="form-text">Email cannot be changed</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Blood Type</label>
                                            <div class="blood-type-container mt-2">
                                                <?php 
                                                $blood_types = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                                foreach ($blood_types as $type):
                                                $is_active = $user['blood_type'] === $type;
                                                ?>
                                                <div class="blood-type-card <?php echo $is_active ? 'active' : ''; ?>" data-blood-type="<?php echo $type; ?>">
                                                    <div class="blood-type"><?php echo $type; ?></div>
                                                    <div class="blood-type-label">Blood Type</div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <input type="hidden" name="blood_type" value="<?php echo $user['blood_type']; ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?php echo $user['date_of_birth']; ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="gender" class="form-label">Gender</label>
                                            <select id="gender" name="gender" class="form-control" required>
                                                <option value="">Select Gender</option>
                                                <option value="Male" <?php echo $user['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                                <option value="Female" <?php echo $user['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                                <option value="Other" <?php echo $user['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="weight" class="form-label">Weight (kg)</label>
                                            <input type="number" id="weight" name="weight" min="0" step="0.1" class="form-control" value="<?php echo $user['weight']; ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" id="address" name="address" class="form-control" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" id="city" name="city" class="form-control" value="<?php echo htmlspecialchars($user['city']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text" id="state" name="state" class="form-control" value="<?php echo htmlspecialchars($user['state']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_donor" name="is_donor" <?php echo $user['is_donor'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="is_donor">
                                                Register as a blood donor
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="<?php echo $user['is_donor'] ? 'sender.php' : 'receiver.php'; ?>" class="btn btn-outline ml-2">Cancel</a>
                                </div>
                                
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
