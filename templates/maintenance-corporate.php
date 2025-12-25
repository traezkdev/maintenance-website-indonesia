<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo htmlspecialchars($title); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --corp-bg: #f8fafc;
            --corp-surface: #ffffff;
            --corp-primary: #0f172a;
            --corp-accent: #3b82f6;
            --corp-text: #334155;
            --corp-text-light: #64748b;
            --corp-border: #e2e8f0;
            --corp-radius: 1px; /* More square for corporate feel */
        }

        /* Base Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--corp-bg);
            color: var(--corp-text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        /* Abstract Business Background */
        .bg-pattern {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background-image: 
                linear-gradient(rgba(15, 23, 42, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 23, 42, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        
        /* Two-Column Layout Container */
        .container {
            width: 90%;
            max-width: 1100px;
            background: var(--corp-surface);
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--corp-border);
            overflow: hidden;
            border-radius: 4px; /* Subtle radius */
        }

        /* Left Content Side */
        .content-side {
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Right Visual Side */
        .visual-side {
            background: var(--corp-primary);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 2rem;
        }

        .visual-side::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.05) 40%, rgba(255,255,255,0.05) 60%, transparent 60%);
            animation: shine 8s infinite linear;
        }

        /* Typography & Elements */
        .brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #eff6ff;
            color: var(--corp-accent);
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2rem;
            width: fit-content;
        }

        h1 {
            font-size: 2.75rem;
            font-weight: 700;
            color: var(--corp-primary);
            margin-bottom: 1.5rem;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .message {
            font-size: 1.1rem;
            color: var(--corp-text-light);
            margin-bottom: 3rem;
            max-width: 90%;
        }

        /* Countdown Corporate Style */
        .countdown-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 3rem;
            border-top: 1px solid var(--corp-border);
            padding-top: 2rem;
        }

        .countdown-unit {
            text-align: left;
        }

        .countdown-value {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--corp-primary);
            line-height: 1;
            margin-bottom: 0.25rem;
            font-variant-numeric: tabular-nums;
        }

        .countdown-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--corp-text-light);
        }

        /* Progress Bar */
        .status-container {
            margin-bottom: 2rem;
        }
        
        .status-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
        }

        .progress-track {
            height: 6px;
            background: var(--corp-border);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: var(--corp-accent);
            width: 0;
            transition: width 1.5s ease-out;
        }

        /* Footer / Contact */
        .footer-info {
            margin-top: auto;
            border-top: 1px solid var(--corp-border);
            padding-top: 1.5rem;
            font-size: 0.875rem;
            color: var(--corp-text-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .contact-link {
            color: var(--corp-primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        
        .contact-link:hover {
            color: var(--corp-accent);
        }

        /* Right Side Content */
        .visual-content {
            text-align: center;
            z-index: 1;
        }

        .visual-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            display: inline-block;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
            animation: float 6s ease-in-out infinite;
        }

        /* Loading Dots */
        .loading-dots span {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: rgba(255,255,255,0.4);
            border-radius: 50%;
            margin: 0 4px;
            animation: dots 1.4s infinite ease-in-out both;
        }
        .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
        .loading-dots span:nth-child(2) { animation-delay: -0.16s; }

        @keyframes dots {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%); }
            100% { transform: translateX(100%) translateY(100%); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Mobile Responsive */
        @media (max-width: 850px) {
            .container {
                grid-template-columns: 1fr;
                width: 100%;
                max-width: none;
                height: 100vh;
                border: none;
                border-radius: 0;
            }
            .visual-side {
                display: none; /* Hide visual on mobile for focus */
            }
            .content-side {
                padding: 2rem;
                justify-content: flex-start;
            }
            h1 { font-size: 2rem; }
            .countdown-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 2rem 1rem;
            }
        }
    </style>
</head>
<body>

    <div class="bg-pattern"></div>

    <div class="container">
        <!-- Main Content (Left) -->
        <main class="content-side">
            
            <div class="brand-pill">
                <i class="fa-solid fa-building-circle-check"></i>
                System Maintenance
            </div>

            <h1><?php echo htmlspecialchars($title); ?></h1>
            
            <div class="message">
                <?php echo wpautop($message); ?>
            </div>

            <!-- Countdown -->
            <?php if (!empty($end_time) && strtotime($end_time) > time()): ?>
            <div class="countdown-grid" id="countdown-wrapper" data-end-time="<?php echo htmlspecialchars($end_time); ?>">
                <div class="countdown-unit">
                    <div class="countdown-value" id="days">00</div>
                    <div class="countdown-label">Days</div>
                </div>
                <div class="countdown-unit">
                    <div class="countdown-value" id="hours">00</div>
                    <div class="countdown-label">Hours</div>
                </div>
                <div class="countdown-unit">
                    <div class="countdown-value" id="minutes">00</div>
                    <div class="countdown-label">Minutes</div>
                </div>
                <div class="countdown-unit">
                    <div class="countdown-value" id="seconds">00</div>
                    <div class="countdown-label">Seconds</div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Progress Status -->
            <div class="status-container">
                <div class="status-header">
                    <span style="color: var(--corp-primary); font-weight: 600;">Current Status</span>
                    <span><?php echo htmlspecialchars($progress_text); ?></span>
                </div>
                <div class="progress-track">
                    <div class="progress-bar" style="width: 0%"></div>
                </div>
            </div>

            <!-- Footer Contact -->
            <footer class="footer-info">
                <div>
                   <?php echo htmlspecialchars($footer_text); ?>
                </div>
                <div style="display: flex; gap: 15px;">
                    <?php if (!empty($contact_email)): ?>
                    <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>" class="contact-link">Email Us</a>
                    <?php endif; ?>
                    <?php if (!empty($contact_phone)): ?>
                    <a href="tel:<?php echo htmlspecialchars($contact_phone); ?>" class="contact-link">Call Support</a>
                    <?php endif; ?>
                </div>
            </footer>
        </main>

        <!-- Visual Side (Right) -->
        <aside class="visual-side">
            <div class="visual-content">
                <div class="visual-icon">
                    <?php echo !empty($maintenance_icon) ? htmlspecialchars($maintenance_icon) : '🏢'; ?>
                </div>
                <h3 style="margin-top: 20px; font-weight: 500; font-size: 1.25rem;">We'll be back shortly</h3>
                <div class="loading-dots" style="margin-top: 15px;">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </aside>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate Progress Bar to 80% (Simulation)
        setTimeout(() => {
            const bar = document.querySelector('.progress-bar');
            if(bar) bar.style.width = '80%'; 
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
