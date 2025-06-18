<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="<?php echo MWI_PLUGIN_URL; ?>assets/css/maintenance-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background-color: <?php echo htmlspecialchars($bg_color); ?>; color: <?php echo htmlspecialchars($text_color); ?>;">
    
    <div class="maintenance-container">
        <div class="maintenance-content">
            <!-- Logo atau Icon -->
            <div class="maintenance-icon">
                <?php echo htmlspecialchars($maintenance_icon); ?>
            </div>
            
            <!-- Judul -->
            <h1 class="maintenance-title">
                <?php echo htmlspecialchars($title); ?>
            </h1>
            
            <!-- Pesan -->
            <div class="maintenance-message">
                <?php echo $message; ?>
            </div>
            
            <!-- Countdown Timer -->
            <?php if (!empty($end_time) && strtotime($end_time) > time()): ?>
            <div class="countdown-container">
                <h3>Perkiraan Selesai:</h3>
                <div class="countdown-timer" data-end-time="<?php echo htmlspecialchars($end_time); ?>">
                    <div class="countdown-item">
                        <span class="countdown-number" id="days">00</span>
                        <span class="countdown-label">Hari</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number" id="hours">00</span>
                        <span class="countdown-label">Jam</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number" id="minutes">00</span>
                        <span class="countdown-label">Menit</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number" id="seconds">00</span>
                        <span class="countdown-label">Detik</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Progress Bar -->
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <p class="progress-text"><?php echo htmlspecialchars($progress_text); ?></p>
            </div>
            
            <!-- Social Media atau Contact Info -->
            <div class="contact-info">
                <p><?php echo htmlspecialchars($contact_text); ?></p>
                <div class="contact-links">
                    <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>" class="contact-link">
                        <i class="fas fa-envelope"></i>
                        Email
                    </a>
                    <a href="tel:<?php echo htmlspecialchars($contact_phone); ?>" class="contact-link">
                        <i class="fas fa-phone"></i>
                        Telepon
                    </a>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="maintenance-footer">
                <p><?php echo htmlspecialchars($footer_text); ?></p>
                <p><small>Powered by Maintenance Website Indonesia</small></p>
            </div>
        </div>
        
        <!-- Background Animation -->
        <div class="bg-animation">
            <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-4"></div>
                <div class="shape shape-5"></div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?php echo MWI_PLUGIN_URL; ?>assets/js/maintenance-script.js"></script>
    
    <script>
    // Inline script untuk memastikan countdown berjalan
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (!empty($end_time)): ?>
        const endTime = '<?php echo htmlspecialchars($end_time); ?>';
        startCountdown(endTime);
        <?php endif; ?>
        
        // Animate progress bar
        setTimeout(function() {
            const progressFill = document.querySelector('.progress-fill');
            if (progressFill) {
                progressFill.style.width = '75%';
            }
        }, 1000);
        
        // Add some Indonesian touch
        console.log('🇮🇩 Maintenance Website Indonesia - Plugin by Indonesia Developer');
    });
    
    function startCountdown(endTime) {
        const countdownTimer = setInterval(function() {
            const now = new Date().getTime();
            const end = new Date(endTime).getTime();
            const distance = end - now;
            
            if (distance < 0) {
                clearInterval(countdownTimer);
                document.querySelector('.countdown-container').innerHTML = '<h3 style="color: #2ecc71;">✅ Maintenance selesai!</h3>';
                // Auto refresh halaman setelah 5 detik
                setTimeout(function() {
                    window.location.reload();
                }, 5000);
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }, 1000);
    }
    
    // Add some interactive features
    document.addEventListener('keydown', function(e) {
        // Easter egg: Press 'M' for maintenance info
        if (e.key === 'm' || e.key === 'M') {
            console.log('🔧 Maintenance Website Indonesia Plugin');
            console.log('📧 Contact: info@<?php echo htmlspecialchars($_SERVER['HTTP_HOST']); ?>');
        }
    });
    </script>
</body>
</html>
