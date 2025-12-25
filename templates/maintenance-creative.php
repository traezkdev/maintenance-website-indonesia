<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo htmlspecialchars($title); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300;400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Palette Ceria & Pastel yang Modern */
            --c-bg: #fff0f5; 
            --c-primary: #ff6b6b;  /* Coral Red */
            --c-secondary: #4ecdc4; /* Teal */
            --c-accent: #ffe66d;   /* Yellow */
            --c-text: #2d3436;
            --c-card: #ffffff;
            --blob-1: #ff9ff3;
            --blob-2: #54a0ff;
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--c-bg);
            color: var(--c-text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Abstract Organic Blobs Background */
        .blobs-container {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            filter: blur(40px);
            opacity: 0.6;
        }
        
        .blob {
            position: absolute;
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
            animation: morph 8s infinite alternate;
        }
        
        .b1 { top: -10%; left: -10%; width: 50vw; height: 50vw; background: var(--blob-1); }
        .b2 { bottom: -10%; right: -10%; width: 60vw; height: 60vw; background: var(--blob-2); animation-delay: -2s; }
        .b3 { top: 40%; left: 40%; width: 30vw; height: 30vw; background: var(--c-accent); opacity: 0.4; animation-duration: 12s; }

        /* Main Glass Card */
        .creative-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 40px;
            padding: 3.5rem 2.5rem;
            width: 100%;
            max-width: 800px;
            margin: 1.5rem;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            position: relative;
            transform-style: preserve-3d;
            animation: popIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* 3D Floating Elements */
        .float-decoration {
            position: absolute;
            font-size: 3rem;
            pointer-events: none;
            z-index: 2;
        }
        .d1 { top: -30px; left: -20px; animation: float 6s ease-in-out infinite; }
        .d2 { bottom: -20px; right: 10px; animation: float 5s ease-in-out infinite reverse; }
        .d3 { top: 30%; right: -30px; font-size: 2rem; animation: float 7s ease-in-out infinite; }

        /* Icon Container */
        .icon-box {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            display: inline-block;
            filter: drop-shadow(0 10px 0 rgba(0,0,0,0.1));
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .icon-box:hover {
            transform: scale(1.2) rotate(10deg);
        }

        /* Typography */
        h1 {
            font-family: 'Fredoka', sans-serif;
            font-size: 3rem;
            color: var(--c-text);
            margin-bottom: 1rem;
            line-height: 1.1;
        }

        .desc {
            font-size: 1.2rem;
            color: #636e72;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        /* Playful Countdown */
        .fun-countdown {
            display: inline-flex;
            gap: 15px;
            margin-bottom: 3rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .fun-box {
            background: #fff;
            padding: 15px 20px;
            border-radius: 20px;
            min-width: 90px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            border-bottom: 6px solid;
            transition: transform 0.2s;
        }
        
        .fun-box:nth-child(1) { border-color: var(--c-primary); color: var(--c-primary); transform: rotate(-3deg); }
        .fun-box:nth-child(2) { border-color: var(--c-secondary); color: var(--c-secondary); transform: rotate(2deg); }
        .fun-box:nth-child(3) { border-color: #a29bfe; color: #a29bfe; transform: rotate(-2deg); }
        .fun-box:nth-child(4) { border-color: #fab1a0; color: #fab1a0; transform: rotate(3deg); }

        .fun-box:hover { transform: scale(1.1) rotate(0deg) !important; z-index: 10; }

        .f-val { font-size: 2rem; font-weight: 800; font-family: 'Fredoka', sans-serif; display: block; }
        .f-lbl { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #b2bec3; }

        /* Chunky Progress Bar */
        .chunk-progress {
            background: #dfe6e9;
            height: 24px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 2.5rem;
            padding: 4px;
            border: 2px solid #fff;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .chunk-fill {
            height: 100%;
            background:repeating-linear-gradient(
                45deg,
                var(--c-secondary),
                var(--c-secondary) 10px,
                #81ecec 10px,
                #81ecec 20px
            );
            border-radius: 8px;
            width: 0;
            transition: 1s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Action Buttons */
        .action-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-fun {
            padding: 12px 30px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            box-shadow: 0 5px 0 rgba(0,0,0,0.1);
        }

        .btn-primary {
            background: var(--c-primary);
            color: #fff;
        }
        
        .btn-primary:active { transform: translateY(5px); box-shadow: none; }
        .btn-primary:hover { filter: brightness(110%); }

        .btn-secondary {
            background: #fff;
            color: var(--c-text);
        }
        .btn-secondary:active { transform: translateY(5px); box-shadow: none; }
        
        /* Footer */
        .footer {
            margin-top: 3rem;
            font-size: 0.9rem;
            color: #b2bec3;
            font-weight: 600;
        }

        @keyframes morph {
            0% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
            100% { border-radius: 60% 40% 30% 70% / 60% 50% 40% 50%; }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes popIn {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        @media (max-width: 600px) {
            h1 { font-size: 2.2rem; }
            .fun-box { min-width: 70px; padding: 10px; }
            .f-val { font-size: 1.5rem; }
            .creative-card { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

    <!-- Blobs Background -->
    <div class="blobs-container">
        <div class="blob b1"></div>
        <div class="blob b2"></div>
        <div class="blob b3"></div>
    </div>

    <div class="creative-card">
        <!-- Floating Stickers -->
        <div class="float-decoration d1">🎨</div>
        <div class="float-decoration d2">🚀</div>
        <div class="float-decoration d3">✨</div>

        <div class="icon-box">
             <?php echo !empty($maintenance_icon) ? htmlspecialchars($maintenance_icon) : '🛠️'; ?>
        </div>

        <h1><?php echo htmlspecialchars($title); ?></h1>
        
        <div class="desc">
            <?php echo wpautop($message); ?>
        </div>

        <?php if (!empty($end_time) && strtotime($end_time) > time()): ?>
        <div class="fun-countdown" id="countdown-wrapper" data-end-time="<?php echo htmlspecialchars($end_time); ?>">
            <div class="fun-box">
                <span class="f-val" id="days">00</span>
                <span class="f-lbl">Days</span>
            </div>
            <div class="fun-box">
                <span class="f-val" id="hours">00</span>
                <span class="f-lbl">Hrs</span>
            </div>
            <div class="fun-box">
                <span class="f-val" id="minutes">00</span>
                <span class="f-lbl">Mins</span>
            </div>
            <div class="fun-box">
                <span class="f-val" id="seconds">00</span>
                <span class="f-lbl">Secs</span>
            </div>
        </div>
        <?php endif; ?>

        <div style="text-align: left; margin-bottom: 0.5rem; font-weight: 700; color: var(--c-text); font-size: 0.9rem; padding-left: 5px;">
             <?php echo htmlspecialchars($progress_text); ?>
        </div>
        <div class="chunk-progress">
            <div class="chunk-fill"></div>
        </div>

        <div class="action-group">
            <?php if (!empty($contact_email)): ?>
            <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>" class="btn-fun btn-primary">
                Contact Us
            </a>
            <?php endif; ?>
            
            <?php if (!empty($contact_phone)): ?>
            <a href="tel:<?php echo htmlspecialchars($contact_phone); ?>" class="btn-fun btn-secondary">
                Need Help?
            </a>
            <?php endif; ?>
        </div>

        <div class="footer">
            <?php echo htmlspecialchars($footer_text); ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate fill
        setTimeout(() => {
            const fill = document.querySelector('.chunk-fill');
            if(fill) fill.style.width = '85%'; 
        }, 500);

        // Countdown
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
