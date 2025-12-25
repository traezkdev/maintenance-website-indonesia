<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo htmlspecialchars($title); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Konkhmer+Sleokchher&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --wood-dark: #5D4037;
            --wood-light: #8D6E63;
            --gold: #FFD700;
            --red-cloth: #B71C1C;
            --bg-pattern: #FFF3E0;
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-pattern);
            color: #3E2723;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Batik Mega Mendung Background (West Java - Home of Wayang Golek) */
        .batik-bg {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -2;
            opacity: 0.1;
            background-image: repeating-linear-gradient(45deg, #FFCC80 0px, #FFCC80 10px, transparent 10px, transparent 20px),
                              repeating-linear-gradient(-45deg, #FFE0B2 0px, #FFE0B2 10px, transparent 10px, transparent 20px);
        }

        /* Curtains (Gorden Panggung) */
        .curtain {
            position: fixed;
            top: 0;
            width: 50%;
            height: 100%;
            background: var(--red-cloth);
            z-index: -1;
            box-shadow: 10px 0 20px rgba(0,0,0,0.3);
            transition: all 1s ease-out;
        }
        .curtain-left { left: 0; transform: translateX(-80%); border-right: 5px solid #800; border-bottom-right-radius: 50% 20%; }
        .curtain-right { right: 0; transform: translateX(80%); border-left: 5px solid #800; border-bottom-left-radius: 50% 20%; }

        /* Container - Panggung Kayu */
        .stage-container {
            width: 100%;
            max-width: 700px;
            background: #FFF8E1;
            border: 8px solid var(--wood-dark);
            border-radius: 12px;
            box-shadow: 
                0 0 0 4px var(--gold),
                0 20px 50px rgba(0,0,0,0.3);
            text-align: center;
            padding: 3rem 2rem;
            position: relative;
            z-index: 10;
        }

        /* Decorative Corners */
        .corner {
            position: absolute;
            width: 40px; height: 40px;
            border: 4px solid var(--gold);
            z-index: 11;
        }
        .tl { top: 10px; left: 10px; border-right: none; border-bottom: none; }
        .tr { top: 10px; right: 10px; border-left: none; border-bottom: none; }
        .bl { bottom: 10px; left: 10px; border-right: none; border-top: none; }
        .br { bottom: 10px; right: 10px; border-left: none; border-top: none; }

        /* Wayang Golek SVG Animation */
        .wayang-wrapper {
            height: 180px;
            margin-bottom: 2rem;
            position: relative;
            display: flex;
            justify-content: center;
        }

        .wayang-svg {
            height: 100%;
            /* Animasi Goyang Wayang */
            transform-origin: bottom center;
            animation: wayangRock 3s ease-in-out infinite alternate;
            filter: drop-shadow(0 10px 10px rgba(0,0,0,0.2));
        }
        
        /* Head Animation specifically */
        .wayang-head {
            transform-origin: 50% 80%;
            animation: headBob 2s ease-in-out infinite alternate;
        }

        /* Typography */
        h1 {
            font-family: 'Konkhmer Sleokchher', cursive; /* Traditional look */
            font-size: 2.25rem;
            color: var(--wood-dark);
            margin-bottom: 1rem;
            text-shadow: 1px 1px 0px rgba(255,255,255,0.5);
        }

        .message {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        /* Countdown */
        .wood-countdown {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .wood-box {
            background: var(--wood-dark);
            color: var(--gold);
            padding: 10px 15px;
            border-radius: 6px;
            min-width: 70px;
            border: 2px solid #3E2723;
        }
        
        .c-val { font-size: 1.5rem; font-weight: bold; display: block; }
        .c-lbl { font-size: 0.7rem; text-transform: uppercase; color: #D7CCC8; }

        /* Progress Bar */
        .bamboo-progress {
            margin: 2rem auto;
            position: relative;
            height: 16px;
            background: #D7CCC8;
            border-radius: 10px;
            border: 2px solid var(--wood-dark);
            overflow: hidden;
            width: 80%;
        }

        .bamboo-fill {
            height: 100%;
            background: linear-gradient(90deg, #66BB6A 0%, #388E3C 100%);
            width: 0;
            border-radius: 8px;
            position: relative;
        }
        
        /* Contact Button */
        .btn-wayang {
            background: var(--red-cloth);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 2px solid var(--gold);
            transition: all 0.3s;
        }
        
        .btn-wayang:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(183, 28, 28, 0.4);
        }

        /* Animations */
        @keyframes wayangRock {
            0% { transform: rotate(-5deg); }
            100% { transform: rotate(5deg); }
        }

        @keyframes headBob {
            0% { transform: rotate(-10deg); }
            100% { transform: rotate(10deg); }
        }

    </style>
</head>
<body>

    <div class="batik-bg"></div>
    <div class="curtain curtain-left"></div>
    <div class="curtain curtain-right"></div>

    <div class="stage-container">
        <!-- Decoration Corners -->
        <div class="corner tl"></div>
        <div class="corner tr"></div>
        <div class="corner bl"></div>
        <div class="corner br"></div>

        <!-- Wayang Golek Illustration (SVG) -> Cepot Style -->
        <div class="wayang-wrapper">
            <svg class="wayang-svg" viewBox="0 0 200 300" xmlns="http://www.w3.org/2000/svg">
                <!-- Body / Shirt -->
                <path d="M60,180 Q100,220 140,180 L160,280 Q100,300 40,280 Z" fill="#333" stroke="#000" stroke-width="2"/>
                <!-- Neck/Collar -->
                <rect x="85" y="160" width="30" height="20" fill="#D7CCC8"/>
                
                <!-- HEAD GROUP (Animated) -->
                <g class="wayang-head">
                    <!-- Face Base (Red for Cepot) -->
                    <path d="M60,80 Q100,10 140,80 Q160,130 140,160 Q100,180 60,160 Q40,130 60,80 Z" fill="#E53935" stroke="#870000" stroke-width="2"/>
                    <!-- Eyes -->
                    <circle cx="80" cy="110" r="10" fill="white" stroke="black" stroke-width="2"/>
                    <circle cx="80" cy="110" r="3" fill="black"/>
                    <circle cx="120" cy="110" r="10" fill="white" stroke="black" stroke-width="2"/>
                    <circle cx="120" cy="110" r="3" fill="black"/>
                    <!-- Nose -->
                    <path d="M95,120 L105,120 L100,140 Z" fill="#D7CCC8"/>
                    <!-- Mouth (Tooth) -->
                    <path d="M80,145 Q100,165 120,145" fill="none" stroke="black" stroke-width="3"/>
                    <rect x="95" y="145" width="10" height="10" fill="white" stroke="black"/>
                    <!-- Headdress (Iket/Batik) -->
                    <path d="M50,80 Q100,-20 150,80 L140,90 Q100,20 60,90 Z" fill="#5D4037"/>
                    <circle cx="100" cy="40" r="10" fill="gold"/>
                </g>
                
                <!-- Arms (Static for now, implies puppet rods) -->
                <line x1="40" y1="280" x2="20" y2="200" stroke="#8D6E63" stroke-width="4"/>
                <circle cx="20" cy="200" r="10" fill="#E53935"/>
                <line x1="160" y1="280" x2="180" y2="200" stroke="#8D6E63" stroke-width="4"/>
                <circle cx="180" cy="200" r="10" fill="#E53935"/>
            </svg>
        </div>

        <h1><?php echo htmlspecialchars($title); ?></h1>
        
        <div class="message">
            <?php echo wpautop($message); ?>
        </div>

        <?php if (!empty($end_time) && strtotime($end_time) > time()): ?>
        <div class="wood-countdown" id="countdown-wrapper" data-end-time="<?php echo htmlspecialchars($end_time); ?>">
            <div class="wood-box">
                <span class="c-val" id="days">00</span>
                <span class="c-lbl">Hari</span>
            </div>
            <div class="wood-box">
                <span class="c-val" id="hours">00</span>
                <span class="c-lbl">Jam</span>
            </div>
            <div class="wood-box">
                <span class="c-val" id="minutes">00</span>
                <span class="c-lbl">Menit</span>
            </div>
            <div class="wood-box">
                <span class="c-val" id="seconds">00</span>
                <span class="c-lbl">Detik</span>
            </div>
        </div>
        <?php endif; ?>

        <div class="bamboo-progress">
            <div class="bamboo-fill"></div>
        </div>
        <p style="font-size: 0.9rem; margin-bottom: 2rem; color: #5D4037;">
            <?php echo htmlspecialchars($progress_text); ?>
        </p>

        <div style="margin-bottom: 20px;">
            <?php if (!empty($contact_email)): ?>
            <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>" class="btn-wayang">
                <i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($contact_text); ?>
            </a>
            <?php endif; ?>
        </div>

        <div style="font-size: 0.8rem; color: #8D6E63; border-top: 1px dashed #8D6E63; padding-top: 20px;">
            <?php echo htmlspecialchars($footer_text); ?>
        </div>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate fill
        setTimeout(() => {
            const fill = document.querySelector('.bamboo-fill');
            if(fill) fill.style.width = '60%'; 
        }, 500);

        // Simple Countdown
        const wrapper = document.getElementById('countdown-wrapper');
        if (wrapper) {
            const endTime = new Date(wrapper.dataset.endTime).getTime();
            setInterval(() => {
                const now = new Date().getTime();
                const d = endTime - now;
                if (d < 0) return;
                document.getElementById('days').innerText = Math.floor(d / (1000 * 60 * 60 * 24)).toString().padStart(2,0);
                document.getElementById('hours').innerText = Math.floor((d % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2,0);
                document.getElementById('minutes').innerText = Math.floor((d % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2,0);
                document.getElementById('seconds').innerText = Math.floor((d % (1000 * 60)) / 1000).toString().padStart(2,0);
            }, 1000);
        }
    });
    </script>
</body>
</html>
