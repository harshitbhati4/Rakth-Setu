
<?php
require_once 'config.php';

/**
 * Function to register a new user
 * @param array $user_data
 * @return bool|string
 */
function registerUser($user_data) {
    global $conn;
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $user_data['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return "Email already registered";
    }
    
    // Hash password
    $hashed_password = password_hash($user_data['password'], PASSWORD_DEFAULT);
    
    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, blood_type, address, city, state, is_donor, date_of_birth, gender, weight) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssisiss", 
        $user_data['name'], 
        $user_data['email'], 
        $hashed_password, 
        $user_data['phone'], 
        $user_data['blood_type'], 
        $user_data['address'], 
        $user_data['city'], 
        $user_data['state'], 
        $user_data['is_donor'], 
        $user_data['date_of_birth'],
        $user_data['gender'],
        $user_data['weight']
    );
    
    if ($stmt->execute()) {
        return true;
    } else {
        return "Registration failed: " . $conn->error;
    }
}

/**
 * Function to login a user
 * @param string $email
 * @param string $password
 * @param bool $remember
 * @return bool|string
 */
function loginUser($email, $password, $remember = false) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, name, email, password, is_donor FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['is_donor'] = $user['is_donor'];
            
            // Set remember me cookie
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expires = time() + (86400 * 30); // 30 days
                
                $stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $stmt->bind_param("si", $token, $user['id']);
                $stmt->execute();
                
                setcookie('remember_token', $token, $expires, '/');
            }
            
            return true;
        }
    }
    
    return "Invalid email or password";
}

/**
 * Function to check remember me cookie
 */
function checkRememberMe() {
    global $conn;
    
    if (isset($_COOKIE['remember_token']) && !isLoggedIn()) {
        $token = $_COOKIE['remember_token'];
        
        $stmt = $conn->prepare("SELECT id, name, email, is_donor FROM users WHERE remember_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['is_donor'] = $user['is_donor'];
        }
    }
}

/**
 * Function to log out user
 */
function logoutUser() {
    // Unset all session values
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Delete remember me cookie
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

/**
 * Function to create a donation request
 * @param array $request_data
 * @return bool|string
 */
function createDonationRequest($request_data) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO donation_requests (user_id, blood_type, units_needed, hospital, location, city, state, urgency, patient_name, contact_phone, additional_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isisssssss", 
        $request_data['user_id'], 
        $request_data['blood_type'], 
        $request_data['units_needed'], 
        $request_data['hospital'], 
        $request_data['location'], 
        $request_data['city'], 
        $request_data['state'], 
        $request_data['urgency'], 
        $request_data['patient_name'], 
        $request_data['contact_phone'], 
        $request_data['additional_info']
    );
    
    if ($stmt->execute()) {
        return true;
    } else {
        return "Failed to create request: " . $conn->error;
    }
}

/**
 * Function to get all donation requests
 * @param string $blood_type Optional filter by blood type
 * @param string $city Optional filter by city
 * @param string $state Optional filter by state
 * @return array
 */
function getDonationRequests($blood_type = null, $city = null, $state = null) {
    global $conn;
    
    $query = "SELECT * FROM donation_requests WHERE status = 'open'";
    $params = [];
    $types = "";
    
    if ($blood_type) {
        $query .= " AND blood_type = ?";
        $params[] = $blood_type;
        $types .= "s";
    }
    
    if ($city) {
        $query .= " AND city = ?";
        $params[] = $city;
        $types .= "s";
    }
    
    if ($state) {
        $query .= " AND state = ?";
        $params[] = $state;
        $types .= "s";
    }
    
    $query .= " ORDER BY urgency DESC, created_at DESC";
    
    $stmt = $conn->prepare($query);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
    
    return $requests;
}

/**
 * Function to get donation request by ID
 * @param int $request_id
 * @return array|null
 */
function getDonationRequestById($request_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM donation_requests WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    
    return null;
}

