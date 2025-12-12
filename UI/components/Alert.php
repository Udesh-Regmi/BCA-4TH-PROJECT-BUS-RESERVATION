<?php
// UI/components/Alert.php
// Popup/toast alert component that uses getAlert() from includes/functions.php

// Ensure session & helper functions are available
if (session_status() !== PHP_SESSION_ACTIVE) {
    @session_start();
}

if (!function_exists('getAlert')) {
    $possible = __DIR__ . '/../../includes/functions.php';
    if (file_exists($possible)) {
        require_once $possible;
    }
}

$alert = null;
if (function_exists('getAlert')) {
    $alert = getAlert(); // getAlert clears the session alert
}

if (!$alert) {
    return;
}

$type = isset($alert['type']) ? $alert['type'] : 'info';
$message = isset($alert['message']) ? $alert['message'] : '';
// safe output helper (use e() if available)
if (function_exists('e')) {
    $messageSafe = e($message);
} else {
    $messageSafe = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
}

$allowedTypes = ['success','danger','warning','info','primary','secondary','light','dark'];
$theme = in_array($type, $allowedTypes) ? $type : 'info';
?>
<div id="app-alert" class="app-alert app-alert-<?php echo $theme; ?>" role="status" aria-live="polite" aria-atomic="true">
    <div class="app-alert-inner">
        <div class="app-alert-icon" aria-hidden="true">
            <?php
            switch ($theme) {
                case 'success': echo '✓'; break;
                case 'danger': echo '✕'; break;
                case 'warning': echo '⚠'; break;
                case 'info': default: echo 'ℹ'; break;
            }
            ?>
        </div>
        <div class="app-alert-message"><?php echo $messageSafe; ?></div>
        <button class="app-alert-close" aria-label="Close alert">&times;</button>
    </div>
</div>

<style>
#app-alert { 
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translate(-50%, -20px);
    min-width: 320px;
    max-width: 500px;
    z-index: 999999;
    box-shadow: 0 8px 28px rgba(0,0,0,0.18);
    border-radius: 12px;
    overflow: hidden;
    opacity: 0;
    transition: transform 240ms ease, opacity 240ms ease;
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
}

/* visible state */
#app-alert.show {
    transform: translate(-50%, 0);
    opacity: 1;
}

/* Inner layout */
.app-alert-inner {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    font-weight: 500;
}

/* Icon */
.app-alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:16px;
    flex: 0 0 40px;
}

/* message */
.app-alert-message {
    flex: 1;
    font-size: 15px;
    line-height: 1.32;
}

/* close */
.app-alert-close {
    background: transparent;
    border: none;
    font-size: 22px;
    line-height: 1;
    cursor: pointer;
    color: inherit;
    padding: 6px;
    margin-left: 6px;
}

/* Theme colors */
.app-alert-success .app-alert-inner { background: #0dd175ff; color:whitesmoke; }

.app-alert-danger .app-alert-inner { background: #a00611ff; color:#bfdbfe; }

.app-alert-warning .app-alert-inner { background: #fffbeb; color:#78350f; }

.app-alert-info .app-alert-inner { background: #eff6ff; color:#1e293b; }

.app-alert-primary .app-alert-inner { background: #eef2ff; color:#3730a3; }
</style>


<script>
(function () {
    var alertEl = document.getElementById('app-alert');
    if (!alertEl) return;

    var closeBtn = alertEl.querySelector('.app-alert-close');
    // Show
    setTimeout(function () {
        alertEl.classList.add('show');
        // Move focus to close button for accessibility
        if (closeBtn) {
            closeBtn.focus({preventScroll:true});
        }
    }, 8);

    // Auto hide after 5s
    var autoHide = setTimeout(hideAlert, 5000);

    function hideAlert() {
        if (!alertEl) return;
        alertEl.classList.remove('show');
        // remove element after transition
        setTimeout(function () {
            if (alertEl && alertEl.parentNode) {
                alertEl.parentNode.removeChild(alertEl);
            }
        }, 260);
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            clearTimeout(autoHide);
            hideAlert();
        });
    }

    // Dismiss on Escape
    document.addEventListener('keydown', function (ev) {
        if (ev.key === 'Escape' || ev.key === 'Esc') {
            hideAlert();
        }
    });
})();
</script>