
<?php
$page_title = "Donate Blood";
require_once 'includes/config.php';

// Redirect if not logged in or not a donor
if (!isLoggedIn()) {
    setFlashMessage('error', 'Please login to donate blood');
    redirect('login.php');
}

if (!$_SESSION['is_donor']) {
    setFlashMessage('error', 'You are not registered as a donor');
    redirect('receiver.php');
}

// Check if request ID is provided
if (!isset($_GET['request']) || empty($_GET['request'])) {
    setFlashMessage('error', 'Invalid blood request');
    redirect('sender.php');
}

$request_id = (int)$_GET['request'];
$request = getDonationRequestById($request_id);

// Check if request exists and is open
if (!$request || $request['status'] !== 'open') {
    setFlashMessage('error', 'The blood request is no longer available');
    redirect('sender.php');
}

// Get user data
$user = getUserById($_SESSION['user_id']);

// Check donor eligibility
$can_donate = true;
$reason = '';

// Check if user has donated in the last 3 months
if ($user['last_donation_date']) {
    $last_donation = new DateTime($user['last_donation_date']);
    $now = new DateTime();
    $diff = $now->diff($last_donation);
    
    if ($diff->days < 84) { // 84 days = 12 weeks
        $can_donate = false;
        $days_left = 84 - $diff->days;
        $reason = "You must wait at least 12 weeks between whole blood donations. You can donate again in $days_left days.";
    }
}

// Check weight
if ($user['weight'] < 50) {
    $can_donate = false;
    $reason = "Your weight is below the minimum requirement of 50kg for blood donation.";
}

