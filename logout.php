
<?php
require_once 'includes/config.php';

// Log out the user
logoutUser();

// Redirect to home page
setFlashMessage('success', 'You have been successfully logged out');
redirect('index.php');
