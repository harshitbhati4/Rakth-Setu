
<?php
$page_title = "Donor Dashboard";
require_once 'includes/config.php';

// Redirect if not logged in or not a donor
if (!isLoggedIn()) {
    setFlashMessage('error', 'Please login to access the donor dashboard');
    redirect('login.php');
}

if (!$_SESSION['is_donor']) {
    setFlashMessage('error', 'You are not registered as a donor');
    redirect('receiver.php');
}

// Get user data
$user = getUserById($_SESSION['user_id']);

// Get the user's donation history
$donations = getUserDonations($_SESSION['user_id']);

// Get available donation requests
$available_requests = getDonationRequests();

include 'includes/header.php';
?>

<main>
    <section class="hero bg-light">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Welcome, <span><?php echo htmlspecialchars($user['name']); ?></span></h1>
                <p class="hero-subtitle">
                    Thank you for being a blood donor. Your contribution can save lives.
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
                                <p class="text-muted">Donor since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
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
                                <?php if ($user['last_donation_date']): ?>
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Last Donation:</span>
                                    <span><?php echo date('F j, Y', strtotime($user['last_donation_date'])); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-4">
                                <a href="profile.php" class="btn btn-outline btn-block">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Donation Eligibility</h3>
                        </div>
                        <div class="card-body">
                            <?php
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
                            ?>
                            
                            <?php if ($can_donate): ?>
                            <div class="alert alert-success">
                                <strong>You are eligible to donate blood!</strong>
                                <p>Based on your profile information, you meet the basic eligibility criteria for blood donation.</p>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-warning">
                                <strong>Donation temporarily deferred</strong>
                                <p><?php echo $reason; ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mt-3">
                                <h4 class="mb-2">Blood Compatibility</h4>
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
                                    <div>
                                        <?php foreach ($compatibility['can_receive_from'] as $type): ?>
                                        <span class="blood-type-pill"><?php echo $type; ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-8">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-between align-items-center">
                            <h3>Donation Requests</h3>
                            <span class="badge bg-primary"><?php echo count($available_requests); ?> request(s) available</span>
                        </div>
                        <div class="card-body">
                            <?php if (empty($available_requests)): ?>
                                <div class="alert alert-info">
                                    No blood donation requests are available at the moment.
                                </div>
                            <?php else: ?>
                                <?php
                                // Filter requests that match user's blood type
                                $compatible_requests = [];
                                $compatibility = getBloodCompatibility($user['blood_type']);
                                
                                if ($compatibility) {
                                    foreach ($available_requests as $request) {
                                        if (in_array($request['blood_type'], $compatibility['can_donate_to'])) {
                                            $compatible_requests[] = $request;
                                        }
                                    }
                                }
                                ?>
                                
                                <?php if (empty($compatible_requests)): ?>
                                    <div class="alert alert-info">
                                        There are no compatible blood donation requests for your blood type (<?php echo $user['blood_type']; ?>) at the moment.
                                    </div>
                                <?php else: ?>
                                    <div class="donation-requests">
                                        <?php foreach ($compatible_requests as $request): ?>
                                            <div class="card mb-3 border">
                                                <div class="card-body">
                                                    <div class="d-flex justify-between align-items-start">
                                                        <div>
                                                            <h4 class="card-title mb-1">
                                                                Blood Type: <span class="text-primary"><?php echo $request['blood_type']; ?></span>
                                                            </h4>
                                                            <p class="text-muted">
                                                                <strong>Location:</strong> <?php echo htmlspecialchars($request['city'] . ', ' . $request['state']); ?>
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <?php
                                                            $badge_class = 'badge-primary';
                                                            if ($request['urgency'] === 'urgent') {
                                                                $badge_class = 'badge-warning';
                                                            } elseif ($request['urgency'] === 'critical') {
                                                                $badge_class = 'badge-danger';
                                                            }
                                                            ?>
                                                            <span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($request['urgency']); ?></span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mt-3">
                                                        <p><strong>Units Needed:</strong> <?php echo $request['units_needed']; ?></p>
                                                        <p><strong>Hospital:</strong> <?php echo htmlspecialchars($request['hospital']); ?></p>
                                                        <p><strong>Required By:</strong> <?php echo date('F j, Y', strtotime('+3 days', strtotime($request['created_at']))); ?></p>
                                                        <?php if (!empty($request['additional_info'])): ?>
                                                            <p><strong>Additional Info:</strong> <?php echo htmlspecialchars($request['additional_info']); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div class="mt-3">
                                                        <?php if ($can_donate): ?>
                                                            <a href="donate.php?request=<?php echo $request['id']; ?>" class="btn btn-primary">Donate Blood</a>
                                                        <?php else: ?>
                                                            <button class="btn btn-primary" disabled>Donation Deferred</button>
                                                            <small class="d-block mt-1 text-muted"><?php echo $reason; ?></small>
                                                        <?php endif; ?>
                                                        
                                                        <button class="btn btn-outline ml-2" onclick="showContactInfo(<?php echo $request['id']; ?>)">Contact Info</button>
                                                        
                                                        <div id="contact-info-<?php echo $request['id']; ?>" class="mt-3" style="display: none;">
                                                            <div class="alert alert-info">
                                                                <p><strong>Contact Person:</strong> <?php echo htmlspecialchars($request['patient_name']); ?></p>
                                                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($request['contact_phone']); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Your Donation History</h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($donations)): ?>
                                <div class="alert alert-info">
                                    You haven't made any blood donations yet.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Blood Type</th>
                                                <th>Hospital</th>
                                                <th>Patient</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($donations as $donation): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($donation['donation_date'])); ?></td>
                                                    <td><?php echo $donation['blood_type']; ?></td>
                                                    <td><?php echo htmlspecialchars($donation['hospital']); ?></td>
                                                    <td><?php echo htmlspecialchars($donation['patient_name']); ?></td>
                                                    <td>
                                                        <?php
                                                        $status_class = 'badge-primary';
                                                        if ($donation['status'] === 'completed') {
                                                            $status_class = 'badge-success';
                                                        } elseif ($donation['status'] === 'cancelled') {
                                                            $status_class = 'badge-danger';
                                                        }
                                                        ?>
                                                        <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($donation['status']); ?></span>
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

<script>
function showContactInfo(requestId) {
    const contactInfo = document.getElementById(`contact-info-${requestId}`);
    if (contactInfo) {
        contactInfo.style.display = contactInfo.style.display === 'none' ? 'block' : 'none';
    }
}
</script>

<?php include 'includes/footer.php'; ?>
