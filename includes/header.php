
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <?php if (basename($_SERVER['PHP_SELF']) === 'admin.php'): ?>
    <link rel="stylesheet" href="assets/css/admin.css">
    <?php endif; ?>
    
    <!-- Print debug information for troubleshooting -->
    <?php if (basename($_SERVER['PHP_SELF']) === 'admin.php'): ?>
    <script>
        console.log('Admin page detected');
    </script>
    <?php endif; ?>
</head>
<body>
    <!-- Debug information visible only to admins -->
    <?php if (basename($_SERVER['PHP_SELF']) === 'admin.php'): ?>
    <div style="background-color: #f8d7da; padding: 10px; margin-bottom: 10px; border: 1px solid #f5c6cb; display: <?php echo isAdmin() ? 'block' : 'none'; ?>">
        Debug info: 
        <ul>
            <li>User logged in: <?php echo isLoggedIn() ? 'Yes' : 'No'; ?></li>
            <li>User is admin: <?php echo isAdmin() ? 'Yes' : 'No'; ?></li>
            <li>Page: <?php echo basename($_SERVER['PHP_SELF']); ?></li>
        </ul>
    </div>
    <?php endif; ?>

    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="navbar-brand">
                    <span class="logo-text">Rakth Setu</span>
                </a>
                
                <button class="navbar-toggler" type="button" id="navbarToggler">
                    <span class="toggler-icon"></span>
                </button>
                
                <div class="navbar-collapse" id="navbarMenu">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="find-donor.php" class="nav-link">Find Donor</a>
                        </li>
                        <li class="nav-item">
                            <a href="about.php" class="nav-link">About</a>
                        </li>
                        <?php if (isAdmin()): ?>
                        <li class="nav-item">
                            <a href="admin.php" class="nav-link">Admin Dashboard</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    
                    <div class="navbar-buttons">
                        <?php if (isLoggedIn()): ?>
                            <?php if ($_SESSION['is_donor']): ?>
                                <a href="sender.php" class="btn btn-outline btn-sm">Donor Dashboard</a>
                            <?php else: ?>
                                <a href="receiver.php" class="btn btn-outline btn-sm">Receiver Dashboard</a>
                            <?php endif; ?>
                            <a href="profile.php" class="btn btn-outline btn-sm">Profile</a>
                            <a href="logout.php" class="btn btn-primary btn-sm">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-outline btn-sm">Login</a>
                            <a href="register.php" class="btn btn-primary btn-sm">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    
    <?php
    // Display flash messages
    $flash_types = ['success', 'error', 'info', 'warning'];
    foreach ($flash_types as $type) {
        $flash_message = getFlashMessage($type);
        if ($flash_message) {
            $class = $type === 'error' ? 'danger' : $type;
            echo '<div class="alert alert-' . $class . '">';
            echo '<div class="container">' . $flash_message['message'] . '</div>';
            echo '</div>';
        }
    }
    ?>
