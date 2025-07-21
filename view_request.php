
<?php
$page_title = "View Request";
require_once 'includes/config.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    setFlashMessage('error', 'Please login to view blood requests');
    redirect('login.php');
}

// Check if request ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    setFlashMessage('error', 'Invalid blood request');
    redirect('receiver.php');
}

$request_id = (int)$_GET['id'];
$request = getDonationRequestById($request_id);

// Check if request exists and belongs to user
if (!$request || $request['user_id'] != $_SESSION['user_id']) {
    setFlashMessage('error', 'You do not have permission to view this request');
    redirect('receiver.php');
}

// Get donations for this request
$stmt = $conn->prepare("
    SELECT d.*, u.name, u.phone, u.email, u.blood_type 
    FROM donations d 
    JOIN users u ON d.donor_id = u.id 
    WHERE d.request_id = ? 
    ORDER BY d.donation_date ASC
");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

$donations = [];
while ($row = $result->fetch_assoc()) {
    $donations[] = $row;
}

include 'includes/header.php';
?>

<main>
    <section class="hero bg-light">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Blood Request <span>#<?php echo $request_id; ?></span></h1>
                <p class="hero-subtitle">
                    View details and donations for your blood request
                </p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="mb-4">
                <a href="receiver.php" class="btn btn-outline">
                    <svg class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7"></path>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
            
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3>Request Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-4 text-center">
                                <div class="mx-auto mb-3" style="width: 100px; height: 100px; background-color: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 2.5rem; font-weight: bold; color: var(--primary);"><?php echo $request['blood_type']; ?></span>
                                </div>
                                
                                <?php
                                $status_class = 'badge-primary';
                                if ($request['status'] === 'fulfilled') {
                                    $status_class = 'badge-success';
                                } elseif ($request['status'] === 'closed') {
                                    $status_class = 'badge-danger';
                                }
                                ?>
                                <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($request['status']); ?></span>
                            </div>
                            
                            <div class="request-details">
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Patient:</span>
                                    <span><?php echo htmlspecialchars($request['patient_name']); ?></span>
                                </div>
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Blood Type:</span>
                                    <span><?php echo $request['blood_type']; ?></span>
                                </div>
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Units Needed:</span>
                                    <span><?php echo $request['units_needed']; ?></span>
                                </div>
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Hospital:</span>
                                    <span><?php echo htmlspecialchars($request['hospital']); ?></span>
                                </div>
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Location:</span>
                                    <span><?php echo htmlspecialchars($request['city'] . ', ' . $request['state']); ?></span>
                                </div>
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Contact:</span>
                                    <span><?php echo htmlspecialchars($request['contact_phone']); ?></span>
                                </div>
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Created:</span>
                                    <span><?php echo date('M d, Y', strtotime($request['created_at'])); ?></span>
                                </div>
                                <div class="mb-2 d-flex justify-between">
                                    <span class="font-semibold">Urgency:</span>
                                    <span><?php echo ucfirst($request['urgency']); ?></span>
                                </div>
                            </div>
                            
                            <?php if (!empty($request['additional_info'])): ?>
                            <div class="mt-4">
                                <h4 class="mb-2">Additional Information</h4>
                                <p><?php echo htmlspecialchars($request['additional_info']); ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($request['status'] === 'open'): ?>
                            <div class="mt-4">
                                <a href="close_request.php?id=<?php echo $request['id']; ?>" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to close this request?')">Close Request</a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-between align-items-center">
                            <h3>Donations</h3>
                            <span class="badge bg-primary"><?php echo count($donations); ?> donation(s)</span>
                        </div>
                        <div class="card-body">
                            <?php if (empty($donations)): ?>
                                <div class="alert alert-info">
                                    No donations have been made yet for this blood request.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Donor</th>
                                                <th>Blood Type</th>
                                                <th>Donation Date</th>
                                                <th>Status</th>
                                                <th>Contact</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($donations as $donation): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($donation['name']); ?></td>
                                                    <td><?php echo $donation['blood_type']; ?></td>
                                                    <td><?php echo date('M d, Y H:i', strtotime($donation['donation_date'])); ?></td>
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
                                                    <td>
                                                        <button class="btn btn-sm btn-outline" onclick="showDonorContact(<?php echo $donation['id']; ?>)">Show Contact</button>
                                                        
                                                        <div id="donor-contact-<?php echo $donation['id']; ?>" class="mt-2" style="display: none;">
                                                            <small><strong>Phone:</strong> <?php echo htmlspecialchars($donation['phone']); ?></small><br>
                                                            <small><strong>Email:</strong> <?php echo htmlspecialchars($donation['email']); ?></small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <?php if ($request['status'] === 'open'): ?>
                                <div class="mt-4">
                                    <p>If you've received enough blood donations, you can mark the request as fulfilled:</p>
                                    <a href="fulfill_request.php?id=<?php echo $request['id']; ?>" class="btn btn-success" onclick="return confirm('Are you sure you want to mark this request as fulfilled?')">Mark as Fulfilled</a>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
function showDonorContact(donationId) {
    const contactInfo = document.getElementById(`donor-contact-${donationId}`);
    if (contactInfo) {
        contactInfo.style.display = contactInfo.style.display === 'none' ? 'block' : 'none';
    }
}
</script>

<?php include 'includes/footer.php'; ?>
