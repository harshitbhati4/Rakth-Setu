
<?php
$page_title = "Admin Dashboard";
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Add debugging information
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log access attempts
$log_message = date('Y-m-d H:i:s') . ' - Admin page accessed by IP: ' . $_SERVER['REMOTE_ADDR'];
if (isLoggedIn()) {
    $log_message .= ' - User ID: ' . $_SESSION['user_id'] . ' - Email: ' . $_SESSION['user_email'] ?? 'unknown';
    $log_message .= ' - Is Admin: ' . (isAdmin() ? 'Yes' : 'No');
} else {
    $log_message .= ' - Not logged in';
}
error_log($log_message);

// Check if user is logged in first
if (!isLoggedIn()) {
    setFlashMessage('error', 'You must be logged in to access the admin dashboard.');
    redirect('login.php?redirect=admin.php');
}

// Then check if user is admin
if (!isAdmin()) {
    setFlashMessage('error', 'You do not have permission to access this page.');
    redirect('index.php');
}

// Handle database operations if form submitted
$action_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Verify CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        setFlashMessage('error', 'Invalid form submission, please try again.');
        redirect('admin.php');
    }
    
    $action = sanitize($_POST['action']);
    
    // Handle different database actions
    switch ($action) {
        case 'view_table':
            $table = sanitize($_POST['table_name']);
            break;
            
        case 'execute_query':
            // Only allow SELECT queries for security
            $query = trim($_POST['query']);
            if (stripos($query, 'SELECT') !== 0) {
                $action_message = '<div class="alert alert-danger">Only SELECT queries are allowed for security reasons.</div>';
            } else {
                // Query will be executed below
            }
            break;
            
        default:
            $action_message = '<div class="alert alert-danger">Invalid action</div>';
    }
}

include __DIR__ . '/includes/header.php';
?>

