<style>
    /* Conteneur pour empiler les notifications en haut au centre */
    .flashMessages-container {
        position: fixed;
        top: 10px;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        z-index: 9999;
        width: 350px;
        max-width: 90%;
        pointer-events: none;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Animation et ajustements pour w3.css */
    .flashMessages {
        pointer-events: auto;
        margin-bottom: 10px !important;
        animation: slide-down 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        transition: opacity 0.4s, transform 0.4s;
        width: 100%;
    }

    .flashMessages.hide {
        opacity: 0;
        transform: translateY(-20px);
    }

    @keyframes slide-down {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .flashMessages.w3-panel {
        margin-top: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 16px;
    }
</style>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Affiche une notification avec l'icône, la couleur et l'animation associées 
 * au type de notification ('confirm' pour un succès, 'errors' pour une erreur).
 *
 * @param string $message Le message à afficher dans la notification.
 * @param string $type Le type de la notification ('confirm' ou 'errors'). Par défaut 'errors'.
 * @return void (fait un echo de html) 
 */
function renderAlert($message, $type = 'errors') {
    $icons = [
        'errors'  => '💥',
        'confirm' => '✅'
    ];

    $icon = $icons[$type];

    $colors = [
        'errors'  => 'w3-red',
        'confirm' => 'w3-green'
    ];

    $color = $colors[$type];
    
    echo "
    <div class='flashMessages w3-panel {$color} w3-card-4 w3-display-container w3-round' data-type='{$type}'>
        <div style='padding-right: 30px;'>
            <span style='font-size: 18px; margin-right: 8px;'>{$icon}</span>
            <span>" . htmlspecialchars($message) . "</span>
        </div>
        <span class='w3-button w3-display-topright w3-transparent w3-hover-none' 
                onclick='closeNotif(this)' style='padding: 8px 12px;'>&times;</span>
    </div>";
}


/**
 * Affiche tous les messages flash stockés dans la session.
 * 
 * Parcours la variable de session $_SESSION['mesgs'], affiche chaque message
 * via la fonction renderAlert() et vide ensuite les messages de la session
 * pour éviter qu'ils ne s'affichent à nouveau lors de la prochaine requête.
 *
 * @return void
 */
function displayFlashMessages() {
    if (isset($_SESSION['mesgs']) && !empty($_SESSION['mesgs'])) {
        echo '<div class="flashMessages-container">';
        foreach ($_SESSION['mesgs'] as $type => $messages) {
            foreach ((array)$messages as $msg) {
                renderAlert($msg, $type);
            }
        }
        echo '</div>';
        unset($_SESSION['mesgs']); 
    }
}


displayFlashMessages();
?>

<script>
    
    function closeNotif(el) {
        const notif = el.closest('.flashMessages');
        notif.classList.add('hide');
        setTimeout(() => notif.remove(), 400);
    }

    // Auto-suppression uniquement pour les message de type "confirm" après 2 secondes
    function setupAutoClose() {
        document.querySelectorAll('.flashMessages').forEach((notif, index) => {
            const type = notif.getAttribute('data-type');
            if (type === 'confirm') {
                if (notif.closest('.flashMessages-container:not([style*="static"])')) {
                    setTimeout(() => {
                        if (notif && notif.parentNode) {
                            notif.classList.add('hide');
                            setTimeout(() => notif.remove(), 400);
                        }
                    }, 2000 + (index * 400));
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', setupAutoClose);
    setTimeout(setupAutoClose, 300);
</script> 