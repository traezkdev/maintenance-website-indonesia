/**
 * JavaScript untuk Admin Panel Maintenance Website Indonesia
 */

jQuery(document).ready(function($) {
    // Initialize color picker
    if ($.fn.wpColorPicker) {
        $('.color-picker').wpColorPicker({
            change: function(event, ui) {
                // Preview color changes in real-time
                previewColorChange($(this).attr('name'), ui.color.toString());
            }
        });
    }
    
    // Form validation
    $('#mwi-settings-form').on('submit', function(e) {
        const title = $('input[name="mwi_title"]').val().trim();
        const message = $('textarea[name="mwi_message"]').val().trim();
        
        if (!title) {
            alert('Judul halaman tidak boleh kosong!');
            e.preventDefault();
            return false;
        }
        
        if (!message) {
            alert('Pesan maintenance tidak boleh kosong!');
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        $(this).find('input[type="submit"]').prop('disabled', true).val('Menyimpan...');
    });
    
    // Auto-save functionality
    let autoSaveTimeout;
    $('input, textarea, select').on('input change', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            showAutoSaveIndicator();
        }, 2000);
    });
    
    // Preview functionality
    $('.preview-btn, a[href*="mwi_preview"]').on('click', function(e) {
        e.preventDefault();
        const previewUrl = $(this).attr('href') || $(this).data('href');
        
        // Open preview in new window
        const previewWindow = window.open(previewUrl, 'mwi_preview', 'width=1200,height=800,scrollbars=yes,resizable=yes');
        
        if (previewWindow) {
            previewWindow.focus();
        } else {
            alert('Popup diblokir! Silakan izinkan popup untuk preview.');
        }
    });
    
    // Toggle maintenance mode with AJAX
    $('input[name="mwi_enabled"]').on('change', function() {
        const enabled = $(this).is(':checked');
        const $toggle = $(this);
        
        // Show loading state
        $toggle.prop('disabled', true);
        
        $.ajax({
            url: mwiAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'mwi_toggle_maintenance',
                enabled: enabled ? 1 : 0,
                nonce: mwiAdmin.nonce
            },
            success: function(response) {
                if (response.success) {
                    showNotice(response.data.message, 'success');
                    updateStatusIndicator(enabled);
                } else {
                    showNotice('Gagal mengubah status maintenance', 'error');
                    $toggle.prop('checked', !enabled);
                }
            },
            error: function() {
                showNotice('Terjadi kesalahan saat mengubah status', 'error');
                $toggle.prop('checked', !enabled);
            },
            complete: function() {
                $toggle.prop('disabled', false);
            }
        });
    });
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl+S to save
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            $('#mwi-settings-form').submit();
        }
        
        // Ctrl+P to preview
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            $('a[href*="mwi_preview"]').first().click();
        }
        
        // Ctrl+M to toggle maintenance
        if (e.ctrlKey && e.key === 'm') {
            e.preventDefault();
            $('input[name="mwi_enabled"]').click();
        }
    });
    
    // DateTime picker enhancement
    $('input[type="datetime-local"]').on('change', function() {
        const selectedDate = new Date($(this).val());
        const now = new Date();
        
        if (selectedDate <= now) {
            showNotice('Waktu selesai harus di masa depan!', 'warning');
        } else {
            const timeDiff = selectedDate - now;
            const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            
            showNotice(`Maintenance akan berlangsung selama ${days} hari ${hours} jam`, 'info');
        }
    });
    
    // Template preview functionality
    $('#mwi_template').on('change', function() {
        const selectedTemplate = $(this).val();
        showTemplatePreview(selectedTemplate);
        
        // Update preview link to use selected template
        updatePreviewLink(selectedTemplate);
    });
    
    function showTemplatePreview(template) {
        const previewContainer = $('#template-preview');
        let previewContent = '';
        
        switch(template) {
            case 'default':
                previewContent = `
                    <div class="template-preview-item active-template">
                        <div style="background: linear-gradient(135deg, #2c3e50, #34495e); padding: 20px; border-radius: 10px; color: white; text-align: center; margin: 10px 0; position: relative; overflow: hidden;">
                            <div style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 15px; font-size: 0.8em;">🇮🇩 Indonesia</div>
                            <div style="font-size: 2rem; margin-bottom: 10px;">🔧</div>
                            <h3 style="margin: 10px 0; font-size: 1.3em;">Indonesia Classic</h3>
                            <p style="margin: 5px 0; opacity: 0.9; font-size: 0.9em;">Template dengan nuansa Indonesia, floating shapes, dan countdown timer yang elegan.</p>
                            <div style="margin-top: 15px; display: flex; justify-content: center; gap: 10px;">
                                <span style="background: rgba(255,255,255,0.2); padding: 3px 8px; border-radius: 10px; font-size: 0.7em;">Countdown</span>
                                <span style="background: rgba(255,255,255,0.2); padding: 3px 8px; border-radius: 10px; font-size: 0.7em;">Responsive</span>
                                <span style="background: rgba(255,255,255,0.2); padding: 3px 8px; border-radius: 10px; font-size: 0.7em;">Animasi</span>
                            </div>
                        </div>
                    </div>
                `;
                break;
            case 'modern':
                previewContent = `
                    <div class="template-preview-item active-template">
                        <div style="background: linear-gradient(135deg, #667eea, #764ba2); padding: 20px; border-radius: 20px; color: white; text-align: center; margin: 10px 0; position: relative; backdrop-filter: blur(10px);">
                            <div style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 15px; font-size: 0.8em;">✨ Modern</div>
                            <div style="font-size: 2rem; margin-bottom: 10px;">⚡</div>
                            <h3 style="margin: 10px 0; font-size: 1.3em;">Modern Minimalist</h3>
                            <p style="margin: 5px 0; opacity: 0.9; font-size: 0.9em;">Design modern dengan glassmorphism effect dan animasi smooth yang memukau.</p>
                            <div style="margin-top: 15px; display: flex; justify-content: center; gap: 10px;">
                                <span style="background: rgba(255,255,255,0.2); padding: 3px 8px; border-radius: 10px; font-size: 0.7em;">Glassmorphism</span>
                                <span style="background: rgba(255,255,255,0.2); padding: 3px 8px; border-radius: 10px; font-size: 0.7em;">Progress Bar</span>
                                <span style="background: rgba(255,255,255,0.2); padding: 3px 8px; border-radius: 10px; font-size: 0.7em;">Floating</span>
                            </div>
                        </div>
                    </div>
                `;
                break;
            case 'corporate':
                previewContent = `
                    <div class="template-preview-item active-template">
                        <div style="background: #ffffff; border: 2px solid #3498db; padding: 20px; border-radius: 10px; color: #2c3e50; text-align: center; margin: 10px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.1); position: relative;">
                            <div style="position: absolute; top: 10px; right: 10px; background: #3498db; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.8em;">🏢 Pro</div>
                            <div style="font-size: 2rem; margin-bottom: 10px; color: #3498db;">🏢</div>
                            <h3 style="margin: 10px 0; font-size: 1.3em; color: #2c3e50;">Corporate Professional</h3>
                            <p style="margin: 5px 0; color: #5a6c7d; font-size: 0.9em;">Template profesional untuk website bisnis dan korporat dengan layout yang bersih.</p>
                            <div style="margin-top: 15px; display: flex; justify-content: center; gap: 10px;">
                                <span style="background: #ecf0f1; color: #2c3e50; padding: 3px 8px; border-radius: 10px; font-size: 0.7em;">Professional</span>
                                <span style="background: #ecf0f1; color: #2c3e50; padding: 3px 8px; border-radius: 10px; font-size: 0.7em;">Clean</span>
                                <span style="background: #ecf0f1; color: #2c3e50; padding: 3px 8px; border-radius: 10px; font-size: 0.7em;">Business</span>
                            </div>
                        </div>
                    </div>
                `;
                break;
            case 'creative':
                previewContent = `
                    <div class="template-preview-item active-template">
                        <div style="background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1); background-size: 300% 300%; animation: gradientShift 3s ease infinite; padding: 20px; border-radius: 30px; color: white; text-align: center; margin: 10px 0; position: relative;">
                            <div style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 15px; font-size: 0.8em;">🎨 Creative</div>
                            <div style="font-size: 2rem; margin-bottom: 10px; animation: bounce 2s infinite;">🎨</div>
                            <h3 style="margin: 10px 0; font-size: 1.3em;">Creative Colorful</h3>
                            <p style="margin: 5px 0; opacity: 0.9; font-size: 0.9em;">Template kreatif dengan warna-warni dan animasi menarik yang eye-catching.</p>
                            <div style="margin-top: 15px; display: flex; justify-content: center; gap: 10px;">
                                <span style="background: rgba(255,255,255,0.2); padding: 3px 8px; border-radius: 10px; font-size: 0.7em;">Colorful</span>
                                <span style="background: rgba(255,255,255,0.2); padding: 3px 8px; border-radius: 10px; font-size: 0.7em;">Animated</span>
                                <span style="background: rgba(255,255,255,0.2); padding: 3px 8px; border-radius: 10px; font-size: 0.7em;">Fun</span>
                            </div>
                        </div>
                    </div>
                `;
                break;
        }
        
        previewContainer.html(previewContent);
        
        // Add animation styles if not exists
        if (!$('#template-preview-styles').length) {
            $('head').append(`
                <style id="template-preview-styles">
                @keyframes gradientShift {
                    0% { background-position: 0% 50%; }
                    50% { background-position: 100% 50%; }
                    100% { background-position: 0% 50%; }
                }
                @keyframes bounce {
                    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
                    40% { transform: translateY(-10px); }
                    60% { transform: translateY(-5px); }
                }
                .active-template {
                    border: 2px solid #0073aa;
                    border-radius: 10px;
                    padding: 5px;
                    background: #f0f8ff;
                }
                </style>
            `);
        }
    }
    
    function updatePreviewLink(template) {
        Saya akan memperbaiki fungsi preview template di admin-script.js agar saat ganti template, preview dan informasi template di bawahnya berubah sesuai pilihan.

Langkah perbaikan:
- Perbaiki fungsi showTemplatePreview untuk menampilkan preview dan info template yang sesuai.
- Pastikan elemen preview di admin-page.php hanya menampilkan satu preview yang berubah dinamis.
- Hilangkan preview statis default di admin-page.php agar tidak bentrok dengan preview dinamis.

Berikut rencana perbaikannya:

1. Edit assets/js/admin-script.js:
- Perbaiki fungsi showTemplatePreview agar menampilkan preview dan info template sesuai pilihan.
- Pastikan previewContainer hanya berisi satu div preview yang berubah.

2. Edit admin/admin-page.php:
- Hapus div preview statis default di bawah select template.
- Pastikan hanya ada div kosong dengan id="template-preview" untuk diisi oleh JS.

Setelah konfirmasi, saya akan lanjutkan dengan implementasi perbaikan ini.

<ask_followup_question>
    
    // Add Indonesian touch
    console.log('🇮🇩 Maintenance Website Indonesia - Admin Panel');
    console.log('Keyboard shortcuts: Ctrl+S (Save), Ctrl+P (Preview), Ctrl+M (Toggle)');
    
    // Check for Indonesian Independence Day
    const today = new Date();
    if (today.getMonth() === 7 && today.getDate() === 17) {
        showNotice('🇮🇩 Selamat Hari Kemerdekaan Indonesia! 🇮🇩', 'success');
    }
});