<main>
    <section class="section admin-dashboard">
        <div class="container">
            <h1 class="page-title">Admin Dashboard</h1>
            
            <!-- Additional status message for troubleshooting -->
            <div class="alert alert-info">
                <p>You are logged in as an administrator. If you're seeing this message, authentication is working correctly.</p>
            </div>
            
            <div class="row">
                <div class="col-md-3">
                    <!-- Sidebar Navigation -->
                    <div class="admin-sidebar">
                        <h3>Database Management</h3>
                        <ul class="admin-menu">
                            <li><a href="#view-tables" data-toggle="tab" class="active">View Tables</a></li>
                            <li><a href="#custom-query" data-toggle="tab">Custom Query</a></li>
                            <li><a href="#statistics" data-toggle="tab">Statistics</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-9">
                    <!-- Action message display -->
                    <?php if (!empty($action_message)): ?>
                        <?php echo $action_message; ?>
                    <?php endif; ?>
                    
                    <!-- Tab content -->
                    <div class="tab-content">
                        <!-- View Tables Tab -->
                        <div id="view-tables" class="tab-pane active">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Database Tables</h3>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <input type="hidden" name="action" value="view_table">
                                        
                                        <div class="form-group">
                                            <label for="table_name">Select Table:</label>
                                            <select name="table_name" id="table_name" class="form-control">
                                                <option value="users">Users</option>
                                                <option value="donation_requests">Donation Requests</option>
                                                <option value="donations">Donations</option>
                                                <option value="blood_inventory">Blood Inventory</option>
                                                <option value="testimonials">Testimonials</option>
                                                <option value="faqs">FAQs</option>
                                            </select>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">View Table</button>
                                    </form>
                                    
                                    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'view_table'): ?>
                                        <?php
                                        $table = sanitize($_POST['table_name']);
                                        $query = "SELECT * FROM " . $table . " LIMIT 100";
                                        $result = $conn->query($query);
                                        ?>
                                        
                                        <div class="table-responsive mt-4">
                                            <h4>Table: <?php echo $table; ?></h4>
                                            <?php if ($result && $result->num_rows > 0): ?>
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <?php
                                                            $fields = $result->fetch_fields();
                                                            foreach ($fields as $field) {
                                                                echo "<th>" . $field->name . "</th>";
                                                            }
                                                            ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($row = $result->fetch_assoc()): ?>
                                                            <tr>
                                                                <?php foreach ($row as $value): ?>
                                                                    <td><?php echo htmlspecialchars($value); ?></td>
                                                                <?php endforeach; ?>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <div class="alert alert-info">No data found in table or table doesn't exist.</div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Custom Query Tab -->
                        <div id="custom-query" class="tab-pane">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Run Custom Query</h3>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning">
                                        <strong>Note:</strong> For security reasons, only SELECT queries are allowed.
                                    </div>
                                    
                                    <form method="post" action="">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <input type="hidden" name="action" value="execute_query">
                                        
                                        <div class="form-group">
                                            <label for="query">SQL Query:</label>
                                            <textarea name="query" id="query" class="form-control" rows="4" placeholder="SELECT * FROM users LIMIT 10"></textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Execute Query</button>
                                    </form>
                                    
                                    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'execute_query'): ?>
                                        <?php
                                        $query = trim($_POST['query']);
                                        if (stripos($query, 'SELECT') === 0) {
                                            $result = $conn->query($query);
                                        }
                                        ?>
                                        
                                        <div class="query-results mt-4">
                                            <h4>Query Results</h4>
                                            <?php if (isset($result) && $result && $result->num_rows > 0): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <?php
                                                                $fields = $result->fetch_fields();
                                                                foreach ($fields as $field) {
                                                                    echo "<th>" . $field->name . "</th>";
                                                                }
                                                                ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                                <tr>
                                                                    <?php foreach ($row as $value): ?>
                                                                        <td><?php echo htmlspecialchars($value); ?></td>
                                                                    <?php endforeach; ?>
                                                                </tr>
                                                            <?php endwhile; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php elseif (isset($result)): ?>
                                                <div class="alert alert-info">Query executed, but no results returned.</div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Statistics Tab -->
                        <div id="statistics" class="tab-pane">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Database Statistics</h3>
                                </div>
                                <div class="card-body">
                                    <?php
                                    // Get table counts
                                    $tables = ['users', 'donation_requests', 'donations', 'blood_inventory', 'testimonials', 'faqs'];
                                    $stats = [];
                                    
                                    foreach ($tables as $table) {
                                        $result = $conn->query("SELECT COUNT(*) as count FROM " . $table);
                                        if ($result && $row = $result->fetch_assoc()) {
                                            $stats[$table] = $row['count'];
                                        } else {
                                            $stats[$table] = 'N/A';
                                        }
                                    }
                                    ?>
                                    
                                    <div class="row">
                                        <?php foreach ($stats as $table => $count): ?>
                                            <div class="col-md-4 mb-4">
                                                <div class="stat-card">
                                                    <h4><?php echo ucfirst(str_replace('_', ' ', $table)); ?></h4>
                                                    <div class="stat-value"><?php echo $count; ?></div>
                                                    <div class="stat-label">Records</div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <h4>Quick Statistics</h4>
                                        <ul class="list-group">
                                            <?php
                                            // Get donor count
                                            $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE is_donor = 1");
                                            if ($result && $row = $result->fetch_assoc()) {
                                                echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Registered Donors
                                                    <span class="badge bg-primary rounded-pill">' . $row['count'] . '</span>
                                                </li>';
                                            }
                                            
                                            // Get open requests
                                            $result = $conn->query("SELECT COUNT(*) as count FROM donation_requests WHERE status = 'open'");
                                            if ($result && $row = $result->fetch_assoc()) {
                                                echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Open Blood Requests
                                                    <span class="badge bg-warning rounded-pill">' . $row['count'] . '</span>
                                                </li>';
                                            }
                                            
                                            // Get fulfilled requests
                                            $result = $conn->query("SELECT COUNT(*) as count FROM donation_requests WHERE status = 'fulfilled'");
                                            if ($result && $row = $result->fetch_assoc()) {
                                                echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                                                    Fulfilled Blood Requests
                                                    <span class="badge bg-success rounded-pill">' . $row['count'] . '</span>
                                                </li>';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple tab functionality
    const tabLinks = document.querySelectorAll('.admin-menu a');
    
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Hide all tab panes
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });
            
            // Remove active class from all tab links
            tabLinks.forEach(tabLink => {
                tabLink.classList.remove('active');
            });
            
            // Show the clicked tab pane
            const targetId = this.getAttribute('href').substring(1);
            document.getElementById(targetId).classList.add('active');
            
            // Add active class to the clicked tab link
            this.classList.add('active');
        });
    });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
