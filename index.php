
<?php
$page_title = "Home";
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Get current blood inventory
$inventory = getBloodInventory();

include __DIR__ . '/includes/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="hero-content">
                        <div class="badge-container">
                            <div class="hero-badge">
                                <i class="droplet-icon"></i>
                                <span>Every drop matters</span>
                            </div>
                        </div>
                        <h1 class="hero-title">Connect to save <span class="text-primary">lives</span> through blood donation</h1>
                        <p class="hero-subtitle">
                            Join our community of donors and recipients to make blood donation accessible, efficient, and life-saving.
                        </p>
                        <div class="hero-buttons">
                            <a href="register.php?type=donor" class="btn btn-primary btn-lg">Become a Donor</a>
                            <a href="find-donor.php" class="btn btn-outline btn-lg">Find a Donor</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="cards-container">
                        <!-- Blood Stats Card -->
                        <div class="stats-card">
                            <div class="card-header">
                                <h3>Blood Stats</h3>
                                <i class="users-icon"></i>
                            </div>
                            <div class="card-content">
                                <?php foreach ($inventory as $item): ?>
                                    <?php if (in_array($item['blood_type'], ['A+', 'O+', 'B+'])): ?>
                                        <div class="blood-stat">
                                            <div class="blood-type-row">
                                                <span class="blood-type"><?php echo $item['blood_type']; ?></span>
                                                <span class="blood-percentage">
                                                    <?php 
                                                        $percentage = 0;
                                                        if ($item['blood_type'] == 'A+') $percentage = 27;
                                                        else if ($item['blood_type'] == 'O+') $percentage = 39;
                                                        else if ($item['blood_type'] == 'B+') $percentage = 25;
                                                        echo $percentage . '%'; 
                                                    ?>
                                                </span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: <?php echo $percentage; ?>%"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Nearest Center Card -->
                        <div class="center-card">
                            <div class="card-header">
                                <h3>Nearest Center</h3>
                                <i class="map-icon"></i>
                            </div>
                            <div class="card-content">
                                <p class="center-name">City Blood Bank</p>
                                <p class="center-address">123 Main St, Downtown</p>
                                <div class="center-distance">2.5 km away</div>
                            </div>
                        </div>
                        
                        <!-- Blood Type Card -->
                        <div class="blood-type-card">
                            <div class="blood-drop">
                                <span class="blood-type-label">A+</span>
                            </div>
                            <div class="card-content text-center">
                                <h3>Your blood type can save lives</h3>
                                <p>One donation can save up to three lives</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