/**
 * Function to create a donation
 * @param array $donation_data
 * @return bool|string
 */
function createDonation($donation_data) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO donations (donor_id, request_id, donation_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", 
        $donation_data['donor_id'], 
        $donation_data['request_id'], 
        $donation_data['donation_date']
    );
    
    if ($stmt->execute()) {
        // Update request status if needed
        if (isset($donation_data['update_request_status']) && $donation_data['update_request_status']) {
            $request_id = $donation_data['request_id'];
            $update_stmt = $conn->prepare("UPDATE donation_requests SET status = 'fulfilled' WHERE id = ?");
            $update_stmt->bind_param("i", $request_id);
            $update_stmt->execute();
        }
        
        return true;
    } else {
        return "Failed to create donation: " . $conn->error;
    }
}

/**
 * Function to get blood compatibility information
 * @param string $blood_type
 * @return array
 */
function getBloodCompatibility($blood_type) {
    $compatibility = [
        'A+' => ['can_donate_to' => ['A+', 'AB+'], 'can_receive_from' => ['A+', 'A-', 'O+', 'O-']],
        'A-' => ['can_donate_to' => ['A+', 'A-', 'AB+', 'AB-'], 'can_receive_from' => ['A-', 'O-']],
        'B+' => ['can_donate_to' => ['B+', 'AB+'], 'can_receive_from' => ['B+', 'B-', 'O+', 'O-']],
        'B-' => ['can_donate_to' => ['B+', 'B-', 'AB+', 'AB-'], 'can_receive_from' => ['B-', 'O-']],
        'AB+' => ['can_donate_to' => ['AB+'], 'can_receive_from' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']],
        'AB-' => ['can_donate_to' => ['AB+', 'AB-'], 'can_receive_from' => ['A-', 'B-', 'AB-', 'O-']],
        'O+' => ['can_donate_to' => ['A+', 'B+', 'AB+', 'O+'], 'can_receive_from' => ['O+', 'O-']],
        'O-' => ['can_donate_to' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'], 'can_receive_from' => ['O-']]
    ];
    
    return isset($compatibility[$blood_type]) ? $compatibility[$blood_type] : null;
}

/**
 * Function to get user donations
 * @param int $user_id
 * @return array
 */
function getUserDonations($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT d.*, dr.blood_type, dr.hospital, dr.patient_name 
        FROM donations d 
        JOIN donation_requests dr ON d.request_id = dr.id 
        WHERE d.donor_id = ? 
        ORDER BY d.donation_date DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $donations = [];
    while ($row = $result->fetch_assoc()) {
        $donations[] = $row;
    }
    
    return $donations;
}

/**
 * Function to get user requests
 * @param int $user_id
 * @return array
 */
function getUserRequests($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT dr.*, 
        (SELECT COUNT(*) FROM donations d WHERE d.request_id = dr.id) as donation_count 
        FROM donation_requests dr 
        WHERE dr.user_id = ? 
        ORDER BY dr.created_at DESC
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
    
    return $requests;
}

/**
 * Function to update user profile
 * @param int $user_id
 * @param array $user_data
 * @return bool|string
 */
function updateUserProfile($user_id, $user_data) {
    global $conn;
    
    $stmt = $conn->prepare("
        UPDATE users SET 
        name = ?, 
        phone = ?, 
        blood_type = ?, 
        address = ?, 
        city = ?, 
        state = ?, 
        is_donor = ?, 
        date_of_birth = ?, 
        gender = ?, 
        weight = ? 
        WHERE id = ?
    ");
    $stmt->bind_param("ssssssissi", 
        $user_data['name'], 
        $user_data['phone'], 
        $user_data['blood_type'], 
        $user_data['address'], 
        $user_data['city'], 
        $user_data['state'], 
        $user_data['is_donor'], 
        $user_data['date_of_birth'],
        $user_data['gender'],
        $user_data['weight'],
        $user_id
    );
    
    if ($stmt->execute()) {
        return true;
    } else {
        return "Failed to update profile: " . $conn->error;
    }
}

/**
 * Function to reset password
 * @param string $email
 * @return bool|string
 */
function resetPassword($email) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE id = ?");
        $stmt->bind_param("ssi", $token, $expires, $user['id']);
        
        if ($stmt->execute()) {
            $reset_url = SITE_URL . "/reset_password.php?token=" . $token;
            $subject = "Password Reset Request - " . SITE_NAME;
            $message = "
                <html>
                <head>
                    <title>Password Reset</title>
                </head>
                <body>
                    <p>Hello,</p>
                    <p>You have requested to reset your password. Please click the link below to reset your password:</p>
                    <p><a href='$reset_url'>Reset Password</a></p>
                    <p>This link will expire in 1 hour.</p>
                    <p>If you did not request this, please ignore this email.</p>
                    <p>Regards,<br>" . SITE_NAME . " Team</p>
                </body>
                </html>
            ";
            
            if (sendEmail($email, $subject, $message)) {
                return true;
            } else {
                return "Failed to send reset email";
            }
        } else {
            return "Failed to create reset token";
        }
    }
    
    return "Email not found";
}

/**
 * Function to verify reset token
 * @param string $token
 * @return bool|array
 */
function verifyResetToken($token) {
    global $conn;
    
    $now = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > ?");
    $stmt->bind_param("ss", $token, $now);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    
    return false;
}

/**
 * Function to update password
 * @param int $user_id
 * @param string $password
 * @return bool
 */
function updatePassword($user_id, $password) {
    global $conn;
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $user_id);
    
    return $stmt->execute();
}