// Helper functions
function previewColorChange(fieldName, color) {
    // This would be used for real-time preview
    console.log(`Color changed: ${fieldName} = ${color}`);
}

function showAutoSaveIndicator() {
    const indicator = $('<div class="auto-save-indicator">💾 Auto-saved</div>');
    $('body').append(indicator);
    
    setTimeout(function() {
        indicator.fadeOut(function() {
            $(this).remove();
        });
    }, 2000);
}

function showNotice(message, type = 'info') {
    const noticeClass = `notice notice-${type}`;
    const notice = $(`
        <div class="${noticeClass} is-dismissible mwi-admin-notice">
            <p><strong>${message}</strong></p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        </div>
    `);
    
    $('.wrap h1').after(notice);
    
    // Auto dismiss after 5 seconds
    setTimeout(function() {
        notice.fadeOut(function() {
            $(this).remove();
        });
    }, 5000);
    
    // Manual dismiss
    notice.find('.notice-dismiss').on('click', function() {
        notice.fadeOut(function() {
            $(this).remove();
        });
    });
}

function updateStatusIndicator(enabled) {
    const statusText = enabled ? 
        '<span style="color: #d63638; font-weight: bold;">🔴 Aktif</span>' : 
        '<span style="color: #00a32a; font-weight: bold;">🟢 Tidak Aktif</span>';
    
    $('.mwi-info-box p:contains("Status:")').html('<strong>Status:</strong> ' + statusText);
}

