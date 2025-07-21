
<?php
$page_title = "Find Donor";
require_once 'includes/config.php';

$blood_type = isset($_GET['blood_type']) ? sanitize($_GET['blood_type']) : '';
$city = isset($_GET['city']) ? sanitize($_GET['city']) : '';
$state = isset($_GET['state']) ? sanitize($_GET['state']) : '';

$results = [];
if ($blood_type || $city || $state) {
    $results = getDonationRequests($blood_type, $city, $state);
}

include 'includes/header.php';
?>

<main>
    <section class="hero bg-light">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">Find <span>Blood Donors</span></h1>
                <p class="hero-subtitle">
                    Search for blood donation requests or donors based on blood type and location
                </p>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3>Search Filters</h3>
                        </div>
                        <div class="card-body">
                            <form action="find-donor.php" method="get">
                                <div class="form-group">
                                    <label for="blood_type" class="form-label">Blood Type</label>
                                    <select id="blood_type" name="blood_type" class="form-control">
                                        <option value="">Any Blood Type</option>
                                        <option value="A+" <?php echo $blood_type === 'A+' ? 'selected' : ''; ?>>A+</option>
                                        <option value="A-" <?php echo $blood_type === 'A-' ? 'selected' : ''; ?>>A-</option>
                                        <option value="B+" <?php echo $blood_type === 'B+' ? 'selected' : ''; ?>>B+</option>
                                        <option value="B-" <?php echo $blood_type === 'B-' ? 'selected' : ''; ?>>B-</option>
                                        <option value="AB+" <?php echo $blood_type === 'AB+' ? 'selected' : ''; ?>>AB+</option>
                                        <option value="AB-" <?php echo $blood_type === 'AB-' ? 'selected' : ''; ?>>AB-</option>
                                        <option value="O+" <?php echo $blood_type === 'O+' ? 'selected' : ''; ?>>O+</option>
                                        <option value="O-" <?php echo $blood_type === 'O-' ? 'selected' : ''; ?>>O-</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" id="city" name="city" class="form-control" value="<?php echo htmlspecialchars($city); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" id="state" name="state" class="form-control" value="<?php echo htmlspecialchars($state); ?>">
                                </div>
                                
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    <button type="reset" class="btn btn-outline btn-block mt-2">Reset Filters</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3>Blood Compatibility</h3>
                        </div>
                        <div class="card-body">
                            <p>Select a blood type to see compatibility information:</p>
                            
                            <div class="blood-type-container mt-3">
                                <?php 
                                $blood_types = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                foreach ($blood_types as $type):
                                ?>
                                <div class="blood-type-card" data-blood-type="<?php echo $type; ?>">
                                    <div class="blood-type"><?php echo $type; ?></div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="mt-4">
                                <h4 class="mb-2">Can donate to:</h4>
                                <div id="can-donate-to" class="mb-3">
                                    <p class="text-muted">Select a blood type above</p>
                                </div>
                                
                                <h4 class="mb-2">Can receive from:</h4>
                                <div id="can-receive-from">
                                    <p class="text-muted">Select a blood type above</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3>Need Blood?</h3>
                        </div>
                        <div class="card-body">
                            <p>If you need blood donation, you can create a blood request.</p>
                            <a href="<?php echo isLoggedIn() ? 'receiver.php' : 'register.php'; ?>" class="btn btn-primary btn-block mt-3">Request Blood</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-between align-items-center">
                            <h3>Blood Donation Requests</h3>
                            <?php if (!empty($results)): ?>
                            <span class="badge bg-primary"><?php echo count($results); ?> request(s) found</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php if (empty($results) && ($blood_type || $city || $state)): ?>
                                <div class="alert alert-info">
                                    No blood donation requests found matching your criteria. Please try different search filters.
                                </div>
                            <?php elseif (empty($results)): ?>
                                <div class="text-center py-4">
                                    <svg class="icon mx-auto mb-3" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                    <h4>Find Blood Donors</h4>
                                    <p class="text-muted">Use the filters on the left to search for blood donation requests</p>
                                </div>
                            <?php else: ?>
                                <div class="donation-requests">
                                    <?php foreach ($results as $request): ?>
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
                                                    <?php if (isLoggedIn() && $_SESSION['is_donor']): ?>
                                                        <a href="donate.php?request=<?php echo $request['id']; ?>" class="btn btn-primary">Donate Blood</a>
                                                    <?php elseif (isLoggedIn()): ?>
                                                        <button class="btn btn-primary" disabled>Become a donor to help</button>
                                                    <?php else: ?>
                                                        <a href="register.php?type=donor" class="btn btn-primary">Register to Donate</a>
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