/**
 * Function to get featured testimonials
 * @param int $limit
 * @return array
 */
function getFeaturedTestimonials($limit = 3) {
    global $conn;
    
    $stmt = $conn->prepare("
        SELECT t.*, u.name, u.blood_type 
        FROM testimonials t 
        JOIN users u ON t.user_id = u.id 
        WHERE t.is_published = 1 
        ORDER BY t.created_at DESC 
        LIMIT ?
    ");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $testimonials = [];
    while ($row = $result->fetch_assoc()) {
        $testimonials[] = $row;
    }
    
    return $testimonials;
}

/**
 * Function to get blood inventory
 * @return array
 */
function getBloodInventory() {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM blood_inventory ORDER BY blood_type");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $inventory = [];
    while ($row = $result->fetch_assoc()) {
        $inventory[] = $row;
    }
    
    return $inventory;
}

/**
 * Function to get FAQs
 * @param string $category
 * @return array
 */
function getFAQs($category = null) {
    global $conn;
    
    $query = "SELECT * FROM faqs";
    $params = [];
    $types = "";
    
    if ($category) {
        $query .= " WHERE category = ?";
        $params[] = $category;
        $types .= "s";
    }
    
    $query .= " ORDER BY display_order ASC";
    
    $stmt = $conn->prepare($query);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $faqs = [];
    while ($row = $result->fetch_assoc()) {
        $faqs[] = $row;
    }
    
    return $faqs;
}

/**
 * Get blood donation eligibility criteria
 * @return array
 */
function getEligibilityCriteria() {
    return [
        'age' => 'You must be at least 18 years old.',
        'weight' => 'You must weigh at least 50 kg (110 lbs).',
        'health' => 'You must be in good health at the time of donation.',
        'hemoglobin' => 'Your hemoglobin level must be at least 12.5 g/dL for females and 13.0 g/dL for males.',
        'time_between_donations' => 'You must wait at least 12 weeks (84 days) between whole blood donations.',
        'diseases' => 'You should not have any blood-borne diseases or conditions that could affect your eligibility.',
        'medication' => 'Some medications may disqualify you from donating blood temporarily or permanently.',
        'recent_travel' => 'Recent travel to certain countries may temporarily defer your eligibility.'
    ];
}
