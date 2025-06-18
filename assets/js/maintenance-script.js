/**
 * JavaScript untuk Maintenance Website Indonesia
 */

jQuery(document).ready(function($) {
    // Inisialisasi countdown timer
    const countdownElement = $('.countdown-timer');
    if (countdownElement.length && countdownElement.data('end-time')) {
        const endTime = countdownElement.data('end-time');
        startCountdown(endTime);
    }
    
    // Animasi progress bar
    setTimeout(function() {
        $('.progress-fill').css('width', '75%');
    }, 1000);
    
    // Floating shapes animation
    createFloatingShapes();
    
    // Auto refresh jika maintenance sudah selesai
    checkMaintenanceStatus();
    
    // Console greeting
    console.log('🇮🇩 Maintenance Website Indonesia - Plugin by Indonesian Developer');
});

function startCountdown(endTime) {
    const countdownTimer = setInterval(function() {
        const now = new Date().getTime();
        const end = new Date(endTime).getTime();
        const distance = end - now;
        
        if (distance < 0) {
            clearInterval(countdownTimer);
            jQuery('.countdown-container').html('<h3 style="color: #2ecc71;">✅ Maintenance selesai!</h3>');
            // Auto refresh setelah 5 detik
            setTimeout(function() {
                window.location.reload();
            }, 5000);
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        jQuery('#days').text(days.toString().padStart(2, '0'));
        jQuery('#hours').text(hours.toString().padStart(2, '0'));
        jQuery('#minutes').text(minutes.toString().padStart(2, '0'));
        jQuery('#seconds').text(seconds.toString().padStart(2, '0'));
    }, 1000);
}

function createFloatingShapes() {
    // Tambahkan lebih banyak floating shapes secara dinamis
    const container = jQuery('.floating-shapes');
    
    for (let i = 6; i <= 10; i++) {
        const shape = jQuery('<div class="shape shape-' + i + '"></div>');
        const size = Math.random() * 100 + 50;
        const top = Math.random() * 100;
        const left = Math.random() * 100;
        const delay = Math.random() * 20;
        
        shape.css({
            width: size + 'px',
            height: size + 'px',
            top: top + '%',
            left: left + '%',
            animationDelay: '-' + delay + 's'
        });
        
        container.append(shape);
    }
}

function checkMaintenanceStatus() {
    // Cek status maintenance setiap 30 detik
    setInterval(function() {
        if (typeof maintenanceWebsiteID !== 'undefined' && maintenanceWebsiteID.ajaxUrl) {
            jQuery.ajax({
                url: maintenanceWebsiteID.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'mwi_check_status'
                },
                success: function(response) {
                    if (response.success && !response.data.enabled) {
                        // Maintenance sudah dinonaktifkan, refresh halaman
                        window.location.reload();
                    }
                }
            });
        }
    }, 30000);
}

// Tambahkan beberapa efek interaktif
jQuery(document).ready(function($) {
    // Efek hover pada contact links
    $('.contact-link').hover(
        function() {
            $(this).css('transform', 'translateY(-3px) scale(1.05)');
        },
        function() {
            $(this).css('transform', 'translateY(0) scale(1)');
        }
    );
    
    // Efek klik pada maintenance icon
    $('.maintenance-icon').click(function() {
        $(this).addClass('pulse-animation');
        setTimeout(() => {
            $(this).removeClass('pulse-animation');
        }, 1000);
    });
    
    // Indonesian touch - keyboard shortcuts
    $(document).keydown(function(e) {
        // Press 'I' for Indonesia info
        if (e.key === 'i' || e.key === 'I') {
            console.log('🇮🇩 Made with ❤️ in Indonesia');
            console.log('Plugin: Maintenance Website Indonesia');
        }
        
        // Press 'M' for maintenance info
        if (e.key === 'm' || e.key === 'M') {
            console.log('🔧 Maintenance Mode Active');
            console.log('Admin can still access the website');
        }
    });
    
    // Add Indonesian flag animation on special occasions
    const today = new Date();
    const independenceDay = new Date(today.getFullYear(), 7, 17); // August 17
    
    if (today.getMonth() === 7 && today.getDate() === 17) {
        // Independence Day special effect
        $('body').addClass('independence-day');
        $('.maintenance-title').prepend('🇮🇩 ');
        console.log('🇮🇩 Selamat Hari Kemerdekaan Indonesia! 🇮🇩');
    }
});

// CSS untuk animasi tambahan
const additionalCSS = `
<style>
.pulse-animation {
    animation: pulse 1s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.independence-day {
    background: linear-gradient(45deg, #ff0000, #ffffff, #ff0000) !important;
    animation: indonesian-flag 3s ease-in-out infinite;
}

@keyframes indonesian-flag {
    0%, 100% { filter: hue-rotate(0deg); }
    50% { filter: hue-rotate(10deg); }
}

.shape {
    transition: all 0.3s ease;
}

.shape:hover {
    transform: scale(1.2);
    opacity: 0.8;
}
</style>
`;

jQuery(document).ready(function() {
    jQuery('head').append(additionalCSS);
});

// AJAX handler untuk check status
jQuery(document).ajaxComplete(function(event, xhr, settings) {
    if (settings.data && settings.data.includes('action=mwi_check_status')) {
        console.log('🔄 Checking maintenance status...');
    }
});
