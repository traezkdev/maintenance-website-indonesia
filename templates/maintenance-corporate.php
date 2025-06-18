<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="<?php echo MWI_PLUGIN_URL; ?>assets/css/maintenance-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: <?php echo htmlspecialchars($bg_color); ?>;
            --text-color: <?php echo htmlspecialchars($text_color); ?>;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .maintenance-container {
            max-width: 900px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .maintenance-header {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .maintenance-icon {
            font-size: 3em;
            margin-bottom: 20px;
            color: white;
        }
        
        .maintenance-title {
            font-size: 2.2em;
            margin: 0 0 15px 0;
            font-weight: 500;
        }
        
        .maintenance-subtitle {
            font-size: 1.1em;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .maintenance-content {
            padding: 50px 40px;
            text-align: center;
        }
        
        .maintenance-message {
            font-size: 1.1em;
            line-height: 1.7;
            margin-bottom: 40px;
            color: #5a6c7d;
        }
        
        .countdown-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 40px 0;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .countdown-item {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            padding: 25px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .countdown-item:hover {
            border-color: #3498db;
            transform: translateY(-2px);
        }
        
        .countdown-number {
            font-size: 2.2em;
            font-weight: 700;
            color: #3498db;
            display: block;
            margin-bottom: 8px;
        }
        
        .countdown-label {
            font-size: 0.9em;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }
        
        .progress-section {
            background: #f8f9fa;
            padding: 30px;
            margin: 30px 0;
            border-radius: 8px;
        }
        
        .progress-title {
            font-size: 1.1em;
            margin-bottom: 15px;
            color: #2c3e50;
            font-weight: 500;
        }
        
        .progress-bar {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3498db, #2980b9);
            width: 0;
            transition: width 2s ease-in-out;
        }
        
        .progress-text {
            font-size: 0.9em;
            color: #7f8c8d;
            margin: 0;
        }
        
        .contact-section {
            background: #2c3e50;
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .contact-title {
            font-size: 1.3em;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .contact-info {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #ecf0f1;
        }
        
        .contact-item i {
            font-size: 1.2em;
            color: #3498db;
        }
        
        .contact-link {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .contact-link:hover {
            color: #5dade2;
        }
        
        .footer {
            background: #34495e;
            color: #bdc3c7;
            padding: 20px;
            text-align: center;
            font-size: 0.9em;
        }
        
        @media (max-width: 768px) {
            .maintenance-container {
                margin: 20px;
                border-radius: 0;
            }
            
            .maintenance-header,
            .maintenance-content,
            .contact-section {
                padding: 30px 20px;
            }
            
            .countdown-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .maintenance-title {
                font-size: 1.8em;
            }
            
            .contact-info {
                flex-direction: column;
                gap: 20px;
            }
        }
        
        /* Professional animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .maintenance-content > * {
            animation: slideInUp 0.6s ease-out;
        }
        
        .countdown-item:nth-child(1) { animation-delay: 0.1s; }
        .countdown-item:nth-child(2) { animation-delay: 0.2s; }
        .countdown-item:nth-child(3) { animation-delay: 0.3s; }
        .countdown-item:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-header">
            <div class="maintenance-icon"><?php echo htmlspecialchars($maintenance_icon); ?></div>
            <h1 class="maintenance-title"><?php echo htmlspecialchars($title); ?></h1>
            <p class="maintenance-subtitle"><?php echo htmlspecialchars($contact_text); ?></p>
        </div>
        
        <div class="maintenance-content">
            <div class="maintenance-message">
                <?php echo $message; ?>
            </div>
            
            <?php if (!empty($end_time) && strtotime($end_time) > time()): ?>
            <div class="countdown-container">
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
            <?php endif; ?>
            
            <div class="progress-section">
                <div class="progress-title">Status Pemeliharaan</div>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <p class="progress-text"><?php echo htmlspecialchars($progress_text); ?></p>
            </div>
        </div>
        
        <div class="contact-section">
            <h3 class="contact-title"><?php echo htmlspecialchars($contact_text); ?></h3>
            <div class="contact-info">
                <div class="contact-item">
                    <i>📧</i>
                    <span>Email: <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>" class="contact-link"><?php echo htmlspecialchars($contact_email); ?></a></span>
                </div>
                <div class="contact-item">
                    <i>📞</i>
                    <span>Telepon: <a href="tel:<?php echo htmlspecialchars($contact_phone); ?>" class="contact-link"><?php echo htmlspecialchars($contact_phone); ?></a></span>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p><?php echo htmlspecialchars($footer_text); ?></p>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate progress bar
        setTimeout(function() {
            const progressFill = document.querySelector('.progress-fill');
            if (progressFill) {
                progressFill.style.width = '65%';
            }
        }, 1000);
        
        // Start countdown if end time exists
        <?php if (!empty($end_time)): ?>
        const endTime = '<?php echo htmlspecialchars($end_time); ?>';
        startCountdown(endTime);
        <?php endif; ?>
    });
    
    function startCountdown(endTime) {
        const countdownTimer = setInterval(function() {
            const now = new Date().getTime();
            const end = new Date(endTime).getTime();
            const distance = end - now;
            
            if (distance < 0) {
                clearInterval(countdownTimer);
                document.querySelector('.countdown-container').innerHTML = 
                    '<div style="grid-column: 1 / -1; padding: 20px; background: #d4edda; color: #155724; border-radius: 8px; border: 1px solid #c3e6cb;">' +
                    '<strong>✅ Pemeliharaan telah selesai!</strong><br>' +
                    '<small>Halaman akan dimuat ulang secara otomatis dalam beberapa detik.</small>' +
                    '</div>';
                
                // Auto refresh after 5 seconds
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
    </script>
</body>
</html>