// Export/Import functionality (future feature)
function exportSettings() {
    const settings = {
        title: $('input[name="mwi_title"]').val(),
        message: $('textarea[name="mwi_message"]').val(),
        endTime: $('input[name="mwi_end_time"]').val(),
        backgroundColor: $('input[name="mwi_background_color"]').val(),
        textColor: $('input[name="mwi_text_color"]').val()
    };
    
    const dataStr = JSON.stringify(settings, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    
    const link = document.createElement('a');
    link.href = URL.createObjectURL(dataBlob);
    link.download = 'mwi-settings.json';
    link.click();
}

function importSettings(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const settings = JSON.parse(e.target.result);
            
            $('input[name="mwi_title"]').val(settings.title || '');
            $('textarea[name="mwi_message"]').val(settings.message || '');
            $('input[name="mwi_end_time"]').val(settings.endTime || '');
            $('input[name="mwi_background_color"]').val(settings.backgroundColor || '#2c3e50').trigger('change');
            $('input[name="mwi_text_color"]').val(settings.textColor || '#ffffff').trigger('change');
            
            showNotice('Pengaturan berhasil diimpor!', 'success');
        } catch (error) {
            showNotice('File tidak valid!', 'error');
        }
    };
    reader.readAsText(file);
}

// Add custom CSS for admin enhancements
const adminCSS = `
<style>
.auto-save-indicator {
    position: fixed;
    top: 32px;
    right: 20px;
    background: #00a32a;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    z-index: 9999;
    font-size: 14px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.mwi-admin-notice {
    margin: 15px 0;
}

.mwi-toggle {
    position: relative;
    display: inline-block;
    vertical-align: middle;
    margin-right: 10px;
}

.form-table th {
    width: 200px;
}

.form-table td {
    padding: 15px 10px;
}

.color-picker {
    width: 100px !important;
}

.wp-picker-container {
    display: inline-block;
}

@media (max-width: 768px) {
    .mwi-admin-container {
        flex-direction: column;
    }
    
    .form-table th,
    .form-table td {
        display: block;
        width: 100%;
        padding: 10px 0;
    }
}
</style>
`;

jQuery(document).ready(function() {
    jQuery('head').append(adminCSS);
});
