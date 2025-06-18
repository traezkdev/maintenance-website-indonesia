<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="<?php echo MWI_PLUGIN_URL; ?>assets/css/maintenance-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: <?php echo htmlspecialchars($bg_color); ?>;
            --text-color: <?php echo htmlspecialchars($text_color); ?>;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--bg-color), #667eea);
            color: var(--text-color);
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .maintenance-container {
            max-width: 800px;
            padding: 40px;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .maintenance-title {
            font-size: 2.5em;
            margin-bottom: 20px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        
        .maintenance-message {
            font-size: 1.2em;
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .countdown-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 40px 0;
        }
        
        .countdown-item {
            background: rgba(255, 255, 255, 0.15);
            padding: 20px;
            border-radius: 15px;
            min-width: 100px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .countdown-number {
            font-size: 2.5em;
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
        }
        
        .countdown-label {
            font-size: 0.9em;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .progress-container {
            margin: 30px 0;
        }
        
        .progress-bar {
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--text-color), rgba(255, 255, 255, 0.5));
            width: 0;
            transition: width 1s ease-in-out;
        }
        
        .contact-info {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .contact-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }
        
        .contact-link {
            color: var(--text-color);
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }
        
        .contact-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .maintenance-container {
                margin: 20px;
                padding: 30px;
            }
            
            .countdown-container {
                flex-wrap: wrap;
            }
            
            .countdown-item {
                min-width: 80px;
            }
            
            .maintenance-title {
                font-size: 2em;
            }
        }
        
        /* Modern animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        .maintenance-icon {
            font-size: 4em;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--text-color), rgba(255, 255, 255, 0.8));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
    <div class="maintenance-icon floating-element"><?php echo htmlspecialchars($maintenance_icon); ?></div>
    
    <h1 class="maintenance-title">
        <?php echo htmlspecialchars($title); ?>
    </h1>
    
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
    
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
        <p class="progress-text"><?php echo htmlspecialchars($progress_text); ?></p>
    </div>
    
    <div class="contact-info">
        <p><?php echo htmlspecialchars($contact_text); ?></p>
        <div class="contact-links">
            <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>" class="contact-link">
                Email
            </a>
            <a href="tel:<?php echo htmlspecialchars($contact_phone); ?>" class="contact-link">
                Telepon
            </a>
        </div>
    </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate progress bar
        setTimeout(function() {
            document.querySelector('.progress-fill').style.width = '75%';
        }, 500);
        
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
                    '<div class="countdown-item" style="background: rgba(46, 204, 113, 0.2);">' +
                    '<span style="color: #2ecc71;">✓</span> Maintenance selesai!' +
                    '</div>';
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
