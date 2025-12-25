<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo htmlspecialchars($title); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">

    <style>
        :root {
            /* Warna Modern Vibrant */
            --modern-bg: #0f172a;
            --modern-card: rgba(30, 41, 59, 0.7);
            --modern-border: rgba(148, 163, 184, 0.1);
            --modern-text: #f1f5f9;
            --modern-text-mute: #94a3b8;
            --gradient-1: #6366f1; /* Indigo */
            --gradient-2: #ec4899; /* Pink */
            --gradient-3: #8b5cf6; /* Violet */
            --glass-blur: blur(12px);
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--modern-bg);
            color: var(--modern-text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Ambient Dynamic Background */
        .ambient-light {
            position: fixed;
            top: 50%; left: 50%;
            width: 120vw; height: 120vw;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(15,23,42,0) 60%);
            transform: translate(-50%, -50%);
            z-index: -2;
            pointer-events: none;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.4;
            animation: floatOrb 10s ease-in-out infinite alternate;
        }
        .orb-1 { width: 300px; height: 300px; background: var(--gradient-1); top: 10%; left: 10%; }
        .orb-2 { width: 400px; height: 400px; background: var(--gradient-2); bottom: 10%; right: 10%; animation-delay: -5s; }

        @keyframes floatOrb {
            0% { transform: translate(0, 0); }
            100% { transform: translate(30px, 40px); }
        }

        /* Modern Typography Container */
        .modern-container {
            width: 100%;
            max-width: 768px;
            padding: 2rem;
            text-align: center;
            z-index: 1;
        }

        /* Logo / Icon Area */
        .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.1);
            font-size: 2.5rem;
            backdrop-filter: var(--glass-blur);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            transform: rotate(-5deg);
            transition: transform 0.3s ease;
        }
        
        .icon-wrapper:hover {
            transform: rotate(0deg) scale(1.05);
        }

        /* Typography */
        h1 {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.02em;
        }

        .message {
            font-size: 1.25rem;
            line-height: 1.6;
            color: var(--modern-text-mute);
            margin-bottom: 3rem;
            font-weight: 300;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Modern Grid Countdown */
        .modern-countdown {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .count-box {
            background: var(--modern-card);
            border: 1px solid var(--modern-border);
            border-radius: 16px;
            padding: 1.5rem 1rem;
            backdrop-filter: var(--glass-blur);
            transition: transform 0.3s ease;
        }

        .count-box:hover {
            transform: translateY(-5px);
            border-color: rgba(99,102,241,0.3);
        }

        .count-val {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--gradient-1), var(--gradient-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.25rem;
        }

        .count-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--modern-text-mute);
        }

        /* Minimal Progress */
        .minimal-progress {
            margin-bottom: 3rem;
            position: relative;
        }

        .progress-track {
            height: 4px;
            background: rgba(255,255,255,0.05);
            border-radius: 4px;
            overflow: hidden;
            width: 100%;
        }

        .progress-indicator {
            height: 100%;
            background: linear-gradient(90deg, var(--gradient-1), var(--gradient-2));
            width: 0;
            border-radius: 4px;
            box-shadow: 0 0 20px rgba(99,102,241,0.4);
            position: relative;
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: var(--modern-text-mute);
            margin-top: 0.75rem;
        }

        /* Action Buttons */
        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-modern {
            padding: 0.875rem 1.75rem;
            border-radius: 50px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #fff;
            color: #0f172a;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255,255,255,0.1);
        }

        .btn-outline {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .btn-outline:hover {
            background: rgba(255,255,255,0.05);
            border-color: #fff;
        }

        /* Mobile Responsive */
        @media (max-width: 640px) {
            h1 { font-size: 2.5rem; }
            .modern-countdown { gap: 0.75rem; }
            .count-val { font-size: 1.75rem; }
            .count-box { padding: 1rem 0.5rem; }
            .icon-wrapper { width: 60px; height: 60px; font-size: 1.75rem; }
        }
    </style>
</head>
<body>

    <!-- Dynamic Background -->
    <div class="ambient-light"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="modern-container">
        
        <div class="icon-wrapper">
            <?php echo !empty($maintenance_icon) ? htmlspecialchars($maintenance_icon) : '<i class="ri-tools-fill"></i>'; ?>
        </div>

        <h1><?php echo htmlspecialchars($title); ?></h1>
        
        <div class="message">
            <?php echo wpautop($message); ?>
        </div>

        <?php if (!empty($end_time) && strtotime($end_time) > time()): ?>
        <div class="modern-countdown" id="countdown-wrapper" data-end-time="<?php echo htmlspecialchars($end_time); ?>">
            <div class="count-box">
                <div class="count-val" id="days">00</div>
                <div class="count-label">Days</div>
            </div>
            <div class="count-box">
                <div class="count-val" id="hours">00</div>
                <div class="count-label">Hours</div>
            </div>
            <div class="count-box">
                <div class="count-val" id="minutes">00</div>
                <div class="count-label">Mins</div>
            </div>
            <div class="count-box">
                <div class="count-val" id="seconds">00</div>
                <div class="count-label">Secs</div>
            </div>
        </div>
        <?php endif; ?>

        <div class="minimal-progress">
            <div class="progress-track">
                <div class="progress-indicator"></div>
            </div>
            <div class="progress-info">
                <span>Progress Update</span>
                <span><?php echo htmlspecialchars($progress_text); ?></span>
            </div>
        </div>

        <div class="actions">
            <?php if (!empty($contact_email)): ?>
            <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>" class="btn-modern btn-primary">
                <i class="ri-mail-send-line"></i> Contact Us
            </a>
            <?php endif; ?>
            
            <?php if (!empty($contact_phone)): ?>
            <a href="tel:<?php echo htmlspecialchars($contact_phone); ?>" class="btn-modern btn-outline">
                <i class="ri-phone-line"></i> Support
            </a>
            <?php endif; ?>
        </div>

        <div style="margin-top: 3rem; font-size: 0.8rem; opacity: 0.5;">
            <?php echo htmlspecialchars($footer_text); ?>
        </div>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate Progress Bar
        setTimeout(() => {
            const bar = document.querySelector('.progress-indicator');
            if(bar) bar.style.width = '70%'; // Simulation value
        }, 500);

        // Countdown Logic
        const wrapper = document.getElementById('countdown-wrapper');
        if (wrapper) {
            const endTime = new Date(wrapper.dataset.endTime).getTime();
            
            const timer = setInterval(() => {
                const now = new Date().getTime();
                const distance = endTime - now;
                
                if (distance < 0) {
                    clearInterval(timer);
                    window.location.reload();
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById('days').innerText = String(days).padStart(2, '0');
                document.getElementById('hours').innerText = String(hours).padStart(2, '0');
                document.getElementById('minutes').innerText = String(minutes).padStart(2, '0');
                document.getElementById('seconds').innerText = String(seconds).padStart(2, '0');
            }, 1000);
        }
    });
    </script>
</body>
</html>
