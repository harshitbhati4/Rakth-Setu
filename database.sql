
-- Create the database
CREATE DATABASE IF NOT EXISTS rakth_setu;
USE rakth_setu;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'),
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    is_donor BOOLEAN DEFAULT FALSE,
    last_donation_date DATE,
    date_of_birth DATE,
    gender ENUM('Male', 'Female', 'Other'),
    weight FLOAT,
    remember_token VARCHAR(100),
    reset_token VARCHAR(100),
    reset_token_expires DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Donation requests table
CREATE TABLE IF NOT EXISTS donation_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NOT NULL,
    units_needed INT NOT NULL DEFAULT 1,
    hospital VARCHAR(255),
    location VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    urgency ENUM('normal', 'urgent', 'critical') DEFAULT 'normal',
    patient_name VARCHAR(255),
    contact_phone VARCHAR(20),
    additional_info TEXT,
    status ENUM('open', 'fulfilled', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Donations table
CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT,
    request_id INT,
    donation_date DATETIME,
    status ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (request_id) REFERENCES donation_requests(id) ON DELETE CASCADE
);

-- Blood inventory table
CREATE TABLE IF NOT EXISTS blood_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') NOT NULL,
    units_available INT NOT NULL DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert initial blood inventory records
INSERT INTO blood_inventory (blood_type, units_available) VALUES
('A+', 10),
('A-', 5),
('B+', 8),
('B-', 4),
('AB+', 3),
('AB-', 2),
('O+', 15),
('O-', 7);

-- Messages table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT,
    receiver_id INT,
    request_id INT,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (request_id) REFERENCES donation_requests(id) ON DELETE CASCADE
);

-- Testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    content TEXT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    is_published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- FAQ table
CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    category VARCHAR(50),
    display_order INT DEFAULT 0
);

-- Insert sample FAQs
INSERT INTO faqs (question, answer, category, display_order) VALUES
('What is blood donation?', 'Blood donation is a voluntary procedure where a person agrees to have blood drawn for use in medical procedures or treatments for others.', 'General', 1),
('Who can donate blood?', 'Generally, donors must be at least 18 years old, weigh at least 45kg, and be in good health. Specific requirements may vary.', 'Eligibility', 2),
('How often can I donate blood?', 'You can donate whole blood every 12 weeks (3 months). Plasma can be donated every 2-3 weeks.', 'Process', 3),
('Is blood donation safe?', 'Yes, blood donation is very safe. New, sterile equipment is used for each donor and the process is conducted by trained professionals.', 'Safety', 4),
('How long does the donation process take?', 'The actual blood donation takes about 8-10 minutes. The entire process, including registration and health screening, takes about 1 hour.', 'Process', 5);

-- Create admin user for the site
INSERT INTO users (name, email, password, is_donor) VALUES
('Admin User', 'admin@rakthsetu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0);
