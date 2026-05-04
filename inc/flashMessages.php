<?php
/**
 * Système de Notifications Flash
 * 
 * Ce fichier gère l'affichage des messages temporaires (confirmations, erreurs)
 * stockés en session via $_SESSION['mesgs'].
 * 
 * UTILISATION :
 * - Inclure ce fichier en haut de vos scripts : require_once 'flashMessages.php';
 * - Pour les pages sans header standard, appeler manuellement la fonction 
 *   displayFlashMessages() juste après l'ouverture de la balise <body>.
 * 
 * EN PHP : 
 * - $_SESSION['mesgs']['confirm'][] pour un message confirm 
 * - $_SESSION['mesgs']['errors'][] pour un message d'erreur 
 * 
 * EN JAVASCRIPT : 
 * - renderAlertJS("Message de succès", "confirm");
 * - renderAlertJS("Message d'erreur", "errors");
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/**
 * Affiche les scripts nécessaires au fonctionnement des messages flash.
 * Cette fonction est appelée automatiquement par displayFlashMessages.
 */
function renderFlashScripts() {
    static $rendered = false;
    if ($rendered) return;
    $rendered = true;
    ?>
    <script>
        /**
         * Ferme une notification manuellement.
         * @param {HTMLElement} el L'élément (généralement un bouton) qui a déclenché la fermeture.
         */
        function closeNotif(el) {
            const notif = el.closest('.flashMessages');
            if (notif) {
                notif.classList.add('hide');
                setTimeout(() => notif.remove(), 400);
            }
        }

        /**
         * Auto-suppression uniquement pour les messages de type "confirm" après 2 secondes
         */
        function setupAutoClose() {
            document.querySelectorAll('.flashMessages').forEach((notif, index) => {
                const type = notif.getAttribute('data-type');
                if (type === 'confirm') {
                    setTimeout(() => {
                        if (notif && notif.parentNode) {
                            notif.classList.add('hide');
                            setTimeout(() => notif.remove(), 400);
                        }
                    }, 2000 + (index * 400));
                }
            });
        }

        /**
         * Affiche une notification flash depuis JavaScript.
         * 
         * @param {string} message - Le texte à afficher.
         * @param {('confirm'|'errors')} type - Le type d'alerte (succès ou erreur).
         */
        function renderAlertJS(message, type = 'errors') {
            const icons = { 
                'errors': '💥', 
                'confirm': '✅' 
            };
            const colors = { 
                'errors': 'w3-red', 
                'confirm': 'w3-green' 
            };

            const html = `
            <div class='flashMessages w3-panel ${colors[type]} w3-card-4 w3-display-container w3-round' data-type='${type}'>
                <div style='padding-right: 30px;'>
                    <span style='font-size: 18px; margin-right: 8px;'>${icons[type]}</span>
                    <span>${message}</span>
                </div>
                <span class='w3-button w3-display-topright w3-transparent w3-hover-none' 
                        onclick='closeNotif(this)' style='padding: 8px 12px;'>&times;</span>
            </div>`;

            // création du conteneur 
            let container = document.querySelector('.flashMessages-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'flashMessages-container';
                document.body.appendChild(container);
            }

            // équivalent d'un "echo" : 
            container.insertAdjacentHTML('beforeend', html);
            setupAutoClose();
        }
    </script>
    <?php
}

/**
 * Affiche tous les messages flash stockés dans la session.
 */
function displayFlashMessages() {
    // On charge les scripts JS systématiquement pour que renderAlertJS soit toujours disponible
    renderFlashScripts();

    if (isset($_SESSION['mesgs']) && !empty($_SESSION['mesgs'])) {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', setupAutoClose);
            setTimeout(setupAutoClose, 300);
        </script>
        <?php
        echo '<div class="flashMessages-container">';
        foreach ($_SESSION['mesgs'] as $type => $messages) {
            foreach ((array)$messages as $msg) {
                renderAlertPHP($msg, $type);
            }
        }
        echo '</div>';
        
        // Nettoyage de la session après affichage
        unset($_SESSION['mesgs']); 
    }
}

/**
 * Génère le code HTML pour une alerte individuelle (PHP).
 */
function renderAlertPHP($message, $type = 'errors') {
    $icons = ['errors'  => '💥', 'confirm' => '✅'];
    $colors = ['errors'  => 'w3-red', 'confirm' => 'w3-green'];
    
    echo "
    <div class='flashMessages w3-panel {$colors[$type]} w3-card-4 w3-display-container w3-round' data-type='{$type}'>
        <div style='padding-right: 30px;'>
            <span style='font-size: 18px; margin-right: 8px;'>{$icons[$type]}</span>
            <span>" . htmlspecialchars($message) . "</span>
        </div>
        <span class='w3-button w3-display-topright w3-transparent w3-hover-none' 
                onclick='closeNotif(this)' style='padding: 8px 12px;'>&times;</span>
    </div>";
}
