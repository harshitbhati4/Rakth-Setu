
<?php
$page_title = "Recipient Dashboard";
require_once 'includes/config.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    setFlashMessage('error', 'Please login to access the recipient dashboard');
    redirect('login.php');
}

// Get user data
$user = getUserById($_SESSION['user_id']);

// Get the user's donation requests
$requests = getUserRequests($_SESSION['user_id']);

// Handle form submission for creating a new request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid form submission, please try again.');
        redirect('receiver.php');
    }
    
    $blood_type = sanitize($_POST['blood_type']);
    $units_needed = sanitize($_POST['units_needed']);
    $hospital = sanitize($_POST['hospital']);
    $location = sanitize($_POST['location']);
    $city = sanitize($_POST['city']);
    $state = sanitize($_POST['state']);
    $urgency = sanitize($_POST['urgency']);
    $patient_name = sanitize($_POST['patient_name']);
    $contact_phone = sanitize($_POST['contact_phone']);
    $additional_info = sanitize($_POST['additional_info']);
    
    $request_data = [
        'user_id' => $_SESSION['user_id'],
        'blood_type' => $blood_type,
        'units_needed' => $units_needed,
        'hospital' => $hospital,
        'location' => $location,
        'city' => $city,
        'state' => $state,
        'urgency' => $urgency,
        'patient_name' => $patient_name,
        'contact_phone' => $contact_phone,
        'additional_info' => $additional_info
    ];
    
    $result = createDonationRequest($request_data);
    
    if ($result === true) {
        setFlashMessage('success', 'Blood donation request created successfully');
        redirect('receiver.php');
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
                <h1 class="hero-title">Welcome, <span><?php echo htmlspecialchars($user['name']); ?></span></h1>
                <p class="hero-subtitle">
                    Create and manage your blood donation requests
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
                            <h3>Your Profile</h3>
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
                                    <span class="font-semibold">Phone:</span>
                                    <span><?php echo htmlspecialchars($user['phone']); ?></span>
                                </div>
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Blood Type:</span>
                                    <span><?php echo $user['blood_type']; ?></span>
                                </div>
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Location:</span>
                                    <span><?php echo htmlspecialchars($user['city'] . ', ' . $user['state']); ?></span>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="profile.php" class="btn btn-outline btn-block">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Blood Compatibility</h3>
                        </div>
                        <div class="card-body">
                            <div>
                                <p class="mb-1"><strong>Your blood type:</strong> <?php echo $user['blood_type']; ?></p>
                                
                                <?php $compatibility = getBloodCompatibility($user['blood_type']); ?>
                                <?php if ($compatibility): ?>
                                <p class="mb-1"><strong>You can donate to:</strong></p>
                                <div class="mb-2">
                                    <?php foreach ($compatibility['can_donate_to'] as $type): ?>
                                    <span class="blood-type-pill"><?php echo $type; ?></span>
                                    <?php endforeach; ?>
                                </div>
                                
                                <p class="mb-1"><strong>You can receive from:</strong></p>
                                <div class="mb-3">
                                    <?php foreach ($compatibility['can_receive_from'] as $type): ?>
                                    <span class="blood-type-pill"><?php echo $type; ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                
                                <p class="text-muted small">
                                    Note: In emergency situations, O- blood can typically be used for any recipient, and O+ can be used for any recipient with a positive blood type.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Want to Donate?</h3>
                        </div>
                        <div class="card-body">
                            <p>
                                If you'd like to become a blood donor, you can update your profile to help save lives!
                            </p>
                            
                            <form action="update_donor_status.php" method="post" class="mt-3">
                                <button type="submit" class="btn btn-primary btn-block">Become a Donor</button>
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Create New Blood Request</h3>
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
                            
                            <form action="receiver.php" method="post">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="blood_type" class="form-label">Blood Type Needed</label>
                                            <select id="blood_type" name="blood_type" class="form-control" required>
                                                <option value="">Select Blood Type</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="units_needed" class="form-label">Units Needed</label>
                                            <input type="number" id="units_needed" name="units_needed" min="1" max="10" value="1" class="form-control" required>
                                            <small class="form-text">1 unit = 450ml of blood</small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="hospital" class="form-label">Hospital Name</label>
                                            <input type="text" id="hospital" name="hospital" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="location" class="form-label">Hospital Address</label>
                                            <input type="text" id="location" name="location" class="form-control" required>
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
                                        <div class="form-group">
                                            <label for="urgency" class="form-label">Urgency Level</label>
                                            <select id="urgency" name="urgency" class="form-control" required>
                                                <option value="normal">Normal</option>
                                                <option value="urgent">Urgent</option>
                                                <option value="critical">Critical</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="patient_name" class="form-label">Patient Name</label>
                                            <input type="text" id="patient_name" name="patient_name" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="contact_phone" class="form-label">Contact Phone</label>
                                            <input type="tel" id="contact_phone" name="contact_phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="additional_info" class="form-label">Additional Information</label>
                                            <textarea id="additional_info" name="additional_info" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">Create Blood Request</button>
                                    <button type="reset" class="btn btn-outline ml-2">Reset Form</button>
                                </div>
                                
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            </form>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Your Blood Requests</h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($requests)): ?>
                                <div class="alert alert-info">
                                    You haven't created any blood donation requests yet.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Blood Type</th>
                                                <th>Units</th>
                                                <th>Hospital</th>
                                                <th>Status</th>
                                                <th>Donors</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($requests as $request): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($request['created_at'])); ?></td>
                                                    <td><?php echo $request['blood_type']; ?></td>
                                                    <td><?php echo $request['units_needed']; ?></td>
                                                    <td><?php echo htmlspecialchars($request['hospital']); ?></td>
                                                    <td>
                                                        <?php
                                                        $status_class = 'badge-primary';
                                                        if ($request['status'] === 'fulfilled') {
                                                            $status_class = 'badge-success';
                                                        } elseif ($request['status'] === 'closed') {
                                                            $status_class = 'badge-danger';
                                                        }
                                                        ?>
                                                        <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($request['status']); ?></span>
                                                    </td>
                                                    <td><?php echo $request['donation_count']; ?> donors</td>
                                                    <td>
                                                        <a href="view_request.php?id=<?php echo $request['id']; ?>" class="btn btn-sm btn-outline">View</a>
                                                        
                                                        <?php if ($request['status'] === 'open'): ?>
                                                        <a href="close_request.php?id=<?php echo $request['id']; ?>" class="btn btn-sm btn-danger ml-1" onclick="return confirm('Are you sure you want to close this request?')">Close</a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
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
