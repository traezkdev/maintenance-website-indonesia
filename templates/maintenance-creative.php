<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="<?php echo MWI_PLUGIN_URL; ?>assets/css/maintenance-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: <?php echo htmlspecialchars($bg_color); ?>;
            --text-color: <?php echo htmlspecialchars($text_color); ?>;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(45deg, var(--bg-color), #4ecdc4, #45b7d1, #96ceb4, #feca57);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            color: var(--text-color);
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .maintenance-container {
            max-width: 900px;
            padding: 40px;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .maintenance-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shine 3s infinite;
            pointer-events: none;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .maintenance-icon {
            font-size: 5em;
            margin-bottom: 20px;
            animation: bounce 2s infinite, colorChange 4s infinite;
            display: inline-block;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-20px); }
            60% { transform: translateY(-10px); }
        }
        
        @keyframes colorChange {
            0% { filter: hue-rotate(0deg); }
            25% { filter: hue-rotate(90deg); }
            50% { filter: hue-rotate(180deg); }
            75% { filter: hue-rotate(270deg); }
            100% { filter: hue-rotate(360deg); }
        }
        
        .maintenance-title {
            font-size: 3em;
            margin-bottom: 20px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: titleGlow 2s ease-in-out infinite alternate;
        }
        
        @keyframes titleGlow {
            from { text-shadow: 0 0 20px rgba(255, 255, 255, 0.5); }
            to { text-shadow: 0 0 30px rgba(255, 255, 255, 0.8), 0 0 40px rgba(255, 255, 255, 0.3); }
        }
        
        .maintenance-message {
            font-size: 1.3em;
            line-height: 1.6;
            margin-bottom: 40px;
            opacity: 0.95;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }
        
        .countdown-container {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin: 50px 0;
            flex-wrap: wrap;
        }
        
        .countdown-item {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            padding: 25px 20px;
            border-radius: 20px;
            min-width: 120px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transform: perspective(1000px) rotateX(0deg);
            transition: all 0.3s ease;
            animation: float 6s ease-in-out infinite;
        }
        
        .countdown-item:nth-child(1) { animation-delay: 0s; }
        .countdown-item:nth-child(2) { animation-delay: 1.5s; }
        .countdown-item:nth-child(3) { animation-delay: 3s; }
        .countdown-item:nth-child(4) { animation-delay: 4.5s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotateX(0deg); }
            50% { transform: translateY(-15px) rotateX(5deg); }
        }
        
        .countdown-item:hover {
            transform: perspective(1000px) rotateX(10deg) scale(1.05);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .countdown-number {
            font-size: 3em;
            font-weight: 700;
            display: block;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .countdown-label {
            font-size: 1em;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
        }
        
        .creative-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            overflow: hidden;
        }
        
        .floating-shape {
            position: absolute;
            opacity: 0.1;
            animation: floatAround 20s infinite linear;
        }
        
        .floating-shape:nth-child(1) {
            top: 10%;
            left: 10%;
            font-size: 2em;
            animation-duration: 25s;
        }
        
        .floating-shape:nth-child(2) {
            top: 20%;
            right: 15%;
            font-size: 1.5em;
            animation-duration: 30s;
            animation-direction: reverse;
        }
        
        .floating-shape:nth-child(3) {
            bottom: 20%;
            left: 20%;
            font-size: 2.5em;
            animation-duration: 35s;
        }
        
        .floating-shape:nth-child(4) {
            bottom: 10%;
            right: 10%;
            font-size: 1.8em;
            animation-duration: 28s;
            animation-direction: reverse;
        }
        
        @keyframes floatAround {
            0% { transform: translateX(0) translateY(0) rotate(0deg); }
            25% { transform: translateX(100px) translateY(-50px) rotate(90deg); }
            50% { transform: translateX(50px) translateY(-100px) rotate(180deg); }
            75% { transform: translateX(-50px) translateY(-50px) rotate(270deg); }
            100% { transform: translateX(0) translateY(0) rotate(360deg); }
        }
        
        .progress-container {
            margin: 40px 0;
            position: relative;
        }
        
        .progress-title {
            font-size: 1.2em;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .progress-bar {
            height: 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1);
            background-size: 200% 100%;
            animation: progressGlow 3s ease-in-out infinite;
            width: 0;
            transition: width 2s ease-in-out;
            border-radius: 20px;
        }
        
        @keyframes progressGlow {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .contact-section {
            margin-top: 50px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }
        
        .contact-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .contact-link {
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 50px;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .contact-link:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.2));
        }
        
        @media (max-width: 768px) {
            .maintenance-container {
                margin: 20px;
                padding: 30px;
            }
            
            .maintenance-title {
                font-size: 2.2em;
            }
            
            .countdown-container {
                gap: 15px;
            }
            
            .countdown-item {
                min-width: 100px;
                padding: 20px 15px;
            }
            
            .countdown-number {
                font-size: 2.2em;
            }
            
            .contact-links {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="creative-elements">
            <div class="floating-shape">🎨</div>
            <div class="floating-shape">✨</div>
            <div class="floating-shape">🌈</div>
            <div class="floating-shape">🎭</div>
        </div>
        
        <div class="maintenance-icon"><?php echo htmlspecialchars($maintenance_icon); ?></div>
        
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
            <div class="progress-title"><?php echo htmlspecialchars($progress_text); ?></div>
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
        </div>
        
        <div class="contact-section">
            <h3><?php echo htmlspecialchars($contact_text); ?></h3>
            <p><?php echo htmlspecialchars($contact_text); ?></p>
            <div class="contact-links">
                <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>" class="contact-link">
                    📧 Email
                </a>
                <a href="tel:<?php echo htmlspecialchars($contact_phone); ?>" class="contact-link">
                    📞 Call
                </a>
                <a href="#" class="contact-link">
                    💬 Chat
                </a>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate progress bar
        setTimeout(function() {
            const progressFill = document.querySelector('.progress-fill');
            if (progressFill) {
                progressFill.style.width = '80%';
            }
        }, 1500);
        
        // Add random floating elements
        createFloatingElements();
        
        // Start countdown if end time exists
        <?php if (!empty($end_time)): ?>
        const endTime = '<?php echo htmlspecialchars($end_time); ?>';
        startCountdown(endTime);
        <?php endif; ?>
    });
    
    function createFloatingElements() {
        const emojis = ['⭐', '🌟', '✨', '💫', '🎪', '🎨', '🌈', '🎭', '🎪', '🎨'];
        const container = document.body;
        
        for (let i = 0; i < 15; i++) {
            const element = document.createElement('div');
            element.textContent = emojis[Math.floor(Math.random() * emojis.length)];
            element.style.position = 'fixed';
            element.style.fontSize = Math.random() * 20 + 10 + 'px';
            element.style.left = Math.random() * 100 + '%';
            element.style.top = Math.random() * 100 + '%';
            element.style.opacity = Math.random() * 0.3 + 0.1;
            element.style.pointerEvents = 'none';
            element.style.zIndex = '-1';
            element.style.animation = `floatAround ${Math.random() * 20 + 20}s infinite linear`;
            element.style.animationDelay = Math.random() * 5 + 's';
            
            container.appendChild(element);
            
            // Remove element after animation
            setTimeout(() => {
                if (element.parentNode) {
                    element.parentNode.removeChild(element);
                }
            }, 45000);
        }
    }
    
    function startCountdown(endTime) {
        const countdownTimer = setInterval(function() {
            const now = new Date().getTime();
            const end = new Date(endTime).getTime();
            const distance = end - now;
            
            if (distance < 0) {
                clearInterval(countdownTimer);
                document.querySelector('.countdown-container').innerHTML = 
                    '<div style="background: linear-gradient(45deg, #2ecc71, #27ae60); padding: 30px; border-radius: 20px; animation: bounce 1s infinite;">' +
                    '<div style="font-size: 3em; margin-bottom: 10px;">🎉</div>' +
                    '<div style="font-size: 1.5em; font-weight: 700;">Selesai!</div>' +
                    '<div>Website akan segera kembali normal</div>' +
                    '</div>';
                
                // Create celebration effect
                createCelebration();
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
    
    function createCelebration() {
        const celebrationEmojis = ['🎉', '🎊', '✨', '🌟', '🎈', '🎁'];
        
        for (let i = 0; i < 50; i++) {
            setTimeout(() => {
                const emoji = document.createElement('div');
                emoji.textContent = celebrationEmojis[Math.floor(Math.random() * celebrationEmojis.length)];
                emoji.style.position = 'fixed';
                emoji.style.left = Math.random() * 100 + '%';
                emoji.style.top = '-50px';
                emoji.style.fontSize = Math.random() * 30 + 20 + 'px';
                emoji.style.pointerEvents = 'none';
                emoji.style.zIndex = '1000';
                emoji.style.animation = 'fall 3s linear forwards';
                
                document.body.appendChild(emoji);
                
                setTimeout(() => {
                    if (emoji.parentNode) {
                        emoji.parentNode.removeChild(emoji);
                    }
                }, 3000);
            }, i * 100);
        }
    }
    
    // Add fall animation for celebration
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fall {
            to {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    </script>
</body>
</html>