// Check blood compatibility
$compatibility = getBloodCompatibility($user['blood_type']);
if (!$compatibility || !in_array($request['blood_type'], $compatibility['can_donate_to'])) {
    $can_donate = false;
    $reason = "Your blood type ({$user['blood_type']}) is not compatible with the requested blood type ({$request['blood_type']}).";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $can_donate) {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid form submission, please try again.');
        redirect("donate.php?request=$request_id");
    }
    
    $donation_date = sanitize($_POST['donation_date']);
    
    $donation_data = [
        'donor_id' => $_SESSION['user_id'],
        'request_id' => $request_id,
        'donation_date' => $donation_date,
        'update_request_status' => isset($_POST['fulfill_request'])
    ];
    
    $result = createDonation($donation_data);
    
    if ($result === true) {
        // Update user's last donation date
        $update_user = [
            'name' => $user['name'],
            'phone' => $user['phone'],
            'blood_type' => $user['blood_type'],
            'address' => $user['address'],
            'city' => $user['city'],
            'state' => $user['state'],
            'is_donor' => $user['is_donor'],
            'date_of_birth' => $user['date_of_birth'],
            'gender' => $user['gender'],
            'weight' => $user['weight'],
            'last_donation_date' => $donation_date
        ];
        
        updateUserProfile($_SESSION['user_id'], $update_user);
        
        setFlashMessage('success', 'Thank you for your donation! Your generosity saves lives.');
        redirect('sender.php');
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
                <h1 class="hero-title">Donate <span>Blood</span></h1>
                <p class="hero-subtitle">
                    Complete the form below to schedule your blood donation
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
                            <h3>Recipient Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($request['patient_name']); ?></p>
                                <p><strong>Blood Type Needed:</strong> <span class="blood-type-pill"><?php echo $request['blood_type']; ?></span></p>
                                <p><strong>Units Needed:</strong> <?php echo $request['units_needed']; ?></p>
                                <p><strong>Urgency:</strong> 
                                    <?php
                                    $badge_class = 'badge-primary';
                                    if ($request['urgency'] === 'urgent') {
                                        $badge_class = 'badge-warning';
                                    } elseif ($request['urgency'] === 'critical') {
                                        $badge_class = 'badge-danger';
                                    }
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($request['urgency']); ?></span>
                                </p>
                            </div>
                            
                            <div>
                                <h4 class="mb-2">Hospital Details</h4>
                                <p><strong>Hospital:</strong> <?php echo htmlspecialchars($request['hospital']); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($request['location']); ?></p>
                                <p><strong>City:</strong> <?php echo htmlspecialchars($request['city']); ?></p>
                                <p><strong>State:</strong> <?php echo htmlspecialchars($request['state']); ?></p>
                            </div>
                            
                            <?php if (!empty($request['additional_info'])): ?>
                            <div class="mt-4">
                                <h4 class="mb-2">Additional Information</h4>
                                <p><?php echo htmlspecialchars($request['additional_info']); ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <div class="alert alert-info mt-4">
                                <strong>Contact Information</strong>
                                <p class="mb-0"><strong>Contact Person:</strong> <?php echo htmlspecialchars($request['patient_name']); ?></p>
                                <p class="mb-0"><strong>Phone:</strong> <?php echo htmlspecialchars($request['contact_phone']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Blood Compatibility</h3>
                        </div>
                        <div class="card-body">
                            <div>
                                <p class="mb-1"><strong>Your blood type:</strong> <?php echo $user['blood_type']; ?></p>
                                <p class="mb-1"><strong>Required blood type:</strong> <?php echo $request['blood_type']; ?></p>
                                
                                <?php if ($compatibility && in_array($request['blood_type'], $compatibility['can_donate_to'])): ?>
                                <div class="alert alert-success mt-3">
                                    <strong>Compatible!</strong> Your blood type can be donated to <?php echo $request['blood_type']; ?> recipients.
                                </div>
                                <?php else: ?>
                                <div class="alert alert-danger mt-3">
                                    <strong>Incompatible!</strong> Your blood type cannot be donated to <?php echo $request['blood_type']; ?> recipients.
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3>Schedule Donation</h3>
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
                            
                            <?php if (!$can_donate): ?>
                            <div class="alert alert-warning mb-4">
                                <strong>Donation Deferred</strong>
                                <p><?php echo $reason; ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <div class="alert alert-info mb-4">
                                <h4>Important Information</h4>
                                <ul class="mt-2">
                                    <li>Please bring a valid ID when you visit the hospital.</li>
                                    <li>Eat a healthy meal before donating blood.</li>
                                    <li>Stay hydrated before and after donation.</li>
                                    <li>The actual blood donation process takes about 10-15 minutes.</li>
                                    <li>After donation, rest for at least 15 minutes and have a light snack.</li>
                                </ul>
                            </div>
                            
                            <form action="donate.php?request=<?php echo $request_id; ?>" method="post" <?php echo !$can_donate ? 'class="opacity-50"' : ''; ?>>
                                <div class="form-group">
                                    <label for="donation_date" class="form-label">When would you like to donate?</label>
                                    <input 
                                        type="datetime-local" 
                                        id="donation_date" 
                                        name="donation_date" 
                                        class="form-control" 
                                        min="<?php echo date('Y-m-d\TH:i'); ?>"
                                        required
                                        <?php echo !$can_donate ? 'disabled' : ''; ?>
                                    >
                                    <small class="form-text">Please select a date and time that is convenient for you to visit the hospital.</small>
                                </div>
                                
                                <div class="form-check mt-4">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="fulfill_request" 
                                        name="fulfill_request"
                                        <?php echo !$can_donate ? 'disabled' : ''; ?>
                                    >
                                    <label class="form-check-label" for="fulfill_request">
                                        Mark the request as fulfilled (check this if you are donating all units needed)
                                    </label>
                                </div>
                                
                                <div class="form-check mt-2">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="terms" 
                                        required
                                        <?php echo !$can_donate ? 'disabled' : ''; ?>
                                    >
                                    <label class="form-check-label" for="terms">
                                        I confirm that I meet all eligibility criteria for blood donation and the information I've provided is accurate.
                                    </label>
                                </div>
                                
                                <div class="mt-4">
                                    <button 
                                        type="submit" 
                                        class="btn btn-primary" 
                                        <?php echo !$can_donate ? 'disabled' : ''; ?>
                                    >
                                        Schedule Donation
                                    </button>
                                    <a href="sender.php" class="btn btn-outline ml-2">Cancel</a>
                                </div>
                                
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            </form>
                            
                            <?php if (!$can_donate): ?>
                            <div class="mt-4">
                                <a href="sender.php" class="btn btn-primary">Back to Dashboard</a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
